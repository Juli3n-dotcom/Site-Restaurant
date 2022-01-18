$(document).ready(function () {
/*
 * --> Help
 * 
 * # Open Modal 
 * 
 * ## Traitement Ajax de la requete
 */
  
  
  $(document).on('click', '.help__btn', function (e) {
    e.preventDefault();
    $('#helpmodal').modal("show");
  });
  
  
  $("#help__form").on('submit', function (e) {
    
    e.preventDefault();

    $.ajax({

      type: 'POST',
      url: 'Assets/Src/Help/HelpScript.php',
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,
      success: function(data){

        if(data.status == true){    
          
          $('#notif').html(data.notif);
          $('#helpmodal').modal('hide');
          
        } else {

           $('#notif').html(data.notif);

      }
      }
    });
  });

  
});