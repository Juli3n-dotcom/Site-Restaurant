$(document).ready(function () {
  /*
  * --> View data
  */
  load_data();
  function load_data(page) {

    $.ajax({
      url: "Assets/Src/Drinks/AffichageDrinks.php",
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
 * --> Add boisson
 * 
 * # Ouverture du Modal d'ajout
 *   
 * ## activation des allergenes
 * 
 * ### traitement Ajax de l'ajout
 * 
 * #### Reset BTN
 */

    //modal d'ajout 
    $('#add_drink').on('click', function () {
        $('#addmodal').modal('show');
    });
  
  //modal d'ajout drink min
    $('#add_drink_min').on('click', function () {
        $('#addmodal').modal('show');
    });
  
  $('select#cat').change(function (e) {
    
    var selectcat = $(this).children("option:selected").val();
  
    $.ajax({
      method: 'post',
      url: 'Assets/Src/Drinks/LoadSubCat.php',
      data: { cat_id: selectcat }
    }).done(function (subcat) {
      subcat = JSON.parse(subcat)
      $('#sous_cat').empty();
            subcat.forEach(function(subcat){
                $('#sous_cat').append('<option value="'+subcat.id+'">'+subcat.titre+'</option>')
            });
      });
  })
  

//  ### activation des allergenes
  $('#haveAllergene').on('click', function () {

    if ($(this).is(':checked')) {
      $('#allergenecontainer').addClass('show')
    } else {
      $('#allergenecontainer').removeClass('show')

    }
  });

    // ## ajout plat ajax
  $("#add_drink_form").on('submit', function (e) {
  
  e.preventDefault();

    $.ajax({

      type: 'POST',
      url: 'Assets/Src/Drinks/AddDrinks.php',
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
          
          $('#add_drink_form').trigger("reset");
          $('#notif').html(data.notif);
          $('#addmodal').modal('hide');
          $('#nbdrinks').html(data.nbplats);
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
    $("#add_plat_form")[0].reset();
  });


/*
 * --> Delete boissons
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

    $('#deletedrink').prop("disabled", false).removeClass('disabledBtn').addClass('deleteBtn');

  } else {

    $('#deletedrink').prop("disabled", true).addClass('disabledBtn').removeClass('deleteBtn');

  }
});


//### delete  ajax
$('#delete_drink').on('submit', function(e){


  e.preventDefault();
  delete_drink();

  function delete_drink(){

    var id = $('#delete_id').val();
    var confirme = $('#confirmedelete').val();

    var parameters = "id=" + id + '&confirmedelete=' + confirme;

    
    $.post('Assets/Src/Drinks/DeleteDrinks.php', parameters, function(data){

            if(data.status == true){ 
              
              $('#delete_drink').trigger("reset");
              $('#notif').html(data.notif);
              $('#nbdrinks').html(data.nbplats);
              $('#deletemodal').modal('hide');
              $('#table').html(data.resultat); 
                
            }else{

              $('#notif').html(data.notif); 
             

            } 
                
    }, 'json');

}

});
  
  /*
 * --> view boisson
 * 
 * # Ouverture du Modal de vue
 * 
 */

// # Ouverture du Modal de vue
$(document).on('click','.viewbtn', function(){  
  var drink_id = $(this).attr("id");  
  $.ajax({  
       url:"Assets/Src/Drinks/ViewDrink.php",  
       method:"post",  
       data:{drink_id:drink_id},  
       success:function(data){  
            $('#drink_detail').html(data);  
            $('#viewmodal').modal("show");  
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

    var id = data[0];
    var parameters = "id="+id + "&publie="+publie;
    
    $.post('Assets/Src/Drinks/UpdatePublie.php', parameters, function(data){

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
 * --> Update mit en avant
 *
 * # traitement Ajax de la modification
 */

// # traitement de la publication
 $(document).on('click', '.est_en_avant', function(e){
  

  $tr = $(this).closest('tr');
  
  let data = $tr.children('td').map(function () {

    return $(this).text()

  }).get();

  var est_en_avant = $(this).val();
  
  update_est_en_avant();

  function update_est_en_avant(){

    var id = data[0];
    var parameters = "id="+id + "&est_en_avant="+est_en_avant;
   
    $.post('Assets/Src/Drinks/UpdateEnAvant.php', parameters, function(data){

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
 * --> Update boissons
 * 
 * # Ouverture du Modal d'edition
 * 
 * ## chargement de l'image en update
 * 
 * ### traitement Ajax de la modification
 */

    
  //# Ouverture du Modal de vue
 $(document).on('click','.editbtn', function(){  
    var drink_id = $(this).attr("id");  
  
    $.ajax({  
          url:"Assets/Src/Drinks/ModalUpdatedrink.php",  
          method:"post",  
          data: { drink_id: drink_id },
          dataType: 'json', 
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
  
  $(document).on('change', '#update_cat',function (e) {
    
    var selectupdatecat = $(this).children("option:selected").val();
    
  $.ajax({
      method: 'post',
      url: 'Assets/Src/Drinks/LoadSubCat.php',
      data: {cat_id: selectupdatecat }
    }).done(function (subcat) {
      subcat = JSON.parse(subcat)
      $('#update_souscat').empty();
            subcat.forEach(function(subcat){
                $('#update_souscat').append('<option value="'+subcat.id+'">'+subcat.titre+'</option>')
            });
      });
    
  })

// checked allergeneContainer
$(document).on('change', '#update_haveallergene', function () {
    if ($(this).is(':checked')) {
      $('#update_allergenecontainer').addClass('show')
    } else {
      $('#update_allergenecontainer').removeClass('show')
    }
});


// ### update plat ajax
$(document).on('submit', '#update_drink', function(e){
  e.preventDefault();

  $.ajax({

    type: 'POST',
    url: 'Assets/Src/Drinks/UpdateDrink.php',
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
        
        $('#update_drink').trigger("reset");
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
