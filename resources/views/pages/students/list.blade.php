@extends('layouts.main')

@section('head_links')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <link href="//cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
    <script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.min.css" integrity="sha512-fZNmykQ6RlCyzGl9he+ScLrlU0LWeaR6MO/Kq9lelfXOw54O63gizFMSD5fVgZvU1YfDIc6mxom5n60qJ1nCrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- import popper--><script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js" integrity="sha512-lxQ4VnKKW7foGFV6L9zlSe+6QppP9B2t+tMMaV4s4iqAv4iHIyXED7O+fke1VeLNaRdoVkVt8Hw/jmZ+XocsXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
@endsection

@section('content')
    <div class="container">

        <div class="row justify-content-center pt-3">
            <div class="col-md-12">

        <div class="page_header_class pt-1" style="position: static;">
            <h5 class="titlePage">{{ __("Student\"s List") }}</h5>
        </div>
        

        @include('pages.students.navbar')

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
    </div>

    </div>
</div>  

@endsection


@include('layouts.elements.modal_csv_import')
@section('footer_js')

<script>

    $('#students').multiselect({
    maxHeight: 400,
    buttonWidth: '100%',
    dropRight: false,
    enableFiltering: true,
    includeSelectAllOption: true,
    includeFilterClearBtn: true,
    search: true,
    noneSelected: "{{__("None selected") }}",
    selectAllText: "{{__("All Students") }}",
    enableCaseInsensitiveFiltering: true,
    enableFullValueFiltering: false,

    onChange:function (option, checked) {
        updateEmailList(option);
    }
  });
</script>


@foreach($families as $family)
    <script>
    $('#students_family_{{ $family->id }}').multiselect({
    maxHeight: 400,
    buttonWidth: '100%',
    dropRight: false,
    enableFiltering: true,
    includeSelectAllOption: false,
    includeFilterClearBtn: true,
    search: true,
    noneSelected: "{{__("None selected") }}",
    selectAllText: "{{__("All Students") }}",
    enableCaseInsensitiveFiltering: true,
    enableFullValueFiltering: false,
    onChange:function (option, checked) {
        updateEmailListFamily(option, '{{ $family->id }}');
    },
    onDropdownShow:function (event) {
    var id = $(event.target).data('family-id');
    var students = {!! json_encode($students) !!};
    var families = {!! json_encode($families) !!};
    var data = students.map(function(student) {
        var selected = false;
        families.forEach(function(family) {
            if(family.id == '{{ $family->id }}') {
                family.students.forEach(function(familyStudent) {
                    if (familyStudent.id === student.id) {
                        selected = true;
                    }
                });
            }
        });
        return {
            label: student.firstname + ' ' + student.lastname,
            value: student.id,
            selected: selected
        };
    });
    $("#students_family_{{ $family->id }}").multiselect('dataprovider', data);
    },
    });
    </script>

@endforeach



<script>
    function updateEmailListFamily(option, id) {
        console.log('alors', id);
        let selectedEmails = [];
        var selectedStudentId = $('#students_family_' + id).val();
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

        $('#principal_email_family').empty();
        selectedEmails.forEach(function (email) {
            $('#principal_email_family').append($('<option>', { value: email, text: email }));
        });

        if(selectedEmails.length === 0) {
            $('#principal_email_family').append($('<option>', { value: '', text: "{{__('Select address')}}" }));
        }
        $('#principal_email_family').append($('<option>', { value: 'custom', text: "{{__('Custom address')}}" }));
    }


    $('#principal_email_family').change(function () {
    var selectedOption = $(this).val();

    if (selectedOption === 'custom') {
        $('.custom-email-input').show();
    } else {
        $('.custom-email-input').hide();
    }
});

$('#principal_email_family').append($('<option>', { value: 'custom', text: "{{__('Custom address')}}" }));

</script>



<script>
    $(document).ready(function () {

        var table = $('#students1').DataTable({
            dom: '<"top"f>rt<"bottom"lp><"clear">',
            ordering: false,
            searching: true,
            paging: true,
            info: false,
        });

        $('#search_text').on('keyup change', function () {
            table.search($(this).val()).draw();
        });

        $("#students1_filter").hide();
    });
</script>

<script>
    $(document).ready(function () {
        var table = $('#example2').DataTable({
            dom: '<"top"f>rt<"bottom"lp><"clear">',
            ordering: false,
            searching: true,
            paging: true,
            info: false,
        });

        $('#search_text_families').on('keyup change', function () {
            table.search($(this).val()).draw();
        });

        $("#example2_filter").hide();
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.delete-student-btn', function(event) {
            event.preventDefault();

            var schoolId = $(this).attr('data-school');
            var studentId = $(this).attr('data-student');

            Swal.fire({
            title: 'Are you sure to delete this student ?',
            text: "Be carreful, this student still have lessons/events to be invoiced !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {


                var deleteUrl = '{{ route('studentDeleteDestroy', ['school' => ':school', 'student' => ':student']) }}';
                deleteUrl = deleteUrl.replace(':school', schoolId).replace(':student', studentId);

                // Sending an AJAX request to delete the student
                $.ajax({
                    url: deleteUrl,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                    console.log(response);
                        $("#pageloader").fadeOut("fast");
                        if (response.status === "success") {
                            Swal.fire(
                                'Deleted!',
                                'The student has been deleted successfully.',
                                'success'
                            )
                            $("#row_" + studentId).fadeOut();
                        } else {
                            if(response.isFuturInvoice) {
                                Swal.fire(
                                'Student is locked',
                                'This student has lessons to invoice.',
                                'error'
                            )
                            } else {
                                Swal.fire(
                                'Error!',
                                'An error occurred while deleting the student.',
                                'error'
                            )
                            }

                        }
                    },
                    error: function(error) {
                        $("#pageloader").fadeOut("fast");
                        Swal.fire(
                            'Error!',
                            'An error occurred while deleting the student.',
                            'error'
                        )
                    }
                });

                }
                })
        });
    });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.switch-student-btn', function(event) {
                event.preventDefault();

                var schoolId = $(this).attr('data-school');
                var studentId = $(this).attr('data-student');
                var currentStatus = $(this).attr('data-status');

                Swal.fire({
                title: currentStatus === '1' ? 'Are you sure to switch this student to inactive status ?' : 'Are you sure to switch this student to active status ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                if (result.isConfirmed) {

                    var urlzz = BASE_URL + '/'+schoolId+'/student/'+studentId+'';
                    var formData = new FormData();
                    formData.append('status', currentStatus);

                    $.ajax({
                    url: urlzz,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        console.log(response);
                        $("#pageloader").fadeOut("fast");
                        if (response.status === "success") {
                            Swal.fire(
                                'Status updated!',
                                'The student has been updated successfully.',
                                'success'
                            )

                        } else {
                            Swal.fire(
                                'Error!',
                                'An error occurred while updating the status.',
                                'error'
                            )
                        }
                    },
                    error: function (error) {
                        console.log(error);
                        $("#pageloader").fadeOut("fast");
                        Swal.fire(
                            'Error!',
                            'An error occurred while updating the status.',
                            'error'
                        )
                    }
                });

                    }
                    })
            });
        });
        </script>
<script>
$(document).ready(function() {
    $(document).on('click', '.send-invite-btn', function(event) {
        event.preventDefault();
        $("#pageloader").fadeIn("fast");
        var schoolId = $(this).attr('data-school');
        var studentId = $(this).attr('data-student');
        var email = $(this).attr('data-email');

        if(email === null || email === "") {
            $("#pageloader").fadeOut("fast");
            Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "{{ __('Student needs to have an email to send the invitation.') }}",
            allowOutsideClick: false

            });
            return false;
        }

        //if (confirm('Are you sure want to send an invitation to this student ?')) {
            var redirectUrl = '{{ route('studentInvitationGet', ['school' => ':school', 'student' => ':student']) }}';
            redirectUrl = redirectUrl.replace(':school', schoolId).replace(':student', studentId);

            // Sending an AJAX request
            $.ajax({
                url: redirectUrl,
                method: 'GET',
                success: function(response) {
                    $("#pageloader").fadeOut("fast");
                    //$('#sendMailOk').modal('show');

                    Swal.fire(
                        'Successfully sended',
                        'Your student will receive an email with instructions',
                        'success'
                    )

                },
                error: function(error) {
                    $("#pageloader").fadeOut("fast");
                    alert('Error occurred while sending the invitation. Please try again.');
                }
            });
        //}
    });
});
</script>
<script>
$(document).ready(function() {
    $('#save_btn_family').on('click', function() {
        $('#pageloader').fadeIn('fast');
    });
});
</script>
<script>
$(document).ready(function() {
    $(document).on('click', '.send-password-btn', function(event) {
        event.preventDefault();
        $("#pageloader").fadeIn("fast");

        var schoolId = $(this).attr('data-school');
        var studentId = $(this).attr('data-student');

        //if (confirm('Are you sure want to send an invitation to reset the password of this student ?')) {
            var redirectUrl = '{{ route('studentPasswordGet', ['school' => ':school', 'student' => ':student']) }}';
            redirectUrl = redirectUrl.replace(':school', schoolId).replace(':student', studentId);

            // Sending an AJAX request
            $.ajax({
                url: redirectUrl,
                method: 'GET',
                success: function(response) {
                    $("#pageloader").fadeOut("fast");
                    $('#sendMailOk').modal('show');
                },
                error: function(error) {
                    $("#pageloader").fadeOut("fast");
                    alert('Error occurred while sending the password reset invitation. Please try again.');
                }
            });
        //} else {
        //    $("#pageloader").fadeOut("fast");
        //}
    });
});
    </script>


<script>
    $(document).ready(function() {
        $(document).on('click', '.send-invite-btn-family', function(event) {
            event.preventDefault();
            $("#pageloader").fadeIn("fast");
            var schoolId = $(this).attr('data-school');
            var familyId = $(this).attr('data-family');
            var email = $(this).attr('data-email');

            if(email === null || email === "") {
                $("#pageloader").fadeOut("fast");
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "{{ __('Student needs to have an email to send the invitation.') }}",
                allowOutsideClick: false

                });
                return false;
            }

                var redirectUrl = '{{ route('familyInvitationGet', ['school' => ':school', 'family' => ':family']) }}';
                redirectUrl = redirectUrl.replace(':school', schoolId).replace(':family', familyId);

                // Sending an AJAX request
                $.ajax({
                    url: redirectUrl,
                    method: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $("#pageloader").fadeOut("fast");
                        if (response.success) {
                        Swal.fire(
                            'Successfully sended',
                            response.message,
                            'success'
                        )
                    } else {
                        Swal.fire(
                            'Error',
                            response.message,
                            'error'
                        )
                    }
                    },
                    error: function(error) {
                        $("#pageloader").fadeOut("fast");
                        alert('Error occurred while sending the invitation. Please try again.');
                    }
                });
        });
    });
    </script>


<script>
    document.getElementById('select-all').addEventListener('change', function () {
        var checkboxes = document.querySelectorAll('input[name="selected_students[]"]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }

        if (this.checked) {
            var deleteButton = document.getElementById('delete-selected');
            deleteButton.scrollIntoView({ behavior: 'smooth' });
        }
    });
</script>
<script type="text/javascript">
    $(document).ready( function () {

        @can('students-create')
        $("#example_filter").append('<a id="csv_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.export',['school'=> $schoolId]) : route('student.export') }}" target="_blank" class="btn btn-theme-success add_teacher_btn"><img src="{{ asset('img/excel_icon.png') }}" width="18" height="auto"/>{{__("Export")}}</a><a href="#" data-bs-toggle="modal" data-bs-target="#importModal" id="csv_btn_import" class="btn btn-theme-success add_teacher_btn"><img src="{{ asset('img/excel_icon.png') }}" width="18" height="auto"/>{{__("Import")}}</a><a class="btn btn-theme-success add_teacher_btn" href="{{ auth()->user()->isSuperAdmin() ? route('admin.student.create',['school'=> $schoolId]) : route('student.create') }}">{{__("Add New")}}</a>')
        @endcan

        if (window.location.href.indexOf('#login') != -1) {
            $('#importModal').modal('show');
        }
        function validateForm() {
            var currency_code = document.getElementById("currency_code").value;
            var currency_title = document.getElementById("currency_title").value;
            var sort_order = document.getElementById("sort_order").value;
            let error = false;
            if (currency_code == null || currency_code == "") {
                    $('#currency_code').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
                    document.getElementById("currency_code").focus();
                    error = true;
            }
            if (currency_title == null || currency_title == "") {
                document.getElementById("currency_title").focus();
                $('#currency_title').parents('.form-group-data').append("<span class='error'>{{__('This field is required.')}}</span>");
                error = true;
            }


            if (error) {
                return false;
            }else{
                return true;
            }
        }
        $("#csv_import").validate({
            // Specify validation rules
            rules: {
                csvFile: {
                    required: true
                }
            },
            // Specify validation error messages
            messages: {
                csvFile:"{{ __('Please select a Excel file') }}"
            },
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').append(error);
            },
            submitHandler: function (form) {
                $("#pageloader").fadeIn("fast");
                return true;
            }
        });
    });

    function addFilter(text) {
        $('#search_text').val(text).change()
    }
</script>


<script type="text/javascript">
	/*
	* student province list
	* function @billing province
	*/
	$(document).ready(function(){
		var country_code = $('#country_code option:selected').val();
		get_province_lists(country_code);
	});

	$('#country_code').change(function(){
		var country_code = $(this).val();
		get_province_lists(country_code);
	})

	function get_province_lists(country_code){
		$.ajax({
			url: BASE_URL + '/get_province_by_country',
			data: 'country_name=' + country_code,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(response) {
					if(response.data.length > 0){
						var html = '';
						$.each(response.data, function(i, item) {
							html += '<option value="'+ item.id +'">' + item.province_name + '</option>';
						});
						$('#province_id').html(html);
						$('#province_id_div').show();
				}else{
					$('#province_id').html('');
					$('#province_id_div').hide();
				}
			},
			error: function(e) {
				//error
			}
		});
	}

	/*
	* Billing province list
	* function @billing province
	*/
	$('#billing_country_code').change(function(){
		var country_code = $(this).val();
		get_billing_province_lists(country_code);
	})

	$(document).ready(function(){
		var billing_country_code = $('#billing_country_code option:selected').val();
		get_billing_province_lists(billing_country_code);
	});

	function get_billing_province_lists(country_code){
		$.ajax({
			url: BASE_URL + '/get_province_by_country',
			data: 'country_name=' + country_code,
			type: 'POST',
			dataType: 'json',
			async: false,
			success: function(response) {
					if(response.data.length > 0){
						var html = '';
						$.each(response.data, function(i, item) {
							html += '<option value="'+ item.id +'">' + item.province_name + '</option>';
						});
						$('#billing_province_id').html(html);
						$('#billing_province_id_div').show();
				}else{
					$('#billing_province_id').html('');
					$('#billing_province_id_div').hide();
				}
			},
			error: function(e) {
				//error
			}
		});
	}

</script>

<script type="text/javascript">
$(function() {

	// var b_country = $('#billing_country_code option:selected').val();
	// var country_code = $('#country_code option:selected').val();
	// if(country_code == 'CA'){
	// 	$('#province_id_div').show();
	// }
	// if(b_country == 'CA'){
	// 	$('#billing_province_id_div').show();
	// }

	$("#birth_date").datetimepicker({
		format: "dd/mm/yyyy",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
	$("#level_date_arp").datetimepicker({
		format: "yyyy-mm-dd",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});
	$("#level_date_usp").datetimepicker({
		format: "yyyy-mm-dd",
		autoclose: true,
		todayBtn: true,
		minuteStep: 10,
		minView: 3,
		maxView: 3,
		viewSelect: 3,
		todayBtn:false,
	});

	$('#bill_address_same_as').click(function(){
		if($(this).is(':checked')){
			$('#billing_place').val( $('#place').val() );
			$('#billing_street').val( $('#street').val() );
			// $('#billing_street2').val( $('#street2').val() );
			$('#billing_street_number').val( $('#street_number').val() );
			$('#billing_zip_code').val( $('#zip_code').val() );
			$('#billing_country_code').val( $('#country_code option:selected').val() );
			$('#billing_province_id').val( $('#province_id option:selected').val() );
		}
	});

	$('#save_btn').click(function (e) {
		var formData = $('#add_student').serializeArray();
		var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
		var error = '';
		$( ".form-control.require" ).each(function( key, value ) {
			var lname = $(this).val();
			if(lname=='' || lname==null || lname==undefined){
				$(this).addClass('error');
				error = 1;
			}else{
				$(this).removeClass('error');
				error = 0;
			}
		});
		formData.push({
			"name": "_token",
			"value": csrfToken,
		});
		if(error < 1){
			return true;
		}else{
			return false;
		}
	});


$("#country_code, #billing_country_code").trigger('change')

});
$(function() { $('.colorpicker').wheelColorPicker({ sliders: "whsvp", preview: true, format: "css" }); });


$('#profile_image_file').change(function(e) {
  var reader = new FileReader();
  reader.onload = function(e) {
    document.getElementById("frame").src = e.target.result;
  };
  reader.readAsDataURL(this.files[0]);
  	$('#profile_image i.fa.fa-plus').hide();
	$('#profile_image i.fa.fa-close').show();
});


$('.box_img i.fa.fa-close').click(function (e) {
	 document.getElementById("frame").src = BASE_URL +"/img/default_profile_image.png";
	$('#profile_image i.fa.fa-plus').show();
	$('#profile_image i.fa.fa-close').hide();
})

</script>
@endsection
