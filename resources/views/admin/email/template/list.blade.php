@extends('admin.template')
@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex" id="search-form">
        <div class="input-group">
          <input type="search" class="form-control" placeholder="件名" id="form-simple">
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
              <th>タイトル</th>
              <th>件名</th>
              <th>登録日</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<footer class="main-footer">
  <div class="row">
    <div class="col-6">
      <button class="btn btn-default" data-toggle="modal" data-target="#setting-modal"><i class="fas fa-sliders-h"></i> 表示設定</button>
    </div>
    <div class="col-6 text-right">
      <a id="btn-new" href="/admin/email/template/new" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> 新規登録</a>
    </div>
  </div>
</footer>

<script>
  var tableConfig = {
    'columns': [{
        data: 'title',
        render: function(data, type, row, meta) {
          return '<a href="/admin/email/template/detail/' + row['id'] + '">' + data + '</a>';
        }
      },
      {
        data: 'subject'
      },
      {
        data: 'created_at'
      }
    ]
  };
</script>
@endsection