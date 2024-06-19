
<form enctype="multipart/form-data" class="form-horizontal" id="add_family" method="post" action="{{ route('student.createFamilyAction') }}"  name="add_family" role="form">
    @csrf

    <input type="hidden" name="schoolID" value="{{ $schoolId }}">
    <div class="row justify-content-center pt-1">
    <div class="col-md-12">

        <div class="card2">
        <div class="card-header titleCardPage h6">{{ __('Create a family') }}</div>
            <div class="card-body">

                <div class="from-group">
                    <label class="titleFieldPage" for="name"><b>{{ __('Family name') }}</b></label>
                    <input class="form-control" id="family_name" placeholder="{{ __('Family name') }}" name="family_name" type="text" required>
                </div>

                <div class="from-group mt-3">
                    <label class="titleFieldPage" for="principal_name"><b>{{ __('Select all members of the family') }}</b></label>
                    <select class="form-select" multiple id="students" name="students[]" required>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" id="{{ $student->id }}">{{ $student->full_name }}</option>
                        @endforeach
                    </select>
                    <!--<hr>
                    @foreach($students as $student)

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $student->id }}" id="{{ $student->id }}" name="students[]">
                            <label class="form-check-label" for="{{ $student->id }}">
                                {{ $student->full_name }}
                            </label>
                        </div>

                    @endforeach-->
                </div>

                <div class="from-group mt-3">
                    <label  class="titleFieldPage"for="principal_email"><b>{{ __('Principal Email') }}</b> <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('This address will receive the email necessary to set up the familly account')}}"></i></label>
                    <select class="form-control" id="principal_email" name="principal_email">
                       <option>{{ __('Select Email') }}</option>
                    </select>
                    <!-- Champ d'entrée pour une adresse personnalisée (initialement caché) -->
                    <div class="custom-email-input mt-3" style="display: none;">
                        <label for="custom_email"><b>{{__('Custom address')}}</b></label>
                        <input class="form-control" id="custom_email" placeholder="{{__('Custom address')}}" name="custom_email" type="email">
                    </div>
                </div>


            </div>
        </div>




    </div>

    <div class="row justify-content-center" style="position:fixed; bottom:0; z-index=99999!important;opacity:1!important; width:100%;">
        <div class="col-md-12 mt-3 pt-3 pb-3 card-header text-center" style="opacity:0.8!important; background-color:#DDDD!important;">
            <button type="submit" id="save_btn" name="save_btn" class="btn btn-success">{{ __('Save') }}</button>
        </div>
    </div>


</div>

</form>


@section('footer_js')
    @parent
    <script>
        function updateEmailList(option) {

            let selectedEmails = [];
            var selectedStudentId = $('#students').val();
            var studentId = option.val();
            selectedEmails = [];

            @foreach($students as $student)
                var studentListId = {{ $student->id }};
                if(selectedStudentId && selectedStudentId.indexOf(studentListId.toString()) !== -1) {
                    var fatherEmail = '{{ $student->father_email }}';
                    var motherEmail = '{{ $student->mother_email }}';
                    if (fatherEmail) {
                        selectedEmails.push(fatherEmail);
                    }
                    if (motherEmail) {
                        selectedEmails.push(motherEmail);
                    }
                }
            @endforeach

            $('#principal_email').empty();
            selectedEmails.forEach(function (email) {
                $('#principal_email').append($('<option>', { value: email, text: email }));
            });

            if(selectedEmails.length === 0) {
                $('#principal_email').append($('<option>', { value: '', text: "{{__('Select address')}}" }));
            }
            $('#principal_email').append($('<option>', { value: 'custom', text: "{{__('Custom address')}}" }));
        }


        $('#principal_email').change(function () {
        var selectedOption = $(this).val();

        if (selectedOption === 'custom') {
            $('.custom-email-input').show();
        } else {
            $('.custom-email-input').hide();
        }
    });

    $('#principal_email').append($('<option>', { value: 'custom', text: "{{__('Custom address')}}" }));

    </script>
@endsection
