$(document).ready(function () { 

  var reader = new FileReader();
var img;

function readURL(input) {
  if (input.files && input.files[0]) {
      
      reader.onload = function (e) {
          $('.img__logo').attr('src', e.target.result);
          img = e.target.value;
      }

      reader.readAsDataURL(input.files[0]);
  }
}

$(document).on('change',"#new_img", function (e) {
  readURL(this);
  img = e.target.value;
});
  
  $('#myTab a').on('click', function (e) {
  e.preventDefault()
    $(this).tab('show')
    var currId = $(e.target).attr("id");
    $('#tab_id').val(currId);
  })
  
  
  $(document).on('submit', '#update_settings', function (e) {
  e.preventDefault();

  $.ajax({
    type: 'POST',
    url: 'Assets/Src/Settings/UpdatePreferences.php',
    data: new FormData(this),
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function () {
        $('#load-update').removeClass('hide').fadeIn(1000);
      },
    success: function(data){

      if(data.status == true){    
        $('#notif').html(data.notif);
        $('#options_settings').html(data.resultat);
        $('#load-update').addClass('hide').fadeOut(1000);
        $('#myTab a').on('click', function (e) {
          e.preventDefault()
          $(this).tab('show')
          var currId = $(e.target).attr("id");
          $('#tab_id').val(currId);
        });
      }else{
        $('#notif').html(data.notif); 
        $('#load-update').addClass('hide').fadeOut(1000);
      } 
    }  
  });
  });
});