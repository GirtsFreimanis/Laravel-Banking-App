<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trade')." $cryptoCurrency->symbol" }}
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
                    select account

                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-6">

                                <form>
                                    <h2>Form 1</h2>
                                    <div class="form-group">
                                        <input type="text">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>

                            <div class="col-md-6">
                                <form>
                                    <h2>Form 2</h2>
                                    <div class="form-group">
                                        <input type="text">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        
    </script>
</x-app-layout>
