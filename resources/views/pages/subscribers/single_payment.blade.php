@extends('layouts.main')

@section('head_links')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="padding:10px 14px;">
            <form action="{{ route('subscription.singleCharge', ['payment_id'=> 10 ]) }}" method="post" id="single-payment-form">
                    @csrf
                    <div class="form-group">
                        <label for="card-element-12" style="font-size: 20px;">
                            Enter or select your credit card information
                        </label>
                    </div>
                    <br>
                    <div class="ex_card_info">
                        <?php $i = 0;
                        foreach ($payment_methods as $payment_method) {
                            $i++; ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="has_payment" id="has_payment_<?= $i ?>" value="<?= $payment_method->id ?>">
                                <label class="form-check-label"><?= $payment_method->card->brand ?>( ...<?= $payment_method->card->last4 ?>)</label>
                            </div>
                        <?php } ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="has_payment" id="has_payment_new" value="new_card">
                            <label class="form-check-label">Pay by new card</label>
                        </div>
                    </div>
                    <div class="form-group new_card_enable">
                        <div class="card-body" style="border: 1px solid gray; border-radius: 3px;">
                            <div id="card-element-12">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                        </div>
                    </div>
                    <br>
                    <div class="card-footer_">
                        <button id="card-button" class="btn btn-dark" type="submit"> confirm </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var style = {
        base: {
            color: '#32325d',
            lineHeight: '18px',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '20px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    const stripeSingle = Stripe('pk_test_51LYWbpFOPvZAjJjOEiWO1I1i5v6iSNKVoIElsrWNBvQtrEqF1e8YEu4hmC48w6V0IAinCuE0clEjEvovs1CWpQz300Kb6MI1BK', {
        locale: 'en'
    });
    var elements_sing = stripeSingle.elements();
    var cardElement_single = elements_sing.create('card');

    // Add an instance of the card UI component into the `card-element` <div>
    var cardHolderName = document.getElementById('card-holder-name');
    var cardButton = document.getElementById('card-button');

    cardButton.addEventListener('click', async (e) => {
        var haspayment_method = $("input[name='has_payment']:checked").val();
        if (haspayment_method === 'new_card') {
            var {
                paymentMethod,
                error
            } = await stripeSingle.createPaymentMethod(
                'card', cardElement_single, {
                    billing_details: {
                        name: ''
                    }
                }
            );
            if (error) {
                if (event.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.innerHTML = '<p class="alert alert-danger">' + event.error.message + '</p>';
                    errorElement.appendChild(errorElement);
                }
            } else {
                var payment_id = paymentMethod.id;
                createPayment(payment_id);
            }
        }else{
            createPayment(haspayment_method);
        }
    });

    var form = document.getElementById('single-payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
    });

    // Submit the form with the token ID.
    function createPayment(payment_id) {
        var form = document.getElementById('single-payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'paymentMethod');
        hiddenInput.setAttribute('value', payment_id);
        form.appendChild(hiddenInput);
        form.submit();
    }

    $('.new_card_enable').css('display', 'none');
    $("input[type='radio']").click(function() {
        $('.new_card_enable').css('display', 'none');
        var radioValue = $("input[name='has_payment']:checked").val();
        if (radioValue === 'new_card') {
            $('.new_card_enable').css('display', 'block');
            cardElement_single.mount('#card-element-12');
        } else {
            cardElement_single.unmount('#card-element-12');
        }
    });
</script>
@endsection