@extends('layouts.main')
@section('content')
@section('head_links')
<style>
.success-animation { margin:50px auto;}

.checkmark {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #4bb71b;
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px #4bb71b;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    position:relative;
    top: 5px;
    right: 5px;
   margin: 0 auto;
}
.checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #4bb71b;
    fill: #fff;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;

}

.checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% {
        stroke-dashoffset: 0;
    }
}

@keyframes scale {
    0%, 100% {
        transform: none;
    }

    50% {
        transform: scale3d(1.1, 1.1, 1);
    }
}

@keyframes fill {
    100% {
        box-shadow: inset 0px 0px 0px 30px #4bb71b;
    }
}
</style>
@endsection


<div class="container">

    <h5>{{ __('Coach Plan') }}</h5>





    <div class="row justify-content-center pt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Congratulations') }} !</div>
                <div class="card-body">
                    <!--@if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif-->

                    <?php
                    if($subscription) { ?>
                        <div class="success-animation">
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" /><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" /></svg>
                        </div>
                    <?php } ?>






                    <div class="mt-3 text-center">
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

                            <?php
                                if($subscription){
                            echo '<p><span class="text-success">'.__('Congratulations').' !<br>'.__('You subscribed to').' '.$product_object->name.'</span></p>';
                                }else{
                                    echo '<span class="">--/--/----</span>';
                                }
                            ?>

                        <p class="text-muted font-size-sm mb-1">

                            <?php
                                if($product_object){
                                    //echo '<span class="plan_type_p">'.$product_object->name.'</span>';
                                }else{
                                    if($AppUI->isSchoolAdmin()){
                                        echo '<span class="plan_type_f">'.__('Trial period').'</span>';
                                    }else{
                                        echo '<span class="plan_type_f">'.__('Basic').'</span>';
                                    }
                                }
                            ?>
                        </p>

                        <a class="btn btn-success" href="{{ route('agenda') }}">
                            <span class="action_icon">OK</span>
                        </a>

                    </div>




                </div>
            </div>
        </div>
    </div>

</div>

@endsection
