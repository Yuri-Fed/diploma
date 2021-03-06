@extends('base.base')

@section('content')
    <link href="{{ asset('css/page.css') }}" rel="stylesheet">
    <div class="bg-white p-3 rounded page-container text-break">
        <h3>{{ $page->title }}</h3>
        <div class="page-content">
            {!! $page->content  !!}
        </div>
    </div>
@endsection
