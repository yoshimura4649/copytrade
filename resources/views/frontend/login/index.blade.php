@extends('frontend.template')

@section('content')
  <div class="login-box m-auto">
    <!-- /.login-logo -->
    <div class="card card-outline card-warning">
      <div class="card-header text-center">
        <b>{{ $title }}</b>
      </div>
      <div class="card-body">
        <form action="{{ route('login') }}" method="post">
          @csrf
          <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" maxlength="255"
              placeholder="dummy@sample.co.jp" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="******" minlength="6" maxlength="32"
              required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="icheck-warning">
                <input type="checkbox" id="remember">
                <label for="remember" style="font-size:13px">
                  私を覚えてますか
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-12 mb-3 mt-2">
              <button type="submit" class="btn btn-warning btn-block">ログイン</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <p class="mb-1">
          <a href="/forgot_password" style="font-size:13px">パスワードが不明な方はコチラ</a>
        </p>
        <p class="mb-0">
          <a href="/register" class="text-center" style="font-size:13px">ユーザー登録</a>
        </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
@endsection
