@extends('layouts.site')

@section('content')
    <div class="max-w-md p-6 mx-auto mt-12 bg-white rounded shadow">
        <h1 class="mb-4 text-xl font-bold">Verificação de E-mail</h1>

        @if (session('success'))
            <div class="p-2 mb-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('email-verification.verify') }}" method="POST">
            @csrf
            <label class="block mb-2 font-medium">Digite o código recebido:</label>
            <input type="text" name="code" class="w-full p-2 mb-4 border rounded" required>

            @error('code')
                <p class="mb-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                Verificar
            </button>
        </form>



        <form action="{{ route('email-verification.send') }}" method="POST">
            @csrf

            <p class="mb-4">
                Clique no botão abaixo para receber um código de verificação no seu e-mail.
            </p>

            <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700">
                Enviar Código
            </button>
        </form>
    </div>
@endsection
