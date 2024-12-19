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
        <div class="card-body">
          <span>以下のアドレスに<br>パスワード再設定用のURLを送付しました。</span>
          <p class="guid center">送付されたURLから、パスワードの再設定を行ってください。</p>
          <div class="section-wrap">
            <div class="pickup-guid">
              <p class="title">登録メールアドレス</p>
              <p class="item">{{ session('email') }}</p>
            </div>
            <div class="guid-wrap">
              <p class="guid center">※パスワードの再設定有効期間は5分です。5分以上経過するとアクセスできなくなりますので、ご登録メールアドレスの入力画面から再設定をお願いします。</p>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer m-auto">
          <a href="/login" class="btn btn-warning font-weight-bold">ログイン画面に戻る <i class="fa-solid fa-right-from-bracket ml-2"></i></a>
        </div>
        <!-- /.card-footer -->
      </div>
    </div>
  </div>
@endsection
