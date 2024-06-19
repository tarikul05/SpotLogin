<form method="POST" action="{{ route('selfUpdateTaxeAction') }}">
    @csrf
    
    <div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">{{__('Taxes')}}</div>
            <div class="card-body">

                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                
                    <table class="table table-stripped table-hover">
                        @if($InvoicesTaxData->count() > 0)
                        <thead>
                            <th class="titleFieldPage" width="30%">{{__('Name')}}</th>
                            <th class="titleFieldPage">{{__('Purcentage')}}</th>
                            <th class="titleFieldPage">{{__('Number')}} <span style="font-size:10px; color:#CCC;">will show on your invoice</span></th>
                            <th width="40" class="text-center titleFieldPage">{{__('Action')}}</th>
                        </thead>
                        @else
                        <i class="fa-solid fa-circle-info"></i> Please create your first taxe<br>
                        @endif
                        <tbody>
                            @foreach($InvoicesTaxData as $tax)
                                <tr class="add_more_tax_row">
                                    <td style="min-width: 100px;">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="tax_name[]" value="<?= $tax['tax_name'] ?>" placeholder="Tax Name" maxlength="255">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control tax_percentage" name="tax_percentage[]" value="<?= $tax['tax_percentage'] ?>" placeholder="Tax Percentage" maxlength="6">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="tax_number[]" value="<?= $tax['tax_number'] ?>" placeholder="Tax Number" maxlength="100">
                                        
                                    </td>
                                    <td class="text-center align-middle">
                                        <a class="text-danger delete_tax" data-tax_id="<?= $tax->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                        <table class="table table-stripped" id="add_more_tax_div" style="display: none;">
                            <thead>
                                <th class="titleFieldPage" width="30%">{{__('Name')}}</th>
                                <th class="titleFieldPage">{{__('Purcentage')}}</th>
                                <th class="titleFieldPage">{{__('Number')}}</th>
                                <th width="40" class="text-center titleFieldPage">{{__('Action')}}</th>
                            </thead>
                            <tbody>
                        </table>


                    <div class="d-flex justify-content-end">
                        <button id="add_more_tax_btn" type="button" class="btn btn-outline-primary">
                          <i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add taxe') }}
                        </button>
                      </div>

                   
            </div>
        </div>

    </div>


    <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.8!important; background-color:#DDDD!important;">
            @if($InvoicesTaxData->count() > 0)
            <button type="submit" class="btn btn-success" id="btnSaveTaxes">{{ __('Save Taxes') }}</button>
            @else
            <button type="submit" class="btn btn-success" id="btnSaveTaxes" style="display:none;">{{ __('Save Taxes') }}</button>
            @endif
        </div>
    </div>

     

    </div>
    </form>

