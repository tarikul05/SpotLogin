@extends('layouts.main')

@section('content')
	<div class="content language_page">
		<div class="container-fluid">
			<form method="POST" action="{{route('add.language')}}" id="langForm" name="langForm" class="form-horizontal" role="form">
				<div class="row">
					<div class="col-sm-12 col-xs-12 header-area">
						<div class="page_header_class">
							<label id="page_header"class="page_title">{{ __('Language Setup')}}</label>
						</div>
					</div>
					@csrf
				
					<div class="col-md-6 offset-md-2">
						<div class="form-group">
							<input type="hidden" id="row_id" name="row_id" value="0">
						</div> 
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Language code')}}: </label>
							<div class="col-sm-6">
								<div class="selectdiv form-group-data">
									<input type="text" class="form-control" id="language_code" name="language_code">
									
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Language name')}}: </label>
							<div class="col-sm-6 form-group-data">
								<input type="text" class="form-control" id="language_title" name="language_title">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Language short name')}}: </label>
							<div class="col-sm-6 form-group-data">
								<input type="text" class="form-control" id="abbr_name" name="abbr_name">
                 
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
							<table class="table">
								<thead>
									<tr>
										<th scope="col">{{__('Language code')}}</th>
										<th scope="col">{{__('Language name')}}</th>
										<th scope="col">{{__('Language short name')}}</th>
										<th scope="col">{{__('Status')}}</th>
										<th scope="col"></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>from</th>
										<td>deutsch</td>
										<td>from</td>
										<td>active</td>
										<td>
										<a class="btn btn-success update_button" href=""> 
											<i class="fa fa-pencil" aria-hidden="true"></i>Update
										</a>
										</td>
									</tr>
									<tr>
										<th>on</th>
										<td>English</td>
										<td>on</td>
										<td>active</td>
										<td>
										<a class="btn btn-success update_button" href=""> 
											<i class="fa fa-pencil" aria-hidden="true"></i>Update
										</a>
										</td>
									</tr>
									<tr>
										<th>fr</th>
										<td>French</td>
										<td>fr</td>
										<td>active</td>
										<td>
										<a class="btn btn-success update_button" href=""> 
											<i class="fa fa-pencil" aria-hidden="true"></i>{{__('Update')}}
										</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</form>
		</div>
	</div>
<script type="text/javascript">
	$(document).ready(function(){
		function validateForm() {
    	var language_code = document.getElementById("language_code").value;
    	var language_title = document.getElementById("language_title").value;
        var abbr_name = document.getElementById("abbr_name").value;
    	let error = false;
    	if (language_code == null || language_code == "") {
				$('#language_code').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
				document.getElementById("language_code").focus();
				error = true;
    	}
			if (language_title == null || language_title == "") {
				document.getElementById("language_title").focus();
				$('#language_title').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
				error = true;             
    	}
    	if (abbr_name == null || abbr_name  == "") {		
				$('#abbr_name').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
				document.getElementById("abbr_name").focus();
				error = true;
    	}

			if (error) {
				return false;
			}            			
    	else
    	{
				return true;
    	}
    }  

		//$('#update_btn').click(function (e) {
		$("#langForm").submit(function(e) {
			$('span.error').remove();
			if(validateForm()) {
			} else {
				e.preventDefault(e);  
			}
		});
		
	}); //ready
</script>
	<!-- End Tabs content -->
@endsection


