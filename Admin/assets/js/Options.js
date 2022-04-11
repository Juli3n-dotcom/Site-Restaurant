$(document).ready(function () {

  /*
  * # --> View data Catégorie
  * ## --> Modification de l'option d'affichage des images pour les catégories de plats
  * ### --> Modification de l'option d'affichage des descriptions pour les catégories de plats
  * #### --> Modification de l'option d'affichage du nombre de pieces pour les catégories de plats
  * #### --> Modification de l'option d'affichage des sous catégories de plats
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
  update_desc_plat();
  function update_desc_plat(){
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
  update_p_plat();
  function update_p_plat(){
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
  
  //#### --> Modification de l'option d'affichage des sous catégories de plats
  $(document).on('click', '#sub_cat_est_publie', function(e){
  var publie = $(this).val();
  update_subcat_plat();
  function update_subcat_plat(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdateSubCat.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#plats_menu').html(data.menu);
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
  * ### --> Modification de l'option d'affichage des stats de plats
  * #### --> Modification de l'option d'affichage de la mise en avant des plats
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
  update_plat_photo();
  function update_plat_photo(){
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
  
  // ### Modification de l'option d'affichage des stats de plats
 $(document).on('click', '#plat_stats_est_publie', function(e){
  var publie = $(this).val();
  update_stats_photo();
  function update_stats_photo(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdatePlatsStats.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#plats').html(data.resultat);
            }else{
              $('#notif').html(data.notif);   
            }  
    }, 'json');
}
});

//#### --> Modification de l'option d'affichage de la mise en avant des plats
  $(document).on('click', '#plat_en_avant_est_publie', function(e){
  var publie = $(this).val();
  update_cat_photo();
  function update_cat_photo(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdatePlatsEnAvant.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#plats').html(data.resultat);  
            }else{
                $('#notif').html(data.notif);  
            }  
    }, 'json');
}
});
  

  /*
  * # --> View data catégorie boissons
  * ## --> Modification de l'option d'affichage des images pour les catégories de boissons
  * ### --> Modification de l'option d'affichage des descriptions pour les catégories de boissons
  * #### --> Modification de l'option d'affichage des sous catégories de boissons
  */
  
  // # affichage 
  load_data_drinks_cat();
  function load_data_drinks_cat(page) {
    $.ajax({
      url: "Assets/Src/Options/DrinksCatAffichage.php",
      method: "post",
      data: { page: page },
      success: function (data) {
        $('#cat_drinks').html(data);
      }
    })
  }

  // ## Modification de l'option d'affichage des images pour les catégories de boissons
 $(document).on('click', '#show_cat_drink_photo', function(e){
  var publie = $(this).val();
  update_drinks_cat_photo();
  function update_drinks_cat_photo(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdateCatDrinksPhoto.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#cat_drinks').html(data.resultat);             
            }else{
              $('#notif').html(data.notif);   
            } 
    }, 'json');
}
});

// ### Modification de l'option d'affichage des descriptions pour les catégories de boissons
 $(document).on('click', '#show_cat_drink_description', function(e){
  var publie = $(this).val();
  update_desc_cat_drinks();
  function update_desc_cat_drinks(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdateDrinksCatDesc.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#cat_drinks').html(data.resultat);             
            }else{
              $('#notif').html(data.notif);                
            }     
    }, 'json');
}
 });
  
  //#### --> Modification de l'option d'affichage des sous catégories de boissons
  $(document).on('click', '#show_drink_sous_cat', function(e){
  var publie = $(this).val();
  update_subcat_drinks();
  function update_subcat_drinks(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdateDrinksSubCat.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#drinks_menu').html(data.menu);
              $('#cat_drinks').html(data.resultat);
              if (data.subcat == true) {
                $('#options__sub__cat__drinks').removeClass('dnone');
              } else {
                 $('#options__sub__cat__drinks').addClass('dnone');
             }
            }else{
              $('#notif').html(data.notif);   
            }  
    }, 'json');
}
  });
  
/*
  * # --> View data sous catégorie boissons
  * ## --> Modification de l'option d'affichage des images pour les sous catégories de boissons
  * ### --> Modification de l'option d'affichage des descriptions pour les sous catégories de boissons
  */
  
  
// # --> View data sous catégorie boissons
  // # affichage 
  load_data_drinks_subcat();
  function load_data_drinks_subcat(page) {
    $.ajax({
      url: "Assets/Src/Options/DrinksSubCatAffichage.php",
      method: "post",
      data: { page: page },
      success: function (data) {
        $('#subcat_drinks').html(data);
      }
    })
  }


  // ## Modification de l'option d'affichage des images pour les sous catégories de boissons
 $(document).on('click', '#show_sub_cat_drink_photo', function(e){
  var publie = $(this).val();
  update_drinks_subcat_photo();
  function update_drinks_subcat_photo(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdateSubCatDrinksPhoto.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#subcat_drinks').html(data.resultat);             
            }else{
              $('#notif').html(data.notif);   
            } 
    }, 'json');
}
});

// ### Modification de l'option d'affichage des descriptions pour les sous catégories de boissons
 $(document).on('click', '#show_sub_cat_drink_description', function(e){
  var publie = $(this).val();
  update_desc_subcat_drinks();
  function update_desc_subcat_drinks(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdateDrinksSubCatDesc.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#subcat_drinks').html(data.resultat);             
            }else{
              $('#notif').html(data.notif);                
            }     
    }, 'json');
}
 });
  
  
/*
  * # --> View data boissons
  * ## --> Modification de l'option d'affichage des images pour les boissons
  * ### --> Modification de l'option d'affichage des stats de boissons
  * #### --> Modification de l'option d'affichage de la mise en avant des boissons
  */
  
  // # affichage 
  load_data_drinks();
  function load_data_drinks(page) {
    $.ajax({
      url: "Assets/Src/Options/DrinksAffichage.php",
      method: "post",
      data: { page: page },
      success: function (data) {
        $('#drinks').html(data);
      }
    })
  }
  
// ## Modification de l'option d'affichage des images des plats
 $(document).on('click', '#drinks_photo_est_publie', function(e){
  var publie = $(this).val();
  update_drinks_photo();
  function update_drinks_photo(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdateDrinksPhoto.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#drinks').html(data.resultat);
            }else{
                $('#notif').html(data.notif);   
            }  
    }, 'json');
}
 });
  
  // ### Modification de l'option d'affichage des stats de boissons
 $(document).on('click', '#drinks_stats_est_publie', function(e){
  var publie = $(this).val();
  update_drinks_stats();
  function update_drinks_stats(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdateDrinksStats.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#drinks').html(data.resultat);
            }else{
              $('#notif').html(data.notif);   
            }  
    }, 'json');
}
});

//#### --> Modification de l'option d'affichage de la mise en avant des boissons
  $(document).on('click', '#drinks_en_avant_est_publie', function(e){
  var publie = $(this).val();
  update_drins_en_avant();
  function update_drins_en_avant(){
    var parameters = "publie="+publie;
    $.post('Assets/Src/Options/UpdateDrinksEnAvant.php', parameters, function(data){
            if(data.status == true){ 
              $('#notif').html(data.notif);
              $('#drinks').html(data.resultat);  
            }else{
                $('#notif').html(data.notif);  
            }  
    }, 'json');
}
});
});