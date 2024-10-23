
<form method="POST" action="{{ route('students.delete') }}">
    @csrf
    
    <div class="row justify-content-center pt-1 mb-3">
    <div class="col-md-12">

        <div class="card2" style="border-radius:10px;">
            <div class="card-header titleCardPage d-flex justify-content-between align-items-center">
                <b class="d-none d-sm-inline">{{ __("Manage your students") }}</b>
               
                <input name="search_text" type="input" class="form-control search_text_box" id="search_text"  placeholder="Find a student">
            </div>
            <div class="card-body2 pt-2 pb-5">

                <input name="schoolId" type="hidden" value="{{$schoolId}}">

                <div class="table-responsive11">
                    <table id="example1" style="width:100%;">
                        <thead>
                        <tr>
                            <th style="width: 10px!important;" class="text-left">
                                <div style="tetx-align:left!important;">
                                  <input type="checkbox" id="select-all">
                                </div>
                              </th>
                              <th class="d-none d-lg-table-cell"></th>
                            <th class="titleFieldPage p-0 text-left">{{ __('Name') }}</th>
                            <th class="d-none d-lg-table-cell"></th>
                            <th class="d-none d-lg-table-cell titleFieldPage p-0 text-left">{{ __('Email') }}</th>
                            <th class="d-none d-lg-table-cell titleFieldPage p-0 text-left">{{ __('User Account') }}</th>
                            <th class="d-none d-lg-table-cell titleFieldPage p-0 text-left">{{ __('Status') }}</th>
                            <th width="40" class="text-center titleFieldPage">{{ __('Action') }}</th>

                        </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr class="add_more_level_row mobile_list_student" id="row_{{ $student->id }}">
                                <td style="width: 10px!important; text-align:center!important;" class="align-middle"><input type="checkbox" name="selected_students[]" value="{{ $student->id }}"></td>
                                    <td class="text-center d-none d-lg-table-cell" style="width:30px; text-align: center;">
                                        <a class="text-reset text-decoration-none" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}">
                                        <?php if (!empty($student->profileImageStudent->path_name)): ?>
                                        <img src="{{ $student->profileImageStudent->path_name }}" style="border-radius:50px; width:40px;" id="admin_logo"  alt="Sportlogin">
                                    <?php elseif (!empty($student->user->profileImage->path_name)): ?>
                                        <img src="{{ $student->user->profileImage->path_name }}" style="border-radius:50px; width:40px;" id="admin_logo"  alt="Sportlogin">
                                    <?php else: ?>
                                        <img src="{{ asset('img/photo_blank.jpg') }}" style="border-radius:50px; width:40px;" id="admin_logo" alt="Sportlogin">
                                    <?php endif; ?>
                                        </a>
                                    </td>
                                    <td style="position: relative;" class="align-middle p-0 text-left">
                                        <a class="text-reset text-decoration-none" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}">
                                            <span style="cursor:pointer; border-bottom:1px dashed #a1a0a0; font-size:15px;">{{ $student->full_name; }}</span>
                                        </a>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        @if(count($student->family) > 0)
                                        <a href="#" data-toggle="modal" data-target="#student_family_{{ $student->id }}" class="text-primary">
                                        <i class="fa fa-users" aria-hidden="true" title="{{ $student->firstname }} is a member of a family"></i>
                                        </a>
                                        @endif
                                    </td>
                                    <td class="d-none d-lg-table-cell p-0 text-left">{{ $student->email ? $student->email : '- ' }}</td>
                                    <td class="d-none d-lg-table-cell align-middle p-0 text-left">
                                        @if($student->user)
                                        <span disabled  class="badge bg-success">{{$student->username}}</span>
                                        @else
                                        No <span disabled class="badge bg-info send-invite-btn" data-email="{{ $student->email }}" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" style="cursor:pointer;"><i class="fa-solid fa-envelope"></i> {{ __('Send invite') }}</span>
                                        @endif

                                        @if($student->user)<span class="titleFieldPage">{{$student->user->username}}</span>@endif
                                        @if(!$student->user)
                                        <span style="font-size:12px;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ (!empty($student->is_sent_invite) ? __('Invited') : __('Not yet invited')) }}   {{ (!empty($student->invited_at) ? $student->invited_at : '') }}">
                                            <i class="fa fa-info-circle"></i>
                                        </span>
                                        @endif
                                    
                                    </td>

                                    <td class="d-none d-lg-table-cell p-0 text-left">
                                        @if (!empty($student->pivot->is_active))
                                            Active
                                        @else
                                            <span class="badge bg-warning switch-student-btn" data-status="{{ $student->pivot->is_active }}" data-school="{{ $student->pivot->school_id }}" data-student="{{ $student->id }}" style="cursor:pointer;">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="text-center align-middle" width="40">

                                       

                                        <div class="dropdown" style="position: relative!important;">
                                            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-h txt-grey"></i>
                                            </a>
                                            <div class="dropdown-menu list action text-left" style="z-index:9999!important; position: absolute!important;">

                                                    <a class="dropdown-item text-primary" href="{{ auth()->user()->isSuperAdmin() ? route('adminEditStudent',['school'=> $schoolId,'student'=> $student->id]) : route('editStudent',['student' => $student->id]) }}">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        {{ __('Edit')}}
                                                    </a>
                                                    <!--<a class="dropdown-item" href="{{ route('students.availabilities', $student) }}">
                                                        <i class="fa-solid fa-calendar"></i>
                                                        {{ __('Availabilities')}}
                                                    </a>-->

                                                    @if(!$student->user)

                                                    <a href="javascript:void(0)" class="dropdown-item send-invite-btn text-primary" data-email="{{ $student->email }}" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                                                        <i class="fa-solid fa-envelope"></i>
                                                        {{ __('Send invite') }}
                                                    </a>

                                                    @else

                                                    <a href="javascript:void(0)" class="dropdown-item send-password-btn text-primary" data-email="{{ $student->email }}" data-school="{{ $schoolId }}" data-student="{{ $student->id }}" title="{{ __("Send invitation") }}">
                                                        <i class="fa-solid fa-envelope"></i>
                                                        {{ __('Resend password') }}
                                                    </a>

                                                    @endif

                                                    <a href="javascript:void(0)" disabled data-status="{{ $student->pivot->is_active }}" data-school="{{ $student->pivot->school_id }}" data-student="{{ $student->id }}" class="switch-student-btn dropdown-item text-primary" href="#">
                                                        <i class="fa-solid fa-retweet"></i> {{ !empty($student->pivot->is_active) ? __('Switch to inactive')  : __('Switch to active') ; }}
                                                    </a>

                                                    <a href="javascript:void(0)" class="dropdown-item delete-student-btn text-danger" data-school="{{ $student->pivot->school_id }}" data-student="{{ $student->id }}">
                                                        <i class="fa fa-trash"></i>
                                                        {{ __('Delete') }}
                                                    </a>

                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    

    </div>

    <div class="row justify-content-center footer2" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.99!important; background-color:#fbfbfb!important; border:1px solid #fcfcfc;">
            
            <button class="btn btn-danger btn-md" style="display:none;" type="submit" id="delete-selected" onclick="return confirm('{{ __('Are you sure you want to delete the selected students?') }}')">{{ __('Delete selected students') }}</button>
            <button class="btn btn-outline-primary" id="nav-add-tab" type="button">
                + {{ __('Add new student') }}
            </button>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal" id="csv_btn_import" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                <i class="fa-solid fa-upload fa-1x"></i> {{ __('Import') }}
            </button>

            <a class="text-primary" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.export',['school'=> $schoolId]) : route('student.export') }}" target="_blank">
                <i class="fa-solid fa-download fa-1x"></i> {{ __('Export') }}
           </a>

        </div>
    </div>
   
</div>
</form>




@foreach($students as $student)

    <div class="modal fade confirm-modal" id="student_family_{{ $student->id }}" tabindex="-1" aria-hidden="true"
    aria-labelledby="student_family_{{ $student->id }}" name="student_family_{{ $student->id }}">


    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #152245; heigth:85px!important; padding:8px!important;">
            <h6 class="modal-title" style="font-size:17px; padding-top:5px;">
                <i class="fa fa-users"></i>  {{ __('Family members') }}
            </h6>
            <button type="button" data-dismiss="modal" aria-label="Close" id="modalClose" class="close" data-bs-dismiss="modal" style="margin-top:-8px; font-size:23px;">
                <i class="fa-solid fa-circle-xmark fa-lg text-white"></i>
            </button>
        </div>
        <div class="modal-body">
            {{ $student->firstname }} {{ $student->lastname }} is a member of at least one family : <br/>
            @php $item = 1; @endphp
            @foreach($student->family as $family)
            @if($family->student_id === $student->id)
                <div class="card-body">
                    <div for="{{ $family->id }}">
                    <b class="mt-2 text-primary"><i class="fa-solid fa-users"></i> Family</b> <b class="text-primary h6">{{ $family->name }}</b><br>
                     <ul>
                    @foreach($family->members as $member)
                     <li>{{ $member }}</li>
                    @endforeach
                     </ul>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

</div>
</div>
</div>
@endforeach





<!-- success modal-->
<div class="modal modal_parameter" id="sendMailOk">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:4px solid #97cc04;">
            <div class="modal-body text-center">
                <h1 class="text-success"><i class="fa-solid fa-check"></i></h1>
                <h3 class="text-success">{{__('Successfully sended') }}</h3>
                <p>{{__('Your student will receive an email with instructions.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="modalClose" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Ok') }}</button>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('search_text').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Empêche la soumission du formulaire
        this.blur();
    }
});
</script>


<script>
    // Fonction pour vérifier si au moins une case est cochée
    function checkIfAnyChecked() {
        var checkboxes = document.querySelectorAll('input[name="selected_students[]"]');
        var isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        
        // Afficher ou masquer le bloc des boutons
        var deleteButton = $('#delete-selected');
        if (isChecked) {
            deleteButton.fadeIn("fast"); // Afficher le bloc
        } else {
            deleteButton.fadeOut("fast"); // Masquer le bloc
        }
    }

    // Événement pour le bouton "Tout sélectionner"
    document.getElementById('select-all').addEventListener('change', function () {
        var checkboxes = document.querySelectorAll('input[name="selected_students[]"]');
        
        // Cochez ou décochez toutes les cases
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
        
        // Vérifier si au moins une case est cochée
        checkIfAnyChecked();
    });

    // Événements pour chaque case à cocher individuelle
    var checkboxes = document.querySelectorAll('input[name="selected_students[]"]');
    for (var checkbox of checkboxes) {
        checkbox.addEventListener('change', function () {
            checkIfAnyChecked(); // Vérifier si au moins une case est cochée
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Lorsque le bouton extérieur est cliqué
    document.getElementById('nav-add-tab').addEventListener('click', function() {
        // Sélectionner l'onglet (contenu) avec l'ID #tab_3
        const tabPane = document.querySelector('#tab_3');

        // Si l'onglet existe
        if (tabPane) {
            // Désactiver tous les autres onglets actifs
            document.querySelectorAll('.tab-pane').forEach(function(pane) {
                pane.classList.remove('show', 'active');
            });

            // Activer l'onglet cible
            tabPane.classList.add('show', 'active');

            // Désactiver les boutons actifs dans la nav
            document.querySelectorAll('.nav-link').forEach(function(nav) {
                nav.classList.remove('active');
            });
        }
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Lorsque le bouton extérieur est cliqué
    document.getElementById('nav-add-family-tab').addEventListener('click', function() {
        // Sélectionner l'onglet (contenu) avec l'ID #tab_3
        const tabPane = document.querySelector('#family');

        // Si l'onglet existe
        if (tabPane) {
            // Désactiver tous les autres onglets actifs
            document.querySelectorAll('.tab-pane').forEach(function(pane) {
                pane.classList.remove('show', 'active');
            });

            // Activer l'onglet cible
            tabPane.classList.add('show', 'active');

            // Désactiver les boutons actifs dans la nav
            document.querySelectorAll('.nav-link').forEach(function(nav) {
                nav.classList.remove('active');
            });
        }
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Lorsque le bouton extérieur est cliqué
    document.getElementById('nav-add-family-tab2').addEventListener('click', function() {
        // Sélectionner l'onglet (contenu) avec l'ID #tab_3
        const tabPane = document.querySelector('#family');

        // Si l'onglet existe
        if (tabPane) {
            // Désactiver tous les autres onglets actifs
            document.querySelectorAll('.tab-pane').forEach(function(pane) {
                pane.classList.remove('show', 'active');
            });

            // Activer l'onglet cible
            tabPane.classList.add('show', 'active');

            // Désactiver les boutons actifs dans la nav
            document.querySelectorAll('.nav-link').forEach(function(nav) {
                nav.classList.remove('active');
            });
        }
    });
});
</script>