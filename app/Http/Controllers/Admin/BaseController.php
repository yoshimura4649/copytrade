<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function __construct()
  {
    $this->middleware(function ($request, $next) {
      $auth = Auth::guard('admin');
      if (!$auth->check()) {
        return redirect('admin/guard/login?ref=' . url()->full())->send();
      }

      $admin = $auth->user();
      $this->admin['id'] = $admin->id;
      $this->admin['name'] = $admin->name;
      $this->admin['group'] = $admin->group;

      View::share(array(
        'admin' => $this->admin,
      ), null, false);

      return $next($request);
    });
  }

  public function getNew()
  {
    $view = $this->getDetail();

    return $view->with('title', str_replace('詳細', '新規', $view->title));
  }

  public function postNew()
  {
    return $this->postDetail();
  }

  public function getCopy($id)
  {
    $view = $this->getDetail($id);

    return $view->with('title', str_replace('詳細', '複製', $view->title));
  }

  public function postCopy($id)
  {
    return $this->postDetail();
  }

  public function order($input, $query)
  {
    if (isset($input['order'])) {
      foreach ($input['order'] as $value) {
        $query->order_by($value['column'], $value['dir']);
      }
    }
  }

  public function upload($keys, &$input)
  {
    $segments = Request::segments();
    $path = $segments[1] . '-' . $segments[2] . DIRECTORY_SEPARATOR;

    foreach ($keys as $key) {
      $file = Request::file($key);
      if ($file) {
        $file->storePublicly($path);
        $input[$key] = $file->hashName();
      } elseif ($input[$key] && $segments[3] == 'copy' && Storage::exists($path . $input[$key])) {
        $newName = uniqid() . $input[$key];
        Storage::copy($path . $input[$key], $path . $newName);
        $input[$key] = $newName;
      }

      $fileOld = $segments[3] == 'copy' ? '' : $input[$key . '_old'];
      unset($input[$key . '_old']);

      if ($fileOld && (empty($input[$key]) || $input[$key] != $fileOld) && Storage::exists($path . $fileOld)) {
        Storage::delete($path . $fileOld);
      }
    }
  }

  public function uploadMulti($keys, &$input)
  {
    $segments = Request::segments();
    $path = $segments[1] . '-' . $segments[2] . DIRECTORY_SEPARATOR;

    $files = [];
    foreach ($keys as $key) {
      $file = Request::file($key);
      if ($file) {
        $file->storePublicly($path);
        $input[$key] = $file->hashName();
      } elseif ($input[$key] && $segments[3] == 'copy' && Storage::exists($path . $input[$key])) {
        $newName = uniqid() . $input[$key];
        Storage::copy($path . $input[$key], $path . $newName);
        $input[$key] = $newName;
      }

      $fileOld = $segments[3] == 'copy' ? '' : $input[$key . '_old'];

      if ($fileOld && (empty($input[$key]) || $input[$key] != $fileOld) && Storage::exists($path . $fileOld)) {
        Storage::delete($path . $fileOld);
      }

      $input[$key] and $files[] = ['name' => $input[$key], 'alt' => $input[$key . '_alt'] . ''];
      unset($input[$key], $input[$key . '_old'], $input[$key . '_alt']);
    }

    return json_encode($files);
  }

  public function unlink($keys, $input)
  {
    $segments = Request::segments();
    $path = $segments[1] . '-' . $segments[2] . DIRECTORY_SEPARATOR;

    foreach ($keys as $key) {
      if (!empty($input[$key]) && Storage::exists($path . $input[$key])) {
        Storage::delete($path . $input[$key]);
      }
    }
  }

  public function unlinkMulti($keys, $input)
  {
    $segments = Request::segments();
    $path = $segments[1] . '-' . $segments[2] . DIRECTORY_SEPARATOR;

    foreach ($keys as $key) {
      foreach (json_decode($input[$key], true) as $file) {
        if (Storage::exists($path . $file['name'])) {
          Storage::delete($path . $file['name']);
        }
      }
    }
  }
}