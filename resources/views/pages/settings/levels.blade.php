<form method="POST" action="{{ route('event_level.create') }}">
    @csrf
    
    <div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">{{__('Levels')}}</div>
            <div class="card-body">

                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                
                    @php $countLevel= isset($eventLastLevelId->id) ? ($eventLastLevelId->id) : 1; @endphp

                    <table class="table table-stripped table-hover">
                        @if($levels->count() > 0)
                        <thead>
                            <th class="titleFieldPage" width="30%">{{__('Name')}}</th>
                            <th class="titleFieldPage"></th>
                            <th class="titleFieldPage" width="40" class="text-center">{{__('Action')}}</th>
                        </thead>
                        @else
                        <i class="fa-solid fa-circle-info"></i> Please create your first level<br>
                        @endif
                        <tbody>
                            @foreach($levels as $lvl)
                                <tr class="add_more_level_row">
                                    <td class="align-middle" style="min-width: 100px;"> <!-- Définissez une largeur minimale pour la colonne "Name" -->
                                        <div class="form-group">
                                            <input type="hidden" name="level[{{$countLevel}}][id]" value="<?= $lvl->id; ?>">
                                            <input class="form-control level_name" name="level[{{$countLevel}}][name]" placeholder="{{ __('level Name') }}" value="<?= $lvl->title; ?>" type="text">
                                        </div>
                                    </td>
                                    <td></td>
                                    <td class="text-center align-middle">
                                        <a type="button" class="text-danger delete_level" data-level_id="<?= $lvl->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                @php $countLevel++; @endphp
                            @endforeach
                        </tbody>
                    </table>

                        <table class="table table-stripped" id="add_more_level_div" style="display: none;">
                            <thead>
                                <th class="titleFieldPage" width="30%">{{__('Name')}}</th>
                                <th class="titleFieldPage"></th>
                                <th class="titleFieldPage" width="40" class="text-center">{{__('Action')}}</th>
                            </thead>
                            <tbody>
                        </table>


                    <div class="d-flex justify-content-end">
                        <button id="add_more_level_btn" data-last_level_id="{{$countLevel}}" type="button" class="btn btn-outline-primary">
                          <i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add Level') }}
                        </button>
                      </div>

                   
            </div>
        </div>
        <br>
        


    </div>

    <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.8!important; background-color:#DDDD!important;">
            @if($levels->count() > 0)
        <button type="submit" class="btn btn-success" id="btnSaveLevels">{{ __('Save Levels') }}</button>
        @else
        <button type="submit" class="btn btn-success" id="btnSaveLevels" style="display:none;">{{ __('Save Levels') }}</button>
        @endif
        </div>
    </div>

        

</div>
</form>