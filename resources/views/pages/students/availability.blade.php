@extends('layouts.main')

@section('content')
<div class="container">

<div class="row justify-content-center pt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Add Availabilities</div>
            <div class="card-body">

                <form action="{{ route('student.availability.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label for="day_of_week">Choose a Day:</label>
                        <select name="day_of_week" class="form-control">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                @if(!$availabilities->contains('day_of_week', $day))
                                    <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="time_of_day">Choose Time of Day:</label>
                        <select name="time_of_day" class="form-control">
                            <option value="AM">Morning</option>
                            <option value="PM">Afternoon</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Add</button>
                </form>

            </div>
        </div>
    </div>


    <div class="col-md-8 mt-3">
        <div class="card">
            <div class="card-header">My Current Availabilities</div>
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

</div>




</div>

@endsection
