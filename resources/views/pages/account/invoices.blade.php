<div class="row justify-content-center pt-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                My invoices
                @if(!empty($invoices) && count($invoices) > 0)
                <select id="invoiceFilter">
                    <?php foreach($invoices as $invoice): ?>
                      <option value="<?= $invoice['hosted_invoice_url'] ?>"><?php echo date('M j, Y', $invoice['created']); ?></option>
                    <?php endforeach;?>
                  </select>
                @endif
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



@if(!empty($invoices) && count($invoices) > 0)
     <div class="table-invoices mb-3">

               <div class="table-responsive">
                 <table class="table table-bordered table-hover">
                   <thead>
                     <tr>
                       <th class="text-left">Date</th>
                       <th class="text-left">Amount</th>
                       <th class="text-left">Status</th>
                       <th class="text-center">Action</th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php foreach($invoices as $invoice): ?>
                       <tr class="<?= $invoice['id'] ?>">
                         <td class="text-left"><?php echo date('M j, Y', $invoice['created']); ?></td>
                         <td class="text-left"><?= '$'.($invoice['amount_paid']/100) ?></td>
                         <td class="text-left"><?= $invoice['status'] ?></td>
                         <td class="text-center">
                           <a class="action_link" href="<?= $invoice['hosted_invoice_url'] ?>" target="_blank"><i class="fa-solid fa-download"></i></a>
                         </td>
                       </tr>
                     <?php endforeach;?>
                   </tbody>
                 </table>
               </div>
         </div>
         @else
         No invoices found.
    @endif





            </div>
        </div>
    </div>
</div>
