<?php
require_once __DIR__ . '/Assets/Config/Init.php';

$page_title = 'Connexion';
include __DIR__ . '/Assets/Includes/HeaderLogin.php';
?>

<div class="notif" id='notif'></div>

<div class="center Login__container">
  <h1>Connexion</h1>
  <form method="post" id="login" enctype="multipart/form-data">
    <div class="txt_field">
      <input type="text" name="username" id="username">
      <span></span>
      <label>Nom d'utilisateur</label>
    </div>
    <div class="txt_field">
      <input type="password" name="current-password" id="current-password">
      <span></span>
      <label>Mot de passe</label>
    </div>
    <div class="pass"><a href="LostPassWord.php" class="LostPassWord__action">Mot de passe oublié?</a></div>
    <input type="submit" value="Connexion">
    <div class="signup_link">
      Besoin d'aider <a href="#" class="help__btn">Help</a>
    </div>
  </form>
</div>





<?php
include __DIR__ . '/Assets/Includes/FooterLogin.php';
?>