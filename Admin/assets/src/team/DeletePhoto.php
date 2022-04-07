<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../functions/TeamFunctions.php';

use App\General;
use App\Team;

$user = $user['id_team_member'];
$ua = getBrowser();

/* #############################################################################

Suppression de la photo de profil d'un membre à partir de profil.php

############################################################################# */



if (!empty($_POST)) {

  $result = array();
  $id = $_POST['photo_id'];

  $data = $pdo->query("SELECT * FROM team_photo WHERE id_photo = '$id'");
  $photo = $data->fetch(PDO::FETCH_ASSOC);

  $file = __DIR__ . '/../../Uploads/';

  $dir = opendir($file);
  unlink($file . $photo['img__jpeg']);
  unlink($file . $photo['img__webp']);
  closedir($dir);

  $pathOriginal = __DIR__ . '/../../Uploads/Originals/';

  $dirOriginal = opendir($pathOriginal);
  unlink($pathOriginal . $photo['original']);
  closedir($dirOriginal);


  $req1 = $pdo->exec("DELETE FROM team_photo WHERE id_photo = '$id'");

  $result['status'] = true;
  $result['notif'] = General::notif('success', 'Photo supprimée');
  postJournal($pdo, $user, 1, 'Photo de profil supprimée', 'Photo supprimée');


  $query = $pdo->query("SELECT * FROM team WHERE id_team_member = '$user'");
  $member = $query->fetch();

  $result['menu'] = '<div class="profile" onclick="menuTeamToggle();">
                        <img src="' . Team::getPhoto($pdo, $member['photo_id'], $member['civilite'], $ua['name']) . '" alt="photo_profil_other">
                    </div>
                    <div class="member_menu">
                    <h3>' . $member['username'] . '</h3>
                        <ul>
                          <li>
                            <i class="bx bxs-user-circle"></i>
                            <a href="Profil.php"> Mon Profil</a>
                          </li>
                          <li>
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <a href="#" class="help__btn"> Help</a>
                          </li>
                          <li>
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            <a href="Assets/Src/Login/Logout.php"> Déconnexion</a>
                          </li>
                        </ul>
                     </div>';

  $result['profil'] = '<img src="' . Team::getPhoto($pdo, $member['photo_id'], $member['civilite'], $ua['name']) . '" alt="">
                        <div class="name_job">
                          <div class="name">' . $member['username'] . '</div>
                          <div class="job">' . Team::getPoste($member['statut']) . '</div>
                        </div>';

  $result['resultat'] = '<form action="" method="post" enctype="multipart/form-data" id="update_profil">
                            <input type="hidden" name="update_id" id="update_id" value="' . $member['id_team_member'] . '">';

  $result['resultat'] .= '<div class="container-fluid profil_container" id="profil_container">
                            <div class="row">';

  $result['resultat'] .= '<div class="col-sm-9 col-md-8 left_part">';

  $result['resultat'] .= '<div class="mb-3">
                           <label for="update_username" class="form-label">Pseudo :</label>
                           <input type="text" class="form-control" name="update_username" id="update_username" placeholder="Votre username" value="' . $member['username'] . '">
                          </div>';


  $result['resultat'] .= '<div class="mb-3">
                          <label for="update_name" class="form-label">Nom :</label>
                          <input type="text" class="form-control" name="update_name" id="update_name" placeholder="Votre nom" value="' . $member['nom'] . '">
                        </div>';

  $result['resultat'] .= '<div class="mb-3">
                            <label for="update_prenom" class="form-label">Prénom :</label>
                            <input type="text" class="form-control" name="update_prenom" id="update_prenom" placeholder="Votre Prénom" value="' . $member['prenom'] . '">
                          </div>';

  $result['resultat'] .= '<div class="mb-3 ">
                          <label for="update_email">Email : </label>
                          <input type="email" name="update_email" id="update_email" class="form-control" value="' . $member['email'] . '">
                        </div>';


  $result['resultat'] .= '<div class="mb-3 ">
                              <label for="update_password">Mot de passe : </label>
                              <input type="password" name="update_password" id="update_password" class="form-control">
                            </div>';

  $result['resultat'] .=  '<div class="mb-3 ">
                            <label for="confirmation">Confirmation Mot de passe : </label>
                            <input type="password" name="confirmation" id="confirmation" class="form-control">
                          </div>';

  $result['resultat'] .= '<div class="mb-3 submit_container">
                              <button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier mon compte</button>
                              <div class="load-update hide" id="load-update">
                                <img src="Assets/Images/loader.gif" alt="Loader">
                                <p>Chargement ...</p>
                              </div>
                           </div>';

  $result['resultat'] .= '</div>';

  $result['resultat'] .= '<div class=" col-sm-3 col-md-4 right_part">';

  $result['resultat'] .= '<label class="label_container">Photo : </label>
                            <div class="mb-3 profil_container-element">';
  if ($member['photo_id']) {
    $result['resultat'] .= '<input type="hidden" name="update_img" id="update_img" value="' . $member['photo_id'] . '">';
  }
  $result['resultat'] .= '<div class="img-profil-container">
                               <img class="img-profil" src="' . Team::getPhoto($pdo, $member['photo_id'], $member['civilite'], $ua['name']) . '">
                              </div>
                                <div class="profil-img-btn-container">
                             <input type="file" name="new_img" id="new_img" title="Changez image du profil" placeholder="indiquez la description du profil">
                              <label for="new_img">Changer la photo</label>';
  if ($member['photo_id'] !== NULL) {
    $result['resultat'] .= '<button id="delete_photo" class="delete-img-profil" value="' . $member['photo_id'] . '">Supprimer la photo</button>';
  }
  $result['resultat'] .= '</div></div>';

  $result['resultat'] .= '<div class="mb-3 submit_container">
                              <button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier mon compte</button>
                              <div class="load-update hide" id="load-update">
                                <img src="Assets/Images/loader.gif" alt="Loader">
                                <p>Chargement ...</p>
                              </div>
                           </div>';

  $result['resultat'] .= '</div>




      </div>
    </div>

  </form>';


  // Return result 
  echo json_encode($result);
}
