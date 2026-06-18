<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use SteadFast\SteadFastCourierLaravelPackage\Facades\SteadfastCourier;
use Illuminate\Support\Facades\Log;

class SyncCourierStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:sync-courier-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync courier status for orders that have trackable courier codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting courier status sync...');

        // Fetch orders that are pending or processing and have a courier tracking code
        $orders = Order::whereNotNull('courier_tracking_code')
            ->whereIn('status', ['pending', 'processing'])
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders found for synchronization.');
            return;
        }

        foreach ($orders as $order) {
            $this->syncOrder($order);
        }

        $this->info('Courier status sync completed.');
    }

    private function syncOrder(Order $order)
    {
        try {
            $response = SteadfastCourier::checkDeliveryStatusByInvoiceId($order->id);
            
            if (isset($response['status']) && $response['status'] == 200) {
                $courierStatus = $response['delivery_status'];
                
                $oldStatus = $order->status;
                $newStatus = $order->status;

                // Map Steadfast status to local status
                switch ($courierStatus) {
                    case 'delivered':
                        $newStatus = 'completed';
                        break;
                    case 'cancelled':
                        $newStatus = 'cancelled';
                        break;
                }

                $order->update([
                    'courier_status' => $courierStatus,
                    'courier_response' => json_encode($response),
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

                $this->info("Synced Order #{$order->id}: {$courierStatus}");
            } else {
                $this->warn("Failed to sync Order #{$order->id}: " . ($response['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            $this->error("Error syncing Order #{$order->id}: " . $e->getMessage());
            Log::error("Courier Sync Error for Order #{$order->id}: " . $e->getMessage());
        }
    }
}
