@extends('admin.template')
@section('content')
<form id="detail-form">
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">基本設定</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="name">名前</label>
            <span class="float-right badge badge-danger">必須</span>
            <input value="<?php echo $detail['name']; ?>" type="text" name="name" class="form-control" id="name" placeholder="例：山田太郎" required>
          </div>
          <!-- /.form-group -->
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <span class="float-right badge badge-danger">必須</span>
            <input value="<?php echo $detail['email']; ?>" type="email" name="email" class="form-control" id="email" placeholder="例：example@example.com" required>
          </div>
         
          
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="password" class="form-control" id="password" minlength="6">
          </div>
          <div class="form-group">
            <label for="group">権限</label>
            <span class="float-right badge badge-danger">必須</span>
            <select class="form-control" name="group" id="group" required>
              <option value="1"<?php if ($detail['group'] == 1): ?> selected<?php endif; ?>>企業者</option>
              <option value="50"<?php if ($detail['group'] == 50): ?> selected<?php endif; ?>>管理者</option>
            </select>
          </div>
        </div>
          <!-- /.form-group -->
        </div>
        <!-- /.col -->
      
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

  <footer class="main-footer">
    <div class="row">
      <div class="col-6">
        <a href="/admin/standard/moderator/list" id="btn-list-back" class="btn btn-link pl-1"><i class="fa fa-backward"></i><span> 一覧に戻る</span></a>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#del-modal"><i class="fas fa-trash-alt"></i> 削除</button>
        <button type="button" id="btn-copy" class="btn btn-default"><i class="fas fa-copy"></i><span> 複製</span></button>
      </div>
      <div class="col-6 text-right">
        <button id="btn-save" class="btn btn-primary"><i class="fas fa-save"></i> 保存</button>
      </div>
    </div>
  </footer>

</form>
@endsection