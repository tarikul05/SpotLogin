@extends('layouts.main')

@section('content')

    <div class="content">
        <div class="container-fluid body pt-3">

                <header class="panel-heading" style="border: none;">
                    <div class="row pt-2" style="margin:0;">
                        <div class="col-lg-6 col-12 header-area">
                                <div class="page_header_class pt-1">
                                    <h1 for="calendar" class="titleCalendar" id="cal_title" style="display: block;">
                                        {{ isset($faq) ? 'Edit FAQ/Tutorial' : 'Create FAQ/Tutorial' }}
                                    </h1>
                                </div>
                        </div>
                        <div class="col-lg-6 col-12" style="text-align: right;">
                            <a class="btn btn-default" href="{{ route('faqs.list') }}">Return</a>
                        </div>
                    </div>
                </header>


<div class="card">
    <div class="card-body bg-tertiary">

    <form action="{{ isset($faq) ? route('faqs.update', $faq) : route('faqs.create') }}" method="POST">
        @csrf
        @if(isset($faq))
            @method('PUT')
        @endif



        <label for="category_id">Category:</label><br>
        <select name="category_id" class="form-control">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ (isset($faq) && $faq->category_id == $category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <br><label for="title">Title:</label>
        <input type="text" class="form-control" name="title" value="{{ old('title', $faq->title ?? '') }}">

        <br><label for="description">Description:</label>
        <textarea name="description" class="form-control">{{ old('description', $faq->description ?? '') }}</textarea>

        <br><label for="youtube_link">YouTube Link:</label>
        <input type="text" class="form-control" name="youtube_link" value="{{ old('youtube_link', $faq->youtube_link ?? '') }}">

        <br>
        <button class="btn btn-success" type="submit">{{ isset($faq) ? 'Update' : 'Create' }}</button>
    </form>

    </div>
</div>

        </div>
    </div>
@endsection
