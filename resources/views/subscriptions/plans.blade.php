@extends('layouts.app')

@section('content')
    @include('site._partials.plans', ['plans' => $plans])
@endsection
