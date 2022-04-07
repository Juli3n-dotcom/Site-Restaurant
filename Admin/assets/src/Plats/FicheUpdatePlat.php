<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/PlatsFunctions.php';

use App\General;
use App\GeneralClass;
use App\Plats;

/* #############################################################################

Update d'un plat a partir FichePlats.php en Ajax

############################################################################# */

$user = $user['id_team_member'];

$result = array();

if (!empty($_POST)) {
  $id = $_POST['update_id'];
  $titre =  htmlspecialchars($_POST['update_titre']);
  $description = htmlspecialchars($_POST['update_description']);
  $price = htmlspecialchars($_POST['update_prix']);
  $prix = str_replace(',', '.', $price);

  if (!empty($_POST['update_cat'])) {
    $cat = $_POST['update_cat'];
  }

  if ($options['show_sous_cat']) {
    if (!empty($_POST['update_souscat'])) {
      $subcat = $_POST['update_souscat'];
    } else {
      $subcat = NULL;
    }
  }

  if (isset($_POST['update_est_epice'])) {
    $epice = 1;
    $epice_level = $_POST['updateepicelevel'];
  } else {
    $epice = 0;
    $epice_level = 0;
  }

  if (isset($_POST['update_haveallergene'])) {
    $haveallergene = 1;

    $query = $pdo->query("SELECT * FROM plats_allergenes_liaison WHERE plat_id = '$id'");
    $plat = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($plat as $a) {
      $liaisonID = $a['id'];
      $req = $pdo->exec("DELETE FROM plats_allergenes_liaison WHERE id = '$liaisonID'");
    }

    $allergenes =  $_POST['update_allergenes'];

    foreach ($allergenes as $a) {
      $allergene = $a;
      $req3 = $pdo->prepare('INSERT INTO plats_allergenes_liaison 
                              (plat_id,allergene_id)VALUES(:plat_id,:allergene_id)');
      $req3->bindParam(':plat_id', $id, PDO::PARAM_INT);
      $req3->bindParam(':allergene_id', $a, PDO::PARAM_INT);
      $req3->execute();
    }
  } else {
    $haveallergene = 0;

    $query = $pdo->query("SELECT * FROM plats_allergenes_liaison WHERE plat_id = '$id'");
    $plat = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($plat as $a) {
      $liaisonID = $a['id'];
      $req = $pdo->exec("DELETE FROM plats_allergenes_liaison WHERE id = '$liaisonID'");
    }
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

  if ($options['show_plat_en_avant']) {
    if (isset($_POST['update_en_avant'])) {
      $en_avant = 1;
    } else {
      $en_avant = 0;
    }
  }

  if (isset($_POST['update_publie'])) {
    $publie = 1;
  } else {
    $publie = 0;
  }

  if (isset($_POST['update_nouveau'])) {
    $new = 1;
  } else {
    $new = 0;
  }


  $data = $pdo->query("SELECT * FROM plats WHERE id = '$id'");
  $plat = $data->fetch(PDO::FETCH_ASSOC);

  // debut de la requete d'update
  $param = FALSE;
  $requete = 'UPDATE plats SET ';

  if (empty($titre)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le titre');
    postJournal($pdo, $user, 5, 'Tentative de modification d\'un de plats', 'Titre manquant');
  } else {

    if ($titre !== $plat['titre']) {
      if (!preg_match('~^[a-zàâçéèêëîïôûùüÿñæœA-Z- ]+$~', $titre)) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'oups! des caractéres ne sont pas autorisé');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'un de plats', 'Caractére non autorisé');
      } else if (getPlatBy($pdo, 'titre', $titre) !== null) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'oups ce plat existe déjà');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'un plats', 'Catégorie déjà existante');
      } else {
        $requete .= ' titre = :titre';

        $param = TRUE;
      }
    }
  }

  if ($description !== $plat['description']) {
    if ($param == TRUE) {

      $requete .= ', description = :description';
    } else {

      $requete .= 'description = :description';
    }

    $param = TRUE;
  }

  if ($prix !== $plat['prix']) {
    if ($param == TRUE) {

      $requete .= ', prix = :prix';
    } else {

      $requete .= 'prix = :prix';
    }

    $param = TRUE;
  }

  if ($cat !== $plat['cat_id']) {
    if ($param == TRUE) {

      $requete .= ', cat_id = :cat_id';
    } else {

      $requete .= 'cat_id = :cat_id';
    }

    $param = TRUE;
  }

  if ($options['show_sous_cat']) {

    if (empty($_POST['update_souscat'])) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'Merci de sélectionner une sous-catégorie');
      postJournal($pdo, $user, 5, 'Tentative de modification d\'un de plats', 'Sous-catégorie manquante');
    } else {
      if ($subcat !== $plat['souscat_id']) {
        if ($param == TRUE) {

          $requete .= ', souscat_id = :souscat_id';
        } else {

          $requete .= 'souscat_id = :souscat_id';
        }

        $param = TRUE;
      }
    }
  }

  if ($epice != $plat['est_epice']) {
    if ($param == TRUE) {
      $requete  .= ', est_epice = :est_epice';
    } else {
      $requete .= 'est_epice = :est_epice';
    }
    $param = TRUE;
  }

  if (isset($_POST['update_est_epice'])) {
    if ($epice_level != $plat['epice_level']) {
      if ($param == TRUE) {
        $requete  .= ', epice_level = :epice_level';
      } else {
        $requete .= 'epice_level = :epice_level';
      }
      $param = TRUE;
    }
  }

  if ($haveallergene != $plat['have_allergenes']) {
    if ($param == TRUE) {
      $requete  .= ', have_allergenes = :have_allergenes';
    } else {
      $requete .= 'have_allergenes = :have_allergenes';
    }
    $param = TRUE;
  }

  if ($vege != $plat['est_vege']) {
    if ($param == TRUE) {
      $requete  .= ', est_vege = :est_vege';
    } else {
      $requete .= 'est_vege = :est_vege';
    }
    $param = TRUE;
  }

  if ($vegan != $plat['est_vegan']) {
    if ($param == TRUE) {
      $requete  .= ', est_vegan = :est_vegan';
    } else {
      $requete .= 'est_vegan = :est_vegan';
    }
    $param = TRUE;
  }

  if ($halal != $plat['est_halal']) {
    if ($param == TRUE) {
      $requete  .= ', est_halal = :est_halal';
    } else {
      $requete .= 'est_halal = :est_halal';
    }
    $param = TRUE;
  }

  if ($casher != $plat['est_casher']) {
    if ($param == TRUE) {
      $requete  .= ', est_casher = :est_casher';
    } else {
      $requete .= 'est_casher = :est_casher';
    }
    $param = TRUE;
  }

  if ($options['show_plat_en_avant']) {
    if ($en_avant != $plat['est_en_avant']) {
      if ($param == TRUE) {
        $requete  .= ', update_en_avant = :update_en_avant';
      } else {
        $requete .= 'update_en_avant = :update_en_avant';
      }
      $param = TRUE;
    }
  }

  if ($publie != $plat['est_publie']) {
    if ($param == TRUE) {
      $requete  .= ', est_publie = :est_publie';
    } else {
      $requete .= 'est_publie = :est_publie';
    }
    $param = TRUE;
  }

  if ($new != $plat['est_nouveau']) {
    if ($param == TRUE) {
      $requete  .= ', est_nouveau = :est_nouveau';
    } else {
      $requete .= 'est_nouveau = :est_nouveau';
    }
    $param = TRUE;
  }

  if ($options['show_plat_photo']) {

    if (!empty($_FILES['new_img']['tmp_name'])) {


      $img = $plat['photo_id'];
      $path = __DIR__ . '/../../../../Global/Uploads/';
      $pathOriginal = __DIR__ . '/../../../../Global/Uploads/Originals/';
      $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
      $allowTypes = array('jpg', 'png', 'jpeg');

      if ($_FILES['new_img']['error'] !== UPLOAD_ERR_OK) {


        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
        postJournal($pdo, $user, 5, 'Tentative de modification d\'un plat', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
      } elseif ($_FILES['new_img']['size'] < 12) {

        $result['status'] = false;
        $result['notif'] = General::notif('error', 'Le fichier envoyé n\'est pas une image');
        postJournal($pdo, $user, 4, 'Tentative de modification d\'un plat', 'Le fichier envoyé n\'est pas une image');
      } elseif (!in_array($extension, $allowTypes)) {
        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'Format d\'image non pris en charge');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'un plat', 'Format d\'image non pris en charge');
      } else {

        do {
          $filename = bin2hex(random_bytes(16));
          $complete_path = $pathOriginal  . $filename . '.' . $extension;
        } while (file_exists($complete_path));

        if (!move_uploaded_file($_FILES['new_img']['tmp_name'], $complete_path)) {
          $result['status'] = false;
          $result['notif'] = General::notif('error', 'La photo n\'a pas pu être enregistrée');
          postJournal($pdo, $user, 4, 'Tentative de modification d\'un plat', 'La photo n\'a pas pu être enregistrée');
        } else {

          if (!is_null($img)) {

            // suppression des anciennes Images
            $data = $pdo->query("SELECT * FROM plats_photo WHERE id_photo = '$img'");
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
          }

          if (is_null($img)) {
            # si la catégorie ne possede pas d'image, enregistrement en BDD de la photo,
            $req1 = $pdo->prepare(
              'INSERT INTO plats_photo(img__jpeg, img__webp, original)
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
            $req_update_photo = $pdo->prepare('UPDATE plats_photo SET img__jpeg = :img__jpeg, 
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
  } // fin if photo

  if ($param == TRUE) {
    $requete  .= ', date_modification = :date_modification';
  } else {
    $requete .= 'date_modification = :date_modification';
  }
  $param = TRUE;

  //lancement de la requete
  $requete .= ' WHERE id= ' . $id;

  //préparation de la requete
  $req_update = $pdo->prepare($requete);

  if ($titre !== $plat['titre']) {
    $req_update->bindParam(':titre', $titre);
  }
  if ($description !== $plat['description']) {
    $req_update->bindParam(':description', $description);
  }
  if ($prix !== $plat['prix']) {
    $req_update->bindParam(':prix', $prix);
  }
  if ($cat !== $plat['cat_id']) {
    $req_update->bindParam(':cat_id', $cat);
  }
  if ($options['show_sous_cat']) {
    if ($subcat !== $plat['souscat_id']) {
      $req_update->bindParam(':souscat_id', $subcat);
    }
  }
  if ($epice != $plat['est_epice']) {
    $req_update->bindValue(':est_epice', isset($_POST['update_est_epice']), PDO::PARAM_BOOL);
  }
  if (isset($_POST['update_est_epice'])) {
    if ($epice_level != $plat['epice_level']) {
      $req_update->bindValue(':epice_level', $epice_level);
    }
  }
  if ($haveallergene != $plat['have_allergenes']) {
    $req_update->bindValue(':have_allergenes', isset($_POST['update_haveallergene']), PDO::PARAM_BOOL);
  }
  if ($options['show_plat_photo']) {
    if (isset($newImg)) {
      $req_update->bindValue(':photo_id', $newImg);
    }
  }
  if ($vege != $plat['est_vege']) {
    $req_update->bindValue(':est_vege', isset($_POST['update_vege']), PDO::PARAM_BOOL);
  }
  if ($vegan != $plat['est_vegan']) {
    $req_update->bindValue(':est_vegan', isset($_POST['update_vegan']), PDO::PARAM_BOOL);
  }
  if ($halal != $plat['est_halal']) {
    $req_update->bindValue(':est_halal', isset($_POST['update_halal']), PDO::PARAM_BOOL);
  }
  if ($casher != $plat['est_casher']) {
    $req_update->bindValue(':est_casher', isset($_POST['update_casher']), PDO::PARAM_BOOL);
  }
  if ($options['show_plat_en_avant']) {
    if ($en_avant != $plat['est_en_avant']) {
      $req_update->bindValue(':est_en_avant', isset($_POST['update_en_avant']), PDO::PARAM_BOOL);
    }
  }
  if ($publie != $plat['est_publie']) {
    $req_update->bindValue(':est_publie', isset($_POST['update_publie']), PDO::PARAM_BOOL);
  }
  if ($new != $plat['est_nouveau']) {
    $req_update->bindValue(':est_nouveau', isset($_POST['update_nouveau']), PDO::PARAM_BOOL);
  }
  $req_update->bindValue(':date_modification', (new DateTime())->format('Y-m-d H:i:s'));
  $req_update->execute();


  $result['status'] = true;
  $result['notif'] = General::notif('success', 'Plat Modifié');
  postJournal($pdo, $user, 1, 'Plat Modifié', 'Le plat ' . $plat['titre'] . ' Modifié');

  $ua = getBrowser();
  $query = $pdo->query("SELECT * FROM plats WHERE id = '$id'");
  $plat = $query->fetch();

  $result['resultat'] = '<form action="" method="post" enctype="multipart/form-data" id="update_plat">
                           <input type="hidden" name="update_id" id="update_id" value="' . $plat['id'] . '">';
  $result['resultat'] .= '<div class="container-fluid plat_container" id="plat_container">
                            <div class="row">';

  $result['resultat'] .= '<div class="col-sm-9 col-md-8 left_part">';

  $result['resultat'] .= '<div class="mb-3">
            <label for="update_titre" class="form-label">Titre :</label>
            <input type="text" class="form-control" name="update_titre" id="update_titre" placeholder="Votre titre" value="' . $plat['titre'] . '">
          </div>';

  $result['resultat'] .= '<div class="mb-3 mt-4">
                            <label for="update_prix">Prix : </label>
                            <input type="text" class="form-control" name="update_prix" id="update_prix" value="' . $plat['prix'] . '">
                           </div>';

  $result['resultat'] .= '<div class="mb-3 mt-4">
                            <label for="update_description" class="form_label">Description : </label>
                            <textarea rows="5" cols="33" name="update_description" id="update_description" class="form-control" placeholder="indiquez la description du plat">' . $plat['description'] . '</textarea>
                          </div>';

  $result['resultat'] .= '<div class="mb-3 mt-4">
                            <label for="update_est_epice" class="form_label">Informations alimentaire : </label>
                            < class="plat_container-element">';

  $result['resultat'] .= '<div class="input-block">
                <label for="update_est_epice">S\' agit il d\'un plat épicé: </label>
                <div>';
  if ($plat['est_epice']) {
    $result['resultat'] .= '<input type="checkbox" id="update_est_epice" name="update_est_epice" class="est_epice" value="' . $plat['est_epice'] . '" checked>';
  } else {
    $result['resultat'] .= '<input type="checkbox" id="update_est_epice" name="update_est_epice" class="est_epice" value="' . $plat['est_epice'] . '">';
  }
  $result['resultat'] .= '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est épicé.
                    </p>
                  </div>
                </div>
              </div>';


  $result['resultat'] .= ' <div class=" mb-3 mt-4 update_epicelevel ' . ($plat['est_epice'] ? 'show' : '') . '" id="update_epicelevel">
                <label for="epicelevel">Niveau d\'épices :</label>
                <div class="form-check-container">';

  $result['resultat'] .=  '<div class="form-check">
                            <input class="form-check-input" type="radio" name="updateepicelevel" id="updateepicelevel1" value="1" ' . ($plat['epice_level'] == 1 ? 'checked' : '') . '>';
  $result['resultat'] .=   '<label class="form-check-label" for="updateepicelevel">  Un peu épicé ' . Plats::getNbPiment(1, $ua["name"]) . '</label>';
  $result['resultat'] .=  '</div>';
  $result['resultat'] .=  '<div class="form-check">
                            <input class="form-check-input" type="radio" name="updateepicelevel" id="updateepicelevel2" value="2" ' . ($plat['epice_level'] == 2 ? 'checked' : '') . '>';
  $result['resultat'] .=   '<label class="form-check-label" for="updateepicelevel">  Un peu épicé ' . Plats::getNbPiment(2, $ua["name"]) . '</label>';
  $result['resultat'] .=  '</div>';
  $result['resultat'] .=  '<div class="form-check">
                            <input class="form-check-input" type="radio" name="updateepicelevel" id="updateepicelevel3" value="3" ' . ($plat['epice_level'] == 3 ? 'checked' : '') . '>';
  $result['resultat'] .=   '<label class="form-check-label" for="updateepicelevel">  Un peu épicé ' . Plats::getNbPiment(3, $ua["name"]) . '</label>';
  $result['resultat'] .=  '</div>';

  $result['resultat'] .= ' </div>
              </div>';

  $result['resultat'] .= '<div class=" mb-3 mt-4 input-block">
                <label>Votre plat contient t\'il des allergénes ? : </label>
                <div>
                  <input type="checkbox" id="update_haveallergene" name="update_haveallergene" value="' . $plat['have_allergenes'] . '" ' . ($plat['have_allergenes'] ? 'checked' : '') . '>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Vous permet de selectionner les allergénes du plat
                    </p>
                  </div>
                </div>
              </div>';


  // récupération des allergenes déjà présent dans le plat
  $allergenesIdArray = getAllergeneCheck($pdo, $id);

  $result['resultat'] .= '<div class="mb-3 mt-4 update_allergenecontainer ' . ($plat['have_allergenes'] ? 'show' : '') . '" id="update_allergenecontainer">';
  foreach (getAllergenes($pdo) as $allergene) {
    $result['resultat'] .= '<div class="form-check allergene-element">
                    <input type="checkbox" value="<?= $allergene["id"] ?>" name="update_allergenes[]" id="update_allergenes" ' . (in_array($allergene['id'], $allergenesIdArray) ? 'checked' : '') . '>
                    <label class="form-check-label" for="update_allergenes">' . $allergene['titre'] . '</label>
                    <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                    <div class="input_help">
                      <div>
                        <h6>Description :</h6>
                        <p>' . $allergene['description'] . '</p>
                      </div>';
    if ($allergene['exclusions']) {
      $result['resultat'] .= '<div>
                          <h6>Exclusions :</h6>
                          <p>' . $allergene['exclusions'] . '</p>
                        </div>';
    }
    $result['resultat'] .= ' </div>
                  </div>';
  }
  $result['resultat'] .= '</div>

            </div>
          </div>';

  $result['resultat'] .= '<div class="mb-3 mt-4">
            <label class="form_label">Régimes alimentaires : </label>
            <div class="plat_container-element" id="regimes_alimentaire">

              <div class=" mb-3  input-block">
                <label for="update_vege">S\'agit il d\'un plat végétarien: </label>
                <div>
                  <input type="checkbox" id="update_vege" name="update_vege" class="est_vege" value="' . $plat['est_vege'] . '" ' . ($plat['est_vege'] ? 'checked' : '') . '>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est végétarien.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3  input-block">
                <label for="update_vegan">S\'agit il d\'un plat végan: </label>
                <div>
                  <input type="checkbox" id="update_vegan" name="update_vegan" value="' . $plat['est_vegan'] . '" ' . ($plat['est_vegan'] ? 'checked' : '') . '>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est végan.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3  input-block">
                <label for="update_halal">S\'agit il d\'un plat halal: </label>
                <div>
                  <input type="checkbox" id="update_halal" name="update_halal" value="' . $plat['est_halal'] . '" ' . ($plat['est_halal'] ? 'checked' : '') . '>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est halal.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3  input-block">
                <label for="update_casher">S\'agit il d\'un plat casher: </label>
                <div>
                  <input type="checkbox" id="update_casher" name="update_casher" value="' . $plat['est_casher'] . '" ' . ($plat['est_casher'] ? 'checked' : '') . '>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est cacher.
                    </p>
                  </div>
                </div>
              </div>


            </div>
          </div>';

  $result['resultat'] .= '<div class="mb-3 mt-4 submit_container">
            <button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier le plat</button>
            <div class="load-update hide" id="load-update">
              <img src="Assets/Images/loader.gif" alt="Loader">
              <p>Chargement ...</p>
            </div>
          </div>';

  $result['resultat'] .= '</div>';



  $result['resultat'] .= '<div class="col-sm-3 col-md-4 right_part">';

  $result['resultat'] .= '<label class="label_container"> Etat et visibilité : </label>
          <div class="mb-3 plat_container-element">

            <div class=" mb-3 mt-4 input-block">
              <label for="update_publie">Afficher le plat: </label>
              <div>
                <input type="checkbox" id="update_publie" name="update_publie" value="' . $plat['est_publie'] . '" ' . ($plat['est_publie'] ? 'checked' : '') . '>
                <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                <div class="input_help">
                  <p>Cocher cette case pour ajouter le plat au menu</p>
                </div>
              </div>
            </div>';

  if ($options['show_plat_en_avant']) {
    $result['resultat'] .= '<div class=" mb-3 mt-4 input-block">
                <label for="update_en_avant">Mettre en avant le plat: </label>
                <div>
                  <input type="checkbox" id="update_en_avant" name="update_en_avant" value="' . $plat['est_en_avant'] . '" ' . ($plat['est_en_avant'] ? 'checked' : '') . '>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Vous permet de promouvoir le plat.
                    </p>
                  </div>
                </div>
              </div>';
  }


  $result['resultat'] .= '<div class=" mb-3 mt-4 input-block">
              <label for="update_nouveau">Afficher <span class="new"> nouveau</span> sur le plat: </label>
              <div>
                <input type="checkbox" id="update_nouveau" name="update_nouveau" value="' . $plat['est_nouveau'] . '" ' . ($plat['est_nouveau'] ? 'checked' : '') . '>
                <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                <div class="input_help">
                  <p>
                    vous donner la possibilité d\' afficher le logo <span class="new"> nouveau</span>
                    sur le plat.
                  </p>
                </div>
              </div>
            </div>';

  $result['resultat'] .= ' </div>';

  $result['resultat'] .= '<label class="label_container">Catégorie: </label>
          <div class="mb-3 plat_container-element">
            <div class=" mb-3 mt-4 input-block text_block">
              <Label>Catégorie :</Label>
              <select class="form-select" name="update_cat" id="update_cat" aria-label="">
                <option value="' . $plat['cat_id'] . '" selected>' . Plats::getCat($pdo, $plat['cat_id']) . '</option>';
  foreach (getCat($pdo, $plat['cat_id']) as $cat) {
    $result['resultat'] .= ' <option value="' . $cat['id'] . '">' . $cat['titre'] . '</option>';
  }
  $result['resultat'] .=  '</select>
            </div>';

  if ($options['show_sous_cat']) {
    $result['resultat'] .= ' <div class=" mb-3 mt-4 input-block text_block">
                <Label>Sous-catégorie :</Label>
                <select class="form-select" name="update_souscat" id="update_souscat" aria-label="">
                  <option value="' . $plat['souscat_id'] . '" selected>' . Plats::getSousCat($pdo, $plat['souscat_id']) . '</option>';
    foreach (getSubCat($pdo, $plat['cat_id']) as $subCat) {
      $result['resultat'] .= '<option value="' . $subCat['id'] . '">' . $subCat['titre'] . '</option>';
    }
    $result['resultat'] .= '</select>
              </div>';
  }
  $result['resultat'] .= '</div>';


  if ($options['show_plat_photo']) {
    $result['resultat'] .= '<label class="label_container">Photo : </label>
            <div class="mb-3 plat_container-element">
              <input type="hidden" name="update_img" id="update_img" value="' . $plat['photo_id'] . '">
              <div class="img-cat-container">' . Plats::getPlatPhoto($pdo, $plat['photo_id'], $ua['name']) . '</div>
              <input type="file" name="new_img" id="new_img" title="Changez image du plat" placeholder="indiquez la description du plat">
              <label for="new_img">Changer la photo</label>
            </div>';
  }

  $result['resultat'] .= '<label class="label_container">Informations : </label>
          <div class="mb-3 plat_container-element">


            <div class=" mb-3 mt-4 input-block text_block">
              <Label>Crée par :</Label>
              <p>' . General::getMembre($pdo, $plat['author_id']) . '</p>
            </div>

            <div class=" mb-3 mt-4 input-block text_block">
              <Label>Crée le :</Label>
              <time>' . date('d-m-Y', strtotime($plat['date_enregistrement'])) . '</time>
            </div>';

  if ($plat['date_modification']) {
  }
  $result['resultat'] .= ' <div class=" mb-3 mt-4 input-block text_block">
                <Label>Modifié le :</Label>
                <time>' . date('d-m-Y', strtotime($plat['date_modification'])) . '</time>
              </div>';


  $result['resultat'] .=  '</div>




        </div>

      </div>
    </div>

  </form>';
}
// Return result 
echo json_encode($result);
