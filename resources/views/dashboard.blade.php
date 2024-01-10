<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @if(session()->has('success')||session()->has('error'))
            <div class="alert container
            @if(session()->has('error'))
            alert-danger
            @elseif(session()->has('success'))
            alert-success
            @endif
    alert-dismissible fade show" role="alert">
                {{session()->get('success')}}
                {{session()->get('error')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 py-3">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                New investment opportunities await!
                                <br>
                                <a href="{{route("crypto.market")}}">
                                    <button type="button" class="btn btn-primary my-3">
                                        Crypto market
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 py-3">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                Start a new transaction
                                <br>
                                <a href="{{route("new.transaction")}}">
                                    <button type="button" class="btn btn-primary my-3">
                                        New transaction
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
