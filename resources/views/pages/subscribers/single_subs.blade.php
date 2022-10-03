@extends('layouts.main')

@section('head_links')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="padding:10px 14px;">
                <div id="card-errors" role="alert"></div>
                <form action="{{ route('subscribe.store') }}" method="post" id="payment-form-sub">
                    @csrf
                    <br>            
                    <div class="form-group">
                        <div class="card-header">
                            <label for="card-element" style="font-size: 20px;">
                                Enter your credit card information
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="">
                                <label for="">Card Holder Name</label>
                                <input type="text" name="card_holder_name" id="card_holder_namename" class="form-control">
                            </div>
                            <br>
                            <div id="card-element"></div>
                            <input type="hidden" name="plan" value="{{ $single_plan_info['id'] }}" />
                            <input type="hidden" name="plan_name" value="{{ $product_object['name'] }}" />
                        </div>
                    </div>
                    <div class="card-footer">
                      <button
                      id="card-button"
                      class="btn btn-dark"
                      type="submit"
                      data-secret="{{ $intent->client_secret }}"
                    > {{ $single_plan_info['amount']/100 }} Pay </button>
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

        const stripe = Stripe('pk_test_51LYWbpFOPvZAjJjOEiWO1I1i5v6iSNKVoIElsrWNBvQtrEqF1e8YEu4hmC48w6V0IAinCuE0clEjEvovs1CWpQz300Kb6MI1BK', { locale: 'en' }); // Create a Stripe client.
        const elements = stripe.elements();
        const cardElement = elements.create('card', { style: style });
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
         
        cardElement.mount('#card-element');
        // Handle real-time validation errors from the card Element.
        cardElement.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
        
        // Handle form submission.
        var form = document.getElementById('payment-form-sub');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var card_holder_namename = document.getElementById("card_holder_namename").value;
            stripe
                .handleCardSetup(clientSecret, cardElement, {
                    payment_method_data: {
                        billing_details: { name: card_holder_namename }
                    }
                })
                .then(function(result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        stripeTokenHandler(result.setupIntent.payment_method);
                    }
                });
        });

        function stripeTokenHandler(paymentMethod) {
            var form = document.getElementById('payment-form-sub');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'paymentMethod');
            hiddenInput.setAttribute('value', paymentMethod);
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>
@endsection
