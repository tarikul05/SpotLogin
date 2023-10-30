<div class="row justify-content-center pt-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">Levels</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                <form method="POST" action="{{ route('event_level.create') }}">
                    @csrf
                    @php $countLevel= isset($eventLastLevelId->id) ? ($eventLastLevelId->id) : 1; @endphp

                    <table class="table table-bordered table-hover">
                        <thead>
                            <th width="30%">Name</th>
                            <th></th>
                            <th width="40" class="text-center">Action</th>
                        </thead>
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
                                        <button type="button" class="btn btn-danger delete_level" data-level_id="<?= $lvl->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                                @php $countLevel++; @endphp
                            @endforeach
                        </tbody>
                    </table>

                        <table class="table table-bordered" id="add_more_level_div" style="display: none;">
                            <thead>
                                <th width="30%">Name</th>
                                <th></th>
                                <th width="40" class="text-center">Action</th>
                            </thead>
                            <tbody>
                        </table>


                    <div class="d-flex justify-content-end">
                        <button id="add_more_level_btn" data-last_level_id="{{$countLevel}}" type="button" class="btn btn-outline-primary">
                          <i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add Level') }}
                        </button>
                      </div>

                    <br>
                    <button type="submit" class="btn btn-success">{{ __('Save levels') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
