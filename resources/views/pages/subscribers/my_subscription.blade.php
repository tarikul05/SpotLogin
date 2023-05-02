@extends('layouts.main')
@section('content')
<div class="my-subscription">
    <div class="container">
        <div class="title">
            <h3 class="h3">My Subscription Info</h3>
        </div>
        <div class="my_subscription row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column">
                    <div class="align-items-center text-center">
                        <img src="{{ asset('img/member_ship.png') }}" width="150" class="rounded-circle">
                    </div>
                    <div class="mt-3">
                        <?php if(!empty($user->trial_ends_at)){ ?>
                            <p class="text-secondary mb-1">
                                <?php 
                                if($AppUI->isSchoolAdmin()){
                                    echo '<b>Trail Valid Until</b>';
                                }else{
                                    echo '<b>Basic Valid Until</b>';
                                }
                            ?>:  <?= date('M j, Y', strtotime($user->trial_ends_at)) ?></td>
                            </p>
                        <?php } ?>
                        <p class="text-secondary mb-1"><b>Next Payment:</b> 
                            <?php 
                                if($subscription){
                                    echo '<span class="">' . date('M j, Y', $subscription['billing_cycle_anchor']).'</span>';
                                }else{
                                    echo '<span class="">--/--/----</span>';
                                }
                            ?>
                        </p>
                        <p class="text-muted font-size-sm mb-1">
                            <b>Plan Type : </b>
                            <?php 
                                if($product_object){
                                    echo '<span class="plan_type_p">'.$product_object->name.'</span>';
                                }else{
                                    if($AppUI->isSchoolAdmin()){
                                        echo '<span class="plan_type_f">Trial period</span>';
                                    }else{
                                        echo '<span class="plan_type_f">Basic</span>';
                                    }
                                }
                            ?>
                        </p>
                        <p class="text-muted font-size-sm">
                            <b>Price:</b>
                            <?php if(!empty($subscription)) { ?>
                                <span class="price"><?= '$'.($subscription['plan']['amount_decimal'])/100 ?></span>
                                <span class="interval"><?= '/'.$subscription['plan']['interval'] ?></span>
                            <?php }else{ ?>
                                <span class="price">Free</span>
                            <?php } ?>
                        </p>
                        <a class="btn btn-success" href="{{ route('subscription.upgradePlan') }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7 17.0129L11.413 16.9979L21.045 7.4579C21.423 7.0799 21.631 6.5779 21.631 6.0439C21.631 5.5099 21.423 5.0079 21.045 4.6299L19.459 3.0439C18.703 2.2879 17.384 2.2919 16.634 3.0409L7 12.5829V17.0129ZM18.045 4.4579L19.634 6.0409L18.037 7.6229L16.451 6.0379L18.045 4.4579ZM9 13.4169L15.03 7.4439L16.616 9.0299L10.587 15.0009L9 15.0059V13.4169Z" fill="#FFFFFF"/>
                                <path d="M5 21H19C20.103 21 21 20.103 21 19V10.332L19 12.332V19H8.158C8.132 19 8.105 19.01 8.079 19.01C8.046 19.01 8.013 19.001 7.979 19H5V5H11.847L13.847 3H5C3.897 3 3 3.897 3 5V19C3 20.103 3.897 21 5 21Z" fill="#FFFFFF"/>
                            </svg>
                            <span class="action_icon">Change plan type</span>
                        </a>
                        <button class="btn btn-outline-success">Cancle</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                    <table class="my_subscription">
                        <tbody>
                            <?php if($AppUI->isSchoolAdmin()){ ?> 
                                <!-- school first higher plan info -->
                                <?php if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_school_premium_plan_one_price_id')) {?>
                                    <tr>
                                        <td>Teachers Unlimited</td>
                                    </tr>
                                    <tr>
                                        <td>Student Unlimited</td>
                                    </tr>
                                <?php } else if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_school_premium_plan_two_price_id')){ ?>
                                <!-- school 2nd higher plan info -->
                                    <tr>
                                        <td>Up 5 teachers</td>
                                    </tr>
                                    <tr>
                                        <td>Student Unlimited</td>
                                    </tr>
                                <?php } else{ ?>
                                    <tr>
                                        <td>Teachers Unlimited</td>
                                    </tr>
                                    <tr>
                                        <td>Student Unlimited</td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>Manage your student</td>
                                </tr>
                                <tr>
                                    <td>Manage your teacher</td>
                                </tr>
                                <tr>
                                    <td>Manage your schedule</td>
                                </tr>
                                <tr>
                                    <td>Share your schedule with your team and your students</td>
                                </tr>
                                <tr>
                                    <td>Automatic invoice based on the Schedule (students and teachers)</td>
                                </tr>
                                <tr>
                                    <td>Manual invoices</td>
                                </tr>
                                <?php if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_school_premium_plan_two')){ ?>
                                    <tr>
                                        <td>Create your final financial statement (for taxes)</td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>Access the mobile app</td>
                                </tr>
                            <?php } ?>
                            <?php if($AppUI->isTeacherAdmin()){ ?>
                                <tr>
                                    <td>Unlimited student</td>
                                </tr>
                                <tr>
                                    <td>Manage your student</td>
                                </tr>
                                <tr>
                                    <td>Manage your schedule</td>
                                </tr>
                                <tr>
                                    <td>Share your schedule with your team and your students</td>
                                </tr>
                                <?php if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_single_cocah_premium_plan_price_id')){ ?>
                                    <tr>
                                        <td>Automatic invoice based on the Schedule</td>
                                    </tr>
                                    <tr>
                                        <td>Manual invoices</td>
                                    </tr>
                                    <tr>
                                        <td>Create your final financial statement (for taxes)</td>
                                    </tr>
                                <?php } ?>
                                    <tr>
                                        <td>Access the mobile app</td>
                                    </tr>
                            <?php } ?>
                            <?php if($AppUI->isTeacherAll() || $AppUI->isTeacherMedium() || $AppUI->isTeacherMinimum()){ ?> 
                                <tr>
                                    <td>Access the school schedule </td>
                                </tr>
                                <tr>
                                    <td>Access the mobile app</td>
                                </tr>
                                <?php if(isset($subscription['plan']['id']) && $subscription['plan']['id'] == env('stripe_teacher_premium_plan_price_id')){ ?>
                                <!-- teacher plan info -->
                                    <tr>
                                        <td>Automatic invoice based on your schedule</td>
                                    </tr>
                                    <tr>
                                        <td>Manal invoice</td>
                                    </tr>
                                    <tr>
                                        <td>Create your final financial statement (for taxes)</td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection