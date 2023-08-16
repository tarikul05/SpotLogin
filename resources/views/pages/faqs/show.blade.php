@extends('layouts.main')

@section('content')
<div class="content">
    <div class="container-fluid body pt-3">

        <header class="panel-heading" style="border: none;">
            <div class="row pt-2" style="margin:0;">
                <div class="col-lg-6 col-12 header-area">
                        <div class="page_header_class pt-1">
                            <h1 for="calendar" class="titleCalendar" id="cal_title" style="display: block;">
                              {{__('FAQs/Tutorials')}}
                            </h1>
                        </div>
                </div>
                <div class="col-lg-6 col-12" style="text-align: right;">
                    <a class="btn btn-default" href="{{ route('faqs.list') }}">Return</a>
                </div>
            </div>
        </header>

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

    <br>
    <a href="{{ route('faqs.edit', $faq) }}"><i class="fa-solid fa-pen-to-square"></i> Edit FAQ/Tutorial</a>

    </div>
</div>

@endsection
