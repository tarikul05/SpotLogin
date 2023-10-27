<div class="row justify-content-center pt-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                My invoices
                <select id="invoiceFilter">
                    <?php foreach($invoices as $invoice): ?>
                      <option value="<?= $invoice['hosted_invoice_url'] ?>"><?php echo date('M j, Y', $invoice['created']); ?></option>
                    <?php endforeach;?>
                  </select>
                </div>
            <div class="card-body">


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



            <div class="card p-2 mb-2">
              <?php if($product_object){?>
                @if($subscriber->cancel_at_period_end)
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

                @if($subscriber->status === 'trialing')
                  Your trial period is valid until <?= date('M j, Y', $subscriber->trial_end) ?>.
                  <?php if($product_object){ ?>
                    <br>
                    <small>(you will not be charged until the end of your trial period)</small>
                  <?php } ?>
                  <hr>
                @endif
                @if($subscriber->status === 'active')
                <span class="text-success"><i class="fa-solid fa-check"></i> Premium Plan is active on your account.</span>
                    Period in process : <?php echo date('M j, Y', $subscription['current_period_start']); ?> - <?php echo date('M j, Y', $subscription['current_period_end']); ?>
                @endif
              <?php } else {

              if($last_past_subscription) {
                  if($last_past_subscription['status'] === 'canceled'){
                      echo 'Your subscription has been canceled since the ' . date('M j, Y', $last_past_subscription['billing_cycle_anchor']);
                  }
              }

              }?>
            </div>



<?php if(!empty($invoices)): ?>
     <div class="table-invoices mb-3">

               <div class="table-responsive">
                 <table class="table table-stripped table-hover">
                   <thead>
                     <tr>
                       <th class="text-center">Date</th>
                       <th class="text-center">Amount</th>
                       <th class="text-center">Status</th>
                       <th class="text-center">Action</th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php foreach($invoices as $invoice): ?>
                       <tr class="<?= $invoice['id'] ?>">
                         <td class="text-center"><?php echo date('M j, Y', $invoice['created']); ?></td>
                         <td class="text-center"><?= '$'.($invoice['amount_paid']/100) ?></td>
                         <td class="text-center"><?= $invoice['status'] ?></td>
                         <td class="text-center">
                           <a class="action_link" href="<?= $invoice['hosted_invoice_url'] ?>" target="_blank"><i class="fa-solid fa-download"></i></a>
                         </td>
                       </tr>
                     <?php endforeach;?>
                   </tbody>
                 </table>
               </div>
         </div>
       <?php endif;?>





            </div>
        </div>
    </div>
</div>
