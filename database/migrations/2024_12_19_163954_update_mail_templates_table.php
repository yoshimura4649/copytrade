<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    DB::statement("INSERT INTO `mail_templates` (`id`, `title`, `subject`, `body`, `admin_subject`, `admin_body`, `created_at`, `updated_at`) VALUES ('5', 'お客様の取引がまもなく期限切れとなります', '【APP】お客様の取引がまもなく期限切れとなります', 'お客様各位,\r\n\r\n平素より弊社サービスをご利用いただき、誠にありがとうございます。\r\n\r\nお客様の取引がまもなく期限切れとなりますので、ご注意ください。  \r\nお取引を継続されたい場合は、必要な手続きを行ってください。', '', '', '2022-05-18 16:18:19', '2022-05-18 16:18:19');");
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    DB::statement('DELETE FROM `mail_templates` WHERE `mail_templates`.`id` = 5');
  }
};
