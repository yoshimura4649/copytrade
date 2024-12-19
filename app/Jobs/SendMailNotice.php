<?php

namespace App\Jobs;

use App\Enums\TransactionStatus;
use App\Services\EmailService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendMailNotice implements ShouldQueue
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
      // Fetch users whose transactions are still active and will expire in 7 days or 1 day
      $usersToNotify = DB::table('users')
        ->where('transaction_status', TransactionStatus::Active) // Only users with Active status
        ->where(function ($query) {
          $query->whereDate('transaction_updated_at', date('Y-m-d', strtotime('-7 days')))
            ->orWhereDate('transaction_updated_at', date('Y-m-d', strtotime('-1 days')));
        })
        ->get();

      // Send notification emails to each user
      foreach ($usersToNotify as $user) {
        EmailService::user($user, 5);
      }

      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();

      // Log the error
      Log::error('SendMailNotice Error: ' . $e->getMessage());
    }
  }

  /**
   * Handle a job failure.
   */
  public function failed($exception): void
  {
    // インポート中にエラーがあった場合
    Log::error('SendMailNotice failed');
  }
}
