@extends('layouts.main')

@section('head_links')
	<!-- <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script> -->
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
@endsection

@section('content')
<div class="content email_template_page">
	<div class="container-fluid area-container">
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
								<button class="btn btn-sm btn-success save_button float-end" id="save_btn" name="save_btn">
									<i class="fa fa-plus"></i>
									{{ __('to safeguard')}}
								</button>
						</div>
				</div>    
			</div>                 
		</header>
		<form method="POST" action="{{route('add.language')}}" id="langForm" name="langForm" class="form-horizontal" role="form">
			<div class="col-lg-12 col-md-12 col-sm-12">
				
				<div class="row">
					
					
					<div class="col-md-10 offset-md-1 row">
						<h4 class="section_header_class">Email Template information</h4>
						
						<div class="row col-lg-5 col-md-5 col-sm-12">
							<label class="col-lg-5 col-md-5 col-sm-12 text-start">Language: <span class="req">*<span></label>
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
								</select>
							</div>
						</div>
						<div class="row col-lg-7 col-md-7 col-sm-12">
							<label class="col-lg-4 col-md-4 col-sm-12 text-start">Email Template: *   </label>
							<div class="col-sm-12 col-lg-4 col-md-4 selectbox">
								<select class="form-control" id="province_id" name="province_id">
									<option value="1">active</option>
									<option value="0">Inactive</option>
								</select>
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
											<div>Subject:</div>
											<input type="text" class="form-control" id="subject_text" name="subject_text" value="Facture en attente de paiement">
										</td>
									</tr>
									<tr align="left" valign="middle">
										<td>
											<div>Email Messsage:</div>

											<div id="editor">
												<p>This is some sample content.</p>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="offset-md-1 col-lg-10 col-md-10">
						<a class="btn btn-sm btn-success save_button float-end" href="" id="save_btn1" name="save_btn1"><i class="fa fa-plus"></i> sauvegarder</a>
					</div>
				</div>
		</form>
	</div>
</div>

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script>
	// ClassicEditor
	// 	.create(document.querySelector('#editor'),
	// 		{
	// 			//toolbar: [ 'bold', 'italic' ]
	// 		}
	//  		//document.querySelector( '#editor' ) 
	//  	)
	// 	.catch( error => {
	// 		console.error( error );
	// 	} );
	
		$('#editor').each( function () {
                        CKEDITOR.replace( this.id, {
													  customConfig: '/ckeditor/config_email.js',
                            height: 300
                            //,extraPlugins: 'smiley'
                            //,extraPlugins: 'font'
                            ,extraPlugins: 'Cy-GistInsert'
														//,extraPlugins: 'uicolor'
														,extraPlugins: 'AppFields'
                        });
                    });  

	// Replace the <textarea id="editor1"> with a CKEditor
	// instance, using default configuration.
	// CKEDITOR.editorConfig = function (config) {
	// 		config.language = 'es';
	// 		config.uiColor = '#F7B42C';
	// 		config.height = 300;
	// 		config.toolbarCanCollapse = true;

	// };
	// CKEDITOR.replace('editor');
	
	$(document).ready(function(){
		// will use for responsive design
		// if($(window).width() > 991){
		// 	bind_top_nav(); 
		// } else {
		// 	bind_top_nav_mobile(); 
		// }

		//PopulateLanguageList();
		//ChangeLanguage();
	}); //ready
</script>
@endsection