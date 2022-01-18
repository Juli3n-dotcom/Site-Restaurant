<?php
require_once __DIR__ . '/Assets/Config/Bootstrap.php';;

$page_title = 'Mot de pass perdu';
include __DIR__ . '/Assets/Includes/HeaderLogin.php';
?>

<div class="notif" id='notif'></div>


<div class="center LostPassWord__container">
  <h1>Mot de passe oublié</h1>

  <div id="form__container">
    <form method="post" id="LostPassWord" enctype="multipart/form-data">
      <div class="txt_field">
        <input type="text" name="email" id="email">
        <span></span>
        <label>Votre Email</label>
      </div>

      <div class="pass"><a href="Login.php" class="LostPassWord__remove">Connexion</a></div>

      <input type="submit" name="submit" value="Réinitialiser">
      <div class="signup_link">
        Besoin d'aider <a href="#" class="help__btn">Help</a>
      </div>
    </form>
  </div>

</div>



<?php
include __DIR__ . '/Assets/Includes/FooterLogin.php';
?>