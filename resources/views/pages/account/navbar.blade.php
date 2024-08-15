<nav class="subNav">

    <div class="nav nav-tabs" id="nav-tab" role="tablist">

        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
        {{ __('Personal information') }}
        </button>

        <button class="nav-link" id="nav-paymentMethod-tab" data-bs-toggle="tab" data-bs-target="#tab_5" type="button" role="tab" aria-controls="nav-paymentMethod" aria-selected="true">
            {{ __('Payment methods') }} <span class="badge bg-danger" style="background-color: #3b75bf!important;">new</span>
        </button>

        @if($AppUI->isTeacherAdmin() || $AppUI->isTeacher() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
         <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
            {{ __('My plan')}}
        </button>
        <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
            {{ __('My invoices')}}
        </button>
        <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
        {{ __('My account')}}
        </button>
        @endif

    </div>

</nav>



@section('footer_js')


<script>
    $(document).ready(function() {
    document.getElementById('type').addEventListener('change', function () {
        const container = document.getElementById('details-container');
        container.innerHTML = ''; // Reset the container

        if (this.value === 'Stripe') {
            container.innerHTML += `
                <div class="form-group">
                    <label for="account_number" style="font-size:11px;">Stripe Account Number</label>
                    <input type="text" name="details[account_number]" class="form-control">
                </div>
                 <button type="submit" class="btn btn-outline-success">Save</button>`;
        } else if (this.value === 'PayPal') {
            container.innerHTML += `
                <div class="form-group">
                    <label for="paypal_address" style="font-size:11px;">PayPal Address</label>
                    <input type="email" name="details[paypal_address]" class="form-control">
                </div>
                 <button type="submit" class="btn btn-outline-success">Save</button>`;
        } else if (this.value === 'Cash') {
            container.innerHTML += `
                <div class="form-group">
                    <label for="paypal_address" style="font-size:11px;">Get pay by Cash</label>
                    <input type="text" name="details[cash]" class="form-control">
                </div>
                 <button type="submit" class="btn btn-outline-success">Save</button>`;
        } else if (this.value === 'IBAN') {
            container.innerHTML += `
                <div class="form-group">
                    <label for="paypal_address" style="font-size:11px;">IBAN N°</label>
                    <input type="text" name="details[iban_number]" class="form-control">
                </div>
                 <button type="submit" class="btn btn-outline-success">Save</button>`;

      
                } else if (this.value === 'E-Transfer') {
            container.innerHTML += `
                <div class="form-group">
                    <label for="paypal_address" style="font-size:11px;">E-Transfer Email</label>
                    <input type="email" name="details[e_transfer_number]" class="form-control">
                </div>
                 <button type="submit" class="btn btn-outline-success">Save</button>`;

        } else if (this.value === 'Swift') {
            container.innerHTML += `
                <div class="form-group">
                    <label for="paypal_address" style="font-size:11px;">SWIFT A/c No</label>
                    <input type="text" name="details[swift_number]" class="form-control">
                </div>
                 <button type="submit" class="btn btn-outline-success">Save</button>`;
        } else if (this.value === 'Bank') {
            container.innerHTML += `
                <div id="cash-fields">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group p-2">
                            <label for="custom_field_name_1" style="font-size:11px;">{{__('Custom Field Name')}}</label>
                            <input type="text" name="details[custom_fields][0][name]" placeholder="ex: Bank Name" class="form-control">
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group p-2">
                            <label for="custom_field_value_1" style="font-size:11px;">{{__('Custom Field Value')}}</label>
                            <input type="text" name="details[custom_fields][0][value]" placeholder="ex: UBS" class="form-control">
                        </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-field" class="btn btn-primary">Add Field</button>
                 <button type="submit" class="btn btn-outline-success">Save</button>`;
            
            document.getElementById('add-field').addEventListener('click', function () {
                const cashFields = document.getElementById('cash-fields');
                const fieldCount = cashFields.getElementsByClassName('form-group').length;

                const newFieldHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group p-2">
                            <label for="custom_field_name_${fieldCount + 1}" style="font-size:11px;">{{__('Custom Field Name')}}</label>
                            <input type="text" name="details[custom_fields][${fieldCount}][name]" class="form-control">
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-group p-2">
                            <label for="custom_field_value_${fieldCount + 1}" style="font-size:11px;">{{__('Custom Field Value')}}</label>
                            <input type="text" name="details[custom_fields][${fieldCount}][value]" class="form-control">
                        </div>
                    </div>
                </div>`;

                cashFields.insertAdjacentHTML('beforeend', newFieldHTML);
            });
        }
    });
});
</script>

<script src="{{ asset('js/pages/account/index.js') }}"></script>
<script src="{{ asset('js/pages/account/image.js') }}"></script>

<script>
var payment_info_checkbox = "{{ $teacher->payment_info_checkbox ?? '' }}";

$(document).ready(function() {
    if (payment_info_checkbox === '2') {
        $('#payment_info_div').hide();
        $('#payment_info_div2').show();
    } else {
        $('#payment_info_div').show();
        $('#payment_info_div2').hide();
    }

    $('#payment_info_checkbox,#payment_info_checkbox2').on('change', function($event) {
        if($event.target.value == 2) {
            $('#payment_info_div').hide();
            $('#payment_info_div2').show();
        }else{
            $('#payment_info_div').show();
            $('#payment_info_div2').hide();
        }
    })

    $(document).ready(function() {
    // Fonction pour copier le contenu d'un champ vers un autre
    function copyFieldContent(sourceField, targetField) {
        $('#' + targetField).val($('#' + sourceField).val());
    }

    // Événement pour le champ "bank_name"
    $('#bank_name').on('input', function() {
        copyFieldContent('bank_name', 'bank_name2');
    });

    // Événement pour le champ "bank_account"
    $('#bank_account').on('input', function() {
        copyFieldContent('bank_account', 'bank_account2');
    });

    // Événement pour le champ "bank_iban"
    $('#bank_iban').on('input', function() {
        copyFieldContent('bank_iban', 'bank_iban2');
    });
});

});
</script>


@endsection