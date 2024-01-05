<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My portfolio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('crypto.sellcrypto') }}" method="get"
                          id="transaction-history-form">
                        @csrf
                        <div class="w-25">
                            <div class="col-md-6 order-md-1 w-100">
                                <div class="mb-3">
                                    <label for="search_account" class="form-label">Select Account:</label>
                                    <select name="search_account" id="search_account" class="form-select"
                                            style="width: 250px;">
                                        <option value="" disabled selected></option>
                                        @if(isset($accounts))
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->IBAN }}"

                                                        data-balance="{{ $account->balance }}"
                                                        data-currency="{{ $account->currency }}"
                                                >{{ $account->IBAN }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="text-danger" id="accountError">
                                    @if($errors->has('search_account'))
                                            {{$errors->first('search_account')}}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>
            <br>
            <div>
                @if(isset($investments))

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Symbol</th>
                                    <th>Amount</th>
                                    <th>Current price</th>
                                    <th>Bought at</th>
                                    <th>value</th>
                                    <th>gain</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($investments as $investment)
                                    <tr>
                                        <td>{{$investment->symbol}}</td>
                                        <td>{{substr($investment->amount,0,12)}}</td>
                                        <td>{{substr($investment->price,0,12)}}</td>
                                        <td>{{substr($investment->bought_at,0,12)}}</td>
                                        <td>{{$investment->value}}</td>
                                        <td>
                                            <span @if($investment->gain<0)class="text-danger" @else class="text-success" @endif>
                                                @if($investment->gain>0)
                                                    +
                                                @endif
                                                {{$investment->gain}}%
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{route("crypto.sell.transaction", ['investment' => $investment])}}">
                                                <button type="button" class="btn btn-danger">
                                                    Sell
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                @endif
            </div>
        </div>
    </div>
    <script>
        document.getElementById('transaction-history-form').addEventListener('submit', (event) => {
            const account = document.getElementById('search_account');
            const accountError = document.getElementById('accountError');

            let isValid = true;

            if (!account.value.trim()) {
                accountError.textContent = 'Please select an account';
                isValid = false
            } else {
                accountError.textContent = '';
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</x-app-layout>
