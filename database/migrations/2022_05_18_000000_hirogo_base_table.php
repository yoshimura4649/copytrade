<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('created_at');
      $table->dropColumn('updated_at');
    });

    Schema::table('users', function (Blueprint $table) {
      $table->string('tel', 15)->nullable()->after('password');
      $table->string('zip', 8)->nullable()->after('tel');
      $table->tinyInteger('prefecture')->default(0)->after('zip');
      $table->string('address', 1024)->nullable()->after('prefecture');
      $table->string('address_other', 1024)->nullable()->after('address');
      $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
      // 論理削除
      $table->date('deleted_at')->nullable()->after('updated_at');
    });

    Schema::create('admins', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('email')->unique();
      $table->integer('group');
      $table->string('password');
      $table->rememberToken();
      $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
    });

    Schema::create('inquiries', function (Blueprint $table) {
      $table->id();
      $table->integer('type')->default(0)->comment('タイプ');
      $table->tinyInteger('status')->default(0)->comment('ステータス');
      $table->integer('user_id')->default(0)->comment('会員ID');
      $table->string('name')->comment('名前');
      $table->string('email')->comment('メールアドレス');
      $table->string('title')->comment('件名');
      $table->string('content', 4096)->comment('お問い合わせ内容');
      $table->string('memo', 4096)->nullable()->comment('管理者メモ');
      $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
    });

    Schema::create('mail_templates', function (Blueprint $table) {
      $table->increments('id');
      $table->string('title', 1024)->comment('タイトル');
      $table->string('subject', 2048)->comment('件名');
      $table->text('body')->nullable()->comment('内容');
      $table->string('admin_subject', 2048)->nullable()->comment('管理者宛の件名');
      $table->text('admin_body', 4096)->nullable()->comment('管理者宛の内容');
      $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
    });

    DB::statement("ALTER TABLE mail_templates AUTO_INCREMENT = 21;");
    DB::statement("INSERT INTO `mail_templates` (`id`, `title`, `subject`, `body`, `admin_subject`, `admin_body`, `created_at`, `updated_at`) VALUES ('1', '新規会員登録完了のお知らせ', '【APP】ご登録完了のお知らせ', '[name]様\r\nこの度は、ご登録ありがとうございました。\r\n会員登録が完了いたしましたのでお知らせいたします。', '【APP】新規会員登録完了のお知らせ', '[name]様が会員登録を完了されました。', '2022-05-18 16:18:19', '2022-05-18 16:18:19');");
    DB::statement("INSERT INTO `mail_templates` (`id`, `title`, `subject`, `body`, `admin_subject`, `admin_body`, `created_at`, `updated_at`) VALUES ('2', 'パスワード再設定リンクのお知らせ', '【APP】パスワード再設定を受け付けました', 'パスワード再設定を受け付けました。\n下記のリンクをクリックしてパスワードをリセットしてください。\n[url]', '', '', '2022-05-18 16:18:19', '2022-05-18 16:18:19');");
    DB::statement("INSERT INTO `mail_templates` (`id`, `title`, `subject`, `body`, `admin_subject`, `admin_body`, `created_at`, `updated_at`) VALUES ('3', 'パスワード再設定完了のお知らせ', '【APP】パスワード再設定完了のお知らせ', '[name]様\nこの度は、ご利用ありがとうございます。\nパスワード再設定は完了しました。\n--------------------------------------------------\nログインＩＤ：[email]\nパスワード：ご指定のパスワード\n', '', '', '2022-05-18 16:18:19', '2022-05-18 16:18:19');");
    DB::statement("INSERT INTO `mail_templates` (`id`, `title`, `subject`, `body`, `admin_subject`, `admin_body`, `created_at`, `updated_at`) VALUES ('4', 'お問い合わせ受付完了のお知らせ', '【APP】お問い合わせ受付完了のお知らせ', '[name]様からお問い合わせが届きました。\r\n\r\nお問い合わせフォーム：\r\n-----------------------------------------------------------------------\r\nお名前：[name]\r\nメールアドレス：[email]\r\nお問い合わせ内容：[content]\r\n-----------------------------------------------------------------------', '【APP】お問い合わせがありました', '[name]様からお問い合わせが届きました。\r\n\r\nお問い合わせフォーム：\r\n-----------------------------------------------------------------------\r\nお名前：[name]\r\nメールアドレス：[email]\r\nお問い合わせ内容：[content]\r\n-----------------------------------------------------------------------', '2022-05-18 16:18:19', '2022-05-18 16:18:19');");

    Schema::create('setups', function (Blueprint $table) {
      $table->id();
      $table->string('manage_mail', 2048)->comment('管理者メール');
      $table->string('tag_head', 2048)->nullable()->comment('head内タグ');
      $table->string('tag_body_top', 2048)->nullable()->comment('body内上タグ');
      $table->string('tag_body_end', 2048)->nullable()->comment('body最後タグ');
      $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
    });

    DB::table('setups')->insert([
      'manage_mail' => Str::random(10) . '@hirogo.net',
    ]);

    DB::statement("ALTER TABLE password_resets CHANGE created_at created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('tel');
      $table->dropColumn('zip');
      $table->dropColumn('prefecture');
      $table->dropColumn('address');
      $table->dropColumn('address_other');
      $table->dropColumn('created_at');
      $table->dropColumn('updated_at');
      $table->dropColumn('deleted_at');
    });

    Schema::table('users', function (Blueprint $table) {
      $table->timestamps();
    });

    Schema::dropIfExists('admins');
    Schema::dropIfExists('inquiries');
    Schema::dropIfExists('mail_templates');
    Schema::dropIfExists('setups');

    DB::statement("ALTER TABLE password_resets CHANGE created_at created_at TIMESTAMP DEFAULT NULL;");
  }
};
