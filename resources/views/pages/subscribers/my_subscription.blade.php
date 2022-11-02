@extends('layouts.main')
@section('content')
<div class="my-subscription">
    <div class="container">
        <div class="title">
            <h3 class="h3">My Subscription Info</h3>
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
                    <tr>
                        <td><?= $user->firstname.''. $user->lastname ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $product_object?$product_object->name:'' ?></td>
                        <td>
                            <?php 
                                if($subscription){
                                    echo date('M j, Y', $subscription['current_period_end']);
                                }
                            ?>
                        </td>
                        <td>
                            <?php 
                                if($subscription){
                                    echo date('M j, Y', $subscription['billing_cycle_anchor']);
                                }
                            ?>
                        </td>
                        <td> 
                            <?php if($subscription) { ?>
                                <span class="price"><?= '$'.($subscription['plan']['amount_decimal'])/100 ?></span>
                                <span class="interval"><?= '/'.$subscription['plan']['interval'] ?></span>
                            <?php } ?>
                        </td>
                        <td>
                            <a class="action_link" href="">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 17.0129L11.413 16.9979L21.045 7.4579C21.423 7.0799 21.631 6.5779 21.631 6.0439C21.631 5.5099 21.423 5.0079 21.045 4.6299L19.459 3.0439C18.703 2.2879 17.384 2.2919 16.634 3.0409L7 12.5829V17.0129ZM18.045 4.4579L19.634 6.0409L18.037 7.6229L16.451 6.0379L18.045 4.4579ZM9 13.4169L15.03 7.4439L16.616 9.0299L10.587 15.0009L9 15.0059V13.4169Z" fill="#657E8E"/>
                                    <path d="M5 21H19C20.103 21 21 20.103 21 19V10.332L19 12.332V19H8.158C8.132 19 8.105 19.01 8.079 19.01C8.046 19.01 8.013 19.001 7.979 19H5V5H11.847L13.847 3H5C3.897 3 3 3.897 3 5V19C3 20.103 3.897 21 5 21Z" fill="#657E8E"/>
                                </svg>
                                <span class="action_icon">Change plan type</span>
                            </a>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection