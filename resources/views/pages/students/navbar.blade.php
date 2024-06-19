<nav class="subNav">
    <div class="nav nav-tabs position-relative" id="nav-tab" role="tablist">

        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tab_1" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                {{ __('List all') }}
        </button>

        <!--<button class="nav-link" id="nav-import_export-tab" data-bs-toggle="tab" data-bs-target="#tab_2" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                {{ __('Export/Import') }}
        </button>-->

        <button class="nav-link" style="background-color:#f8f5f5; padding-left:10px; margin-bottom:1px;" id="nav-add-tab" data-bs-toggle="tab" data-bs-target="#tab_3" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                + {{ __('Add new student') }}
        </button>

        @if($students && $families->count() > 0)
            <button class="nav-link" id="nav-family-list-tab" data-bs-toggle="tab" data-bs-target="#family-list" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                {{ __('list_families') }}
            </button>
        @endif

        @if($students)
            <button class="nav-link" style="right:0; background-color:#f8f5f5; padding-left:10px; margin-bottom:1px;" id="nav-family-tab" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
               + {{ __('Create a family') }}
            </button>
        @endif

    </div>
</nav>
