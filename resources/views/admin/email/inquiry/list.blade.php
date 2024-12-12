@extends('admin.template')
@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header d-flex" id="search-form">
        <div class="input-group">
          <input type="search" class="form-control" placeholder="お問い合わせ内容・メモ" id="form-simple">
          <div class="input-group-append">
            <button class="btn btn-outline-primary" id="search-simple">
              <i class="fas fa-search"></i><span> シンプルな検索</span>
            </button>
          </div>
        </div>
      </div>

      <div class="card-body">
        <table id="list-table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>名前</th>
              <th>対応状況</th>
              <th>メールアドレス</th>
              <th>更新日</th>
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
  </div>
</footer>

<script>
  var tableConfig = {
    buttons :[],
    columns: [{
        data: 'name',
        render: function(data, type, row, meta) {
          return '<a href="/admin/email/inquiry/detail/' + row['id'] + '">' + data + '</a>';
        }
      },
      {
        data: 'status',
        render: function(data, type, row, meta) {
          return data == 1 ? '対応済み' : '未対応';
        }
      },
      {
        data: 'email'
      },
      {
        data: 'updated_at'
      }
    ]
  };
</script>
@endsection