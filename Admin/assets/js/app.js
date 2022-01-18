
let btn = document.querySelector('#header_btn')
let sidebar = document.querySelector('.sidebar')

if (window.location.href.indexOf('Login') > -1) {
  console.log('Login')
}
  
    btn.onclick = function () {
      sidebar.classList.toggle('active')
      sidebar.classList.toggle('close')
    }

    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
      arrow[i].addEventListener("click", (e) => {
        let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
        arrowParent.classList.toggle("showMenu");
      });
    }
    
    // menu team member
    function menuTeamToggle() {
      const toggleMenu = document.querySelector('.member_menu');
      toggleMenu.classList.toggle('active')
    }


/*
 * --> Help Modal
 * 
 * # Ouverture du Modal d'aide
 * 
 * ## traitement Ajax
 *
 */

$(document).ready(function () {

  $(document).on('click', '.help__btn', function () {

    $('#helpmodal').modal("show");
    
  });

});
