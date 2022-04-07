$(document).ready(function () { 

  // checked epice
$(document).on('change', '#update_est_epice', function () {
  
  if ($(this).is(':checked')) {
    $('#update_epicelevel').addClass('show')
  } else {
    $('input[name=updateepicelevel]').attr('checked',false);
    $('#update_epicelevel').removeClass('show')
    }
});
  
  $(document).on('change', '#update_haveallergene', function () {
  
  if ($(this).is(':checked')) {
    $('#update_allergenecontainer').addClass('show')
  } else {
    $('#update_allergenecontainer').removeClass('show')
    }
  });
  
  $(document).on('change', '#update_cat',function (e) {
    
    var selectupdatecat = $(this).children("option:selected").val();
    
  $.ajax({
      method: 'post',
      url: 'Assets/Src/Plats/LoadSubCat.php',
      data: {cat_id: selectupdatecat }
    }).done(function (subcat) {
      subcat = JSON.parse(subcat)
      $('#update_souscat').empty();
            subcat.forEach(function(subcat){
                $('#update_souscat').append('<option value="'+subcat.id+'">'+subcat.titre+'</option>')
            });
      });
    
  })

  var reader = new FileReader();
var img;
// ## view logo if change
function readURL(input) {
  if (input.files && input.files[0]) {
      
      reader.onload = function (e) {
          $('.img-plat').attr('src', e.target.result);
          img = e.target.value;
      }

      reader.readAsDataURL(input.files[0]);
  }
}

$(document).on('change',"#new_img", function (e) {
  readURL(this);
  img = e.target.value;
});


  $(document).on('submit', '#update_plat', function (e) {
  e.preventDefault();

  $.ajax({
    type: 'POST',
    url: 'Assets/Src/Plats/FicheUpdatePlat.php',
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
        $('#load-update').addClass('hide').fadeOut(1000);
      }else{
        
        $('#notif').html(data.notif); 
        $('#load-update').addClass('hide').fadeOut(1000);
        console.log(data.test);
        
      } 
    }  
  });
});
  
});