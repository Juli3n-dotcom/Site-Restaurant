</main>



<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
<?php
if (stripos($_SERVER['REQUEST_URI'], 'Team.php')) {
  echo '<script type="text/javascript" src="assets/js/Team.min.js"></script>';
}
if (stripos($_SERVER['REQUEST_URI'], 'History.php')) {
  echo '<script type="text/javascript" src="assets/js/History.min.js"></script>';
}
?>
<script src="assets/js/app.js" type="text/javascript"></script>

</body>

</html>