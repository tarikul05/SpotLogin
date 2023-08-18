@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<!-- color wheel -->
<script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="content" id="manual_invoice_page">
	<div class="container-fluid body">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0; padding-top:15px;">
				<div class="col-sm-6 col-xs-12 header-area" style="padding-bottom:25px;">
					<div class="page_header_class">
						<label id="page_header" name="page_header"><i class="fa-solid fa-file-invoice"></i> {{__('Manual Invoice')}}</label>
					</div>
				</div>
                <div class="col-sm-6 col-xs-12 btn-area pt-1">
                    <div class="float-end btn-group save-button ">
                       <button id="save_btn" style="display: block;" name="save_btn" class="btn btn-sm btn-primary invoice_save_btn">Save</button>
                    </div>
                </div>
			</div>
		</header>
        <?php //echo '<pre>' ; print_r($InvoicesExpData); exit; ?>
        <div class="row mb-5" style="margin:0;">
            <div class="col-lg-12">
                <div id="content">
                    <form role="form" id="form_main" class="form-horizontal tbl_wrp_form" method="post" action="">
                        @csrf
                        <input type="hidden" id="auto_id" name="auto_id" value="0">
                        <input type="hidden" id="invoice_filename" name="invoice_filename" value="">
                        <input type="hidden" id="action" name="action" value="new">
                        <input type="hidden" id="total_min" name="action" value="">
                        <input type="hidden" id="person_id" name="person_id" value="">
                        <input type="hidden" id="invoice_status_id" name="invoice_status_id" value="1">
                        <input type="hidden" id="invoice_id" name="invoice_id" value="">
                        <input type="hidden" id="invoice_type" name="invoice_type" value="0">
                        <input type="hidden" id="approved_flag" name="approved_flag" value="0">
                        <input type="hidden" id="payment_status" name="payment_status" value="0">
                        <select style="display:none;" class="form-control" id="inv_payment_status" name="inv_payment_status"></select>
                        <label style="display:none;" id="invoice_date_cap" name="invoice_date_cap">Date of invoice</label>
                        <label style="display:none;" id="payment_info_cap" name="payment_info_cap">Payment Information</label>
                        <label style="display:none;" id="bank_caption" name="bank_caption">Bank</label>
                        <label style="display:none;" id="holder_cap" name="holder_cap">Account Holder</label>
                        <fieldset>
                            <table id="table_header" class="table table-stripped" width="100%" style="background:rgb(223, 228, 230);">
                                <tbody>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <label class="gilroy-semibold light-blue-txt" id="lbl_detail_hdr">Informations sur les factures</label>
                                        </td>
                                        <td colspan="1" align="center">
                                            <label id="payment_status_label" class="gilroy-semibold light-blue-txt">Payment Status:</label>
                                            <label id="payment_status_text"></label>
                                        </td>
                                        <td colspan="1" align="center"> <span>
                                            <label id="invoice_status" class="gilroy-semibold light-blue-txt">Invoice Status: New</label>
                                            <button style="display: none;" id="button_unlock" class="btn btn-xs btn-warning">Unlock</button>
                                            </span> </td>
                                    </tr>
                                    <tr>
                                        <td class="row_hdr_invoice_name">
                                            <label id="row_hdr_invoice_name" class="txtdarkblue gilroy-semibold text-right">Invoice Name</label>
                                        </td>
                                        <td class="invoice_name">
                                            <input id="invoice_name" name="invoice_name" type="text" class="form-control" placeholder="Invoice Name" tabindex="0" maxlength="150">
                                        </td>
                                        <td class="lbl_date_invoice" align="center">
                                            <label id="lbl_date_invoice" class="txtdarkblue gilroy-semibold text-right">Date of invoice</label>
                                        </td>
                                        <td class="date_invoice_div">
                                            <div class="input-group datepicker" id="date_invoice_div">
                                                <!--<input id="date_invoice" name="date_invoice" type="text" class="form-control datepicker" /> -->
                                                <input id="date_invoice" name="date_invoice" type="text" placeholder="Date of invoice" class="form-control datetimepicker">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- client info -->
                            <div class="section_header_class">
								<label class="invoice_subtitle">{{__('Client Information') }}:</label>
							</div>
                            <?php //echo '<pre>'; print_r($students);exit; ?>
                            <div id="client_detail_id" open="">
                                <div id="table_client">
                                    <div class="card">
                                        <div class="card-body bg-tertiary">
                                    <div class="row">
                                        <div class="col-sm-9 col-md-3" style="margin-bottom: 15px;">
                                            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                                                <input id="client_list_id" class="form-control" list="client_seller_datalist" name="client_list_id" onchange="get_client_seller_info(this)" autocomplete="on">
                                                <datalist id="client_seller_datalist">
                                                        <option value="{{ $school->school_name }} (SCHOOL)" data-type="school" id="{{ $school->id }}" <="" option=""></option>
                                                    @foreach($students as $key => $student)
													    <option value="{{ $student->firstname }} {{ $student->lastname }} (STUDENT)" data-type="student" id="{{ $student->student_id }}" <="" option=""></option>
												    @endforeach
                                                    @foreach($teachers as $key => $professor)
													    <option value="{{ $professor->full_name }} (TEACHER)" data-type="teacher" id="{{ $professor->teacher_id }}" <="" option=""></option>
												    @endforeach
                                                </datalist>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="table_client_detail">
                                        <div class="col-md-6">
                                            <div class="form-group row" style="display: none;">
                                                <input type="hidden" id="client_id" name="client_id" value="EC7E9C27-1B10-11EC-9CF6-067B4964D503">
                                                <label id="client_name_caption" name="client_name_caption" for="client_name" class="col-lg-3 col-sm-3 text-left">Client Name</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="client_name" name="client_name" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="first_name_label_id" name="first_name_label_id" for="client_firstname" class="col-lg-3 col-sm-3 text-left">First Name : *</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="client_firstname" name="client_firstname" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="street_caption" name="street_caption" for="client_street" class="col-lg-3 col-sm-3 text-left">Street</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="client_street" name="client_street" value="" placeholder="" maxlength="120"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="street_number_caption" name="street_number_caption" for="client_street_number" class="col-lg-3 col-sm-3 text-left">Street No :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="client_street_number" name="client_street_number" value="" placeholder="" maxlength="20"> </div>
                                            </div>
                                            <div class="form-group row" style="display: none;">
                                                <label id="street2_caption" name="street2_caption" for="client_street2" class="col-lg-3 col-sm-3 text-left">Street 2 :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="client_street2" name="client_street2" value="" placeholder="" maxlength="100"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="postal_code_caption" name="postal_code_caption" for="client_zip_code" class="col-lg-3 col-sm-3 text-left">Postal Code :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="client_zip_code" name="client_zip_code" value="" placeholder="" maxlength="8"> </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label id="family_name_label_id" name="family_name_label_id" for="client_lastname" class="col-lg-3 col-sm-3 text-left">Family Name :*</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="client_lastname" name="client_lastname" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="pays_caption" name="pays_caption" for="client_country_id" class="col-lg-3 col-sm-3 text-left">Country :</label>
                                                <div class="col-sm-7">
                                                    <div class="selectdiv">
                                                        <select class="form-control select_two_defult_class" id="client_country_id" name="client_country_id">
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->code }}">{{ $country->name }} ({{ $country->code }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="client_province_id_div" style="display:none;">
                                                <label id="province_caption" for="client_province_id" class="col-lg-3 col-sm-3 text-left">Province</label>
                                                <div class="col-sm-7">
                                                    <div class="selectdiv">
                                                        <select class="form-control select_two_defult_class" id="client_province_id" name="client_province_id">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="locality_caption" name="locality_caption" for="client_place" class="col-lg-3 col-sm-3 text-left">City :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="client_place" name="client_place" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    </div>
                                </div>
                            </div>
                            <!-- client info END -->
                            <!-- Seller info -->
                            <div class="section_header_class">
								<label class="invoice_subtitle">{{__('Account Invoicing') }}:</label>
							</div>

                            <div class="card">
                                <div class="card-body bg-tertiary">

                            <div id="seller_detail_id" open="">
                                <!-- <summary></summary> -->
                                <div id="table_seller">
                                    <?php //echo '<pre>';print_r($AppUI);exit;
                                        if($AppUI->isTeacherSchoolAdmin()){ ?>
                                    <div class="row">
                                        <div class="col-sm-9 col-md-3" style="margin-bottom: 15px;">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </span>
                                            <input id="seller_list_id" class="form-control" list="creator_seller_datalist" name="seller_list_id" onchange="get_client_seller_info(this)" autocomplete="off"> </div>
                                            <datalist id="creator_seller_datalist">
                                                    <option value="The school account" data-type="school" id="{{ $school->id }}" <="" option=""></option>
                                                    <option value="My account" data-type="teacher" id="{{ $AppUI->person_id }}" <="" option=""></option>
                                            </datalist>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="row" id="table_seller_detail">
                                        <div class="col-md-6">
                                            <div class="form-group row" style="display: none;">
                                                <input type="hidden" id="seller_id" name="seller_id" value="">
                                                <label id="seller_name_caption" name="seller_name_caption" for="seller_name" class="col-lg-3 col-sm-3 text-left">Seller Name</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_name" name="seller_name" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="first_name_label_id" name="first_name_label_id" for="seller_firstname" class="col-lg-3 col-sm-3 text-left">First Name : *</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_firstname" name="seller_firstname" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="street_caption" name="street_caption" for="seller_street" class="col-lg-3 col-sm-3 text-left">Street</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_street" name="seller_street" value="" placeholder="" maxlength="120"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="street_number_caption" name="street_number_caption" for="seller_street_number" class="col-lg-3 col-sm-3 text-left">Street No :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_street_number" name="seller_street_number" value="" placeholder="" maxlength="15"> </div>
                                            </div>
                                            <div class="form-group row" style="display: none;">
                                                <label id="street2_caption" name="street2_caption" for="seller_street2" class="col-lg-3 col-sm-3 text-left">Street 2 :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_street2" name="seller_street2" value="" placeholder="" maxlength="100"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="postal_code_caption" name="postal_code_caption" for="seller_zip_code" class="col-lg-3 col-sm-3 text-left">Postal Code :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_zip_code" name="seller_zip_code" value="" placeholder="" maxlength="8"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="phone_caption" name="phone_caption" for="seller_phone" class="col-lg-3 col-sm-3 text-left">Téléphone</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_phone" name="seller_phone" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label id="family_name_label_id" name="family_name_label_id" for="seller_lastname" class="col-lg-3 col-sm-3 text-left">Family Name :*</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_lastname" name="seller_lastname" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="pays_caption" name="pays_caption" for="seller_country_id" class="col-lg-3 col-sm-3 text-left">Country :</label>
                                                <div class="col-sm-7">
                                                    <div class="selectdiv">
                                                        <select class="form-control select_two_defult_class" id="seller_country_id" name="seller_country_id">
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->code }}">{{ $country->name }} ({{ $country->code }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="seller_province_id_div" style="display:none">
                                                <label id="province_caption" for="seller_province_id" class="col-lg-3 col-sm-3 text-left">Province</label>
                                                <div class="col-sm-7">
                                                    <div class="selectdiv">
                                                        <select class="form-control select_two_defult_class" id="seller_province_id" name="seller_province_id">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="locality_caption" name="locality_caption" for="seller_place" class="col-lg-3 col-sm-3 text-left">City :</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_place" name="seller_place" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="email_caption" name="email_caption" for="seller_email" class="col-lg-3 col-sm-3 text-left">email</label>
                                                <div class="col-sm-7">
                                                    <input type="email" class="form-control" id="seller_email" name="seller_strseller_emaileet2" value="" placeholder="" maxlength="100"> </div>
                                            </div>
                                            <div class="form-group row" style="display: none;">
                                                <label id="mobile_caption" name="mobile_caption" for="seller_mobile" class="col-lg-3 col-sm-3 text-left">Mobile</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="seller_mobile" name="seller_mobile" value="" placeholder="" maxlength="150"> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                </div>
                            </div>
                            <!-- Seller info END -->
                            <!-- Payment Bank info for Seller -->
                            @if ($isInEurope)
                            <div class="section_header_class">
								<label class="invoice_subtitle">{{__('Payment Bank Information') }}:</label>
							</div>

                            <div class="card">
                                <div class="card-body bg-tertiary">

                            <div id="" open="">
                                <div class="row" id="payment_bank_info">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label id="payment_bank_account_name_cap" name="payment_bank_account_name_cap" for="payment_bank_account_name" class="col-lg-2 col-sm-2 text-left">Payment Bank Account Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="payment_bank_account_name" name="payment_bank_account_name" value="" placeholder="" maxlength="100"> </div>
                                        </div>
                                        <div class="form-group row">
                                            <label id="name_of_bank_caption" name="name_of_bank_caption" for="payment_bank_name" class="col-lg-2 col-sm-2 text-left">Bank Name</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="payment_bank_name" name="payment_bank_name" value="" placeholder="" maxlength="100"> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label id="address_caption" name="address_caption" for="payment_bank_address" class="col-lg-3 col-sm-3 text-left">Address</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="payment_bank_address" name="payment_bank_address" value="" placeholder="" maxlength="100"> </div>
                                        </div>
                                        <div class="form-group row">
                                            <label id="postal_code_caption" name="postal_code_caption" for="payment_bank_zipcode" class="col-lg-3 col-sm-3 text-left">Postal Code :</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="payment_bank_zipcode" name="payment_bank_zipcode" value="" placeholder="" maxlength="8"> </div>
                                        </div>
                                        <div class="form-group row">
                                            <label id="locality_caption" name="locality_caption" for="payment_bank_place" class="col-lg-3 col-sm-3 text-left">City :</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="payment_bank_place" name="payment_bank_place" value="" placeholder="" maxlength="150"> </div>
                                        </div>
                                        <div class="form-group row">
                                            <label id="account_number" name="account_number" for="payment_bank_account" class="col-lg-3 col-sm-3 text-left">Account No</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="payment_bank_account" name="payment_bank_account" value="" placeholder="" maxlength="30"> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label id="pays_caption" name="pays_caption" for="payment_bank_country_id" class="col-lg-3 col-sm-3 text-left">Country :</label>
                                            <div class="col-sm-7">
                                                <div class="selectdiv">
                                                    <select class="form-control select_two_defult_class" id="payment_bank_country_id" name="payment_bank_country_id">
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->code }}">{{ $country->name }} ({{ $country->code }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" id="bank_province_id_div" style="display:none">
                                            <label id="province_caption" for="bank_province_id" class="col-lg-3 col-sm-3 text-left">Province</label>
                                            <div class="col-sm-7">
                                                <div class="selectdiv">
                                                    <select class="form-control select_two_defult_class" id="bank_province_id" name="bank_province_id">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label id="iban_caption" name="iban_caption" for="payment_bank_iban" class="col-lg-3 col-sm-3 text-left">IBAN No</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="payment_bank_iban" name="payment_bank_iban" value="" placeholder="" maxlength="50"> </div>
                                        </div>
                                        <div class="form-group row">
                                            <label id="swift_number" name="swift_number" for="payment_bank_swift" class="col-lg-3 col-sm-3 text-left">SWIFT A/c No</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="payment_bank_swift" name="payment_bank_swift" value="" placeholder="" maxlength="30"> </div>
                                        </div>
                                        <div class="form-group row">
                                            <label id="label_payment_phone" name="payment_phone" for="payment_phone" class="col-lg-3 col-sm-3 text-left">Phone</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="payment_phone" name="payment_phone" value="" placeholder="" maxlength="150"> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="payment_bank_info_canada">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label id="e_transfer_email_caption" for="e_transfer_email" class="col-lg-3 col-sm-3 text-left">E-transfer e-mail</label>
                                            <div class="col-sm-7">
                                                <input type="email" class="form-control" id="e_transfer_email" name="e_transfer_email" value="" placeholder="E-transfer e-mail" maxlength="100"> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none;">
                                        <div class="form-group row">
                                            <label id="name_for_checks_caption" for="name_for_checks" class="col-lg-3 col-sm-3 text-left">Name for Checks</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="name_for_checks" name="name_for_checks" value="" placeholder="Name for Checks" maxlength="100"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                </div>
                            </div>
                            @else
                            <div class="section_header_class">
								<label class="invoice_subtitle">{{__('Payment Information') }}:</label>
							</div>
                            <div class="card">
                                <div class="card-body bg-tertiary">
                            <div id="" open="">
                                <div class="row" id="payment_bank_info">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label id="payment_bank_account_name_cap" name="payment_bank_account_name_cap" for="payment_bank_account_name" class="col-lg-12 col-sm-12 text-left">Payment preference</label>
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control" id="payment_bank_account_name" name="payment_bank_account_name" value="" placeholder="" maxlength="100">
                                            </div>
                                        </div>
                                            <input type="hidden" class="form-control" id="payment_bank_name" name="payment_bank_name" value="" placeholder="" maxlength="100">
                                            <input type="hidden" class="form-control" id="payment_bank_address" name="payment_bank_address" value="" placeholder="" maxlength="100">
                                            <input type="hidden" class="form-control" id="payment_bank_zipcode" name="payment_bank_zipcode" value="" placeholder="" maxlength="8">
                                            <input type="hidden" class="form-control" id="payment_bank_place" name="payment_bank_place" value="" placeholder="" maxlength="150">
                                            <input type="hidden" class="form-control" id="payment_bank_account" name="payment_bank_account" value="" placeholder="" maxlength="30">
                                            <input type="hidden" class="form-control" id="payment_bank_country_id" value="US" name="payment_bank_country_id">
                                            <input type="hidden" class="form-control" id="bank_province_id" name="bank_province_id">
                                            <input type="hidden" class="form-control" id="payment_bank_iban" name="payment_bank_iban" value="" placeholder="" maxlength="50">
                                            <input type="hidden" class="form-control" id="payment_bank_swift" name="payment_bank_swift" value="" placeholder="" maxlength="30">
                                            <input type="hidden" class="form-control" id="payment_phone" name="payment_phone" value="" placeholder="" maxlength="150">
                                    </div>
                                </div>
                                <div class="row" id="payment_bank_info_canada">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label id="e_transfer_email_caption" for="e_transfer_email" class="col-lg-12 col-sm-12 text-left">E-transfer e-mail</label>
                                            <div class="col-sm-12">
                                                <input type="email" class="form-control" id="e_transfer_email" name="e_transfer_email" value="" placeholder="E-transfer e-mail" maxlength="100"> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none;">
                                        <div class="form-group row">
                                            <label id="name_for_checks_caption" for="name_for_checks" class="col-lg-3 col-sm-3 text-left">Name for Checks</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="name_for_checks" name="name_for_checks" value="" placeholder="Name for Checks" maxlength="100"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                </div>
                            </div>
                            @endif
                            <!-- Payment Bank info for Seller -->
                            <!-- Transaction Detail info -->
                            <div class="section_header_class">
								<label class="invoice_subtitle">{{__('Invoice Detail Information') }}:</label>
							</div>
                            <div id="details_transaction" open="">
                                <!-- <summary><h4></h4></summary> -->
                                <table id="details_tbl" class="table table-stripped" width="100%" cellpadding="0" cellspacing="0" style="background:rgb(223, 228, 230);">
                                    <thead>
                                        <tr>
                                            <th style="display: none;" width="0%">#</th>
                                            <th class="manual_inv_date">
                                                <label id="row_hdr_date" name="row_hdr_date" class="gilroy-semibold light-blue-txt">Date</label>
                                            </th>
                                            <th width="manual_inv_txt">
                                                <label id="item_particular_caption" name="item_particular_caption" class="gilroy-semibold light-blue-txt">Description/Detail</label>
                                            </th>
                                            <th width="10%" style="text-align:center;">
                                                <label id="row_hdr_amount" name="row_hdr_amount" class="gilroy-semibold light-blue-txt">Currency</label>
                                                <div class="selectdiv">
                                                    <select class="form-control" id="price_currency" name="price_currency">
                                                       @foreach($currency as $key => $curr)
                                                       @if($curr->currency_code == $school_currency)
                                                       <option value="{{$curr->currency_code}}" selected>{{$curr->currency_code}}</option>
                                                        @else
                                                            <option value="{{$curr->currency_code}}">{{$curr->currency_code}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </th>
                                            <th width="2%" align="center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="tr_row_id" class="detail_row">
                                            <td style="display: none;">1</td>
                                            <td>
                                                <div class="input-group datetimepicker" id="date_div">
                                                    <input id="date" name="date[]" type="text" class="form-control datetimepicker" value="">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" id="caption" name="caption[]" placeholder="" class="form-control">
                                            </td>
                                            <td class="row_item_value">
                                                <input type="text" pattern="[0-9.]" id="total_item1" name="total_item[]" placeholder="" style="text-align: right;" class="form-control numeric float item_value">
                                            </td>
                                            <td>
                                                <button tabindex="-1" onclick="remove_rows(this)" type="button" id="del" class="btn btn-theme-warn delete_row">
                                                <i class="fa fa-remove"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="txtdarkblue gilroy-semibold" style="text-align:right">Grand Total:</th>
                                            <th colspan="1" style="text-align:right">
                                                <label name="grand_total" id="grand_total" class="txtdarkblue gilroy-semibold" style="text-align: right;">0.00</label>
                                            </th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="5" style="text-align:right;">
                                                <button type="button" class="btn btn-theme-success add-row"><em class="glyphicon glyphicon-plus"></em>Add invoice item</button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- Transaction Detail info END -->


                            <div id="add_tax_div" open="">

                                <?php $count = count($RegisterTaxData); ?>
                                <?php if($count > 0) { ?>
                                <div class="section_header_class">
                                    <label class="invoice_subtitle">{{__('Taxes') }}:</label>
                                </div>
                                You have <?php echo $count; ?> pre-registered {{$count > 1 ? __('taxes') : __('tax')}}
                                <?php } else { ?>
                                    <div class="section_header_class">
                                        <label class="invoice_subtitle">{{__('Add Taxes') }}:</label>
                                    </div>
                                    <div class="card mb-2">
                                    <div class="card-body bg-tertiary">
                                        <span class="badge bg-info">new</span>
                                    <div class="add_more_tax_row row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label id="tax_name_caption" for="tax_name" class="col-lg-3 col-sm-3 text-left">Name of Tax</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="tax_name[]" value="" placeholder="Tax Name" maxlength="255"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="tax_percentage_caption" for="tax_percentage" class="col-lg-3 col-sm-3 text-left">% of Tax</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control tax_percentage" name="tax_percentage[]" value="" placeholder="Tax Percentage" maxlength="5"> </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label id="tax_number_caption" for="tax_number" class="col-lg-3 col-sm-3 text-left">Tax Number</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="tax_number[]" value="" placeholder="Tax Number" maxlength="255"> </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="tax_amount_caption" for="tax_amount" class="col-lg-3 col-sm-3 text-left">Price</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control tax_amount" name="tax_amount[]" value="" placeholder="Tax Amount" maxlength="100"> </div>
                                            </div>
                                        </div>
                                    </div></div>
                                    </div>
                                <?php } ?>

                                <?php foreach($RegisterTaxData as $tax): ?>
                                <div class="add_more_tax_row card mb-2">
                                    <div class="card-body bg-tertiary">
                                <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label id="tax_name_caption" for="tax_name" class="col-lg-3 col-sm-3 text-left">Name of Tax</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="tax_name[]" value="<?= $tax['tax_name'] ?>" placeholder="Tax Name" maxlength="255">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="tax_percentage_caption" for="tax_percentage" class="col-lg-3 col-sm-3 text-left">% of Tax</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control tax_percentage" name="tax_percentage[]" value="<?= $tax['tax_percentage'] ?>" placeholder="Tax Percentage" maxlength="5">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label id="tax_number_caption" for="tax_number" class="col-lg-3 col-sm-3 text-left">Tax Number</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="tax_number[]" value="<?= $tax['tax_number'] ?>" placeholder="Tax Number" maxlength="255">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label id="tax_amount_caption" for="tax_amount" class="col-lg-3 col-sm-3 text-left">Price</label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control tax_amount" name="tax_amount[]" value="" placeholder="Tax Amount" maxlength="100">
                                                </div>
                                                <div class="col-sm-1">
                                                    <button type="button" class="btn btn-theme-warn delete_tax"><i class="fa-solid fa-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div></div>
                                <?php endforeach; ?>



                                <div id="add_more_tax_div"></div>
                                <div class="row col-md-12 mt-3 mb-5">
                                    <button id="add_more_tax_btn" type="button" class="btn btn-theme-success"><em class="glyphicon glyphicon-plus"></em>Add Another Tax</button>
                                </div>
                            </div>


                            <div id="add_expense_div" open="">
                                <div class="section_header_class">
                                    <label class="invoice_subtitle">{{__('Add Expenses') }}:</label>
                                </div>
                                <div class="card mb-2">
                                    <div class="card-body bg-tertiary">
                                        <span class="badge bg-info">new</span>
                                <div class="add_more_expense_row row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label id="expense_name_caption" for="expense_name" class="col-lg-3 col-sm-3 text-left">Name of Expense</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="expense_name[]" value="" placeholder="Expense Name" maxlength="255"> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label id="expense_amount_caption" for="expense_amount" class="col-lg-3 col-sm-3 text-left">Amount</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="expense_amount[]" value="" placeholder="Expense Amount" maxlength="100"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                <div id="add_more_expense_div"></div>

                            </div></fieldset>
                            </div>
                                </div>
                                <div class="row col-md-12 mt-3 mb-5">
                                    <button id="add_more_expense_btn" type="button" class="btn btn-theme-success"><em class="glyphicon glyphicon-plus"></em>Add Another Expense</button>


                        </fieldset>
                    </form>
                </div>
    </div>
</div>

@endsection


@section('footer_js')

<script type="text/javascript">

var isInEurope = {{ $isInEurope ? 'true' : 'false' }};

$(document).on('click','#add_more_tax_btn',function(){

    var resultHtml = `<div class="add_more_tax_row card mb-2">
                <div class="card-body bg-tertiary">
                    <span class="badge bg-info">new</span>
                    <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label id="tax_name_caption" for="tax_name" class="col-lg-3 col-sm-3 text-left">Name of Tax</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="tax_name[]" value="" placeholder="Tax Name" maxlength="255">
                    </div>
                </div>
                <div class="form-group row">
                    <label id="tax_percentage_caption" for="tax_percentage" class="col-lg-3 col-sm-3 text-left">% of Tax</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control tax_percentage" name="tax_percentage[]" value="" placeholder="Tax Percentage" maxlength="5">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group row">
                    <label id="tax_number_caption" for="tax_number" class="col-lg-3 col-sm-3 text-left">Tax Number</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="tax_number[]" value="" placeholder="Tax Number" maxlength="255">
                    </div>
                </div>
                <div class="form-group row">
                    <label id="tax_amount_caption" for="tax_amount" class="col-lg-3 col-sm-3 text-left">Price</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control tax_amount" name="tax_amount[]" value="" placeholder="Tax Amount" maxlength="100">
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-theme-warn delete_tax"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>
            </div>
        </div></div>
        </div>`;

        $("#add_more_tax_div").append(resultHtml);

})

$(document).on('click','.delete_tax',function(){
    $(this).parents('.add_more_tax_row').remove();
})

$(document).on('click','#add_more_expense_btn',function(){

var resultHtml = `<div class="card add_more_expense_row mb-2">
    <div class="card-body bg-tertiary">
        <span class="badge bg-info">new</span>
        <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label id="expense_name_caption" for="expense_name" class="col-lg-3 col-sm-3 text-left">Name of Expense</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="expense_name[]" value="" placeholder="Expense Name" maxlength="255">
                </div>
            </div>
        </div>

        <div class="col-md-6">

            <div class="form-group row">
                <label id="expense_amount_caption" for="expense_amount" class="col-lg-3 col-sm-3 text-left">Amount</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="expense_amount[]" value="" placeholder="Expense Amount" maxlength="100">
                </div>
                <div class="col-sm-1">
                        <button type="button" class="btn btn-theme-warn delete_expense"><i class="fa-solid fa-trash"></i></button>
                    </div>
            </div>
        </div></div>
    </div>
    </div>`;

    $("#add_more_expense_div").append(resultHtml);

})

$(document).on('click','.delete_expense',function(){
    $(this).parents('.add_more_expense_row').remove();
})

$(".add-row").click(function(){
    var i =document.getElementById("details_tbl").rows.length-2;

    var markup = '<tr id="tr_row_id" class="detail_row"><td style="display: none;">'+i+'</td>';
    markup+='<td><div class="input-group datetimepicker" id="date_div"> <input name="date[]" type="text" class="form-control date_picker" value=""/><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></td>';
    markup+='<td><input type="text" id="caption" name="caption[]" placeholder="" class="form-control"></td>';
    markup+='<td class="row_item_value"><input type="text" pattern="[0-9.]" id="total_item'+i+'" name="total_item[]" placeholder="" style="text-align: right;" pattern="^[0-9]\d{0,9}(\.\d{1,3})?%?$" class="form-control numeric float item_value"></td>';
    markup+='<td><button tabIndex="-1" onclick="remove_rows(this)" type="button" id="del" class="btn btn-theme-warn delete_row"><i class="fa fa-remove"></i></button></td>';
    //markup+='<td><button tabIndex="-1" onclick="remove_rows(this)" type="button" id="del" class="delete_row">X</button></td>';
    markup+='</tr>';

    $("#details_tbl tbody").append(markup);

    $('.date_picker').datetimepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
    });

});

$('#details_tbl').on('keyup','.item_value',function(event) {
    CalculateTotal();
});

function remove_rows(r){
    var i = document.getElementById("details_tbl").rows.length; //get total rows including header footer
    //alert(i);
    if (i>4){
        i = r.parentNode.parentNode.rowIndex;
        document.getElementById("details_tbl").deleteRow(i);
        //$(this).parent("tr").remove();
    };

    CalculateTotal();
};

function CalculateTotal(){
var mtotal=0,curval=0;
var mytable = document.getElementById("details_tbl");
var rCount = mytable.rows.length-1;
for (var i=1; i<rCount; i++){
    try {
        curval=parseFloat(mytable.rows[i].cells[3].getElementsByTagName('input')[0].value);
        if (isNaN(curval)==false) {
            mtotal+=curval;
        }else {
            //mtotal+=curval;
        }
    }
    catch(err) {
            //alert('i='+i+' error:'+err.message);
    }
};


document.getElementById("grand_total").innerHTML=mtotal.toFixed(2);

    $(".tax_percentage").each(function(){
        if($(this).val()){
            var tax_percentage = Number($(this).val());
            var tax_amount = parseFloat(Number(mtotal * tax_percentage/100)).toFixed(2);
            $(this).parents('.add_more_tax_row').find('.tax_amount').val(tax_amount);
        }
    })

    /*$(".tax_amount").each(function(){
        if($(this).val()){
            var tax_amount = Number($(this).val());
            var tax_percentage = parseFloat(Number(tax_amount/mtotal * 100)).toFixed(2);
            $(this).parents('.add_more_tax_row').find('.tax_percentage').val(tax_percentage);
        }
    })*/
}

 $(function() {
	$("#date").datetimepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});

    $("#date_invoice").datetimepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});


    $(".date_picker").datetimepicker({
        format: "dd.mm.yyyy",
        autoclose: true,
        todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});

    var now = new Date();
    var month = now.getMonth()+1;
    var day = now.getDate();
    var current_date = ((''+day).length<2 ? '0' : '') + day+ '.' +((''+month).length<2 ? '0' : '') + month + '.'+ now.getFullYear();
    $("#date_invoice").val( current_date );

 });

$( document ).ready(function() {
    var userType = '<?= $AppUI->roleType(); ?>' ;

    if(userType == 'school_admin' || userType == 'teacher_admin'){
        var p_code=<?= $AppUI->school_id; ?> ;;
	    var p_type='school';
    }else{
        var p_code=<?= $AppUI->person_id; ?> ;
	    var p_type='teacher';
    }

    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$.ajax({
	url: BASE_URL + '/invoice_data',
	data: 'p_type='+p_type+'&p_code='+p_code,
	type: 'POST',
	dataType: 'json',
	async: false,
	success: function(data) {
		var resultHtml ='';
		$.each(data, function(key,value){

            if (p_type == 'teacher') {
				document.getElementById("invoice_type").value=0;
			}else if (p_type == 'school') {
				document.getElementById("invoice_type").value=0;
			}

			document.getElementById("seller_id").value=p_code;

            if (p_type == 'school') {
                document.getElementById("seller_name").value=value.school_name;
                document.getElementById("seller_firstname").value=value.school_name;
                document.getElementById("seller_lastname").value=value.school_name;
            }else{
                document.getElementById("seller_name").value=value.firstname +' '+ value.lastname;
                document.getElementById("seller_firstname").value=value.firstname;
                document.getElementById("seller_lastname").value=value.lastname;
            }


			document.getElementById("seller_street_number").value=value.street_number
			document.getElementById("seller_street").value=value.street;
			document.getElementById("seller_street2").value=value.street2;
			document.getElementById("seller_country_id").value=value.country_code;
			if(value.country_code == 'CA'){
				// PopulateProvince(value.country_code,'seller');
			}
			if(value.province_id > 0){
				$("#seller_province_id").val(value.province_id);
			}
			document.getElementById("seller_zip_code").value=value.zip_code;
			document.getElementById("seller_place").value=value.place;

			document.getElementById("seller_email").value=value.email;

			document.getElementById("payment_bank_account_name").value=value.bank_account;

            if(isInEurope) {
                document.getElementById("payment_bank_name").value=value.bank_name;
                document.getElementById("payment_bank_address").value=value.bank_address;
                document.getElementById("payment_bank_zipcode").value=value.bank_zipcode;
                document.getElementById("payment_bank_place").value=value.bank_place;
                document.getElementById("payment_bank_country_id").value=value.bank_country_id;
            }

			if(value.bank_country_id == 'CA'){
				// PopulateProvince(value.bank_country_id == 'CA','bank');
			}
			if (value.bank_province_id > 0){
				$("#bank_province_id").val(value.bank_province_id );
			}

            if(isInEurope) {
                document.getElementById("payment_bank_iban").value=value.bank_iban;
                document.getElementById("payment_bank_account").value=value.bank_account;
                document.getElementById("payment_bank_swift").value=value.bank_swift;
            }

			document.getElementById("seller_phone").value=value.phone;
			document.getElementById("seller_mobile").value=value.mobile;


		});
	},   // sucess
	error: function(ts) {
		//errorModalCall(GetAppMessage('error_message_text'));

		}
	});
});

$( document ).ready(function() {
    $width = $( window ).width();
    if($width <= 767){
        $('.invoice_name').attr('colspan',3);
    }else{
        $('invoice_name').removeAttr('colspan');
    }
});

function get_client_seller_info(obj){
    var opt = $("option[value='"+obj.value+"']");
   if (opt.attr('id') == undefined) return false;
    var p_code=opt.attr('id');
	var p_type=opt.attr('data-type');

    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$.ajax({
	url: BASE_URL + '/invoice_data',
	data: 'p_type='+p_type+'&p_code='+p_code,
	type: 'POST',
	dataType: 'json',
	async: false,
	success: function(data) {
		var resultHtml ='';
		$.each(data, function(key,value){

			if (p_type == 'student') {
				document.getElementById("invoice_type").value=0;
			}else if (p_type == 'teacher') {
				document.getElementById("invoice_type").value=0;
			}else if (p_type == 'school') {
				document.getElementById("invoice_type").value=0;
			}

			if (obj.id == "client_list_id") {

                if (value.firstname != null){
                    var fname = value.firstname;
                }else{
                    var fname = value.school_name
                }

				document.getElementById("client_id").value=p_code;
				document.getElementById("client_name").value=value.firstname +' '+ value.lastname;

				document.getElementById("client_firstname").value= fname;
				document.getElementById("client_lastname").value=value.lastname;

				document.getElementById("client_street_number").value=value.street_number;
				document.getElementById("client_street").value=value.street;
				document.getElementById("client_street2").value=value.street2;
				document.getElementById("client_country_id").value=value.country_code

               // document.getElementById("select2-client_country_id-container").textContent = getCountryName(value.country_code) + ' (' + value.country_code + ')';

				if(value.country_code == 'CA'){
					 $('#client_province_id_div').show();
                     $('#seller_province_id_div').show();
				}else{
                     $('#client_province_id_div').hide();
                     $('#seller_province_id_div').hide();
                }

				if(value.province_id > 0){
					$("#client_province_id").val(value.province_id);
				}

				document.getElementById("client_zip_code").value=value.zip_code;
				document.getElementById("client_place").value=value.place;

		}else if (obj.id == "seller_list_id"){

			document.getElementById("seller_id").value=p_code;

			if (p_type == 'school') {
                document.getElementById("seller_name").value=value.school_name;
                document.getElementById("seller_firstname").value=value.school_name;
                document.getElementById("seller_lastname").value=value.school_name;
            }else{
                document.getElementById("seller_name").value=value.firstname +' '+ value.lastname;
                document.getElementById("seller_firstname").value=value.firstname;
                document.getElementById("seller_lastname").value=value.lastname;
            }


			document.getElementById("seller_street_number").value=value.street_number
			document.getElementById("seller_street").value=value.street;
			document.getElementById("seller_street2").value=value.street2;
			document.getElementById("seller_country_id").value=value.country_code;
			if(value.country_code == 'CA'){
				// PopulateProvince(value.country_code,'seller');
			}
			if(value.province_id > 0){
				$("#seller_province_id").val(value.province_id);
			}
			document.getElementById("seller_zip_code").value=value.zip_code;
			document.getElementById("seller_place").value=value.place;

			document.getElementById("seller_email").value=value.email;

			document.getElementById("payment_bank_account_name").value=value.bank_account;

            if(isInEurope) {
                document.getElementById("payment_bank_name").value=value.bank_name;
                document.getElementById("payment_bank_address").value=value.bank_address;
                document.getElementById("payment_bank_zipcode").value=value.bank_zipcode;
                document.getElementById("payment_bank_place").value=value.bank_place;
                document.getElementById("payment_bank_country_id").value=value.bank_country_id;
            }

			if(value.bank_country_id == 'CA'){
				// PopulateProvince(value.bank_country_id == 'CA','bank');
			}
			if (value.bank_province_id > 0){
				$("#bank_province_id").val(value.bank_province_id );
			}

            if(isInEurope) {
                document.getElementById("payment_bank_iban").value=value.bank_iban;
                document.getElementById("payment_bank_account").value=value.bank_account;
                document.getElementById("payment_bank_swift").value=value.bank_swift;
            }

			document.getElementById("seller_phone").value=value.phone;
			document.getElementById("seller_mobile").value=value.mobile;
		}

		});
	},   // sucess
	error: function(ts) {
		//errorModalCall(GetAppMessage('error_message_text'));

		}
	});

}

// Get country name from country code
/*function getCountryName(countryCode) {
    var listCountries = @json($countries)
  for (var i = 0; i < listCountries.length; i++) {
    if (listCountries[i].code === countryCode) {
      return listCountries[i].name;
    }
  }
  return null; // Retourne null si aucun pays ne correspond au code
}*/

function PopulateProvince(country_code, type = null) {
    $.ajax({
        url: '../admin/get_master_data.php',
        data: 'type=province_list&country_code=' + country_code,
        type: 'POST',
        dataType: 'json',
        async: false,
        success: function (data) {
            if (type == 'client') {
                if (data) { $("#client_province_id_div").show(); } else { $("#client_province_id_div").hide(); }
            }
            if (type == 'seller') {
                if (data) { $("#seller_province_id_div").show(); } else { $("#seller_province_id_div").hide(); }
            }
            if (type == 'bank') {
                if (data) { $("#bank_province_id_div").show(); } else { $("#bank_province_id_div").hide(); }
            }
            var resultHtml = '<option value="">Select Province</option>';
            $.each(data, function (key, value) {
                resultHtml += '<option value="' + value.province_id + '">' + value.province_name + '</option>';
            });

            if (type == 'address') {
                $('#client_province_id').html(resultHtml);
            } else if (type == 'seller') {
                $("#seller_province_id").html(resultHtml);
            } else if (type == 'bank') {
                $("#bank_province_id").html(resultHtml);
            } else {
                $('#client_province_id').html(resultHtml);
                $("#seller_province_id").html(resultHtml);
                $("#bank_province_id").html(resultHtml);
            }
        }, // sucess
        error: function (ts) {
            errorModalCall(GetAppMessage('error_message_text'));

        }
    });
}

$('#save_btn').click(function (e) {
    e.preventDefault();
    AddEditInvoice();
});

function AddEditInvoice(){
    var p_auto_id= document.getElementById("auto_id").value;
    var p_invoice_id = document.getElementById("invoice_id").value;
    var p_date_invoice = document.getElementById("date_invoice").value;
    var p_invoice_name = document.getElementById("invoice_name").value;
    var p_price_currency = document.getElementById("price_currency").value;
    //alert(p_price_currency)
    if (p_invoice_name ==''){
        //errorModalCall(GetAppMessage('Invalid_invoice')+' name.');

        //alert(GetAppMessage("Invalid_invoice")+' name.');
        //return false;
    }
    if (p_date_invoice ==''){
        //errorModalCall(GetAppMessage('Invalid_invoice')+' date.');

        //alert(GetAppMessage("Invalid_invoice")+' date');
        //return false;
    }

    if (p_price_currency == '') {
        //errorModalCall(GetAppMessage('Invalid_invoice')+' Currency.');

        //alert(GetAppMessage("Invalid_invoice")+' Currency.');
        //return false;
    }

    p_date_invoice=p_date_invoice.replace("/",".");
    p_date_invoice=p_date_invoice.replace("/",".");

    var p_invoice_status_id = document.getElementById("invoice_status_id").value;
    var p_invoice_type = document.getElementById("invoice_type").value;


    var p_client_id = document.getElementById("client_id").value;
    var p_client_name = document.getElementById("client_name").value;
    var p_client_firstname = document.getElementById("client_firstname").value;
    var p_client_lastname = document.getElementById("client_lastname").value;
    var p_client_street_number = document.getElementById("client_street_number").value;
    var p_client_street = document.getElementById("client_street").value;
    var p_client_street2 = document.getElementById("client_street2").value;
    var p_client_country_id = document.getElementById("client_country_id").value;
    var p_client_province_id = document.getElementById("client_province_id").value;


    var p_client_zip_code = document.getElementById("client_zip_code").value;
    var p_client_place = document.getElementById("client_place").value;
    var p_seller_id = document.getElementById("seller_id").value;
    var p_seller_name = document.getElementById("seller_name").value;
    var p_seller_firstname = document.getElementById("seller_firstname").value;
    var p_seller_lastname = document.getElementById("seller_lastname").value;
    var p_seller_street_number = document.getElementById("seller_street_number").value;
    var p_seller_street = document.getElementById("seller_street").value;
    var p_seller_street2 = document.getElementById("seller_street2").value;
    var p_seller_country_id = document.getElementById("seller_country_id").value;
    var p_seller_province_id = document.getElementById("seller_province_id").value;

    var p_seller_zip_code = document.getElementById("seller_zip_code").value;
    var p_seller_place = document.getElementById("seller_place").value;
    var p_seller_phone = document.getElementById("seller_phone").value;
    var p_seller_mobile = document.getElementById("seller_mobile").value;
    var p_seller_email = document.getElementById("seller_email").value;
    var p_payment_bank_account_name = document.getElementById("payment_bank_account_name").value;

    var p_payment_bank_name = !isInEurope ? '' : document.getElementById("payment_bank_name").value;
    var p_payment_bank_address = !isInEurope ? '' : document.getElementById("payment_bank_address").value;
    var p_payment_bank_country_id = !isInEurope ? '' : document.getElementById("payment_bank_country_id").value;
    var p_bank_province_id = !isInEurope ? '' : document.getElementById("bank_province_id").value;
    var p_payment_bank_zipcode = !isInEurope ? '' : document.getElementById("payment_bank_zipcode").value;
    var p_payment_bank_place = !isInEurope ? '' : document.getElementById("payment_bank_place").value;
    var p_payment_bank_iban = !isInEurope ? '' : document.getElementById("payment_bank_iban").value;
    var p_payment_bank_account = !isInEurope ? '' : document.getElementById("payment_bank_account").value;
    var p_payment_bank_swift = !isInEurope ? '' : document.getElementById("payment_bank_swift").value;
    var p_total_amount = document.getElementById("grand_total").innerText;
    var p_detail_rows = '';

    var tbl = document.getElementById("details_tbl");
    var rCount = tbl.rows.length;
    var mdt,mcaption,mtotal_item;
    var valid_det_rec_flag=0;       // to check if any valid detail record exists or not for validation.

    var item_date = $("input[name='date[]']").map(function(){return $(this).val();}).get();
    var item_caption = $("input[name='caption[]']").map(function(){return $(this).val();}).get();
    var item_total = $("input[name='total_item[]']").map(function(){return $(this).val();}).get();

    var p_e_transfer_email = $("#e_transfer_email").val();
    var p_name_for_checks = $("#name_for_checks").val();
    var p_payment_phone = $("#payment_phone").val();

    var tax_name = $("input[name='tax_name[]']").map(function(){return $(this).val();}).get();
    var tax_percentage = $("input[name='tax_percentage[]']").map(function(){return $(this).val();}).get();
    var tax_number = $("input[name='tax_number[]']").map(function(){return $(this).val();}).get();
    var tax_amount = $("input[name='tax_amount[]']").map(function(){return $(this).val();}).get();

    var expense_name = $("input[name='expense_name[]']").map(function(){return $(this).val();}).get();
    var expense_amount = $("input[name='expense_amount[]']").map(function(){return $(this).val();}).get();


    if (p_auto_id == ''){
        p_auto_id = 0;
    }
    if (p_invoice_type == ''){
        p_invoice_type = 0;
    }
    var status_flag='';
    var data='' ;

        $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

        $.ajax({
            url: BASE_URL + '/{{$schoolId}}/store_invoice_data',
            data: {
                p_auto_id:p_auto_id,
                p_invoice_id:p_invoice_id,
                p_invoice_type:p_invoice_type,
                p_invoice_name:p_invoice_name,
                p_date_invoice:p_date_invoice,
                p_client_id:p_client_id,
                p_client_name:p_client_name,
                p_client_firstname:p_client_firstname,
                p_client_lastname:p_client_lastname,
                p_client_street_number:p_client_street_number,
                p_client_street:p_client_street,
                p_client_street2:p_client_street2,
                p_client_country_id:p_client_country_id,
                p_client_zip_code:p_client_zip_code,
                p_client_place:p_client_place,
                p_seller_id:p_seller_id,
                p_seller_name:p_seller_name,
                p_seller_firstname:p_seller_firstname,
                p_seller_lastname:p_seller_lastname,
                p_seller_street_number:p_seller_street_number,
                p_seller_street:p_seller_street,
                p_seller_street2:p_seller_street2,
                p_seller_country_id:p_seller_country_id,
                p_seller_zip_code:p_seller_zip_code,
                p_seller_phone:p_seller_phone,
                p_seller_mobile:p_seller_mobile,
                p_seller_place:p_seller_place,
                p_seller_email:p_seller_email,
                p_payment_bank_account_name:p_payment_bank_account_name,
                p_payment_bank_name:p_payment_bank_name,
                p_payment_bank_address:p_payment_bank_address,
                p_payment_bank_country_id:p_payment_bank_country_id,
                p_payment_bank_zipcode:p_payment_bank_zipcode,
                p_payment_bank_place:p_payment_bank_place,
                p_payment_bank_iban:p_payment_bank_iban,
                p_payment_bank_account:p_payment_bank_account,
                p_payment_bank_swift:p_payment_bank_swift,
                p_price_currency:p_price_currency,
                p_detail_rows:p_detail_rows,
                p_client_province_id:p_client_province_id,
                p_seller_province_id:p_seller_province_id,
                p_bank_province_id:p_bank_province_id,
                item_date: item_date,
                item_caption: item_caption,
                item_total: item_total,
                tax_name:tax_name,
                tax_percentage:tax_percentage,
                tax_number:tax_number,
                tax_amount:tax_amount,
                expense_name:expense_name ,
                expense_amount:expense_amount,
                p_e_transfer_email:p_e_transfer_email,
                p_payment_phone:p_payment_phone,
                p_name_for_checks:p_name_for_checks,
                p_total_amount:p_total_amount
            },
            type: 'POST',
            dataType: 'json',
            async: false,
            encode:true,
            success:function(result){
                if(result.status == 1){
                    status_flag = 'success';
                    p_auto_id=result.id;
                    var newURL = BASE_URL + '/admin/' + result.schoolId + '/modification-invoice/' + p_auto_id;
                    window.location.href = newURL //window.location.href+'/'+p_auto_id
                }
            },   // success
            error: function(ts) {
                //errorModalCall(ts.responseText+' '+GetAppMessage('error_message_text'));

            }

        }); //ajax-type

        return false;

}   //AddEditInvoice


$(document).on('keyup','.tax_percentage',function(){
    var grand_total = Number($("#grand_total").text());
    var tax_percentage = Number($(this).val());
    var tax_amount = parseFloat(Number(grand_total * tax_percentage/100)).toFixed(2);
    $(this).parents('.add_more_tax_row').find('.tax_amount').val(tax_amount);
})

$(document).on('keyup','.tax_amount',function(){
    var grand_total = Number($("#grand_total").text());
    var tax_amount = Number($(this).val());
    var tax_percentage = parseFloat(Number(tax_amount/grand_total * 100)).toFixed(2);
    $(this).parents('.add_more_tax_row').find('.tax_percentage').val(tax_percentage);
})

$(document).on('change','#client_firstname',function(){
    if(!$("#invoice_name").val() && $(this).val()){
        var now = new Date();
        var month = now.getMonth()+1;
        var day = now.getDate();
        var current_date=now.getFullYear() + '-' +((''+month).length<2 ? '0' : '') + month + '-' +((''+day).length<2 ? '0' : '') + day;
        var time = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
        var invoice_name = 'INV/'+$(this).val()+'/'+current_date;
        $("#invoice_name").val(invoice_name);
    }

})

$(document).on('change','#client_list_id',function(){
    if(!$("#invoice_name").val() && $("#client_firstname").val()){
        var now = new Date();
        var month = now.getMonth()+1;
        var day = now.getDate();
        var current_date=now.getFullYear() + '-' +((''+month).length<2 ? '0' : '') + month + '-' +((''+day).length<2 ? '0' : '') + day;
        var time = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
        var invoice_name = 'INV/'+$("#client_firstname").val()+'/'+current_date;
        $("#invoice_name").val(invoice_name);
    }

})


</script>
<script type="text/javascript">
	/*
	* manual client province list
	* function @billing province
	*/
	$(document).ready(function(){
		var country_code = $('#client_country_id option:selected').val();
        console.log(country_code,'clientclientclient');
		get_client_province_lists(country_code);
	});

	$('#client_country_id').change(function(){
		var country_code = $(this).val();
		get_client_province_lists(country_code);
	})

	function get_client_province_lists(country_code){
		$.ajax({
			url: BASE_URL + '/get_province_by_country',
			data: 'country_name=' + country_code,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(response) {
					if(response.data.length > 0){
						var html = '';
						$.each(response.data, function(i, item) {
							html += '<option value="'+ item.id +'">' + item.province_name + '</option>';
						});
						$('#client_province_id').html(html);
						$('#client_province_id_div').show();
				}else{
					$('#client_province_id').html('');
					$('#client_province_id_div').hide();
				}
			},
			error: function(e) {
				//error
			}
		});
	}

	/*
	* Seller province list
	* function @billing province
	*/
	$('#seller_country_id').change(function(){
		var country_code = $(this).val();
		get_seller_province_lists(country_code);
	})

	$(document).ready(function(){
		var country_code = $('#seller_country_id option:selected').val();
        console.log(country_code,'country_codecountry_code');
		get_seller_province_lists(country_code);
	});

	function get_seller_province_lists(country_code){
		$.ajax({
			url: BASE_URL + '/get_province_by_country',
			data: 'country_name=' + country_code,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(response) {
					if(response.data.length > 0){
						var html = '';
						$.each(response.data, function(i, item) {
							html += '<option value="'+ item.id +'">' + item.province_name + '</option>';
						});
						$('#seller_province_id').html(html);
						$('#seller_province_id_div').show();
				}else{
					$('#seller_province_id').html('');
					$('#seller_province_id_div').hide();
				}
			},
			error: function(e) {
				//error
			}
		});
	}

    /*
	* bank payment province list
	* function @billing province
	*/
	$('#payment_bank_country_id').change(function(){
		var country_code = $(this).val();
		get_bankpayment_province_lists(country_code);
	})

	$(document).ready(function(){
		var country_code = $('#payment_bank_country_id option:selected').val();
		get_bankpayment_province_lists(country_code);
	});

	function get_bankpayment_province_lists(country_code){
		$.ajax({
			url: BASE_URL + '/get_province_by_country',
			data: 'country_name=' + country_code,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(response) {
					if(response.data.length > 0){
						var html = '';
						$.each(response.data, function(i, item) {
							html += '<option value="'+ item.id +'">' + item.province_name + '</option>';
						});
						$('#bank_province_id').html(html);
						$('#bank_province_id_div').show();
				}else{
					$('#bank_province_id').html('');
					$('#bank_province_id_div').hide();
				}
			},
			error: function(e) {
				//error
			}
		});
	}

</script>
@endsection
