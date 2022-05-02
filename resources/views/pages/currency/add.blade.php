@extends('layouts.main')

@section('content')
<script type="text/javascript">
		
</script>
	<div class="content currency_page">
		<div class="container-fluid">
			<form method="POST" action="{{route('add.currency')}}" id="langForm" name="langForm" class="form-horizontal" role="form">
				<div class="row">
					<div class="col-sm-12 col-xs-12 header-area">
						<div class="page_header_class">
							<label id="page_header"class="page_title">{{ __('Currency Setup')}}</label>
						</div>
					</div>
					@csrf
				
					<div class="col-md-6 offset-md-2">
						<div class="form-group">
							<input type="hidden" id="row_id" name="row_id" value="0">
							<input type="hidden" id="currency_code_data" name="currency_code_data" value="">
						</div> 
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Country')}}: </label>
							<div class="col-sm-6">
								<div class="selectdiv form-group-data">
									<select class="form-control" id="country_code"  name="country_code">
										@foreach($countries as $key => $country)
											<option value="{{ $country->code }}">{{ $country->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Currency code')}}: </label>
							<div class="col-sm-6">
								<div class="form-group-data">
									<input type="text" class="form-control" id="currency_code" name="currency_code">
									
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Currency name')}}: </label>
							<div class="col-sm-6 form-group-data">
								<input type="text" class="form-control" id="currency_title" name="currency_title">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Currency short order')}}: </label>
							<div class="col-sm-6 form-group-data">
								<input type="number" class="form-control" id="sort_order" name="sort_order">
                 
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Active')}} / {{ __('Inactive')}}: </label>
							<div class="col-sm-6 selectbox form-group-data">
								<select class="form-control" name="is_active" id="is_active">
									<option value="1">{{ __('Active')}}</option>
									<option value="0">{{ __('Inactive')}}</option>
								</select>
							</div>
							<div class="col-lg-2 col-md-2">
								<button type="submit" class="btn btn-success btn-theme-success save_button float-end" id="update_btn"><i class="fa fa-plus-circle" aria-hidden="true"></i>{{ __('Save')}}</button>
								
							</div>
						</div>
					</div>
				</div>
			</form>
			<div class="row">
				<form class="form-horizontal" role="form" action="">
					<div class="offset-md-1 col-lg-10 col-md-10">
						<div class="table-responsive">
							<table class="table" id="currency_table">
								<thead>
									<tr>
										<th scope="col">{{__('Currency code')}}</th>
										<th scope="col">{{__('Name')}}</th>
										<th scope="col">{{__('Country')}}</th>
										<th scope="col">{{__('Short order')}}</th>
										<th scope="col">{{__('Status')}}</th>
										<th scope="col"></th>
									</tr>
								</thead>
								<tbody>
									@php
									$i = 1
									@endphp
									@foreach($allcurrency as $key => $value)
										<tr>
											<th>{{ $value->currency_code }}</th>
											<td>{{ $value->name }}</td>
											<td data-code="{{$value->country_code}}">{{ $value->country->name }}</td>
											<td>{{ $value->sort_order }}</td>
											<td data-active="{{ $value->is_active }}">
												@if($value->is_active == 1)
													{{__('Active')}}
												@else
													{{__('Inactive')}}
												@endif

											</td>
											<td>

												<button class="btn btn-success update_button" type="button" onclick="FetchData({{$i}})"><i class="fa fa-pencil"></i>
												{{__('Update')}}
												</button>
											</td>
										</tr>
										@php
										$i++
										@endphp
										
									@endforeach
									
								</tbody>
							</table>
						</div>
					</div>
				</form>
		</div>
	</div>

	<!-- End Tabs content -->
@endsection

@section('footer_js')
<script type="text/javascript">
	$(document).ready(function(){

		

		function validateForm() {
    	var currency_code = document.getElementById("currency_code").value;
    	var currency_title = document.getElementById("currency_title").value;
        var sort_order = document.getElementById("sort_order").value;
    	let error = false;
    	if (currency_code == null || currency_code == "") {
				$('#currency_code').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
				document.getElementById("currency_code").focus();
				error = true;
    	}
		if (currency_title == null || currency_title == "") {
			document.getElementById("currency_title").focus();
			$('#currency_title').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
			error = true;             
    	}
 

		if (error) {
			return false;
		}else{
			return true;
    	}
    }  

		$("#langForm").submit(function(e) {
			$('span.error').remove();
			if(validateForm()) {
			} else {
				e.preventDefault(e);  
			}
		});
		
	}); //ready


	function FetchData(p_row){
        document.getElementById("currency_code").disabled = true;
        document.getElementById("currency_code").value=document.getElementById("currency_table").rows[p_row].cells[0].innerHTML;
        document.getElementById("currency_title").value=document.getElementById("currency_table").rows[p_row].cells[1].innerHTML;
        document.getElementById("country_code").value=document.getElementById("currency_table").rows[p_row].cells[2].getAttribute('data-code');
        document.getElementById("sort_order").value=document.getElementById("currency_table").rows[p_row].cells[3].innerHTML;
        document.getElementById("is_active").value=document.getElementById("currency_table").rows[p_row].cells[4].getAttribute('data-active');
		document.getElementById("row_id").value=p_row;
		document.getElementById("currency_code_data").value=document.getElementById("currency_table").rows[p_row].cells[0].innerHTML;
        
        document.getElementById("currency_title").focus();
        return false;
    }
</script>
@endsection


