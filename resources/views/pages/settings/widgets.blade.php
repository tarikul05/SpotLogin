<form method="POST" action="{{ route('widgets.saveUserWidgets') }}">
    @csrf

    <div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">{{ __('Widgets') }}</div>
            <div class="card-body">


                    <div class="form-group">
                        <table>
                            @foreach ($widgets as $widget)
                            <tr>
                                <td>
                                <label>
                                    <input type="checkbox" name="widgets[{{ $widget->id }}]" value="1"
                                        {{ isset($userWidgets[$widget->id]) && $userWidgets[$widget->id] ? 'checked' : '' }}>
                                    {{ $widget->name }} - {{ $widget->explanation }}
                                </label>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>

                
            </div>
        </div>
    </div>

    <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.8!important; background-color:#DDDD!important;">
            <button type="submit" class="btn btn-success">{{ __('Save Widgets Settings') }}</button>
        </div>
    </div>

</div>
</form>

