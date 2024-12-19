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
          <p class="guid center">ご登録いただきましたメールアドレスに、マイページへのログインURLを送信いたしました。<br>ご確認の上、ログインしてください。</p>
        </div>
        <!-- /.card-body -->
        <div class="card-footer m-auto">
          <a href="/login" class="btn btn-warning btn-block">ログインへ <i class="fa-solid fa-right-from-bracket ml-2"></i></a>
        </div>
        <!-- /.card-footer -->
      </div>
    </div>
  </div>
@endsection
