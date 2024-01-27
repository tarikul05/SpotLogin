
<form enctype="multipart/form-data" class="form-horizontal" id="add_family" method="post" action="{{ route('student.createFamilyAction') }}"  name="add_family" role="form">
    @csrf
    <input type="hidden" name="schoolID" value="{{ $schoolId }}">
    <div class="row justify-content-center pt-1">
    <div class="col-md-12">

        <div class="card">
        <div class="card-header h6">{{ __('Create a family') }}</div>
            <div class="card-body">

                <div class="from-group">
                    <label for="name"><b>{{ __('Family name') }}</b></label>
                    <input class="form-control" id="family_name" placeholder="{{ __('Family name') }}" name="family_name" type="text" required>
                </div>
                <div class="from-group mt-3">
                    <label for="principal_email"><b>{{ __('Principal Email') }}</b> <i class="fa fa-info-circle" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Parents email addresses are added to this list when you check students (if parents email adresses are registered)')}}"></i></label>
                    <select class="form-control" id="principal_email" name="principal_email">
                       <option>{{ __('Select Email') }}</option>
                    </select>
                    <!-- Champ d'entrée pour une adresse personnalisée (initialement caché) -->
                    <div class="custom-email-input mt-3" style="display: none;">
                        <label for="custom_email"><b>{{__('Custom address')}}</b></label>
                        <input class="form-control" id="custom_email" placeholder="{{__('Custom address')}}" name="custom_email" type="email">
                    </div>
                </div>
                <div class="from-group mt-3">
                    <label for="principal_name"><b>{{ __('Select all members of the family') }}</b></label>
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

            </div>
        </div>





		<div class="mt-3">
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
