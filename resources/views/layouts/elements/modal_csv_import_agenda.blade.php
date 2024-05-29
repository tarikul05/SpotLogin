<!-- Modal -->
<div class="modal fade login-signup-modal" id="importModal" tabindex="-1" aria-hidden="true"
  aria-labelledby="importModalLabel" style="display:none;">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header d-block text-center border-0">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h3 class="modal-title light-blue-txt gilroy-bold" id="importModalLabel"><i class="fa-solid fa-file-excel"></i> {{ __('Import Excel Agenda') }}</h3>

      </div>
      <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
        <form id="csv_import" name="csv_import" method="POST" action="{{ route('agenda.import') }}" enctype="multipart/form-data">
          @csrf
            <input type="file" class="form-control form-control-sm" name="csvFile" id="csvFile" accept=".xlsx,.xls"/>
          <div class="text-center mt-3">
            <button type="submit" class="btn btn-lg btn-primary btn-block" id="btnImportAgenda">{{ __('Import Excel') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>