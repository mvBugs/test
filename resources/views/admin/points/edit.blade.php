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
                <div class="col-lg-12">
                    <i class="ion ion-clipboard"></i>
                    <h3 class="box-title">Edit point<strong>{{ $point->title }}</strong></h3>
                </div>
            </div>
        </div>
        <div class="box-body">
            <!--tabs-->
            <div class="nav-tabs-justified">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <br>
                            {!! Form::model($point, [
                                'method' => 'PATCH',
                                'route' => ['admin.points.update', $point],
                                'files' => true
                            ]) !!}
                            @include('admin.points._form')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div><!--/tabs-->
        </div>

    </div>
</section>
@stop
