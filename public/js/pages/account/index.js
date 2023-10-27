$('#invoiceFilter').on('change', function() {
    var url = this.value;
    window.open(url, '_blank');
});

$(document).ready(function(){
    $('#buttonCancelSubscription').on('click', function() {
      $('#cancel_subscription').modal("show")
    })
});
