function UploadImage() {
    document.getElementById("profile_image_file").value = "";
    $("#profile_image_file").trigger('click');
  }
  function ChangeImage() {
    var p_person_id = $("#user_id").val(),
        p_file_id = '', data = '';
    var file_data = $('#profile_image_file').prop('files')[0];
    var formData = new FormData();
    formData.append('profile_image_file', file_data);
    formData.append('type', 'upload_image');
    formData.append('p_person_id', p_person_id);
    var csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';
    let loader = $('#pageloader');
    loader.fadeIn();
    $.ajax({
      url: BASE_URL + '/admin/update-profile-photo',
      data: formData,
      type: 'POST',
      //dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend: function (xhr) {
        loader.fadeIn();
      },
      success: function (result) {
        loader.fadeOut();
        var mfile = result.image_file + '?time=' + new Date().getTime();
        $("#profile_image_user_account").attr("src",mfile);
        $("#user_profile_image").attr("src",mfile);
        $("#admin_logo").attr("src",mfile);
        $("#admin_logo_mobile").attr("src",mfile);
        $("#delete_profile_image").show();
        var isStudent = "{{ $AppUI->isStudent() }}";
        if(isStudent) {
          successModalCall("Your profile picture is added!");
        } else {
          successModalCall("Your logo is added!");
        }
      },// success
      error: function (reject) {
        loader.fadeOut();
        let errors = $.parseJSON(reject.responseText);
        errors = errors.errors;
        $.each(errors, function (key, val) {
          //$("#" + key + "_error").text(val[0]);
          errorModalCall(val[0]+ ' '+GetAppMessage('error_message_text'));
        });
      },
      complete: function() {
        loader.fadeOut();
      }
    });
  }
  $('#delete_profile_image').click(function (e) {
    DeleteProfileImage();      // refresh lesson details for billing
  })
  function DeleteProfileImage() {
    //delete image
    document.getElementById("profile_image_file").value = "";
    var p_person_id = document.getElementById('user_id').value;
    let loader = $('#pageloader');
    $.ajax({
      url: BASE_URL + '/admin/delete-profile-photo',
      data: 'user_id=' + p_person_id,
      type: 'POST',
      dataType: 'json',
      beforeSend: function (xhr) {
        loader.fadeIn();
      },
      success: function(response) {
        if (response.status == 'success'){
          loader.fadeOut();
          $("#profile_image_user_account").attr("src",BASE_URL+'/img/photo_blank.jpg');
          $("#user_profile_image").attr("src",BASE_URL+'/img/photo_blank.jpg');
          $("#admin_logo").attr("src",BASE_URL+'/img/photo_blank.jpg');
          $("#admin_logo_mobile").attr("src",BASE_URL+'/img/photo_blank.jpg');
          $("#delete_profile_image").hide();
          successModalCall(response.message);
        }

      },
      error: function (reject) {
        loader.hide("fast");
        let errors = $.parseJSON(reject.responseText);
        errors = errors.errors;
        $.each(errors, function (key, val) {
          //$("#" + key + "_error").text(val[0]);
          errorModalCall(val[0]+ ' '+GetAppMessage('error_message_text'));
        });
      },
      complete: function() {
        loader.hide("fast");
      }
    });

  }
