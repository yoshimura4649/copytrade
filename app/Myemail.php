<?php

namespace App;

use App\Models\Setup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Myemail
{
  // ユーザー宛にメールを送る
  public static function user($user, $templateId, $files = [])
  {
    $template = DB::table('mail_templates')
      ->select(['subject', 'body', 'admin_subject', 'admin_body'])
      ->where('id', '=', $templateId)
      ->first();

    unset($user['password']);
    $body = $template['body'];
    $adminBody = $template['admin_body'];
    $subject = $template['subject'];

    // 置換タグを置き換える
    foreach ($user as $key => $value) {
      if (!is_array($value)) {
        $value = $value ?: '';
        $body = str_replace('[' . $key . ']', $value, $body);
        $adminBody = str_replace('[' . $key . ']', $value, $adminBody);
        $subject =  str_replace('[' . $key . ']', $value, $subject);
      }
    }

    // 管理者宛に送る
    if ($template['admin_subject'] && $adminBody) {
      self::admin($template['admin_subject'], $adminBody);
    }

    // 会員宛に送る
    return self::send($subject, $body, $user['email'], $user['name'], $files);
  }

  // 管理者宛にメール送信
  public static function admin($subject, $body)
  {
    $mail = preg_split("/\r\n|\n|\r/", Setup::get('manage_mail'));
    static::send($subject, $body, $mail);
  }

  public static function send($subject, $body, $to, $name = '', $files = [])
  {
    try {
      Mail::send([], [], function ($message) use ($subject, $body, $to, $name) {
        $message->to($to, $name)
          ->subject($subject)
          ->text($body);
      });
    } catch (\Exception $e) {
      return false;
    }

    return true;
  }
}