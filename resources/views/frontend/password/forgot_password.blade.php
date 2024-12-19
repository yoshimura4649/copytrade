@extends('frontend.template')

@section('content')
  <div class="login-box m-auto">
    <!-- /.login-logo -->
    <div class="card card-outline card-warning">
      <div class="card-header text-center">
        <b>{{ $title }}</b>
      </div>
      <div class="card-body">
        <form method="post" id="request-password-form" class="section-wrap">
          @csrf
          <div class="input-group mb-3">
            <label for="email">ログインID</label>
            <input type="email" class="form-control w-100" id="email" name="email" value="{{ old('email') }}" maxlength="255"
              placeholder="dummy@sample.co.jp" required>
          </div>
          <div class="input-group mb-3">
            <label for="phone">連絡先電話番号</label>
            <input id="phone" class="form-control w-100" type="tel" value="{{ old('phone') }}" name="phone" maxlength="15" required
              data-rule-telnum placeholder="0123456789">
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-12 mb-3 mt-2">
              <button type="submit" class="btn btn-warning btn-block">送信</button>
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
      $('#request-password-form').validate();
    });
  </script>
@endsection
