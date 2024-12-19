<?php

namespace App\Jobs;

use App\Enums\TransactionStatus;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TurnOffTrade implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   */
  public function __construct()
  {
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    try {
      DB::beginTransaction();

      // Automatically turns off once a month
      DB::table('users')
        ->where('transaction_status', TransactionStatus::Active)
        ->where('transaction_updated_at', '<', Carbon::now()->subMonth())
        ->update(['transaction_status' => TransactionStatus::Inactive]);

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();

      // Log the error
      Log::error('TurnOffTrade Error: ' . $e->getMessage());
    }
  }

  /**
   * Handle a job failure.
   */
  public function failed($exception): void
  {
    // インポート中にエラーがあった場合
    Log::error('TurnOffTrade failed');
  }
}
