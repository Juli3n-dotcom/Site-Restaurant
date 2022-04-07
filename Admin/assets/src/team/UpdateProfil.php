<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../functions/TeamFunctions.php';

use App\General;
use App\Team;
/* #############################################################################

Update d'un member a partir Profil.php en Ajax

############################################################################# */

$result = array();


if (!empty($_POST)) {
  $id = $_POST['update_id'];
  $username =  htmlspecialchars($_POST['update_username']);
  $fname = htmlspecialchars($_POST['update_prenom']);
  $name = htmlspecialchars($_POST['update_name']);
  $email = htmlspecialchars($_POST['update_email']);
  $mdp = htmlspecialchars($_POST['update_password']);
  $confirmation = htmlspecialchars($_POST['confirmation']);
  $updateMdp = FALSE;
  $ua = getBrowser();


  $data = $pdo->query("SELECT * FROM team WHERE id_team_member = '$id'");
  $user = $data->fetch(PDO::FETCH_ASSOC);

  // debut de la requete d'update
  $param = FALSE;
  $requete = 'UPDATE team SET ';

  if (empty($name)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le nom');
    postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Nom manquant');
  } elseif (!preg_match('~^[a-zA-Z- ]+$~', $name)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! le nom est pas bon');
    postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Nom non valide');
  } elseif ($name !== $user['nom']) {
    $requete .= 'nom = :nom';
    $param = TRUE;
  }


  //modification du prénom
  if (empty($fname)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le prénom');
    postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Prénom manquant');
  } elseif (!preg_match('~^[a-zA-Z- ]+$~', $fname)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! le prénom est pas bon');
    postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Prénom non valide');
  } elseif ($fname !== $user['prenom']) {

    if ($param == TRUE) {

      $requete .= ', prenom = :prenom';
    } else {

      $requete .= 'prenom = :prenom';
    }

    $param = TRUE;
  }


  //modification de l'username
  if (empty($username)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le pseudo');
    postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Pseudo manquant');
  } elseif ($username !== $user['username']) {

    if (getMemberBy($pdo, 'username', $username) !== null) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'pseudo déjà utilisé');
      postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'pseudo déjà utilisé');
    } elseif (!preg_match('~^[a-zA-Z0-9_-]+$~', $username)) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'oups! le pseudo est pas bon');
      postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Pseudo non valide');
    } else {

      if ($param == TRUE) {

        $requete .= ', username = :username';
      } else {

        $requete .= 'username = :username';
      }

      $param = TRUE;
    }
  }

  //modification de l'email
  if (empty($email)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le mail');
    postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Email manquant');
  } elseif ($email !== $user['email']) {

    if (getMemberBy($pdo, 'email', $email) !== null) {

      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'email déjà utilisé');
      postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Email déjà utilisé');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'email non valide');
      postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'email non valide');
    } else {

      if ($param == TRUE) {

        $requete .= ', email = :email, confirmation = :confirmation';
      } else {

        $requete .= 'email = :email, confirmation = :confirmation';
      }

      $param = TRUE;
    }
  }

  //modification du mot de passe de
  if (!empty($mdp)) {
    if ($mdp !== $confirmation) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'Les mots de passe ne correspondent pas');
      postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Les mots de passe ne corresponde pas');
    } else {
      $hash = password_hash($mdp, PASSWORD_DEFAULT);
      $updateMdp = TRUE;
      if ($param == TRUE) {

        $requete .= ', password = :password';
      } else {

        $requete .= 'password = :password';
      }

      $param = TRUE;
    }
  }


  if (!empty($_FILES['new_img']['tmp_name'])) {

    $img = $user['photo_id'];
    $path = __DIR__ . '/../../Uploads/';
    $pathOriginal = __DIR__ . '/../../Uploads/Originals/';
    $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'png', 'jpeg');

    if ($_FILES['new_img']['error'] !== UPLOAD_ERR_OK) {

      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
      postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
    } elseif ($_FILES['new_img']['size'] < 12) {

      $result['status'] = false;
      $result['notif'] = General::notif('error', 'Le fichier envoyé n\'est pas une image');
      postJournal($pdo, $id, 4, 'Tentative de modification d\'un profil', 'Le fichier envoyé n\'est pas une image');
    } elseif (!in_array($extension, $allowTypes)) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'Format d\'image non pris en charge');
      postJournal($pdo, $id, 5, 'Tentative de modification d\'un profil', 'Format d\'image non pris en charge');
    } else {
      do {
        $filename = bin2hex(random_bytes(16));
        $complete_path = $pathOriginal  . $filename . '.' . $extension;
      } while (file_exists($complete_path));

      if (!move_uploaded_file($_FILES['new_img']['tmp_name'], $complete_path)) {
        $result['status'] = false;
        $result['notif'] = General::notif('error', 'La photo n\'a pas pu être enregistrée');
        postJournal($pdo, $id, 4, 'Tentative de modification d\'un profil', 'La photo n\'a pas pu être enregistrée');
      } else {
        if (!is_null($img)) {

          // suppression des anciennes Images
          $data = $pdo->query("SELECT * FROM team_photo WHERE id_photo = '$img'");
          $photo = $data->fetch(PDO::FETCH_ASSOC);

          $dir = opendir($path);
          unlink($path . $photo['img__jpeg']);
          unlink($path . $photo['img__webp']);
          closedir($dir);

          $dirOriginal = opendir($pathOriginal);
          unlink($pathOriginal . $photo['original']);
          closedir($dirOriginal);
        }

        //création des autres images
        if (file_exists($complete_path)) {

          if ($extension == 'png') {

            $quality = 50;

            $image = imagecreatefrompng($complete_path);
            ob_start();
            imagewebp($image, $path . $filename . '.webp');
            imagejpeg($image, $path . $filename . '.jpg', $quality);
            ob_end_clean();
            imagedestroy($image);
          } elseif ($extension == 'jpg' || $extension = 'jpeg') {
            copy($complete_path, $path  . $filename . '.' . $extension);
            $image = imagecreatefromjpeg($complete_path);
            ob_start();
            imagewebp($image, $path  . $filename . '.webp');
            ob_end_clean();
            imagedestroy($image);
          }


          if (is_null($img)) {
            # si la catégorie ne possede pas d'image, enregistrement en BDD de la photo,
            $req1 = $pdo->prepare(
              'INSERT INTO team_photo(img__jpeg, img__webp, original)
                         VALUES (:img__jpeg,:img__webp,:original)'
            );

            if ($extension == 'png') {
              $req1->bindValue(':img__jpeg', $filename . '.jpg');
              $req1->bindValue(':img__webp',  $filename . '.webp');
            } elseif ($extension == 'jpg' || $extension = 'jpeg') {

              $req1->bindValue(':img__jpeg',  $filename . '.' . $extension);
              $req1->bindValue(':img__webp',  $filename . '.webp');
            }
            $req1->bindValue(':original', $filename . '.' . $extension);
            $req1->execute();

            if ($param == TRUE) {
              $requete  .= ', photo_id = :photo_id';
            } else {
              $requete  .= 'photo_id = :photo_id';
            }
            $param = TRUE;
            $newImg = $pdo->lastInsertId();
          } else {
            # si la catégorie possede déja une image, on update avec les nouveaux fichiers
            $req_update_photo = $pdo->prepare('UPDATE team_photo SET img__jpeg = :img__jpeg, 
                                                                                  img__webp = :img__webp, 
                                                                                  original = :original 
                                                                                  WHERE id_photo = :id');
            $req_update_photo->bindParam(':id', $img, PDO::PARAM_INT);
            if ($extension == 'png') {
              $req_update_photo->bindValue(':img__jpeg', $filename . '.jpg');
              $req_update_photo->bindValue(':img__webp',  $filename . '.webp');
            } elseif ($extension == 'jpg' || $extension = 'jpeg') {

              $req_update_photo->bindValue(':img__jpeg',  $filename . '.' . $extension);
              $req_update_photo->bindValue(':img__webp',  $filename . '.webp');
            }
            $req_update_photo->bindValue(':original', $filename . '.' . $extension);
            $req_update_photo->execute();
          }
        }
      }
    }
  }

  //lancement de la requete
  $requete .= ' WHERE id_team_member = ' . $id;

  //préparation de la requete
  $req_update = $pdo->prepare($requete);

  if ($username !== $user['username']) {
    $req_update->bindParam(':username', $username);
  }
  if ($fname !== $user['prenom']) {
    $req_update->bindParam(':prenom', $fname);
  }
  if ($name !== $user['nom']) {
    $req_update->bindParam(':nom', $name);
  }
  if ($email !== $user['email']) {
    $req_update->bindParam(':email', $email);
    $req_update->bindValue(':confirmation', 0);
  }
  if ($updateMdp == TRUE) {
    $req_update->bindParam(':password', $hash);
  }
  if (isset($newImg)) {
    $req_update->bindValue(':photo_id', $newImg);
  }
  $req_update->execute();



  $result['status'] = true;
  $result['notif'] = General::notif('success', 'Profil Modifié');
  postJournal($pdo, $id, 1, 'Profil Modifié', 'Le Profil de ' . $user['username'] . ' a été Modifié');

  $query = $pdo->query("SELECT * FROM team WHERE id_team_member = '$id'");
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
}
// Return result 
echo json_encode($result);
