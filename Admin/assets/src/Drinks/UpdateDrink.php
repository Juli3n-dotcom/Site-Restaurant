<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksFunctions.php';

use App\General;
use App\Drinks;

/* #############################################################################

Update d'une boisson a partir Drinks.php en Ajax

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

  if ($options['show_drink_sous_cat']) {
    if (!empty($_POST['update_souscat'])) {
      $subcat = $_POST['update_souscat'];
    } else {
      $subcat = NULL;
    }
  }


  if (isset($_POST['update_haveallergene'])) {
    $haveallergene = 1;

    $query = $pdo->query("SELECT * FROM boissons_allergenes_liaison WHERE boisson_id = '$id'");
    $drink = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($drink as $a) {
      $liaisonID = $a['id'];
      $req = $pdo->exec("DELETE FROM boissons_allergenes_liaison WHERE id = '$liaisonID'");
    }

    $allergenes =  $_POST['update_allergenes'];

    foreach ($allergenes as $a) {
      $allergene = $a;
      $req3 = $pdo->prepare('INSERT INTO boissons_allergenes_liaison 
                              (boisson_id,allergene_id)VALUES(:boisson_id,:allergene_id)');
      $req3->bindParam(':boisson_id', $id, PDO::PARAM_INT);
      $req3->bindParam(':allergene_id', $a, PDO::PARAM_INT);
      $req3->execute();
    }
  } else {
    $haveallergene = 0;

    $query = $pdo->query("SELECT * FROM boissons_allergenes_liaison WHERE boisson_id = '$id'");
    $drink = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($drink as $a) {
      $liaisonID = $a['id'];
      $req = $pdo->exec("DELETE FROM boissons_allergenes_liaison WHERE id = '$liaisonID'");
    }
  }


  if ($options['show_drinks_en_avant']) {
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


  $data = $pdo->query("SELECT * FROM boissons WHERE id = '$id'");
  $ThisDrink = $data->fetch(PDO::FETCH_ASSOC);

  // debut de la requete d'update
  $param = FALSE;
  $requete = 'UPDATE boissons SET ';

  if (empty($titre)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le titre');
    postJournal($pdo, $user, 5, 'Tentative de modification d\'une boisson', 'Titre manquant');
  } else {

    if ($titre !== $ThisDrink['titre']) {
      if (!preg_match('~^[a-zàâçéèêëîïôûùüÿñæœA-Z-0-9 ]+$~', $titre)) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'oups! des caractéres ne sont pas autorisé');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'une boisson', 'Caractére non autorisé');
      } else if (getDrinkBy($pdo, 'titre', $titre) !== null) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'oups ce plat existe déjà');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'une boisson', 'Boisson déjà existante');
      } else {
        $requete .= ' titre = :titre';

        $param = TRUE;
      }
    }
  }

  if ($description !== $ThisDrink['description']) {
    if ($param == TRUE) {

      $requete .= ', description = :description';
    } else {

      $requete .= 'description = :description';
    }

    $param = TRUE;
  }

  if ($prix !== $ThisDrink['prix']) {
    if ($param == TRUE) {

      $requete .= ', prix = :prix';
    } else {

      $requete .= 'prix = :prix';
    }

    $param = TRUE;
  }

  if ($cat !== $ThisDrink['cat_id']) {
    if ($param == TRUE) {

      $requete .= ', cat_id = :cat_id';
    } else {

      $requete .= 'cat_id = :cat_id';
    }

    $param = TRUE;
  }

  if ($options['show_drink_sous_cat']) {

    if (empty($_POST['update_souscat'])) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'Merci de sélectionner une sous-catégorie');
      postJournal($pdo, $user, 5, 'Tentative de modification d\'une boisson', 'Sous-catégorie manquante');
    } else {
      if ($subcat !== $ThisDrink['souscat_id']) {
        if ($param == TRUE) {

          $requete .= ', souscat_id = :souscat_id';
        } else {

          $requete .= 'souscat_id = :souscat_id';
        }

        $param = TRUE;
      }
    }
  }


  if ($haveallergene != $ThisDrink['have_allergenes']) {
    if ($param == TRUE) {
      $requete  .= ', have_allergenes = :have_allergenes';
    } else {
      $requete .= 'have_allergenes = :have_allergenes';
    }
    $param = TRUE;
  }


  if ($options['show_drinks_en_avant']) {
    if ($en_avant != $ThisDrink['est_en_avant']) {
      if ($param == TRUE) {
        $requete  .= ', est_en_avant = :est_en_avant';
      } else {
        $requete .= 'est_en_avant = :est_en_avant';
      }
      $param = TRUE;
    }
  }

  if ($publie != $ThisDrink['est_publie']) {
    if ($param == TRUE) {
      $requete  .= ', est_publie = :est_publie';
    } else {
      $requete .= 'est_publie = :est_publie';
    }
    $param = TRUE;
  }

  if ($new != $ThisDrink['est_nouveau']) {
    if ($param == TRUE) {
      $requete  .= ', est_nouveau = :est_nouveau';
    } else {
      $requete .= 'est_nouveau = :est_nouveau';
    }
    $param = TRUE;
  }

  if ($options['show_drink_photo']) {

    if (!empty($_FILES['new_img']['tmp_name'])) {


      $img = $ThisDrink['photo_id'];
      $path = __DIR__ . '/../../../../Global/Uploads/';
      $pathOriginal = __DIR__ . '/../../../../Global/Uploads/Originals/';
      $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
      $allowTypes = array('jpg', 'png', 'jpeg');

      if ($_FILES['new_img']['error'] !== UPLOAD_ERR_OK) {


        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
        postJournal($pdo, $user, 5, 'Tentative de modification d\'une boisson', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
      } elseif ($_FILES['new_img']['size'] < 12) {

        $result['status'] = false;
        $result['notif'] = General::notif('error', 'Le fichier envoyé n\'est pas une image');
        postJournal($pdo, $user, 4, 'Tentative de modification d\'une boisson', 'Le fichier envoyé n\'est pas une image');
      } elseif (!in_array($extension, $allowTypes)) {
        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'Format d\'image non pris en charge');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'une boisson', 'Format d\'image non pris en charge');
      } else {

        do {
          $filename = bin2hex(random_bytes(16));
          $complete_path = $pathOriginal  . $filename . '.' . $extension;
        } while (file_exists($complete_path));

        if (!move_uploaded_file($_FILES['new_img']['tmp_name'], $complete_path)) {
          $result['status'] = false;
          $result['notif'] = General::notif('error', 'La photo n\'a pas pu être enregistrée');
          postJournal($pdo, $user, 4, 'Tentative de modification d\'une boisson', 'La photo n\'a pas pu être enregistrée');
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

  if ($titre !== $ThisDrink['titre']) {
    $req_update->bindParam(':titre', $titre);
  }
  if ($description !== $ThisDrink['description']) {
    $req_update->bindParam(':description', $description);
  }
  if ($prix !== $ThisDrink['prix']) {
    $req_update->bindParam(':prix', $prix);
  }
  if ($cat !== $ThisDrink['cat_id']) {
    $req_update->bindParam(':cat_id', $cat);
  }
  if ($options['show_drink_sous_cat']) {
    if ($subcat !== $ThisDrink['souscat_id']) {
      $req_update->bindParam(':souscat_id', $subcat);
    }
  }
  if ($haveallergene != $ThisDrink['have_allergenes']) {
    $req_update->bindValue(':have_allergenes', isset($_POST['update_haveallergene']), PDO::PARAM_BOOL);
  }
  if ($options['show_drink_photo']) {
    if (isset($newImg)) {
      $req_update->bindValue(':photo_id', $newImg);
    }
  }
  if ($options['show_drinks_en_avant']) {
    if ($en_avant != $ThisDrink['est_en_avant']) {
      $req_update->bindValue(':est_en_avant', isset($_POST['update_en_avant']), PDO::PARAM_BOOL);
    }
  }
  if ($publie != $ThisDrink['est_publie']) {
    $req_update->bindValue(':est_publie', isset($_POST['update_publie']), PDO::PARAM_BOOL);
  }
  if ($new != $ThisDrink['est_nouveau']) {
    $req_update->bindValue(':est_nouveau', isset($_POST['update_nouveau']), PDO::PARAM_BOOL);
  }
  $req_update->bindValue(':date_modification', (new DateTime())->format('Y-m-d H:i:s'));
  $req_update->execute();

  $result['status'] = true;
  $result['notif'] = General::notif('success', 'Boisson Modifiée');
  postJournal($pdo, $user, 1, 'Boisson Modifiée', 'La boisson ' . $ThisDrink['titre'] . 'a été Modifiée');


  #14 - Retour AJAX 
  $ua = getBrowser();

  $record_per_page = 10;
  $page = 0;

  if (isset($_POST['page'])) {

    $page = $_POST['page'];
  } else {

    $page = 1;
  }

  $start_from = ($page - 1) * $record_per_page;

  $query = $pdo->query("SELECT * FROM boissons ORDER BY cat_id, titre ASC LIMIT $start_from,$record_per_page");


  $result['resultat'] = '<table>

          <thead>
            <tr>
              <th class="dnone">ID</th>';
  //$result['resultat'] .= '<th class="tab desktop" title="Position de l\'element sur la carte">#</th>'
  $result['resultat'] .= '<th>Titre</th>';
  if ($options['show_drink_photo'] == 1) {
    $result['resultat'] .= '<th>Image</th>';
  }
  $result['resultat'] .= '<th class="desktop">Prix</th>';
  $result['resultat'] .= '<th class="desktop">Description</th>';
  $result['resultat'] .= '<th class="tab desktop">Catégorie</th>';
  if ($options['show_drinks_stats'] == 1) {
    $result['resultat'] .= '<th class="desktop"><i class="fas fa-chart-area"></i></th>';
  }
  if ($options['show_drinks_en_avant'] == 1) {
    $result['resultat'] .= '<th class="tab desktop">Mise en avant</th>';
  }
  $result['resultat'] .= '<th class="tab desktop">Afficher</th>
              <th>Actions</th>';
  $result['resultat'] .= ' </tr></thead>';
  $result['resultat'] .= '<tbody class="page_list">';

  while ($row = $query->fetch()) {

    //$result['resultat'] .= '<tr data-position="' . $row['position'] . '" data-id="' . $row['id'] . '">';
    // $result['resultat'] .= '<td class="tab desktop">' . $row['position'] . '</td>';
    $result['resultat'] .= '<td id="' . $row['id'] . '"class="dnone">' . $row['id'] . '</td>';
    $result['resultat'] .= '<td class="plat_title"><strong>' . $row['titre'] . '</strong>' . ($row['est_nouveau'] ? '<span class="new"> nouveau</span>' : '') . '</td>';
    if ($options['show_drink_photo'] == 1) {
      $result['resultat'] .= '<td class="dnone cat_pics">' . $row['photo_id'] . '</td>';
      $result['resultat'] .= '<td class="td-team">' . Drinks::getImage($pdo, $row['photo_id'], $ua['name']) . '</td>';
    }
    $result['resultat'] .= '<td class="desktop">' . $row['prix'] . ' €</td>';
    $result['resultat'] .= '<td class="desktop description">' . $row['description'] . '</td>';
    $result['resultat'] .= '<td class="tab desktop"><strong>' . Drinks::getCat($pdo, $row['cat_id']) . '</strong></td>';
    if ($options['show_drinks_stats'] == 1) {
      $result['resultat'] .= '<td class="desktop">0</td>';
    }
    if ($options['show_drinks_en_avant'] == 1) {
      $result['resultat'] .= '<th class="tab desktop">' . Drinks::getEstEnAvant($pdo, $row['id']) . '</th>';
    }
    if ($row['est_publie'] == 1) {

      $result['resultat'] .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . ' checked></td>';
    } else {

      $result['resultat'] .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . '></td>';
    }
    $result['resultat'] .= '<td class="member_action">';
    $result['resultat'] .= '<div class="member_action-container">';
    $result['resultat'] .= '<a href="FichePlat.php?id=' . $row['id'] . '" class="linkbtn"><i class="fa-regular fa-eye"></i></a>';
    $result['resultat'] .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
    $result['resultat'] .= '<input type="button" class="editbtn" id="' . $row['id'] . '"></input>';
    $result['resultat'] .= '<input type="button" class="deletebtn"></input>';
    $result['resultat'] .= '</div>';
    $result['resultat'] .= '</td>';
    $result['resultat'] .= '</tr>';
  }

  $result['resultat'] .= '</tbody></table>';
  if (countDrinks($pdo) > 10) {
    $result['resultat'] .= '<br /><div class="custom_pagination">';

    $page_query = $pdo->query('SELECT * FROM boissons ORDER BY cat_id, titre ASC');
    $total_records = $page_query->rowCount();
    $total_pages = ceil($total_records / $record_per_page);


    $result['resultat'] .= '<ul class="pagination">';

    if ($page > 1) {
      $previous = $page - 1;
      $result['resultat'] .= '<li class="pagination_link" id="' . $previous . '"><span class="page-link"><i class="fas fa-caret-left"></i> Précédent</span></li>';
    }

    if ($page < $total_pages) {
      $page++;
      $result['resultat'] .= '<li class="pagination_link" id="' . $page . '"><span class="page-link">Suivant <i class="fas fa-caret-right"></i></span></li>';
    }

    $result['resultat'] .= '</ul>';

    $result['resultat'] .= '</div>';
  }
}
// Return result 
echo json_encode($result);
