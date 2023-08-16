@extends('layouts.main')

@section('content')
<div class="content">
    <div class="container-fluid body pt-3">

            <header class="panel-heading" style="border: none;">
                <div class="row pt-2" style="margin:0;">
                    <div class="col-lg-6 col-12 header-area">
                            <div class="page_header_class pt-1">
                                <h1 for="calendar" class="titleCalendar" id="cal_title" style="display: block;">
                                  {{__('Categories')}}
                                </h1>
                            </div>
                    </div>
                    <div class="col-lg-6 col-12" style="text-align: right;">
                        <a class="btn btn-default" href="{{ route('faqs.list') }}">Manage F.A.Q's</a>
                        <a class="btn btn-primary" href="{{ route('categories.add') }}"><i class="fa-solid fa-plus"></i> Create New Category</a>
                    </div>
                </div>
            </header>


            <table id="example" class="table table-stripped table-hover" style="width:100%">
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
                            <div class="row">
                                <div class="col-lg-6">
                                    <a class="btn btn-primary w-90" href="{{ route('categories.edit', $category) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                </div>
                                <div class="col-lg-6">
                                    <form action="{{ route('categories.remove', $category) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-90"><i class="fa-solid fa-trash"></i> Delete</button>
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
