@extends('layouts.navbar')

@section('content')
<div class="content">
    <br><br><br>
	<div class="container-fluid">
        <div class="row">
            <h3 class="col-lg-6 col-md-6 col-xs-12">Manage F.A.Q</h3>
                <div class="col-lg-6 col-md-6 col-xs-12 text-right">
                    <a class="btn btn-default btn-sm" href="{{ route('categories.list') }}">Manage categories</a>
                    <a class="btn btn-primary btn-sm" href="{{ route('faqs.add') }}"><i class="fa-solid fa-plus"></i> Add New FAQ/Tutorial</a>
                </div>
                </div>



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

                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Actions
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a class="dropdown-item" href="{{ route('faqs.edit', $faq) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                      <form action="{{ route('faqs.remove', $faq) }}" method="POST">
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
@endsection
