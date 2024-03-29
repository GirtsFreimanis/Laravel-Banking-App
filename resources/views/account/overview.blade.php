<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @foreach($accounts as $account)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="container">
                            <div class="row">
                                <div class="col">{{$account->IBAN}}</div>
                                <div
                                    class="col">{{$account->currency}} {{number_format($account->balance/100,2,".","")}}</div>
                                <div class="col">{{$account->type}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</x-app-layout>
