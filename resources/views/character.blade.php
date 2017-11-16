@extends('layouts.app')
@section('nav')
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('home') }}">
                    Buscador Marvel
                </a>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <!-- TODO: Add link to home. -->
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="content">
                <h1>{{ $character['name'] }}</h1>
            </div>
        </div>
    </div>
    <div class="row character">
        <div class= "col-md-2">
            <img src="{{ $character['thumbnail']['path'] }}.{{ $character['thumbnail']['extension'] }}" alt="{{ $character['name'] }}" class="img img-responsive img-thumbnail">
        </div>
        <div class="col-md-10 description">
            <p>{{ $character['description'] }}</p>
        </div>
    </div>
    <div class="row comics">
        @foreach ($comics as $comic)
            <div class="col-md-3 col-xs-12">
                <img src="{{ $comic['thumbnail']['path'] }}.{{ $comic['thumbnail']['extension'] }}" alt="{{ $comic['title'] }}" class="img img-responsive img-thumbnail">
                <p>{{ $comic['title'] }}</p>
                <p>{{ (new Carbon\Carbon($comic['dates'][0]['date']))->format('d/m/Y') }}</p>
            </div>
        @endforeach
    </div>
    <div class="row comics-links">
        <div class="col-md-6 col-md-offset-3">
            {{ $comics->links() }}
        </div>
    </div>
@endsection
