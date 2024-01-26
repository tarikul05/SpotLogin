

    $(document).ready(function(){

    $(document).on('click', "input[name$=\'[s_std_pay_type]\'][value='2']", function(event) {
        if ($(this).prop("checked")) {
            $(this).closest('.pack_invoice_area').find("input[name$=\'[s_thr_pay_type]\'][value='1']").prop('checked', true)
        }
    });

    //if student package selected, teacher can't be move on hourly rate
    $(document).on('click', "input[name$=\'[s_thr_pay_type]\'][value='0']", function(event) {
        var dd = $(this).closest('.pack_invoice_area').find("input[name$=\'[s_std_pay_type]\'][value='2']").prop('checked')
        if (dd) {
            alert("If the student is packaged the teacher can not be paid hourly")
            event.preventDefault();
        }
    });


    $('#add_more_event_category_div').on('click', '.invcat_name', function() {
		var type = $(this).val();
		if(type == 'T'){
			$(this).closest(".invoice_part").find('.pack_invoice_area.student').hide();
			$(this).closest(".invoice_part").find('.pack_invoice_area.teacher').show();
		}else if(type == 'S'){
			$(this).closest(".invoice_part").find('.pack_invoice_area.teacher').hide();
			$(this).closest(".invoice_part").find('.pack_invoice_area.student').show();
		}
	});

})



