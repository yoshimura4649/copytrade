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

class UserController extends BaseController
{
  public function getList()
  {
    if (Request::ajax()) {
      $input = Request::query();

      $result = DB::table('users');

      // Search
      $this->search($input, $result);

      return response()->json([
        'draw' => $input['draw'],
        'recordsFiltered' => $result->count(),
        'data' => $result
          ->offset($input['start'])
          ->limit(min($input['length'], 5000))
          ->orderBy('id', 'DESC')
          ->get()
      ]);
    }

    return view('admin.standard.user.list', ['title' => '会員管理 | 一覧']);
  }

  public function search($input, $query)
  {
    if (!empty($input['filter'])) {
      foreach ($input['filter'] as $key => $value) {
        if ($value) {
          switch ($key) {
            case 'name':
            case 'tel':
            case 'email':
              $query->where($key, 'LIKE', '%' . $value . '%');
              break;
          }
        }
      }
    } elseif ($input['search']['value']) {
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
    $detail = [
      'id' => '', 'name' => '', 'email' => '', 'tel' => '',
      'zip' => '', 'prefecture' => '', 'address' => '', 'address_other' => '',
    ];

    if (is_numeric($id)) {
      $detail = DB::table('users')
        ->select(array_keys($detail))
        ->where('id', '=', $id)
        ->first();
    }

    return view('admin.standard.user.detail', ['title' => '会員管理 | 詳細', 'detail' => $detail]);
  }

  public function postDetail($id = null)
  {
    $input = Request::post();
    $errors = [];

    try {
      DB::beginTransaction();

      if (!is_numeric($id)) {
        $validator = Validator::make($input, [
          'email' => ['required', 'unique:users'],
        ]);

        if ($validator->fails()) {
          throw new Exception('このメールアドレスは利用できません。', 1);
        }

        DB::table('users')->insert(['password' => Hash::make($input['password'] ?: Str::random(16))] + $input);
      } else {
        $validator = Validator::make($input, [
          'email' => ['required', Rule::unique('users')->ignore($id)],
        ]);

        if ($validator->fails()) {
          throw new Exception('このメールアドレスは利用できません。', 1);
        }

        if ($input['password']) {
          $input['password'] = Hash::make($input['password']);
        } else {
          unset($input['password']);
        }

        DB::table('users')
          ->where('id', '=', $id)
          ->update($input);
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

      DB::table('users')
        ->where('id', '=', $id)
        ->delete();

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
      Log::error($e->getMessage());
    }

    return response()->json(['errors' => $errors]);
  }
}
