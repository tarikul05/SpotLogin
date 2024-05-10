<div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <form method="POST" action="{{ route('selfUpdateTaxeAction') }}">
            @csrf
        <div class="card">
            <div class="card-header">{{__('Taxes')}}</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                
                    <table class="table table-bordered table-hover">
                        @if($InvoicesTaxData->count() > 0)
                        <thead>
                            <th width="30%">{{__('Name')}}</th>
                            <th>{{__('Purcentage')}}</th>
                            <th>{{__('Number')}}</th>
                            <th width="40" class="text-center">{{__('Action')}}</th>
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
                                        <p style="font-size:11px;">this number will show on your invoice</p>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger delete_tax" data-tax_id="<?= $tax->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                        <table class="table table-bordered" id="add_more_tax_div" style="display: none;">
                            <thead>
                                <th width="30%">{{__('Name')}}</th>
                                <th>{{__('Purcentage')}}</th>
                                <th>{{__('Number')}}</th>
                                <th width="40" class="text-center">{{__('Action')}}</th>
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
        <br>
        @if($InvoicesTaxData->count() > 0)
        <button type="submit" class="btn btn-primary" id="btnSaveTaxes">{{ __('Save Taxes') }}</button>
        @else
        <button type="submit" class="btn btn-primary" id="btnSaveTaxes" style="display:none;">{{ __('Save Taxes') }}</button>
        @endif
    </form>
    </div>
</div>
