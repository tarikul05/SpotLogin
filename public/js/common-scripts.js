function successModalCall(title, desc = '') {
  $('#successModal').remove();
  var modalHtml = '';
  modalHtml = `<div class="modal fade" id="successModal" name="successModal">
        <div class="modal-dialog mt-5">
            <div class="modal-content">
                <div class="modal-body text-center p-4">                    
                    <h4 class="light-blue-txt gilroy-bold">` + title + `</h4>
                    <p style="font-size: 20px;">` + desc + `</p>
                    <button type="button" class="btn btn-primary gilroy-medium" data-dismiss="modal" style="width:188px;">OK</button>
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
                        <button type="button" class="btn btn-primary gilroy-medium" data-dismiss="modal" style="width:188px;">OK</button>
                    </div>
                </div>
            </div>
        </div>`;
  //alert(modalHtml);
  $('body').append(modalHtml);
  $("#errorModal").modal('show');
}