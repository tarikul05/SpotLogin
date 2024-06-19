<form method="POST" action="{{ route('event_location.create') }}">
    @csrf
    
    <div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">{{__('Locations')}}</div>
            <div class="card-body">

                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

              
                    @php $countLocation= isset($eventLastLocaId->id) ? ($eventLastLocaId->id) : 1; @endphp

                    <table class="table table-stripped table-hover">
                        @if($locations->count() > 0)
                        <thead>
                            <th class="titleFieldPage"  width="30%">{{__('Name')}}</th>
                            <th class="titleFieldPage"></th>
                            <th class="titleFieldPage"  width="40" class="text-center">{{__('Action')}}</th>
                        </thead>
                        @else
                        <i class="fa-solid fa-circle-info"></i> Please create your first location<br>
                        @endif
                        <tbody>
                            @foreach($locations as $loca)
                                <tr class="add_more_location_row">
                                    <td class="align-middle" style="min-width: 100px;"> <!-- Définissez une largeur minimale pour la colonne "Name" -->
                                        <div class="form-group">
                                            <input type="hidden" name="location[{{$countLocation}}][id]" value="<?= $loca->id; ?>">
                                            <input class="form-control location_name" name="location[{{$countLocation}}][name]" placeholder="{{ __('Location Name') }}" value="<?= $loca->title; ?>" type="text">
                                        </div>
                                    </td>
                                    <td></td>
                                    <td class="text-center align-middle">
                                        <a type="button" class="text-danger delete_location" data-location_id="<?= $loca->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                @php $countLocation++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                        <table class="table table-stripped" id="add_more_location_div" style="display: none;">
                            <thead>
                                <th class="titleFieldPage"  width="30%">{{__('Name')}}</th>
                                <th class="titleFieldPage"></th>
                                <th class="titleFieldPage"  width="40" class="text-center">{{__('Action')}}</th>
                            </thead>
                            <tbody>
                        </table>
                    <div class="d-flex justify-content-end">
                        <button id="add_more_location_btn" data-last_location_id="{{$countLocation}}" type="button" class="btn btn-outline-primary">
                          <i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add location') }}
                        </button>
                    </div>
            </div>
        </div>
  
    </div>

    <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.8!important; background-color:#DDDD!important;">
            @if($locations->count() > 0)
            <button type="submit" class="btn btn-success" id="btnSaveLocations">{{ __('Save Locations') }}</button>
            @else
            <button type="submit" class="btn btn-success" id="btnSaveLocations" style="display:none;">{{ __('Save Locations') }}</button>
            @endif
        </div>
    </div>

</div>
 </form>