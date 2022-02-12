@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<!-- color wheel -->
<script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
@endsection

@section('content')
  <div class="m-4">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" name="page_header">Gestion des professeurs</label>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 btn-area">
					<div class="float-end btn-group">
						<a style="display: none;" id="delete_btn" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> Delete</a>
						<button id="save_btn" name="save_btn" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Save</button>
					</div>
				</div>    
			</div>          
		</header>
		<!-- Tabs navs -->

		<nav>
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Données principales</button>
				<button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Cours</button>
				<button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Sections et tarifs</button>
			</div>
		</nav>
		<!-- Tabs navs -->

		<!-- Tabs content -->
		<div class="tab-content" id="ex1-content">
			<div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
				<form action="" class="form-horizontal" id="myForm" method="post" name="myForm" role="form">
					<fieldset>
						<div class="section_header_class">
							<label id="teacher_personal_data_caption">Données personnelles du professeur</label>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="availability_select" id="visibility_label_id">Visibilité: *</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="availability_select" name="availability_select">
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="snickname" id="nickname_label_id">Pseudo:</label>
									<div class="col-sm-7">
										<input class="form-control" id="snickname" maxlength="50" name="snickname" placeholder="Pseudo" type="text" value="">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sgender_id" id="gender_label_id">Genre: *</label>
									<div class="col-sm-7">
										<div class="selectdiv">
											<select class="form-control" id="sgender_id" name="sgender_id">
											</select>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="slastname" id="family_name_label_id">Nom de famille: *</label>
									<div class="col-sm-7">
										<input class="form-control" id="slastname" name="slastname" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="sfirstname" id="first_name_label_id">Prénom: <span class="required_sign">*</span></label>
									<div class="col-sm-7">
										<input class="form-control" id="sfirstname" name="sfirstname" type="text">
									</div>
								</div>
								<div class="form-group row" style="display: none;">
									<label class="col-lg-3 col-sm-3 text-left" for="smiddlename" id="middle_name_label_id">Deuxième prénom:</label>
									<div class="col-sm-7">
										<input class="form-control" id="smiddlename" name="smiddlename" type="text">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group row" style="display: none;">
									<label class="col-lg-3 col-sm-3 text-left" for="sbirthname" id="birth_name_label_id">Nom de jeune fille:</label>
									<div class="col-sm-7">
										<input class="form-control" id="sbirthname" name="sbirthname" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="birth_date_label_id">Date de naissance:</label>
									<div class="col-sm-7">
										<div class="input-group" id="sbirth_date_div"> 
											<input id="sbirth_date" name="sbirth_date" type="text" class="form-control">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" id="slicence_js_caption">Licence Jeunesse et sport:</label>
									<div class="col-sm-7">
										<input class="form-control" id="slicence_js" name="slicence_js" type="text">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-lg-3 col-sm-3 text-left" for="semail_user_copy" id="email_caption">Email:</label>
									<div class="col-sm-7">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-envelope"></i></span> <input class="form-control" id="semail_user_copy" name="semail_user_copy" type="text">
										</div>
									</div>
								</div>
								<div class="form-group row" id="shas_user_account_div">
									<div id="shas_user_account_div111" class="row">
										<label class="col-lg-3 col-sm-3 text-left" for="shas_user_account" id="has_user_ac_label_id">Dispose d'un compte utilisateur:</label>
										<div class="col-sm-7">
											<input id="shas_user_account" name="shas_user_account" type="checkbox" value="0">
										</div>
									</div>
								</div>
								<div class="form-group row" id="authorisation_div">
										<label class="col-lg-3 col-sm-3 text-left"><span id="autorisation_caption">Autorisation</span> </label>
									<div class="col-sm-7">
										<b><input id="authorisation_all" name="authorisation_id" type="radio" value="ALL"> ALL<br>
										<input id="authorisation_med" name="authorisation_id" type="radio" value="MED"> Medium<br>
										<input checked="true" id="authorisation_min" name="authorisation_id" type="radio" value="MIN"> Minimum<br></b>
									</div>
								</div>
								<div class="form-group row" id="sbg_color_agenda_div">
									<label class="col-lg-3 col-sm-3 text-left" for="sbg_color_agenda" id="sbg_color_agenda_caption">Couleur agenda:</label>
									<div class="col-sm-2">
										<input type="text" class="colorpicker dot" />
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="address_caption">Adresse du professeur</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet" id="street_caption">Rue:</label>
										<div class="col-sm-7">
											<input class="form-control" id="sstreet" name="sstreet" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet_number" id="street_number_caption">Numéro de rue:</label>
										<div class="col-sm-7">
											<input class="form-control" id="sstreet_number" name="sstreet_number" type="text">
										</div>
									</div>
									<div class="form-group row" id="street2_div" style="display: none;">
										<label class="col-lg-3 col-sm-3 text-left" for="sstreet2" id="street2_caption">Complément de rue:</label>
										<div class="col-sm-7">
											<input class="form-control" id="sstreet2" name="sstreet2" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="szip_code" id="postal_code_caption">Code postal:</label>
										<div class="col-sm-7">
											<input class="form-control" id="szip_code" name="szip_code" type="text">
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="splace" id="locality_caption">Localité:</label>
										<div class="col-sm-7">
											<input class="form-control" id="splace" name="splace" type="text">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="scountry_id" id="pays_caption">Pays:</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" id="scountry_id" name="scountry_id">
												</select>
											</div>
										</div>
									</div>
									<div class="form-group row" id="province_id_div" style="display:none;">
										<label class="col-lg-3 col-sm-3 text-left" for="province_id" id="province_caption">Province:</label>
										<div class="col-sm-7">
											<div class="selectdiv">
												<select class="form-control" id="province_id" name="province_id">
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class">
								<label id="contact_info_caption">Informations de contact</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="sphone" id="phone_caption">Téléphone:</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-phone-square"></i></span> <input class="form-control" id="sphone" name="sphone" type="text">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="btn-group col-lg-3 col-sm-3 text-left">
											<label>Téléphone</label> <label class="text-left"></label>
										</div>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-phone"></i></span> <input class="form-control" id="sphone2" name="sphone2" type="text">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="smobile" id="mobile_caption">Téléphone mobile:</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-mobile"></i></span> <input class="form-control" id="smobile" name="smobile" type="text">
											</div>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<div class="btn-group col-lg-3 col-sm-3 text-left">
											<label for="smobile2">Téléphone mobile</label> <label class="text-left"></label>
										</div>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-mobile"></i></span> <input class="form-control" id="smobile2" name="smobile2" type="text">
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group row">
										<label class="col-lg-3 col-sm-3 text-left" for="semail" id="email_caption">Email:</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope"></i></span> <input class="form-control" id="semail" name="semail" type="text">
											</div>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<div class="btn-group col-lg-3 col-sm-3 text-left">
											<label for="semail2">Email</label> <label class="text-left">(2)</label>
										</div>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope"></i></span> <input class="form-control" id="semail2" name="semail2" type="text">
											</div>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label class="col-lg-3 col-sm-3 text-left" for="sfax" id="fax_caption">Fax:</label>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-print"></i></span> <input class="form-control" id="sfax" name="sfax" type="text">
											</div>
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<div class="btn-group col-lg-3 col-sm-3 text-left">
											<label for="sfax2">Fax</label> <label class="text-left">(2)</label>
										</div>
										<div class="col-sm-7">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-print"></i></span> <input class="form-control" id="sfax2" name="sfax2" type="text">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div id="canada_payment_div" style="display: none;">
								<div id="payment_detail_div">
									<label class="section_header_class" id="teacher_payment_detail_caption">Coordonnées de paiement professeur</label>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left" id="etransfer_acc_cap">To pay by e-transfer:</label>
											<div class="col-sm-7">
												<input class="form-control" id="etransfer_acc" name="etransfer_acc" type="text">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left">To pay by check:</label>
											<div class="col-sm-7">
												<input class="form-control" id="cheque_payee" name="cheque_payee" type="text">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="professor_payment_div" style="display: none;">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left" id="name_of_bank_caption">Nom de la banque:</label>
											<div class="col-sm-7">
												<input class="form-control" id="sbank_name" name="sbank_name" type="text">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left">Adresse:</label>
											<div class="col-sm-7">
												<input class="form-control" id="sbank_address" name="sbank_address" type="text">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left" for="sbank_zipcode">Code postal:</label>
											<div class="col-sm-7">
												<input class="form-control" id="sbank_zipcode" name="sbank_zipcode" type="text">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left" for="sbank_place">Localité:</label>
											<div class="col-sm-7">
												<input class="form-control" id="sbank_place" name="sbank_place" type="text">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left" id="pays_caption">Pays:</label>
											<div class="col-sm-7">
												<div class="selectdiv">
													<select class="form-control" id="sbank_country_id" name="sbank_country_id">
													</select>
												</div>
											</div>
										</div>
										<div class="form-group row" id="bank_province_id_div" style="display:none;">
											<label class="col-lg-3 col-sm-3 text-left" for="bank_province_id" id="bank_province_caption">Province:</label>
											<div class="col-sm-7">
												<div class="selectdiv">
													<select class="form-control" id="bank_province_id" name="bank_province_id">
													</select>
												</div>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left" id="iban_caption">IBAN:</label>
											<div class="col-sm-7">
												<input class="form-control" id="sbank_iban" name="sbank_iban" type="text">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left" id="account_number">Numéro de compte:</label>
											<div class="col-sm-7">
												<input class="form-control" id="sbank_account" name="sbank_account" type="text">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left" id="swift_number">Numéro SWIFT:</label>
											<div class="col-sm-7">
												<input class="form-control" id="sbank_swift" name="sbank_swift" type="text">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div id="commentaire_div">
								<div class="section_header_class">
									<label id="private_comment_caption">Commentaire privé</label>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left">Commentaire privé:</label>
											<div class="col-sm-7">
												<textarea class="form-control" cols="60" id="scomment" name="desc" rows="5"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="section_header_class" style="display: none;">
								<label id="about_caption">A propos du professeur</label>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group row" id="display_home_flag_div" style="display: none;">
										<label class="col-lg-3 col-sm-3 text-left" for="display_home_flag" id="disp_on_home_caption" style="display: none;">Afficher sur la page d'accueil:</label>
										<div class="col-sm-7" style="display: none;">
											<div class="selectdiv">
												<select class="form-control m-bot15" id="display_home_flag" name="display_home_flag">
													<option value="0">
														No
													</option>
													<option value="1">
														Yes
													</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div id="display_home_div" style="display: none;">
										<div class="form-group row">
											<label class="col-lg-3 col-sm-3 text-left" for="scomment" id="comment_caption">À propos de l'élève:</label>
											<div class="col-sm-7">
												<textarea class="form-control" id="about_text" maxlength="100" name="about_text" placeholder="About" rows="2"></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
				<form class="form-horizontal" role="form">
					<input id="selected_month" name="selected_month" type="hidden" value="7"> <input id="selected_year" name="selected_year" type="hidden" value="2016">
					<div class="clearfix"></div>
					<div id="teacher_disc_perc_div">
						<div class="">
							<label id="perc_deduction_warning_cap_teacher">Saisir le montant de la réduction pour la retenue de charges</label>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 col-sm-3 text-left" id="teacher_disc_perc_cap">Taux retenue charges:</label>
							<div class="col-sm-6">
								<div class="table-responsive">
									<table class="table list-item" id="tariff_table_id">
										<tr>
											<td width="20%"><input class="form-control" id="discount_perc" name="discount_perc" type="text" value="10"></td>
											<td><button class="btn btn-sm btn-primary" id="changer_btn">Changer</button></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-sm-2 text-left" id="period_caption">Choix de la période:</label>
						<div class="col-sm-3">
							<input class="form-control" id="billing_period_start_date" name="billing_period_start_date">
						</div>
						<div class="col-sm-3">
							<input class="form-control" id="billing_period_end_date" name="billing_period_end_date">
						</div>
						<div class="col-sm-2">
							<button class="btn btn-primary" id="billing_period_search_btn" type="button">Search</button>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="section_header_class">
						<label id="course_for_billing_caption">Cours disponibles à la facturation</label>
					</div>
					<div class="table-responsive">
						<table class="table lessons-list" id="lesson_table">
							<!--<thead><th colspan="5">Charges/Frix</th></thead>-->
							<tbody>
								<tr class="lesson-item-list-empty">
									<td colspan="12"><label id="lesson_item_empty_caption"></label></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="alert alert-info" id="lesson_footer_div" style="display: none;">
						<label id="verify_label_id">Veuillez vérifier toutes les entrées avant de pouvoir convertir ces éléments en facture.</label> <button class="btn btn-primary" id="btn_convert_invoice">Générer les factures assistants</button>
					</div>
				</form>
			</div>
			<div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
				<form class="form-horizontal" role="form">
					<label id="row_hdr_buy" style="display: none;"></label> <label id="row_hdr_sale" style="display: none;"></label> <!--<label style="display: none;" id="course_type_caption" name="course_type_caption"></label>-->
						<!-- <font color="blue">
							<h5>Tarifs</h5>
						</font> -->
					<div class="section_header_class">
						<label class="tarif_caption" id="tarif_caption">Tarifs</label>
					</div>
					<div class="table-responsive">
						<table class="table list-item" id="tariff_table_rate" width="100%">
							<thead>
								<tr>
									<th>#</th>
									<th><span id="course_type_caption">Hourly Rate</span></th>
									<th><span class="tarif_caption" id="tarif_caption">Tarif</span></th>
									<th class="text-right" id="buy_header">
										<span id="row_hdr_buy_cap">Buy</span>
										<h6><span id="buy_buy_caption_info" style="white-space: pre-line1;display:block;">The ‘Buy price’ is the price you ask offer the teacher for his service</span></h6>
									</th>
									<th class="text-right">
										<span id="row_hdr_sell_cap">Sell</span>
										<h6><span id="buy_sell_caption_info" style="white-space: pre-line;display:block;">The ‘Sell price’ is the price you sell your students the lesson</span></h6>
									</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot>
								<tr>
									<th colspan="5" style="text-align:right"><button class="btn btn-theme-success add-row" type="button"><em class="glyphicon glyphicon-plus"></em><span id="add_new_id">Add</span></button></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- End Tabs content -->
@endsection


@section('footer_js')
<script type="text/javascript">
$(function() {
	$("#sbirth_date").datetimepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
});

$(function() { $('.colorpicker').wheelColorPicker({ sliders: "whsvp", preview: true, format: "css" }); });
  
</script>
@endsection