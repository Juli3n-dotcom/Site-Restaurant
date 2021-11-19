$(document).ready(function () {
  
  /*
  * --> View data
  *
  */
  load_data();
  function load_data(page) {

    $.ajax({
      url: "assets/src/team_statut/affichage_statut.php",
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
* --> Add statut
* 
* # Ouverture du Modal d'ajout
* 
* #### traitement Ajax de l'ajout
*/

// # modal d'ajout statut
$('#add_role_btn').on('click', function () {
  $('#addmodal').modal('show');
});



// #### ajout statut ajax
$("#add_role").on('submit', function(e){

  e.preventDefault();

  $.ajax({

    type: 'POST',
    url: 'assets/src/team_statut/add_statut.php',
    data: new FormData(this),
    dataType: 'json',
    contentType: false,
    cache: false,
    processData:false,
    success: function (data) {
      

      if(data.status == true){    
        
        $('#add_statut').trigger("reset");
        $('#notif').html(data.notif);
        $('#addmodal').modal('hide');
        $('#table').hide().html(data.resultat).fadeIn(); 
       
      }else{
  
        $('#notif').html(data.notif); 
      
      } 

    }
  });
});
  
  
 /*
 * --> Delete statut
 * 
 * # Ouverture du Modal de suppresion
 * 
 * ## Gestion de la confirmation de suppression
 * Désactivation du BTN si l'utilisateur n'a pas confirmé la suppression
 * 
 * ### traitement Ajax de la suppression
 */


// # modal delete statut
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

    $('#deletestatut').prop("disabled", false).removeClass('disabledBtn').addClass('deleteBtn');

  } else {

    $('#deletestatut').prop("disabled", true).addClass('disabledBtn').removeClass('deleteBtn');

  }
});


// ### delete statut ajax
$('#delete_statut').on('submit', function(e){

  e.preventDefault();
  delete_cat();

  function delete_cat(){

    var id = $('#delete_id').val();
    var confirme = $('#confirmedelete').val();
    var parameters = "id="+id + '&confirmedelete=' + confirme;

        
    $.post('assets/src/team_statut/delete_statut.php', parameters, function(data){

            if(data.status == true){ 
              
                $('#delete_statut').trigger("reset"); 
                $('#notif').html(data.notif);
                $('#deletemodal').modal('hide');
                $('#table').hide().html(data.resultat).fadeIn();
                
            }else{

                $('#notif').html(data.notif); 

            } 
                
    }, 'json');

}

});
  
  
/*
 * --> Update statut
 * 
 * # Ouverture du Modal d'edition
 * 
 * ## traitement Ajax de la modification
 */


// # Ouverture du Modal de vue
$(document).on('click','.editbtn', function(){  
  var statut_id = $(this).attr("id");  

  $.ajax({  
       url:"assets/src/team_statut/update_modal.php",  
       method:"post",  
       data:{statut_id:statut_id},  
       success:function(data){  
            $('#update_modal').html(data);  
            $('#editmodal').modal("show");  
       }  
  });  
});  



// ### update statut ajax
$(document).on('submit', '#update_edu', function(e){
    e.preventDefault();

    $.ajax({

      type: 'POST',
      url: 'assets/src/team_statut/update_education_script.php',
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,
      success: function(data){

        if(data.status == true){    

          $('#update_edu').trigger("reset"); 
          $('#notif').html(data.notif);
          $('#editmodal').modal('hide');
          $('#edu_table').hide().html(data.resultat).fadeIn();

        }else{
          
          $('#notif').html(data.notif); 

        } 

      }
    });
  
});

  
  
  
  
  
  
});

