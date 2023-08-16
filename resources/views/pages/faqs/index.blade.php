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
                        <a class="btn btn-default" href="{{ route('categories.list') }}">Manage categories</a>
                        <a class="btn btn-primary" href="{{ route('faqs.add') }}"><i class="fa-solid fa-plus"></i> Add New FAQ/Tutorial</a>
                    </div>
                </div>
            </header>


    <table id="example" class="table table-stripped table-hover" style="width:100%">
        <thead>
            <tr>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Category') }}</th>
                <th style="text-align: right;">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>

        @foreach ($faqs as $faq)
        <tr>
            <td>
                <a href="{{ route('faqs.show', $faq) }}">{{ $faq->title }}</a>
            </td>
            <td>
                {{ $faq->category->name }}
            </td>
            <td style="text-align: right; width:180px;">
                <div class="row">
                <div class="col-lg-6">
                <a class="btn btn-primary w-90" href="{{ route('faqs.edit', $faq) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                </div>
                <form class="col-lg-6" action="{{ route('faqs.remove', $faq) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-90"><i class="fa-solid fa-trash"></i> Delete</button>
                </form>
                </div>
            </td>
        </tr>
        @endforeach

        </tbody>
    </table>


    </div>
</div>
@endsection
