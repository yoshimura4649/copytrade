<?php

namespace App\Http\Controllers\Admin\Standard;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class OrderController extends BaseController
{
  public function getList()
  {
    if (Request::ajax()) {
      $input = Request::query();

      $result = DB::table('orders')
        ->select(['orders.*', 'users.name as user_name'])
        ->leftJoin('users', 'users.id', '=', 'orders.user_id')
        ->where('users.deleted_at', '=', null);

      // Search
      $this->search($input, $result);

      // Order
      $this->order($input, $result);

      return response()->json([
        'draw' => $input['draw'],
        'recordsFiltered' => $result->count(),
        'data' => $result
          ->offset($input['start'])
          ->limit(min($input['length'], 5000))
          ->orderBy('id', 'DESC')
          ->get(),
      ]);
    }

    return view('admin.standard.order.list', ['title' => '発注管理 | 一覧']);
  }

  public function search($input, $query)
  {
    if (!empty($input['filter'])) {
      foreach ($input['filter'] as $key => $value) {
        if ($value) {
          switch ($key) {
            case 'symbol':
              $query->where('orders.' . $key, 'LIKE', '%' . $value . '%');
              break;
          }
        }
      }
    } elseif ($input['search']['value']) {
      $query
        ->where(function ($query) use ($input) {
          $query->where('orders.symbol', 'LIKE', '%' . $input['search']['value'] . '%')
            ->orWhere('orders.price', 'LIKE', '%' . $input['search']['value'] . '%');
        });
    }

    return $query;
  }

  public function getDetail($id)
  {
    $detail = [
      'id' => '', 'user_id' => '', 'order_number' => '', 'order_status' => '', 'side'=>'', 'symbol'=>'',
      'exchange' => '', 'price' => '', 'quantity' => '', 'front_order_type' => '',
    ];

    $orderDetail = [];
    $errors = '';
    if (is_numeric($id)) {
      $detail = DB::table('orders')
        ->select(array_keys($detail))
        ->where('id', '=', $id)
        ->first();

      if (isset($detail) && $detail['user_id'] && $detail['order_number']) {
        $user = DB::table('users')
          ->where('id', '=', $detail['user_id'])
          ->first();

        $response = Http::withHeaders([
          'Content-Type' => 'application/json',
          'Host' => 'localhost',
          'X-API-KEY' => $user['token'],
        ])->get($user['app_api_url'] . '/kabusapi/orders?id=' . $detail['order_number']);

        if ($response && $response->successful()) {
          $orderDetail = ($response && $response->successful()) ? $response->json()[0] : [];
        } else {
          $errors = $response->json()['Message'];
        }
      }
    }

    return view('admin.standard.order.detail', ['title' => '発注管理 | 詳細', 'detail' => $detail, 'orderDetail' => $orderDetail, 'errors' => $errors]);
  }
}
