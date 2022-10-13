<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
        <title>invoice</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style type='text/css'>
            body{
                font-family: 'Gilroy';
            }
            .table-bordered thead tr th, .table-bordered  tbody tr td{
                border: 1px solid #B3D6EC;
                padding: 8px 10px;
                font-size: 14px;
            }
            .table-bordered thead tr th{
                font-weight: bold;
            }
            .table-bordered .col_date{
                width: 100px;
            }
            .table-bordered .col_details{
                width: 350px;
            }
            .table-bordered .col_duration{
                width: 60px;
            }
            .table-bordered .col_amount{
                width: 50px;
            }
            .table-bordered .total{
                padding-top: 8px;
                padding-right: 10px;
                text-align: right;
                font-family: 'Gilroy-Bold';
                font-weight: bold;
                color: #0075BF;
                font-size: 14px;
            }
            .table-bordered .extra_col td{
                padding-top: 8px;
                padding-right: 10px;
            }
            .table-bordered .extra_col .text{
                text-align: center;
                font-size: 14px;
            }
            .table-bordered .extra_col .price{
                text-align: right;
                font-size: 14px;
            }
            .table-bordered .total_col td{
                padding-top: 10px;
                padding-right: 10px;
            }
            .table-bordered .total_col .text{
                text-align: center;
                font-family: 'Gilroy-Bold';
                color: #0075BF;
                font-size: 15px;
                font-weight: bold;
            }
            .table-bordered .total_col .price{
                text-align: right;
                font-family: 'Gilroy-Bold';
                color: #0075BF;
                font-size: 15px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="logo_area">
            <div class="left_part">
                Logo Area
            </div>
            <div class="right_part">
                
            </div>
        </div>
        <div class="invoice_table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col_date">Date</th>
                        <th class="col_details">Details</th>
                        <th class="col_duration">Duration</th>
                        <th class="col_amount">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>20.10.2022 00:00</td>
                        <td>3e</td>
                        <td>20</td>
                        <td>30.00</td>
                    </tr>
                    <tr>
                        <td>27.10.2022 00:00</td>
                        <td>asDXCZ</td>
                        <td>20</td>
                        <td>50.00</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="total">80.00</td>
                    </tr>
                    <tr class="extra_col">
                        <td colspan="2" class="text">Extra charges </td>
                        <td colspan="2" class="price">+ 45.00</td>
                    </tr>
                    <tr class="extra_col">
                        <td colspan="2" class="text"></td>
                        <td colspan="2" class="price">+ 45.00</td>
                    </tr>
                    <tr class="total_col">
                        <td colspan="2" class="text">Total (CHF) </td>
                        <td colspan="2" class="price">165.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </body>
</html>