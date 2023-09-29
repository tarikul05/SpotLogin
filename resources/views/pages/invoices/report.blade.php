@extends('layouts.main')

@section('head_links')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="{{ asset('js/lib/moment.min.js')}}"></script>
<style type="text/css">
	input[readonly="readonly"] {
	  border: none;
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
                                <option value="create">Without details</option>
                                <option value="details">With details</option>
                            </select>
                        </div>
                        <div class="col-md-2 md-12 p-2 text-center">
                            <a class="btn btn-primary w-100" id="billing_period_search_btn"><i class="fa-solid fa-file-lines"></i> {{ __('Create') }}</a>
                            <a class="btn btn-primary w-100" id="billing_period_search_btn_2" style="display: none;"><i class="fa-solid fa-file-lines"></i> {{ __('Create') }}</a>
                        </div>
                        <div class="col-md-2 md-12 p-2 text-center" id="download_btn_report">
                            <a class="btn btn-success w-100" id="billing_period_download_btn"><i class="fa-solid fa-file-pdf"></i> {{ __('Download') }}</a>
                        </div>

                    </div>
                </form>
			</div>

            <div id="info_invoice_report" class="md-12 mt-4 mb-2 bg-tertiary"></div>
            <div id="table_container"></div>

		</div>
	</div>
</div>
@endsection

@section('footer_js')
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
            if (grandTotal > 0) {
                infoInvoiceReport.style.display = 'block';
                infoInvoiceReport.textContent = 'Total: ' + result.length + ' invoices found for ' + grandTotal.toFixed(2) + ' €';
                download_btn_report.style.display = 'block';
            } else {
                infoInvoiceReport.style.display = 'none';
                download_btn_report.style.display = 'none';
            }



            }

        })


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




    });
    </script>
@endsection
