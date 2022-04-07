<?php
require_once __DIR__ . '/../../Config/Init.php';
require_once __DIR__ . '/../../Functions/CategoriesFunctions.php';

use App\General;
use App\Categories;

$user = $user['id_team_member'];
/* #############################################################################

Delete d'une catégorie de plats à partir categories.php en Ajax

#1 Confirmation du suppresion
#2 récupération des informations de la catégorie
#3 récupération des catégories avec une position supérieur 
#4 update des positions des autres catégories
#5 si image, suppression des images 
#6 suppression de la catégorie
#7 retour AJAX

############################################################################# */

if (!empty($_POST)) {

  $result = array();
  $id = $_POST['id'];
  $confirme = 'on';

  if (($_POST['confirmedelete']) !== $confirme) {
    #1
    $result['status'] = false;
    $result['notif'] = General::notif('error', 'Merci de confirmer la suppression');
    postJournal($pdo, $user, 4, 'Tentative de suppresion d\'une catégorie', 'Suppression non confirmée');
  } else {
    #2
    $data = $pdo->query("SELECT * FROM plats_categories WHERE id = '$id'");
    $cat = $data->fetch(PDO::FETCH_ASSOC);
    $titre = $cat['titre'];
    $pics = $cat['photo_id'];
    $position = $cat['position'];

    #3
    $req = $pdo->query("SELECT id, position FROM plats_categories WHERE position > $position");
    $upCat = $req->fetchAll(PDO::FETCH_ASSOC);

    #4
    foreach ($upCat as $cats) {
      $updatePos = $pdo->prepare('UPDATE plats_categories SET position = :position WHERE id=' . $cats['id']);
      $updatePos->bindValue(':position', --$cats['position']);
      $updatePos->execute();
    }

    #5
    if ($pics !== null) {

      //suppresion des images de catégories

      $data = $pdo->query("SELECT * FROM plats_photo WHERE id_photo = '$pics'");
      $photo = $data->fetch(PDO::FETCH_ASSOC);

      $file = __DIR__ . '/../../../../Global/Uploads/';

      $dir = opendir($file);
      unlink($file . $photo['img__jpeg']);
      unlink($file . $photo['img__webp']);
      closedir($dir);

      $pathOriginal = __DIR__ . '/../../../../Global/Uploads/Originals/';

      $dirOriginal = opendir($pathOriginal);
      unlink($pathOriginal . $photo['original']);
      closedir($dirOriginal);


      $req1 = $pdo->exec("DELETE FROM plats_photo WHERE id_photo = '$pics'");
    }


    #6
    $req2 = $pdo->exec("DELETE FROM plats_categories WHERE id = '$id'");

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Categorie supprimée');
    postJournal($pdo, $user, 2, 'Catégorie supprimée', 'Catégorie ' . $titre . ' supprimée');

    $record_per_page = 10;
    $page = 0;
    $ua = getBrowser();

    #7
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
}
