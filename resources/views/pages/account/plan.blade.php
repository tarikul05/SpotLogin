<div class="row justify-content-center pt-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('My plan')}}</div>
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
                <div class="card p-2 mb-2">
                @if($subscriber->cancel_at_period_end)
                <?php
                if($subscription['status'] === 'trialing') {
                  echo '<span class="text-danger">Your subscription is canceled and will stop the ' . date('M j, Y', $subscription['billing_cycle_anchor']).'</span>';
                }
                if($subscription['status'] === 'active') {
                  echo '<span class="text-danger">Your subscription is canceled and will stop the ' . date('M j, Y', $subscription['current_period_end']).'</span>';
                }
                ?>
                <?php if($subscription['status'] != 'trialing' && $subscription['status'] != 'active') {?>
                    <span class="text-danger">{{ __('Your subscription is canceled and will stop the') }} <?php echo date('M j, Y', $subscription['current_period_end']); ?>.</span><br>
                    <?php } ?>
                @endif

                @if($subscriber->status === 'trialing')
                  {{ __('Your trial period is valid until') }} <?= date('M j, Y', $subscriber->trial_end) ?>.
                  <?php if($product_object && !$subscriber->cancel_at_period_end){ ?>
                    <br>
                    <small>({{ __('you will not be charged until the end of your trial period') }})</small>
                  <?php } ?>

                @endif
                @if($subscriber->status === 'active')
                <hr>
                <span class="text-success"><i class="fa-solid fa-check"></i> {{ __('Premium Plan is active on your account') }}.</span>
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





<table class="table table-bordered table-hover">

    <tr>
      <td><b>{{ __('Plan Type') }}</b></td>
      <?php
          if($product_object){
              echo '<td><span class="badge bg-success"><i class="fa-solid fa-check"></i> '.$product_object->name.'</span></td>';
          }else{
              if($AppUI->isSchoolAdmin()){
              echo '<td><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> ' . __('Trial period') .' </span></td>';
              }else{
                $today_date = new DateTime();
                $trial_ends_at = new DateTime($user->trial_ends_at);
                if (!empty($user->trial_ends_at) && $today_date <= $trial_ends_at) {
                  echo '<td><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic</span> <small>(' . __('Trial period') .')</small></td>';
                } else {

                  if($last_past_subscription) {
                  if($last_past_subscription['status'] === 'canceled'){
                      echo '<td><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic</span> <small>(' . __('Cancelled') .')</small></td>';
                  } else {
                  echo '<td><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic</span> <small>(' . __('Incomplete payment') .')</small></td>';
                  }
                  } else {
                      echo '<td><span class="badge bg-info"><i class="fa-solid fa-circle-info"></i> Basic</span> (' . __('Trial ended') .')</td>';
                  }

                }
              }
          }
      ?>
    </tr>

    @if ($subscription['plan']['billing_scheme'] === 'tiered' && $subscription['plan']['tiers_mode'] === 'volume')
    <tr>
<td>{{ __('Number of teachers') }}</td>
        <td>{{ $subscription['quantity'] }} teachers available</td>
    </tr>
    @endif

    <?php if(!empty($user->trial_ends_at)){ ?>
      <tr>
          <?php
            if($AppUI->isSchoolAdmin()){
                echo '<td><b>' . __('Trial Valid Until') . '</b></td>';
            }else{
                echo '<td><b>' . __('Basic Valid Until') . '</b></td>';
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
            echo '<td><b>' . __('Next payment') . '</b></td><td>' . date('M j, Y', $subscription['billing_cycle_anchor']).'</td>';
            }
            if($subscription['status'] === 'active') {
              echo '<td><b>' . __('Next payment') . '</b></td><td>' . date('M j, Y', $subscription['current_period_end']).'</td>';
            }
            }
          ?>
        </tr>
      <?php } ?>

      <tr>
        <td><b>{{ __('Price') }}</b></td>
          <?php if(!empty($subscription)) { ?>
              <td><span class="price"><?= '$'.($subscription['plan']['amount_decimal'])/100 ?></span>
              <span class="interval"><?= '/'.$subscription['plan']['interval'] ?></span></td>
          <?php }else{ ?>
              <td><span class="price">{{ __('Free') }}</span></td>
          <?php } ?>
      </tr>

      <?php if(!empty($subscription)) { ?>
        <tr>
          <td><b>{{ __('Invoice') }}</b></td><td>
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
      <a class="btn btn-warning btn-md disabled" href="#"><i class="fa-solid fa-arrow-right"></i> {{ __('Cancel my subscription') }}</a>
      @else
      <a class="btn btn-warning btn-md" href="#" id="buttonCancelSubscription"><i class="fa-solid fa-arrow-right"></i> {{ __('Cancel my subscription') }}</a>
      @endif
    <?php } else { ?>
      <a class="btn btn-success" href="{{ route('subscription.upgradePlan') }}"><i class="fa-regular fa-bell fa-bounce"></i>  {{ __('Choose a Plan & Upgrade Now') }} !</a>
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
                <a class="btn btn-danger" href="{{ route('subscription.cancelPlan') }}">Yes, Cancel</a>
            </div>
        </div>
    </div>
</div>
