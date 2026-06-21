<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Combo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use SteadFast\SteadFastCourierLaravelPackage\Facades\SteadfastCourier;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        if ($status === null) {
            $status = 'pending';
        }
        $categoryId = $request->query('category_id');
        $productId = $request->query('product_id');
        $comboId = $request->query('combo_id');
        
        $query = Order::with(['items.product', 'items.combo'])->has('items');
        
        if ($status && $status !== 'all' && in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($categoryId && $categoryId !== 'all') {
            $query->whereHas('items.product', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        if ($productId && $productId !== 'all') {
            $query->whereHas('items', function($q) use ($productId) {
                $q->where('product_id', $productId);
            });
        }

        if ($comboId && $comboId !== 'all') {
            $query->whereHas('items', function($q) use ($comboId) {
                $q->where('combo_id', $comboId);
            });
        }
        
        if ($status === 'all') {
            $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 WHEN status = 'processing' THEN 1 WHEN status = 'completed' THEN 2 ELSE 3 END")
                  ->orderBy('created_at', 'desc');
        } else {
            $query->latest();
        }

        $orders = $query->paginate(10)->withQueryString();
        $categories = \App\Models\Category::all();
        $products = \App\Models\Product::all();
        $combos = \App\Models\Combo::all();
        
        $overduePendingCount = Order::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(2))
            ->count();
        
        return Inertia::render('Admin/Order/Index', [
            'orders' => $orders,
            'categories' => $categories,
            'products' => $products,
            'combos' => $combos,
            'currentStatus' => $status,
            'currentCategoryId' => $categoryId ?? 'all',
            'currentProductId' => $productId ?? 'all',
            'currentComboId' => $comboId ?? 'all',
            'overduePendingCount' => $overduePendingCount,
        ]);
    }

    public function toggleActive(Order $order)
    {
        $order->update(['is_active' => !$order->is_active]);
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function show(Order $order)
    {
        return Inertia::render('Admin/Order/Show', [
            'order' => $order->load(['items.product', 'items.combo.products', 'messages' => function($q) {
                $q->with(['user', 'admin'])->oldest();
            }])
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,processing,completed,cancelled',
            'delivery_charge' => 'nullable|numeric|min:0',
            'delivery_location' => 'nullable|string|in:Inside Dhaka,Outside Dhaka',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
        ]);

        if ($request->has('delivery_location')) {
            $settingKey = $validated['delivery_location'] === 'Inside Dhaka' 
                ? 'delivery_charge_inside_dhaka' 
                : 'delivery_charge_outside_dhaka';
            
            $charge = \App\Models\Setting::where('key', $settingKey)->first()->value ?? ($validated['delivery_location'] === 'Inside Dhaka' ? 60 : 120);
            
            $order->delivery_location = $validated['delivery_location'];
            $order->delivery_charge = $charge;
            
            $itemsTotal = $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $order->total_amount = $itemsTotal + $charge;
        }

        if ($request->has('shipping_address')) {
            $order->shipping_address = $validated['shipping_address'];
        }

        if ($request->has('customer_name')) {
            $order->customer_name = $validated['customer_name'];
        }

        if ($request->has('customer_phone')) {
            $order->customer_phone = $validated['customer_phone'];
        }

        if ($request->has('total_amount')) {
            $order->total_amount = $validated['total_amount'];
        }

        if ($request->has('delivery_charge')) {
            $itemsTotal = $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $order->total_amount = $itemsTotal + $validated['delivery_charge'];
            $order->delivery_charge = $validated['delivery_charge'];
        }

        if ($request->has('status')) {
            $oldStatus = $order->status;
            $newStatus = $validated['status'];
            
            // Fix: Allow status change to pending, processing, completed or cancelled even if couriered
            if (!empty($order->courier_tracking_code) && !in_array($newStatus, ['pending', 'processing', 'completed', 'cancelled'])) {
                return redirect()->back()->with('error', 'Couriered orders cannot change status unless setting to completed, cancelled, pending or keeping it in processing.');
            }

            $order->status = $newStatus;

            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                // Restore stock
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $product = $item->product;
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    } elseif ($item->combo_id) {
                        foreach ($item->combo->products as $p) {
                            $p->increment('stock', $item->quantity);
                        }
                    }
                }
            } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                // Deduct stock if it was cancelled previously
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $product = $item->product;
                        if ($product) {
                            $product->decrement('stock', $item->quantity);
                        }
                    } elseif ($item->combo_id) {
                        foreach ($item->combo->products as $p) {
                            $p->decrement('stock', $item->quantity);
                        }
                    }
                }
            }
        }

        $order->save();

        // Send Email to customer
        if ($order->customer_email) {
            try {
                \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderStatusUpdated($order));
            } catch (\Exception $e) {
                // Ignore mail errors
            }
        }

        return redirect()->route('admin.orders.index', ['status' => $request->query('status', $order->status)])
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order, Request $request)
    {
        $status = $request->query('status', 'all');
        $order->delete();
        return redirect()->route('admin.orders.index', ['status' => $status])
            ->with('success', 'Order deleted successfully.');
    }

    public function create()
    {
        return Inertia::render('Admin/Order/Create', [
            'products' => Product::where('is_active', true)->get(),
            'combos' => Combo::where('is_active', true)->with('products')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'district' => 'required|string',
            'upazila' => 'required|string',
            'delivery_location' => 'required|string',
            'delivery_charge' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|string|in:product,combo',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = $validated['delivery_charge'];
        
        $order = Order::create([
            'id' => Order::generateUniqueId(),
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'shipping_address' => $validated['shipping_address'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'delivery_location' => $validated['delivery_location'],
            'delivery_charge' => $validated['delivery_charge'],
            'status' => $validated['status'],
            'total_amount' => 0,
            'payment_method' => 'Cash on Delivery',
        ]);

        foreach ($validated['items'] as $itemData) {
            if ($itemData['type'] === 'product') {
                $product = Product::findOrFail($itemData['id']);
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->discounted_price,
                    'buying_price' => $product->buying_price,
                    'package_cost' => $product->package_cost,
                    'quantity' => $itemData['quantity'],
                ]);
                $totalAmount += $product->discounted_price * $itemData['quantity'];
                if ($validated['status'] !== 'cancelled') {
                    $product->decrement('stock', $itemData['quantity']);
                }
            } else {
                $combo = Combo::with('products')->findOrFail($itemData['id']);
                $order->items()->create([
                    'combo_id' => $combo->id,
                    'product_name' => $combo->name . ' (Bundle)',
                    'price' => $combo->price,
                    'buying_price' => $combo->products->sum('buying_price'),
                    'package_cost' => $combo->products->sum('package_cost'),
                    'quantity' => $itemData['quantity'],
                ]);
                $totalAmount += $combo->price * $itemData['quantity'];
                if ($validated['status'] !== 'cancelled') {
                    foreach ($combo->products as $p) {
                        $p->decrement('stock', $itemData['quantity']);
                    }
                }
            }
        }

        $order->update(['total_amount' => $totalAmount]);

        return redirect()->route('admin.orders.index')->with('success', 'Order created manually.');
    }

    public function sendToCourier(Order $order)
    {
        $result = $this->executeCourierSend($order);
        if ($result['status'] === 'success') {
            return redirect()->back()->with('success', $result['message']);
        }
        return redirect()->back()->with('error', $result['message']);
    }

    private function executeCourierSend(Order $order)
    {
        // 1. Basic Validation
        if (empty($order->customer_phone) || empty($order->shipping_address) || empty($order->customer_name)) {
            return ['status' => 'error', 'message' => 'Order must have a customer name, phone, and shipping address.'];
        }

        // 2. Sanitize phone number
        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        
        if (str_starts_with($phone, '880')) {
            $phone = substr($phone, 2);
        }

        if (strlen($phone) < 6) {
            return ['status' => 'error', 'message' => 'Steadfast requires a valid phone number.'];
        }

        $data = [
            'invoice' => (string) $order->id,
            'recipient_name' => $order->customer_name,
            'recipient_phone' => $phone,
            'recipient_address' => $order->shipping_address,
            'cod_amount' => (int) $order->total_amount,
            'note' => $order->customer_remarks,
        ];
        
        try {
            $response = SteadfastCourier::placeOrder($data);
            $result = json_decode(json_encode($response));

            $order->update([
                'courier_response' => json_encode($response),
            ]);

            if (isset($result->status) && $result->status == 200) {
                $order->update([
                    'status' => 'completed',
                    'courier_name' => 'Steadfast',
                    'courier_tracking_code' => $result->consignment->tracking_code,
                    'courier_consignment_id' => $result->consignment->consignment_id,
                    'courier_status' => $result->consignment->status,
                ]);

                return ['status' => 'success', 'message' => 'Order sent to Steadfast successfully. Tracking: ' . $result->consignment->tracking_code];
            }

            $errorMsg = $result->message ?? 'Failed to send order to Steadfast.';
            if (isset($result->errors)) {
                $errorMsg .= ' ' . collect($result->errors)->flatten()->implode(' ');
            }

            return ['status' => 'error', 'message' => $errorMsg];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Steadfast API Exception: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Connection Error: Could not reach Steadfast.'];
        }
    }

    public function bulkSendToCourier(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);

        $orders = Order::whereIn('id', $request->order_ids)->get();
        $data = [];

        foreach ($orders as $order) {
            // Sanitize phone number: remove any non-digit characters
            $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
            
            // If it starts with 880, remove the 88 (Steadfast prefers 01...)
            if (str_starts_with($phone, '880')) {
                $phone = substr($phone, 2);
            }

            $data[] = [
                'invoice' => (string) $order->id,
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $phone,
                'recipient_address' => $order->shipping_address,
                'cod_amount' => (int) $order->total_amount,
                'note' => $order->customer_remarks,
            ];
        }

        $results = SteadfastCourier::bulkCreateOrders($data);

        if ($results && is_array($results)) {
            $successCount = 0;
            foreach ($results as $res) {
                if ($res['status'] === 'success') {
                    $order = Order::find($res['invoice']);
                    if ($order) {
                        $order->update([
                            'status' => 'completed',
                            'courier_name' => 'Steadfast',
                            'courier_tracking_code' => $res['tracking_code'],
                            'courier_consignment_id' => $res['consignment_id'],
                            'courier_status' => 'success',
                            'courier_response' => json_encode($res),
                        ]);
                        $successCount++;
                    }
                }
            }
            return redirect()->back()->with('success', "$successCount orders sent to Steadfast successfully.");
        }

        return redirect()->back()->with('error', 'Failed to send bulk orders to Steadfast.');
    }

    public function courierOrders()
    {
        $orders = Order::whereNotNull('courier_tracking_code')
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Order/CourierIndex', [
            'orders' => $orders
        ]);
    }

    public function syncCourierStatus(Order $order)
    {
        if (empty($order->courier_tracking_code)) {
            return redirect()->back()->with('error', 'This order has no courier tracking code.');
        }

        try {
            $response = SteadfastCourier::checkDeliveryStatusByInvoiceId($order->id);
            
            if (isset($response['status']) && $response['status'] == 200) {
                $courierStatus = $response['delivery_status'];
                $this->updateOrderStatusFromCourier($order, $courierStatus, $response);
                return redirect()->back()->with('success', "Order status synced: " . ucfirst($courierStatus));
            }

            return redirect()->back()->with('error', 'Could not sync status from Steadfast: ' . ($response['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error syncing status: ' . $e->getMessage());
        }
    }

    public function getSteadfastBalance()
    {
        try {
            $response = SteadfastCourier::getCurrentBalance();
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    private function updateOrderStatusFromCourier(Order $order, string $courierStatus, array $fullResponse)
    {
        $oldStatus = $order->status;
        $newStatus = $order->status;

        // Map Steadfast status to local status
        // Mapping as per common Steadfast API statuses: delivered, cancelled, hold, partial_delivered, in_review, etc.
        switch ($courierStatus) {
            case 'delivered':
                $newStatus = 'completed';
                break;
            case 'cancelled':
                $newStatus = 'cancelled';
                break;
            // You can add more mappings if needed
        }

        $order->update([
            'courier_status' => $courierStatus,
            'courier_response' => json_encode($fullResponse),
            'status' => $newStatus
        ]);

        // Handle stock if status changed to cancelled
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $item->product?->increment('stock', $item->quantity);
                } elseif ($item->combo_id) {
                    foreach ($item->combo->products as $p) {
                        $p->increment('stock', $item->quantity);
                    }
                }
            }
        }
    }
}
