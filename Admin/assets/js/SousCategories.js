$(document).ready(function () { 

  /*
  * --> View data
  */
  load_data();
  function load_data(page) {

    $.ajax({
      url: "Assets/Src/SousCategories/AffichageSousCategories.php",
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
 * --> Add Sous Cat
 * 
 * # Ouverture du Modal d'ajout
 * 
 * ## traitement Ajax de l'ajout
 * 
 * ### Reset BTN
 */

    //modal d'ajout 
    $('#add_cat').on('click', function () {
        $('#addmodal').modal('show');
    });
  
  //modal d'ajout team member min
    $('#add_cat_min').on('click', function () {
        $('#addmodal').modal('show');
    });

    // ## ajout membre ajax
$("#add_cat_form").on('submit', function(e){

  e.preventDefault();

    $.ajax({

      type: 'POST',
      url: 'Assets/Src/SousCategories/AddSousCategories.php',
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
          
          $('#add_cat_form').trigger("reset");
          $('#notif').html(data.notif);
          $('#addmodal').modal('hide');
          $('#table').html(data.resultat);
          $('#nbsubcats').html(data.nbsubcat);
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
    $("#add_cat_form")[0].reset();
  });


   /*
 * --> view sous catégorie
 * 
 * # Ouverture du Modal de vue
 * 
 */

// # Ouverture du Modal de vue
$(document).on('click','.viewbtn', function(){  
  var cat_id = $(this).attr("id");  
  $.ajax({  
       url:"Assets/Src/SousCategories/ViewSousCategorie.php",  
       method:"post",  
       data:{cat_id:cat_id},  
       success:function(data){  
            $('#cat_detail').html(data);  
            $('#viewmodal').modal("show");  
       }  
  });  
}); 
  
  
  /*
 * --> Delete Sous Categorie
 * 
 * # Ouverture du Modal de suppresion
 * 
 * ## Gestion de la confirmation de suppression
 * Désactivation du BTN si l'utilisateur n'a pas confirmé la suppression
 * 
 * ### traitement Ajax de la suppression
 */
  
 
// # modal delete team member
$(document).on('click','.deletebtn', function () {

  $('#deletemodal').modal('show');
  
  $tr = $(this).closest('tr');
  
  let data = $tr.children('td').map(function () {

    return $(this).text()

  }).get();

  
  $('#delete_id').val(data[1]);
  

  
});

// ## confirmation de la validation de suppression
$('#confirmedelete').on('click', function () {
  if ($(this).is(':checked')) {

    $('#deletecat').prop("disabled", false).removeClass('disabledBtn').addClass('deleteBtn');

  } else {

    $('#deletecat').prop("disabled", true).addClass('disabledBtn').removeClass('deleteBtn');

  }
});


// ### delete cat ajax
$('#delete_cat').on('submit', function(e){


  e.preventDefault();
  delete_cat();

  function delete_cat(){

    var id = $('#delete_id').val();
    var confirme = $('#confirmedelete').val();

    var parameters = "id=" + id + '&confirmedelete=' + confirme;

    
    $.post('Assets/Src/SousCategories/DeleteSousCategories.php', parameters, function(data){

            if(data.status == true){ 
              
              $('#delete_cat').trigger("reset");
              $('#notif').html(data.notif);
              $('#deletemodal').modal('hide');
              $('#nbsubcats').html(data.nbsubcat);
              $('#table').html(data.resultat); 
                
            }else{

              $('#notif').html(data.notif); 
            } 
                
    }, 'json');

}

});
  
  /*
 * --> Update Categorie
 * 
 * # Ouverture du Modal d'edition
 * 
 * ## chargement de l'image en update
 * 
 * ### traitement Ajax de la modification
 */

    
  //# Ouverture du Modal de vue
 $(document).on('click','.editbtn', function(){  
    var cat_id = $(this).attr("id");  
  
    $.ajax({  
         url:"Assets/Src/SousCategories/ModalUpdateSousCategories.php",  
         method:"post",  
         data:{cat_id:cat_id},  
         success:function(data){  
              $('#update_modal').html(data);  
              $('#editmodal').modal("show");  
         }  
    });  
 });  
  
var reader = new FileReader();
var img;
// ## view logo if change
function readURL(input) {
  if (input.files && input.files[0]) {
      

      reader.onload = function (e) {
          $('.img-cat').attr('src', e.target.result);
          img = e.target.value;
      }

      reader.readAsDataURL(input.files[0]);
  }
}

$(document).on('change',"#new_img", function (e) {
  readURL(this);
  img = e.target.value;
});

// ### update sous catégorie ajax
$(document).on('submit', '#update_cat', function(e){
  e.preventDefault();

  $.ajax({

    type: 'POST',
    url: 'Assets/Src/SousCategories/UpdateSousCategories.php',
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
        
        $('#update_cat').trigger("reset");
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
  
/*
 * --> Update Publication
 *
 * # traitement Ajax de la modification
 */

// # traitement de la publication
 $(document).on('click', '.est_publie', function(e){
  

  $tr = $(this).closest('tr');
  
  let data = $tr.children('td').map(function () {

    return $(this).text()

  }).get();

  var publie = $(this).val();
  
  update_est_publie();

  function update_est_publie(){

    var id = data[1];
    var parameters = "id="+id + "&publie="+publie;

    $.post('Assets/Src/SousCategories/UpdatePublie.php', parameters, function(data){

            if(data.status == true){ 

                $('#notif').html(data.notif);
                 $('#table').html(data.resultat); 
                
            }else{

                $('#notif').html(data.notif); 

            } 
                
    }, 'json');

}

 });
  
  /*
* --> Update Position
*
* # création du retour json
*
* ## traitement Ajax de la modification
 */
 
    
  $('#table').sortable({
    items: 'tbody > tr',
    update: function (event, ui) {
      dataPosition()
      function dataPosition() {
        parameters = [];
        $('tbody tr').each(function () {
          var position = $(this).data('position');
          var id = $(this).data('id');
          var newPos = $(this).index();
     
          item = {}
          item["id"] = id;
          item["position"] = position;
          item["newPos"] = ++newPos;

          parameters.push(item);
          return parameters;
        });
      };


      var data = JSON.stringify(parameters);
      
     
      $.ajax({  
        url:"Assets/Src/SousCategories/UpdatePositionSousCategories.php",  
        method: "POST", 
        dataType: 'JSON',
        data: {data: data},  
        success: function (data) {  
           
          if(data.status == true){ 

                $('#notif').html(data.notif);
                 $('#table').html(data.resultat); 
                
            }else{

            $('#notif').html(data.notif); 
            

            } 
             
         }  
    });  
    }
  });
  

});
