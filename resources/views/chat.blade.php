@extends('layouts.app')

@section('content')
    <div class="container h-100">
        <div class="row h-100 mb-4">
            <chat-component></chat-component>
            <users-component></users-component>
        </div>
    </div>
@endsection
