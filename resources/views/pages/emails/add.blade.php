@extends('layouts.main')

@section('head_links')
	<script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
@endsection

@section('content')
<div class="content email_template_page">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 col-xs-12 header-area">
				<div class="page_header_class">
					<label id="page_header"class="page_title">Email Template</label>
				</div>
			</div>
			
			<h4 class="section_header_class">Email Template information</h4>

			<div class="col-md-10 offset-md-1 row">
				<div class="form-group col-lg-5 col-md-5 row">
					<label class="col-lg-5 col-sm-5 text-start">Language: <span class="req">*<span></label>
					<div class="col-sm-6 selectbox">
						<select class="form-control" id="province_id" name="province_id">
							<option value="1">active</option>
							<option value="0">Inactive</option>
						</select>
					</div>
				</div>
				<div class="form-group col-lg-7 col-md-7 row">
					<label class="col-lg-4 col-sm-4 text-start">Email Template: *   </label>
					<div class="col-sm-7 selectbox">
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
			<div class="table-responsive">
				<table id="email_template_tbl" name="email_template_tbl" width="100%" border="0" class="email_template resizable">
					<tbody>
						<tr align="left" valign="middle">
							<td align="center" width="15%">Subject:</td>
							<td><input type="text" class="form-control" id="subject_text" name="subject_text" value="Facture en attente de paiement"></td>
						</tr>
						<tr align="left" valign="middle">
							<td align="center">Email Messsage:</td>
							<td>
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
</div>

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script>
	ClassicEditor
		.create( document.querySelector( '#editor' ) )
		.catch( error => {
			console.error( error );
		} );
</script>
@endsection