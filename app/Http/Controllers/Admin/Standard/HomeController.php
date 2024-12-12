<?php

namespace App\Http\Controllers\Admin\Standard;

use App\Http\Controllers\Admin\BaseController;

class HomeController extends BaseController
{
  public function getIndex()
  {
    return view('admin.standard.home.index', ['title' => 'ホーム']);
  }
}
