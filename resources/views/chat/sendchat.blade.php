@extends('layouts.app')

@section('content')

<div class="container">
    <ul>
        @foreach($users as $user)
        <li><a href="{{ route('conversations.show', $user->id) }}">{{ $user->name }}</a></li>
        @endforeach
    </ul>
</div>
@endsection
