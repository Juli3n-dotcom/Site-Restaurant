<?php
require_once __DIR__ . '/../../Config/Init.php';
require_once __DIR__ . '/../../Functions/CategoriesFunctions.php';

use App\General;
use App\Categories;
/* #############################################################################

Ajout d'une catégorie de plats à partir categories.php en Ajax

############################################################################# */


$user = $user['id_team_member'];


if (!empty($_POST)) {
  $result = array();
  $titre =  htmlspecialchars($_POST['add_title']);
  if ($options['show_cat_description']) {
    $description = htmlspecialchars($_POST['add_description']);
  } else {
    $description = NULL;
  }

  if ($options['show_cat_pieces']) {
    if (empty($_POST['add_pieces'])) {
      $pieces = NULL;
    } else {
      $pieces = htmlspecialchars($_POST['add_pieces']);
    }
  }


  if (empty($titre) && !preg_match('~^[a-zA-Z- ]+$~', $titre)) {

    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le titre');
    postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une catégorie de plats', 'Titre manquant');
  } else if (getCatBy($pdo, 'titre', $titre) !== null) {

    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups cette catégorie existe déjà');
    postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une catégorie de plats', 'Catégorie déjà existante');
  } else {


    if ($options['show_cat_photo']) {

      if (empty($_FILES['add_img']['name'])) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'oups il manque la photo de catégorie');
        postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une catégorie de plats', 'oups il manque la photo de catégorie');
      } else {

        $extension = pathinfo($_FILES['add_img']['name'], PATHINFO_EXTENSION);
        $pathOriginal = __DIR__ . '/../../../../Global/Uploads/Originals';
        $allowTypes = array('jpg', 'png', 'jpeg');

        if ($_FILES['add_img']['error'] !== UPLOAD_ERR_OK) {

          $result['status'] = false;
          $result['notif'] = General::notif('warning', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['add_img']['error']);
          postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une catégorie de plats', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['add_img']['error']);
        } elseif ($_FILES['add_img']['size'] < 12) {

          $result['status'] = false;
          $result['notif'] = General::notif('error', 'Le fichier envoyé n\'est pas une image');
          postJournal($pdo, $user, 4, 'Tentative d\'ajout d\'une catégorie de plats', 'Le fichier envoyé n\'est pas une image');
        } elseif (!in_array($extension, $allowTypes)) {
          $result['status'] = false;
          $result['notif'] = General::notif('warning', 'Format d\'image non pris en charge');
          postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une catégorie de plats', 'Format d\'image non pris en charge');
        } else {

          do {
            $filename = bin2hex(random_bytes(16));
            $complete_path = $pathOriginal . '/' . $filename . '.' . $extension;
          } while (file_exists($complete_path));

          if (!move_uploaded_file($_FILES['add_img']['tmp_name'], $complete_path)) {
            $result['status'] = false;
            $result['notif'] = General::notif('error', 'La photo n\'a pas pu être enregistrée');
            postJournal($pdo, $user, 4, 'Tentative d\'ajout d\'une catégorie de plats', 'La photo n\'a pas pu être enregistrée');
          } else {

            if (file_exists($complete_path)) {

              $path = __DIR__ . '/../../../../Global/Uploads';

              if ($extension == 'png') {

                $quality = 50;

                $image = imagecreatefrompng($complete_path);
                ob_start();
                imagewebp($image, $path . '/' . $filename . '.webp');
                imagejpeg($image, $path . '/' . $filename . '.jpg', $quality);
                ob_end_clean();
                imagedestroy($image);
              } elseif ($extension == 'jpg' || $extension = 'jpeg') {
                copy($complete_path, $path . '/' . $filename . '.' . $extension);
                $image = imagecreatefromjpeg($complete_path);
                ob_start();
                imagewebp($image, $path . '/' . $filename . '.webp');
                ob_end_clean();
                imagedestroy($image);
              }
            }

            $req1 = $pdo->prepare(
              'INSERT INTO plats_photo(img__jpeg, img__webp, original)
                         VALUES (:img__jpeg,:img__webp,:original)'
            );

            if ($extension == 'png') {
              $req1->bindValue('img__jpeg', $filename . '.jpg');
              $req1->bindValue('img__webp',  $filename . '.webp');
            } elseif ($extension == 'jpg' || $extension = 'jpeg') {

              $req1->bindValue('img__jpeg',  $filename . '.' . $extension);
              $req1->bindValue('img__webp',  $filename . '.webp');
            }

            $req1->bindValue('original', $filename . '.' . $extension);
            $req1->execute();
          }
        }
      } // si empty image
    } // if option photo est activé

    if ($options['show_cat_photo']) {
      $img = $pdo->lastInsertId();
    } else {
      $img = NULL;
    }


    $lastPosition = getLastPosition($pdo);


    $req2 = $pdo->prepare('INSERT INTO plats_categories(titre,
                                                        description, 
                                                        photo_id,
                                                        author_id,
                                                        pieces,
                                                        position,
                                                        est_publie,
                                                        date_enregistrement)
                                  VALUES(:titre,
                                  :description, 
                                  :photo_id,
                                  :author_id,
                                  :pieces,
                                  :position,
                                  :publie,
                                  :date)');

    $req2->bindParam(':titre', $titre);
    $req2->bindParam(':description', $description);
    $req2->bindParam(':pieces', $pieces);
    $req2->bindValue(':photo_id', $img);
    $req2->bindValue(':position', getLastPosition($pdo));
    $req2->bindvalue(':author_id', $user);
    $req2->bindValue(':publie', 1);
    $req2->bindValue(':date', (new DateTime())->format('Y-m-d H:i:s'));
    $req2->execute();


    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Nouvelle catégorie créée');
    postJournal($pdo, $user, 0, 'Nouvelle catégorie', 'Nouvelle catégorie créée');

    $record_per_page = 10;
    $page = 0;
    $ua = getBrowser();


    if (isset($_POST['page'])) {

      $page = $_POST['page'];
    } else {

      $page = 1;
    }

    $start_from = ($page - 1) * $record_per_page;

    $query = $pdo->query("SELECT * FROM plats_categories ORDER BY position ASC LIMIT $start_from,$record_per_page");



    $result['resultat'] = '<table>

           <thead>
             <tr>
              <th class="dnone">ID</th>
              <th class="tab desktop" title="Position de la categorie sur la carte">#</th>
              <th>Titre</th>';

    if ($options['show_cat_photo'] == 1) {
      $result['resultat'] .= '<th>Image</th>';
    }
    if ($options['show_cat_description'] == 1) {
      $result['resultat'] .= '<th class="desktop">Description</th>';
    }
    if ($options['show_cat_pieces'] == 1) {
      $result['resultat'] .= '<th class="desktop"># Pièces</th>';
    }
    $result['resultat'] .= '<th class="desktop"># Plats</th>';
    if ($options['show_cat_stats'] == 1) {
      $result['resultat'] .= '<thclass="desktop" ><i class="fas fa-chart-area"></i></thclass=>';
    }
    $result['resultat'] .= '<th class="tab desktop">Afficher</th>
               <th>Actions</th>';
    $result['resultat'] .= '</tr>
           </thead>';
    $result['resultat'] .= '<tbody>';

    while ($row = $query->fetch()) {

      $result['resultat'] .= '<tr data-position="' . $row['position'] . '" data-id="' . $row['id'] . '">';
      $result['resultat'] .= '<td class="tab desktop">' . $row['position'] . '</td>';

      $result['resultat'] .= '<td class="dnone">' . $row['id'] . '</td>';

      $result['resultat'] .= '<td><strong>' . $row['titre'] . '</strong></td>';
      if ($options['show_cat_photo'] == 1) {
        $result['resultat'] .= '<td class="dnone">' . $row['photo_id'] . '</td>';
        $result['resultat'] .= '<td class="td-team">' . Categories::getImage($pdo, $row['photo_id'], $ua['name']) . '</td>';
      }
      if ($options['show_cat_description'] == 1) {
        $result['resultat'] .= '<td class="desktop">' . substr($row['description'], 0, 45) . '...' . '</td>';
      }
      if ($options['show_cat_pieces'] == 1) {
        $result['resultat'] .= '<td class="desktop">' . $row['pieces'] . '</td>';
      }
      $result['resultat'] .= '<td class="desktop">' . Categories::getNbPlats($pdo, $row['id']) . '</td>';
      if ($options['show_cat_stats'] == 1) {
        $result['resultat'] .= '<td class="desktop">0</td>';
      }
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
    if (countCat($pdo) > 10) {
      $result['resultat'] .= '</br /><div  class="custom_pagination">';

      $page_query = $pdo->query('SELECT * FROM plats_categories ORDER BY position ASC');
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
} // fin if post
