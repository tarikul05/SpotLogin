<nav class="subNav">

    <div class="nav nav-tabs" id="nav-tab" role="tablist">

        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
        {{ __('Personal information') }}
        </button>

        @if($AppUI->isTeacherAdmin() || $AppUI->isTeacher() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
         <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
            {{ __('My plan')}}
        </button>
        <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
            {{ __('My invoices')}}
        </button>
        <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
        {{ __('My account')}}
        </button>
        @endif

    </div>

</nav>
