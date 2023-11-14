<div class="row justify-content-center pt-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">Import / Export students</div>
            <div class="card-body text-center">

                <a href="#" data-bs-toggle="modal" data-bs-target="#importModal" id="csv_btn_import" class="btn btn-primary btn-lg" id="importStudents"><i class="fa-solid fa-upload"></i> Import Students</a>
                <a id="csv_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.export',['school'=> $schoolId]) : route('student.export') }}" target="_blank" class="btn btn-success btn-lg" id="exportStudents"><i class="fa-solid fa-download"></i> Export Students</a>

            </div>
        </div>
    </div>
</div>
