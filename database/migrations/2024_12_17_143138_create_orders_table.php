<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->integer('user_id')->default(0)->comment('会員ID');
      $table->string('order_number')->nullable()->comment('注文番号');
      $table->tinyInteger('order_status')->default(0)->comment('ステータス');
      $table->tinyInteger('front_order_type')->comment('執行条件');
      $table->tinyInteger('side')->comment('売買区分');
      $table->string('symbol')->comment('銘柄コード');
      $table->tinyInteger('exchange')->comment('市場コード');
      $table->bigInteger('price')->comment('値段');
      $table->bigInteger('quantity')->comment('発注数量');
      $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
