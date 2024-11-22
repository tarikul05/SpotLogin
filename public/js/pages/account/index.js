$('#invoiceFilter').on('change', function() {
    var url = this.value;
    window.open(url, '_blank');
});

$(document).ready(function(){
    $('#buttonCancelSubscription').on('click', function() {
      $('#cancel_subscription').modal("show")
    })
});

$(document).ready(function(){
  $('#buttonReactivateSubscription').on('click', function() {
    $('#reactivate_subscription').modal("show")
  })
});

$(document).ready(function(){
  $('#openNewPaymentMethod').on('click', function() {
    $('#newPaymentMethod').modal("show")
  })
});





