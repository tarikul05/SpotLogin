@extends('layouts.main')

@section('content')
<style>
			.se-pre-con {
					position: fixed;
					left: 0px;
					top: 0px;
					width: 100%;
					height: 100%;
					z-index: 9999;  
					
					opacity: 1; 
			}
	</style>
	<!-- background: url({{ asset('img/loader4.gif') }}) center no-repeat #ffffff; -->
	<div class="se-pre-con"></div> 
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
					@if(session()->has('error'))
							<div class="alert alert-danger invalid-feedback d-block">{{ session()->get('error') }}</div>
					@endif
					@if (session('status'))
							<div class="alert alert-success">
							{{ session('status') }}
							</div>
					@endif
					@if (session('warning'))
							<div class="alert alert-warning">
							{{ session('warning') }}
							</div>
					@endif
					<div class="col-md-6 offset-md-2">
						<div class="form-group">
							<input type="hidden" id="row_id" name="row_id" value="0">
						</div> 
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Language code')}}: </label>
							<div class="col-sm-6">
								<div class="selectdiv">
									<input type="text" class="form-control" id="language_code" name="language_code">
									
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Language name')}}: </label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="language_title" name="language_title">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Language short name')}}: </label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="abbr_name" name="abbr_name">
                 
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-4 col-sm-4 text-end">{{ __('Active')}} / {{ __('Inactive')}}: </label>
							<div class="col-sm-6 selectbox">
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
		$(".se-pre-con").hide();
	}); //ready
</script>
	<!-- End Tabs content -->
@endsection


