@extends('layouts.navbar')

@section('content')

<br><br><br>
<div class="container-fluid">
    <div class="row">
        <h3 class="col-lg-6 col-md-6 col-xs-12">{{ isset($faq) ? 'Edit FAQ/Tutorial' : 'Create FAQ/Tutorial' }}</h3>
            <div class="col-lg-6 col-md-6 col-xs-12 text-right">
            <a href="{{ route('faqs.list') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
            </div>
            </div>


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

        <br><label for="youtube_link">Link: (youtube link or /videos/file-name.mp4)</label>
        <input type="text" class="form-control" name="youtube_link" value="{{ old('youtube_link', $faq->youtube_link ?? '') }}">

        <br>
        <button class="btn btn-success" type="submit">{{ isset($faq) ? 'Update' : 'Create' }}</button>
    </form>

    </div>
</div>


    </div>
@endsection
