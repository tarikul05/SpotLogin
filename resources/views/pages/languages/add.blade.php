@extends('layouts.main')

@section('content')
	<div class="content language_page">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header"class="page_title">Language Setup</label>
					</div>
				</div>
				<div class="col-md-6 offset-md-2">
					<div class="form-group row">
						<label class="col-lg-4 col-sm-4 text-end">Language code: </label>
						<div class="col-sm-6">
							<div class="selectdiv">
								<input class="form-control" id="code" name="code" type="text">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-4 col-sm-4 text-end">Language name: </label>
						<div class="col-sm-6">
							<input class="form-control" id="name" name="name" type="text">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-4 col-sm-4 text-end">Language short name: </label>
						<div class="col-sm-6">
							<input class="form-control" id="short_name" name="short_name" type="text">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-4 col-sm-4 text-end">On / Off: </label>
						<div class="col-sm-6">
							<select class="form-control" id="province_id" name="province_id">
								<option value="1">active</option>
                                <option value="0">Inactive</option>
							</select>
						</div>
						<div class="col-lg-2 col-md-2">
							<a class="btn btn-success btn-theme-success save_button float-end" href="" id="update_btn"> 
								<i class="fa fa-plus-circle" aria-hidden="true"></i>Save
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
			<div class="offset-md-1 col-lg-10 col-md-10">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Language code</th>
								<th scope="col">Language name</th>
								<th scope="col">Language short name</th>
								<th scope="col">Status</th>
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
									<i class="fa fa-pencil" aria-hidden="true"></i>Update
								</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- End Tabs content -->
@endsection
