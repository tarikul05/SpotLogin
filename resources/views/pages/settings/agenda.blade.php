<form method="POST" action="{{ route('calendar.settings.store') }}">
    @csrf

    <div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">{{ __('Schedule Settings') }}</div>
            <div class="card-body">

                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                
                    <div class="form-group">
                        <label for="timezone" class="titleFieldPage">{{ __('Timezone') }}</label>

                        <select class="select2 form-control" id="timezone" name="timezone" data-live-search="true">
                            <option value="">{{ __('Select Timezone')}}</option>
                            @foreach ($allTimezones as $key => $value)
                            <option value="{{ $key }}" @if($calendarSettings->timezone === $key) selected @endif>  {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="min_time" class="titleFieldPage">{{ __('Start time')}} ({{ __('you can decide what the time range display on your calendar') }})</label>
                        <select id="min_time" name="min_time" class="form-control">
                            @for ($hour = 0; $hour <= 23; $hour++)
                                @php
                                    $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
                                @endphp
                                <option value="{{ $formattedHour }}" @if($calendarSettings->min_time === $formattedHour) selected @endif>{{ $hour }}:00</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="max_time" class="titleFieldPage">{{ __('End time')}}</label>
                        <select id="max_time" name="max_time" class="form-control">
                            @for ($hour = 23; $hour >= 0; $hour--)
                                @php
                                    $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
                                @endphp
                                <option value="{{ $formattedHour }}" @if($calendarSettings->max_time === $formattedHour) selected @endif>{{ $hour }}:00</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                    <label for="weekends" class="titleFieldPage">{{ __('Weekends')}}</label>
                        <select id="weekends" name="weekends" class="form-control">
                           <option value="1" @if($calendarSettings->weekends === 1) selected @endif>{{ __('Yes')}}</option>
                           <option value="0" @if($calendarSettings->weekends === 0) selected @endif>{{ __('No')}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="weekends" class="titleFieldPage">{{ __('Use local timezone')}}</label>
                            <select id="current" name="current" class="form-control">
                               <option value="0" @if($calendarSettings->current === 0) selected @endif>{{ __('No')}}</option>
                               <option value="1" @if($calendarSettings->current === 1) selected @endif>{{ __('Yes')}}</option>
                            </select>
                        </div>


                
            </div>
        </div>
    </div>

    <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.8!important; background-color:#DDDD!important;">
            <button type="submit" class="btn btn-success">{{ __('Save Schedule Settings') }}</button>
        </div>
    </div>

</div>
</form>

