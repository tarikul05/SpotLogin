@extends('layouts.main')

@section('head_links')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
<div id="card-errors" role="alert"></div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="subscription-form-wrapper">
                <div class="payment-info-top">
                    <h4 class="h4">Payment details</h4>
                    <p class="txt">Enter your payment details below to subscribe </p>
                </div>
                <div class="subscription-plan-info">
                    <h4 class="h4">{{ $product_object['name'] }}</h4>
                    <h2 class="h2">{{ $single_plan_info['amount']/100 }}$ <span class="intervation_type">/{{ $single_plan_info['interval'] }}</span></h2>
                </div>
                <div class="payment-form card_payment">
                    
                    <form action="{{ route('subscribe.store') }}" method="post" id="payment-form-sub">
                        @csrf
                        <div class="form-group">
                            <label for="card_holder_name">Card holder</label>
                            <input type="hidden" name="plan" value="{{ $single_plan_info['id'] }}" />
                            <input type="hidden" name="plan_name" value="{{ $product_object['name'] }}" />
                            <input name="card_holder_name" type="input" class="form-control" id="card_holder_name" placeholder="Card holder">
                        </div>
                        <div class="form-group">
                            <label for="card_number">Card number</label>
                            <div id="card_number"></div>
                            <!-- <input data-stripe="number" name="" type="input" class="form-control" id="card_number" placeholder="Card number"> -->
                        </div>
                        <div class="form-row col_sep_div">
                            <div class="padding_right">
                                <div class="form-group">
                                    <label for="expiry">Expiry</label>
                                    <div id="cardExpiry"></div>
                                    <!-- <input data-stripe="exp-month-year" name="" type="input" class="form-control" id="expiry" placeholder="05/24"> -->
                                </div>
                            </div>
                            <div class="padding_left">
                                <div class="form-group">
                                    <label for="cvv">CVV</label>
                                    <div id="cardCvc"></div>
                                    <!-- <input data-stripe="cvc" name="" type="input" class="form-control" id="cvv" placeholder="123"> -->
                                </div>
                            </div>
                            <!-- <div class="padding_left">
                                <div class="form-group">
                                    <label for="cvv">ZIP</label>
                                    <div id="cardZip"></div>
                                    <input data-stripe="zip" name="" type="input" class="form-control" id="cvv" placeholder="123">
                                </div>
                            </div> -->
                        </div>
                        <div class="button-area">
                            <div class="btn btn_c">
                                <a class="btn-cancle" href="">Cancel</a>
                            </div>
                            <button id="card-button" class="btn btn-blue" type="submit" data-secret="{{ $intent->client_secret }}"
                            >Proceed payment</button>
                        </div>
                    
                        <div class="payment-footer-wrapper">
                            <p class="title">Automatic renewal:</p>
                            <p class="f-txt">
                                Your subscription will renew automatically every month as one paypent of <b>{{ $single_plan_info['amount']/100 }}.00$</b>. 
                                You may cancel your subscription anytime from <b>My subscription</b> section in your profile.
                            </p>
                            <p class="f-txt f_margin_top">
                                By clicking “Proceed payment” you agree to the <b>Terms and Conditions</b>.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-6">
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
                                <input type="text" name="card_holder_name" id="card_holder_name" class="form-control">
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
        </div> -->
    </div>
</div>
    <script>
        var elementStyles = {
            base: {
            color: '#002A44',
            fontWeight: 400,
            padding:'8px 12px',
            fontFamily: 'Gilroy',
            fontSize: '18px',
            '::placeholder': {
                color: '#002A44',
                fontSize: '18px',
            },
            ':-webkit-autofill': {
                color: '#002A44',
                fontSize: '18px',
            },
            },
            invalid: {
                color: '#E25950',
                fontSize: '18px',
                '::placeholder': {
                    color: '#FFCCA5',
                    fontSize: '18px',
                },
            },
        };
        var elementClasses = {
            focus: 'form-control',
            empty: 'form-control',
            invalid: 'invalid',
        };
        const stripe = Stripe('pk_test_51LYWbpFOPvZAjJjOEiWO1I1i5v6iSNKVoIElsrWNBvQtrEqF1e8YEu4hmC48w6V0IAinCuE0clEjEvovs1CWpQz300Kb6MI1BK', { locale: 'en' }); // Create a Stripe client.
        const elements = stripe.elements();
        var cardNumber = elements.create('cardNumber', {
            style: elementStyles,
            classes: elementClasses,
        });
        cardNumber.mount('#card_number');

        var cardExpiry = elements.create('cardExpiry', {
            style: elementStyles,
            classes: elementClasses,
        });
        cardExpiry.mount('#cardExpiry');

        var cardCvc = elements.create('cardCvc', {
            style: elementStyles,
            classes: elementClasses,
        });
        cardCvc.mount('#cardCvc');

        // var postalCode = elements.create('postalCode', {
        //     style: elementStyles,
        //     classes: elementClasses,
        // });
        // postalCode.mount('#cardZip');

        // const cardElement = '';
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        // cardElement.mount('#card-element');
        // Handle real-time validation errors from the card Element.
        // cardElement.addEventListener('change', function(event) {
        //     var displayError = document.getElementById('card-errors');
        //     if (event.error) {
        //         displayError.textContent = event.error.message;
        //     } else {
        //         displayError.textContent = '';
        //     }
        // });
        
        // Handle form submission.
        var form = document.getElementById('payment-form-sub');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var card_holder_name = document.getElementById("card_holder_name").value;
            stripe
                .handleCardSetup(clientSecret, cardNumber , {
                    payment_method_data: {
                        billing_details: { name: card_holder_name }
                    }
                })
                .then(function(result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.innerHTML = '<div class="alert alert-dismissible alert-danger alert-block">'+
                        '<strong>' + errorMessage(result.error.code) +'</strong>'+
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'+   
                        '</div>';
                        window.scrollTo(0, 10);
                    } else {
                        stripeTokenHandler(result.setupIntent.payment_method);
                    }
                });
        });

        function errorMessage(error_code){
            var error_message = '';
            if(error_code === 'incomplete_number'){
                error_message = 'Incomplete card number'
            }else if(error_code === 'incomplete_expiry'){
                error_message = 'Incomplete expiry date'
            }else if(error_code === 'incomplete_cvc'){
                error_message = 'Incomplete security code'
            }else if(error_code === 'incorrect_cvc'){
                error_message = 'Incorrect security code'
            }else if(error_code === 'expired_card'){
                error_message = 'Your card has expired'
            }else if(error_code === 'processing_error'){
                error_message = 'An error occurred while processing your card. Please try again later'
            }else if(error_code === 'card_declined'){
                error_message = 'your card has been declined'
            }else{
                error_message = error_code.'error occurred'
            }
            return error_message;
        }
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
