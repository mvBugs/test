@extends('layouts.app')

@section('content')
    @php
        $text = json_encode('fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf
        dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg fg
        fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf
        gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf
        dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf
        gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg
        fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg
        fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg
        fg fsd dsf gdsfghdshfgsdf dsfhg fg fsd dsf gdsfghdshfgsdf dsfhg');
    $points = [
        ['lat' => 50.147441, 'lng' => 25.125092, 'text' => '<p> Point 1</p>', 'title' => 'Test 1'],
        ['lat' => 50.247441, 'lng' => 25.225092, 'text' => '<p> Point 2</p>', 'title' => 'Test 2'],
        ['lat' => 50.347441, 'lng' => 25.325092, 'text' => '<p> Point 3</p>', 'title' => 'Test 3'],
        ['lat' => 50.447441, 'lng' => 25.425092, 'text' => '<p> Point 4</p>', 'title' => 'Test 4'],
        ['lat' => 50.547441, 'lng' => 25.025092, 'text' => '<p> Point 5</p>', 'title' => 'Test 5'],
    ];
    @endphp
    <div class="box" id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">

                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'MAp') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        <div class="content">
            <sidebar-component
            ></sidebar-component>
            <map-component
                {{--:points="{{ json_encode($points) }}"--}}
                @auth
                    :user="{{ auth()->user() }}"
                @endauth
            ></map-component>
        </div>
        <modal-form-component></modal-form-component>

    </div>
@endsection
