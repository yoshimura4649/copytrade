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
            <label for="email">管理者メールアドレス</label>
            <span class="float-right badge badge-danger">必須</span>
            <textarea name="manage_mail" class="form-control" id="manage-mail" rows="5" maxlength="2000" required
              data-rule-multiemail><?php echo $detail['manage_mail']; ?></textarea>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.card-body -->
  </div>

  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">タグ</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="tag_head">（全ページ）head内</label>
            <textarea name="tag_head" class="form-control" id="tag-head" rows="5" maxlength="2000"><?php echo $detail['tag_head']; ?></textarea>
          </div>
          <div class="form-group">
            <label for="tag_body_top">（全ページ）body内の上</label>
            <textarea name="tag_body_top" class="form-control" id="tag-body-top" rows="5" maxlength="2000"><?php echo $detail['tag_body_top']; ?></textarea>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <div class="form-group">
            <label for="tag_body_end">（全ページ）body内の最後</label>
            <textarea name="tag_body_end" class="form-control" id="tag-body-end" rows="5" maxlength="2000"><?php echo $detail['tag_body_end']; ?></textarea>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.card-body -->
  </div>

  <footer class="main-footer">
    <div class="row">
      <div class="col-6">
        <a href="/admin/requirement/setup/detail" id="btn-list-back" class="btn btn-link pl-1" hidden></a>
      </div>
      <div class="col-6 text-right">
        <button id="btn-save" class="btn btn-primary"><i class="fas fa-save"></i> 保存</button>
      </div>
    </div>
  </footer>

</form>
@endsection