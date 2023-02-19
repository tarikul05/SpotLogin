
jQuery(document).ready(function($) {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

});


function successModalCall(title, desc = '') {
  $('#successModal').remove();
  var modalHtml = '';
  modalHtml = `<div class="modal fade" id="successModal" name="successModal">
        <div class="modal-dialog mt-5">
            <div class="modal-content">
                <div class="modal-body text-center p-4">                    
                    <h4 class="light-blue-txt gilroy-bold">` + title + `</h4>
                    <p style="font-size: 20px;">` + desc + `</p>
                    <button type="button" class="btn btn-primary gilroy-medium" data-bs-dismiss="modal" style="width:188px;">OK</button>
                </div>
            </div>
        </div>
    </div>`;
  $('body').append(modalHtml);
  $("#successModal").modal('show');
}

function errorModalCall(title, desc = '') {
  $('#errorModal').remove();
  var modalHtml = '';
  modalHtml = `<div class="modal fade" id="errorModal" name="errorModal">
            <div class="modal-dialog mt-5">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">                    
                        <h4 class="light-blue-txt gilroy-bold">` + title + `</h4>
                        <p style="font-size: 20px;">` + desc + `</p>
                        <button type="button" class="btn btn-primary gilroy-medium" data-bs-dismiss="modal" style="width:188px;">OK</button>
                    </div>
                </div>
            </div>
        </div>`;
  //alert(modalHtml);
  $('body').append(modalHtml);
  $("#errorModal").modal('show');
}

function GetAppMessage(appcode){
    var appmsg='';
    if (typeof(Storage) !== "undefined") {
        appmsg=localStorage.getItem(appcode);
        if ((appmsg =='') || (appmsg == null)) {
                appmsg=sessionStorage.getItem(appcode);
            }
        if ((appmsg == null)){
            appmsg='';    
        }
    } else {
        //undefined
        appmsg='Error: check application code: '+appcode;
    }
    return appmsg;
}

function setSessionStorage(p_key,p_val) {
	sessionStorage.setItem(p_key, p_val);
	localStorage.setItem(p_key, p_val);
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}


function confirmModalCall(title,function_name){
    $('#confirmModal').remove();  
    var modalHtml='';
    
    var v_title='remove_title_text';
    var ok_btn_text='confirm_ok_btn';
    var cancel_btn_text='confirm_cancel_btn';
    
    v_title = ((title == '') ? v_title : title);
    //ok_btn_text = ((ok_btn_text =='') ? 'Ok' : ok_btn_text);
    //cancel_btn_text = ((ok_btn_text =='') ? 'Cancel' : cancel_btn_text);
    
    modalHtml =`
    <div class="modal fade confirm-modal" id="confirmModal" tabindex="-1" aria-hidden="true"
        aria-labelledby="confirmModal" name="confirmModal">
        <div class="modal-dialog mt-5">
            <div class="modal-content">
                <div class="modal-body text-center p-4">                    
                    <h4 class="light-blue-txt gilroy-bold">`+v_title+`</h4>
                    
                    <button id="confirm_ok_btn" type="button" class="btn btn-primary gilroy-medium" data-bs-dismiss="modal" style="width:188px;" onclick="`+function_name+`">`+ok_btn_text+`</button>
                    <button id="confirm_cancel_btn" type="button" class="btn btn-danger gilroy-medium" aria-label="Close" style="width:188px;" data-bs-dismiss="modal">`+cancel_btn_text+`</button>
                </div>
            </div>
        </div>
    </div>`;
    $('body').append(modalHtml);
    $("#confirmModal").modal('show');
}



function confirmDeleteModalCall(p_event_id,title,function_name,showWarning=true){
    $('#confirmModal').remove();  
    var modalHtml='';
    
    var v_title='remove_title_text';
    var ok_btn_text='Confirm';
    var cancel_btn_text='Cancel';
    
    v_title = ((title == '') ? v_title : title);
    //var selected_ids = [];
    if (p_event_id.length!=0) {
        p_event_id = p_event_id.split("|");
    }
    modalHtml =`
    <div class="modal fade confirm-modal" id="confirmModal" tabindex="-1" aria-hidden="true"
        aria-labelledby="confirmModal" name="confirmModal">
        <div class="modal-dialog mt-5">
            <div class="modal-content">
                <div class="modal-body text-center p-4">                    
                    <h4 class="light-blue-txt gilroy-bold">`+v_title+`</h4>
                    <ul>`;
                    if (p_event_id.length!=0) {
                        p_event_id.forEach((element) => {
                            modalHtml +=`<li class="light-blue-txt gilroy-bold"><p>`+element.replace(/['"]+/g, '')+`</p></li>`;
                        });
                    }
                    modalHtml +=`</ul>`
                    if (showWarning)  modalHtml +=`<div class="alert alert-warning" role="alert">Only un-validated events/lessons will be deleted</div>`

                modalHtml +=`
                    <button id="confirm_ok_btn" type="button" class="btn btn-primary gilroy-medium" data-bs-dismiss="modal" style="width:188px;" onclick="`+function_name+`">`+ok_btn_text+`</button>
                    <button id="confirm_cancel_btn" type="button" class="btn btn-danger gilroy-medium" aria-label="Close" style="width:188px;" data-bs-dismiss="modal">`+cancel_btn_text+`</button>
                </div>
            </div>
        </div>
    </div>`;
    $('body').append(modalHtml);
    $("#confirmModal").modal('show');
}

function confirmMultipleValidateModalCall(p_event_id,title,function_name,all_events){
    $('#confirmModal').remove();  
    var modalHtml='';
    
    var v_title='remove_title_text';
    var ok_btn_text='Confirm';
    var cancel_btn_text='Cancel';
    
    v_title = ((title == '') ? v_title : title);
    //var selected_ids = [];
    if (p_event_id.length!=0) {
        p_event_id = p_event_id.split("|");
    }
    modalHtml =`
    <div class="modal fade confirm-modal" id="confirmModal" tabindex="-1" aria-hidden="true"
        aria-labelledby="confirmModal" name="confirmModal">
        <div class="modal-dialog mt-5">
            <div class="modal-content">
                <div class="modal-body text-center p-4">                    
                    <h4 class="light-blue-txt gilroy-bold">`+v_title+`</h4>
                    <ul>`;
                    if (p_event_id.length!=0) {
                        p_event_id.forEach((element) => {
                            modalHtml +=`<li class="light-blue-txt gilroy-bold"><p>`+element.replace(/['"]+/g, '')+`</p></li>`;
                        });
                    }
                modalHtml +=`</ul>
                    <button id="confirm_ok_btn" type="button" class="btn btn-primary gilroy-medium" data-bs-dismiss="modal" style="width:188px;" onclick="`+function_name+`">`+ok_btn_text+`</button>
                    <button id="confirm_cancel_btn" type="button" class="btn btn-danger gilroy-medium" aria-label="Close" style="width:188px;" data-bs-dismiss="modal">`+cancel_btn_text+`</button>
                </div>
            </div>
        </div>
    </div>`;
    $('body').append(modalHtml);
    $("#confirmModal").modal('show');
}



function confirmPayReminderModalCall(p_event_id,title,all_events,p_school_id){
    //$('#email_list_modal').hide();  
    //var modalHtml='';
    
    var v_title='remove_title_text';
    
    v_title = ((title == '') ? v_title : title);
    var p_event_id = p_event_id;
    document.getElementById("p_school_id").value = p_school_id;
                
    

    //email selection modal on click email reminder -->

    //modalHtml =` `;
    var find_flag = 0;
    $.each(all_events, function (key, value) {
        find_flag = 1;
        console.log(value.father_email);
        if (value.class_name == 'student') {
            if (value.father_email && value.father_email != '') {
                //document.getElementById("father_email_chk").value = value.father_email;
                document.getElementById("father_email_chk").checked = true;
                $('#father_email_cap').html(value.father_email);
                $("#father_email_div").show();
            } else {
                $("#father_email_div").hide();
            }

            if (value.mother_email && value.mother_email != '') {
                //document.getElementById("mother_email_chk").value = value.mother_email;
                document.getElementById("mother_email_chk").checked = true;
                $('#mother_email_cap').html(value.mother_email);
                $("#mother_email_div").show();
            } else {
                $("#mother_email_div").hide();
            }

            if (value.student_email  && value.student_email != '') {
                //document.getElementById("student_email_chk").value = value.student_email;
                document.getElementById("student_email_chk").checked = true;
                $('#student_email_cap').html(value.student_email);
                $("#student_email_div").show();
            } else {
                $("#student_email_div").hide();
            }
        } else {

            $("#student_email_div").hide();
            document.getElementById("student_email_chk").value = "";
            if (value.primary_email && value.primary_email != '') {
                document.getElementById("father_email_chk").checked = true;
                $('#father_email_cap').html(value.primary_email);
                $("#father_email_div").show();
            } else {
                $('#father_email_cap').html('');
                $("#father_email_div").hide();
            }

            if (value.secondary_email && value.secondary_email != '') {
                document.getElementById("mother_email_chk").checked = true;
                $('#mother_email_cap').html(value.secondary_email);
                $("#mother_email_div").show();
            } else {
                $('#mother_email_cap').html('');
                $("#mother_email_div").hide();
            }
        
        }

    });
    if (find_flag == 0) {
        document.getElementById("father_email_chk").checked = false;
        document.getElementById("mother_email_chk").checked = false;
        document.getElementById("student_email_chk").checked = false;

        $("#father_email_div").hide();
        $("#mother_email_div").hide();
        $("#student_email_div").hide();
    }
    //send email dialog -->
   
    //$('body').append(modalHtml);
    $("#email_list_modal").modal('show');
}


function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
} 

function SendInvoiceEmail(p_template_code, p_inv_auto_id, p_inv_file, p_email ='',p_school_id=0){
    var emails='',email_cc='',email_bcc='',email_subject='',email_body='',data='';
    var p_attached_file ='';
    var client_id='',client_name='',client_lastname='',client_firstname='',invoice_price='',invoice_filename='';
    
    $.ajax({
        url: BASE_URL + '/pay_reminder_email',
        data: 'type=fetch_email_inv_detail&template_code='+p_template_code+'&p_inv_auto_id='+p_inv_auto_id+'&p_inv_file='+p_inv_file+'&p_email='+p_email+'&p_school_id='+p_school_id,
        type: 'POST',                     
        dataType: 'json',
        //async: false,
        beforeSend: function( xhr ) {
            $("#pageloader").show();
        },
        success: function(data) {
            console.log(data);
            $("#pageloader").hide();
            successModalCall("email_sent",'','120px','100px'); // suppress as requested by vanessa - on 9th Apr 2018
            // $.each(data, function(key,value){
            //     email_subject=value.subject_text;
            //     email_body=value.body_text;
            //     if (p_email !='') {
            //         emails=p_email;
            //     } else {
            //         emails=value.emails;
            //     }
                
            //     client_name=value.client_name;
            //     invoice_filename=value.invoice_filename;
			// });
        },   // sucess
        error: function(ts) { 
            $("#pageloader").hide();
            alert(ts.responseText+'sorry for the inconvenience caused.')
            //alert(ts.responseText+' email template='+p_template_code) 
        }
    });
    
    return true;    
}   //END - SendInvoiceEmail