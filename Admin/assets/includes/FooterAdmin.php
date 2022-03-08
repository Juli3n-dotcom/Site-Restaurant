<?php

use Sign\Signature;
?>



</main>


<?php

Signature::getSignature();
include __DIR__ . '/HelpModal.php';
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
<?php

if (stripos($_SERVER['REQUEST_URI'], 'Team.php')) {
  echo '<script type="text/javascript" src="Assets/Js/Team.js"></script>';
}
if (stripos($_SERVER['REQUEST_URI'], 'History.php')) {
  echo '<script type="text/javascript" src="Assets/Js/History.min.js"></script>';
}
if (stripos($_SERVER['REQUEST_URI'], 'RestaurantCategories.php')) {
  echo '<script type="text/javascript" src="Assets/Js/RestaurantCategories.js"></script>';
}
if (stripos($_SERVER['REQUEST_URI'], 'SousCat.php')) {
  echo '<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
';
  echo '<script type="text/javascript" src="Assets/Js/SousCategories.min.js"></script>';
}
if (stripos($_SERVER['REQUEST_URI'], 'Categories.php')) {
  echo '<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
';
  echo '<script type="text/javascript" src="Assets/Js/Categories.min.js"></script>';
}
if (stripos($_SERVER['REQUEST_URI'], 'Options.php')) {
  echo '<script type="text/javascript" src="Assets/Js/Options.js"></script>';
}
echo '<script src="Assets/Js/App.js" type="text/javascript"></script>';

?>

<!-- <script src="Assets/Js/Help.js" type="text/javascript"></script> -->

</body>

</html>