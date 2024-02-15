

//Display more prices
$(document).on('click','.see_more_prices',function(){
    $('.hide-custom-price').slideToggle('slow');
    $('.see_more_prices').slideToggle();
    $('.see_less_prices').slideToggle();
    $('html, body').animate({
    scrollTop: $(document).height()
    }, 100);
});

//Hide more prices
$(document).on('click','.see_less_prices',function(){
    $('.hide-custom-price').slideToggle('slow');
    $('.see_more_prices').slideToggle();
    $('.see_less_prices').slideToggle();
    $('html, body').animate({
    scrollTop: $(document).height()
    }, 100);
});

//ColorPicker
$('.colorpicker').each(function() {
    var colorValue = $(this).val() || "{{ old('bg_color_agenda') }}";
    $(this).wheelColorPicker({
        sliders: "whsvp",
        preview: true,
        format: "css"
    }).wheelColorPicker('value', colorValue);
});

$(document).ready(function(){
$('.colorpicker').on('colorpickerChange', function(event) {
    $(this).val(event.color.toString());
    console.log('new color', event.color.toString())
});
});

//Function intialize colorPicker
function initializeColorpicker(element) {
    element.wheelColorPicker({
        sliders: "whsvp",
        preview: true,
        format: "css"
    });
}

//Remove category
$(document).on('click','.delete_event',function(){
    var lst_id = $(this).attr('data-r_id');
    var incre = parseInt(lst_id);
    $(this).attr('data-last_event_cat_id',incre);
    var id = $(this).data('category_id');
    if(id){
    return Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {

          var lst_id = $('#add_more_event_category_btn').attr('data-last_event_cat_id');
          var incre = (parseInt(lst_id)-1);
          $('#add_more_event_category_btn').attr('data-last_event_cat_id',incre);
          var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
          var id = $(this).data('category_id');
          var current_obj = $(this);
          if(id){
              $.ajax({
                  url: BASE_URL + '/remove-event-category/'+id,
                  type: 'DELETE',
                  dataType: 'json',
                  data: {
                      "id": id,
                      "_token": csrfToken,
                  },
                  success: function(response){

                      if(response.status == 1){
                        $(this).parents('.add_more_event_category_row').remove();
                      }

                      let timerInterval
                        Swal.fire({
                        title: 'Category has deleted !',
                        html: 'Reload categories...',
                        icon: 'success',
                        timer: 2500,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                            timerInterval = setInterval(() => {
                            b.textContent = Swal.getTimerLeft()
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                        }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            console.log('I was closed by the timer')
                            //reload the page
                            location.reload();
                        }
                        })


                  }
              })
              $(this).parents('.add_more_event_category_row').fadeOut();
          }else{
              $(this).parents('.add_more_event_category_row').remove();
          }
        }
      });
    }else{

        $(this).parents('.add_more_event_category_row').remove();
        var table = document.getElementById("add_more_event_category_div");
        var rows = table.querySelectorAll("tr");
        if (rows.length === 1) {
            table.style.display = "none";
            $('#btnSaveCategories').hide();
        }
    }
});

//Add category
$(document).ready(function(){
	$(document).on('click','#add_more_event_category_btn',function(){
        $('#btnSaveCategories').show();
        $('#add_more_event_category_div').fadeIn();
		var lst_id = $(this).attr('data-last_event_cat_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_event_cat_id',incre); //<span class="badge bg-info">new</span>
		var resultHtml = `<tr class="add_more_event_category_row">
        <td class="text-center align-middle">
        <div class="form-group">
            <input class="invcat_name" name="category[`+lst_id+`][invoice]" type="hidden" value="S" checked>
            <input type="text" class="form-control" name="category[`+lst_id+`][name]" value=""></div>
            </td>
        <td class="text-center align-middle">
        <input type="text" name="category[`+lst_id+`][bg_color_agenda]"  class="colorpicker dot category_bg_color_agenda" />
        </td>
        <td>
        <div class="form-check">
            <label class="form-check-label" for="sradio2`+lst_id+`">
                <input type="radio" class="form-check-input" id="sradio2`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="0">Hourly rate
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" for="sradio`+lst_id+`">
                <input type="radio" class="form-check-input" id="sradio`+lst_id+`" name="category[`+lst_id+`][s_std_pay_type]" value="1">Fixed price (per student /hour)
            </label>
        </div>
        </td>
        <td class="align-middle text-center">
        <button type="button" class="btn btn-theme-warn delete_event" data-r_id="`+lst_id+`"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
        </tr>`;
        $("#add_more_event_category_div").append(resultHtml);

        //Initalize colorPicker for new category
        initializeColorpicker($("#add_more_event_category_div .colorpicker").last());
        //Scroll to bottom
		window.scrollTo(0, document.body.scrollHeight);
    });


    $(document).on('click','#add_more_location_btn',function(){
        console.log('ou');
        $('#add_more_location_div').fadeIn();
		var lst_id = $(this).attr('data-last_location_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_location_id',incre);

		var resultHtml = `<tr class="add_more_location_row">
        <td class="text-center align-middle">
        <div class="form-group">
        <input class="form-control location_name" name="location[`+lst_id+`][name]" placeholder="location name" type="text">
        </td>
        <td></td>
        <td class="align-middle text-center">
        <button type="button" class="btn btn-theme-warn delete_location"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
        </tr>`;

		$("#add_more_location_div").append(resultHtml);
		window.scrollTo(0, document.body.scrollHeight);
	})



    $(document).on('click','.delete_location',function(){
        var lst_id = $(this).attr('data-r_id');
        var incre = parseInt(lst_id);
        $(this).attr('data-last_location_id',incre);
        var id = $(this).data('location_id');
        if(id){
        return Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
            Swal.fire(
                'Deleted!',
                'Your category has been deleted.',
                'success'
            )
            var lst_id = $('#add_more_event_category_btn').attr('data-last_location_id');
            var incre = (parseInt(lst_id)-1);
            $('#add_more_event_category_btn').attr('data-last_location_id',incre);
            var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
            var id = $(this).data('location_id');
            var current_obj = $(this);
            if(id){
                $.ajax({
                    url: BASE_URL + '/remove-event-location/'+id,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "id": id,
                        "_token": csrfToken,
                    },
                    success: function(response){

                        if(response.status == 1){
                            $(this).parents('.add_more_location_row').remove();
                        }
                    }
                })
                $(this).parents('.add_more_location_row').fadeOut();
            }else{
                $(this).parents('.add_more_location_row').remove();
            }
            }
        });
        }else{

            $(this).parents('.add_more_location_row').remove();
            var table = document.getElementById("add_more_location_div");
            var rows = table.querySelectorAll("tr");
            if (rows.length === 1) {
                table.style.display = "none";
            }
        }
    });






    $(document).on('click','#add_more_level_btn',function(){
        console.log('ou');
        $('#add_more_level_div').fadeIn();
		var lst_id = $(this).attr('data-last_level_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_level_id',incre);

		var resultHtml = `<tr class="add_more_level_row">
        <td class="text-center align-middle">
        <div class="form-group">
        <input class="form-control level_name" name="level[`+lst_id+`][name]" placeholder="level name" type="text">
        </td>
        <td></td>
        <td class="align-middle text-center">
        <button type="button" class="btn btn-theme-warn delete_level"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
        </tr>`;

		$("#add_more_level_div").append(resultHtml);
		window.scrollTo(0, document.body.scrollHeight);
	})


    $(document).on('click','.delete_level',function(){
        var lst_id = $(this).attr('data-r_id');
        var incre = parseInt(lst_id);
        $(this).attr('data-last_level_id',incre);
        var id = $(this).data('level_id');
        if(id){
        return Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
            Swal.fire(
                'Deleted!',
                'Your category has been deleted.',
                'success'
            )
            var lst_id = $('#add_more_event_category_btn').attr('data-last_level_id');
            var incre = (parseInt(lst_id)-1);
            $('#add_more_event_category_btn').attr('data-last_level_id',incre);
            var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
            var id = $(this).data('level_id');
            var current_obj = $(this);
            if(id){
                $.ajax({
                    url: BASE_URL + '/remove-event-level/'+id,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "id": id,
                        "_token": csrfToken,
                    },
                    success: function(response){

                        if(response.status == 1){
                            $(this).parents('.add_more_level_row').remove();
                        }
                    }
                })
                $(this).parents('.add_more_level_row').fadeOut();
            }else{
                $(this).parents('.add_more_level_row').remove();
            }
            }
        });
        }else{

            $(this).parents('.add_more_level_row').remove();
            var table = document.getElementById("add_more_level_div");
            var rows = table.querySelectorAll("tr");
            if (rows.length === 1) {
                table.style.display = "none";
            }
        }
    });



    $(document).on('click','#add_more_tax_btn',function(){
        console.log('ou');
        $('#add_more_tax_div').fadeIn();
		var lst_id = $(this).attr('data-last_tax_id');
		var incre = (parseInt(lst_id)+1);
		$(this).attr('data-last_tax_id',incre);

		var resultHtml = `<tr class="add_more_tax_row">
        <td class="text-center align-middle">
        <div class="form-group">
        <input type="text" class="form-control" name="tax_name[]" value="" placeholder="Tax Name" maxlength="255">
        </td>
        <td>
        <input type="text" class="form-control tax_percentage" name="tax_percentage[]" value="" placeholder="Tax Percentage" maxlength="6">
        </td>
        <td>
        <input type="text" class="form-control" name="tax_number[]" value="" placeholder="Tax Number" maxlength="255">
        </td>
        <td class="align-middle text-center">
        <button type="button" class="btn btn-danger delete_tax"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
        </tr>`;

		$("#add_more_tax_div").append(resultHtml);
		window.scrollTo(0, document.body.scrollHeight);
	})


    $(document).on('click','.delete_tax',function(){
        var lst_id = $(this).attr('data-r_id');
        var incre = parseInt(lst_id);
        $(this).attr('data-last_tax_id',incre);
        var id = $(this).data('tax_id');
        if(id){
        return Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
            Swal.fire(
                'Deleted!',
                'Your tax has been deleted.',
                'success'
            )
            var lst_id = $('#add_more_event_category_btn').attr('data-last_tax_id');
            var incre = (parseInt(lst_id)-1);
            $('#add_more_event_category_btn').attr('data-last_tax_id',incre);
            var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
            var id = $(this).data('tax_id');
            var current_obj = $(this);
            if(id){
                $.ajax({
                    url: BASE_URL + '/remove-event-tax/'+id,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "id": id,
                        "_token": csrfToken,
                    },
                    success: function(response){

                        if(response.status == 1){
                            $(this).parents('.add_more_tax_row').remove();
                        }
                    }
                })
                $(this).parents('.add_more_tax_row').remove();
            }else{
                $(this).parents('.add_more_tax_row').remove();
            }
            }
        });
        }else{

            $(this).parents('.add_more_tax_row').remove();
            var table = document.getElementById("add_more_tax_div");
            var rows = table.querySelectorAll("tr");
            if (rows.length === 1) {
                table.style.display = "none";
            }
        }
    });


});
