@if( !$is_subscribed )

<?php if( !empty($user->trial_ends_at) && ($today_date <= $ends_at) ){ ?>
        <a href="{{ route('subscription.upgradePlan') }}" class="nav-item nav-link text-center mr-2 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $day_diff ?> {{__('days remaining in your trial period. Please get a Premium Plan to unlock all features.')}}"><b><i class="fa-regular fa-star"></i> <span class="d-none d-sm-block"></span> {{ __('Premium') }}</b></a>
<?php } else { ?>
    <?php if( !empty( $user->trial_ends_at) ){ ?>
        <a href="{{ route('subscription.upgradePlan') }}" class="nav-item nav-link text-center mr-2 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Your trial period is ended. Please get a Premium Plan to unlock all features.')}}"><b><i class="fa-regular fa-star"></i> <span class="d-none d-sm-block"></span> {{ __('Premium') }}</b></a>
    <?php } else { ?>
        <a href="{{ route('subscription.upgradePlan') }}" class="nav-item nav-link text-center mr-2 text-success" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Your subscription is ended. Please get a Premium Plan to unlock all features.')}}"><b><i class="fa-regular fa-star"></i> <span class="d-none d-sm-block"></span> {{ __('Premium') }}</b></a>
    <?php } ?>
<?php } ?>

    <?php /*if( !empty($user->trial_ends_at) && ($today_date <= $ends_at) ){ ?>
        <div class="subscription_info">
        <div class="d-none d-sm-block">
                <div class="text-success">
                    <p class="text">
                        <span class="day_count"><?php echo $day_diff ?></span> {{ __('days remaining in your trial period') }} <a class="custom-link" href="{{ route('subscription.upgradePlan') }}"> {{ __('Upgrade now') }}</a>
                    </p>
                    </div>
                </div>
        </div>

        <div class="d-block d-sm-none">
            <div class="text-success pt-3 text-center">
                <p class="text small">
                    {{ __('Trial period valid') }} <?php echo $day_diff ?> days <a class="custom-link" href="{{ route('subscription.upgradePlan') }}"> {{ __('Upgrade now') }}</a>
                </p>
            </div>
        </div>


    <?php } else { ?>
        <?php if( !empty( $user->trial_ends_at) ){ ?>
        <div class="subscription_info">
                <div class="text-success">
                    <p class="text pb-2">
                      Trial period ended. <a class="custom-link" href="{{ route('subscription.upgradePlan') }}"> {{ __('Upgrade now') }}</a>
                    </p>
                </div>
        </div>

        <div class="d-block d-sm-none">
            <div class="text-warning pt-3">
                <p class="text small">
                    Trial period ended. <a class="custom-link" href="{{ route('subscription.upgradePlan') }}"> {{ __('Upgrade now') }}</a>
                </p>
            </div>
        </div>

    <?php
            }
        }*/
    ?>
@endif
