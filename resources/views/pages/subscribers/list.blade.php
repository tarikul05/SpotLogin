@extends('layouts.main')

@section('content')
<div class="my-subscription dataTables_wrapper">
    <div class="container">
        <div class="title">
            <h3 class="h3">List of subscribers</h3>
        </div>
        <div class="row justify-content-center">
            <table class="table my_subscription">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Plan Type</th>
                        <th>Expired Date</th>
                        <th>Next Payment</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <thead>
                    <?php foreach($subscribers as $subscriber){ ?>
                    <tr>
                        <th><?= $subscriber['user_name'] ?></th>
                        <th><?= $subscriber['email'] ?></th>
                        <th><?= $subscriber['plan_name'] ?></th>
                        <th><?= date('M j, Y', $subscriber['current_period_end']); ?></th>
                        <th><?= date('M j, Y', $subscriber['billing_cycle_anchor']); ?></th>
                        <th>
                            <span class="price"><?= '$'.($subscriber['amount_decimal'])/100 ?></span>
                            <span class="interval"><?= '/'.$subscriber['interval'] ?></span>
                        </th>
                        <th>
                            <a class="action_link" target="_blank" href="<?= $subscriber['invoice_url'] ?>"> View Invoice </a>
                        </th>
                    </tr>
                    <?php } ?>    
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection