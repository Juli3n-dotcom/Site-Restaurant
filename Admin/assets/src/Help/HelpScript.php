<?php

require_once __DIR__ . '/../../Config/Init.php';

use App\Notifications;


if (!empty($_POST)) {

  $result = array();
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $subject = htmlspecialchars($_POST['subject']);
  $subject = htmlspecialchars($_POST['message']);

  if (!preg_match('~^[a-zA-Z- ]+$~', $name)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque votre nom');
  }

  echo json_encode($result);
}
