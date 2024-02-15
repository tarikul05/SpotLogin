<div class="row justify-content-center pt-1">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">{{ __('Import / Export') }}</div>
            <div class="text-center p-5">
                <div class="row">
                    <div class="col-md-5 card bg-tertiary p-3">
                        <i class="fa-solid fa-upload fa-2x text-primary"></i><br>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#importModal" id="csv_btn_import" class="btn btn-primary" id="importStudents">Import Teachers</a>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-5 card bg-tertiary p-3">
                        <i class="fa-solid fa-download fa-2x text-success"></i><br>
                        <a id="csv_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.teacher.export',['school'=> $schoolId]) : route('teacher.export') }}" target="_blank" class="btn btn-success btn-lg" id="exportStudents">Export Teachers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
