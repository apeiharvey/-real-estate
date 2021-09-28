@if ($errors->any())
    <div  class="mb-4 alert alert-danger" style="max-width: 500px;text-align: left;">
        <div class="font-medium text-red-600">{{ __('Maaf, terjadi kesalahan sebagai berikut.') }}</div>
        <ul class="mt-3 list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
