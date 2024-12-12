<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
  protected $message = [
    200 => 'Successed',
    201 => 'Created',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    410 => 'Expired',
    412 => 'Verify code required',
    417 => 'Point ballance is not enough',
    428 => 'Precondition required',
    432 => 'Email address already exist',
    433 => 'Phone number already exist',
    500 => 'Internal Server Error',
    503 => 'Service Unavailable'
  ];

  public function responseSuccess($data = null, $other = [])
  {
    return response(
      [
        'status_code' => 200,
        'message' => 'Successed',
        'data' => $data,
      ] + $other,
      200
    );
  }

  public function responseError($status)
  {
    return response(
      [
        'status_code' => $status,
        'message' => $this->message[$status],
      ],
      $status
    );
  }
}