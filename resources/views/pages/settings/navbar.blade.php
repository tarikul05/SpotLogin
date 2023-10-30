<nav class="subNav">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            {{ __('Categories') }}
            @if(!empty($eventCat) && $eventCat->count() > 0)
            @else
                <span class="text-warning"><i class="fa-solid fa-circle-info"></i></span>
            @endif
        </button>
        @if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
         <button class="nav-link" id="nav-prices-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
            {{ __('Prices by category')}}
            @if(!empty($eventCat) && $eventCat->count() > 0)
            @else
                @if(!empty($eventCategory) && $eventCategory->count() > 0)
                @else
                <span class="text-warning"><i class="fa-solid fa-circle-info"></i></span>
                @endif
            @endif
        </button>
        @endif
        <button class="nav-link" id="nav-locations-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            {{ __('Locations') }}
        </button>
        <button class="nav-link" id="nav-levels-tab" data-bs-toggle="tab" data-bs-target="#tab_4" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            {{ __('Levels') }}
        </button>
        @if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
         <button class="nav-link" id="nav-taxes-tab" data-bs-toggle="tab" data-bs-target="#tab_taxes" type="button" role="tab" aria-controls="nav-logo" aria-selected="false">
            {{ __('Taxes')}}
        </button>
        @endif
        @can('parameters-list')
        @if($AppUI->isTeacherAdmin() || $AppUI->isTeacherSchoolAdmin() || $AppUI->isSchoolAdmin())
            <button class="nav-link" id="nav-parameters-tab" data-bs-toggle="tab" data-bs-target="#tab_5" type="button" role="tab" aria-controls="nav-parameters" aria-selected="false">
            {{ __('Schedule Settings')}}
            </button>
            @endif
        @endcan
    </div>
</nav>
