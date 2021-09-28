<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a href="{{route('login')}}">
                <img src="{{ asset('assets/media/logos/logo.png') }}" class="max-h-75px" alt="Siplah Klikmro"/>
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Mohon maaf, kelihatannya akun Anda telah terdaftar pada sistem basis data kami, tapi tipe akun Anda tidak memiliki akses untuk aplikasi ini.') }}
        </div>

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Keluar') }}
                </button>
            </form>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>
