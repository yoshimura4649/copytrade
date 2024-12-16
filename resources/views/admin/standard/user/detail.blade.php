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
          <div class="row">
            <div class="form-group col-md-12">
              <label for="name">氏名</label>
              <span class="float-right badge badge-danger">必須</span>
              <input value="<?php echo $detail['name']; ?>" type="text" name="name" class="form-control" id="name" placeholder="例：山田太郎" required>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="zip">郵便番号</label>
              <span class="float-right badge badge-danger">必須</span>
              <input value="<?php echo $detail['zip']; ?>" type="number" name="zip" class="form-control" id="zip" placeholder="例：1234567" required>
            </div>
            <div class="form-group col-md-6">
              <label for="prefecture">都道府県</label>
              <span class="float-right badge badge-danger">必須</span>
              <select class="form-control" name="prefecture" id="prefecture" required>
                <option value="">選択してください</option>
                <?php foreach (Lang::get('system.prefectures') as $k => $v) : ?>
                <option value="<?php echo $k; ?>" <?php echo $k == $detail['prefecture'] ? ' selected' : ''; ?>><?php echo $v; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="address">都道府県以下住所</label>
            <span class="float-right badge badge-danger">必須</span>
            <input value="<?php echo isset($detail['address']) ? $detail['address'] : '' ?>" type="text" name="address" class="form-control" id="address" required>
          </div>
          <div class="form-group">
            <label for="address_other">建物名</label>
            <input value="<?php echo isset($detail['address_other']) ? $detail['address_other'] : '' ?>" type="text" name="address_other" class="form-control" id="address_other">
          </div>
          <div class="form-group">
            <label for="email">電話番号</label>
            <span class="float-right badge badge-danger">必須</span>
            <input value="<?php echo $detail['tel']; ?>" type="tel" name="tel" class="form-control" placeholder="例：12345678901" id="tel" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="app_api_url">アプリのAPI URL</label>
            <span class="float-right badge badge-danger">必須</span>
            <input value="<?php echo $detail['app_api_url']; ?>" type="text" name="app_api_url" class="form-control" id="app_api_url" required>
          </div>
          <div class="form-group">
            <label for="api_password">APIパスワード</label>
            <span class="float-right badge badge-danger">必須</span>
            <input value="<?php echo $detail['api_password']; ?>" type="password" name="api_password" class="form-control" id="api_password" required>
          </div>
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <span class="float-right badge badge-danger">必須</span>
            <input value="<?php echo $detail['email']; ?>" type="email" name="email" class="form-control" id="email" placeholder="例：example@example.com" required>
          </div>
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="password" class="form-control" id="password" minlength="6">
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="main-footer">
    <div class="row">
      <div class="col-6">
        <a href="/admin/standard/user/list" id="btn-list-back" class="btn btn-link pl-1"><i class="fa fa-backward"></i><span> 一覧に戻る</span></a>
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