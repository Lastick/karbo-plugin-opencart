
function KarboUpdate(obj){
  var karbo_info_obj = document.getElementById('payment_status');
  var karbo_tx_conf = obj.tx_conf;
  var lang_payment_wait = obj.lang.text_payment_wait;
  var lang_payment_unconf = obj.lang.text_payment_unconf;
  var lang_payment_conf = obj.lang.text_payment_conf;
  var payment_tx_conf = obj.payment.tx_conf;
  if (karbo_info_obj.style.display == 'none') karbo_info_obj.style.display = 'inline';
  if (payment_tx_conf == 0){
    karbo_info_obj.innerHTML = ' (' + lang_payment_wait + ')';
  }
  if (payment_tx_conf < karbo_tx_conf && payment_tx_conf > 0){
    karbo_info_obj.innerHTML = ' (' + lang_payment_unconf + ': ' + payment_tx_conf + '/' + karbo_tx_conf + ')';
  }
  if (payment_tx_conf >= karbo_tx_conf){
    karbo_info_obj.innerHTML = ' (' + lang_payment_conf + ')';
  }
}

function KarboUpdateInit(){
  if (document.getElementById('payment_status') != null && document.getElementById('karbo_payment_id') != null){
    var payment_id = document.getElementById('karbo_payment_id').innerHTML;
    $.ajax({
      type: "GET",
      url: 'index.php?route=payment/karbo/api&karbo_payment_id=' + payment_id,
      success: function(msg){
        var obj = jQuery.parseJSON(msg);
        if (obj.status){
          KarboUpdate(obj);
        }
      }
    });
  }
  setTimeout(KarboUpdateInit, 5000);
}

$(document).ready(function(){
  KarboUpdateInit();
});

