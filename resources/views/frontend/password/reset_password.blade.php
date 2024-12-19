@extends('frontend.template')

@section('content')
  <div class="login-box m-auto">
    <!-- /.login-logo -->
    <div class="card card-outline card-warning">
      <div class="card-header text-center">
        <b>{{ $title }}</b>
      </div>
      <div class="card-body">
        <form method="post" id="update-password-form" class="section-wrap">
          @csrf
          <input type="hidden" name="verify_token" value="{{ $token }}">
          <div class="input-group mb-3">
            <label for="password">新しいパスワード</label>
            <input class="form-control w-100" id="password" type="password" name="password" minlength="6" maxlength="32"
              placeholder="**********" required autocomplete="password">
          </div>
          <div class="input-group mb-3">
            <label for="password_confirmation">新しいパスワード（確認）</label>
            <input class="form-control w-100" id="password_confirmation" type="password" name="password_confirmation" placeholder="**********"
              data-rule-equalTo="#password" required autocomplete="password">
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-12 mb-3 mt-2">
              <button type="submit" class="btn btn-warning btn-block">再設定する</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#update-password-form').validate();
    });
  </script>
@endsection
