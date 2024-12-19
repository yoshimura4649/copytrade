@extends('frontend.template')

@section('content')
  <div class="col-md-12">
    <div class="col-md-8 m-auto">
      <div class="card card-info">
        <div class="card-header bg-warning">
          <h3 class="card-title font-weight-bold">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" action="{{ route('register') }}" method="post">
          @csrf
          <div class="card-body">
            <div class="form-group row">
              <label for="name" class="col-sm-4 col-form-label font-weight-light">氏名</label>
              <div class="col-sm-8">
                <input readonly type="text" class="form-control" name="name" placeholder="氏名" value="{{ $response['name'] }}"
                  maxlength="255" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="email" class="col-sm-4 col-form-label font-weight-light">メールアドレス</label>
              <div class="col-sm-8">
                <input readonly type="email" class="form-control" name="email" value="{{ $response['email'] }}" maxlength="255" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="tel" class="col-sm-4 col-form-label font-weight-light">連絡先電話番号</label>
              <div class="col-sm-8">
                <input readonly type="tel" class="form-control" name="tel" value="{{ $response['tel'] }}" placeholder="0123456789"
                  minlength="10" maxlength="15" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="password" class="col-sm-4 col-form-label font-weight-light">パスワード</label>
              <div class="col-sm-8">
                <input readonly type="password" class="form-control" id="password" name="password" placeholder="******"
                  value="{{ $response['password'] }}" minlength="6" maxlength="32" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="password-confirmation" class="col-sm-4 col-form-label font-weight-light">パスワード（確認用）</label>
              <div class="col-sm-8">
                <input readonly type="password" class="form-control" name="password_confirmation" placeholder="******"
                  value="{{ $response['password_confirmation'] }}" minlength="6" maxlength="32" required>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <button type="button" class="btn btn-secondary" onclick="history.back()"><i class="fa-solid fa-arrow-left mr-2"></i>
              戻る</button>
            <button type="submit" class="btn btn-warning font-weight-bold float-right">登録する <i
                class="fa-solid fa-arrow-right ml-2"></i></button>
          </div>
          <!-- /.card-footer -->
        </form>
      </div>
    </div>
  </div>
@endsection
