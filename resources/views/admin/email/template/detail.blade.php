@extends('admin.template')
@section('content')
<form id="detail-form">
  <div class="row">
    <div class="col-md-6">

      <div class="card card-default">
        <div class="card-header">
          <h3 class="card-title">基本設定</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>

        <div class="card-body">
          <?php if (!empty($detail['id']) && $detail['id'] <= App\Models\MailTemplate::SYSTEM_MAX) : ?>
          <p class="mb-3 text-danger">※システムのメールテンプレート</p>
          <?php endif; ?>

          <div class="form-group">
            <label for="subject">タイトル</label>
            <span class="float-right badge badge-danger">必須</span>
            <input value="<?php echo isset($detail['title']) ? $detail['title'] : '' ?>" type="text" name="title" class="form-control" id="title" required>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">

      <div class="card card-default collapsed-card">
        <div class="card-header">
          <h3 class="card-title">タグ一覧</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group">
            <table class="table table-striped table-sm table-bordered" id="list-tag">
              <thead>
                <tr>
                  <th>タグ</th>
                  <th>内容</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><a href="#">[name]</a></td>
                  <td>お名前</td>
                </tr>
                <tr>
                  <td><a href="#">[tel]</a></td>
                  <td>電話番号</td>
                </tr>
                <tr>
                  <td><a href="#">[email]</a></td>
                  <td>メールアドレス</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">ユーザーへ通知</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <div class="card-body">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="subject">件名</label>
            <span class="float-right badge badge-danger">必須</span>
            <input value="<?php echo isset($detail['subject']) ? $detail['subject'] : '' ?>" type="text" name="subject" class="form-control" id="subject" required>
          </div>
          <div class="form-group">
            <label for="body">本文</label>
            <span class="float-right badge badge-danger">必須</span>
            <textarea name="body" class="form-control" id="body" required rows="10"><?php echo isset($detail['body']) ? $detail['body'] : '' ?></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if (!empty($detail['id']) && $detail['id'] <= App\Models\MailTemplate::SYSTEM_MAX) : ?>
  <div class="card card-default">
    <div class="card-header">
      <h3 class="card-title">管理者へ通知</h3>

      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
      </div>
    </div>
    <div class="card-body">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="admin_subject">件名</label>
            <input value="<?php echo isset($detail['admin_subject']) ? $detail['admin_subject'] : '' ?>" type="text" name="admin_subject" class="form-control" id="admin-subject">
          </div>
          <div class="form-group">
            <label for="admin_body">本文</label>
            <textarea name="admin_body" class="form-control" id="admin-body" rows="5"><?php echo isset($detail['admin_body']) ? $detail['admin_body'] : '' ?></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <footer class="main-footer">
    <div class="row">
      <div class="col-6">
        <a href="/admin/email/template/list" id="btn-list-back" class="btn btn-link pl-1"><i class="fa fa-backward"></i><span> 一覧に戻る</span></a>
        <?php if (!empty($detail['id']) && $detail['id'] > App\Models\MailTemplate::SYSTEM_MAX) : ?>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#del-modal"><i class="fas fa-trash-alt"></i> 削除</button>
        <?php endif; ?>
      </div>
      <div class="col-6 text-right">
        <button id="btn-save" class="btn btn-primary"><i class="fas fa-save"></i> 保存</button>
      </div>
    </div>
  </footer>

</form>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    function fallbackCopyTextToClipboard(text) {
      var textArea = document.createElement('textarea');
      textArea.value = text;

      // Avoid scrolling to bottom
      textArea.style.top = '0';
      textArea.style.left = '0';
      textArea.style.position = 'fixed';

      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();

      try {
        if (document.execCommand('copy')) {
          showSuccess('タグをコピーしました');
        }
      } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
      }

      document.body.removeChild(textArea);
    }

    function copyTextToClipboard(text) {
      if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text);
        return;
      }
      navigator.clipboard.writeText(text).then(function() {
        showSuccess('タグをコピーしました');
      }, function(err) {
        console.error('Async: Could not copy text: ', err);
      });
    }

    $('#list-tag').on('click', 'a', function(e) {
      e.preventDefault();
      copyTextToClipboard($(this).text());
    });
  });
</script>
@endsection