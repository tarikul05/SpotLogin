<div class="row justify-content-center pt-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">Schedule settings</div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('calendar.settings.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="timezone">Timezone</label>
     
                        <select class="select2  form-control" id="timezone" name="timezone" data-live-search="true">
                            <option value="">{{ __('Select Timezone')}}</option>
                            @foreach ($allTimezones as $key => $value)
                            <option value="{{ $key }}" @if($calendarSettings->timezone === $key) selected @endif>  {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="min_time">Start time</label>
                        <select id="min_time" name="min_time" class="form-control">
                            <option value="00:00:00" @if($calendarSettings->min_time === '00:00:00') selected @endif>00:00</option>
                            <option value="01:00:00" @if($calendarSettings->min_time === '01:00:00') selected @endif>01:00</option>
                            <option value="02:00:00" @if($calendarSettings->min_time === '02:00:00') selected @endif>02:00</option>
                            <option value="03:00:00" @if($calendarSettings->min_time === '03:00:00') selected @endif>03:00</option>
                            <option value="04:00:00" @if($calendarSettings->min_time === '04:00:00') selected @endif>04:00</option>
                            <option value="06:00:00" @if($calendarSettings->min_time === '06:00:00') selected @endif>06:00</option>
                            <option value="07:00:00" @if($calendarSettings->min_time === '07:00:00') selected @endif>07:00</option>
                            <option value="08:00:00" @if($calendarSettings->min_time === '08:00:00') selected @endif>08:00</option>
                            <option value="09:00:00" @if($calendarSettings->min_time === '09:00:00') selected @endif>09:00</option>
                            <option value="10:00:00" @if($calendarSettings->min_time === '10:00:00') selected @endif>10:00</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="max_time">End time</label>
                        <select id="max_time" name="max_time" class="form-control">
                            <option value="23:59:00" @if($calendarSettings->max_time === '23:59:00') selected @endif>23:59</option>
                            <option value="22:59:00" @if($calendarSettings->max_time === '22:59:00') selected @endif>22:59</option>
                            <option value="21:59:00" @if($calendarSettings->max_time === '21:59:00') selected @endif>21:59</option>
                            <option value="20:59:00" @if($calendarSettings->max_time === '20:59:00') selected @endif>20:59</option>
                            <option value="19:59:00" @if($calendarSettings->max_time === '19:59:00') selected @endif>19:59</option>
                            <option value="18:59:00" @if($calendarSettings->max_time === '18:59:00') selected @endif>18:59</option>
                        </select>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">{{ __('Save Schedule Settings') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
