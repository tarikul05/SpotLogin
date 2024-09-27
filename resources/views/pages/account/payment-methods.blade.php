
@section('head_links')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- Bootstrap select box-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
@endsection

    <div class="row justify-content-center pt-3">
    <div class="col-md-12">
        <div class="card2">
            <div class="card-header titleCardPage">{{ __('Payment Methods') }}</div>
            <div class="card-body">

               <div class="container">
            
                @if (!$AppUI->hasPaymentMethods())
                    <b>{{__('You have no payment methods registered')}}.</b><br>
                    {{__('You actually use the older payment methods system')}},
                    {{__('please add at least one payment method to activate the new system')}}.
                @else
                <table id="paymentMethod_table">
                    <thead>
                        <tr>
                            <th>{{__('Type')}}</th>
                            <th>{{__('Info')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                        @foreach ($AppUI->paymentMethods()->get() as $paymentMethod)
                        <div style="display:block; margin:15px;">
                            <tbody>
                           <tr>
                            <td style="width:150px;">
                                <b>{{ $paymentMethod->type }}</b>
                            </td>
                            <td>
                                @if ($paymentMethod->type === 'Stripe')
                                    <span>{{ $paymentMethod->details['account_number'] ?? 'N/A' }}</span> 
                                    @if($is_conneced_account_charges_enabled)
                                        (ready to be used)
                                    @else
                                        <span class="text-danger" style="cursor:pointer;" id="continueStripeAccount"><i class="fa fa-warning"></i> require informations</span>
                                    @endif
                                @elseif ($paymentMethod->type === 'PayPal')
                                    <span>{{ $paymentMethod->details['paypal_address'] ?? 'N/A' }}</span>
                                @elseif ($paymentMethod->type === 'IBAN')
                                    <span>IBAN N°: {{ $paymentMethod->details['iban_number'] ?? 'N/A' }}</span>
                                    <br><span>SWIFT N°: {{ $paymentMethod->details['swift_number'] ?? 'N/A' }}</span>
                                @elseif ($paymentMethod->type === 'Swift')
                                    <span>{{ $paymentMethod->details['swift_number'] ?? 'N/A' }}</span>
                                @elseif ($paymentMethod->type === 'Cash')
                                    <span>{{ $paymentMethod->details['cash'] ?? 'N/A' }}</span>
                                @elseif ($paymentMethod->type === 'E-Transfer')
                                    <span>{{ $paymentMethod->details['e_transfer_number'] ?? 'N/A' }}</span>
                                @elseif ($paymentMethod->type === 'Bank')
                                    <ul>
                                        @forelse ($paymentMethod->details['custom_fields'] ?? [] as $field)
                                            <li><strong>{{ $field['name'] }}:</strong> {{ $field['value'] }}</li>
                                        @empty
                                            <li>No custom fields added.</li>
                                        @endforelse
                                    </ul>
                                @endif
                                    <!--<br>
                                    <small>{{__('Created at')}}: {{ $paymentMethod->created_at->format('d/m/Y H:i') }}</small>-->
                            </td>
                            <td style="width:50px; text-align:center;">
                                <form id="deleteForm{{ $paymentMethod->id }}" action="{{ route('payment_methods.destroy', $paymentMethod->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $paymentMethod->id }})" style="background:none; border:none; cursor:pointer;">
                                        <i class="fa fa-trash text-danger" style="float:right;"></i>
                                    </button>
                                </form>
                            </td>
                            </tr>
                            </tbody>
                        </div>
                        @endforeach
                    </table>
                @endif
            
            </div>


            <form method="post" class="mt-5 text-center" action="{{ route('payment_methods.store') }}" enctype="multipart/form-data" style="opacity:1!important; z-index:999!important;">
                @csrf
                <div class="card2">
                    <div class="card-header titleCardPage" style="opacity:1!important;">
                    <div class="card-title">
                        {{ __('Add a Payment Method') }}
                        <span class="d-block d-sm-none text-danger" style="font-size:11px;">{{ __('Optional - this information will appear on the invoice') }} </span><br>
                        <span class="d-none d-sm-inline text-danger" style="padding-left: 10px; font-size:11px;">[ {{ __('Optional - this information will appear on the invoice') }} ]</span>
                    </div>
            
                    <div class="form-group" style="width: 100%; max-width:500px; margin:0 auto; opacity:1!important; z-index:999!important;">
                        <select id="type" name="type" class="form-control">
                        <option value="">{{__('Select a payment method type')}}</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank">Bank information</option>
                        <option value="IBAN">IBAN/SWIFT</option>
                        <option value="E-Transfer">E-Transfer</option>
                        <option value="Stripe">Stripe</option>
                        <option value="PayPal">PayPal</option>
                        </select>
                    </div>
                    
                    </div>
                </div>
            </form>

            <div id="details-container" class="mt-3" style="z-index:998!important; background-color:#fafafa; padding:15px!important; border-radius:8px; display:none!important;"></div>
                

            </div>
        </div>
    </div>



</div>





