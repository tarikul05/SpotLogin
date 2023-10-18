@extends('layouts.navbar')

@section('content')
<div class="content">

    <br><br><br>
	<div class="container-fluid">
        <div class="row">
            <h3 class="col-lg-6 col-md-6 col-xs-12">{{__('Categories')}}</h3>
                <div class="col-lg-6 col-md-6 col-xs-12 text-right">
                    <a class="btn btn-default btn-sm" href="{{ route('faqs.list') }}">Manage F.A.Q's</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('categories.add') }}"><i class="fa-solid fa-plus"></i> Create New Category</a>
                </div>
                </div>


            <table class="table table-stripped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th style="text-align: right;">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td>
                            <a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a>
                        </td>
                        <td style="text-align: right; width:180px;">

                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Actions
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item" href="{{ route('categories.edit', $category) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                              <form action="{{ route('categories.remove', $category) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item" type="submit"><i class="fa-solid fa-trash"></i> Delete</button>
                                </form>
                                </div>
                              </div>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-category');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                const categoryId = this.getAttribute('data-category-id');
                const categoryName = this.closest('tr').querySelector('td:first-child a').innerText;

                if (confirm(`Are you sure you want to delete the category "${categoryName}" and all associated FAQs?`)) {
                    window.location.href = `/admin/categories/remove/${categoryId}`;
                }
            });
        });
    });
</script>
@endsection
