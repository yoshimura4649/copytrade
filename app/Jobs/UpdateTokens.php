<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateTokens implements ShouldQueue
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
      // Retrieve all users
      $users = DB::table('users')->get();

      // Iterate over each user to send POST requests and update tokens
      foreach ($users as $user) {
        $response = Http::withHeaders([
          'Content-Type' => 'application/json',
          'Host' => 'localhost',
        ])->post($user['app_api_url'] . '/kabusapi/token', [
          'APIPassword' => $user['api_password'],
        ]);

        // Check if the response was successful
        if ($response->successful()) {
          $token = $response->json('Token'); // Assuming the response contains a 'Token' field
          // Update the token in the database for the current user
          DB::table('users')
            ->where('id', '=', $user['id'])
            ->update(['token' => $token]);
        } else {
          // Log error for failed requests
          Log::error('Failed to retrieve token for user ID: ' . $user['id'], [
            'response' => $response->body(),
          ]);
        }
      }
    } catch (Exception $e) {
      // Log the error
      Log::error('UpdateTokens Error: ' . $e->getMessage());
    }
  }

  /**
   * Handle a job failure.
   */
  public function failed($exception): void
  {
    // インポート中にエラーがあった場合
    Log::error('UpdateTokens failed');
  }
}
