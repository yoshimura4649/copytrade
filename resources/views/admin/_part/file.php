<?php $id = str_replace('_', '-', $name); ?>
<div class="myupload">
  <p>ドラッグ&ドロップまたは <label for="{{ $id; }}" class="btn btn-outline-primary btn-sm mb-0"><i class="fas fa-folder-open"></i>ファイルを選択</label></p>
  <input type="file" name="{{ $name; }}" id="{{ $id; }}>" hidden<?php if (isset($accept)) : ?> accept="{{ $accept; }}" <?php endif; ?> <?php if (isset($max) && $max > 1) : ?> multiple data-rule-maxfiles="{{ $max }}" <?php endif; ?>>
  <ul class="p-0 m-0">
    <li class="mt-2 d-flex align-items-center justify-content-between">
      <span><img src="/assets/admin/dist/img/avatar5.png" class="mr-2">a9d229303d194da870700f45f38a9837.PNG</span>

      <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i><span> 削除</span></button>
    </li>
    <li class="mt-2 d-flex align-items-center justify-content-between">
      <span><img src="/assets/admin/dist/img/avatar5.png" class="mr-2">a9d229303d194da870700f45f38a9837.PNG</span>

      <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i><span> 削除</span></button>
    </li>
  </ul>
</div>