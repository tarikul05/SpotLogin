@if( !$is_subscribed )
    <div class="subscription_info">
        <div class="container-fluid area-container">
            <div class="text-success">
                <?php if( !empty($user->trial_ends_at) && ($today_date <= $ends_at) ){ ?>
                <p class="text">
                    <span class="day_count"><?php echo $day_diff ?></span> days remaining in your trial period. <a href="{{ route('subscription.upgradePlan') }}"> Upgrade now! </a>
                </p>
                <?php } else { ?>
                    <?php if( !empty( $user->trial_ends_at) ){ ?>
                    <p class="text">
                        your trial period ended at <?= date('M j, Y, g:i a', strtotime($trial_ends_date)) ?>. <a href="{{ route('subscription.upgradePlan') }}"> Upgrade now! </a>
                    </p>
                <?php 
                        }
                    } 
                ?>
            </div>
        </div>
    </div>
@endif