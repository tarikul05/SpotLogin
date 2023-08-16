@extends('layouts.main')

@section('content')
<div class="content">
    <div class="container-fluid body pt-3">
        <header class="panel-heading" style="border: none;">
            <div class="row pt-2" style="margin:0;">
                <div class="col-lg-6 col-12 header-area">
                    <div class="page_header_class pt-1">
                        <h1 for="calendar" class="titleCalendar" id="cal_title" style="display: block;">
                            <small>Category:</small> {{ $category->name }}
                        </h1>
                    </div>
                </div>
                <div class="col-lg-6 col-12" style="text-align: right;">
                        <a class="btn btn-default px-3" href="{{ route('categories.list') }}">Return</a>
                        <a class="btn btn-primary px-3" href="{{ route('categories.edit', $category) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                </div>
            </div>
        </header>

        <!--<br>
        <a href="{{ route('categories.edit', $category) }}"><i class="fa-solid fa-pen-to-square"></i> Edit Category</a>-->


        <div class="card">
            <div class="card-body bg-tertiary">
        <div>
        @if ($category->faqs->isEmpty())
            <b>No FAQs associated with this category.</b>
        @else
        <b>FAQs Associated with this Category</b><br><br>
            <ul>
                @foreach ($category->faqs as $faq)
                    <li><a href="{{ route('faqs.show', $faq) }}">{{ $faq->title }}</a></li>
                @endforeach
            </ul>
        @endif
        </div>
            </div>
        </div>


        <form action="{{ route('categories.remove', $category) }}" method="POST" style="text-align: right; padding-top:12px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger px-3"><i class="fa-solid fa-trash"></i> Delete</button>
        </form>

    </div>
</div>
@endsection
