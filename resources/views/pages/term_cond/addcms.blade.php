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
					<div class="col-sm-10 col-xs-12 header-area">
							<div class="page_header_class">
									<label id="page_header" name="page_header">
										{{__('General_TC_PAGE_TITLE')}}
									</label>
							</div>
					</div>
					<div class="col-sm-2 col-xs-12 btn-area">
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
					</div>
				</div>
				<div class="row">
					<div class="offset-md-1 col-lg-10 col-md-10">
						<div class="tc_cms_tbl table-responsive mt-1">
							<table id="tc_cms_tbl" name="tc_cms_tbl" width="100%" border="0" class="tc_template resizable">
								<tbody>
									
                  <tr align="left" valign="middle">
                    <th align="center" width="100%" class="detail_header">GENERAL CONDITIONS OF USE OF THE SPORTLOGIN SOLUTION</th>
                  </tr>
									<tr align="left" valign="middle">
										<td>
											<div class="form-group-data">
												<textarea rows="30" name="body_text" id="body_text" type="textarea" class="form-control my_ckeditor textarea"></textarea>
											</div>
										</td>
									</tr>
                  <tr align="left" valign="middle">
                    <th align="center" width="100%" class="detail_header">GENERAL CONDITIONS OF USE OF THE SPORTLOGIN SOLUTION</th>
                  </tr>
                  <tr align="left" valign="middle">
										<td>
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
		

		//ChangeLanguage();
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