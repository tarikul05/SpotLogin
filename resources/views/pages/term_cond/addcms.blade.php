@extends('layouts.main')

@section('head_links')
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
@endsection

@section('content')
<div class="content email_template_page">
	<div class="container-fluid area-container">
		<form method="POST" action="{{route('add.term_cond_cms')}}" id="tcTemplateForm" name="tcTemplateForm" class="form-horizontal" role="form">
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
					<input type="hidden" name="tc_template_id" id="tc_template_id" value="{{old('tc_template_id') ? old('tc_template_id') : ''}}">
					

					<div class="col-md-10 offset-md-1 row">
						
						<div class="row col-lg-5 col-md-5 col-sm-12">
							<label class="col-lg-5 col-md-5 col-sm-12 text-start">{{ __('Language')}}: <span class="req">*<span></label>
							<div class="col-sm-12 col-lg-5 col-md-5 selectbox">
								
								<select class="form-control m-bot15" name="language_id" id="language_id" onchange="ChangeLanguage()" >
									@foreach ($alllanguages as $key => $lan)
											<option 
											value="{{ $lan->language_code }}" {{ old('language_id') == $lan->language_code ? 'selected' : '' }}
											@if ($lan->language_code == app()->getLocale())
													selected="selected"
											@endif
											>  {{ $lan->title }}</option>
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
												<textarea rows="30" name="tc_text" id="tc_text" type="textarea" class="form-control my_ckeditor textarea">
												{{old('tc_text') ? old('tc_text') : ''}}
												</textarea>
												<span id="tc_text_error" class="error"></span>
											</div>
										</td>
									</tr>
                  <tr align="left" valign="middle">
                    <th align="center" width="100%" class="detail_header">SPORTLOGIN SOLUTION PRIVACY POLICY</th>
                  </tr>
                  <tr align="left" valign="middle">
										<td>
											<div class="form-group-data">
												<textarea rows="30" name="spp_text" id="spp_text" type="textarea" class="form-control my_ckeditor textarea">
												{{old('spp_text') ? old('spp_text') : ''}}
												</textarea>
												<span id="spp_text_error" class="error"></span>
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
		$("#tcTemplateForm").submit(function(e) {
			$('.error').html('');
			if(validateForm()) {
				//var body_text = CKEDITOR.instances["body_text"].getData(); 
			} else {
				e.preventDefault(e);  
			}
		});



		  
 

		
		Fetch_page_item_info();

	}); //ready

	function ChangeLanguage(){
		Fetch_page_item_info(1);
	}

	function validateForm() {
		var tc_text = CKEDITOR.instances["tc_text"].getData(); 
		var spp_text = CKEDITOR.instances["spp_text"].getData(); 
		
		let error = false;
		if (tc_text == null || tc_text == "") {
			$('#tc_text').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
			document.getElementById("tc_text").focus();
			error = true;
		}
		if (spp_text == null || spp_text == "") {
			document.getElementById("spp_text").focus();
			$('#spp_text').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
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
		var resultHtml ='',data='',fetched_rows =0,
		language_id=document.getElementById("language_id").value;
		if (lan != null) {
			tc_template_id=0;
			$('#tc_template_id').val(tc_template_id); 
		} else {
			tc_template_id=document.getElementById("tc_template_id").value; 
		}
		 
		
		
		document.getElementById("type").value='fetch_tc_cms_template';
		let formdata = $("#tcTemplateForm").serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
      
	
		// console.log(formdata);
		// return false;
		for (key in CKEDITOR.instances) {
				CKEDITOR.instances[key].destroy(true);
		}		
		$.ajax({
				url: BASE_URL + '/fetch_tc_cms_template',
				data: formdata,
				type: 'POST',                     
				dataType: 'json',
				success: function(data) {
					if (data.status==1) {
							
							document.getElementById("tc_text").value=data.data.tc_text;
							// let tc_text = document.getElementById("tc_text");   
							// tc_text.innerHTML=data.data.tc_text;
							document.getElementById("spp_text").value=data.data.spp_text;
							// let spp_text = document.getElementById("spp_text");   
							// spp_text.innerHTML=data.data.spp_text;

							
							var tc_template_id=data.data.tc_template_id;
							if (tc_template_id != "") {
									$('#tc_template_id').val(tc_template_id);
							}
							//SetContents(data.data.body_text);
					} else{
						document.getElementById("tc_text").value='';
						document.getElementById("spp_text").value='';
					} 


					var langid=document.getElementById("language_id").value;
					var ckfinder_html='ckfinder/ckfinder.html?type=Images&time='+new Date().getTime();
					$('.my_ckeditor').each( function () {
						//CKEDITOR.replace( "body_text", {
							CKEDITOR.replace( this.id, {
										//customConfig: '/ckeditor/config_all.js',
										height: 300
										,extraPlugins: 'Cy-GistInsert'
										,extraPlugins: 'AppFields'
										,language: langid
										,filebrowserBrowseUrl: 'ckfinder/ckfinder.html'
										,filebrowserImageBrowseUrl: ckfinder_html
										,filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
										,filebrowserImageUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload'
									});
					});
					 
					  
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