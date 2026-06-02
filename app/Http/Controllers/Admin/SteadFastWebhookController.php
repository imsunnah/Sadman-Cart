<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SteadFastWebhookController extends Controller
{
    public function handleSteadFastWebhook(Request $request)
    {
        $payload = $request->all();
        $token = $request->header('Authorization');

        // Check if Bearer token is valid
        $configuredToken = env('STEADFAST_BEARER_TOKEN');
        if ($token !== 'Bearer ' . $configuredToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $this->validatePayload($payload);
            $this->processPayload($payload);

            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            Log::error('Steadfast Webhook Error: ' . $th->getMessage());
            return response()->json(['error' => $th->getMessage()], 400);
        }
    }

    private function validatePayload($payload)
    {
        $properties = [
            'consignment_id',
            'invoice',
            'status',
            'cod_amount',
            'updated_at',
        ];
        $missingProperties = array_diff($properties, array_keys($payload));
        if ($missingProperties) {
            abort(400, 'Missing required properties: ' . implode(', ', $missingProperties));
        }
    }

    private function processPayload($payload)
    {
        $invoice = $payload['invoice'];
        $orderStatus = $payload['status'];

        // Find the order by invoice (order id)
        $order = Order::find($invoice);
        
        if ($order) {
            $order->update([
                'courier_status' => $orderStatus,
                'courier_response' => json_encode($payload) // Update the response log too
            ]);
            
            Log::info("Steadfast Webhook: Order #{$invoice} status updated to {$orderStatus}");
        }
    }
}
