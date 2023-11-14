
@extends('layouts.main')

@section('content')
<div class="container">

<div class="row justify-content-center pt-5">
    <div class="col-md-8 mt-3">
        <div class="card">
            <div class="card-header">{{ $student->firstname }} {{ $student->lastname }}'s availabilities</div>
            <div class="card-body">
                <table class="table table-bordered">
                    @if($availabilities->isEmpty())
                    <tr>
                        <td colspan="3">No availabilities found</td>
                    </tr>
                    @endif
                    @foreach($availabilities as $availability)
                    <tr>
                        <td>{{ $availability->day_of_week }}</td>
                        <td>{{ $availability->time_of_day }}</td>
                        <td width="100px" class="text-right">
                            <form action="{{ route('student.availability.destroy', $availability) }}" method="post" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>


@endsection
