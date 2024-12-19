<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SendUserTradeOrder implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected $items;

  /**
   * Create a new job instance.
   */
  public function __construct($items)
  {
    $this->items = $items;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    Log::info('Job started: Sending requests');

    if (!$this->items) {
      Log::error('Not Found!');

      return;
    }

    $users = User::get_app_api_url();

    $validated = $this->validateItems($this->items);
    if (!$validated) {
      Log::error('Validation failed for order data');

      return;
    }

    $orderPost = $this->buildOrderData($this->items);

    if (!$orderPost) {
      Log::error('Invalid order data');

      return;
    }

    try {
      // Use Http::pool to send concurrent requests
      $responses = Http::pool(function ($pool) use ($users, $orderPost) {
        foreach ($users as $user) {
          if (!isset($user['app_api_url'], $user['token'])) {
            Log::error('User missing API URL or token', ['userID' => $user['id']]);
            continue;
          }
          $pool
            ->withHeaders([
              'Content-Type' => 'application/json',
              'Host' => 'localhost',
              'X-API-KEY' => $user['token'],
            ])
            ->post($user['app_api_url'] . '/kabusapi/sendorder', $orderPost);
        }
      });

      // Process the responses
      foreach ($users as $index => $user) {
        $response = $responses[$index] ?? null;
        $responseBody = $response ? $response->json() : null;

        $ordersToInsert[] = [
          'user_id'           => $user['id'],
          'order_number'      => $responseBody['OrderId'] ?? null,
          'order_status'      => $response && $response->successful()
                                ? OrderStatus::Completed->value
                                : OrderStatus::Failed->value,
          'front_order_type'  => $orderPost['FrontOrderType'],
          'side'              => $orderPost['Side'],
          'symbol'            => $orderPost['Symbol'],
          'exchange'          => $orderPost['Exchange'],
          'price'             => $orderPost['Price'],
          'quantity'          => $orderPost['Qty'],
        ];

        if ($response && $response->successful()) {
          Log::info("Request to {$user['app_api_url']} successful.", ['response' => $responseBody]);
        } else {
          $errorMessage = $responseBody['Message'] ?? 'Unknown error';
          Log::error("Request to {$user['app_api_url']} failed.", ['status' => $response->status(), 'error' => $errorMessage]);
        }
      }

      if (!empty($ordersToInsert)) {
        DB::table('orders')->insert($ordersToInsert);
        Log::info('Orders successfully inserted into the database.');
      }
    } catch (Exception $e) {
      Log::error('An error occurred in SendCopyTradeRequests Job.', ['message' => $e->getMessage()]);
    }
  }

  /**
   * Validate order data parameters.
   *
   * @param array $items
   * @return bool
   */
  private function validateItems(array $items): bool
  {
    $validator = Validator::make($items, [
      'Future'          => 'required|numeric',
      'Symbol'          => 'required|string',
      'Exchange'        => 'required|numeric',
      'Side'            => 'required|string|in:1,2',
      'Qty'             => 'required|numeric|min:1',
      'FrontOrderType'  => 'required|numeric',
      'Price'           => 'required|numeric|min:0',
      'ExpireDay'       => 'required|numeric',
    ]);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $error) {
        Log::error("Validation Error: {$error}");
      }

      return false;
    }

    return true;
  }

  /**
   * Build order data based on item parameters.
   *
   * @param array $item
   * @return array|null
   */
  private function buildOrderData(array $item)
  {
    $baseOrder = [
      'Symbol'          => $item['Symbol'],                     // 銘柄コード
      'Exchange'        => 1,                                   // 市場コード : 東証
      'SecurityType'    => 1,                                   // 商品種別 : 株式
      'CashMargin'      => 1,                                   // 信用区分 : 現物
      'AccountType'     => 4,                                   // 口座種別 : 特定
      'Qty'             => $item['Qty'],                        // 注文数量
      'FrontOrderType'  => $item['FrontOrderType'],             // 執行条件
      'Price'           => $item['Price'],                      // 注文価格
      'ExpireDay'       => $item['ExpireDay'],                  // 注文有効期限
    ];

    $baseOrder['Side'] = $item['Side'] == 1 ? '1' : '2';        // 売買区分 : 売 / 買
    $baseOrder['DelivType'] = $item['Side'] == 1 ? 0 : 2;       // 受渡区分 : 指定なし / お預り金
    $baseOrder['FundType'] = $item['Side'] == 1 ? '  ' : 'AA';  // 資産区分（預り区分）: 現物売の場合 / 信用代用

    return $baseOrder;
  }
}
