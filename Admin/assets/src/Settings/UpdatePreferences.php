<?php
require_once __DIR__ . '/../../config/Init.php';

use App\General;
use App\GeneralClass;


/* #############################################################################

Update d'un member a partir Settings.php en Ajax

############################################################################# */

$user = $user['id_team_member'];
$ua = getBrowser();
$result = array();

if (!empty($_POST)) {
  $token = htmlspecialchars($_POST['update_id']);
  $name =  htmlspecialchars($_POST['update_name']);
  $adresse = htmlspecialchars($_POST['update_adresse']);
  $complements = htmlspecialchars($_POST['update_complements']);
  $cp = htmlspecialchars($_POST['update_cp']);
  $ville = htmlspecialchars($_POST['update_city']);
  $email = htmlspecialchars($_POST['update_email']);
  $phone = htmlspecialchars($_POST['update_phone']);
  $siret = htmlspecialchars($_POST['update_siret']);
  $tva = htmlspecialchars($_POST['update_tva']);
  $insta = htmlspecialchars($_POST['update_insta']);
  $fb = htmlspecialchars($_POST['update_fb']);
  $pinterest = htmlspecialchars($_POST['update_pinterest']);
  $twitter = htmlspecialchars($_POST['update_twitter']);
  $snap = htmlspecialchars($_POST['update_snap']);
  $tik = htmlspecialchars($_POST['update_tiktok']);
  $tab = htmlspecialchars($_POST['tab_id']);
  $newToken = '';
  $value = null;

  $data = $pdo->query("SELECT * FROM resto_infos WHERE token = '$token'");
  $settings = $data->fetch(PDO::FETCH_ASSOC);

  // debut de la requete d'update
  $param = FALSE;
  $validation = FALSE;
  $requete = 'UPDATE resto_infos SET ';

  if (empty($name)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le nom');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Nom manquant');
  } elseif (!preg_match('~^[a-zàâçéèêëîïôûùüÿñæœA-Z-0-9 ]+$~', $name)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! le nom est pas bon');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Nom non valide');
  } elseif ($name !== $settings['name']) {
    $requete .= 'name = :name';
    $param = TRUE;
    $validation = TRUE;
  }


  if (empty($adresse)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque l\'adresse');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Adresse manquante');
  } elseif (!preg_match('~^[a-zàâçéèêëîïôûùüÿñæœA-Z-0-9 ]+$~', $adresse)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'Caractére non autorisé');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Caractére non autorisé');
  } elseif ($adresse !== $settings['adresse']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', adresse = :adresse';
    } else {

      $requete .= 'adresse = :adresse';
    }

    $param = TRUE;
  }


  if ($complements !== $settings['complements']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', complements = :complements';
    } else {

      $requete .= 'complements = :complements';
    }

    $param = TRUE;
  }


  if (empty($cp)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le code postal');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'CP manquant');
  } elseif (!preg_match('~^[0-9-.]+$~', $cp)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'Caractére non autorisé');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Caractére non autorisé');
  } elseif ($cp !== $settings['cp']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', cp = :cp';
    } else {

      $requete .= 'cp = :cp';
    }

    $param = TRUE;
  }

  if (empty($ville)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque la ville');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Ville manquante');
  } elseif (!preg_match('~^[a-zàâçéèêëîïôûùüÿñæœA-Z-0-9 ]+$~', $ville)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'Caractére non autorisé');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Caractére non autorisé');
  } elseif ($ville !== $settings['ville']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', ville = :ville';
    } else {

      $requete .= 'ville = :ville';
    }

    $param = TRUE;
  }

  if (empty($email)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque l\'email');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Email manquant');
  } elseif ($email !== $settings['email']) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'adresse email non valide');
      postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'email non valide');
    } else {
      $validation = TRUE;
      if ($param == TRUE) {

        $requete .= ', email = :email';
      } else {

        $requete .= 'email = :email';
      }

      $param = TRUE;
    }
  }

  if (empty($phone)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le numéro de téléphone');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'CP manquant');
  } elseif (!preg_match('~^[0-9-.]+$~', $phone)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'Caractére non autorisé pour un numéro de téléphone');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Caractére non autorisé');
  } elseif ($phone !== $settings['telephone']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', telephone = :telephone';
    } else {

      $requete .= 'telephone = :telephone';
    }

    $param = TRUE;
  }

  if (empty($siret)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le numero de siret');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'SIRET manquant');
  } elseif (!preg_match('~^[0-9-. ]+$~', $siret)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'Caractére non autorisé pour un SIRET');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Caractére non autorisé');
  } elseif ($siret !== $settings['siret']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', siret = :siret';
    } else {

      $requete .= 'siret = :siret';
    }

    $param = TRUE;
  }

  if (empty($tva)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le numéro de TVA');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'TVA manquante');
  } elseif (!preg_match('~^[a-zA-Z-0-9 ]+$~', $tva)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'Caractére non autorisé pour un numéro de TVA');
    postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Caractére non autorisé');
  } elseif ($tva !== $settings['tva']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', tva = :tva';
    } else {

      $requete .= 'tva = :tva';
    }

    $param = TRUE;
  }


  if ($insta !== $settings['insta']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', insta = :insta';
    } else {

      $requete .= 'insta = :insta';
    }

    $param = TRUE;
  }



  if ($fb !== $settings['facebook']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', facebook = :facebook';
    } else {

      $requete .= 'facebook = :facebook';
    }

    $param = TRUE;
  }



  if ($pinterest !== $settings['pinterest']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', pinterest = :pinterest';
    } else {

      $requete .= 'pinterest = :pinterest';
    }

    $param = TRUE;
  }




  if ($twitter !== $settings['twitter']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', twitter = :twitter';
    } else {

      $requete .= 'twitter = :twitter';
    }

    $param = TRUE;
  }



  if ($snap !== $settings['snap']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', snap = :snap';
    } else {

      $requete .= 'snap = :snap';
    }

    $param = TRUE;
  }



  if ($tik !== $settings['tiktok']) {
    $validation = TRUE;
    if ($param == TRUE) {

      $requete .= ', tiktok = :tiktok';
    } else {

      $requete .= 'tiktok = :tiktok';
    }

    $param = TRUE;
  }


  if (!empty($_FILES['new_img']['tmp_name'])) {

    $img = $settings['photo_id'];
    $path = __DIR__ . '/../../../../Global/Uploads/';
    $pathOriginal = __DIR__ . '/../../../../Global/Uploads/Originals/';
    $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
    $allowTypes = array('jpg', 'png', 'jpeg');

    if ($_FILES['new_img']['error'] !== UPLOAD_ERR_OK) {

      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
      postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
    } elseif ($_FILES['new_img']['size'] < 12) {

      $result['status'] = false;
      $result['notif'] = General::notif('error', 'Le fichier envoyé n\'est pas une image');
      postJournal($pdo, $user, 4, 'Tentative de modification des informations du restaurant', 'Le fichier envoyé n\'est pas une image');
    } elseif (!in_array($extension, $allowTypes)) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'Format d\'image non pris en charge');
      postJournal($pdo, $user, 5, 'Tentative de modification des informations du restaurant', 'Format d\'image non pris en charge');
    } else {
      do {
        $filename = bin2hex(random_bytes(16));
        $complete_path = $pathOriginal  . $filename . '.' . $extension;
      } while (file_exists($complete_path));

      if (!move_uploaded_file($_FILES['new_img']['tmp_name'], $complete_path)) {
        $result['status'] = false;
        $result['notif'] = General::notif('error', 'La photo n\'a pas pu être enregistrée');
        postJournal($pdo, $user, 4, 'Tentative de modification des informations du restaurant', 'La photo n\'a pas pu être enregistrée');
      } else {
        if (!is_null($img)) {

          // suppression des anciennes Images
          $data = $pdo->query("SELECT * FROM resto_photo WHERE id_photo = '$img'");
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

          $validation = TRUE;


          if (is_null($img)) {
            # si la catégorie ne possede pas d'image, enregistrement en BDD de la photo,
            $req1 = $pdo->prepare(
              'INSERT INTO resto_photo(img__jpeg, img__webp, original)
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

            $validation = TRUE;

            if ($param == TRUE) {
              $requete  .= ', photo_id = :photo_id';
            } else {
              $requete  .= 'photo_id = :photo_id';
            }
            $param = TRUE;
            $newImg = $pdo->lastInsertId();
          } else {
            # si la catégorie possede déja une image, on update avec les nouveaux fichiers
            $req_update_photo = $pdo->prepare('UPDATE resto_photo SET img__jpeg = :img__jpeg, 
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

  if ($validation == TRUE) {
    $requete  .= ', date_modification = :date_modification, token = :token ';
    $newToken = bin2hex(random_bytes(16));

    //lancement de la requete
    $requete .= 'WHERE id = ' . $settings['id'];

    $req_update = $pdo->prepare($requete);

    if ($name !== $settings['name']) {
      $req_update->bindParam(':name', $name);
    }
    if ($adresse !== $settings['adresse']) {
      $req_update->bindParam(':adresse', $adresse);
    }
    if (!empty($complements)) {
      if ($complements !== $settings['complements']) {
        $req_update->bindParam(':complements', $complements);
      }
    } else {
      $req_update->bindParam(':complements', $value);
    }

    if ($cp !== $settings['cp']) {
      $req_update->bindParam(':cp', $cp);
    }
    if ($ville !== $settings['ville']) {
      $req_update->bindParam(':ville', $ville);
    }
    if ($email !== $settings['email']) {
      $req_update->bindParam(':email', $email);
    }
    if ($phone !== $settings['telephone']) {
      $req_update->bindParam(':telephone', $phone);
    }
    if ($siret !== $settings['siret']) {
      $req_update->bindParam(':siret', $siret);
    }
    if ($tva !== $settings['tva']) {
      $req_update->bindParam(':tva', $tva);
    }
    if (isset($newImg)) {
      $req_update->bindValue(':photo_id', $newImg);
    }
    if (!empty($insta)) {
      if ($insta !== $settings['insta']) {
        $req_update->bindParam(':insta', $insta);
      }
    } else {
      $req_update->bindParam(':insta', $value);
    }
    if (!empty($fb)) {
      if ($fb !== $settings['facebook']) {
        $req_update->bindParam(':facebook', $fb);
      }
    } else {
      $req_update->bindParam(':facebook', $value);
    }
    if (!empty($twitter)) {
      if ($twitter !== $settings['twitter']) {
        $req_update->bindParam(':twitter', $twitter);
      }
    } else {
      $req_update->bindParam(':twitter', $value);
    }
    if (!empty($snap)) {
      if ($snap !== $settings['snap']) {
        $req_update->bindParam(':snap', $snap);
      }
    } else {
      $req_update->bindParam(':snap', $value);
    }
    if (!empty($tik)) {
      if ($tik !== $settings['tiktok']) {
        $req_update->bindParam(':tiktok', $tik);
      }
    } else {
      $req_update->bindParam(':tiktok', $value);
    }
    if (!empty($pinterest)) {
      if ($pinterest !== $settings['pinterest']) {
        $req_update->bindParam(':pinterest', $pinterest);
      }
    } else {
      $req_update->bindParam(':pinterest', $value);
    }

    $req_update->bindValue(':date_modification', (new DateTime())->format('Y-m-d H:i:s'));
    $req_update->bindParam(':token', $newToken);
    $req_update->execute();

    if (isset($_POST['update_est_epice'])) {
      $epice = 1;
    } else {
      $epice = 0;
    }

    if (isset($_POST['update_vege'])) {
      $vege = 1;
    } else {
      $vege = 0;
    }

    if (isset($_POST['update_vegan'])) {
      $vegan = 1;
    } else {
      $vegan = 0;
    }

    if (isset($_POST['update_halal'])) {
      $halal = 1;
    } else {
      $halal = 0;
    }

    if (isset($_POST['update_casher'])) {
      $casher = 1;
    } else {
      $casher = 0;
    }
    $id = $settings['id'];



    // update options
    $options = $pdo->query("SELECT * FROM resto_options WHERE resto_id = '$id'");
    $optionsResto = $options->fetch(PDO::FETCH_ASSOC);

    $paramOptions = FALSE;
    $req = 'UPDATE resto_options SET ';


    if ($epice !== $optionsResto['est_epice']) {
      $req .= 'est_epice = :est_epice';
      $paramOptions = TRUE;
    }

    if ($vege !== $optionsResto['est_vege']) {
      if ($paramOptions == TRUE) {
        $req .= ', est_vege = :est_vege';
      } else {
        $req .= 'est_vege = :est_vege';
      }
      $paramOptions = TRUE;
    }

    if ($vegan !== $optionsResto['est_vegan']) {
      if ($paramOptions == TRUE) {
        $req .= ', est_vegan = :est_vegan';
      } else {
        $req .= 'est_vegan = :est_vegan';
      }
      $paramOptions = TRUE;
    }

    if ($halal !== $optionsResto['est_halal']) {
      if ($paramOptions == TRUE) {
        $req .= ', est_halal = :est_halal';
      } else {
        $req .= 'est_halal = :est_halal';
      }
      $paramOptions = TRUE;
    }

    if ($casher !== $optionsResto['est_casher']) {
      if ($paramOptions == TRUE) {
        $req .= ', est_casher = :est_casher';
      } else {
        $req .= 'est_casher = :est_casher';
      }
      $paramOptions = TRUE;
    }

    $req .= ' WHERE id = ' . $optionsResto['id'];

    $result['status'] = false;
    $result['test'] = $req;


    $req_update2 = $pdo->prepare($req);

    if ($epice !== $optionsResto['est_epice']) {
      $req_update2->bindValue(':est_epice', isset($_POST['update_est_epice']), PDO::PARAM_BOOL);
    }
    if ($vege !== $optionsResto['est_vege']) {
      $req_update2->bindValue(':est_vege', isset($_POST['update_vege']), PDO::PARAM_BOOL);
    }
    if ($vegan !== $optionsResto['est_vegan']) {
      $req_update2->bindValue(':est_vegan', isset($_POST['update_vegan']), PDO::PARAM_BOOL);
    }
    if ($halal !== $optionsResto['est_halal']) {
      $req_update2->bindValue(':est_halal', isset($_POST['update_halal']), PDO::PARAM_BOOL);
    }
    if ($casher !== $optionsResto['est_casher']) {
      $req_update2->bindValue(':est_casher', isset($_POST['update_casher']), PDO::PARAM_BOOL);
    }
    $req_update2->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification(s) effectuée(s) avec succés');
    postJournal($pdo, $user, 1, 'Informations du restaurant Modifiée(s)', 'Modification(s) effectuée avec succés');


    $data = $pdo->query("SELECT * FROM resto_infos WHERE id = '$id' ");
    $infos = $data->fetch(PDO::FETCH_ASSOC);

    $result['resultat'] = '<ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <a class="nav-link' . ($tab == 'home-tab' ? ' active' : '') . '" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Général</a>
                            </li>
                           <li class="nav-item" role="presentation">
                             <a class="nav-link' . ($tab == 'social-tab' ? ' active' : '') . '" id="social-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">Social</a>
                            </li>
                            <li class="nav-item" role="presentation">
                              <a class="nav-link' . ($tab == 'regimes-tab' ? ' active' : '') . '" id="regimes-tab" data-toggle="tab" href="#regimes" role="tab" aria-controls="regimes" aria-selected="false">Options Alimentaires</a>
                            </li>
                            <li class="nav-item" role="presentation">
                              <a class="nav-link' . ($tab == 'infos-tab' ? ' active' : '') . '" id="infos-tab" data-toggle="tab" href="#infos" role="tab" aria-controls="infos" aria-selected="false">Informations</a>
                            </li>
                          </ul>';

    $result['resultat'] .= '<div id="container_settings">';
    $result['resultat'] .= ' <form method="post" enctype="multipart/form-data" id="update_settings">
                              <input type="hidden" name="update_id" id="update_id" value="' . $infos['token'] . '">
                              <input type="hidden" name="tab_id" id="tab_id" value="' . $tab . '">

                              <div class="tab-content">';

    $result['resultat'] .= '<div class="tab-pane' . ($tab == 'home-tab' ? ' active' : '') . '" id="home" role="tabpanel" aria-labelledby="home-tab">
                              <div class="mb-3 mt-4">
                                <div class="container-fluid settings_container" id="settings_container">
                                  <div class="row">
                                    <div class="col-sm-9 col-md-8 left_part">

                                      <div class="mb-3">
                                        <label for="update_name" class="form-label">Nom de votre restaurant :</label>
                                        <input type="text" class="form-control" name="update_name" id="update_name" placeholder="Le nom de votre restaurant" value="' . $infos['name'] . '">
                                      </div>

                                      <div class="mb-3 mt-4">
                                        <label class="form_label">Coordonnées : </label>
                                        <div class="settings_container-element">
                                          <div class="mb-3 mt-4">
                                            <label for="update_adresse">Adresse : </label>
                                            <input type="text" class="form-control" name="update_adresse" id="update_adresse" placeholder="L\'adresse de votre restaurant" value="' . $infos['adresse'] . '">
                                          </div>
                                          <div class="mb-3 mt-4">
                                            <label for="update_complements">Compléments d\'adresse : </label>
                                            <input type="text" class="form-control" name="update_complements" id="update_complements" value="' . $infos['complements'] . '">
                                          </div>
                                          <div class="mb-3 mt-4">
                                            <label for="update_cp">Code postal : </label>
                                            <input type="text" class="form-control" name="update_cp" id="update_cp" placeholder="Indiquer le code postal de votre restaurant" value="' . $infos['cp'] . '">
                                          </div>
                                          <div class="mb-3 mt-4">
                                            <label for="update_city">Ville : </label>
                                            <input type="text" class="form-control" name="update_city" id="update_city" placeholder="indiquer la ville de votre restaurant" value="' . $infos['ville'] . '">
                                          </div>
                                          <div class="mb-3 mt-4">
                                            <label for="update_email">Email : </label>
                                            <input type="email" class="form-control" name="update_email" id="update_email" placeholder="Adresse email principale de votre restaurant" value="' . $infos['email'] . '">
                                          </div>
                                          <div class="mb-3 mt-4">
                                            <label for="update_phone">téléphone : </label>
                                            <input type="tel" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" class="form-control" name="update_phone" id="update_phone" placeholder="Numéro de téléphone principal de votre restaurant" value="' . $infos['telephone'] . '">
                                          </div>
                                        </div>
                                      </div>

                                      <div class="mb-3 mt-4">
                                        <label for="update_description" class="form_label">Informations Légale : </label>
                                        <div class="settings_container-element">
                                          <div class="mb-3 mt-4">
                                            <label for="update_siret">SIRET : </label>
                                            <input type="text" class="form-control" name="update_siret" id="update_siret" placeholder="le numéro de SIRET de votre restaurant" value="' . $infos['siret'] . '">
                                          </div>
                                          <div class="mb-3 mt-4">
                                            <label for="update_tva">Numéro de TVA : </label>
                                            <input type="text" class="form-control" name="update_tva" id="update_tva" placeholder="le numéro de TVA de votre restaurant" value="' . $infos['tva'] . '">
                                          </div>
                                        </div>
                                      </div>


                                    </div> <!--  fin left part-->

                                    <div class="col-sm-3 col-md-4 right_part">



                                      <label class="label_container">Logo : </label>
                                      <div class="mb-3 settings_container-element">
                                        <input type="hidden" name="update_img" id="update_img" value="' . $infos['photo_id'] . '">
                                        <div class="img-cat-container">' . GeneralClass::getLogo($pdo, $infos['photo_id'], $ua['name']) . '</div>
                                        <input type="file" name="new_img" id="new_img" title="Changez image du plat" placeholder="indiquez la description du plat">
                                        <label for="new_img">Changer le logo</label>
                                      </div>

                                    </div>

                                  </div>
                                </div>
                              </div>
                           </div>';

    $result['resultat'] .= '<div class="tab-pane' . ($tab == 'social-tab' ? ' active' : '') . '" id="social" role="tabpanel" aria-labelledby="profile-tab">
                              <div class="mb-3 mt-4">
                                <div class="rs_label-container">
                                  <label class="label_container">Réseaux sociaux : </label>
                                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>

                                  <div class="input_help">
                                    <p>
                                      Copiez l\'url de vos profils dans les champs corresponants
                                    </p>
                                  </div>
                                </div>

                                <div class="mb-3 settings_container-element">
                                  <div class="mb-4  input-block">
                                    <label for="update_insta"><i class="fa-brands fa-instagram"></i> Instagram : </label>
                                    <div>
                                      <input type="text" class="form-control" id="update_insta" name="update_insta" value="' . $infos['insta'] . '">
                                    </div>
                                  </div>
                                  <div class=" mb-3  input-block">
                                    <label for="update_fb"><i class="fa-brands fa-facebook-square"></i> facebook :</label>
                                    <div>
                                      <input type="text" class="form-control" id="update_fb" name="update_fb" value="' . $infos['facebook'] . '">
                                    </div>
                                  </div>
                                  <div class=" mb-3  input-block">
                                    <label for="update_pinterest"><i class="fa-brands fa-pinterest-square"></i> Pinterest :</label>
                                    <div>
                                      <input type="text" class="form-control" id="update_pinterest" name="update_pinterest" value="' . $infos['pinterest'] . '">
                                    </div>
                                  </div>
                                  <div class=" mb-3  input-block">
                                    <label for="update_twitter"><i class="fa-brands fa-twitter-square"></i> Twitter :</label>
                                    <div>
                                      <input type="text" class="form-control" id="update_twitter" name="update_twitter" value="' . $infos['twitter'] . '">
                                    </div>
                                  </div>
                                  <div class=" mb-3  input-block">
                                    <label for="update_snap"><i class="fa-brands fa-snapchat-square"></i> Snapchat :</label>
                                    <div>
                                      <input type="text" class="form-control" id="update_snap" name="update_snap" value="' . $infos['snap'] . '">
                                    </div>
                                  </div>
                                  <div class=" mb-3  input-block">
                                    <label for="update_tiktok"><i class="fa-brands fa-tiktok"></i> TikTok :</label>
                                    <div>
                                      <input type="text" class="form-control" id="update_tiktok" name="update_tiktok" value="' . $infos['tiktok'] . '">
                                    </div>
                                  </div>
                                </div>
                             </div>
                           </div>';

    $id = $infos['id'];
    $query = $pdo->query("SELECT * FROM resto_options WHERE resto_id = '$id' ");
    $optionsResto = $query->fetch(PDO::FETCH_ASSOC);

    $result['resultat'] .=  '<div class="tab-pane' . ($tab == 'regimes-tab' ? ' active' : '') . '" id="regimes" role="tabpanel" aria-labelledby="messages-tab">
                              <div class="mb-3 mt-4">
                                <label for="update_est_epice" class="form_label">Informations alimentaire : </label>
                                <div class="settings_container-element">
                                  <div class="input-block">
                                    <label for="update_est_epice">Votre restaurant propose t\'il des spécialités épicées: </label>
                                    <div>
                                      <input type="checkbox" id="update_est_epice" name="update_est_epice" class="est_epice" value="' . $optionsResto['est_epice'] . '" ' . ($optionsResto['est_epice'] ? 'checked' : '') . '>
                                      <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                                      <div class="input_help">
                                        <p>
                                          Cocher cette case si votre restaurant propose des plats épicée.
                                        </p>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                              </div>

                              <div class="mb-3 mt-4">
                                <label class="form_label">Régimes alimentaires : </label>
                                <div class="settings_container-element" id="regimes_alimentaire">

                                  <div class=" mb-3  input-block">
                                    <label for="update_vege">Spécialités végétarienne: </label>
                                    <div>
                                      <input type="checkbox" id="update_vege" name="update_vege" class="est_vege" value="' . $optionsResto['est_vege'] . '" ' . ($optionsResto['est_vege'] ? 'checked' : '') . '>
                                      <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                                      <div class="input_help">
                                        <p>
                                          Cocher cette case si votre restaurant propose des spécialités végétarienne.
                                        </p>
                                      </div>
                                    </div>
                                  </div>

                                  <div class=" mb-3  input-block">
                                    <label for="update_vegan">Spécialités végan: </label>
                                    <div>
                                      <input type="checkbox" id="update_vegan" name="update_vegan" value="' . $optionsResto['est_vegan'] . '"' . ($optionsResto['est_vegan'] ? 'checked' : '') . '>
                                      <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                                      <div class="input_help">
                                        <p>
                                          Cocher cette case si votre restaurant propose des spécialités vegan.
                                        </p>
                                      </div>
                                    </div>
                                  </div>

                                  <div class=" mb-3  input-block">
                                    <label for="update_halal">Spécialités halal: </label>
                                    <div>
                                      <input type="checkbox" id="update_halal" name="update_halal" value="' . $optionsResto['est_halal'] . '"' . ($optionsResto['est_halal'] ? 'checked' : '') . '>
                                      <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                                      <div class="input_help">
                                        <p>
                                          Cocher cette case si votre restaurant propose des spécialités halal.
                                        </p>
                                      </div>
                                    </div>
                                  </div>

                                  <div class=" mb-3  input-block">
                                    <label for="update_casher">Spécialités casher: </label>
                                    <div>
                                      <input type="checkbox" id="update_casher" name="update_casher" value="' . $optionsResto['est_casher'] . '"' . ($optionsResto['est_casher'] ? 'checked' : '') . '>
                                      <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                                      <div class="input_help">
                                        <p>
                                          Cocher cette case si votre restaurant propose des spécialités casher
                                        </p>
                                      </div>
                                    </div>
                                  </div>


                                </div>
                             </div>
                           </div>';

    $result['resultat'] .= '<div class="tab-pane' . ($tab == 'infos-tab' ? ' active' : '') . '" id="infos" role="tabpanel" aria-labelledby="settings-tab">
                              <div class="mb-3 mt-4">
                                <label class="label_container">Informations : </label>
                                <div class="mb-3 settings_container-element">


                                  <div class=" mb-3 mt-4 input-block text_block">
                                    <Label>Crée par :</Label>
                                    <p>' . General::getMembre($pdo, $infos['author_id']) . '</p>
                                  </div>

                                  <div class=" mb-3 mt-4 input-block text_block">
                                    <Label>Crée le :</Label>
                                    <time>' . date('d-m-Y', strtotime($infos['date_enregistrement'])) . '</time>
                                  </div>';

    if ($infos['date_modification']) {
      $result['resultat'] .= '<div class=" mb-3 mt-4 input-block text_block">
                                                                <Label>Modifié le :</Label>
                                                                <time>' . date('d-m-Y', strtotime($infos['date_modification'])) . '</time>
                                                              </div>';
    }



    $result['resultat'] .= '</div>
                              </div>
                           </div>';

    $result['resultat'] .= '</div>';

    $result['resultat'] .= '<div class="mb-3 mt-4 submit_container">
                              <button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier les informations</button>
                             <div class="load-update hide" id="load-update">
                               <img src="Assets/Images/loader.gif" alt="Loader">
                               <p>Chargement ...</p>
                             </div>
                           </div>

                          </form></div>';
  }
}
// Return result 
echo json_encode($result);
