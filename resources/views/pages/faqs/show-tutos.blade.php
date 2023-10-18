@extends('layouts.main')

@section('content')
<div class="content">
    <br><br><br>
	<div class="container-fluid">
        <div class="row">
            <h3 class="col-lg-6 col-md-6 col-xs-12">Tasks Development</h3>
                <div class="col-lg-6 col-md-6 col-xs-12 text-right">
                <a href="{{ route('faqs.list') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Back
                </a>
                </div>
                </div>

    <div class="row">

        <div class="col-lg-6 card card-body">
            <x-embed url="{{ $faq->youtube_link }}" />
        </div>
        <div class="col-lg-6 card card-body">
            <p>ID: {{ $faq->id }}</p>
            <p>Category: {{ $faq->category->name }}</p>
            <p>Title: {{ $faq->title }}</p>
            <p>Description: {{ $faq->description }}</p>
            <p>YouTube Link: {{ $faq->youtube_link }}</p>
        </div>

    </div>

    </div>
</div>

@endsection
