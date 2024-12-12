<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class InquiryController extends BaseController
{
  public function getList()
  {
    if (Request::ajax()) {
      $input = Request::query();

      $result = DB::table('inquiries');

      // Search
      $this->search($input, $result);

      return response()->json([
        'draw' => $input['draw'],
        'recordsFiltered' => $result->count(),
        'data' => $result
          ->offset($input['start'])
          ->limit(min($input['length'], 5000))
          ->get()
      ]);
    }

    return view('admin.email.inquiry.list', ['title' => 'お問い合わせ | 一覧']);
  }

  public function search($input, $query)
  {
    if ($input['search']['value']) {
      $query
        ->where(function ($query) use ($input) {
          $query->where('memo', 'LIKE', '%' . $input['search']['value'] . '%')
            ->orWhere('title', 'LIKE', '%' . $input['search']['value'] . '%')
            ->orWhere('content', 'LIKE', '%' . $input['search']['value'] . '%');
        });
    }

    return $query;
  }

  public function getDetail($id = null)
  {
    $detail = [];
    if (is_numeric($id)) {
      $detail = DB::table('inquiries')
        ->where('inquiries.id', '=', $id)
        ->first();
    }

    return view('admin.email.inquiry.detail', ['title' => 'お問い合わせ | 詳細', 'detail' => $detail]);
  }

  public function postDetail($id)
  {
    $input = Request::post();
    $errors = [];

    try {
      DB::beginTransaction();

      DB::table('inquiries')
        ->where('id', '=', $id)
        ->update($input);

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      Log::error($e->getMessage());
    }

    return response()->json([
      'errors' => $errors,
    ]);
  }
}
