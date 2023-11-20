<div class="row justify-content-center pt-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Account information</div>
            <div class="card-body">
                <!--@if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif-->



                <table class="table table-bordered table-hover">
                    <tr><td><b>{{ __('School name') }}</b></td> <td>{{  $AppUI->related_school->school_name }}</td></tr>
                    @if($AppUI->related_school->discipline != null)
                    <tr><td><b>{{ __('Activity') }}</b></td> <td>{{  $AppUI->related_school->discipline }}</td></tr>
                    @endif
                    <tr><td><b>{{ __('Created at') }}</b></td> <td>{{  $AppUI->created_at }}</td></tr>
                    <tr><td><b>Account Timezone</b></td> <td>{{  $AppUI->related_school->timezone }}</td></tr>
                    @if(!empty($settingUser))
                    <tr><td><b>{{ __('Setting Timezone') }}</b> <span class="badge bg-info">current</span></td> <td>{{  $settingUser->timezone }}</td></tr>
                    @endif
                    @php
                      $countryCode = $AppUI->related_school->country_code; // Vous pouvez remplacer "FR" par la valeur souhaitée
                      $countryName = DB::table('countries')->where('code', $countryCode)->value('name');
                    @endphp

                    <tr><td><b>{{ __('Country') }}</b></td> <td>{{  $countryName }}</td></tr>
                    <tr><td><b>{{ __('Currency') }}</b></td> <td>{{  $AppUI->related_school->default_currency_code }}</td></tr>

                  </table>

                  <br>
                  <a class="btn btn-danger btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#delete_user">{{ __('Delete my account') }}</a>


            </div>
        </div>
    </div>
</div>


<div class="modal" id="delete_user">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Delete my account') }}</h5>
                <span type="button" class="close" data-dismiss="modal"><i class="fa-solid fa-question fa-beat"></i></span>
            </div>
            <div class="modal-body">

                <div class="text-center">
                    <i class="fas fa-exclamation-circle fa-5x text-danger"></i> <!-- Utilisez l'icône d'alerte ou d'information souhaité -->
                </div>
                <p class="text-center mt-3">{{ __('Do you really want to delete your account ?') }}<br></p>

            </div>
            <div class="modal-footer">
                <a href="<?= $BASE_URL;?>/admin/profile-update" class="btn btn-secondary close"  data-dismiss="modal">{{ __('No') }}</a>
                <a class="btn btn-danger" href="{{ route('user.disable_user') }}">{{ __('Yes, i confirm') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="cancel_subscription">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Cancel Subscription') }}</h5>
                <span type="button" class="close" data-dismiss="modal"><i class="fa-solid fa-question fa-beat"></i></span>
            </div>
            <div class="modal-body">
              <?php if(!empty($subscription)) { ?>
                <div class="text-center">
                    <i class="fas fa-exclamation-circle fa-5x text-danger"></i> <!-- Utilisez l'icône d'alerte ou d'information souhaité -->
                </div>
                <p class="text-center mt-3">{{ __('Do you really want to cancel your subscription?') }}<br>
                <small>({{ __('Your premium access will be valid until') }}

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
                <a href="<?= $BASE_URL;?>/admin/profile-update" class="btn btn-secondary close"  data-dismiss="modal">{{ __('No') }}</a>
                <a class="btn btn-danger" href="{{ route('subscription.cancelPlan') }}">{{ __('Yes, Cancel') }}</a>
            </div>
        </div>
    </div>
</div>
