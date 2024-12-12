@extends('admin.template')
@section('content')
<form id="detail-form">
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">お客様の内容</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>名前</label>
            <input type="text" class="form-control" disabled value="<?php echo $detail['name']; ?>">
          </div>
          <div class="form-group">
            <label>メールアドレス</label>
            <input type="text" class="form-control" disabled value="<?php echo $detail['email']; ?>">
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <div class="form-group">
            <label>お問合せ内容</label>
            <textarea class="form-control" rows="8" disabled><?php echo $detail['content']; ?></textarea>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">管理情報</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="memo">対応状況</label>
            <div>
              <div class="custom-control custom-radio custom-control-inline">
                <input value="0" class="custom-control-input" type="radio" id="status1" name="status" <?php if ($detail['status'] == 0) : ?> checked<?php endif; ?>>
                <label for="status1" class="custom-control-label">未対応</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input value="1" class="custom-control-input" type="radio" id="status2" name="status" <?php if ($detail['status'] == 1) : ?> checked<?php endif; ?>>
                <label for="status2" class="custom-control-label">対応済み</label>
              </div>
            </div>
          </div>
          <!-- /.form-group -->
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="memo">メモ</label>
            <textarea name="memo" class="form-control" id="memo" rows="2"><?php echo $detail['memo']; ?></textarea>
          </div>
          <!-- /.form-group -->
        </div>
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
        <a href="/admin/email/inquiry/list" id="btn-list-back" class="btn btn-link pl-1"><i class="fa fa-backward"></i><span> 一覧に戻る</span></a>
      </div>
      <div class="col-6 text-right">
        <button id="btn-save" class="btn btn-primary"><i class="fas fa-save"></i> 保存</button>
      </div>
    </div>
  </footer>

</form>
@endsection