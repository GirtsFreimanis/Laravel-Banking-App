<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Open a New Account') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('account.store') }}" method="post">
                        @csrf

                        <div class="mb-3">
                            <label for="currency" class="form-label">Select Account Currency:</label>
                            <select name="currency" id="currency" class="form-select" style="width:250px;">
                                <option value="" disabled selected></option>
                                @foreach($currencies as $currency => $d)
                                    <option value="{{ $currency }}">{{ $currency }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Select Account type:</label>
                            <select name="type" id="type" class="form-select" style="width:250px;">
                                <option value="" disabled selected></option>
                                <option value="debit">Debit</option>
                                <option value="credit">Credit</option>
                                <option value="investment">Investment</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Create account</button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
