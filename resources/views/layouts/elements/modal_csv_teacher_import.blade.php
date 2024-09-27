<!-- Modal -->
<div class="modal fade login-signup-modal" id="importModal" tabindex="-1" aria-hidden="true"
  aria-labelledby="importModalLabel" style="display:none;">
  <form id="csv_import" name="csv_import" method="POST" action="{{ auth()->user()->isSuperAdmin() ? route('admin.teacher.import',['school'=> $schoolId]) : route('teacher.import') }}" enctype="multipart/form-data">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header text-white" style="background-color: #152245; heigth:85px!important; padding:8px!important;">
        <h6 class="modal-title page_header_class">
          <i class="fa-solid fa-upload"></i>  {{ __('Import teachers') }}
        </h6>
        <button type="button" class="close" id="modalClose" class="text text-light" data-bs-dismiss="modal" style="background:transparent!important; border:none!important;">
            <i class="fa-solid fa-circle-xmark fa-2x text-white"></i>
        </button>
    </div>
      <div class="modal-body" style="max-width: 375px; margin: 0 auto; padding-top: 25px;">
            <input type="file" class="form-control form-control-sm" name="csvFile" id="csvFile" accept=".xlsx" />
       
      </div>
      <div class="modal-footer pt-2" style="background-color: #fdfdfd; heigth:85px!important; padding:8px!important;">
        <button type="submit" class="btn btn-lg btn-outline-primary btn-block">{{ __('CONFIRM') }}</button>
      </div>
    </div>
  </div>
</form>
</div>
