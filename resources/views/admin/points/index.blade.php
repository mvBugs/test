
@extends('admin.layouts.app')

@php
    $content_header = [
        'page_title' => 'Points',
        'small_page_title' => '',
        'url_back' => '',
        'url_create' => route('admin.points.create')
    ]
@endphp

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Points</h3>
            </div>
            <div class="box-body">
                @unless($points->count())
                    @include('admin.inc.empty-rows', ['url_create' => route('admin.points.create')])
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="width:35px;">#</th>
                                <th>Title</th>
                                <th>lat</th>
                                <th>lng</th>
                                <th style="width:100px;">Events</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($points as $point)
                                <tr>
                                    <td>{{ $point->id }}</td>
                                    <td>{{ $point->title }}</td>
                                    <td>{{ $point->lat }}</td>
                                    <td>{{ $point->lng }}</td>
                                    <td style="width: 110px">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.points.edit', $point) }}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                                            <a href="#" data-url="{{ route('admin.points.destroy', $point) }}" class="btn btn-xs btn-danger js-action-destroy"><i class="fa fa-remove"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endunless
            </div>
        </div>
    </section>
@endsection
