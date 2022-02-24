@extends('layouts.main')

@section('head_links')
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
@endsection

@section('content')
<div class="content email_template_page">
	<div class="container-fluid area-container">
		<form method="POST" action="{{route('add.email_template')}}" id="emailForm" name="emailForm" class="form-horizontal" role="form">
			<header class="panel-heading" style="border: none;">
				<div class="row panel-row" style="margin:0;">
					<div class="col-sm-6 col-xs-12 header-area">
							<div class="page_header_class">
									<label id="page_header" name="page_header">
										{{__('Email Template')}}
									</label>
							</div>
					</div>
					<div class="col-sm-6 col-xs-12 btn-area">
							<div class="pull-right btn-group">
									<a class="btn btn-sm btn-info text-white" href="../admin/" id="back_btn"> 
										<i class="fa fa-arrow-left"></i>
										{{ __('back')}}
									</a>
									<button type="submit" class="btn btn-sm btn-success save_button float-end" id="update_btn">
										<i class="fa fa-plus" aria-hidden="true"></i>
										{{ __('Save')}}
									</button>
							</div>
					</div>    
				</div>                 
			</header>
		
			<div class="col-lg-12 col-md-12 col-sm-12">
				@csrf
				<div class="row">
					<input type="hidden" name="type" id="type" value="">
					<input type="hidden" name="last_template_code" id="last_template_code" value="">
					<input type="hidden" name="html_subject_text" id="html_subject_text" value="">
					<input type="hidden" name="html_body_text" id="html_body_text" value="">
					
					
					<div class="col-md-10 offset-md-1 row">
						<h4 class="section_header_class">{{ __('Email Template information')}}</h4>
						
						<div class="row col-lg-5 col-md-5 col-sm-12">
							<label class="col-lg-5 col-md-5 col-sm-12 text-start">{{ __('Language')}}: <span class="req">*<span></label>
							<div class="col-sm-12 col-lg-5 col-md-5 selectbox">
								
								<select class="form-control m-bot15" name="language_id" id="language_id" onchange="ChangeLanguage()" >
									@foreach ($alllanguages as $key => $lan)
											<option 
											value="{{ $lan->language_code }}"
											@if ($lan->language_code == app()->getLocale())
													selected="selected"
											@endif
											">  {{ $lan->title }}</option>
									@endforeach
								</select>
								<span id="language_id_error" class="error"></span>
							</div>
						</div>
						<div class="row col-lg-7 col-md-7 col-sm-12">
							<label class="col-lg-4 col-md-4 col-sm-12 text-start">{{ __('Email Template')}}: *   </label>
							<div class="col-sm-12 col-lg-4 col-md-4 selectbox">
								
								<select class="form-control m-bot15" name="template_code" id="template_code" onchange="Fetch_page_item_info()" >
									@foreach ($email_template as $key => $template)
											<option 
											value="{{ $key }}"
											">  {{ __($template) }}</option>
									@endforeach
								</select>
								<span id="template_code_error" class="error"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="offset-md-1 col-lg-10 col-md-10">
						<div class="email_template_tbl table-responsive mt-1">
							<table id="email_template_tbl" name="email_template_tbl" width="100%" border="0" class="email_template resizable">
								<tbody>
									
									<tr align="left" valign="middle">
										<td>
											<div>{{ __('Subject')}}:</div>
											<div class="form-group-data">
												<input type="text" class="form-control" id="subject_text" name="subject_text" value="">
											</div>
										</td>
									</tr>
									<tr align="left" valign="middle">
										<td>
											<div>{{ __('Email Messsage')}}:</div>
											<div class="form-group-data">
												<textarea rows="30" name="body_text" id="body_text" type="textarea" class="form-control my_ckeditor textarea"></textarea>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="offset-md-1 col-lg-10 col-md-10">
						<button type="submit" class="btn btn-sm btn-success save_button float-end" id="update_btn">
							<i class="fa fa-plus" aria-hidden="true"></i>
							{{ __('Save')}}
						</button>
								
					</div>
				</div>
			</div>
		
		
		</form>
	</div>
</div>

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script>
	
	

	$(document).ready(function(){
		$('#back_btn').click(function (e) {							
	   	window.history.back();
		});
		// will use for responsive design
		// if($(window).width() > 991){
		// 	bind_top_nav(); 
		// } else {
		// 	bind_top_nav_mobile(); 
		// }
		$("#emailForm").submit(function(e) {
			$('.error').html('');
			if(validateForm()) {
				var body_text = CKEDITOR.instances["body_text"].getData(); 
			} else {
				e.preventDefault(e);  
			}
		});
		

		ChangeLanguage();
	}); //ready

	function ChangeLanguage(){
		Fetch_page_item_info(1);
	}

	function validateForm() {
		var subject_text = document.getElementById("subject_text").value;
		var body_text = document.getElementById("body_text").value;
		let error = false;
		if (subject_text == null || subject_text == "") {
			$('#subject_text').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
			document.getElementById("subject_text").focus();
			error = true;
		}
		if (body_text == null || body_text == "") {
			document.getElementById("body_text").focus();
			$('#body_text').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
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

	function SetContents(value) {
		// Get the editor instance that you want to interact with.
		var editor = CKEDITOR.instances.body_text;
		
		editor.setData( value );
	}

	function Fetch_page_item_info(lan=null){    
		var found=0;
		var p_template_code=document.getElementById("template_code").value;      
		document.getElementById("last_template_code").value=p_template_code;         
		var resultHtml ='',data='';
		
		document.getElementById("type").value='fetch_email_template';
		let formdata = $("#emailForm").serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
      
		formdata.push({
			"name": "_token",
			"value": csrfToken
		});
		for (key in CKEDITOR.instances) {
				CKEDITOR.instances[key].destroy(true);
		}		
		$.ajax({
				url: BASE_URL + '/fetch_email_template',
				data: formdata,
				type: 'POST',                     
				dataType: 'json',
				success: function(data) {
					if (data.status==1) {
							
							document.getElementById("subject_text").value=data.data.subject_text;
							document.getElementById("body_text").value=data.data.body_text;
							let body_text = document.getElementById("body_text");   
							body_text.innerHTML=data.data.body_text;

							var resultHtml ='';
							$.each(data.lngdata, function(key,value){
								resultHtml+='<option value="'+key+'">'+value+'</option>';  
    					});
    					$('#template_code').html(resultHtml);
							var last_template_code=document.getElementById("last_template_code").value;
							if (last_template_code != "") {
									$('#template_code').val(last_template_code);
									$('#last_template_code').val(last_template_code);
							}
							//SetContents(data.data.body_text);
					} else{
						document.getElementById("subject_text").value='';
						let body_text = document.getElementById("body_text");   
						body_text.innerHTML='';
					} 
					//$('.my_ckeditor').each( function () {
						CKEDITOR.replace( "body_text", {
							customConfig: '/ckeditor/config_email.js',
							height: 300
							,extraPlugins: 'Cy-GistInsert'
							,extraPlugins: 'AppFields'
						});
					//});  
					  
				},   // sucess
				error: function(reject) { 
					if( reject.status === 422 ) {
						let errors = $.parseJSON(reject.responseText);
						errors = errors.errors;
						$.each(errors, function (key, val) {
								$("#" + key + "_error").text(val[0]);
						});
					}
				}
		});                    
	}   //Fetch_page_item_info


	
</script>
@endsection