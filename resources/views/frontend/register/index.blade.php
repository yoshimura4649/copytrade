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
        <form class="form-horizontal" action="{{ route('register_confirm') }}" method="post" id="register-form">
          @csrf
          <div class="card-body">
            <div class="form-group row">
              <label for="name" class="col-sm-4 col-form-label font-weight-light">氏名</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" placeholder="氏名" value="{{ old('name') }}" maxlength="255"
                  required>
              </div>
            </div>
            <div class="form-group row">
              <label for="email" class="col-sm-4 col-form-label font-weight-light">メールアドレス</label>
              <div class="col-sm-8">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" maxlength="255" required
                  placeholder="dummy@sample.co.jp">
              </div>
            </div>
            <div class="form-group row">
              <label for="tel" class="col-sm-4 col-form-label font-weight-light">連絡先電話番号</label>
              <div class="col-sm-8">
                <input type="tel" class="form-control" name="tel" value="{{ old('tel') }}" placeholder="0123456789" minlength="10"
                  maxlength="15" required autocomplete="tel" data-rule-telnum>
              </div>
            </div>
            <div class="form-group row">
              <label for="password" class="col-sm-4 col-form-label font-weight-light">パスワード</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" id="password" name="password" placeholder="******" value="{{ old('password') }}"
                  minlength="6" maxlength="32" required autocomplete="password">
              </div>
            </div>
            <div class="form-group row">
              <label for="password-confirmation" class="col-sm-4 col-form-label font-weight-light">パスワード（確認用）</label>
              <div class="col-sm-8">
                <input type="password" id="password-confirmation" class="form-control" name="password_confirmation" placeholder="******"
                  value="{{ old('password_confirmation') }}" minlength="6" maxlength="32" required autocomplete="password"
                  data-rule-equalTo="#password">
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="/login" class="btn btn-default font-weight-bold"><i class="fa-solid fa-arrow-left mr-2"></i>ログインへ</a>
            <button type="submit" class="btn btn-warning font-weight-bold float-right">確認画面へ <i
                class="fa-solid fa-arrow-right ml-2"></i></button>
          </div>
          <!-- /.card-footer -->
        </form>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#register-form').validate();
    });
  </script>
@endsection
