<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'ホーム' }} | CopyTrade</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/assets/dist/admin/css/adminlte.min.css">
  <!-- Datetimepicker -->
  <link rel="stylesheet" href="/assets/plugins/bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css">
  <!--Daterangepicker -->
  <link rel="stylesheet" href="/assets/plugins/daterangepicker/daterangepicker.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/assets/plugins/datatables/datatables.min.css">
  <!-- CustomCss -->
  <link rel="stylesheet" href="/assets/dist/css/mypage.css">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
  <!-- Bootstrap 4 -->
  <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="/assets/dist/admin/js/adminlte.min.js?v=3.2.0"></script>
  <!-- jQuery Validation -->
  <script defer src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script defer src="/assets/plugins/jquery-validation/additional-methods.min.js"></script>
  <script defer src="/assets/plugins/jquery-validation/localization/messages_ja.min.js"></script>
  <!-- datetime-picker -->
  <script defer src="/assets/plugins/moment/moment.min.js"></script>
  <script defer src="/assets/plugins/moment/locale/ja.js"></script>
  <!-- Daterangepicker -->
  <script defer src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Datetimepicker -->
  <script defer src="/assets/plugins/bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
  <!-- ChartJS -->
  <script src="/assets/plugins/chart.js/Chart.min.js"></script>
</head>

<body class="hold-transition layout-top-nav pt-2">
  <div class="wrapper pt-5">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white position-fixed w-100 fixed-top">
      <div class="container">
        <button class="navbar-toggler order-0" type="button" data-toggle="collapse" data-target="#navbarCollapse"
          aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a href="{{ route('mypage') }}" class="navbar-brand">
          <span class="brand-text font-weight-bold text-warning">CopyTrade</span>
        </a>
        @auth
          <div class="navbar-collapse order-3 collapse" id="navbarCollapse" style="">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
              <li class="nav-item font-weight-bold">
                <a class="nav-link px-2" href="{{ route('account') }}">
                  アカウント設定画面
                </a>
              </li>
              <li class="nav-item font-weight-bold">
                <a class="nav-link px-2" href="{{ route('transaction_history_list') }}">
                  取引履歴画面
                </a>
              </li>
              <li class="nav-item font-weight-bold">
                <a class="nav-link px-2" href="{{ route('transaction_setting') }}">
                  売買設定
                </a>
              </li>
            </ul>
          </div>
        @endauth

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand">
          @auth
            <li class="nav-item dropdown mr-3">
              <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="fas fa-envelope mr-2"></i> 4 new messages
                  <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="fas fa-users mr-2"></i> 8 friend requests
                  <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="fas fa-file mr-2"></i> 3 new reports
                  <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
              </div>
            </li>
            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="btn btn-block btn-default" href="{{ route('logout') }}">
                <label>ログアウト</label>
                <i class="fa-solid fa-right-from-bracket ml-1"></i>
              </a>
            </li>
          @else
            <li class="nav-item dropdown">
              <a class="btn btn-block btn-default" href="{{ route('login') }}">
                <label>ログイン</label>
                <i class="fa-solid fa-right-to-bracket ml-1"></i>
              </a>
            </li>
          @endauth
        </ul>
      </div>
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">{{ $title ?? 'ホーム' }}</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                @isset($title)
                  <li class="breadcrumb-item"><a href="{{ route('mypage') }}">ホーム</a></li>
                  <li class="breadcrumb-item active">{{ $title }}</li>
                @endisset
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container">
          <div class="row pt-3">
            @yield('content')
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>

    <footer class="main-footer text-center">CopyTrade
    </footer>
  </div>
  @if ($errors->any())
    <script>
      const errors = @json($errors->all());
      let errorMessage = "通知:\n";
      errors.forEach((error) => {
        errorMessage += `"${error}"\n`;
      });
      alert(errorMessage);
    </script>
  @endif
</body>

</html>
