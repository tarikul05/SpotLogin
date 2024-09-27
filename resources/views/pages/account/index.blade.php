@extends('layouts.main')

@section('head_links')

<!-- Cropper CSS -->
<link href="https://fengyuanchen.github.io/cropperjs/css/cropper.css" rel="stylesheet">
<!-- Cropper JS -->
<script src="https://fengyuanchen.github.io/cropperjs/js/cropper.js"></script>

<style>
    #paymentMethod_table {
        width: 100%;
    }
    #paymentMethod_table td {
        border:none!important;
        border-bottom:1px solid #EEE!important;
        font-size:15px;
        margin-bottom:15px!important;
        padding-top:7px!important;
        padding-bottom:7px!important;
    }
    #paymentMethod_table td img {
        height:30px!important;
        width:30px!important;
    }
    #paymentMethod_table tr:hover {
        border:1px solid #EEE!important;
        background-color:#fcfcfc!important;
    }
    #paymentMethod_table th {
        border:none!important;
        border-bottom:3px solid #EEE!important;
        font-size:13px;
        font-weight:bold;
    }

    /* Overlay flou */
    .overlay {
      display: none; /* Masquer par défaut */
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5); /* Couleur de fond semi-transparente */
      backdrop-filter: blur(8px); /* Effet de flou */
      z-index: 999; /* Assurez-vous qu'il est au-dessus de tout autre contenu */
    }
  
    /* Conteneur du faux modal */
    .cropContainer {
      display: none; /* Masquer par défaut */
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 100%;
      max-width: 500px;
      height: auto;
      background: white; /* Couleur de fond pour le conteneur */
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3); /* Ombre autour du conteneur */
      box-sizing: border-box;
      z-index: 1000; /* Assurez-vous qu'il est au-dessus de l'overlay */
      overflow: hidden; /* Empêcher le débordement */
    }
  
    /* Image dans le conteneur */
    #image {
      width: 90%;
      height: auto;
      max-height: 400px; /* Ajustez selon vos besoins */
      margin:0 auto;
    }

    .cropper-view-box,
    .cropper-face {
      border-radius: 50%;
    }

  
    /* Bouton de fermeture */

    .close-btn {
      position: absolute;
      bottom: 10px;
      left: 10px;
      background: #8e8f90;
      color: #302e2e;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      width: 100px;
    }
    .close-btn:hover {
      background: #a33133;
    }
  
    /* Bouton de recadrage */
    .crop-btn {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
    }
  
    .crop-btn:hover {
      background: #0056b3;
    }
</style>


@endsection

@section('content')
    <div class="container">

        <div class="row justify-content-center pt-3">
            <div class="col-md-10">

        <div class="page_header_class pt-1" style="position: static;">
            <h5 class="titlePage">{{ __('Coach Account') }}</h5>
        </div>
        

        @include('pages.account.navbar')

        <div class="tab-content" id="ex1-content">

            <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
                @include('pages.account.informations')
            </div>

            <div class="tab-pane fade" id="tab_5" role="tabpanel" aria-labelledby="tab_5">
                @include('pages.account.payment-methods')
            </div>

            <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
                @include('pages.account.plan')
            </div>

            <div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
                @include('pages.account.invoices')
            </div>

            <div class="tab-pane fade" id="tab_4" role="tabpanel" aria-labelledby="tab_4">
                @include('pages.account.info-plus')
            </div>

        </div>
    </div>

        </div>
    </div>
@endsection
