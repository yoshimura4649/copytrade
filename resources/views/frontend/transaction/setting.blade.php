@extends('frontend.template')

@section('content')
  @php
    use Carbon\Carbon;

    $user = Auth::user();
    $transactionStatus = $user->transaction_status == TransactionStatus::Active->value;
    $expirationDate = $user->transaction_updated_at ? Carbon::parse($user->transaction_updated_at)->addMonth() : null;
    $buttonEnabled = abs($expirationDate->diffInDays(Carbon::now())) <= 7 ? true : false;
  @endphp
  <div class="col-md-12 m-auto">
    <div class="card card-warning card-outline">
      <div class="card-header">
        <h5 class="m-0 font-weight-bold">売買設定</h5>
      </div>
      <div class="card-body">
        <h6 class="card-title">
          @if (!$transactionStatus)
            登録してください
          @else
            登録しました
          @endif
        </h6>
        <p class="card-text">「登録する」を押した瞬間から一日がカウントされます<br>そして30日後に期限切れになります</p>
        <form method="POST">
          @csrf
          <button type="submit" class="btn btn-warning {{ $buttonEnabled ? '' : 'disabled' }}" {{ $buttonEnabled ? '' : 'disabled' }}>
            登録する
          </button>
        </form>
        @if ($expirationDate)
          <h6 class="card-title mt-2">
            有効期限: {{ $expirationDate->format('d/m/Y') }}
          </h6>
        @endif
      </div>
    </div>
  </div>
@endsection
