$(document).ready(function () {

  /*
  * # --> View data Catégorie
  * ## --> Modification de l'option d'affichage des images pour les catégories de plats
  * ### --> Modification de l'option d'affichage des descriptions pour les catégories de plats
  * #### --> Modification de l'option d'affichage du nombre de pieces pour les catégories de plats
  */
  
  // # affichage 
  load_data();
  function load_data(page) {

    $.ajax({
      url: "Assets/Src/Options/CategoriesAffichage.php",
      method: "post",
      data: { page: page },
      success: function (data) {
        $('#cat_plat').html(data);
      }
    })

  }

  // ## Modification de l'option d'affichage des images pour les catégories de plats
 $(document).on('click', '#cat_photo_est_publie', function(e){
  

   var publie = $(this).val();
  
  update_cat_photo();

  function update_cat_photo(){

    var parameters = "publie="+publie;

    $.post('Assets/Src/Options/UpdateCatPhoto.php', parameters, function(data){

            if(data.status == true){ 

              $('#notif').html(data.notif);
              $('#cat_plat').html(data.resultat);
              
                
            }else{

                $('#notif').html(data.notif); 
                
            } 
                
    }, 'json');

}

});

// ### Modification de l'option d'affichage des descriptions pour les catégories de plats
 $(document).on('click', '#cat_desc_est_publie', function(e){
  

   var publie = $(this).val();
  
  update_desc_photo();

  function update_desc_photo(){

    var parameters = "publie="+publie;

    $.post('Assets/Src/Options/UpdateCatDesc.php', parameters, function(data){

            if(data.status == true){ 

              $('#notif').html(data.notif);
              $('#cat_plat').html(data.resultat);
              
                
            }else{

                $('#notif').html(data.notif); 
                
            } 
                
    }, 'json');

}

 });
  
  
  // #### Modification de l'option d'affichage du nombre de pieces pour les catégories de plats
 $(document).on('click', '#cat_p_est_publie', function(e){
  

   var publie = $(this).val();
  
  update_p_photo();

  function update_p_photo(){

    var parameters = "publie="+publie;

    $.post('Assets/Src/Options/UpdateCatPieces.php', parameters, function(data){

            if(data.status == true){ 

              $('#notif').html(data.notif);
              $('#cat_plat').html(data.resultat);
              
                
            }else{

                $('#notif').html(data.notif); 
                
            } 
                
    }, 'json');

}

 });
  
  /*
  * # --> View data plats
  * ## --> Modification de l'option d'affichage des images pour les plats
  * ### --> Modification de l'option d'affichage des descriptions pour les catégories de plats
  * #### --> Modification de l'option d'affichage du nombre de pieces pour les catégories de plats
  */
  
  // # affichage 
  load_data_plats();
  function load_data_plats(page) {

    $.ajax({
      url: "Assets/Src/Options/PlatsAffichage.php",
      method: "post",
      data: { page: page },
      success: function (data) {
        $('#plats').html(data);
      }
    })

  }
  
  // ## Modification de l'option d'affichage des images des plats
 $(document).on('click', '#plat_photo_est_publie', function(e){
  

   var publie = $(this).val();
  
  update_cat_photo();

  function update_cat_photo(){

    var parameters = "publie="+publie;

    $.post('Assets/Src/Options/UpdatePlatsPhoto.php', parameters, function(data){

            if(data.status == true){ 

              $('#notif').html(data.notif);
              $('#plats').html(data.resultat);
              
                
            }else{

                $('#notif').html(data.notif); 
                
            } 
                
    }, 'json');

}

});


  


});