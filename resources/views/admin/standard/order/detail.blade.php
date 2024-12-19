@extends('admin.template')
@section('content')
  @if (!empty($orderDetail))
    <div class="card card-default">
      <div class="card-header">
        <h3 class="card-title">注文内容</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="ID">受付番号</label>
                <input value="{{ $orderDetail['ID'] }}" id="ID" type="text" class="form-control" disabled readonly>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="side">売買</label>
                <input value="{{ \App\Enums\Side::tryFrom($orderDetail['Side'])?->label() ?? '' }}" id="side" type="text"
                  class="form-control" disabled readonly>
              </div>
              <div class="form-group col-md-6">
                <label for="symbol">コード</label>
                <input value="{{ $orderDetail['Symbol'] }}" id="symbol" type="text" class="form-control" disabled readonly>
              </div>
            </div>
            <div class="form-group">
              <label for="ExchangeName">市場等</label>
              <input value="{{ $orderDetail['ExchangeName'] }}" id="ExchangeName" type="text" class="form-control" disabled readonly>
            </div>
            <div class="form-group">
              <label for="SymbolName">銘柄</label>
              <input value="{{ $orderDetail['SymbolName'] }}" id="SymbolName" type="text" class="form-control" disabled readonly>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="Qty">注文中 / 約定数量</label>
              <input value="{{ $orderDetail['OrderQty'] }} 口 / {{ $orderDetail['CumQty'] }}口" id="Qty" type="text"
                class="form-control" disabled readonly>
            </div>
            <div class="form-group">
              <label for="RecvTime">注文受付日</label>
              @php
                $recvTime = new DateTime($orderDetail['RecvTime']);
                $recvTime = $recvTime->format('Y-m-d H:i:s');
              @endphp
              <input value="{{ $recvTime }}" id="RecvTime" type="text" class="form-control" disabled readonly>
            </div>
            <div class="form-group">
              <label for="ExpireDay">有効期限</label>
              <input
                value="{{ substr($orderDetail['ExpireDay'], 0, 4) . '-' . substr($orderDetail['ExpireDay'], 4, 2) . '-' . substr($orderDetail['ExpireDay'], 6, 2) }}"
                id="ExpireDay" type="text" class="form-control" disabled readonly>
            </div>
            <div class="form-group">
              <label for="front_order_type">執行条件</label>
              <input
                value="{{ \App\Enums\FrontOrderType::tryFrom($detail['front_order_type'])?->label() ?? '' }} {{ $orderDetail['Price'] ? $orderDetail['Price'] . '円' : '' }}"
                id="front_order_type" type="text" class="form-control" disabled readonly>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-default">
      <div class="card-header">
        <h3 class="card-title">注文状態</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
      </div>

      <!-- /.card-header -->
      <div class="card-body">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="table-secondary">
                <tr class="text-center">
                  <th scope="col" class="align-middle">内容<br>（市場）</th>
                  <th scope="col" class="align-middle">状態</th>
                  <th scope="col" class="align-middle">単価</th>
                  <th scope="col" class="align-middle">数量</th>
                  <th scope="col" class="align-middle">約定金額<br>受渡日</th>
                  <th scope="col" class="align-middle">手数料<br>消費税</th>
                  <th scope="col" class="align-middle">更新日時</th>
                </tr>
              </thead>
              <tbody>
                @if (count($orderDetail['Details']))
                  @foreach (array_reverse($orderDetail['Details']) as $detail)
                    @php
                      $transactTime = new DateTime($detail['TransactTime']);
                      $transactTime = $transactTime->format('Y-m-d H:i:s');
                    @endphp
                    <tr>
                      <td class="align-middle text-center">{{ \App\Enums\RecType::tryFrom($detail['RecType'])?->label() ?? '' }}</td>
                      <td class="align-middle text-center">{{ \App\Enums\DetailState::tryFrom($detail['State'])?->label() ?? '' }}</td>
                      <td class="align-middle text-right">{{ $detail['Price'] ? $detail['Price'] . ' 円' : '' }} </td>
                      <td class="align-middle text-right">{{ $detail['Qty'] ? $detail['Qty'] . '口' : '' }}</td>
                      <td class="align-middle text-right">{{ $detail['Price'] ? $detail['Price'] . ' 円' : '' }}
                        <br>{{ $detail['Price'] ? substr($detail['DelivDay'], 0, 4) . '-' . substr($detail['DelivDay'], 4, 2) . '-' . substr($detail['DelivDay'], 6, 2) : '' }}
                      </td>
                      <td class="align-middle text-right">{{ $detail['Commission'] ? $detail['Commission'] . '円' : '' }}
                        <br>{{ $detail['CommissionTax'] ? $detail['CommissionTax'] . '円' : '' }}
                      </td>
                      <td class="align-middle text-center">{{ $transactTime }}</td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="3" class="text-center">一致するレコードがありません</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  @else
    {{ $errors ? $errors : '該当する注文約定詳細はありません。' }}
  @endif
  <footer class="main-footer">
    <div class="row">
      <div class="col-6">
        <a href="/admin/standard/order/list" id="btn-list-back" class="btn btn-link pl-1"><i class="fa fa-backward"></i><span>
            一覧に戻る</span></a>
      </div>
    </div>
  </footer>
@endsection
