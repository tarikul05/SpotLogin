@if( !$is_subscribed )
    <?php if( !empty($user->trial_ends_at) && ($today_date <= $ends_at) ){ ?>
        <div class="subscription_info">
                <div class="text-success">
                    <p class="text">
                        <span class="day_count"><?php echo $day_diff ?></span> days remaining in your trial period. <a href="{{ route('subscription.upgradePlan') }}"> Upgrade now! </a>
                    </p>
                </div>
        </div>
    <?php } else { ?>
        <?php if( !empty( $user->trial_ends_at) ){ ?>
        <div class="subscription_info">
                <div class="text-success">
                    <p class="text">
                        Your trial period ended at <?= date('M j, Y, g:i a', strtotime($trial_ends_date)) ?>. <a href="{{ route('subscription.upgradePlan') }}"> Upgrade now! </a>
                    </p>
                </div>
        </div>
    <?php 
            }
        } 
    ?>
@endif