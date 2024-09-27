<nav class="subNav">
    <div class="nav nav-tabs position-relative" id="nav-tab" role="tablist">

        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            <i class="fa-solid fa-list fa-1x" style="color:#0075bf!important;"></i>  {{ __('List all') }}
        </button>

        @if($students && $families->count() > 0) @endif
        <button class="nav-link" id="nav-family-list-tab" data-bs-toggle="tab" data-bs-target="#family-list" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            <i class="fa-solid fa-users fa-1x" style="color:#0075bf!important;"></i>   {{ __('list_families') }}
        </button>

    </div>
</nav>

<div class="tab-content" id="ex1-content">

    <div class="tab-pane fade show active" id="tab_1" role="tabpanel" aria-labelledby="tab_1">
        @include('pages.students.listing2')
    </div>

    <div class="tab-pane fade" id="tab_2" role="tabpanel" aria-labelledby="tab_2">
        @include('pages.students.import_export')
    </div>

    <div class="tab-pane fade" id="tab_3" role="tabpanel" aria-labelledby="tab_3">
        @include('pages.students.add_new')
    </div>

    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family">
        @include('pages.students.create_family')
    </div>

    <div class="tab-pane fade" id="family-list" role="tabpanel" aria-labelledby="family">
        @include('pages.students.families')
    </div>

</div>
