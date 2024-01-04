<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crypto market') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('crypto.search') }}" method="get"
                          id="transaction-history-form">
                        @csrf
                        <div class="w-25">
                            <div class="col-md-6 order-md-1 w-100">
                                <div class="mb-3">
                                    <label for="search_crypto" class="form-label">Search for Cryptocurrency:</label>
                                    <input class="form-control" id="search_crypto" name="search_crypto" type="text">
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

                <div class="container">

                    <div class="row">
                        @if(!count($cryptoCurrencies))
                            <div class="d-flex align-items-center justify-content-center">
                                <p>Nothing found...</p>
                            </div>
                        @else
                            <div class="col">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>Symbol</th>
                                        <th>Price</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cryptoCurrencies as $crypto)
                                        <tr>
                                            <td>{{ $crypto->symbol }}</td>
                                            <td>{{ number_format($crypto->price,8,".","") }}</td>
                                            <td class="text-center">
                                                <a href="{{route("crypto.trade", ['symbol' => $crypto->symbol])}}">
                                                    <button type="button" class="btn btn-primary">
                                                        Trade
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex">
                                    {!! $cryptoCurrencies->links() !!}
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>
    </div>
    <script>

    </script>
</x-app-layout>
