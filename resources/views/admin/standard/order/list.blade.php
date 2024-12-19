@extends('admin.template')
@section('content')
  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header d-flex" id="search-form">
          <div class="input-group">
            <input type="search" class="form-control" placeholder="売買区分・値段" id="form-simple">
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
                    <label for="symbol">売買区分</label>
                    <input name="symbol" id="symbol" class="form-control">
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
                <th>ID</th>
                <th>売買区分</th>
                <th>ステータス</th>
                <th>氏名</th>
                <th>銘柄コード</th>
                <th>市場コード</th>
                <th>値段</th>
                <th>発注数量</th>
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
    </div>
  </footer>

  <script>
    const orderStatusLabels = @json(collect(App\Enums\OrderStatus::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()]));
    const sideLabels = @json(collect(App\Enums\Side::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()]));

    var tableConfig = {
      buttons: [],
      columns: [{
          data: 'id',
          render: function(data, type, row, meta) {
            return '<a href="/admin/standard/order/detail/' + row['id'] + '">' + data + '</a>';
          }
        },
        {
          data: 'side',
          render: function(data, type, row, meta) {
            return sideLabels[data];
          }
        },
        {
          data: 'order_status',
          render: function(data, type, row, meta) {
            return orderStatusLabels[data];
          }
        },
        {
          data: 'user_name'
        },
        {
          data: 'symbol'
        },
        {
          data: 'exchange'
        },
        {
          data: 'price'
        },
        {
          data: 'quantity'
        },
        {
          data: 'created_at'
        }
      ]
    };
  </script>
@endsection
