@extends('layouts.main')

@section('content')

    <div class="content">
        <div class="container-fluid body pt-3">

                <header class="panel-heading" style="border: none;">
                    <div class="row pt-2" style="margin:0;">
                        <div class="col-lg-6 col-12 header-area">
                                <div class="page_header_class pt-1">
                                    <h1 for="calendar" class="titleCalendar" id="cal_title" style="display: block;">
                                        {{ isset($category) ? 'Edit Category' : 'Create a new category' }}
                                    </h1>
                                </div>
                        </div>
                        <div class="col-lg-6 col-12" style="text-align: right;">
                            <a class="btn btn-default" href="{{ route('categories.list') }}">Return</a>
                        </div>
                    </div>
                </header>


<div class="card">
    <div class="card-body bg-tertiary">

    <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.create') }}" method="POST">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <label for="name">Name:</label>
        <input class="form-control" type="text" name="name" value="{{ old('name', $category->name ?? '') }}">

        <br>
        <button class="btn btn-success" type="submit">{{ isset($category) ? 'Update' : 'Create' }}</button>
    </form>

    </div>
</div>

        </div>
    </div>
@endsection
