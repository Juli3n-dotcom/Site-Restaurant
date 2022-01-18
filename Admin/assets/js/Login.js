$(document).ready(function () {

/*
 * --> Login
 * 
 * # Traitement Ajax du login
 */
  
  $("#login").on('submit', function (e) {
  
    e.preventDefault();

    $.ajax({

      type: 'POST',
      url: 'Assets/Src/Login/Login.php',
      data: new FormData(this),
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,
      success: function(data){

        if(data.status == false){    
          
          $('#notif').html(data.notif);
          
        } else {

          document.cookie = 'SESSION='+data.cookie + ";path=/" ;
          $("html").load(data.location).hide().fadeIn(1000);

      }
      }
    });
  });

// /*
//  * --> Lost Pass Word
//  * 
//  * # Changement block 
//  * 
//  * ## Traitement Ajax de la requete
//  */
  
//   $('.LostPassWord__action').on('click', function (e) {
//     e.preventDefault();
//     $(this).parents().addClass('none', 500);
//     $('.LostPassWord__container').addClass('active', 500)
//   });

//   $('.LostPassWord__remove').on('click', function (e) {
//     e.preventDefault();
//     $(this).parents().removeClass('active', 500);
//     $('.Login__container').removeClass('none', 500)
//   });



  $("#LostPassWord").on('submit', function (e) {
  
  e.preventDefault();

    $.ajax({

      type: 'POST',
      url: 'Assets/Src/Login/LostPassWord.php',
      data: new FormData(this),
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData:false,
      success: function(data){

        if(data.status == false){    
          
          $('#notif').html(data.notif);
        
        } else {
          
          $('#notif').html(data.notif);
          $('#form__container').html(data.resultat).hide().fadeIn(1000);;
          
        }
        
      }
    });
});
  
  
$("#verifcode").on('submit', function (e) {
  console.log('test')
  e.preventDefault();

    // $.ajax({

    //   type: 'POST',
    //   url: 'Assets/Src/Login/VerifCode.php',
    //   data: new FormData(this),
    //   dataType: 'JSON',
    //   contentType: false,
    //   cache: false,
    //   processData:false,
    //   success: function(data){

    //     if(data.status == false){    
    //       console.log(data)
    //       $('#notif').html(data.notif);

    //     } else {
    //       console.log(data)
    //       $('#notif').html(data.notif);
    //       // $('#form__container').html(data.resultat);
          
    //     }
        
    //   }
    // });
});
  


  
});