<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction history') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('transaction.history.search') }}" method="get"
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

                        <div class="mb-3 w-25">
                            <label for="date_from" class="form-label">Date From:</label>
                            <input type="date" name="date_from" id="date_from" class="form-control">
                        </div>

                        <div class="mb-3 w-25">
                            <label for="date_to" class="form-label">Date To:</label>
                            <input type="date" name="date_to" id="date_to" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>

            <br>
            @if(isset($transactions))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at }}</td>
                                                <td>
                                                    @if($search_account == $transaction->account_to)
                                                        {{ $transaction->account_from }}
                                                    @else
                                                        {{ $transaction->account_to }}
                                                    @endif
                                                </td>
                                                @if($search_account == $transaction->account_from)
                                                    <td class="text-danger">- {{$transaction->amount}}</td>
                                                @else
                                                    <td class="text-success">+ {{$transaction->exchanged_amount}}</td>
                                                @endif
                                                @if($search_account == $transaction->account_from)
                                                    <td>{{$transaction->account_from_balance}}</td>
                                                @else
                                                    <td>{{$transaction->account_to_balance}}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
        </div>
    </div>
    <script>

        /*document.getElementById('search_account').addEventListener('change', function () {
            document.getElementById("balanceShow").style.display = "flex";

            let selectedOption = this.options[this.selectedIndex];

            let balance = selectedOption.getAttribute('data-balance');
            let currency = selectedOption.getAttribute('data-currency');

            document.getElementById('account_currency').textContent = currency;
            document.getElementById('account_balance').textContent = balance;
        });*/

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
