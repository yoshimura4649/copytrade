<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Setup extends Model
{
  protected $table = 'setups';

  public static function get($key = null)
  {
    static $setup;
    if (!$setup) {
      $setup = DB::table('setups')
        ->select('manage_mail', 'tag_head', 'tag_body_top', 'tag_body_end')
        ->where('id', '=', 1)
        ->first();
    }

    return $key ? $setup[$key] : $setup;
  }
}