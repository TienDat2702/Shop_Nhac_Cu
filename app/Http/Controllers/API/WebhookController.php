<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info('Received request: ', $request->all()); // Log dữ liệu nhận từ Dialogflow

        $intent = $request->input('queryResult.intent.displayName');
        $parameters = $request->input('queryResult.parameters');

        switch ($intent) {
            case 'GetProductPrice':
                $productName = $parameters['productName'];

                Log::info("Calling API to get price for product: $productName");

                // Gọi API lấy giá sản phẩm
                try {
                    $response = Http::timeout(1200)->get('http://127.0.0.1:8000/api/products/price', [
                        'product_name' => $productName,
                    ]);
                
                    if ($response->successful()) {
                        $price = $response->json()['price'] ?? 'Không xác định';
                
                        return response()->json([
                            'fulfillmentText' => "Giá của sản phẩm $productName là: $price VNĐ."
                        ]);
                    }
                
                    Log::error("API call failed with status: " . $response->status());
                } catch (\Exception $e) {
                    Log::error("Error occurred while calling API: " . $e->getMessage());
                }

                Log::error("Failed to fetch product price. Response: ", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'fulfillmentText' => "Không thể lấy thông tin giá sản phẩm $productName."
                ]);

            default:
                Log::warning("Unhandled intent: $intent");

                return response()->json([
                    'fulfillmentText' => "Tôi không hiểu yêu cầu của bạn. Vui lòng thử lại!"
                ]);
        }
    }
}
