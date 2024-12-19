<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
  public function getList()
  {
        //
  }

  public function getDetail()
  {
        //
  }

  public function postDetail(Request $request, string $id)
  {
        //
  }

  public function actionSetting(Request $request)
  {
    if ($request->isMethod('post')) {
      try {
        $updateData = [
          'transaction_status' => TransactionStatus::active,
          'transaction_updated_at' => Carbon::now('Asia/Tokyo')->toDateTimeString(),
        ];

        DB::transaction(function () use ($updateData) {
          DB::table('users')
            ->where('id', '=', Auth::id())
            ->whereNull('deleted_at')
            ->update($updateData);
        });

        return back()->withErrors('登録が成功しました！');
      } catch (Exception $e) {
        Log::error($e->getMessage());

        return redirect()->back()->withErrors($e->getMessage());
      }
    }

    return view('frontend.transaction.setting', ['title' => '売買設定画面']);
  }
}
