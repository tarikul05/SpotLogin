<div class="row justify-content-center pt-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">Locations</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                <form method="POST" action="{{ route('event_location.create') }}">
                    @csrf
                    @php $countLocation= isset($eventLastLocaId->id) ? ($eventLastLocaId->id) : 1; @endphp

                    <table class="table table-bordered table-hover">
                        <thead>
                            <th width="30%">Name</th>
                            <th></th>
                            <th width="40" class="text-center">Action</th>
                        </thead>
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
                                        <button type="button" class="btn btn-danger delete_location" data-location_id="<?= $loca->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                                @php $countLocation++; @endphp
                            @endforeach
                        </tbody>
                    </table>

                        <table class="table table-bordered" id="add_more_location_div" style="display: none;">
                            <thead>
                                <th width="30%">Name</th>
                                <th></th>
                                <th width="40" class="text-center">Action</th>
                            </thead>
                            <tbody>
                        </table>


                    <div class="d-flex justify-content-end">
                        <button id="add_more_location_btn" data-last_location_id="{{$countLocation}}" type="button" class="btn btn-outline-primary">
                          <i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add Location') }}
                        </button>
                      </div>

                    <br>
                    <button type="submit" class="btn btn-success">{{ __('Save Locations') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
