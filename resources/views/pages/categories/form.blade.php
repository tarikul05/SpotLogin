@extends('layouts.navbar')

@section('content')

    <div class="content">

        <br><br><br>
        <div class="container-fluid">
            <div class="row">
                <h3 class="col-lg-6 col-md-6 col-xs-12">{{ isset($category) ? 'Edit Category' : 'Create a new category' }}</h3>
                    <div class="col-lg-6 col-md-6 col-xs-12 text-right">
                        <a class="btn btn-primary btn-sm" href="{{ route('categories.list') }}">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </a>
                    </div>
                    </div>


<div class="card">
    <div class="card-body bg-tertiary">

    <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.create') }}" method="POST">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <label for="name">Name:</label>
        <input class="form-control" type="text" name="name" value="{{ old('name', $category->name ?? '') }}">

        <br><label for="description">Description:</label>
        <textarea name="description" class="form-control">{{ old('description', $category->description ?? '') }}</textarea>

        <br>
        <button class="btn btn-success" type="submit">{{ isset($category) ? 'Update' : 'Create' }}</button>
    </form>

    </div>
</div>

        </div>
    </div>
@endsection
