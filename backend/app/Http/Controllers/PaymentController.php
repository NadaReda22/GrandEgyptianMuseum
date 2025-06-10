<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{
    // 1. Receive ticket info + payment method (except card details)

        public function createOrder(Request $request)
        {
            $request->validate([
                'booking_id' => 'required|integer|exists:bookings,id',
                'payment_type' => 'required|in:visa,mastercard,fawry,not selected',
            ]);

            try {
                $booking = Booking::find($request->booking_id);

                if (!$booking) {
                    return response()->json(['message' => 'Booking not found.'], 404);
                }

                $booking->payment_type = $request->payment_type;
                $booking->save();

                $paymentUrl = $this->paymobCreatePaymentKey($booking);

                return response()->json([
                    'payment_url' => $paymentUrl,
                    'order_id' => $booking->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Error creating payment key', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->json([
                    'message' => 'Payment service unavailable. Please try again later.'
                ], 500);
            }
        }



    private function getPaymobAuthToken($apiKey)
    {
        return Cache::remember('paymob_auth_token', now()->addMinutes(20), function () use ($apiKey) {
            $retries = 3;
            $delayMs = 1000;

            for ($i = 0; $i < $retries; $i++) {
                $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
                    'api_key' => $apiKey,
                ]);

                if ($response->successful()) {
                    return $response->json()['token'];
                }

                if ($response->status() == 429 && $i < $retries - 1) {
                    usleep($delayMs * 1000); // Wait before retry
                    continue;
                }

                Log::error('Paymob Auth failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                abort(500, 'Payment service unavailable (auth)');
            }
        });
    }

    public function paymobCreatePaymentKey(Booking $order)
    {
        $apiKey = config('services.paymob.api_key');
        $integrationId = config('services.paymob.integration_id');
        $iframeId = config('services.paymob.iframe_id');

        $authToken = $this->getPaymobAuthToken($apiKey);

        $amountCents = intval($order->amount * 100);

        $orderResponse = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            "auth_token" => $authToken,
            "amount_cents" => $amountCents,
            "currency" => "EGP",
            "delivery_needed" => false,
            "merchant_order_id" => uniqid($order->id . '_'),
            "items" => []
        ]);

        if (!$orderResponse->successful()) {
            return response()->json([
                'message' => 'Order creation failed',
                'errors' => $orderResponse->json()
            ], 500);
        }

        $paymobOrderId = $orderResponse->json()['id'];

        $paymentKeyResponse = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
            "auth_token" => $authToken,
            "amount_cents" => $amountCents,
            "order_id" => $paymobOrderId,
            "expiration" => 3600,
            "billing_data" => [
                "email" => "user@example.com",
                "first_name" => "Guest",
                "last_name" => "NA",
                "phone_number" => "01090627281",
                "city" => "Cairo",
                "country" => "EG",
                "apartment" => "mnm",
                "floor" => "mn",
                "street" => "oi",
                "building" => "mn,",
                "shipping_method" => "NA",
                "postal_code" => "NA",
                "state" => "NA"
            ],
            "currency" => "EGP",
            "integration_id" => $integrationId,
        ]);

        if (!$paymentKeyResponse->successful()) {
            return response()->json([
                'message' => 'Payment key creation failed',
                'errors' => $paymentKeyResponse->json()
            ], 500);
        }

        $paymentToken = $paymentKeyResponse->json()['token'];

        return "https://accept.paymob.com/api/acceptance/iframes/{$iframeId}?payment_token={$paymentToken}";
    }

    public function handlePaymobWebhook(Request $request)
    {
        $data = $request->all();
        Log::info('Paymob webhook received', $data);

        $orderId = $data['obj']['merchant_order_id'] ?? null;
        $success = $data['obj']['success'] ?? false;
        $amountCents = $data['obj']['amount_cents'] ?? 0;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        $order = Booking::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($success && $amountCents == intval($order->amount * 100)) {
            $order->status = 'paid';
        } else {
            $order->status = 'failed';
        }

        $order->save();

        return response()->json(['message' => 'Webhook processed']);
    }

    private function calculateAmount($eventId)
    {
        // You can replace this logic with actual event-based pricing
        return 200; // Fixed amount for example
    }
}
