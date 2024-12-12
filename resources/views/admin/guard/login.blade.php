@extends('admin.guard.template')
@section('content')
<div class="card">
  <div class="card-body login-card-body">
    <p class="login-box-msg">マネジメントへ</p>

    <form method="post">
      @csrf
      <div class="input-group mb-3">
        <input type="email" name="email" placeholder="メールアドレス" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-envelope"></span>
          </div>
        </div>
      </div>
      <div class="input-group mb-3">
        <input type="password" placeholder="パスワード" name="password" class="form-control" id="exampleInputPassword1">
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-lock"></span>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-6">
          <div class="icheck-primary">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" >
              <small>ログイン状態を保持</small>
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-6">
          <button type="submit" class="btn btn-primary btn-block">ログイン</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-card-body -->
</div>
@endsection