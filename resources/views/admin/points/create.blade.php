@extends('admin.layouts.app')

@php
    $content_header = [
        'page_title' => 'Points',
        'url_back' => session('admin.points.index'),
        'urlCreate' => ''
    ]
@endphp

@section('content')
<section class="content">
    <div class="box">
        <div class="box-header">
            <div class="row">
                <div class="col-lg-8">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Add Point</h3>

                </div>
            </div>
        </div>
        <div class="box-body">
            {!! Form::open([
                 'route' => 'admin.points.store',
                 'files' => true
            ]) !!}
            @include('admin.points._form')
            {!! Form::close() !!}
        </div>

    </div>
</section>
@stop
