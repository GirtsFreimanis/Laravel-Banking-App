<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New transaction') }}
        </h2>
    </x-slot>
    <div class="py-12">
        @if(session()->has('error'))
            <div class="alert container alert-danger
    alert-dismissible fade show" role="alert">
                {{session()->get('error')}}.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(!count($accounts))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <div>
                            You currently do not have any accounts.
                            Click <a href="{{ route('account.create') }}">here</a>
                            to open a new account.
                        </div>
                    </div>
                </div>
            </div>
            <br>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('transactions.store') }}" method="post" id="new-transaction-form">
                        @if(!count($accounts))
                            <fieldset disabled="disabled">

                                @endif
                                @csrf
                                <div class="row">

                                    <div>
                                        <label for="account_from" class="form-label">Recipient Account
                                            Number:</label>
                                        <select name="account_from" id="account_from" class="form-select"
                                                style="width: 250px;">
                                            <option disabled selected></option>
                                            <!-- Add options for accounts here -->
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->IBAN }}"
                                                        data-balance="{{ $account->balance }}"
                                                        data-currency="{{ $account->currency }}">{{ $account->IBAN }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="from_accountError">
                                        @if($errors->has('account_from'))
                                                {{ $errors->first('account_from') }}
                                            @endif
                                    </span>
                                    </div>


                                    <div>
                                        <div class="input-group" style="display:none" id="balanceShow">
                                            Balance:
                                            <span id="account_balance" class=""></span>
                                            <span id="account_currency" class=""></span>
                                        </div>
                                        <br>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="account_to" class="form-label">Recipient Account Number:</label>
                                    <input name="account_to" type="text" id="account_to" class="form-control"
                                           style="width:250px;" value="{{old('account_to')}}">
                                    <span class="text-danger" id="to_accountError">
                            @if($errors->has('account_to'))
                                            {{$errors->first('account_to')}}
                                        @endif
                            </span>
                                </div>

                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount:</label>
                                    <div class="">
                                        <div style="width:250px;" class="input-group">
                                            <input name="amount" type="text" id="amount" class="form-control"
                                                   value="{{old('amount')}}">
                                            <span class="input-group-text" id="amount_currency"></span>
                                        </div>
                                        <span class="text-danger" id="amountError">
                                @if($errors->has('amount'))
                                                {{$errors->first('amount')}}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Send Payment</button>
                                @if(!count($accounts))
                            </fieldset>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>

        document.getElementById('account_from').addEventListener('change', function () {
            document.getElementById("balanceShow").style.display = "flex";

            let selectedOption = this.options[this.selectedIndex];

            let balance = selectedOption.getAttribute('data-balance');
            let currency = selectedOption.getAttribute('data-currency');

            document.getElementById('amount_currency').textContent = currency;
            document.getElementById('account_currency').textContent = currency;
            document.getElementById('account_balance').textContent = balance;
        });

        document.getElementById('new-transaction-form').addEventListener('submit', (event) => {
                let account_from = document.getElementById('account_from');
                let account_to = document.getElementById('account_to');
                let amount = document.getElementById('amount');

                let selectedOption = document.getElementById('account_from').options[document.getElementById('account_from').selectedIndex];
                let balance = selectedOption.getAttribute('data-balance');


                let account_fromError = document.getElementById('from_accountError');
                let account_toError = document.getElementById('to_accountError');
                let amountError = document.getElementById('amountError');

                let isValid = true

                if (!account_from.value.trim()) {
                    account_fromError.textContent = 'Please select an account';
                    isValid = false
                } else {
                    account_fromError.textContent = '';
                }

                if (!account_to.value.trim()) {
                    account_toError.textContent = 'Please enter an account';
                    isValid = false
                } else if (!/^[0-9]+$/.test(account_to.value.trim())) {
                    account_toError.textContent = 'Account number must only contain numbers';
                    isValid = false
                } else if (account_to.value.trim().length !== 13) {
                    account_toError.textContent = 'Account number must be 13 digits long';
                    isValid = false
                } else if (account_to.value.trim() === account_from.value.trim()) {
                    account_toError.textContent = 'Account number must be different from the sender';
                    isValid = false
                } else {
                    account_toError.textContent = '';
                }

                if (!amount.value.trim()) {
                    amountError.textContent = 'Please enter an amount';
                    isValid = false
                } else if (isNaN(amount.value.trim())) {
                    amountError.textContent = 'Amount needs to be a number';
                    isValid = false
                } else if (parseInt(amount.value.trim()) > parseInt(balance)) {
                    amountError.textContent = 'Amount exceeds balance';
                    isValid = false
                } else {
                    amountError.textContent = '';
                }

                if (!isValid) {
                    event.preventDefault();
                }
            }
        )
    </script>
</x-app-layout>
