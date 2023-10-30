@extends('layouts.main')
@section('head_links')
<style>
    .example.example4 {
  background-color: #f6f9fc;
}

.example.example4 * {
  font-family: Inter, Open Sans, Segoe UI, sans-serif;
  font-size: 16px;
  font-weight: 500;
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

.errorStripe {
    color:#e25950;
    padding-top:10px;
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
</style>
@endsection

@section('content')

<div class="container">

    <h5>Coach Plan</h5>


        <div class="pricing-title text-center pt-3">
            <!--<h3 class="h3 text-default text-center">Choose a plan and enable all features</h3>-->

            <?php if($is_subscribed && (!empty($subscription))){ ?>
                <h4 class="text-success mb-5">You're already subscribed to a premium plan <br><small>your subscription is valid until <?php echo  $subscription['billing_cycle_anchor'] ? date('M j, Y', $subscription['billing_cycle_anchor']) : ''; ?></small></h4>
            <?php } ?>

        </div>
        <div class="row justify-content-center pb-4">
            <div class="col-md-12 plans">

                <div class="columns p-4 card" id="current-plan">

                        <div class="card-body p-4 bg-tertiary">

                          <div class="h4 pt-2" style="color:#0075bf;">My current plan</div>

                          <?php if($is_subscribed && (!empty($subscription)) ){?>
                            @if($subscription['cancel_at_period_end'])
                            <?php
                            if($subscription['status'] === 'trialing') {
                              echo '<span class="text-danger">Your subscription is canceled and will stop the' . date('M j, Y', $subscription['billing_cycle_anchor']).'</span>';
                            }
                            if($subscription['status'] === 'active') {
                              echo '<span class="text-danger">Your subscription is canceled and will stop the' . date('M j, Y', $subscription['current_period_end']).'</span>';
                            }
                            ?>
                              <span class="text-danger">Your subscription is canceled and will stop the <?php echo date('M j, Y', $subscription['current_period_end']); ?>.</span><br>
                            @endif

                            @if($subscription['status'] === 'trialing')
                              Your trial period is valid until <?= date('M j, Y', $subscription['billing_cycle_anchor']) ?>.
                              <?php if($is_subscribed){ ?>
                                <br>
                                <small>(you will not be charged until the end of your trial period)</small>
                              <?php } ?>
                              <hr>
                            @endif
                            @if($subscription['status'] === 'active')
                                Premium Plan is active on your account.<br>
                                Period in process : <?php echo date('M j, Y', $subscription['current_period_start']); ?> - <?php echo date('M j, Y', $subscription['current_period_end']); ?>
                            @endif
                          <?php } ?>




                          <table class="table table-stripped table-hover">

                            <tr>

                              <?php
                                  if($is_subscribed){
                                      echo '<td><b>Plan Type</b><br><span class="badge bg-success"><i class="fa-solid fa-check"></i> Premium</span></td>';
                                  }else{
                                      if($AppUI->isSchoolAdmin()){
                                          echo '<td><b>Plan Type</b><br><span class="badge bg-info">Trial period</span></td>';
                                      }else{
                                        $today_date = new DateTime();
                                        $trial_ends_at = new DateTime($user->trial_ends_at);
                                        if (!empty($user->trial_ends_at) && $today_date <= $trial_ends_at) {
                                          echo '<td><b>Plan Type</b><br><span class="badge bg-info">Basic</span> <small>(Trial period)</small></td>';
                                        } else {
                                          echo '<td><b>Plan Type</b><br><span class="badge bg-warning">Basic (Trial ended)</span></td>';
                                        }
                                      }
                                  }
                              ?>
                            </tr>

                            <?php if(!empty($user->trial_ends_at)){ ?>
                              <tr>
                                  <?php
                                    if($AppUI->isSchoolAdmin()){
                                        $until = '<b>Trail Valid Until</b>';
                                    }else{
                                        $until = '<b>Basic Valid Until</b>';
                                    }
                                  ?>
                                  <td>
                                   <?php echo $until; ?><br><?= date('M j, Y', strtotime($user->trial_ends_at)) ?>
                                  </td>
                              </tr>
                            <?php } ?>

                            <?php
                              if($subscription) { ?>
                                <tr>
                                  <?php {
                                    if($subscription['status'] === 'trialing') {
                                      echo '<td><b>Next payment</b><br>' . date('M j, Y', $subscription['billing_cycle_anchor']).'</td>';
                                    }
                                    if($subscription['status'] === 'active') {
                                      echo '<td><b>Next payment</b><br>' . date('M j, Y', $subscription['current_period_end']).'</td>';
                                    }
                                    }
                                  ?>
                                </tr>
                              <?php } ?>

                              <tr>

                                  <?php if(!empty($subscription)) { ?>
                                      <td><b>Price</b><br><span class="price"><?= '$'.($subscription['plan']['amount_decimal'])/100 ?></span>
                                      <span class="interval"><?= '/'.$subscription['plan']['interval'] ?></span></td>
                                  <?php }else{ ?>
                                      <td><b>Price</b><br><span class="price">Free</span></td>
                                  <?php } ?>
                              </tr>

                          </table>


                    <div class="text-center">
                    <?php
                          if($is_subscribed){
                          }else{
                                $today_date = new DateTime();
                                $trial_ends_at = new DateTime($user->trial_ends_at);
                                if (!empty($user->trial_ends_at) && $today_date <= $trial_ends_at) {
                                  echo '<h5 class="pt-5"><small>Get your Premium Plan before the end of your trial period</small></h5>';
                                } else {
                                    echo '<h5>Get your Premium since your trial period is ended.<p><br></p><small>Continue to access all features now !</small></h5>';
                                }
                          }
                      ?>
                    </div>



                          <div class="mt-2 btns-plan">
                            <?php if($is_subscribed && (!empty($subscription))) { ?>
                              <a class="btn btn-success btn-md disabled" href="{{ route('subscription.upgradePlan') }}">Congratulations ! you already have a premium plan !</a>
                              <br><br>
                             <?php if($subscription['cancel_at_period_end'] === true || $subscription['cancel_at_period_end'] === "true") { ?>
                              <a class="btn btn-warning btn-md disabled w-100" href="#"><i class="fa-solid fa-arrow-right"></i> Cancel my subscription !</a>
                              <?php } else { ?>
                              <a class="btn btn-warning btn-md w-100" href="#" id="buttonCancelSubscription"><i class="fa-solid fa-arrow-right"></i> Cancel my subscription !</a>
                              <?php } ?>
                            <?php } else { ?>
                              <!--<a class="btn btn-success" href="#">Please choose a plan and unlock all features</a>-->
                            <?php } ?>
                          </div>

                        </div>

                </div>


                <?php sort($plans); ?>
                @foreach($plans as $plan)
                <div class="columns premium-plan">
                    <ul class="price">
                        <li>
                            <div class="plan_name">{{ $plan['plan_name']->name }}</div>
                            <div class="plan_interval">${{ number_format($plan['amount'], 2) }}<span class="plan_type"> /{{ $plan['interval'] }}</span></div>
                        </li>

                            <li>
                                <span class="svg_img">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99991 16.9093C10.1216 16.9093 12.1565 16.0664 13.6568 14.5662C15.1571 13.0659 15.9999 11.031 15.9999 8.9093C15.9999 6.78757 15.1571 4.75274 13.6568 3.25245C12.1565 1.75216 10.1216 0.909302 7.99991 0.909302C5.87818 0.909302 3.84335 1.75216 2.34305 3.25245C0.842763 4.75274 -9.15527e-05 6.78757 -9.15527e-05 8.9093C-9.15527e-05 11.031 0.842763 13.0659 2.34305 14.5662C3.84335 16.0664 5.87818 16.9093 7.99991 16.9093ZM11.8569 7.1003C11.9148 7.02059 11.9565 6.93025 11.9795 6.83444C12.0025 6.73864 12.0064 6.63924 11.991 6.54192C11.9755 6.44461 11.9411 6.35128 11.8896 6.26727C11.8381 6.18326 11.7706 6.11021 11.6909 6.0523C11.6112 5.99439 11.5209 5.95274 11.4251 5.92974C11.3292 5.90674 11.2298 5.90284 11.1325 5.91825C11.0352 5.93367 10.9419 5.9681 10.8579 6.01958C10.7739 6.07106 10.7008 6.13859 10.6429 6.2183L7.15991 11.0083L5.27991 9.1283C5.21069 9.0567 5.1279 8.99961 5.03638 8.96034C4.94486 8.92108 4.84644 8.90044 4.74685 8.89962C4.64727 8.8988 4.54852 8.91782 4.45636 8.95558C4.36421 8.99333 4.2805 9.04906 4.21011 9.11951C4.13973 9.18997 4.08407 9.27373 4.04641 9.36592C4.00874 9.45811 3.98981 9.55688 3.99072 9.65646C3.99163 9.75605 4.01237 9.85445 4.05172 9.94593C4.09107 10.0374 4.14824 10.1201 4.21991 10.1893L6.71991 12.6893C6.79654 12.766 6.88887 12.8251 6.99055 12.8627C7.09224 12.9002 7.20085 12.9153 7.30892 12.9068C7.41699 12.8984 7.52193 12.8666 7.61654 12.8137C7.71114 12.7608 7.79315 12.688 7.85691 12.6003L11.8569 7.1003Z" fill="white"/>
                                    </svg>
                                </span>
                                <span class="text-area">Manage your Unlimited <b>students</b></span>
                            </li>
                            <li>
                                <span class="svg_img">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99991 16.9093C10.1216 16.9093 12.1565 16.0664 13.6568 14.5662C15.1571 13.0659 15.9999 11.031 15.9999 8.9093C15.9999 6.78757 15.1571 4.75274 13.6568 3.25245C12.1565 1.75216 10.1216 0.909302 7.99991 0.909302C5.87818 0.909302 3.84335 1.75216 2.34305 3.25245C0.842763 4.75274 -9.15527e-05 6.78757 -9.15527e-05 8.9093C-9.15527e-05 11.031 0.842763 13.0659 2.34305 14.5662C3.84335 16.0664 5.87818 16.9093 7.99991 16.9093ZM11.8569 7.1003C11.9148 7.02059 11.9565 6.93025 11.9795 6.83444C12.0025 6.73864 12.0064 6.63924 11.991 6.54192C11.9755 6.44461 11.9411 6.35128 11.8896 6.26727C11.8381 6.18326 11.7706 6.11021 11.6909 6.0523C11.6112 5.99439 11.5209 5.95274 11.4251 5.92974C11.3292 5.90674 11.2298 5.90284 11.1325 5.91825C11.0352 5.93367 10.9419 5.9681 10.8579 6.01958C10.7739 6.07106 10.7008 6.13859 10.6429 6.2183L7.15991 11.0083L5.27991 9.1283C5.21069 9.0567 5.1279 8.99961 5.03638 8.96034C4.94486 8.92108 4.84644 8.90044 4.74685 8.89962C4.64727 8.8988 4.54852 8.91782 4.45636 8.95558C4.36421 8.99333 4.2805 9.04906 4.21011 9.11951C4.13973 9.18997 4.08407 9.27373 4.04641 9.36592C4.00874 9.45811 3.98981 9.55688 3.99072 9.65646C3.99163 9.75605 4.01237 9.85445 4.05172 9.94593C4.09107 10.0374 4.14824 10.1201 4.21991 10.1893L6.71991 12.6893C6.79654 12.766 6.88887 12.8251 6.99055 12.8627C7.09224 12.9002 7.20085 12.9153 7.30892 12.9068C7.41699 12.8984 7.52193 12.8666 7.61654 12.8137C7.71114 12.7608 7.79315 12.688 7.85691 12.6003L11.8569 7.1003Z" fill="white"/>
                                    </svg>
                                </span>
                                <span class="text-area">Manage and share your <b>schedule</b></span>
                            </li>
                            <!--<li>
                                <span class="svg_img">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99991 16.9093C10.1216 16.9093 12.1565 16.0664 13.6568 14.5662C15.1571 13.0659 15.9999 11.031 15.9999 8.9093C15.9999 6.78757 15.1571 4.75274 13.6568 3.25245C12.1565 1.75216 10.1216 0.909302 7.99991 0.909302C5.87818 0.909302 3.84335 1.75216 2.34305 3.25245C0.842763 4.75274 -9.15527e-05 6.78757 -9.15527e-05 8.9093C-9.15527e-05 11.031 0.842763 13.0659 2.34305 14.5662C3.84335 16.0664 5.87818 16.9093 7.99991 16.9093ZM11.8569 7.1003C11.9148 7.02059 11.9565 6.93025 11.9795 6.83444C12.0025 6.73864 12.0064 6.63924 11.991 6.54192C11.9755 6.44461 11.9411 6.35128 11.8896 6.26727C11.8381 6.18326 11.7706 6.11021 11.6909 6.0523C11.6112 5.99439 11.5209 5.95274 11.4251 5.92974C11.3292 5.90674 11.2298 5.90284 11.1325 5.91825C11.0352 5.93367 10.9419 5.9681 10.8579 6.01958C10.7739 6.07106 10.7008 6.13859 10.6429 6.2183L7.15991 11.0083L5.27991 9.1283C5.21069 9.0567 5.1279 8.99961 5.03638 8.96034C4.94486 8.92108 4.84644 8.90044 4.74685 8.89962C4.64727 8.8988 4.54852 8.91782 4.45636 8.95558C4.36421 8.99333 4.2805 9.04906 4.21011 9.11951C4.13973 9.18997 4.08407 9.27373 4.04641 9.36592C4.00874 9.45811 3.98981 9.55688 3.99072 9.65646C3.99163 9.75605 4.01237 9.85445 4.05172 9.94593C4.09107 10.0374 4.14824 10.1201 4.21991 10.1893L6.71991 12.6893C6.79654 12.766 6.88887 12.8251 6.99055 12.8627C7.09224 12.9002 7.20085 12.9153 7.30892 12.9068C7.41699 12.8984 7.52193 12.8666 7.61654 12.8137C7.71114 12.7608 7.79315 12.688 7.85691 12.6003L11.8569 7.1003Z" fill="white"/>
                                    </svg>
                                </span>
                                <span class="text-area"><b>Share your schedule</b> with your team and your students</span>
                            </li>-->
                            <li>
                                <span class="svg_img">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99991 16.9093C10.1216 16.9093 12.1565 16.0664 13.6568 14.5662C15.1571 13.0659 15.9999 11.031 15.9999 8.9093C15.9999 6.78757 15.1571 4.75274 13.6568 3.25245C12.1565 1.75216 10.1216 0.909302 7.99991 0.909302C5.87818 0.909302 3.84335 1.75216 2.34305 3.25245C0.842763 4.75274 -9.15527e-05 6.78757 -9.15527e-05 8.9093C-9.15527e-05 11.031 0.842763 13.0659 2.34305 14.5662C3.84335 16.0664 5.87818 16.9093 7.99991 16.9093ZM11.8569 7.1003C11.9148 7.02059 11.9565 6.93025 11.9795 6.83444C12.0025 6.73864 12.0064 6.63924 11.991 6.54192C11.9755 6.44461 11.9411 6.35128 11.8896 6.26727C11.8381 6.18326 11.7706 6.11021 11.6909 6.0523C11.6112 5.99439 11.5209 5.95274 11.4251 5.92974C11.3292 5.90674 11.2298 5.90284 11.1325 5.91825C11.0352 5.93367 10.9419 5.9681 10.8579 6.01958C10.7739 6.07106 10.7008 6.13859 10.6429 6.2183L7.15991 11.0083L5.27991 9.1283C5.21069 9.0567 5.1279 8.99961 5.03638 8.96034C4.94486 8.92108 4.84644 8.90044 4.74685 8.89962C4.64727 8.8988 4.54852 8.91782 4.45636 8.95558C4.36421 8.99333 4.2805 9.04906 4.21011 9.11951C4.13973 9.18997 4.08407 9.27373 4.04641 9.36592C4.00874 9.45811 3.98981 9.55688 3.99072 9.65646C3.99163 9.75605 4.01237 9.85445 4.05172 9.94593C4.09107 10.0374 4.14824 10.1201 4.21991 10.1893L6.71991 12.6893C6.79654 12.766 6.88887 12.8251 6.99055 12.8627C7.09224 12.9002 7.20085 12.9153 7.30892 12.9068C7.41699 12.8984 7.52193 12.8666 7.61654 12.8137C7.71114 12.7608 7.79315 12.688 7.85691 12.6003L11.8569 7.1003Z" fill="white"/>
                                    </svg>
                                </span>
                                <span class="text-area">Automatic system invoice <!--based on the <b>Schedule</b>--></span>
                            </li>
                            <li>
                                <span class="svg_img">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99991 16.9093C10.1216 16.9093 12.1565 16.0664 13.6568 14.5662C15.1571 13.0659 15.9999 11.031 15.9999 8.9093C15.9999 6.78757 15.1571 4.75274 13.6568 3.25245C12.1565 1.75216 10.1216 0.909302 7.99991 0.909302C5.87818 0.909302 3.84335 1.75216 2.34305 3.25245C0.842763 4.75274 -9.15527e-05 6.78757 -9.15527e-05 8.9093C-9.15527e-05 11.031 0.842763 13.0659 2.34305 14.5662C3.84335 16.0664 5.87818 16.9093 7.99991 16.9093ZM11.8569 7.1003C11.9148 7.02059 11.9565 6.93025 11.9795 6.83444C12.0025 6.73864 12.0064 6.63924 11.991 6.54192C11.9755 6.44461 11.9411 6.35128 11.8896 6.26727C11.8381 6.18326 11.7706 6.11021 11.6909 6.0523C11.6112 5.99439 11.5209 5.95274 11.4251 5.92974C11.3292 5.90674 11.2298 5.90284 11.1325 5.91825C11.0352 5.93367 10.9419 5.9681 10.8579 6.01958C10.7739 6.07106 10.7008 6.13859 10.6429 6.2183L7.15991 11.0083L5.27991 9.1283C5.21069 9.0567 5.1279 8.99961 5.03638 8.96034C4.94486 8.92108 4.84644 8.90044 4.74685 8.89962C4.64727 8.8988 4.54852 8.91782 4.45636 8.95558C4.36421 8.99333 4.2805 9.04906 4.21011 9.11951C4.13973 9.18997 4.08407 9.27373 4.04641 9.36592C4.00874 9.45811 3.98981 9.55688 3.99072 9.65646C3.99163 9.75605 4.01237 9.85445 4.05172 9.94593C4.09107 10.0374 4.14824 10.1201 4.21991 10.1893L6.71991 12.6893C6.79654 12.766 6.88887 12.8251 6.99055 12.8627C7.09224 12.9002 7.20085 12.9153 7.30892 12.9068C7.41699 12.8984 7.52193 12.8666 7.61654 12.8137C7.71114 12.7608 7.79315 12.688 7.85691 12.6003L11.8569 7.1003Z" fill="white"/>
                                    </svg>
                                </span>
                                <span class="text-area">Manual invoices</span>
                            </li>
                            <li>
                                <span class="svg_img">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99991 16.9093C10.1216 16.9093 12.1565 16.0664 13.6568 14.5662C15.1571 13.0659 15.9999 11.031 15.9999 8.9093C15.9999 6.78757 15.1571 4.75274 13.6568 3.25245C12.1565 1.75216 10.1216 0.909302 7.99991 0.909302C5.87818 0.909302 3.84335 1.75216 2.34305 3.25245C0.842763 4.75274 -9.15527e-05 6.78757 -9.15527e-05 8.9093C-9.15527e-05 11.031 0.842763 13.0659 2.34305 14.5662C3.84335 16.0664 5.87818 16.9093 7.99991 16.9093ZM11.8569 7.1003C11.9148 7.02059 11.9565 6.93025 11.9795 6.83444C12.0025 6.73864 12.0064 6.63924 11.991 6.54192C11.9755 6.44461 11.9411 6.35128 11.8896 6.26727C11.8381 6.18326 11.7706 6.11021 11.6909 6.0523C11.6112 5.99439 11.5209 5.95274 11.4251 5.92974C11.3292 5.90674 11.2298 5.90284 11.1325 5.91825C11.0352 5.93367 10.9419 5.9681 10.8579 6.01958C10.7739 6.07106 10.7008 6.13859 10.6429 6.2183L7.15991 11.0083L5.27991 9.1283C5.21069 9.0567 5.1279 8.99961 5.03638 8.96034C4.94486 8.92108 4.84644 8.90044 4.74685 8.89962C4.64727 8.8988 4.54852 8.91782 4.45636 8.95558C4.36421 8.99333 4.2805 9.04906 4.21011 9.11951C4.13973 9.18997 4.08407 9.27373 4.04641 9.36592C4.00874 9.45811 3.98981 9.55688 3.99072 9.65646C3.99163 9.75605 4.01237 9.85445 4.05172 9.94593C4.09107 10.0374 4.14824 10.1201 4.21991 10.1893L6.71991 12.6893C6.79654 12.766 6.88887 12.8251 6.99055 12.8627C7.09224 12.9002 7.20085 12.9153 7.30892 12.9068C7.41699 12.8984 7.52193 12.8666 7.61654 12.8137C7.71114 12.7608 7.79315 12.688 7.85691 12.6003L11.8569 7.1003Z" fill="white"/>
                                    </svg>
                                </span>
                                <span class="text-area">Create final financial statement<!-- (for taxes)--></span>
                            </li>
                            <li>
                                <span class="svg_img">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99991 16.9093C10.1216 16.9093 12.1565 16.0664 13.6568 14.5662C15.1571 13.0659 15.9999 11.031 15.9999 8.9093C15.9999 6.78757 15.1571 4.75274 13.6568 3.25245C12.1565 1.75216 10.1216 0.909302 7.99991 0.909302C5.87818 0.909302 3.84335 1.75216 2.34305 3.25245C0.842763 4.75274 -9.15527e-05 6.78757 -9.15527e-05 8.9093C-9.15527e-05 11.031 0.842763 13.0659 2.34305 14.5662C3.84335 16.0664 5.87818 16.9093 7.99991 16.9093ZM11.8569 7.1003C11.9148 7.02059 11.9565 6.93025 11.9795 6.83444C12.0025 6.73864 12.0064 6.63924 11.991 6.54192C11.9755 6.44461 11.9411 6.35128 11.8896 6.26727C11.8381 6.18326 11.7706 6.11021 11.6909 6.0523C11.6112 5.99439 11.5209 5.95274 11.4251 5.92974C11.3292 5.90674 11.2298 5.90284 11.1325 5.91825C11.0352 5.93367 10.9419 5.9681 10.8579 6.01958C10.7739 6.07106 10.7008 6.13859 10.6429 6.2183L7.15991 11.0083L5.27991 9.1283C5.21069 9.0567 5.1279 8.99961 5.03638 8.96034C4.94486 8.92108 4.84644 8.90044 4.74685 8.89962C4.64727 8.8988 4.54852 8.91782 4.45636 8.95558C4.36421 8.99333 4.2805 9.04906 4.21011 9.11951C4.13973 9.18997 4.08407 9.27373 4.04641 9.36592C4.00874 9.45811 3.98981 9.55688 3.99072 9.65646C3.99163 9.75605 4.01237 9.85445 4.05172 9.94593C4.09107 10.0374 4.14824 10.1201 4.21991 10.1893L6.71991 12.6893C6.79654 12.766 6.88887 12.8251 6.99055 12.8627C7.09224 12.9002 7.20085 12.9153 7.30892 12.9068C7.41699 12.8984 7.52193 12.8666 7.61654 12.8137C7.71114 12.7608 7.79315 12.688 7.85691 12.6003L11.8569 7.1003Z" fill="white"/>
                                    </svg>
                                </span>
                                <span class="text-area">Access the mobile app</span>
                            </li>

                        <?php if(!$is_subscribed && empty($subscription)){ ?>
                            <!--<li class="submit-button"><a href="{{ route('subscribe.plan', $plan['id']) }}" class="button">Choose this plan</a></li>-->
                            <li class="submit-button"><a href="#" id="choose-plan" class="button">Choose this plan</a></li>
                            <li class="info-txt text-warning">you will not be the charged until the end of your trial period</li>
                        <?php } else {
                            if((!empty($subscription)) && $subscription['plan']['id'] == $plan['id']){
                        ?>
                            <li class="submit-button disabled"><a href="javascript:void(0)" class="button">Your current plan</a></li>
                            <li class="info-txt">Subscription valid until <?php echo  $subscription['billing_cycle_anchor'] ? date('M j, Y', $subscription['billing_cycle_anchor']) : ''; ?></li>
                        <?php } else { ?>
                            <li class="submit-button"><a href="{{ route('subscribe.upgradeNewPlan', ['payment_id'=>$plan['id']]) }}" class="button">Upgrade plan</a></li>
                            <li class="info-txt text-warning">you will not be the charged until the end of your trial period</li>
                        <?php
                                }
                            }
                        ?>
                    </ul>
                </div>
                @endforeach

                <div class="columns p-4 card bg-white" id="payment-form" style="display: none;">
                            <div class="text-center">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" width="120">
                            </div>

                        <div class="subscription-form-wrapper2">
                            <div class="payment-info-top text-center">
                                Enter your payment details below to subscribe your Coach Premium Plan
                                <hr>
                            </div>

                            <form action="{{ route('subscribe.store') }}" method="post" id="payment-form-sub">
                                @csrf
                                <input type="hidden" name="plan" value="{{ $plans[0]['id'] }}" />
                                <input type="hidden" name="plan_name" value="{{ $plans[0]['plan_name']->name }}" />
                                <input type="hidden" name="paymentMethod" id="paymentMethod" value="" />

                                <div class="form-group">
                                    <label style="font-size:11px;" for="coupon_code">Coupon Code</label>
                                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Enter Coupon Code">
                                </div>
                                <div class="form-group">
                                    <label style="font-size:11px;" for="cardholder_name">Cardholder's full name</label>
                                    <input type="text" class="form-control" id="card_holder_name" name="card_holder_name" placeholder="Enter Cardholder's full name" value="{{ Auth::user()->firstname .' ' . Auth::user()->lastname }}" required>
                                </div>
                                <br>

                                <div class="example4"></div>
                                <div id="example4-paymentRequest">
                                    <!--Stripe paymentRequestButton Element inserted here-->
                                </div>

                                    <div class="container">
                                    <div id="example4-card"></div>
                                    </div>

                                    <span class="errorStripe"></span>


                                    <br>

                                    <div class="text-center">
                                        <span style="font-size:11px; display:block; padding:5px;">
                                            <hr>
                                            Your subscription will renew automatically every month as one paypent of ${{ number_format($plans[0]['amount'], 2) }}.
                                            You may cancel your subscription anytime from My plan section in your profile.

                                            By clicking "Proceed payment" you agree to the Terms and Conditions.
                                        </span>
                                        <br>
                                        <a id="payment-button" class="btn btn-success btn-md">Proceed payment ${{ number_format($plans[0]['amount'], 2) }}</a>
                                        <br><br>
                                        <img src="https://www.eposhybrid.uk/ihybridnew//upload/ck/2035148938.png" width="110">
                                    </div>

                                </form>

                        </div>

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
                <small>(Your premium access will be valid until <?php echo date('M j, Y', $subscription['billing_cycle_anchor']); ?>)</small>
                </p>
              <?php } ?>
            </div>
            <div class="modal-footer">
                <a href="<?= $BASE_URL;?>/admin/profile-update" class="btn btn-secondary close"  data-dismiss="modal">No</a>
                <a class="btn btn-danger" href="{{ route('subscription.cancelPlan') }}">Yes, Cancel</a>
            </div>
        </div>
    </div>
</div>

<style>
    .main-content{
        background: #f2fbff;
    }
</style>
@endsection



@section('footer_js')
<script src="https://js.stripe.com/v3/"></script>

<script>
/** if clic on href id "choose-plan" we hide div id "current-plan" and whe display div id "payment-form" */
$("#choose-plan").click(function(){
    $("#current-plan").hide();
    $("#payment-form").show();
    document.getElementById("choose-plan").innerText = "Waiting payment method...";
});


</script>

<script>
(function() {
  "use strict";
  let cardSaved = "";
  const stripe = Stripe('<?= env('STRIPE_KEY') ?>', { locale: 'en' });
  var elements = stripe.elements({
    fonts: [
      {
        cssSrc: "https://rsms.me/inter/inter.css"
      }
    ],
    // Stripe's examples are localized to specific languages, but if
    // you wish to have Elements automatically detect your user's locale,
    // use `locale: 'auto'` instead.
    locale: window.__exampleLocale,
  });

  /**
   * Card Element
   */
  var card = elements.create("card", {
    hidePostalCode: true,
    style: {
      base: {
        color: "#32325D",
        fontWeight: 500,
        fontFamily: "Inter, Open Sans, Segoe UI, sans-serif",
        fontSize: "16px",
        fontSmoothing: "antialiased",

        "::placeholder": {
          color: "#AAAAAA"
        }
      },
      invalid: {
        color: "#E25950"
      }
    }
  });

  card.mount("#example4-card");

  const errorDiv = document.querySelector('.errorStripe');

// Lorsqu'il y a une erreur
card.on('change', function(event) {
  if (event.error) {
    // Afficher l'erreur dans le div
    errorDiv.textContent = event.error.message;
    errorDiv.style.display = 'block';
  } else {
    // Masquer le div si pas d'erreur
    errorDiv.style.display = 'none';
  }
});

  $("#payment-button").click(function(){
    $('#pageloader').show();
        stripe.createPaymentMethod({
        type: 'card',
        card: card,
        }).then(function(result) {
        if (result.error) {
            $('#pageloader').hide();
            console.error('la carte est pas ok', result)
            Swal.fire({
            icon: 'error',
            title: 'Payment error',
            text:result.error.message,
            });
        } else {
            console.log('la carte est ok', result)
            //send resu.tid in input text id <input type="hidden" name="paymentMethod" id="paymentMethod" value="" />
            document.getElementById("paymentMethod").value = result.paymentMethod.id;
            // submit form id="payment-form-sub"
            $("#payment-form-sub").submit();
        }
        });
    });


})();

const paymentButton = document.getElementById('payment-button');




</script>
@endsection
