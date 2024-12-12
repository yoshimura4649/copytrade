<?php

namespace App\Http\Controllers\Admin\Standard;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class ModeratorController extends BaseController
{
  public function getList()
  {
    if (Request::ajax()) {
      $input = Request::query();

      $result = DB::table('admins')
        ->where('id', '!=', 1);

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

    return view('admin.standard.moderator.list', ['title' => '管理者管理 | 一覧']);
  }

  public function search($input, $query)
  {
    if ($input['search']['value']) {
      $query
        ->where(function ($query) use ($input) {
          $query->where('name', 'LIKE', '%' . $input['search']['value'] . '%')
            ->orWhere('email', 'LIKE', '%' . $input['search']['value'] . '%');
        });
    }

    return $query;
  }

  public function getDetail($id = null)
  {
    $detail = ['id' => '', 'name' => '', 'email' => '', 'group' => ''];
    if (is_numeric($id)) {
      $detail = DB::table('admins')
        ->select(array_keys($detail))
        ->where('id', '=', $id)
        ->first();
    }

    return view('admin.standard.moderator.detail', ['title' => '管理者管理 | 詳細', 'detail' => $detail]);
  }

  public function postDetail($id = null)
  {
    $input = Request::post();
    $errors = [];

    try {
      DB::beginTransaction();

      if (!is_numeric($id)) {
        $validator = Validator::make($input, [
          'email' => ['required', 'unique:admins'],
        ]);

        if ($validator->fails()) {
          throw new Exception('このメールアドレスは利用できません。', 1);
        }

        DB::table('admins')->insert([
          'name' => $input['name'],
          'email' => $input['email'],
          'group' => min($this->admin['group'], $input['group']),
          'password' => Hash::make($input['password'] ?: Str::random(16)),
        ]);
      } else {
        $validator = Validator::make($input, [
          'email' => ['required', Rule::unique('admins')->ignore($id)],
        ]);

        if ($validator->fails()) {
          throw new Exception('このメールアドレスは利用できません。', 1);
        }

        $update = [
          'name' => $input['name'],
          'email' => $input['email'],
          'group' => min($this->admin['group'], $input['group']),
        ];

        if ($input['password']) {
          $update['password'] = Hash::make($input['password']);
        }

        DB::table('admins')
          ->where('id', '=', $id)
          ->update($update);
      }

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      $errors = ['email' => 'このメールアドレスは利用できません。'];
    }

    return response()->json([
      'errors' => $errors,
    ]);
  }

  public function deleteDetail($id)
  {
    $errors = [];

    try {
      DB::beginTransaction();

      DB::table('admins')
        ->where('id', '=', $id)
        ->where('group', '<=', $this->admin['group'])
        ->delete();

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      Log::error($e->getMessage());
    }

    return response()->json(['errors' => $errors]);
  }
}
