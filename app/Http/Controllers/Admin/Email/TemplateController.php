<?php

namespace App\Http\Controllers\Admin\Email;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TemplateController extends BaseController
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function getList()
  {
    if (Request::ajax()) {
      $input = Request::query();
      $result = DB::table('mail_templates');

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

    return view('admin.email.template.list', ['title' => 'メールテンプレート | 一覧']);
  }

  public function search($input, $query)
  {
    if ($input['search']['value']) {
      $query->where('title', 'LIKE', '%' . $input['search']['value'] . '%');
    }

    return $query;
  }

  public function getDetail($id = null)
  {
    $detail = ['id' => '', 'title' => '', 'subject' => '', 'body' => '', 'admin_subject' => '', 'admin_body' => ''];
    if (is_numeric($id)) {
      $detail = DB::table('mail_templates')
        ->select(array_keys($detail))
        ->where('id', '=', $id)
        ->first();
    }

    return view('admin.email.template.detail', ['title' => 'メールテンプレート | 詳細', 'detail' => $detail]);
  }

  public function postDetail($id = null)
  {
    $input = Request::post();
    $errors = [];
    try {
      DB::beginTransaction();

      if (is_numeric($id)) {
        DB::table('mail_templates')
          ->where('id', '=', $id)
          ->update($input);
      } else {
        DB::table('mail_templates')
          ->insert($input);
      }

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
    }

    return response()->json([
      'errors' => $errors,
    ]);
  }

  public function deleteDetail($id)
  {
    try {
      DB::beginTransaction();

      DB::table('mail_templates')
        ->where('id', '=', $id)
        ->delete();

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      Log::error($e->getMessage());
    }
  }
}
