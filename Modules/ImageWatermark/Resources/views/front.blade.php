@extends('imagewatermark::layouts.front')

@section('content')
    <div class="container mt-5 iw-container">
        @if(Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{$error}}</div>
            @endforeach
        @endif
        @if(!$imageList->isEmpty())
            <div class="table-responsive">
                <table class="table table-hover mt-3 table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('imagewatermark::iw.title')</th>
                        <th scope="col">@lang('imagewatermark::iw.image')</th>
                        <th scope="col" class="text-right">@lang('imagewatermark::iw.download')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($imageList as $image)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{$image->title}}</td>
                            <td>
                                <form id="download-form-{{ $image->id }}" action="{{ route('iw.download', $image->id) }}"
                                      method="POST">
                                    @csrf
                                    @foreach($image->content as $imageContent)
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">@lang('imagewatermark::iw.content') {{ $loop->iteration }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control title" name="title[{{$imageContent->id}}]"
                                                   placeholder="@lang('imagewatermark::iw.enter_content')"
                                                   value="{{isset(old('title')[0]) ? old('title')[0] : ''}}" required>
                                        </div>
                                        <div class="col-sm-2">
                                            <input style="margin-top: 5px" type="color" name="color[{{$imageContent->id}}]" value="">
                                        </div>
                                    </div>
                                    @endforeach
                                </form>
                                <div class="form-group row">
                                    <img src="{{asset('images/iw/'.$image->image)}}" style="max-width: 200px;padding-left: 15px">
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('iw.download', $image->id) }}" class="btn btn-primary"
                                   onclick="event.preventDefault();confirmDownload('#download-form-{{ $image->id }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $imageList->links() !!}
            </div>
        @else
            <h3>@lang('imagewatermark::iw.empty')</h3>
        @endif
    </div>
@endsection
