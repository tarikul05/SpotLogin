<nav class="subNav">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">

        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            <i class="fa-solid fa-list fa-1x" style="color:#0075bf!important;"></i> {{ __('List all') }}
        </button>

        <button class="nav-link" data-bs-toggle="modal" data-bs-target="#importModal" id="csv_btn_import" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            <i class="fa-solid fa-upload fa-1x" style="color:#0075bf!important;"></i> {{ __('Import') }}
        </button>

        <button class="nav-link" id="nav-import_export-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
             <i class="fa-solid fa-download fa-1x" style="color:#0075bf!important;"></i> {{ __('Export') }}
        </button>

    </div>
</nav>
