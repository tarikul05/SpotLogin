function UploadImage() {
    document.getElementById("profile_image_file").value = "";
    $("#profile_image_file").trigger('click');
  }
  let cropper;

const uploadImage = () => {
    document.getElementById("profile_image_file").value = "";
    $("#profile_image_file").trigger('click');
}

const changeImage = () => {
    const fileInput = document.getElementById('profile_image_file');
    const file = fileInput.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const image = document.getElementById('image');
            image.src = event.target.result;

            // Afficher le faux modal et initialiser Cropper.js
            document.getElementById('fakeModalOverlay').style.display = 'block';
            document.getElementById('cropContainer').style.display = 'block';
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                ready: function () {
                    // Ajuster la vue de recadrage initiale si nécessaire
                }
            });
        }
        reader.readAsDataURL(file);
    }
}

const cropImage = () => {
    const canvas = cropper.getCroppedCanvas({
        width: 150, // Dimensions finales de l'image recadrée
        height: 150,
    });

    canvas.toBlob(function (blob) {
        const formData = new FormData();
        formData.append('profile_image_file', blob);
        formData.append('type', 'upload_image');
        formData.append('p_person_id', $("#user_id").val());
        const csrfToken = $('meta[name="_token"]').attr('content') ? $('meta[name="_token"]').attr('content') : '';

        $.ajax({
            url: BASE_URL + '/admin/update-profile-photo',
            data: formData,
            type: 'POST',
            processData: false,
            contentType: false,
            beforeSend: function (xhr) {
                $('#pageloader').fadeIn();
            },
            success: function (result) {
                setTimeout(() => {
                  $('#pageloader').fadeOut();
                }, 500);
                const mfile = result.image_file + '?time=' + new Date().getTime();
                $("#profile_image_user_account").attr("src", mfile);
                $("#user_profile_image").attr("src", mfile);
                $("#admin_logo").attr("src", mfile);
                $("#admin_logo_mobile").attr("src", mfile);
                $("#delete_profile_image").show();
                const isStudent = "{{ $AppUI->isStudent() }}";
                setTimeout(() => {
                  if (isStudent) {
                    successModalCall("Your profile picture is added!");
                  } else {
                      successModalCall("Your logo is added!");
                  }
                }, 1000);
                document.getElementById('fakeModalOverlay').style.display = 'none';
                document.getElementById('cropContainer').style.display = 'none';
            },
            error: function (reject) {
                $('#pageloader').fadeOut();
                const errors = $.parseJSON(reject.responseText);
                $.each(errors.errors, function (key, val) {
                    errorModalCall(val[0] + ' ' + GetAppMessage('error_message_text'));
                });
            },
            complete: function () {
                $('#pageloader').fadeOut();
            }
        });
    }, 'image/jpeg');
}

// Ajouter les gestionnaires d'événements
document.getElementById('profile_image_file').addEventListener('change', changeImage);
document.getElementById('cropImage').addEventListener('click', cropImage);
document.getElementById('closeModal').addEventListener('click', () => {
    // Masquer le faux modal
    document.getElementById('fakeModalOverlay').style.display = 'none';
    document.getElementById('cropContainer').style.display = 'none';
    if (cropper) {
        cropper.destroy();
    }
});