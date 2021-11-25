$(document).ready(function () {


  /*
  * --> View data
  */
  load_data();
  function load_data(page) {

    $.ajax({
      url: "assets/src/team/AffichageTeam.php",
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
 * --> Add member
 * 
 * # Ouverture du Modal d'ajout
 * 
 * ## traitement Ajax de l'ajout
 * 
 * ### Reset BTN
 */

    //modal d'ajout team member
    $('#add_team_member').on('click', function () {
        $('#addmodal').modal('show');
    });

    // ## ajout membre ajax
$("#add_member").on('submit', function(e){

    e.preventDefault();

    $.ajax({

      type: 'POST',
      url: 'assets/src/team/AddTeamMember.php',
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,
      success: function(data){

        if(data.status == true){    
          
          $('#add_member').trigger("reset");
          $('#notif').html(data.notif);
          $('#addmodal').modal('hide');
          $('#table').html(data.resultat); 
                
        }else{
          console.log(data.test);
          $('#notif').html(data.notif);
          
         
        } 

      }
    });
});
  
  $('#resetBtn').on('click', function() {
    $("#add_member")[0].reset();
  });

/*
 * --> View member
 * 
 * # Ouverture du Modal de vue
 *
 */

// # Ouverture du Modal de vue
$(document).on('click','.viewbtn', function(){  
  var member_id = $(this).attr("id");  

  $.ajax({  
       url:"assets/src/team/ViewTeamMember.php",  
       method:"post",  
       data:{member_id:member_id},  
       success:function(data){  
            $('#member_detail').html(data);  
            $('#viewmodal').modal("show");  
       }  
  });  
});  


/*
 * --> Delete member
 * 
 * # Ouverture du Modal de suppresion
 * 
 * ## Gestion de la confirmation de suppression
 * Désactivation du BTN si l'utilisateur n'a pas confirmé la suppression
 * 
 * ### traitement Ajax de la suppression
 */


// # modal delete langage
$(document).on('click','.deletebtn', function () {

  $('#deletemodal').modal('show');
  
  $tr = $(this).closest('tr');
  
  let data = $tr.children('td').map(function () {

    return $(this).text()

  }).get();
  
  $('#delete_id').val(data[0]);
  $('#delete_img').val(data[1]);
  
});

// ## confirmation de la validation de suppression
$('#confirmedelete').on('click', function () {
  if ($(this).is(':checked')) {

    $('#deletemember').prop("disabled", false).removeClass('disabledBtn').addClass('deleteBtn');

  } else {

    $('#deletemember').prop("disabled", true).addClass('disabledBtn').removeClass('deleteBtn');

  }
});


// ### delete cat ajax
$('#delete_member').on('submit', function(e){

  

  e.preventDefault();
  delete_member();

  function delete_member(){

    var id = $('#delete_id').val();
    var confirme = $('#confirmedelete').val();
    var parameters = "id="+ id + '&confirmedelete=' + confirme;

    $.post('assets/src/team/DeleteTeamMember.php', parameters, function(data){

            if(data.status == true){ 

              $('#delete_member').trigger("reset");
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
 * --> Update Team Member
 * 
 * # Ouverture du Modal d'edition
 * 
 * ## traitement Ajax de la modification
 */

    
    // # Ouverture du Modal de vue
 $(document).on('click','.editbtn', function(){  
    var team_id = $(this).attr("id");  
  
    $.ajax({  
         url:"assets/src/team/ModalUpdateTeamMember.php",  
         method:"post",  
         data:{team_id:team_id},  
         success:function(data){  
              $('#update_modal').html(data);  
              $('#editmodal').modal("show");  
         }  
    });  
  });  

  // ## update member ajax
$(document).on('submit', '#update_member', function(e){
  e.preventDefault();

  $.ajax({

    type: 'POST',
    url: 'assets/src/team/UpdateTeamMemberScrip.php',
    data: new FormData(this),
    dataType: 'json',
    contentType: false,
    cache: false,
    processData:false,
    success: function(data){

      if(data.status == true){    
        
        $('#update_member').trigger("reset");
        $('#notif').html(data.notif);
        $('#editmodal').modal('hide');
        $('#table').html(data.resultat); 
       
      }else{
        
        $('#notif').html(data.notif); 

      } 

    }
    
  });

});



});