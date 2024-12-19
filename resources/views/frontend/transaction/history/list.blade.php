@extends('frontend.template')

@section('content')
  <div class="col-md-12 m-auto">
    <div class="card">
      <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <h3 class="card-title mb-0 font-weight-bold">取引履歴</h3>
        <div class="btn-group transaction-history">
          <button type="button" title="week view" aria-pressed="true" class="fc-timeGridWeek-button btn btn-primary active">週</button>
          <button type="button" title="month view" aria-pressed="false" class="fc-dayGridMonth-button btn btn-primary">月</button>
        </div>
      </div>
      <!-- /.card-header -->
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
          <button class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" id="search-toggle" style="height: fit-content;">
            <i class="fas fa-filter"></i> 高度な検索
          </button>
          <div class="dropdown-menu p-2 end-50" id="form-advanced">
            <form class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="id">ID</label>
                  <input name="id" id="id" class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="created_at">施行日</label>
                  <input type="text" name="created_at" id="created_at" class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="details">注文内容</label>
                  <input name="details" id="details" class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="status">約定結果</label>
                  <input name="status" id="status" class="form-control">
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
              <th>施行日</th>
              <th>注文内容</th>
              <th>約定結果</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
  <!-- DataTables -->
  <script defer src="/assets/plugins/datatables/datatables.min.js"></script>
  <script defer src="/assets/dist/js/mypage.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#created_at').datetimepicker({
        format: 'YYYY-MM-DD',
      });

      $('.transaction-history button').on('click', function(e) {
        $('.transaction-history button').removeClass('active');
        $(this).addClass('active');
      });
    })

    var tableConfig = {
      buttons: [],
      columns: [{
          data: 'id',
        },
        {
          data: 'created_at'
        },
        {
          data: 'details'
        },
        {
          data: 'status'
        }
      ]
    };
  </script>
@endsection
