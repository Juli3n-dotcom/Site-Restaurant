$(document).ready(function () { 

var reader = new FileReader();
var img;

function readURL(input) {
  if (input.files && input.files[0]) {
      
      reader.onload = function (e) {
          $('.img-profil').attr('src', e.target.result);
          img = e.target.value;
      }

      reader.readAsDataURL(input.files[0]);
  }
}

$(document).on('change',"#new_img", function (e) {
  readURL(this);
  img = e.target.value;
});
  
  
  $(document).on('submit', '#update_profil', function (e) {
  e.preventDefault();

  $.ajax({
    type: 'POST',
    url: 'Assets/Src/Team/UpdateProfil.php',
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
        $('#member_menu-action').html(data.menu);
        $('#profile_details').html(data.profil);
        $('#container_profil').html(data.resultat);
        $('#load-update').addClass('hide').fadeOut(1000);
      }else{
        $('#notif').html(data.notif); 
        $('#load-update').addClass('hide').fadeOut(1000);
      } 
    }  
  });
  });


  // ### delete cat ajax
$('#delete_photo').on('click', function(e){
  e.preventDefault();
  delete_photo();
  function delete_photo(){
    var photo_id = $('#delete_photo').val();
    var parameters = "photo_id=" + photo_id;
    $.post('Assets/Src/Team/DeletePhoto.php', parameters, function(data){

            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#member_menu-action').html(data.menu);
              $('#profile_details').html(data.profil);
              $('#container_profil').html(data.resultat);
            }           
    }, 'json');
}
}); 
  

  
});