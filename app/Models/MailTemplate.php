<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MailTemplate extends Model
{
  protected $table = 'mail_templates';

  const SYSTEM_MAX = 20;

  public static function get_manual_list()
  {
    return DB::table('mail_templates')
      ->select(['id', 'subject'])
      ->where('id', '>', static::SYSTEM_MAX)
      ->get()->toArray();
  }
}
