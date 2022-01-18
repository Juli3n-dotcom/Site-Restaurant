$(document).ready(function () {


  /*
  * --> View data
  */
  load_data();
  function load_data(page) {

    $.ajax({
      url: "Assets/Src/History/AffichageHistory.php",
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
 * --> View history element
 * 
 * # Ouverture du Modal de vue
 *
 */

// # Ouverture du Modal de vue
$(document).on('click','.viewbtn', function(){  
  var history_id = $(this).attr("id");  

  $.ajax({  
       url:"Assets/Src/History/ViewHistoryElement.php",  
       method:"post",  
       data:{history_id:history_id},  
       success:function(data){  
            $('#history_detail').html(data);  
            $('#viewmodal').modal("show");  
       }  
  });  
});
  
/*
 * --> View all Add
 * 
 * # load data
 * 
 * ## pagination
 * 
 * ###traitement ajax
 */
  
function load_data_add(page) {

    $.ajax({
      url: "Assets/Src/History/ViewAllAdd.php",
      method: "post",
      dataType: 'json', 
      data: { page: page },
      success: function (data) {
        $('#table').html(data.resultat);
      }
    })

  }

  $(document).on('click', '.pagination_link_add', function () {
  
    var page = $(this).attr('id');
    
    load_data_add(page);
  })
  

  $(document).on('click', '#allAdd', function (e) {
  
  e.preventDefault();
  load_data_add()
  $.ajax({  
       url:"Assets/Src/History/ViewAllAdd.php",  
       method:"post",
       dataType: 'json', 
       success:function(data){  

        $('#table').html(data.resultat);  

       }  
  });  
  });

/*
 * --> View all Delete
 * 
 * # load data
 * 
 * ## pagination
 * 
 * ###traitement ajax
 */
  
function load_data_delete(page) {

    $.ajax({
      url: "Assets/Src/History/ViewAllDelete.php",
      method: "post",
      dataType: 'json', 
      data: { page: page },
      success: function (data) {
        $('#table').html(data.resultat);
      }
    })

  }

  $(document).on('click', '.pagination_link_delete', function () {
  
    var page = $(this).attr('id');
    
    load_data_delete(page);
  })
  

  $(document).on('click', '#allDelete', function (e) {
  
  e.preventDefault();
  load_data_delete()
  $.ajax({  
       url:"Assets/Src/History/ViewAllDelete.php",  
       method:"post",
       dataType: 'json', 
       success:function(data){  

        $('#table').html(data.resultat);  

       }  
  });  
  });

  /*
 * --> View all update
 * 
 * # load data
 * 
 * ## pagination
 * 
 * ###traitement ajax
 */
  
function load_data_update(page) {

    $.ajax({
      url: "Assets/Src/History/ViewAllUpdate.php",
      method: "post",
      dataType: 'json', 
      data: { page: page },
      success: function (data) {
        $('#table').html(data.resultat);
      }
    })

  }

  $(document).on('click', '.pagination_link_update', function () {
  
    var page = $(this).attr('id');
    
    load_data_update(page);
  })
  

  $(document).on('click', '#allUpdate', function (e) {
  
  e.preventDefault();
  load_data_update()
  $.ajax({  
       url:"Assets/Src/History/ViewAllUpdate.php",  
       method:"post",
       dataType: 'json', 
       success:function(data){  

        $('#table').html(data.resultat);  

       }  
  });  
  });

/*
 * --> View all info
 * 
 * # load data
 * 
 * ## pagination
 * 
 * ###traitement ajax
 */
  
function load_data_info(page) {

    $.ajax({
      url: "Assets/Src/History/ViewAllInfo.php",
      method: "post",
      dataType: 'json', 
      data: { page: page },
      success: function (data) {
        $('#table').html(data.resultat);
      }
    })

  }

  $(document).on('click', '.pagination_link_info', function () {
  
    var page = $(this).attr('id');
    
    load_data_info(page);
  })
  

  $(document).on('click', '#allInfo', function (e) {
  
  e.preventDefault();
  load_data_info()
  $.ajax({  
       url:"Assets/Src/History/ViewAllInfo.php",  
       method:"post",
       dataType: 'json', 
       success:function(data){  

        $('#table').html(data.resultat);  

       }  
  });  
  });

  /*
 * --> View all error
 * 
 * # load data
 * 
 * ## pagination
 * 
 * ###traitement ajax
 */
  
function load_data_error(page) {

    $.ajax({
      url: "Assets/Src/History/ViewAllError.php",
      method: "post",
      dataType: 'json', 
      data: { page: page },
      success: function (data) {
        $('#table').html(data.resultat);
      }
    })

  }

  $(document).on('click', '.pagination_link_error', function () {
  
    var page = $(this).attr('id');
    
    load_data_error(page);
  })
  

  $(document).on('click', '#allError', function (e) {
  
  e.preventDefault();
  load_data_error()
  $.ajax({  
       url:"Assets/Src/History/ViewAllError.php",  
       method:"post",
       dataType: 'json', 
       success:function(data){  

        $('#table').html(data.resultat);  

       }  
  });  
  });

  /*
 * --> View all warning
 * 
 * # load data
 * 
 * ## pagination
 * 
 * ###traitement ajax
 */
  
function load_data_warning(page) {

    $.ajax({
      url: "Assets/Src/History/ViewAllWarning.php",
      method: "post",
      dataType: 'json', 
      data: { page: page },
      success: function (data) {
        $('#table').html(data.resultat);
      }
    })

  }

  $(document).on('click', '.pagination_link_warning', function () {
  
    var page = $(this).attr('id');
    
    load_data_error(page);
  })
  

  $(document).on('click', '#allWarning', function (e) {
  
  e.preventDefault();
  load_data_warning()
  $.ajax({  
       url:"Assets/Src/History/ViewAllWarning.php",  
       method:"post",
       dataType: 'json', 
       success:function(data){  

        $('#table').html(data.resultat);  

       }  
  });  
  });

  
  /*
 * --> View all reset
 * 
 * # traitement ajax
 * 
 */

  $(document).on('click', '#resetBtn', function (e) {
  
  e.preventDefault();

  $.ajax({  
       url:"Assets/Src/History/ResetViewAll.php",  
       method:"post",
       dataType: 'json', 
       success:function(data){  

        $('#table').html(data.resultat);  

       }  
  });  
});  

});