@extends('layouts.app')

@section('title', "Error {{ $status ?? 500 }}")

@section('content')
<div class="container mt-5 text-center">
    <h1 class="display-4 text-danger">Error {{ $status ?? 500 }}</h1>
    <p class="lead">{{ $message ?? 'An unexpected error occurred.' }}</p>
    
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Go Back to Home</a>
</div>
@endsection
