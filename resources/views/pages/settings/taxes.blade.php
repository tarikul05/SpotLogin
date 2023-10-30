<div class="row justify-content-center pt-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">Taxes</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                <form method="POST" action="{{ route('selfUpdateTaxeAction') }}">
                    @csrf
                    <table class="table table-bordered table-hover">
                        <thead>
                            <th width="30%">Name</th>
                            <th>Purcentage</th>
                            <th>Number</th>
                            <th width="40" class="text-center">Action</th>
                        </thead>
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
                                <th width="30%">Name</th>
                                <th>Purcentage</th>
                                <th>Number</th>
                                <th width="40" class="text-center">Action</th>
                            </thead>
                            <tbody>
                        </table>


                    <div class="d-flex justify-content-end">
                        <button id="add_more_tax_btn" type="button" class="btn btn-outline-primary">
                          <i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add Taxe') }}
                        </button>
                      </div>

                    <br>
                    <button type="submit" class="btn btn-success">{{ __('Save Taxes') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
