<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'Название', ['class' => 'control-label']) !!}
    {!! Form::text('title', isset($point) ? $point->title : null, ['class' => 'form-control', 'required']) !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Form::label('description', 'Опис', ['class' => 'control-label']) !!}
    {!! Form::textarea('description', isset($point) ? $point->description : null, ['class' => 'form-control', 'required', 'rows' => 2]) !!}
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>

<div class="form-group {{ $errors->has('lat') ? 'has-error' : ''}}">
    {!! Form::label('lat', 'Широта', ['class' => 'control-label']) !!}
    {!! Form::number('lat', isset($point) ? $point->lat : null, ['class' => 'form-control', 'step' => '0.00000000001']) !!}
    {!! $errors->first('lat', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('lng') ? 'has-error' : ''}}">
    {!! Form::label('lng', 'Довгота', ['class' => 'control-label']) !!}
    {!! Form::number('lng', isset($point) ? $point->lng : null, ['class' => 'form-control', 'step' => '0.00000000001']) !!}
    {!! $errors->first('lng', '<p class="help-block">:message</p>') !!}
</div>
@include('admin.fields.field-images-uploaded',[
     'label' => 'Images',
     'field_name' => 'images',
     'entity' => isset($point) ? $point : null,
])

@include('admin.fields.field-form-buttons', [
    'url_store_and_create' => route('admin.points.create'),
    'url_store_and_close' => session('admin.points.index'),
    'url_destroy' => isset($item) ? route('admin.points.destroy', $item) : '',
    'url_after_destroy' => session('admin.points.index'),
    'url_close' => session('admin.points.index'),
])


