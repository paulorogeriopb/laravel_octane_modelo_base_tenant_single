@extends('layouts.app')

@section('content')
    <div class="content-box">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>



    <div class="mt-12 content-box">
        <x-alert />
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>
@endsection
