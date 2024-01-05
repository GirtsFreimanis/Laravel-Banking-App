<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buy')." $crypto->symbol" }}
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
                            You Currently do not have an Investment Account.
                            Click <a href="{{ route('account.create') }}">here</a>
                            to open a new account and select Account type: Investment
                        </div>
                    </div>
                </div>
            </div>
            <br>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="row">
                        <div class="col-md-6 mt-4">

                            <form action="{{ route('cryptocurrency.store') }}" method="post" id="new-transaction-form">
                                @if(!count($accounts))
                                    <fieldset disabled="disabled">
                                        @endif
                                        @csrf
                                        <input type="hidden" name="symbol" value="{{ $crypto->symbol }}">
                                        <div>
                                            <label for="account_from" class="form-label">Select account:</label>
                                            <select name="account_from" id="account_from" class="form-select"
                                                    style="width: 250px;">
                                                <option disabled selected></option>

                                                @foreach($accounts as $account)
                                                    <option value="{{ $account->IBAN }}"
                                                            data-balance="{{ $account->balance }}"
                                                            data-cryptoPrice="{{$account->cryptoPrice}}"
                                                            data-cryptoExchangeRate="{{$account->cryptoExchangeRate}}"
                                                            data-minimumPurchase="{{$account->minimumPurchase}}"
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


                                        <div class="mb-3">
                                            <label for="amount" class="form-label">Crypto amount:</label>
                                            <div class="">
                                                <div style="width:250px;" class="input-group">
                                                    <input disabled name="amount" type="number" id="amount"
                                                           step="any" class="form-control"
                                                           value="">
                                                    <span class="input-group-text"
                                                          id="crypto_symbol">{{$crypto->symbol}}</span>
                                                </div>
                                                <span class="text-danger" id="crypto_amountError">
                                @if($errors->has('amount'))
                                                        {{$errors->first('amount')}}
                                                    @endif
                                        </span>
                                            </div>
                                        </div>


                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price:</label>
                                            <div class="">
                                                <div style="width:250px;" class="input-group">
                                                    <input disabled name="price" type="number" id="price"
                                                           step="any" class="form-control"
                                                           value="">
                                                    <span class="input-group-text" id="amount_currency"></span>
                                                </div>
                                                <span class="text-danger" id="priceError">
                                @if($errors->has('price'))
                                                        {{$errors->first('price')}}
                                                    @endif
                                        </span>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Buy</button>
                                        @if(!count($accounts))
                                    </fieldset>
                                @endif
                            </form>
                        </div>


                        <div class="col-md-6 mt-4">
                            <div class="input-group">
                                coin price:
                                <span id="crypto_price"></span>
                            </div>
                            <div class="input-group">
                                minimum purchase amount
                                <span id="minimumPurchase"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        document.getElementById('account_from').addEventListener('change', function () {
            document.getElementById("balanceShow").style.display = "flex";

            document.getElementById("price").disabled = false;
            document.getElementById("amount").disabled = false;

            let selectedOption = this.options[this.selectedIndex];

            let balance = selectedOption.getAttribute('data-balance');
            let currency = selectedOption.getAttribute('data-currency');
            let cryptoPrice = selectedOption.getAttribute('data-cryptoPrice');
            let minPur = selectedOption.getAttribute('data-minimumPurchase');

            document.getElementById('crypto_price').textContent = parseFloat(cryptoPrice).toFixed(12);
            document.getElementById('minimumPurchase').textContent = ` ${minPur}`;
            document.getElementById('amount_currency').textContent = currency;
            document.getElementById('account_currency').textContent = currency;
            document.getElementById('account_balance').textContent = balance;

            document.getElementById('amount').value = "";
            document.getElementById('price').value = "";
            document.getElementById('priceError').textContent = "";
            document.getElementById('crypto_amountError').textContent = "";
        });

        document.getElementById('new-transaction-form').addEventListener('submit', (event) => {
                let account_from = document.getElementById('account_from');
                let amount = document.getElementById('amount');
                let price = document.getElementById('price');

                let selectedOption = document.getElementById('account_from').options[document.getElementById('account_from').selectedIndex];
                let balance = selectedOption.getAttribute('data-balance');
                let minimumPurchase = selectedOption.getAttribute('data-minimumPurchase');

                let account_fromError = document.getElementById('from_accountError');
                let amountError = document.getElementById('crypto_amountError');
                let priceError = document.getElementById('priceError');

                let isValid = true

                if (!account_from.value.trim()) {
                    account_fromError.textContent = 'Please select an account';
                    isValid = false
                } else {
                    account_fromError.textContent = '';
                }

                if (!amount.value.trim()) {
                    amountError.textContent = 'Please enter an amount';
                    isValid = false;
                } else if (amount.value.trim() < 0) {
                    amountError.textContent = 'Amount cannot be negative';
                    isValid = false;
                } else {
                    amountError.textContent = '';
                }

                if (!price.value.trim()) {
                    priceError.textContent = 'Please enter an amount';
                    isValid = false;
                } else if (price.value.trim() < 0) {
                    priceError.textContent = 'Price cannot be negative';
                    isValid = false;
                } else if (!/^\d+(\.\d{1,2})?$/.test(price.value.trim())) {
                    priceError.textContent = 'More than two numbers after decimal point';
                    isValid = false;
                } else if (parseFloat(price.value.trim()) > parseFloat(balance)) {
                    console.log(typeof balance)
                    priceError.textContent = 'Price exceeds balance';
                    isValid = false;
                } else if (parseFloat(price.value.trim()) < parseFloat(minimumPurchase)) {
                    priceError.textContent = `Minimum purchase: ${minimumPurchase}`;
                    isValid = false;
                } else {
                    priceError.textContent = '';
                }

                if (!isValid) {
                    event.preventDefault();
                }
            }
        );

        document.getElementById('amount').addEventListener('input', (event) => {
            let selectedOption = document.getElementById('account_from').options[document.getElementById('account_from').selectedIndex];
            let cryptoPrice = selectedOption.getAttribute('data-cryptoPrice');

            document.getElementById("price").value = (parseFloat(document.getElementById('amount').value) * parseFloat(cryptoPrice)).toFixed(2);
        });

        document.getElementById('price').addEventListener('input', (event) => {
            this.value = parseFloat(this.value).toFixed(2)

            let selectedOption = document.getElementById('account_from').options[document.getElementById('account_from').selectedIndex];
            let cryptoExchangeRate = selectedOption.getAttribute('data-cryptoExchangeRate');

            document.getElementById("amount").value = (parseFloat(document.getElementById('price').value) * parseFloat(cryptoExchangeRate)).toFixed(12);
        });
    </script>
</x-app-layout>
