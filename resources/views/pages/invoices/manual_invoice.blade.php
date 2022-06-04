@extends('layouts.main')

@section('head_links')
<!-- datetimepicker -->
<script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
<!-- color wheel -->
<script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
<script src="{{ asset('ckeditor/ckeditor.js')}}"></script>
@endsection

@section('content')
<div class="content" id="manual_invoice_page">
	<div class="container-fluid">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area">
					<div class="page_header_class">
						<label id="page_header" name="page_header">{{__('Manual Detail')}}</label>
					</div>
				</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 btn-area">
                    <div class="btn-group save-button pull-right"> 
                        <a id="issue_inv_btn" style="display: block;" name="issue_inv_btn" class="btn btn-sm btn-success" target="">
                        <i class="fa fa-cog" aria-hidden="true"></i> Issue invoice
                        </a> 
                        <a id="print_preview_btn" style="display: block;" name="print_preview_btn" class="btn btn-sm btn-default" target="_blank">Print Preview</a> 
                        <a id="delete_btn" style="display: block!important;" name="delete_btn" class="btn btn-sm btn-danger" href="">Delete</a>
                        <button id="save_btn" style="display: block;" name="save_btn" class="btn btn-sm btn-primary">Save</button> 
                        <button id="approved_btn" style="display: none;" target="" href="" class="btn btn-sm btn-primary">Send by email</button> 
                        <a id="download_pdf_btn" name="download_pdf_btn" style="display: none;" target="" href="" class="btn btn-sm btn-default">Download PDF</a> 
                    </div>
                </div>
			</div>
		</header>
        <div class="row" style="margin:0;">
            <div class="col-lg-12">
                <div id="content">
                    <form role="form" id="form_main" class="form-horizontal tbl_wrp_form" method="post" action="">
                        <input type="hidden" id="auto_id" name="auto_id" value="0">
                        <input type="hidden" id="invoice_filename" name="invoice_filename" value="">
                        <input type="hidden" id="action" name="action" value="new">
                        <input type="hidden" id="total_min" name="action" value="">
                        <input type="hidden" id="person_id" name="person_id" value="">
                        <input type="hidden" id="invoice_status_id" name="invoice_status_id" value="1">
                        <input type="hidden" id="invoice_id" name="invoice_id" value="">
                        <input type="hidden" id="invoice_type" name="invoice_type" value="2">
                        <input type="hidden" id="approved_flag" name="approved_flag" value="0">
                        <input type="hidden" id="payment_status" name="payment_status" value="0">
                        <select style="display:none;" class="form-control" id="inv_payment_status" name="inv_payment_status"></select>
                        <label style="display:none;" id="invoice_date_cap" name="invoice_date_cap">Date of invoice</label>
                        <label style="display:none;" id="payment_info_cap" name="payment_info_cap">Payment Information</label>
                        <label style="display:none;" id="bank_caption" name="bank_caption">Bank</label>
                        <label style="display:none;" id="holder_cap" name="holder_cap">Account Holder</label>
                        <fieldset>
                            <table id="table_header" width="100%" border="1" style="background:lightblue;">
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
                                        <td width="15%">
                                            <label id="row_hdr_invoice_name" class="txtdarkblue gilroy-semibold text-right">Invoice Name</label>
                                        </td>
                                        <td>
                                            <input id="invoice_name" name="invoice_name" type="text" class="form-control" tabindex="0" maxlength="150"> </td>
                                        <td width="20%" align="center">
                                            <label id="lbl_date_invoice" class="txtdarkblue gilroy-semibold text-right">Date of invoice</label>
                                        </td>
                                        <td width="20%">
                                            <div class="input-group datepicker" id="date_invoice_div">
                                                <!--<input id="date_invoice" name="date_invoice" type="text" class="form-control datepicker" /> -->
                                                <input id="date_invoice" name="date_invoice" type="text" class="form-control datetimepicker"> 
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
                            <div id="client_detail_id" open="">
                                <div id="table_client">
                                    <div class="row">
                                        <div class="col-sm-9 col-md-3" style="margin-bottom: 15px;">
                                            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                                                <input id="client_list_id" class="form-control" list="client_seller_datalist" name="client_list_id" onchange="get_client_seller_info(this)" autocomplete="on">
                                                <datalist id="client_seller_datalist">
                                                    <option value="ammy roy (STUDENT)" id="2601914D-B642-11EC-BC5D-067B4964D503" <="" option=""></option>
                                                    <option value="Arindam Biswas (TEACHER)" id="EC7E9C27-1B10-11EC-9CF6-067B4964D503" <="" option=""></option>
                                                    <option value="Arindam Student (STUDENT)" id="C63B4A39-1C41-11EC-9CF6-067B4964D503" <="" option=""></option>
                                                    <option value="Arindam1 Biswas1 (STUDENT)" id="9C256610-2B8F-11EC-8F1E-067B4964D503" <="" option=""></option>
                                                    <option value="avijit chakraborty (STUDENT)" id="30043550-2B7C-11EC-8F1E-067B4964D503" <="" option=""></option>
                                                    <option value="bappa adak (STUDENT)" id="C141E48D-4248-11EC-82EC-067B4964D503" <="" option=""></option>
                                                    <option value="bappa1 adak1 (STUDENT)" id="F85C0586-4248-11EC-82EC-067B4964D503" <="" option=""></option>
                                                    <option value="birendra Middey (STUDENT)" id="75236B91-42F7-11EC-A50B-067B4964D503" <="" option=""></option>
                                                    <option value="Bivas Adak (STUDENT)" id="98B5B50C-4243-11EC-82EC-067B4964D503" <="" option=""></option>
                                                    <option value="bobby powali (STUDENT)" id="02ECC159-424A-11EC-82EC-067B4964D503" <="" option=""></option>
                                                    <option value="clara clara O`corner (STUDENT)" id="B77DAF18-B42E-11EC-AC37-067B4964D503" <="" option=""></option>
                                                    <option value="das1 samir1 (STUDENT)" id="AE29659F-7556-11E8-BBE1-0A608F1BF91B" <="" option=""></option>
                                                    <option value="jani Middey (STUDENT)" id="C71CAAE1-37AE-11EC-8F1E-067B4964D503" <="" option=""></option>
                                                    <option value="Junior1 middey (STUDENT)" id="085EB422-2E4E-11EC-8F1E-067B4964D503" <="" option=""></option>
                                                    <option value="manas adak (STUDENT)" id="952DC9E3-41E8-11EC-82EC-067B4964D503" <="" option=""></option>
                                                    <option value="megha dutta (STUDENT)" id="E8C9AF95-1EC3-11EC-9CF6-067B4964D503" <="" option=""></option>
                                                    <option value="Middey O`corner (STUDENT)" id="5DD7E31A-B430-11EC-AC37-067B4964D503" <="" option=""></option>
                                                    <option value="mihir middey (STUDENT)" id="702E7F7E-41E3-11EC-82EC-067B4964D503" <="" option=""></option>
                                                    <option value="sammy middey (STUDENT)" id="08E7AB5F-8615-11EA-8FFD-0A608F1BF91B" <="" option=""></option>
                                                    <option value="santanu khamaru (STUDENT)" id="7476E688-4249-11EC-82EC-067B4964D503" <="" option=""></option>
                                                    <option value="Soumendra Middey (STUDENT)" id="A629D0F5-18A6-11EC-9CF6-067B4964D503" <="" option=""></option>
                                                    <option value="srija middey (STUDENT)" id="06805313-7555-11E8-BBE1-0A608F1BF91B" <="" option=""></option>
                                                    <option value="sss sss (STUDENT)" id="A3BD258F-AB30-11EC-AC37-067B4964D503" <="" option=""></option>
                                                    <option value="student1 middey (STUDENT)" id="34552992-2E59-11EC-8F1E-067B4964D503" <="" option=""></option>
                                                    <option value="suparna dutta (TEACHER)" id="3330B801-1EC4-11EC-9CF6-067B4964D503" <="" option=""></option>
                                                    <option value="teacher all (TEACHER)" id="6503D09C-9DB7-11EA-8FFD-0A608F1BF91B" <="" option=""></option>
                                                    <option value="teacher med (TEACHER)" id="CC6AB82C-9DB7-11EA-8FFD-0A608F1BF91B" <="" option=""></option>
                                                    <option value="teacher min (TEACHER)" id="14086343-9DB8-11EA-8FFD-0A608F1BF91B" <="" option=""></option>
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
                                                        <select class="form-control" id="client_country_id" name="client_country_id">
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->code }}">{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="client_province_id_div" style="display:none;">
                                                <label id="province_caption" for="client_province_id" class="col-lg-3 col-sm-3 text-left">Province</label>
                                                <div class="col-sm-7">
                                                    <div class="selectdiv">
                                                        <select class="form-control" id="client_province_id" name="client_province_id"> 
                                                            <option value="">Select Province</option>
                                                            <option value="3">Alberta</option>
                                                            <option value="2">British Columbia</option>
                                                            <option value="5">Manitoba</option>
                                                            <option value="10">Newfoundland &amp; Labrador</option>
                                                            <option value="12">Northwest territory</option>
                                                            <option value="8">Nova Scotia</option>
                                                            <option value="11">Nunavut</option>
                                                            <option value="6">Ontario</option>
                                                            <option value="9">PEI</option>
                                                            <option value="7">Quebec</option>
                                                            <option value="4">Saskatchewan</option>
                                                            <option value="13">Yukon</option>
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
                            <!-- client info END -->
                            <!-- Seller info -->
                            <div class="section_header_class">
								<label class="invoice_subtitle">{{__('Basic data Seller (creditor of invoice)') }}:</label>
							</div>
                            <div id="seller_detail_id" open="">
                                <!-- <summary></summary> -->
                                <div id="table_seller">
                                    <div class="row">
                                        <div class="col-sm-9 col-md-3" style="margin-bottom: 15px;">
                                            <div class="input-group"> 
                                            <span class="input-group-addon">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </span>
                                            <input id="seller_list_id" class="form-control" list="client_seller_datalist" name="seller_list_id" onchange="get_client_seller_info(this)" autocomplete="on"> </div>
                                        </div>
                                    </div>
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
                                                        <select class="form-control" id="seller_country_id" name="seller_country_id">
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->code }}">{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="seller_province_id_div" style="display:none">
                                                <label id="province_caption" for="seller_province_id" class="col-lg-3 col-sm-3 text-left">Province</label>
                                                <div class="col-sm-7">
                                                    <div class="selectdiv">
                                                        <select class="form-control" id="seller_province_id" name="seller_province_id">
                                                            <option value="">Select Province</option>
                                                            <option value="3">Alberta</option>
                                                            <option value="2">British Columbia</option>
                                                            <option value="5">Manitoba</option>
                                                            <option value="10">Newfoundland &amp; Labrador</option>
                                                            <option value="12">Northwest territory</option>
                                                            <option value="8">Nova Scotia</option>
                                                            <option value="11">Nunavut</option>
                                                            <option value="6">Ontario</option>
                                                            <option value="9">PEI</option>
                                                            <option value="7">Quebec</option>
                                                            <option value="4">Saskatchewan</option>
                                                            <option value="13">Yukon</option>
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
                            <!-- Seller info END -->
                            <!-- Payment Bank info for Seller -->
                            <div class="section_header_class">
								<label class="invoice_subtitle">{{__('Payment Bank Information') }}:</label>
							</div>
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
                                                    <select class="form-control" id="payment_bank_country_id" name="payment_bank_country_id">
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row" id="bank_province_id_div" style="display:none">
                                            <label id="province_caption" for="bank_province_id" class="col-lg-3 col-sm-3 text-left">Province</label>
                                            <div class="col-sm-7">
                                                <div class="selectdiv">
                                                    <select class="form-control" id="bank_province_id" name="bank_province_id">
                                                        <option value="">Select Province</option>
                                                        <option value="3">Alberta</option>
                                                        <option value="2">British Columbia</option>
                                                        <option value="5">Manitoba</option>
                                                        <option value="10">Newfoundland &amp; Labrador</option>
                                                        <option value="12">Northwest territory</option>
                                                        <option value="8">Nova Scotia</option>
                                                        <option value="11">Nunavut</option>
                                                        <option value="6">Ontario</option>
                                                        <option value="9">PEI</option>
                                                        <option value="7">Quebec</option>
                                                        <option value="4">Saskatchewan</option>
                                                        <option value="13">Yukon</option>
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
                                    </div>
                                </div>
                                <div class="row" id="payment_bank_info_canada" style="display: none;">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label id="e_transfer_email_caption" for="e_transfer_email" class="col-lg-3 col-sm-3 text-left">E-transfer e-mail</label>
                                            <div class="col-sm-7">
                                                <input type="email" class="form-control" id="e_transfer_email" name="e_transfer_email" value="" placeholder="E-transfer e-mail" maxlength="100"> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label id="name_for_checks_caption" for="name_for_checks" class="col-lg-3 col-sm-3 text-left">Name for Checks</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" id="name_for_checks" name="name_for_checks" value="" placeholder="Name for Checks" maxlength="100"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Payment Bank info for Seller -->
                            <!-- Transaction Detail info -->
                            <div class="section_header_class">
								<label class="invoice_subtitle">{{__('Invoice Detail Information') }}:</label>
							</div>
                            <div id="details_transaction" open="">
                                <!-- <summary><h4></h4></summary> -->
                                <table id="details_tbl" width="100%" border="1" cellpadding="0" cellspacing="0" style="background:lightblue;">
                                    <thead>
                                        <tr>
                                            <th style="display: none;" width="0%">#</th>
                                            <th width="12%">
                                                <label id="row_hdr_date" name="row_hdr_date" class="gilroy-semibold light-blue-txt">Date</label>
                                            </th>
                                            <th width="50%">
                                                <label id="item_particular_caption" name="item_particular_caption" class="gilroy-semibold light-blue-txt">Details</label>
                                            </th>
                                            <th width="10%" style="text-align:right">
                                                <label id="row_hdr_amount" name="row_hdr_amount" class="gilroy-semibold light-blue-txt">Amount</label>
                                                <div class="selectdiv">
                                                    <select class="form-control" id="price_currency" name="price_currency">
                                                        <option value="CHF">CHF</option>
                                                        <option value="DEM">DEM</option>
                                                        <option value="EUR">EUR</option>
                                                        <option value="GBP">GBP</option>
                                                        <option value="USD">USD</option>
                                                        <option value="AUD">AUD</option>
                                                        <option value="CAD">CAD</option>
                                                        <option value="SGD">SGD</option>
                                                        <option value="JPY">JPY</option>
                                                        <option value="CNY">CNY</option>
                                                        <option value="TRY">TRY</option>
                                                        <option value="RUB">RUB</option>
                                                        <option value="DKK">DKK</option>
                                                        <option value="RON">RON</option>
                                                        <option value="CZK">CZK</option>
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
                                                    <input id="date" name="date" type="text" class="form-control datetimepicker" value="">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" id="caption" name="caption" placeholder="" class="form-control">
                                            </td>
                                            <td class="row_item_value">
                                                <input type="number" pattern="[0-9.]" id="total_item1" name="total_item1" placeholder="" style="text-align: right;" class="form-control numeric float item_value">
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
                                            <th colspan="5" style="text-align:right">
                                                <button type="button" class="btn btn-theme-success add-row"><em class="glyphicon glyphicon-plus"></em>Add</button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- Transaction Detail info END -->
                            <div class="section_header_class">
								<label class="invoice_subtitle">{{__('Add Taxes') }}:</label>
							</div>
                            <div id="add_tax_div" open="">
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
                                </div>
                                <div id="add_more_tax_div"></div>
                                <div class="row col-md-12">
                                    <button id="add_more_tax_btn" type="button" class="btn btn-theme-success"><em class="glyphicon glyphicon-plus"></em>Add Another Tax</button>
                                </div>
                            </div>
                            <div id="add_expense_div" open="">
                                <div class="section_header_class">
                                    <label class="invoice_subtitle">{{__('Add Expenses') }}:</label>
                                </div>
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
                                <div id="add_more_expense_div"></div>
                                <div class="row col-md-12">
                                    <button id="add_more_expense_btn" type="button" class="btn btn-theme-success"><em class="glyphicon glyphicon-plus"></em>Add Another Expense</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('footer_js')
<script type="text/javascript">

$('#seller_country_id').change(function(){
	var country = $(this).val();

	if(country == 'CA'){
		$('#seller_province_id_div').show();
	}else{
		$('#seller_province_id_div').hide();
	}
})

$('#payment_bank_country_id').change(function(){
	var country = $(this).val();

	if(country == 'CA'){
		$('#bank_province_id_div').show();
	}else{
		$('#bank_province_id_div').hide();
	}
})

$('#client_country_id').change(function(){
	var country = $(this).val();

	if(country == 'CA'){
		$('#client_province_id_div').show();
	}else{
		$('#client_province_id_div').hide();
	}
})

$(document).on('click','#add_more_tax_btn',function(){

    var resultHtml = `<div class="add_more_tax_row row">
        <hr>
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
                        <button type="button" class="btn btn-theme-warn delete_tax"><i class="fa fa-trash-o"></i></button>
                    </div>
                </div>
            </div>
        </div>`;

        $("#add_more_tax_div").append(resultHtml);

})

$(document).on('click','.delete_tax',function(){
    $(this).parents('.add_more_tax_row').remove();
})

$(document).on('click','#add_more_expense_btn',function(){

var resultHtml = `<div class="add_more_expense_row row">
        <hr>
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
                        <button type="button" class="btn btn-theme-warn delete_expense"><i class="fa fa-trash-o"></i></button>
                    </div>
            </div>
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
    markup+='<td><div class="input-group datetimepicker" id="date_div"> <input name="date" type="text" class="form-control date_picker" value=""/><span class="input-group-addon"><i class="fa fa-calendar"></i></span></div></td>';
    markup+='<td><input type="text" id="caption" name="caption" placeholder="" class="form-control"></td>';                   
    markup+='<td class="row_item_value"><input type="number" pattern="[0-9.]" id="total_item'+i+'" name="total_item'+i+'" placeholder="" style="text-align: right;" pattern="^[0-9]\d{0,9}(\.\d{1,3})?%?$" class="form-control numeric float item_value"></td>';
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

function remove_rows(r){
    var i = document.getElementById("details_tbl").rows.length; //get total rows including header footer
    //alert(i);
    if (i>4){
        i = r.parentNode.parentNode.rowIndex;
        document.getElementById("details_tbl").deleteRow(i);        
        //$(this).parent("tr").remove();
    };
};

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

 });

</script>
@endsection