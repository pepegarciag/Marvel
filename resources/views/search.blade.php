@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="search col-md-6 col-md-offset-3">
            <div class="content">
                <h1>Buscador de personajes Marvel</h1>

                @if (Session::has('character'))
                    <p class="error bg-warning text-warning">{{ session('character') }}</p>
                @endif

                <form action="{{ route('search') }}" method="get" class="form-inline">
                    <div class="form-group col-md-9 {{ $errors->has('character') ? 'has-error' : '' }}">
                        <input type="text" class="form-control" placeholder="Buscar..." name="character" id="character" required>
                        @if ($errors->has('character'))
                            <p class="error bg-danger text-danger">{{$errors->first('character')}}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection