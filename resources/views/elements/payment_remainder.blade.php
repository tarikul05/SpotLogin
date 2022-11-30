@if( !$is_subscribed && (!empty($user->trial_ends_at) && ($today_date <= $ends_at)))
    <div class="subscription_info">
        <div class="container-fluid area-container">
            <div class="text-success">
                <p class="text">
                    <span class="day_count"><?php echo $day_diff ?></span> days remaining in your trial period. <a href="{{ route('subscription.upgradePlan') }}"> Upgrade now! </a>
                </p>
            </div>
        </div>
    </div>
@endif