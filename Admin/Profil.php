<?php
require_once __DIR__ . '/Assets/Config/Init.php';

use App\Team;

$page_title = 'Mon Compte';
$ua = getBrowser();
include __DIR__ . '/Assets/Includes/HeaderAdmin.php';
?>
<div class="notif" id='notif'></div>


<section id="container_profil" class="container_profil">
  <form action="" method="post" enctype="multipart/form-data" id="update_profil">
    <input type="hidden" name="update_id" id="update_id" value="<?= $user['id_team_member']; ?>">

    <div class="container-fluid profil_container" id="profil_container">
      <div class="row">

        <div class="col-sm-9 col-md-8 left_part">

          <div class="mb-3">
            <label for="update_username" class="form-label">Pseudo :</label>
            <input type="text" class="form-control" name="update_username" id="update_username" placeholder="Votre username" value="<?= $user['username'] ?>">
          </div>

          <div class="mb-3">
            <label for="update_name" class="form-label">Nom :</label>
            <input type="text" class="form-control" name="update_name" id="update_name" placeholder="Votre nom" value="<?= $user['nom'] ?>">
          </div>

          <div class="mb-3">
            <label for="update_prenom" class="form-label">Prénom :</label>
            <input type="text" class="form-control" name="update_prenom" id="update_prenom" placeholder="Votre Prénom" value="<?= $user['prenom'] ?>">
          </div>

          <div class="mb-3 ">
            <label for="update_email">Email : </label>
            <input type="email" name="update_email" id="update_email" class="form-control" value="<?= $user['email'] ?>">
          </div>

          <div class="mb-3 ">
            <label for="update_password">Mot de passe : </label>
            <input type="password" name="update_password" id="update_password" class="form-control">
          </div>

          <div class="mb-3 ">
            <label for="confirmation">Confirmation Mot de passe : </label>
            <input type="password" name="confirmation" id="confirmation" class="form-control">
          </div>

          <div class="mb-3 submit_container">
            <button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier mon compte</button>
            <div class="load-update hide" id="load-update">
              <img src="Assets/Images/loader.gif" alt="Loader">
              <p>Chargement ...</p>
            </div>
          </div>

        </div>

        <div class=" col-sm-3 col-md-4 right_part">

          <label class="label_container">Photo : </label>
          <div class="mb-3 profil_container-element">
            <?php if ($user['photo_id']) : ?>
              <input type="hidden" name="update_img" id="update_img" value="<?= $user['photo_id']; ?>">
            <?php endif; ?>
            <div class="img-profil-container">
              <img class='img-profil' src="<?= Team::getPhoto($pdo, $user['photo_id'], $user['civilite'], $ua['name']) ?>">
            </div>
            <div class="profil-img-btn-container">
              <input type="file" name="new_img" id="new_img" title="Changez image du profil" placeholder="indiquez la description du profil">
              <label for="new_img">Changer la photo</label>
              <?php if ($user['photo_id'] !== NULL) : ?>
                <button id="delete_photo" class="delete-img-profil" value="<?= $user['photo_id']; ?>">Supprimer la photo</button>
              <?php endif; ?>
            </div>

          </div>

          <div class="mb-3 submit_container">
            <button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier mon compte</button>
            <div class="load-update hide" id="load-update">
              <img src="Assets/Images/loader.gif" alt="Loader">
              <p>Chargement ...</p>
            </div>
          </div>


        </div>




      </div>
    </div>

  </form>

</section>


<?php
include __DIR__ . '/Assets/Includes/FooterAdmin.php';
?>