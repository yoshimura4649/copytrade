@extends('frontend.template')

@section('content')
  <div class="col-md-12">
    <div class="col-md-8 m-auto">
      <div class="card card-warning">
        <div class="card-header">
          <h3 class="card-title">アカウント設定</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <form method="POST" id="info-form">
            @csrf
            <div class="form-group">
              <label for="name">氏名</label>
              <input readonly type="text" id="name" name="name" class="form-control" value="{{ Auth::user()->name }}">
            </div>
            <div class="form-group">
              <label for="inputDescription">メールアドレス</label>
              <input readonly type="text" id="email" name="email" class="form-control" value="{{ Auth::user()->email }}">
            </div>
            <div class="form-group">
              <label for="inputDescription">連絡先電話番号</label>
              <input type="text" id="tel" name="tel" class="form-control" value="{{ Auth::user()->tel }}">
            </div>
            <div class="form-group">
              <div class="icheck-warning">
                <input type="checkbox" id="change-user-password" class="change-password" name="change_user_password">
                <label for="change-user-password" style="font-size:13px">
                  パスワードを変更する
                </label>
              </div>
            </div>
            <div class="change-user-password" style="display:none">
              <div class="form-group">
                <label for="inputDescription">パスワード</label>
                <input type="password" id="password" name="password" class="form-control" value="" placeholder="******">
              </div>
              <div class="form-group">
                <label for="inputDescription">パスワード（確認用）</label>
                <input type="password" id="password-confirmation" class="form-control" name="password_confirmation" placeholder="******"
                  value="" minlength="6" maxlength="32" required autocomplete="password" data-rule-equalTo="#password">
              </div>
            </div>
            <div class="form-group">
              <label for="inputDescription">カブコムID</label>
              <input type="text" id="kabu-id" name="kabu_id" class="form-control" value="{{ Auth::user()->kabu_id }}"
                placeholder="XSFEGDGFG" required>
            </div>
            <div class="form-group">
              <div class="icheck-warning">
                <input type="checkbox" id="change-kubu-password" class="change-password" name="change_kabu_password">
                <label for="change-kubu-password" style="font-size:13px">
                  カブコムパスワードを変更する
                </label>
              </div>
            </div>
            <div class="change-kubu-password" style="display:none">
              <div class="form-group">
                <label for="inputDescription">カブコムパスワード</label>
                <input type="password" id="kabu-password" name="kabu_password" class="form-control" value="" placeholder="******"
                  required>
              </div>
              <div class="form-group">
                <label for="inputDescription">カブコムパスワード（確認用）</label>
                <input type="password" id="kabu-password-confirmation" class="form-control" name="kabu_password_confirmation"
                  placeholder="******" value="" required autocomplete="kabu-password" data-rule-equalTo="#kabu-password">
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-warning float-right">保存する <i class="fa-solid fa-arrow-right ml-2"></i></button>
              </div>
            </div>
          </form>
        </div>
        <!-- /.card-body -->
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#info-form').validate();

      $('.change-password').on('change', function() {
        if ($(this).is(':checked')) {
          $('.' + $(this).attr('id')).slideDown(500);
        } else {
          $('.' + $(this).attr('id')).slideUp(500);
        }
      });

      $('#info-form').on('submit', function(e) {
        if (!confirm('本当に送信しますか？')) {
          e.preventDefault();
        }
      });
    });
  </script>
@endsection
