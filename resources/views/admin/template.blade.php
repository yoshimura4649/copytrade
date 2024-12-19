<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <link rel="icon" type="image/png" href="/assets/dist/admin/img/favicon-16x16.png">

  <title>{{ $title }}</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/fontawesome.min.css" />
  <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/regular.min.css" />
  <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/solid.min.css" />

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Datetimepicker -->
  <link rel="stylesheet" href="/assets/plugins/bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css">
  <!--Daterangepicker -->
  <link rel="stylesheet" href="/assets/plugins/daterangepicker/daterangepicker.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <?php
  $segments = Request::segments();
  !isset($segments[3]) and $segments[3] = 'index';
  ?>
  <?php $isList = $segments[3] == 'list' || !empty($list); ?>
  <?php if ($isList) : ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables/datatables.min.css">
  <?php endif; ?>
  <!-- Theme style -->
  <link rel="stylesheet" href="/assets/dist/admin/css/adminlte.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.css">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/earlyaccess/mplus1p.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/earlyaccess/roundedmplus1c.css" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/dist/admin/css/main.css">

  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script defer src="/assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script defer src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- overlayScrollbars -->
  <script defer src="/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- datetime-picker -->
  <script defer src="/assets/plugins/moment/moment.min.js"></script>
  <script defer src="/assets/plugins/moment/locale/ja.js"></script>
  <!-- Datetimepicker -->
  <script defer src="/assets/plugins/bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
  <!-- Daterangepicker -->
  <script defer src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Select2 -->
  <script defer src="/assets/plugins/select2/js/select2.full.min.js"></script>
  <!-- ajaxzip3 -->
  <script src="https://ajaxzip3.github.io/ajaxzip3.js"></script>

  @if ($isList)
    <!-- DataTables -->
    <script defer src="/assets/plugins/datatables/datatables.min.js"></script>
  @endif
  <!-- jquery-validation -->
  <script defer src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script defer src="/assets/plugins/jquery-validation/localization/messages_ja.min.js"></script>
  <script defer src="/assets/plugins/jquery-validation/additional-methods.min.js"></script>

  <!-- Summernote -->
  <script defer src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
  <script defer src="/assets/plugins/summernote/lang/summernote-ja-JP.min.js"></script>

  <!-- AdminLTE App -->
  <script defer src="/assets/dist/admin/js/adminlte.min.js"></script>

  <!-- OPTIONAL SCRIPTS -->
  <script defer src="/assets/dist/admin/js/base.js"></script>
</head>

<body id="{{ $segments[1] }}-{{ $segments[2] }}" class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed text-sm {{ $segments[3] }}">
  <script>
    if (localStorage.getItem('remember.lte.pushmenu') == 'sidebar-collapse') document.body.classList.add('sidebar-collapse');
    const storageUrl = '{{ trim(Storage::url('.'), '.') }}';
  </script>
  <div class="wrapper r-<?php // echo implode(' r-', Auth::group('Simplegroup')->get_roles());
                        ?>">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" data-enable-remember="true" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <!-- <li class="nav-item">
          <a href="/admin/standard/home" class="nav-link">ホーム</a>
        </li> -->
        <li class="nav-item">
          <a href="/" class="nav-link" target="_blank">フロント表示</a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="/admin/standard/home" class="brand-link">
        <img src="/assets/dist/admin/img/logo.png" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">マネジメント</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="overflow: unset;">
          <div class="image">
            <img src="/assets/dist/admin/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block dropdown-toggle dropdown-icon" data-toggle="dropdown"><span class="mr-2">{{ $admin['name'] }}</span></a>
            <div class="dropdown-menu">
              <a class="dropdown-item text-primary" href="/admin/guard/logout">ログアウト</a>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" id="nav">
            <li class="nav-item">
              <a href="/admin/standard/user/list" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  会員管理
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/standard/order/list" class="nav-link">
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>
                  発注管理
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/standard/moderator/list" class="nav-link">
                <i class="nav-icon fas fa-users-cog"></i>
                <p>
                  管理者管理
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/email/template/list" class="nav-link">
                <i class="nav-icon fas fa-envelope"></i>
                <p>
                  メールテンプレート
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/requirement/setup/detail" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                  設定管理
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <?php if ($isList) : ?>
  <div class="modal fade" id="setting-modal" tabindex="-1" role="dialog" aria-labelledby="setting-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="setting-modal-label">検索結果の表示設定</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group" id="setting-length">
            <label>表示件数</label>
            <div>
              <div class="custom-control custom-radio custom-control-inline">
                <input value="10" class="custom-control-input" type="radio" id="length10" name="length">
                <label for="length10" class="custom-control-label">10</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input value="25" class="custom-control-input" type="radio" id="length25" name="length">
                <label for="length25" class="custom-control-label">25</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input value="50" class="custom-control-input" type="radio" id="length50" name="length">
                <label for="length50" class="custom-control-label">50</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline">
                <input value="100" class="custom-control-input" type="radio" id="length100" name="length">
                <label for="length100" class="custom-control-label">100</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>表示項目</label>
            <ul class="row p-0" id="setting-item" style="list-style-type: none;"></ul>
          </div>
        </div>
        <div class="modal-footer justify-content-start">
          ここで設定した内容はブラウザに保存されます。次回表示時も有効です。
        </div>
      </div>
    </div>
  </div>
  <?php else : ?>
  <!-- Modal -->
  <div class="modal fade" id="del-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">削除</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          本当に選択したデータを削除してもよいですか ?
        </div>
        <div class="modal-footer">
          <button type="button" id="btn-del" class="btn btn-danger"><i class="fas fa-trash-alt"></i> 削除を実行</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> キャンセル</button>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

</body>

</html>