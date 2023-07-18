@extends('layouts.main')

@section('head_links')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')

<div class="container">

    <header class="panel-heading" style="border: none;">
        <div class="row panel-row" style="margin:0;">
          <div class="col-sm-6 col-xs-12 header-area" style="padding-top:8px;">
            <div class="page_header_class">
              <label id="page_header" name="page_header">
                <i class="fa-solid fa-user"></i> {{__('User Account')}}: <?php echo !empty($AppUI['firstname']) ? $AppUI['firstname'] : '';?>
              </label>        
            </div>
          </div>
        </div>                 
      </header>
      
      



    <div class="row mt-3">

        <div class="col-md-7 card d-none d-sm-block">


                        <div class="card-body">

             
                            <div class="subscription-plan-info pb-1">
                                <h4 class="h4">{{ $product_object['name'] }}</h4>
                                <h2 class="h2">{{ $single_plan_info['amount']/100 }}$ <span class="intervation_type">/{{ $single_plan_info['interval'] }}</span></h2>
                            </div>
                            <br>
                            <div class="card p-4 bg-tertiary small arrow-shape">
                                <p style="font-size:13px;"><i class="fa-solid fa-circle-info fa-beat"></i> <b>Automatic renewal</b></p>
                                <p style="font-size:13px;">
                                    Your subscription will renew automatically every month as one paypent of <b>{{ $single_plan_info['amount']/100 }}.00$</b>.<br>
                                    You may cancel your subscription anytime from <b>My plan</b> section in your profile.
                                </p>
                            </div>
         
                            <ul class="list-group list-group-flush pt-4 mb-1">
                                <?php if($product_object['id'] == env('stripe_school_premium_plan_one_price_id')) {?>
                                    <!-- price id for 200 -->
                                    <li class="list-group-item">
                                        Unlimited <b>teachers</b>
                                    </li>
                                    <li class="list-group-item">
                                        Unlimited <b>students</b>
                                    </li>
                                    <li class="list-group-item">
                                        Manage your <b>students</b>
                                    </li>
                                    <li class="list-group-item">
                                        Manage your <b>teachers</b>
                                    </li>
                                    <li class="list-group-item">
                                        Manage your <b>schedule</b>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Share your schedule</b> with your team and your students
                                    </li>
                                    <li class="list-group-item">
                                        Automatic invoice based on the Schedule <b>(students and teachers)</b>
                                    </li>
                                    <li class="list-group-item">
                                        Manual invoices
                                    </li>
                                    <li class="list-group-item">
                                        Access the mobile app
                                    </li>
                                <?php } else if($single_plan_info['id'] == env('stripe_school_premium_plan_two_price_id')){ ?>
                                    <!-- price id for 125 -->
                                    <li class="list-group-item">
                                        Up to 5 <b>teachers</b>
                                    </li>
                                    <li class="list-group-item">
                                        Unlimited <b>students</b>
                                    </li>
                                    <li class="list-group-item">
                                        Manage your <b>students</b>
                                    </li>
                                    <li class="list-group-item">
                                        Manage your <b>teachers</b>
                                    </li>
                                    <li class="list-group-item">
                                        Manage your <b>schedule</b>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Share your schedule</b> with your team and your students
                                    </li>
                                    <li class="list-group-item">
                                        Automatic invoice based on the Schedule <b>(students and teachers)</b>
                                    </li>
                                    <li class="list-group-item">
                                        Manual invoices
                                    </li>
                                    <li class="list-group-item">
                                        Create your final financial statement (for taxes)
                                    </li>
                                    <li class="list-group-item">
                                        Access the mobile app
                                    </li>
                                <?php }else if($single_plan_info['id'] == env('stripe_single_cocah_premium_plan_price_id')){ ?>
                                    <!-- price id for 50 -->
                                    <!--<li class="list-group-item">
                                        Unlimited <b>students</b>
                                    </li>-->
                                    <li class="list-group-item">
                                        Manage your Unlimited <b>students</b>
                                    </li>
                                    <li class="list-group-item">
                                        Manage and share your <b>schedule</b>
                                    </li>
                                    <!--<li class="list-group-item">
                                        <b>Share your schedule</b> with your team and your students
                                    </li>-->
                                    <li class="list-group-item">
                                        Automatic system invoice <!--based on the <b>Schedule</b>-->
                                    </li>
                                    <li class="list-group-item">
                                        Manual invoices
                                    </li>
                                    <li class="list-group-item">
                                        Create final financial statement<!-- (for taxes)-->
                                    </li>
                                    <li class="list-group-item">
                                        Access the mobile app
                                    </li>
                                <?php }else if($single_plan_info['id'] == env('stripe_teacher_premium_plan_price_id')){ ?>
                                    <!-- price id for 25 -->
                                    <li class="list-group-item">
                                        Access the school <b>schedule</b>
                                    </li>
                                    <li class="list-group-item">
                                        Access the <b>app</b>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Automatic invoice</b> based on your schedule
                                    </li>
                                    <li class="list-group-item">
                                        Manal invoice
                                    </li>
                                    <li class="list-group-item">
                                        Create your final financial statement <b>(for taxes)</b>
                                    </li>
                                <?php }else{ ?>
                                    <!-- Handle other cases here -->
                                <?php } ?>
                            </ul>
                            <p style="padding:1px;"></p>
                        </div>
                    
    


            
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-body bg-tertiary">
                <div class="payment-info-top text-center">
                    <p class="txt">Enter your payment<br>details below to subscribe</p>
                </div>

                <div class="beforeError" style="min-height: 40px;"></div>
                <div id="card-errors" role="alert"></div>

                <div class="payment-form card_payment pt-1 p-1 pb-3">
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

                        <div class="payment-footer-wrapper">
                            <p class="f-txt f_margin_top">
                                Your subscription will renew automatically every month.<br>
                                By clicking "Proceed payment" you agree to the <b>Terms and Conditions</b>.
                            </p>
                        </div>

                        <div class="button-area pt-2">
                                <a class="btn btn-default" href="{{ url()->previous() }}">Cancel</a>
                            <button id="card-button" class="btn btn-success" type="submit" data-secret="{{ $intent->client_secret }}"><i class="fa-solid fa-spinner fa-spin loaderPayment" style="display:none;"></i> Proceed payment</button>
                        </div>
                    
                        <div class="payment-footer-wrapper small text-center d-block d-sm-none">
                                <p>-- Automatic renewal --</p>
                                <p>
                                    Your subscription will renew automatically every month as one paypent of <b>{{ $single_plan_info['amount']/100 }}.00$</b>. 
                                    You may cancel your subscription anytime from <b>My plan</b> section in your profile.
                                </p>
                        </div>
                        
                        
                    </form>
                </div>
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
                color: '#842029',
                fontSize: '18px',
                '::placeholder': {
                    color: '#842029',
                    fontSize: '17px',
                },
            },
        };
        var elementClasses = {
            focus: 'form-control',
            empty: 'form-control',
            invalid: 'invalid',
        };
        const stripe = Stripe('<?= env('STRIPE_KEY') ?>', { locale: 'en' }); // Create a Stripe client.
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
            $('.loaderPayment').fadeIn();
            var card_holder_name = document.getElementById("card_holder_name").value;
            stripe
                .handleCardSetup(clientSecret, cardNumber , {
                    payment_method_data: {
                        billing_details: { name: card_holder_name }
                    }
                })
                .then(function(result) {
                    $('.loaderPayment').fadeOut();
                    $('.beforeError').fadeOut('fast');
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
            if(error_code === 'invalid_number') {
                error_message = 'Invalid card number'
            }else if(error_code === 'setup_intent_authentication_failure'){
                error_message = 'The provided payment method has failed authentication. Please provide a new payment method'
            }else if(error_code === 'incomplete_number'){
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
                error_message = error_code
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
