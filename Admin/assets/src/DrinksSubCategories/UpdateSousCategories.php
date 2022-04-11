<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksSubCategoriesFunctions.php';

use App\General;
use App\DrinksSubCategories;

/* #############################################################################

Update d'une sous categories a partir  DrinksSub.php  en Ajax

############################################################################# */

$user = $user['id_team_member'];

$result = array();

if (!empty($_POST)) {

  $id = $_POST['update_id'];
  if (isset($_POST['update_cat'])) {
    $catparent = $_POST['update_cat'];
  } else {
    $catparent = NULL;
  }
  $titre =  htmlspecialchars($_POST['update_titre']);
  if ($options['show_cat_drink_description']) {
    $description = htmlspecialchars($_POST['update_description']);
  } else {
    $description = NULL;
  }
  if (isset($_POST['update_publie'])) {
    $publie = 1;
  } else {
    $publie = 0;
  }


  $data = $pdo->query("SELECT * FROM boissons_sous_categories WHERE id = '$id'");
  $thisCat = $data->fetch(PDO::FETCH_ASSOC);

  // debut de la requete d'update
  $param = FALSE;
  $requete = 'UPDATE boissons_sous_categories SET ';

  if (empty($titre)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le titre');
    postJournal($pdo, $user, 5, 'Tentative de modification d\'une sous catégorie de boissons', 'Titre manquant');
  } else {

    if ($titre !== $thisCat['titre']) {
      if (!preg_match('~^[a-zàâçéèêëîïôûùüÿñæœA-Z- ]+$~', $titre)) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'oups! des caractéres ne sont pas autorisé');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'une sous catégorie de boissons', 'Caractére non autorisé');
      } else if (getCatBy($pdo, 'titre', $titre) !== null) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'oups cette catégorie existe déjà');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'une sous catégorie de boissons', 'Catégorie déjà existante');
      } else {
        $requete .= ' titre = :titre';

        $param = TRUE;
      }
    }
  }

  if ($catparent !== NULL) {
    if ($catparent !== $thisCat['cat_id']) {
      if ($param == TRUE) {
        $requete .= ', cat_id = :cat_id';
      } else {
        $requete .= 'cat_id = :cat_id';
      }
      $param = TRUE;
    }
  }

  if ($options['show_cat_drink_description']) {
    if ($description !== $thisCat['description']) {
      if ($param == TRUE) {

        $requete .= ', description = :description';
      } else {

        $requete .= 'description = :description';
      }

      $param = TRUE;
    }
  }



  if ($publie != $thisCat['est_publie']) {
    if ($param == TRUE) {
      $requete  .= ', est_publie = :est_publie';
    } else {
      $requete .= 'est_publie = :est_publie';
    }
    $param = TRUE;
  }

  if ($options['show_sub_cat_drink_photo']) {

    if (!empty($_FILES['new_img']['tmp_name'])) {


      $img = $thisCat['photo_id'];
      $path = __DIR__ . '/../../../../Global/Uploads/';
      $pathOriginal = __DIR__ . '/../../../../Global/Uploads/Originals/';
      $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
      $allowTypes = array('jpg', 'png', 'jpeg');

      if ($_FILES['new_img']['error'] !== UPLOAD_ERR_OK) {


        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
        postJournal($pdo, $user, 5, 'Tentative de modification d\'une sous catégorie de boissons', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['new_img']['error']);
      } elseif ($_FILES['new_img']['size'] < 12) {

        $result['status'] = false;
        $result['notif'] = General::notif('error', 'Le fichier envoyé n\'est pas une image');
        postJournal($pdo, $user, 4, 'Tentative de modification d\'une sous catégorie de boissons', 'Le fichier envoyé n\'est pas une image');
      } elseif (!in_array($extension, $allowTypes)) {
        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'Format d\'image non pris en charge');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'une sous catégorie de boissons', 'Format d\'image non pris en charge');
      } else {

        do {
          $filename = bin2hex(random_bytes(16));
          $complete_path = $pathOriginal  . $filename . '.' . $extension;
        } while (file_exists($complete_path));

        if (!move_uploaded_file($_FILES['new_img']['tmp_name'], $complete_path)) {
          $result['status'] = false;
          $result['notif'] = General::notif('error', 'La photo n\'a pas pu être enregistrée');
          postJournal($pdo, $user, 4, 'Tentative de modification d\'une sous catégorie de boissons', 'La photo n\'a pas pu être enregistrée');
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

  //lancement de la requete
  $requete .= ' WHERE id= ' . $id;

  //préparation de la requete
  $req_update = $pdo->prepare($requete);

  if ($titre !== $thisCat['titre']) {
    $req_update->bindParam(':titre', $titre);
  }
  if ($catparent !== NULL) {
    if ($catparent !== $thisCat['cat_id']) {
      $req_update->bindParam(':cat_id', $catparent, PDO::PARAM_INT);
    }
  }
  if ($options['show_cat_drink_description']) {
    if ($description !== $thisCat['description']) {
      $req_update->bindParam(':description', $description);
    }
  }
  if ($options['show_sub_cat_drink_photo']) {
    if (isset($newImg)) {
      $req_update->bindValue(':photo_id', $newImg);
    }
  }
  if ($publie != $thisCat['est_publie']) {
    $req_update->bindValue(':est_publie', isset($_POST['update_publie']), PDO::PARAM_BOOL);
  }
  $req_update->execute();

  $result['status'] = true;
  $result['notif'] = General::notif('success', 'Sous Catégorie modifiée');
  postJournal($pdo, $user, 1, 'Catégorie modifiée', 'Sous Catégorie modifiée # ' . $thisCat['id']);

  $record_per_page = 10;
  $page = 0;
  $ua = getBrowser();


  if (isset($_POST['page'])) {

    $page = $_POST['page'];
  } else {

    $page = 1;
  }

  $start_from = ($page - 1) * $record_per_page;

  $query = $pdo->query("SELECT * FROM boissons_sous_categories ORDER BY position ASC LIMIT $start_from,$record_per_page");

  $result['nbdrinkcat'] = countSousCat($pdo);

  $result['resultat'] = '<table>

            <thead>
              <tr>
                <th class="dnone">ID</th>
                <th class="tab desktop" title="Position de la sous categorie sur la carte">#</th>
                <th>Titre</th>';
  if ($options['show_sub_cat_drink_photo'] == 1) {
    $result['resultat'] .= '<th >Image</th>';
  }
  $result['resultat'] .= '<th class="tab desktop">Catégorie Parente</th>';
  if ($options['show_cat_drink_description'] == 1) {
    $result['resultat'] .= '<th class="desktop">Description</th>';
  }
  $result['resultat'] .= '<th class="desktop"># Boissons</th>';
  $result['resultat'] .= '<th class="tab desktop">Afficher</th>
                <th>Actions</th>';
  $result['resultat'] .= ' </tr></thead>';
  $result['resultat'] .= '<tbody class="page_list">';

  while ($row = $query->fetch()) {

    $result['resultat'] .= '<tr data-position="' . $row['position'] . '" data-id="' . $row['id'] . '">';
    $result['resultat'] .= '<td class="tab desktop">' . $row['position'] . '</td>';
    $result['resultat'] .= '<td id="' . $row['id'] . '"class="dnone">' . $row['id'] . '</td>';
    $result['resultat'] .= '<td><strong>' . $row['titre'] . '</strong></td>';
    if ($options['show_sub_cat_drink_photo'] == 1) {
      $result['resultat'] .= '<td class="dnone cat_pics">' . $row['photo_id'] . '</td>';
      $result['resultat'] .= '<td class="td-team">' . DrinksSubCategories::getImage($pdo, $row['photo_id'], $ua['name']) . '</td>';
    }
    $result['resultat'] .= '<td class="tab desktop"><strong>' . DrinksSubCategories::getParent($pdo, $row['cat_id']) . '</strong></td>';
    if ($options['show_cat_drink_description'] == 1) {
      $result['resultat'] .= '<td class="desktop">' . substr($row['description'], 0, 45) .  '</td>';
    }
    $result['resultat'] .= '<td class="desktop">' . DrinksSubCategories::getNbDrinks($pdo, $row['id']) . '</td>';

    if ($row['est_publie'] == 1) {

      $result['resultat'] .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . ' checked></td>';
    } else {

      $result['resultat'] .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . '></td>';
    }
    $result['resultat'] .= '<td class="member_action">';
    $result['resultat'] .= '<div class="member_action-container">';
    $result['resultat'] .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
    $result['resultat'] .= '<input type="button" class="editbtn" id="' . $row['id'] . '"></input>';
    $result['resultat'] .= '<input type="button" class="deletebtn"></input>';
    $result['resultat'] .= '</div>';
    $result['resultat'] .= '</td>';
    $result['resultat'] .= '</tr>';
  }

  $result['resultat'] .= '</tbody></table>';
  if (countSousCat($pdo) > 10) {
    $result['resultat'] .= '<br /><div class="custom_pagination">';

    $page_query = $pdo->query('SELECT * FROM boissons_sous_categories ORDER BY position ASC');
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
} // fin if $_POST Global

// Return result 
echo json_encode($result);
