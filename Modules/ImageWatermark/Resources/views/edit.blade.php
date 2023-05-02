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
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <div @if(!isset($iwImage)) style="display: none" @endif class="iw-preview">
                        <hr>
                        <h3>@lang('imagewatermark::iw.preview')</h3>
                        <div class="form-group list-image-content">
                            @if(!isset($iwImage))
                            <div class="form-group row row-content-image active">
                                <div class="col-lg-3">
                                    <label>@lang('imagewatermark::iw.preview_button')</label>
                                    <div>
                                        <button type="button" class="btn btn-secondary preview-content"> @lang('imagewatermark::iw.preview_button')</button>
                                        <button type="button" class="btn btn-danger delete-content disabled"> @lang('imagewatermark::iw.delete')</button>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label>@lang('imagewatermark::iw.fontsize')</label>
                                    <select class="form-control font_size" name="font_size[]">
                                        @php
                                            $dataFontSize = isset(old('font_size')[0]) ? old('font_size') [0]: 0;
                                        @endphp
                                        @foreach($listFontSize as $fontSize)
                                            <option value="{{$fontSize}}" @if($dataFontSize == $fontSize) selected @endif>{{$fontSize}}px</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label>@lang('imagewatermark::iw.sample')</label>
                                    <input class="form-control input-preview-text" value="0123456789">
                                </div>
                                <div class="col-lg-2">
                                    <label>@lang('imagewatermark::iw.horizontal')</label>
                                    <input type="number" class="form-control horizontal" name="horizontal[]" value="{{isset(old('horizontal')[0]) ? old('horizontal')[0] : 0}}">
                                </div>
                                <div class="col-lg-2">
                                    <label>@lang('imagewatermark::iw.vertical')</label>
                                    <input type="number" class="form-control vertical" name="vertical[]" value="{{isset(old('vertical')[0]) ? old('vertical')[0] : 0}}">
                                    <input type="hidden" class="form-control" name="background[]" value="{{isset(old('background')[0]) ? old('background')[0] : 0}}">
                                    <input type="hidden" class="form-control" name="image_content_id[]" value="0">
                                </div>
                            </div>
                            @else
                                @foreach($iwImage->content as $key => $imageContent)
                                    <div class="form-group row row-content-image @if(!$key) active @endif">
                                        <div class="col-lg-3">
                                            @if(!$key)
                                            <label>@lang('imagewatermark::iw.preview_button')</label>
                                            @endif
                                            <div>
                                                <button type="button" class="btn btn-secondary preview-content"> @lang('imagewatermark::iw.preview_button')</button>
                                                <button type="button" class="btn btn-danger delete-content @if(!$key) disabled @endif"> @lang('imagewatermark::iw.delete')</button>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            @if(!$key)
                                                <label>@lang('imagewatermark::iw.fontsize')</label>
                                            @endif
                                            <select class="form-control font_size" name="font_size[]">
                                                @php
                                                    $dataFontSize = isset(old('font_size')[$key]) ? old('font_size')[$key] : $imageContent->font_size;
                                                @endphp
                                                @foreach($listFontSize as $fontSize)
                                                    <option value="{{$fontSize}}" @if($dataFontSize == $fontSize) selected @endif>{{$fontSize}}px</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            @if(!$key)
                                                <label>@lang('imagewatermark::iw.sample')</label>
                                            @endif
                                            <input class="form-control input-preview-text" value="0123456789">
                                        </div>
                                        <div class="col-lg-2">
                                            @if(!$key)
                                                <label>@lang('imagewatermark::iw.horizontal')</label>
                                            @endif
                                            <input type="number" class="form-control horizontal" name="horizontal[]" value="{{isset(old('horizontal')[$key]) ? old('horizontal')[$key]: $imageContent->horizontal}}">
                                        </div>
                                        <div class="col-lg-2">
                                            @if(!$key)
                                                <label>@lang('imagewatermark::iw.vertical')</label>
                                            @endif
                                            <input type="number" class="form-control vertical" name="vertical[]" value="{{isset(old('vertical')[$key]) ? old('vertical')[$key]: $imageContent->vertical}}">
                                            <input type="hidden" class="form-control" name="background[]" value="{{isset(old('background')[$key]) ? old('background')[$key]: $imageContent->background}}">
                                            <input type="hidden" class="form-control" name="image_content_id[]" value="{{$imageContent->id}}">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary add_new_text">@lang('imagewatermark::iw.add_new_text')</button>
                        </div>
                        <div class="font-italic">@lang('imagewatermark::iw.image_info')</div>
                        <div class="image-box mt-3">
                            <img src="@if(isset($iwImage)){{asset('images/iw/'.$iwImage->image)}}@endif" alt="" class="preview-img" id="preview-img">
                            <div class="preview-text" @if(isset($iwImage)) style="top: {{$iwImage->content[0]->vertical}}px;left: {{$iwImage->content[0]->horizontal}}px;font-size: {{$iwImage->content[0]->font_size}}px;@if($iwImage->content[0]->background)background:#fff;@endif" @endif>0123456789</div>
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
