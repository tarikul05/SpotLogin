<div class="row justify-content-center pt-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">Account information</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->



                <table class="table table-stripped table-hover">
                    <tr><td width="250"><b>Connected with Login ID</b></td> <td>{{  $AppUI->related_school->school_name }}</td></tr>
                    @if($AppUI->related_school->discipline != null)
                    <tr><td width="250"><b>Activity</b></td> <td>{{  $AppUI->related_school->discipline }}</td></tr>
                    @endif
                    <tr><td><b>Account created date</b></td> <td>{{  $AppUI->created_at }}</td></tr>
                    <tr><td><b>Account timezone</b></td> <td>{{  $AppUI->related_school->timezone }}</td></tr>
                    @php
                      $countryCode = $AppUI->related_school->country_code; // Vous pouvez remplacer "FR" par la valeur souhaitée
                      $countryName = DB::table('countries')->where('code', $countryCode)->value('name');
                    @endphp

                    <tr><td><b>Country</b></td> <td>{{  $countryName }}</td></tr>
                    <tr><td><b>Currency</b></td> <td>{{  $AppUI->related_school->default_currency_code }}</td></tr>
                      <tr class="mt-2"><td><b class="text-danger">Delete my account</b></td> <td>
                          <a class="btn btn-danger btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#delete_user">Delete my account</a></td></tr>
                    <!--<tr><td><b>Acces</b></td> <td>{{  $AppUI->role_type }}</td></tr>-->
                  </table>




            </div>
        </div>
    </div>
</div>


<div class="modal" id="cancel_subscription">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Subscription</h5>
                <span type="button" class="close" data-dismiss="modal"><i class="fa-solid fa-question fa-beat"></i></span>
            </div>
            <div class="modal-body">
              <?php if(!empty($subscription)) { ?>
                <div class="text-center">
                    <i class="fas fa-exclamation-circle fa-5x text-danger"></i> <!-- Utilisez l'icône d'alerte ou d'information souhaité -->
                </div>
                <p class="text-center mt-3">Do you really want to cancel your subscription?<br>
                <small>(Your premium access will be valid until

                  @if($subscriber->status === 'active')
                  <?php echo date('M j, Y', $subscription['current_period_end']); ?>
                  @else
                  <?php echo date('M j, Y', $subscription['billing_cycle_anchor']); ?>
                  @endif

                  )</small>
                </p>
              <?php } ?>
            </div>
            <div class="modal-footer">
                <a href="<?= $BASE_URL;?>/admin/profile-update" class="btn btn-secondary close"  data-dismiss="modal">No</a>
                <a class="btn btn-danger" href="{{ route('subscription.cancelPlan') }}">Yes, Cancel</a>
            </div>
        </div>
    </div>
</div>
