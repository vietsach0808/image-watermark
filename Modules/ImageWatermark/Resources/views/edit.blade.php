@extends('imagewatermark::layouts.master')

@section('content')
    <div class="container mt-5 iw-container">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{$error}}</div>
            @endforeach
        @endif
        <form action="@if(isset($iwImage)){{route('iw.edit', $iwImage->id)}}@else{{route('iw.store')}}@endif"
              method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">@lang('imagewatermark::iw.title') <span style="color: red">*</span></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="title" name="title"
                           placeholder="@lang('imagewatermark::iw.enter_title')"
                           value="{{old('title', isset($iwImage) ? $iwImage->title : '')}}" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="description" class="col-sm-2 col-form-label">@lang('imagewatermark::iw.description')</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="description" name="description"
                           placeholder="@lang('imagewatermark::iw.enter_description')"
                           value="{{old('description', isset($iwImage) ? $iwImage->description : '')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="file" class="col-sm-2 col-form-label">@lang('imagewatermark::iw.status')</label>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="active" id="active_publish" value="1"
                               @if(old('active', isset($iwImage) ? $iwImage->active : 1)) checked @endif>
                        <label class="form-check-label" for="active_publish">
                            @lang('imagewatermark::iw.publish')
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="active" id="active_private" value="0"
                               @if(!old('active', isset($iwImage) ? $iwImage->active : 1)) checked @endif>
                        <label class="form-check-label" for="active_private">
                            @lang('imagewatermark::iw.private')
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="image" class="col-sm-2 col-form-label">@lang('imagewatermark::iw.image')</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" id="image" name="image">
                    <div @if(!isset($iwImage)) style="display: none" @endif class="iw-preview">
                        <hr>
                        <h3>@lang('imagewatermark::iw.preview')</h3>
                        <div class="form-group">
                            <label for="fontsize">@lang('imagewatermark::iw.fontsize')</label>
                            <select class="form-control" id="fontsize" name="font_size">
                                @php
                                    $dataFontSize = old('font_size', isset($iwImage) ? $iwImage->font_size : 14);
                                @endphp
                                @foreach($listFontSize as $fontSize)
                                    <option value="{{$fontSize}}" @if($dataFontSize == $fontSize) selected @endif>{{$fontSize}}px</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fontsize">@lang('imagewatermark::iw.sample') <span class="font-italic">(@lang('imagewatermark::iw.not_save'))</span></label>
                            <input class="form-control input-preview-text" value="0123456789">
                        </div>
                        <div class="font-italic">@lang('imagewatermark::iw.image_info')</div>
                        <input type="hidden" class="form-control" name="horizontal" value="{{old('horizontal', isset($iwImage) ? $iwImage->horizontal : 0)}}">
                        <input type="hidden" class="form-control" name="vertical" value="{{old('vertical', isset($iwImage) ? $iwImage->vertical : 0)}}">
                        <input type="hidden" class="form-control" name="background" value="{{old('background', isset($iwImage) ? $iwImage->background : 0)}}">
                        <div class="image-box mt-3">
                            <img src="@if(isset($iwImage)){{asset('images/iw/'.$iwImage->image)}}@endif" alt="" class="preview-img" id="preview-img">
                            <div class="preview-text" @if(isset($iwImage)) style="top: {{$iwImage->vertical}}px;left: {{$iwImage->horizontal}}px;font-size: {{$iwImage->font_size}}px;@if($iwImage->background)background:#fff;@endif" @endif>0123456789</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a class="btn btn-secondary" href="{{route('iw.index')}}">@lang('imagewatermark::iw.cancel')</a>
                <button class="btn btn-primary" type="submit">@lang('imagewatermark::iw.save')</button>
            </div>
        </form>

    </div>
@endsection
