@extends('layouts.main')

@section('head_links')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
<style type="text/css">
	input[readonly="readonly"] {
	  border: none;
	}
    table td {
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="content">
	<div class="container-fluid body">
		<header class="panel-heading" style="border: none;">
			<div class="row panel-row" style="margin:0;">
				<div class="col-sm-6 col-xs-12 header-area" style="padding-bottom:25px;">
					<div class="page_header_class">
						<label id="page_header" name="page_header"><i class="fa-solid fa-user"></i> {{ __('Report for:') }} {{!empty($user->username) ? $user->username : ''}}</label>
					</div>
				</div>
				<div class="col-sm-6 col-xs-12 btn-area">
					<div class="float-end btn-group">
						<a style="display: none;" id="delete_btn" href="#" class="btn btn-theme-warn"><em class="glyphicon glyphicon-trash"></em> {{ __('Delete')}}</a>
					</div>
				</div>
			</div>
		</header>

		<!-- Formulaires -->
		<div class="row">
			<div class="col-md-12">
				<form enctype="multipart/form-data" role="form" id="form_invoicing" class="form-horizontal" method="post" action="#">
                    <div class="form-group row">
                        <div class="col-md-4 p-2 md-12">
                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down float-end"></i>
                            </div>
                        </div>
                        <div class="col-md-2 text-center md-12 p-2 text-center">
                            <select class="form-control" id="report_type" aria-label="report_type">
                                <option value="details" selected>Complete report</option>
                                <option value="create" disabled>Custom report (soon)</option>
                            </select>
                        </div>
                        <div class="col-md-2 md-12 p-2 text-center">
                            <a class="btn btn-primary w-100" id="billing_period_search_btn_2"><i class="fa-solid fa-file-lines"></i> {{ __('Generate') }}</a>
                            <a class="btn btn-primary w-100" id="billing_period_search_btn_2" style="display: none;"><i class="fa-solid fa-file-lines"></i> {{ __('Generate') }}</a>
                        </div>
                        <div class="col-md-2 md-12 p-2 text-center" id="download_btn_report">
                            <a href="{{ route('generateReportPDF') }}" class="btn btn-success w-100" id="billing_period_download_btn"><i class="fa-solid fa-file-pdf"></i> {{ __('Download') }}</a>
                        </div>

                    </div>
                </form>
			</div>

            <div id="info_invoice_report" class="md-12 mt-4 mb-2 card bg-tertiary p-2 text-center mb-3"></div>

            <br>
            <div class="container">
            <div class="row pb-4">
                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12" id="table_container"></div>
                <div id="editor"></div>
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 text-right" id="resumeReport">
                    <div class="card p-2 text-right"><b>Total Taxes</b><div class="text-lowercase" id="totalTaxes"></div></div>
                    <div class="card p-2 text-right"><b>Total Extras</b><div class="text-lowercase" id="totalExtra"></div></div>
                    <div class="card p-2 text-right"><b>Total Discount</b><div class="text-lowercase" id="totalDiscount"></div></div>
                    <div class="card p-2 text-right"><b>Total Amount H.T</b><div class="text-lowercase" id="totalAmountLessonHT"></div></div>
                    <div class="card p-2 text-right"><b>Total Amount</b><div class="text-lowercase" id="totalAmount"></div></div>
                    <br>
                    <a class="btn btn-success w-100" id="billing_period_download_btn2"><i class="fa-solid fa-file-pdf"></i> {{ __('Download') }}</a>
                </div>
            </div>
            </div>
		</div>
	</div>
</div>
@endsection

@section('footer_js')
<script>
      var doc = new jsPDF();
     $('#billing_period_download_btn').on('click', function() {

var specialElementHandlers = {
    '#table_container': function (element, renderer) {
        return true;
    }
};

$('#cmd').click(function () {
doc.fromHTML($('#table_container').html(), 15, 15, {
        'width': 170,
            'elementHandlers': specialElementHandlers
    });
    doc.save('sample-file.pdf');
});
     });
</script>
<script>
    // Gérer le changement de sélection dans la liste déroulante
    document.getElementById('report_type').addEventListener('change', function () {
        var selectedValue = this.value;
        if (selectedValue === 'details') {
            // Si l'utilisateur choisit "Details", afficher le bouton billing_period_search_btn_2
            document.getElementById('billing_period_search_btn_2').style.display = 'inline-block';
            document.getElementById('billing_period_search_btn').style.display = 'none';
        } else {
            // Sinon, afficher le bouton billing_period_search_btn
            document.getElementById('billing_period_search_btn_2').style.display = 'none';
            document.getElementById('billing_period_search_btn').style.display = 'inline-block';
        }
    });
</script>
<script type="text/javascript">
    $(function() {

        var start = moment().subtract(29, 'days');
        var end = moment();

        function isMobileDevice() {
            return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
        }

        function cb(start, end) {
            if (isMobileDevice()) {
                $('#reportrange span').html(start.format('D MMM. YYYY') + ' - ' + end.format('D MMM. YYYY'));
            } else {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment().startOf('day'), moment().endOf('day')],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);

        cb(start, end);






        $('#billing_period_search_btn').on('click', function() {
            var selectedStartDate = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
            var selectedEndDate = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');

            // Vous pouvez maintenant utiliser selectedStartDate et selectedEndDate pour vos opérations de recherche.
            console.log('Start Date:', selectedStartDate);
            console.log('End Date:', selectedEndDate);

            // Si vous souhaitez envoyer les dates au serveur, vous pouvez les ajouter à un champ de formulaire caché et soumettre le formulaire.
            $('#billing_period_start_date').val(selectedStartDate);
            $('#billing_period_end_date').val(selectedEndDate);
            //$('#form_invoicing').submit(); // Soumettre le formulaire


        data = 'billing_period_start_date=' + selectedStartDate + '&billing_period_end_date=' + selectedEndDate;

		$.ajax({
			url: BASE_URL + '/admin/report',
			data: data,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(result) {



            // Créez un objet pour regrouper les invoices par date
            var invoicesByDate = {};

            // Parcourez les invoices et regroupez-les par date
            result.forEach(function(rowData) {
                var date = rowData.date_invoice.split(' ')[0]; // Récupérez la date (jour + mois + année)
                if (!invoicesByDate[date]) {
                    invoicesByDate[date] = [];
                }
                invoicesByDate[date].push(rowData);
            });

            // Sélectionnez le tableau HTML
            var table = document.createElement('table');
            table.className = 'table table-bordered';

            // Créez l'en-tête du tableau
            var thead = document.createElement('thead');
            var headerRow = thead.insertRow();
            var headers = ['Date', 'Total Amount'];
            headers.forEach(function(headerText, index) {
                var th = document.createElement('th');
                th.textContent = headerText;

                // Ajoutez une classe spéciale au dernier th
                if (index === headers.length - 1) {
                    th.classList.add('report_action');
                }

                headerRow.appendChild(th);
            });
            table.appendChild(thead);

            // Créez le corps du tableau
            var tbody = document.createElement('tbody');
            var grandTotal = 0; // Initialisez le grand total

            // Parcourez les invoices groupées par date
            for (var date in invoicesByDate) {
                var dateInvoices = invoicesByDate[date];
                var totalAmount = 0; // Initialisez le total pour cette date

                // Calculez le total pour cette date
                dateInvoices.forEach(function(invoice) {
                    totalAmount += parseFloat(invoice.total_amount);
                });

                // Ajoutez une ligne pour cette date
                var row = tbody.insertRow();
                var cellDate = row.insertCell(0);
                var cellTotalAmount = row.insertCell(1);

                cellDate.textContent = date;
                cellTotalAmount.textContent = totalAmount.toFixed(2); // Formatez le total à deux décimales

                grandTotal += totalAmount; // Mettez à jour le grand total
            }

            // Ajoutez une ligne pour le grand total
            var totalRow = tbody.insertRow();
            var cellTotalLabel = totalRow.insertCell(0);
            var cellGrandTotal = totalRow.insertCell(1);

            cellTotalLabel.textContent = 'Grand Total';
            cellTotalLabel.style.textAlign = 'right';
            cellGrandTotal.textContent = grandTotal.toFixed(2);

            // Ajoutez la classe bg-light au tr
            totalRow.classList.add('bg-light');
            totalRow.classList.add('text-white');

            table.appendChild(tbody);

            // Sélectionnez l'élément où vous souhaitez afficher le tableau
            var tableContainer = document.getElementById('table_container');
            tableContainer.innerHTML = ''; // Assurez-vous que le conteneur est vide
            tableContainer.appendChild(table);


            var infoInvoiceReport = document.getElementById('info_invoice_report');
            var download_btn_report = document.getElementById('download_btn_report');
            var resumeReport = document.getElementById('resumeReport');
            if (grandTotal > 0) {
                infoInvoiceReport.style.display = 'block';
                infoInvoiceReport.textContent = 'Total: ' + result.length + ' invoices found for ' + grandTotal.toFixed(2) + ' €';
                download_btn_report.style.display = 'block';
                resumeReport.style.display = 'block';
            } else {
                infoInvoiceReport.style.display = 'none';
                download_btn_report.style.display = 'none';
                resumeReport.style.display = 'none';
            }



            }

        })


        });


















$('#billing_period_search_btn_22').on('click', function() {
    var selectedStartDate = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var selectedEndDate = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');

    // Vous pouvez maintenant utiliser selectedStartDate et selectedEndDate pour vos opérations de recherche.
    console.log('Start Date:', selectedStartDate);
    console.log('End Date:', selectedEndDate);

    // Si vous souhaitez envoyer les dates au serveur, vous pouvez les ajouter à un champ de formulaire caché et soumettre le formulaire.
    $('#billing_period_start_date').val(selectedStartDate);
    $('#billing_period_end_date').val(selectedEndDate);
    //$('#form_invoicing').submit(); // Soumettre le formulaire

    data = 'billing_period_start_date=' + selectedStartDate + '&billing_period_end_date=' + selectedEndDate;

    $.ajax({
        url: BASE_URL + '/admin/report',
        data: data,
        type: 'POST',
        dataType: 'json',
        async: false,
        success: function(result) {
            // Créez un objet pour regrouper les invoices par date
            var invoicesByDate = {};

            // Parcourez les invoices et regroupez-les par date
            result.forEach(function(rowData) {
                var date = rowData.date_invoice.split(' ')[0]; // Récupérez la date (jour + mois + année)
                if (!invoicesByDate[date]) {
                    invoicesByDate[date] = [];
                }
                invoicesByDate[date].push(rowData);
            });

            // Sélectionnez le tableau HTML
            var table = document.createElement('table');
            table.className = 'table table-bordered';

            // Créez l'en-tête du tableau
            var thead = document.createElement('thead');
            var headerRow = thead.insertRow();
            var headers = ['Date', 'Total Amount'];
            headers.forEach(function(headerText, index) {
                var th = document.createElement('th');
                th.textContent = headerText;

                // Ajoutez une classe spéciale au dernier th
                if (index === headers.length - 1) {
                    th.classList.add('report_action');
                }

                headerRow.appendChild(th);
            });
            table.appendChild(thead);

            // Créez le corps du tableau
            var tbody = document.createElement('tbody');
            var grandTotal = 0; // Initialisez le grand total

            // Parcourez les invoices groupées par date
            for (var date in invoicesByDate) {
                var dateInvoices = invoicesByDate[date];
                var totalAmount = 0; // Initialisez le total pour cette date

                // Ajoutez une ligne pour cette date
                var dateRow = tbody.insertRow();
                var cellDate = dateRow.insertCell(0);
                var cellTotalAmount = dateRow.insertCell(1);

                cellDate.textContent = date;

                // Créez une liste pour les détails des invoices
                var invoiceList = document.createElement('ul');

                // Parcourez les invoices pour cette date
                dateInvoices.forEach(function(invoice) {
                    totalAmount += parseFloat(invoice.total_amount);

                    // Ajoutez chaque invoice à la liste avec les détails
                    var invoiceItem = document.createElement('li');
                    //invoiceItem.textContent = 'Invoice #' + invoice.id + ' : ' + invoice.total_amount + ' €';
                    invoiceItem.textContent = invoice.client_name + ' : ' + invoice.total_amount + ' €';
                    invoiceItem.textContent += invoice.extra_expenses > 0 ? ' | Extra: ' + invoice.extra_expenses + ' €' : '';
                    invoiceItem.textContent += invoice.tax_amount > 0 ? ' | TAX: ' + invoice.tax_amount + ' €' : '';
                    invoiceList.appendChild(invoiceItem);
                });

                // Ajoutez la liste des invoices à la cellule des détails
                cellDate.appendChild(invoiceList);

                cellTotalAmount.textContent = totalAmount.toFixed(2); // Formatez le total à deux décimales

                grandTotal += totalAmount; // Mettez à jour le grand total
            }

            // Ajoutez une ligne pour le grand total
            var totalRow = tbody.insertRow();
            var cellTotalLabel = totalRow.insertCell(0);
            var cellGrandTotal = totalRow.insertCell(1);

            cellTotalLabel.textContent = 'Grand Total';
            cellTotalLabel.style.textAlign = 'right';
            cellGrandTotal.textContent = grandTotal.toFixed(2);

            // Ajoutez la classe bg-light au tr
            totalRow.classList.add('bg-light');
            totalRow.classList.add('text-white');

            table.appendChild(tbody);

            // Sélectionnez l'élément où vous souhaitez afficher le tableau
            var tableContainer = document.getElementById('table_container');
            tableContainer.innerHTML = ''; // Assurez-vous que le conteneur est vide
            tableContainer.appendChild(table);

            var infoInvoiceReport = document.getElementById('info_invoice_report');
            var download_btn_report = document.getElementById('download_btn_report');
            if (grandTotal > 0) {
                infoInvoiceReport.style.display = 'block';
                infoInvoiceReport.textContent = 'Total: ' + result.length + ' invoices found for ' + grandTotal.toFixed(2) + ' €';
                download_btn_report.style.display = 'block';
            } else {
                infoInvoiceReport.style.display = 'none';
                download_btn_report.style.display = 'none';
            }
        }
    });
});











$('#billing_period_search_btn_2').on('click', function() {
    var selectedStartDate = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var selectedEndDate = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');

    // Vous pouvez maintenant utiliser selectedStartDate et selectedEndDate pour vos opérations de recherche.
    console.log('Start Date:', selectedStartDate);
    console.log('End Date:', selectedEndDate);

    // Si vous souhaitez envoyer les dates au serveur, vous pouvez les ajouter à un champ de formulaire caché et soumettre le formulaire.
    $('#billing_period_start_date').val(selectedStartDate);
    $('#billing_period_end_date').val(selectedEndDate);
    //$('#form_invoicing').submit(); // Soumettre le formulaire

    data = 'billing_period_start_date=' + selectedStartDate + '&billing_period_end_date=' + selectedEndDate;

    $.ajax({
        url: BASE_URL + '/admin/report',
        data: data,
        type: 'POST',
        dataType: 'json',
        async: false,
        success: function(result) {
            console.log(result)
            // Créez un objet pour regrouper les invoices par date
            var invoicesByDate = {};

            // Parcourez les invoices et regroupez-les par date
            result.forEach(function(rowData) {
                var date = rowData.date_invoice.split(' ')[0]; // Récupérez la date (jour + mois + année)
                if (!invoicesByDate[date]) {
                    invoicesByDate[date] = [];
                }
                invoicesByDate[date].push(rowData);
            });

            // Sélectionnez le tableau HTML
            var table = document.createElement('table');
            table.className = 'table table-bordered';

            // Créez l'en-tête du tableau
            var thead = document.createElement('thead');
            var headerRow = thead.insertRow();
            var headers = ['Date', 'Taxes & Extras', 'Total Amount'];
            headers.forEach(function(headerText, index) {
                var th = document.createElement('th');
                th.textContent = headerText;

                // Ajoutez une classe spéciale au dernier th
                if (index === headers.length - 1) {
                    th.classList.add('report_action');
                }

                headerRow.appendChild(th);
            });
            table.appendChild(thead);

            // Créez le corps du tableau
            var tbody = document.createElement('tbody');

            var grandTotal = 0; // Initialisez le grand total
            var grandTotalHT = 0; // Initialisez le grand total HT
            var grandTotalExtra = 0; // Initialisez le grand total des extras
            var grandTotalDiscount = 0; // Initialisez le grand total des remises
            var grandTotalTaxes = 0; // Initialisez le grand total des taxes
            // Parcourez les invoices groupées par date
            for (var date in invoicesByDate) {
                var dateInvoices = invoicesByDate[date];
                var totalAmount = 0; // Initialisez le total pour cette date
                var totalAmountLessonHT = 0; // Initialisez le total HT pour cette date
                var totalTaxes = 0; // Initialisez le total des taxes pour cette date
                var totalExtra = 0; // Initialisez le total des extras pour cette date
                var totalDiscount = 0; // Initialisez le total des remises pour cette date
                // Ajoutez une ligne pour cette date
                var dateRow = tbody.insertRow();
                var cellDate = dateRow.insertCell(0);
                var cellTaxes = dateRow.insertCell(1);
                var cellTotalAmount = dateRow.insertCell(2);

                cellDate.innerHTML = 'Rapport for ' + date;
                cellDate.style.fontWeight = 'bold';
                dateRow.classList.add('bg-light');
                dateRow.classList.add('text-white');

                // Créez une liste pour les détails des invoices
                var invoiceList = document.createElement('td'); // Utilisez une cellule <td> au lieu d'une liste <ul>

                // Parcourez les invoices pour cette date
                dateInvoices.forEach(function(invoice) {
                    totalAmount += parseFloat(invoice.total_amount);
                    totalAmountLessonHT += parseFloat(invoice.subtotal_amount_with_discount);
                    totalTaxes += parseFloat(invoice.tax_amount);
                    totalDiscount += parseFloat(invoice.amount_discount_1+invoice.amount_discount_2);

                    // Créez une nouvelle ligne de tableau <tr> pour chaque invoice
                    var invoiceRow = tbody.insertRow();
                    var cellInvoiceName = invoiceRow.insertCell(0); // Cellule pour le nom du client
                    var cellInvoiceAmount = invoiceRow.insertCell(1); // Cellule pour le montant total de la facture
                        cellInvoiceAmount.classList.add('text-center');
                        cellInvoiceAmount.classList.add('bg-tertiary');
                    var cellInvoiceTaxes = invoiceRow.insertCell(2); // Cellule pour les taxes

                    // Remplissez les cellules avec les détails de l'invoice
                    cellInvoiceAmount.innerHTML = '<b>B.T:</b> ' + (invoice.total_amount-invoice.tax_amount).toLocaleString(2) + '€';
                    cellInvoiceAmount.innerHTML += '<br><b>Total</b>: <span class="text-primary">' + invoice.total_amount.toLocaleString(2) + '€</span>';
                    cellInvoiceTaxes.innerHTML = 'Taxes ==> ' + invoice.tax_amount.toLocaleString(2) + '€';


                    var textInvoiceDetails = '<b>Before Tax</b>';
                    cellInvoiceName.innerHTML = textInvoiceDetails /* + ' - ' + invoice.subtotal_amount_with_discount.toFixed(2) + '€'*/;
                    invoice.invoice_items.forEach(function(item) {
                    if (item.caption.startsWith("Event :")) {
                        if(item.eventDetail.costs_1 > 0) {
                            totalExtra = totalExtra + item.eventDetail.costs_1;
                            cellInvoiceName.innerHTML += '<br/>Event ==> ' + (item.price-item.eventDetail.costs_1).toLocaleString(2) + '€';
                            cellInvoiceTaxes.innerHTML += '<br>Extra ==> ' + item.eventDetail.costs_1.toLocaleString(2) + '€';
                        } else {
                            cellInvoiceName.innerHTML += '<br/>Event ==> ' + item.price.toLocaleString(2) + '€';
                        }
                        if(invoice.discount_percent_2 > 0) {
                            var eventDiscountAmount = ((item.price-item.eventDetail.costs_1) * invoice.discount_percent_2 / 100);
                            cellInvoiceTaxes.innerHTML += '<br/>Discount on Event ==> ' + (eventDiscountAmount).toFixed(2).toLocaleString() + '€' + ' (' + (invoice.discount_percent_2).toLocaleString(2) + '%)';
                        }
                    } else {
                            var lessonPrice = (item.price) - (item.price * invoice.discount_percent_1 / 100);
                            cellInvoiceName.innerHTML += '<br/>Lesson ==> ' + (lessonPrice).toLocaleString(2) + '€';
                            if(invoice.discount_percent_1 > 0) {
                                cellInvoiceTaxes.innerHTML += '<br/>Discount on Lesson ==> ' + (invoice.amount_discount_1).toFixed(2).toLocaleString() + '€' + ' (' + (invoice.discount_percent_1).toLocaleString(2) + '%)';
                            }
                    }
                    });


                    // Ajoutez les cellules à la ligne de tableau <tr>
                    invoiceRow.appendChild(cellInvoiceName);
                    invoiceRow.appendChild(cellInvoiceTaxes);
                    invoiceRow.appendChild(cellInvoiceAmount);

                });

                // Ajoutez une ligne pour cette date
                var dateRow = tbody.insertRow();
                var cellDate = dateRow.insertCell(0);
                var cellTaxes = dateRow.insertCell(1);
                var cellTotalAmount = dateRow.insertCell(2);

                cellTaxes.style.backgroundColor = '#EEEEEE';
                cellTotalAmount.style.backgroundColor = '#EEEEEE';
                cellTotalAmount.style.textAlign = 'center';
                cellTotalAmount.style.verticalAlign = 'bottom';
                cellDate.style.fontWeight = 'bold';
                //dateRow.classList.add('bg-light');
                //dateRow.classList.add('text-white');

                // Ajoutez la cellule des détails au tableau <table>
                cellDate.appendChild(invoiceList);
                cellTaxes.innerHTML = '<b>Total Taxes</b>: ' + totalTaxes.toLocaleString(2)+ '€';
                cellTaxes.innerHTML += totalExtra > 0 ? '<br><b>Total Extras</b>: ' + totalExtra.toLocaleString(2)+ '€' : '';
                cellTaxes.innerHTML += totalDiscount > 0 ? '<br><b>Total Discount</b>: ' + totalDiscount.toLocaleString(2)+ '€' : '';
                cellTotalAmount.innerHTML = 'Total B.T: <b>' + totalAmountLessonHT.toLocaleString(2)+ '€</b>'; // Formatez le total à deux décimales
                cellTotalAmount.innerHTML += '<br>Total Amount: <b>' + totalAmount.toLocaleString(2)+ '€</b>'; // Formatez le total à deux décimales

                grandTotal += totalAmount; // Mettez à jour le grand total
                grandTotalTaxes += totalTaxes; // Mettez à jour le grand total des taxes
                grandTotalHT += totalAmountLessonHT
                grandTotalExtra += totalExtra
                grandTotalDiscount += totalDiscount
            }

            // Ajoutez une ligne pour le grand total
            var totalRow = tbody.insertRow();
            var cellTotalLabel = totalRow.insertCell(0);
            var cellTotalTaxesLabel = totalRow.insertCell(1);
            var cellGrandTotal = totalRow.insertCell(2);

            cellTotalLabel.textContent = ''; //'=>'; //Grand Total
            cellTotalLabel.style.textAlign = 'right';
            cellTotalTaxesLabel.textContent = ''; //'Total Taxes: ' + grandTotalTaxes.toLocaleString(2) + '€';
            cellGrandTotal.innerHTML = '<b>Grand Total</b>: ' + grandTotal.toLocaleString(2) + ' €';
            cellGrandTotal.style.fontWeight = 'bold';
            cellGrandTotal.style.textAlign = 'center';

            // Ajoutez la classe bg-light au tr
            totalRow.classList.add('bg-light');
            totalRow.classList.add('text-white');

            table.appendChild(tbody);

            // Sélectionnez l'élément où vous souhaitez afficher le tableau
            var tableContainer = document.getElementById('table_container');
            tableContainer.innerHTML = ''; // Assurez-vous que le conteneur est vide
            tableContainer.appendChild(table);

            var infoInvoiceReport = document.getElementById('info_invoice_report');
            var download_btn_report = document.getElementById('download_btn_report');
            var resumeReport = document.getElementById('resumeReport');
            if (grandTotal > 0) {
                infoInvoiceReport.style.display = 'block';
                infoInvoiceReport.innerHTML = result.length + ' invoices <br>Period : ' + selectedStartDate + ' - ' + selectedEndDate + ' <br>Total: <span class="text-primary">' + grandTotal.toLocaleString() + '€</span>';
                infoInvoiceReport.classList.add('h5');
                download_btn_report.style.display = 'block';

                //Resume
                var divTotalAmount = document.getElementById('totalAmount');
                var divTotalAmountLessonHT = document.getElementById('totalAmountLessonHT');
                var divTotalTaxes = document.getElementById('totalTaxes');
                var divTotalDiscount = document.getElementById('totalDiscount');
                var divTotalExtra = document.getElementById('totalExtra');
                //Display Resume
                resumeReport.style.display = 'block';

                divTotalAmount.innerHTML = grandTotal.toLocaleString(2) + '€';
                divTotalAmountLessonHT.innerHTML = grandTotalHT.toLocaleString(2) + '€';
                divTotalTaxes.innerHTML = grandTotalTaxes.toLocaleString(2) + '€';
                divTotalDiscount.innerHTML = grandTotalDiscount.toLocaleString(2) + '€';
                divTotalExtra.innerHTML = grandTotalExtra.toLocaleString(2) + '€';

            } else {
                infoInvoiceReport.style.display = 'none';
                download_btn_report.style.display = 'none';
                resumeReport.style.display = 'none';
            }
        },
        error: function(result) { console.log(result) }
    });
});






    });
    </script>
@endsection
