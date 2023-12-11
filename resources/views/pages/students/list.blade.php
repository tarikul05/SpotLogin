@extends('layouts.main')

@section('head_links')
    <link href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <link href="//cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css')}}"/>
    <script src="{{ asset('js/jquery.wheelcolorpicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/wheelcolorpicker.css')}}"/>
@endsection

@section('content')
    <div class="container">

        <h5>{{ __("Student\"s List") }}</h5>

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


        </div>
    </div>

@endsection


@include('layouts.elements.modal_csv_import')
@section('footer_js')
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
        $('#studentList').DataTable({
            language: { search: "" },
            /* start resultat to 10 results */
            pageLength: -1,
            lengthMenu: [
                [-1, 10, 25, 50, 100],
                ['All', 10, 25, 50, 100]
            ],
            "responsive": true,
            "oLanguage": {
                "sLengthMenu": "Show _MENU_",
            }
        });

        var searchInput = document.querySelector('.dataTables_wrapper .dataTables_filter input');
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                this.style.backgroundImage = 'none';
            }
            else {
                this.style.backgroundImage = 'url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB2ZXJzaW9uPSIxLjEiICAgaWQ9InN2ZzQ0ODUiICAgdmlld0JveD0iMCAwIDIxLjk5OTk5OSAyMS45OTk5OTkiICAgaGVpZ2h0PSIyMiIgICB3aWR0aD0iMjIiPiAgPGRlZnMgICAgIGlkPSJkZWZzNDQ4NyIgLz4gIDxtZXRhZGF0YSAgICAgaWQ9Im1ldGFkYXRhNDQ5MCI+ICAgIDxyZGY6UkRGPiAgICAgIDxjYzpXb3JrICAgICAgICAgcmRmOmFib3V0PSIiPiAgICAgICAgPGRjOmZvcm1hdD5pbWFnZS9zdmcreG1sPC9kYzpmb3JtYXQ+ICAgICAgICA8ZGM6dHlwZSAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4gICAgICAgIDxkYzp0aXRsZT48L2RjOnRpdGxlPiAgICAgIDwvY2M6V29yaz4gICAgPC9yZGY6UkRGPiAgPC9tZXRhZGF0YT4gIDxnICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLC0xMDMwLjM2MjIpIiAgICAgaWQ9ImxheWVyMSI+ICAgIDxnICAgICAgIHN0eWxlPSJvcGFjaXR5OjAuNSIgICAgICAgaWQ9ImcxNyIgICAgICAgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNjAuNCw4NjYuMjQxMzQpIj4gICAgICA8cGF0aCAgICAgICAgIGlkPSJwYXRoMTkiICAgICAgICAgZD0ibSAtNTAuNSwxNzkuMSBjIC0yLjcsMCAtNC45LC0yLjIgLTQuOSwtNC45IDAsLTIuNyAyLjIsLTQuOSA0LjksLTQuOSAyLjcsMCA0LjksMi4yIDQuOSw0LjkgMCwyLjcgLTIuMiw0LjkgLTQuOSw0LjkgeiBtIDAsLTguOCBjIC0yLjIsMCAtMy45LDEuNyAtMy45LDMuOSAwLDIuMiAxLjcsMy45IDMuOSwzLjkgMi4yLDAgMy45LC0xLjcgMy45LC0zLjkgMCwtMi4yIC0xLjcsLTMuOSAtMy45LC0zLjkgeiIgICAgICAgICBjbGFzcz0ic3Q0IiAvPiAgICAgIDxyZWN0ICAgICAgICAgaWQ9InJlY3QyMSIgICAgICAgICBoZWlnaHQ9IjUiICAgICAgICAgd2lkdGg9IjAuODk5OTk5OTgiICAgICAgICAgY2xhc3M9InN0NCIgICAgICAgICB0cmFuc2Zvcm09Im1hdHJpeCgwLjY5NjQsLTAuNzE3NiwwLjcxNzYsMC42OTY0LC0xNDIuMzkzOCwyMS41MDE1KSIgICAgICAgICB5PSIxNzYuNjAwMDEiICAgICAgICAgeD0iLTQ2LjIwMDAwMSIgLz4gICAgPC9nPiAgPC9nPjwvc3ZnPg==)';
            }
        });

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
