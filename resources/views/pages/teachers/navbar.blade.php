<nav class="subNav">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">

        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            {{ __('List all') }}
        </button>

        <button class="nav-link" id="nav-import_export-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            {{ __('Export/Import') }}
        </button>

        <a class="nav-link" style="background-color:#EEE; padding-left:10px;" href="{{ auth()->user()->isSuperAdmin() ? route('admin.teachers.create',['school'=> $schoolId]) : route('teachers.create') }}">
            {{ __('Add new teacher') }} ({{ $teachers->where('pivot.role_type', '!=', 'school_admin')->count() }}/{{ $number_of_coaches > 0 ? $number_of_coaches : 1 }})
        </a>

    </div>
</nav>
