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
          <span>パスワードの再設定完了</span>
          <p class="guid center">送付されたURLから、パスワードの再設定を行ってください。</p>
          <div class="section-wrap">
            <div class="pickup-guid">
              <p class="title">パスワードの再設定が完了しました。</p>
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
