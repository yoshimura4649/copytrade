<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
  public function actionAccount(Request $request)
  {
    if ($request->isMethod('post')) {
      $isUserChange = $request->filled('change_user_password');
      $isKabuChange = $request->filled('change_kabu_password');

      $rules = [
        'tel' => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
        'kabu_id' => ['required', 'string'],
      ];

      if ($isUserChange) {
        $rules['password'] = ['required', 'regex:/^(?=.*[0-9])(?=.*[a-zA-Z])[0-9a-zA-Z]+$/', 'min:6', 'max:32', 'confirmed'];
      }

      if ($isKabuChange) {
        $rules['kabu_password'] = ['required', 'confirmed'];
      }

      $validator = Validator::make($request->post(), $rules);
      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
      }
      $validated = $validator->validated();

      $updateData = [
        'tel' => $validated['tel'],
        'kabu_id' => $validated['kabu_id'],
      ];

      if ($isUserChange) {
        $updateData['password'] = Hash::make($validated['password']);
      }

      if ($isKabuChange) {
        $updateData['kabu_password'] = Hash::make($validated['kabu_password']);
      }

      try {
        DB::transaction(function () use ($updateData) {
          DB::table('users')
            ->where('id', '=', Auth::id())
            ->whereNull('deleted_at')
            ->update($updateData);
        });

        return back()->withErrors('情報が正常に更新されました。');
      } catch (Exception $e) {
        Log::error($e->getMessage());

        return redirect()->back()->withErrors($e->getMessage());
      }
    }

    return view('frontend.account.index', ['title' => 'アカウント設定画面']);
  }
}
