<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->string('kabu_id')->nullable()->after('tel');
      $table->string('kabu_password')->nullable()->after('kabu_id');
      $table->tinyInteger('transaction_status')->default(0)->after('kabu_password');
      $table->timestamp('transaction_updated_at')->nullable()->after('transaction_status');
      $table->dropColumn('zip');
      $table->dropColumn('prefecture');
      $table->dropColumn('address');
      $table->dropColumn('address_other');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('kabu_id');
      $table->dropColumn('kabu_password');
      $table->dropColumn('transaction_status');
      $table->dropColumn('transaction_updated_at');
      $table->string('zip', 8)->nullable()->after('tel');
      $table->tinyInteger('prefecture')->default(0)->after('zip');
      $table->string('address', 1024)->nullable()->after('prefecture');
      $table->string('address_other', 1024)->nullable()->after('address');
    });
  }
};
