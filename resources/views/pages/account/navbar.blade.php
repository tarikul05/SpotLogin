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
    document.addEventListener('DOMContentLoaded', function () {
        // Récupérer l'URL actuelle
        const urlParams = new URLSearchParams(window.location.search);

        // Vérifier si le paramètre 'tab' est présent
        const tabId = urlParams.get('tab');

        if (tabId) {
            // Sélectionner l'onglet correspondant à l'ID
            const tabButton = document.querySelector(`[data-bs-target="#tab_${tabId}"]`);

            if (tabButton) {
                // Créer une instance du composant Tab de Bootstrap
                const bootstrapTab = new bootstrap.Tab(tabButton);

                // Activer l'onglet avec la méthode Bootstrap
                bootstrapTab.show();
            }
        }
    });
</script>

<script>
    function confirmDelete(paymentMethodId) {
        Swal.fire({
            title: "{{ __('Are you sure?') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{ __('Yes, delete it!') }}",
            cancelButtonText: "{{ __('Cancel') }}",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + paymentMethodId).submit();
            }
        })
    }
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '#continueStripeAccount', function(e) {
        e.preventDefault(); 
        $('#type').val('Stripe');
        $('#type').trigger('change');
    });

     $('#type').on('change', function() {
        const container = document.getElementById('details-container');
        container.innerHTML = ''; // Reset the container

        if (this.value === 'Stripe') {
            var is_stripe_account = "{{$is_connected_account ? $AppUI->stripe_account_id : 'none'}}";
            var is_conneced_account_charges_enabled = "{{$is_conneced_account_charges_enabled ? 'true' : 'false'}}";
            if(is_stripe_account === 'none') {
                container.innerHTML += `
                <div class="form-group" id="askUrlStripeAccountLink">
                    <br>
                    <label for="account_number" style="font-size:11px;">Stripe Account Information</label><br>
                    <label for="account_number">Please create your stripe connected account link by clicking the button below.</label>
                    <br>
                     <a id="create-stripe-account" class="btn btn-outline-primary" href="#">Create</a>
                </div>
                <div id="urlStripeAccountLink" class="form-group"></div>
              `;
            } else {
                if(is_conneced_account_charges_enabled === 'true') {
                        container.innerHTML += `
                        <div class="form-group" id="askUrlStripeAccountLink">
                            <br>
                            <label>Account ID: <b>${is_stripe_account}</b></label><br>
                            Your Stripe connected account is ready to be used
                        </div>`
                } else {
                container.innerHTML += `
                <div class="form-group" id="askUrlStripeAccountLink">
                    <br>
                    <label for="account_number" style="font-size:11px;">Stripe Account Information</label><br>
                    Account ID: <b>${is_stripe_account}</b><br>
                    <label for="account_number">Some fields are necessary to complete the creation of your connected account.</label>
                     <br>
                     <a id="continue-stripe-account" class="btn btn-outline-primary" href="#">Continue</a>
                </div>
                <div id="urlStripeAccountLink" class="form-group"></div>`;
                }
            }
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
                    <br>
                    <label for="paypal_address" style="font-size:11px;">SWIFT A/c No</label>
                    <input type="text" name="details[swift_number]" class="form-control">
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
});

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



$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', '#create-stripe-account', function(e) {
        e.preventDefault(); 
        $("#pageloader").fadeIn();
        $.ajax({
            url: BASE_URL + '/create-stripe-bank-account', 
            type: 'POST',
            dataType: 'json',
            data: {},
            success: function(response) {
              
                $('#askUrlStripeAccountLink').hide();

                $('#urlStripeAccountLink').html('<br>Click on secure link below to create your connected account via Stripe:<br><small>(you will be automatically redirect to Sportlogin after your request)</small><br><br><i class="fa fa-arrow-right"><i/> <a style="font-size:12px; font-family:Arial;" href="'+response.url+'">' + response.url + '</a>');

                $("#pageloader").hide();

            },
            error: function(xhr, status, error) {
                console.error(error); // Affichez l'erreur dans la console
                alert('Erreur lors de la création du compte Stripe.');
            }
        });
    });
});
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', '#continue-stripe-account', function(e) {
        e.preventDefault(); 
        $("#pageloader").fadeIn();
        $.ajax({
            url: BASE_URL + '/continue-stripe-bank-account', 
            type: 'POST',
            dataType: 'json',
            data: {},
            success: function(response) {
              
                $('#askUrlStripeAccountLink').hide();

                $('#urlStripeAccountLink').html('<br>Click on secure link below to finish the creation of your connected account via Stripe:<br><small>(you will be automatically redirect to Sportlogin after your request)</small><br><br><i class="fa fa-arrow-right"><i/> <a style="font-size:12px; font-family:Arial;" href="'+response.url+'">' + response.url + '</a>');

                $("#pageloader").hide();

            },
            error: function(xhr, status, error) {
                console.error(error); // Affichez l'erreur dans la console
                alert('Erreur lors de la création du compte Stripe.');
            }
        });
    });
});

</script>
@endsection