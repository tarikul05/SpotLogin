
<style>
    .example.example4 {
  background-color: #f6f9fc;
}



.example.example4 form {
  max-width: 496px !important;
  padding: 0 15px;
}

.example.example4 form > * + * {
  margin-top: 20px;
}

.example.example4 .container {
  background-color: #fff;
  box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
  border-radius: 4px;
  padding: 3px;
}

.example.example4 fieldset {
  border-style: none;
  padding: 5px;
  margin-left: -5px;
  margin-right: -5px;
  background: rgba(18, 91, 152, 0.05);
  border-radius: 8px;
}

.example.example4 fieldset legend {
  float: left;
  width: 100%;
  text-align: center;
  font-size: 13px;
  color: #8898aa;
  padding: 3px 10px 7px;
}

.example.example4 .card-only {
  display: block;
}
.example.example4 .payment-request-available {
  display: none;
}

.example.example4 fieldset legend + * {
  clear: both;
}

#card-errors {
    color:#b54f48;
    margin-top:10px;
}

.example.example4 input, .example.example4 button {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  outline: none;
  border-style: none;
  color: #fff;
}

.example.example4 input:-webkit-autofill {
  transition: background-color 100000000s;
  -webkit-animation: 1ms void-animation-out;
}

.example.example4 #example4-card {
  padding: 10px;
  margin-bottom: 2px;
}

.example.example4 input {
  -webkit-animation: 1ms void-animation-out;
}

.example.example4 input::-webkit-input-placeholder {
  color: #9bacc8;
}

.example.example4 input::-moz-placeholder {
  color: #9bacc8;
}

.example.example4 input:-ms-input-placeholder {
  color: #9bacc8;
}

.example.example4 button {
  display: block;
  width: 100%;
  height: 37px;
  background-color: #d782d9;
  border-radius: 2px;
  color: #fff;
  cursor: pointer;
}

.example.example4 button:active {
  background-color: #b76ac4;
}

.example.example4 .error svg .base {
  fill: #e25950;
}

.example.example4 .error svg .glyph {
  fill: #f6f9fc;
}

.example.example4 .error .message {
  color: #e25950;
}

.example.example4 .success .icon .border {
  stroke: #ffc7ee;
}

.example.example4 .success .icon .checkmark {
  stroke: #d782d9;
}

.example.example4 .success .title {
  color: #32325d;
}

.example.example4 .success .message {
  color: #8898aa;
}

.example.example4 .success .reset path {
  fill: #d782d9;
}
.coupon_result {
  font-size: 12px;
  color:#0075bf;
}
</style>




<div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">{{ __('My plan')}}</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->

                <!--@if(!$AppUI->isStudent())
                <span id="page_header" class="page_title text-black"></span>
                <?php if(!empty($subscription)) { ?>
                  <div class="alert alert-info  h6">
                    <i class="fa-solid fa-check"></i>  Premium Access activated.
                  </div>
                <?php }else{ ?>
                  <div class="alert alert-info"><i class="fa-solid fa-circle-info"></i> Activate your Premium access and enable all features <a href="{{ route('subscription.upgradePlan') }}"> Choose a plan and upgrade now! </a></div>
                <?php } ?>
              @endif-->




              <?php if($product_object){?>
                <div class="p-2 mb-2">
                @if($subscriber->cancel_at_period_end)
                <?php
                if($subscription['status'] === 'trialing') {
                  echo '<span class="text-danger"><i class="fa fa-warning"></i> Your subscription is canceled and will stop the ' . date('M j, Y', $subscription['billing_cycle_anchor']).'</span><br>';
                }
                if($subscription['status'] === 'active') {
                  echo '<span class="text-danger"><i class="fa fa-warning"></i> Your subscription is canceled and will stop the ' . date('M j, Y', $subscription['current_period_end']).'</span><br>';
                }
                ?>
                <?php if($subscription['status'] != 'trialing' && $subscription['status'] != 'active') {?>
                    <span class="text-danger">{{ __('Your subscription is canceled and will stop the') }} <?php echo date('M j, Y', $subscription['current_period_end']); ?>.</span><br>
                    <?php } ?>
                @endif
                  <br>
                @if($subscriber->status === 'trialing')
                  {{ __('Your trial period is valid until') }} <?= date('M j, Y', $subscriber->trial_end) ?>.
                  <?php if($product_object && !$subscriber->cancel_at_period_end){ ?>
                    <br>
                    <small>({{ __('you will not be charged until the end of your trial period') }})</small>
                  <?php } ?>

                @endif
                @if($subscriber->status === 'active')
                <span class="text-success"><i class="fa-solid fa-check"></i> {{ __('Premium Plan is active on your account') }}.</span><br>
                    Period in process : <?php echo date('M j, Y', $subscription['current_period_start']); ?> - <?php echo date('M j, Y', $subscription['current_period_end']); ?>
                @endif
            </div>
              <?php } else { ?>

              <?php
                if($last_past_subscription) {
                    if($last_past_subscription['status'] === 'canceled'){
                        echo '<div class="card p-2 mb-2">';
                        echo 'Your subscription has been canceled since the ' . date('M j, Y', $last_past_subscription['billing_cycle_anchor']);
                        echo '</div>';
                    }
                }
                ?>

              <?php }?>





<table class="table table-stripped table-hover">

    <tr>
      <td class="titleFieldPage" width="200"><b>{{ __('Plan Type') }}</b></td>
      <?php
          if($product_object){
              echo '<td class="titleFieldPage"><span class="badge bg-success">'.$product_object->name.'</span></td>';
          }else{
              if($AppUI->isSchoolAdmin()){
              echo '<td class="titleFieldPage"><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> ' . __('Trial period') .' </span></td>';
              }else{
                $today_date = new DateTime();
                $trial_ends_at = new DateTime($user->trial_ends_at);
                if (!empty($user->trial_ends_at) && $today_date <= $trial_ends_at) {
                  echo '<td class="titleFieldPage"><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic</span> <small>(' . __('Trial period') .')</small></td>';
                } else {

                  if($last_past_subscription) {
                  if($last_past_subscription['status'] === 'canceled'){
                      echo '<td class="titleFieldPage"><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic</span> <small>(' . __('Cancelled') .')</small></td>';
                  } else {
                  echo '<td class="titleFieldPage"><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic</span> <small>(' . __('Incomplete payment') .')</small></td>';
                  }
                  } else {
                      echo '<td class="titleFieldPage"><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic</span> (' . __('Trial ended') .')</td>';
                  }

                }
              }
          }
      ?>
    </tr>

    @if ($subscription && ($subscription['plan']['billing_scheme'] === 'tiered' && $subscription['plan']['tiers_mode'] === 'volume'))
    <tr>
        <td class="titleFieldPage">{{ __('Number of teachers') }}</td>
        <td class="titleFieldPage">{{ $subscription['quantity'] }} teachers available</td>
    </tr>
    @endif

    <?php if(!empty($user->trial_ends_at)){ ?>
      <tr>
          <?php
            if($AppUI->isSchoolAdmin()){
                echo '<td class="titleFieldPage"><b>' . __('Trial Valid Until') . '</b></td>';
            }else{
                echo '<td class="titleFieldPage"><b>' . __('Basic Valid Until') . '</b></td>';
            }
          ?>
          <td>
            <?= date('M j, Y', strtotime($user->trial_ends_at)) ?>
          </td>
      </tr>
    <?php } ?>

    <?php
      if($subscription) { ?>
        <tr>
          <?php {
            if($subscription['status'] === 'trialing') {
            echo '<td class="titleFieldPage"><b>' . __('Next payment') . '</b></td><td>' . date('M j, Y', $subscription['billing_cycle_anchor']).'</td>';
            }
            if($subscription['status'] === 'active') {
              echo '<td class="titleFieldPage"><b>' . __('Next payment') . '</b></td><td>' . date('M j, Y', $subscription['current_period_end']).'</td>';
            }
            }
          ?>
        </tr>
        <tr>
          <td class="titleFieldPage"><b>{{ __('Payment Method') }}</b></td>
          <td>{{$user->getStripeUserMethod()}} [ <a href="#" id="openNewPaymentMethod">change</a> ]</td>
        </tr>
      <?php } ?>

      <tr>
        <td class="titleFieldPage"><b>{{ __('Price') }}</b></td>
          <?php if(!empty($subscription)) { ?>
              <td><span class="price"><?= '$'.($subscription['plan']['amount_decimal'])/100 ?></span>
              <span class="interval"><?= '/'.$subscription['plan']['interval'] ?></span></td>
          <?php }else{ ?>
              <td><span class="price">{{ __('Free') }}</span></td>
          <?php } ?>
      </tr>

      <?php if(!empty($subscription)) { ?>
        <tr>
          <td class="titleFieldPage"><b>{{ __('Invoice') }}</b></td><td>
            <i class="fa-solid fa-download"></i>
            <a class="action_link" target="_blank" href="<?= $invoice_url['hosted_invoice_url'] ?>">
            <span class="action_icon">{{ __('Download') }}</span>
        </a></td>
        </tr>
      <?php } ?>

  </table>




  <div class="mt-2 btns-plan">
    <?php if(!empty($subscriber)) { ?>
      <!--<a class="btn btn-success btn-md disabled" href="{{ route('subscription.upgradePlan') }}">Choose a Plan</a>-->
      @if($subscriber->cancel_at_period_end === true || $subscriber->cancel_at_period_end === "true")
      <a class="btn btn-outline-warning btn-md" href="#" id="buttonReactivateSubscription"><i class="fa-solid fa-arrow-right"></i> {{ __('I want to continue my subscription') }}</a>
      @else
      <a class="btn btn-outline-warning btn-md" href="#" id="buttonCancelSubscription"><i class="fa-solid fa-arrow-right"></i> {{ __('Cancel my subscription') }}</a>
      @endif
    <?php } else { ?>
      <a class="btn btn-outline-primary" href="{{ route('subscription.upgradePlan') }}"><i class="fa-regular fa-bell fa-bounce"></i>  {{ __('Choose a Plan & Upgrade Now') }} !</a>
    <?php } ?>
  </div>





            </div>
        </div>
    </div>
</div>


<div class="modal" id="cancel_subscription">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Subscription</h5>
                <span type="button" class="close" data-dismiss="modal"><i class="fa-solid fa-question fa-beat"></i></span>
            </div>
            <div class="modal-body">
              <?php if(!empty($subscription)) { ?>
                <div class="text-center">
                    <i class="fas fa-exclamation-circle fa-5x text-danger"></i> <!-- Utilisez l'icône d'alerte ou d'information souhaité -->
                </div>
                <p class="text-center mt-3">Do you really want to cancel your subscription?<br>
                <small>(Your premium access will be valid until

                  @if($subscriber->status === 'active')
                  <?php echo date('M j, Y', $subscription['current_period_end']); ?>
                  @else
                  <?php echo date('M j, Y', $subscription['billing_cycle_anchor']); ?>
                  @endif

                  )</small>
                </p>
              <?php } ?>
            </div>
            <div class="modal-footer">
                <a href="{{ route('updateTeacher') }}" class="btn btn-secondary close"  data-dismiss="modal">No</a>
                <a class="btn btn-danger-outline" href="{{ route('subscription.cancelPlan') }}">Yes, Cancel</a>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="reactivate_subscription">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Re-activate Subscription</h5>
              <span type="button" class="close" data-dismiss="modal"><i class="fa-solid fa-question fa-beat"></i></span>
          </div>
          <div class="modal-body">
            <?php if(!empty($subscription)) { ?>
              <div class="text-center">
                  <i class="fas fa-exclamation-circle fa-5x text-danger"></i> <!-- Utilisez l'icône d'alerte ou d'information souhaité -->
              </div>
              <p class="text-center mt-3">Do you really want to re-activate your subscribtion ?<br>
             You will be charged the next time you renew your subscription.<br>
              </p>
            <?php } ?>
          </div>
          <div class="modal-footer">
              <a href="{{ route('updateTeacher') }}" class="btn btn-secondary close"  data-dismiss="modal">No</a>
              <a class="btn btn-success" href="{{ route('subscription.reactivatePlan') }}">Yes, Re-activate</a>
          </div>
      </div>
  </div>
</div>

<div class="modal" id="newPaymentMethod">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title"><small>Change Payment Method</small></h5>
          </div>
          <div class="modal-body">
            <?php if(!empty($subscription)) { ?>
              <p>The new payment method will be used for your next renewals.</p>
              <div id="card-element"></div>
              <div id="card-errors" style="color: red;"></div>

            <?php } ?>
          </div>
          <div class="modal-footer">
              <a href="{{ route('updateTeacher') }}" class="btn btn-secondary close"  data-dismiss="modal">Cancel</a>
              <button id="submit-button" class="btn btn-success" disabled>Validate</button>
          </div>
      </div>
  </div>
</div>



<script src="https://js.stripe.com/v3/"></script>
<script>
  $(document).ready(function () {
    if(window.location.href.indexOf('#myplan') != -1) {
      activaTab('tab_2');
    }
  });

  function activaTab(tab) {
		$('.nav-tabs button[data-bs-target="#' + tab + '"]').tab('show');
	};

  $(document).ready(function () {
    $('#openNewPaymentMethod').on('click', function () {
      const stripe = Stripe('<?= env('STRIPE_KEY') ?>', { locale: 'en' });
      // Créez un élément de type carte
      const elements = stripe.elements({
        disableLink:true,
        locale: window.__exampleLocale,
      });
      const cardElement = elements.create('card',{hidePostalCode: true});
      cardElement.mount('#card-element');

      // Sélectionnez les éléments HTML
      const errorDiv = document.getElementById('card-errors');
      const submitButton = document.getElementById('submit-button');

      // Ajoutez un gestionnaire d'événement "change"
      cardElement.on('change', function (event) {
        if (event.error) {
          errorDiv.textContent = event.error.message;
          errorDiv.style.display = 'block';
          submitButton.disabled = true; // Désactivez le bouton en cas d'erreur
        } else {
          errorDiv.style.display = 'none';
          errorDiv.textContent = '';

          // Activez le bouton uniquement si les informations sont complètes
          submitButton.disabled = !event.complete;
        }
      });

      // Action lorsque le bouton "Enregistrer la carte" est cliqué
      $('#submit-button').on('click', async function () {
        $('#pageloader').fadeIn('slow');
        try {
          // Demandez un Setup Intent au serveur
          const response = await fetch(BASE_URL + '/create-setup-intent', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
              customer_id: '<?= $user->stripe_id ?>', // Passez l'ID du client
            }),
          });

          const setupIntentData = await response.json();
          const clientSecret = setupIntentData.client_secret;

          // Confirmez la carte avec Stripe.js
          const { setupIntent, error } = await stripe.confirmCardSetup(clientSecret, {
            payment_method: {
              card: cardElement,
            },
          });

          if (error) {
            $('#pageloader').hide();
            errorDiv.textContent = error.message;
            errorDiv.style.display = 'block';
            console.error('Erreur lors de la confirmation :', error);
          } else {
            // Une fois le Setup Intent confirmé, enregistrez le Payment Method dans le profil
            const saveResponse = await fetch(BASE_URL + '/save-payment-method', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?= csrf_token() ?>',
              },
              body: JSON.stringify({
                customer_id: '<?= $user->stripe_id ?>',
                payment_method: setupIntent.payment_method,
              }),
            });

            const saveResult = await saveResponse.json();
            if (saveResult.success) {
              $('#pageloader').hide();
              $('#newPaymentMethod').modal("hide");

              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Payment method updated successfully',
                allowOutsideClick: false, 
                allowEscapeKey: false,  
                confirmButtonText: 'OK', 
              }).then((result) => {
                if (result.isConfirmed) {
                  $('#pageloader').fadeIn('slow');
                  window.location.href = window.location.origin + window.location.pathname + '#myplan';
                  window.location.reload();
                }
              });
              

            } else {
              $('#pageloader').hide();
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Failed to update payment method',
                });
            }
          }
        } catch (err) {
          $('#pageloader').hide();
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to update payment method',
            });
        }
      });
    });
  });
</script>
  