<!-- Modal -->
<div class="modal fade login-signup-modal" id="importModal" tabindex="-1" aria-hidden="true"
  aria-labelledby="importModalLabel" style="display:none;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-block text-center border-0">
        <h3 class="modal-title light-blue-txt gilroy-bold" id="importModalLabel">{{ __('Import CSV') }}</h3>
      </div>
      <div class="modal-body" style="max-width: 375px; margin: 0 auto;padding-top: 0;">
        <form id="csv_import" name="csv_import" method="POST" action="{{ auth()->user()->isSuperAdmin() ? route('admin.teacher.import',['school'=> $schoolId]) : route('teacher.import') }}" enctype="multipart/form-data">
          <div class="form-group">
            <input type="file" name="csvFile" id="csvFile" accept=".csv"/>
          </div>
          <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('Import CSV') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>
