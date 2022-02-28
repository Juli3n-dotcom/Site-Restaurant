<?php
require_once __DIR__ . '/../../Config/Init.php';

if (isset($_COOKIE['SESSION'])) {

  setcookie('SESSION', null, time() - 3600, "/");
}
unset($_SESSION['team']);
header('location: ../../../Login.php');
