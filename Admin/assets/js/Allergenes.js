$(document).ready(function () {

  
  /*
  * --> View data
  */
  load_data();
  function load_data(page) {

    $.ajax({
      url: "Assets/Src/Allergenes/AffichageAllergenes.php",
      method: "post",
      data: { page: page },
      success: function (data) {
        $('#table').html(data);
      }
    })

  }

  $(document).on('click', '.pagination_link', function () {
    var page = $(this).attr('id');
    load_data(page);
  })


  /*
 * --> Add Allergenes
 * 
 * # Ouverture du Modal d'ajout
 * 
 * ## traitement Ajax de l'ajout
 * 
 * ### Reset BTN
 */

    //modal d'ajout 
    $('#add_aller').on('click', function () {
        $('#addmodal').modal('show');
    });
  
  //modal d'ajout  min
    $('#add_aller_min').on('click', function () {
        $('#addmodal').modal('show');
    });

    // ## ajout  ajax
$("#add_allergene").on('submit', function(e){

  e.preventDefault();

    $.ajax({

      type: 'POST',
      url: 'Assets/Src/Allergenes/AddAllergenes.php',
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $('#footer-action').hide().fadeOut(1000)
        $('#load-add').removeClass('hide').fadeIn(1000);
      },
      success: function(data){

        if (data.status == true) {   
          
          $('#add_allergene').trigger("reset");
          $('#notif').html(data.notif);
          $('#addmodal').modal('hide');
          $('#table').html(data.resultat); 
          $('#load-add').addClass('hide').fadeOut(1000);
          $('#footer-action').show().fadeIn(1000)
   
        }else{
          
          $('#notif').html(data.notif);
          $('#load-add').addClass('hide').fadeOut(1000);
          $('#footer-action').show().fadeIn(1000)
           
        } 

      }
    });
});
  
  $('#resetBtn').on('click', function() {
    $("#add_allergene")[0].reset();
  });

/*
 * --> Delete Allergenes
 * 
 * # Ouverture du Modal de suppresion
 * 
 * ## Gestion de la confirmation de suppression
 * Désactivation du BTN si l'utilisateur n'a pas confirmé la suppression
 * 
 * ### traitement Ajax de la suppression
 */
  
 
// # modal delete 
$(document).on('click','.deletebtn', function () {

  $('#deletemodal').modal('show');
  
  $tr = $(this).closest('tr');
  
  let data = $tr.children('td').map(function () {

    return $(this).text()

  }).get();

  
  $('#delete_id').val(data[0]);
  
  
  
});

// ## confirmation de la validation de suppression
$('#confirmedelete').on('click', function () {
  if ($(this).is(':checked')) {

    $('#deletealler').prop("disabled", false).removeClass('disabledBtn').addClass('deleteBtn');

  } else {

    $('#deletealler').prop("disabled", true).addClass('disabledBtn').removeClass('deleteBtn');

  }
});


//### delete  ajax
$('#delete_aller').on('submit', function(e){


  e.preventDefault();
  delete_aler();

  function delete_aler(){

    var id = $('#delete_id').val();
    var confirme = $('#confirmedelete').val();

    var parameters = "id=" + id + '&confirmedelete=' + confirme;

    
    $.post('Assets/Src/Allergenes/DeleteAllergenes.php', parameters, function(data){

            if(data.status == true){ 
              
              $('#delete_aller').trigger("reset");
              $('#notif').html(data.notif);
              $('#deletemodal').modal('hide');
              $('#table').html(data.resultat); 
                
            }else{

              $('#notif').html(data.notif); 
             

            } 
                
    }, 'json');

}

});
  
  /*
 * --> view Allergene
 * 
 * # Ouverture du Modal de vue
 * 
 */

// # Ouverture du Modal de vue
$(document).on('click','.viewbtn', function(){  
  var aller_id = $(this).attr("id");  
  $.ajax({  
       url:"Assets/Src/Allergenes/ViewAllergene.php",  
       method:"post",  
       data:{aller_id:aller_id},  
       success:function(data){  
            $('#aller_detail').html(data);  
            $('#viewmodal').modal("show");  
       }  
  });  
}); 
  
  
/*
 * --> Update Allergenes
 * 
 * # Ouverture du Modal d'edition
 * 
 * ## traitement Ajax de la modification
 */

    
  //# Ouverture du Modal de vue
 $(document).on('click','.editbtn', function(){  
    var aller_id = $(this).attr("id");  
  
    $.ajax({  
         url:"Assets/Src/Allergenes/ModalUpdateAllergenes.php",  
         method:"post",  
         data:{aller_id:aller_id},  
         success:function(data){  
              $('#update_modal').html(data);  
              $('#editmodal').modal("show");  
         }  
    });  
 });  
  


// ## update Allergene ajax
$(document).on('submit', '#update_aller', function(e){
  e.preventDefault();

  $.ajax({

    type: 'POST',
    url: 'Assets/Src/Allergenes/UpdateAllergenes.php',
    data: new FormData(this),
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function () {
        $('.modal-footer').hide().fadeOut(1000)
        $('#load-update').removeClass('hide').fadeIn(1000);
      },
    success: function(data){

      if(data.status == true){    
        
        $('#update_aller').trigger("reset");
        $('#notif').html(data.notif);
        $('#editmodal').modal('hide');
        $('#table').html(data.resultat); 
        $('#load-update').addClass('hide').fadeOut(1000);
        $('.modal-footer').show().fadeIn(1000)
      }else{
        
        $('#notif').html(data.notif); 
        $('#load-update').addClass('hide').fadeOut(1000);
        $('.modal-footer').show().fadeIn(1000)
      } 

    }
    
  });

});


});