

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
                <table class="table table-bordered table-hover">
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
                                @elseif ($paymentMethod->type === 'PayPal')
                                    <span>{{ $paymentMethod->details['paypal_address'] ?? 'N/A' }}</span>
                                @elseif ($paymentMethod->type === 'IBAN')
                                    <span>{{ $paymentMethod->details['iban_number'] ?? 'N/A' }}</span>
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
                                    <br>
                                <small>{{__('Created at')}}: {{ $paymentMethod->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td style="width:50px; text-align:center;">
                                <form action="{{ route('payment_methods.destroy', $paymentMethod->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; cursor:pointer; color:#FF0000;">
                                        <i class="fa fa-trash" style="float:right;"></i>
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


            <form method="post" action="{{ route('payment_methods.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-center pt-4">
                    <div class="col-md-12">
                        <div class="card2">
                            <div class="card-header titleCardPage">
                            <div class="card-title">
                                {{ __('Add a Payment Method') }}
                                <span class="d-block d-sm-none" style="color:red; font-size:11px;">{{ __('Optional - this information will appear on the invoice') }} </span> 
                                <span class="d-none d-sm-inline" style="padding-left: 10px; color:red; font-size:11px;">[ {{ __('Optional - this information will appear on the invoice') }} ]</span>
                            </div>
                   
                            <div class="form-group">
                            <select id="type" name="type" class="form-control">
                            <option value="">{{__('Select a payment method type')}}</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank information</option>
                            <option value="IBAN">IBAN N°</option>
                            <option value="Swift">SWIFT A/c No</option>
                            <option value="E-Transfer">E-Transfer</option>
                            <option value="Stripe">Stripe</option>
                            <option value="PayPal">PayPal</option>
                            </select>
                            </div>
                            <div id="details-container"></div>
                    
                            </div>
                        </div>
                    </div>
                </div>
            </form>
                

            </div>
        </div>
    </div>

</div>



