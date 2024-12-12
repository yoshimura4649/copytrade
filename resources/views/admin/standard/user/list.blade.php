@extends('admin.template')
@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header d-flex" id="search-form">
        <div class="input-group">
          <input type="search" class="form-control" placeholder="氏名・メールアドレス" id="form-simple">
          <div class="input-group-append">
            <button class="btn btn-outline-primary" id="search-simple">
              <i class="fas fa-search"></i><span> シンプルな検索</span>
            </button>
          </div>
        </div>

        <div class="btn-group position-static">
          <button class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" id="search-toggle">
            <i class="fas fa-filter"></i> 高度な検索
          </button>
          <div class="dropdown-menu" id="form-advanced">
            <form class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="name">氏名</label>
                  <input name="name" id="name" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="email">メールアドレス</label>
                  <input name="email" id="email" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="tel">電話番号</label>
                  <input name="tel" id="tel" class="form-control">
                </div>
              </div>
            </form>
            <div class="text-center">
              <button class="btn btn-default"><i class="fas fa-times"></i> 閉じる</button>
              <button class="btn btn-default" id="search-clear"><i class="fas fa-eraser"></i> クリア</button>
              <button class="btn btn-primary" id="search-advanced"><i class="fas fa-filter"></i> 検索する</button>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body">
        <table id="list-table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>氏名</th>
              <th>メールアドレス</th>
              <th>電話番号</th>
              <th>登録日</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>

<footer class="main-footer">
  <div class="row">
    <div class="col-6">
      <button class="btn btn-default" data-toggle="modal" data-target="#setting-modal"><i class="fas fa-sliders-h"></i> 表示設定</button>
    </div>
    <div class="col-6 text-right">
      <a id="btn-new" href="/admin/standard/user/new/" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> 新規登録</a>
    </div>
  </div>
</footer>

<script>
  var tableConfig = {
    buttons :[],
    columns: [{
        data: 'name',
        render: function(data, type, row, meta) {
          return '<a href="/admin/standard/user/detail/' + row['id'] + '">' + data + '</a>';
        }
      },
      {
        data: 'email'
      },
      {
        data: 'tel'
      },
      {
        data: 'created_at'
      }
    ]
  };
</script>
@endsection