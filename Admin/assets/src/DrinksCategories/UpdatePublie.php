<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksCategoriesFunctions.php';

use App\General;
use App\DrinksCat;

/* #############################################################################

Update de la publication d'une categories a partir  DrinksCat.php en Ajax

############################################################################# */

$user = $user['id_team_member'];

if (!empty($_POST)) {

  $result = array();
  $id = $_POST['id'];
  $publie = $_POST['publie'];

  if ($publie == 0) {

    $req = $pdo->prepare('UPDATE boissons_categories SET est_publie = :publie WHERE id = :id');

    $req->bindParam(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':publie', 1);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Catégorie Affichée');
    postJournal($pdo, $user, 1, 'Catégorie Affichée', 'Catégorie Affichée # ' . $id);

    $record_per_page = 10;
    $page = 0;
    $ua = getBrowser();


    if (isset($_POST['page'])) {

      $page = $_POST['page'];
    } else {

      $page = 1;
    }

    $start_from = ($page - 1) * $record_per_page;

    $query = $pdo->query("SELECT * FROM boissons_categories ORDER BY position ASC LIMIT $start_from,$record_per_page");



    $result['resultat'] = '<table>

           <thead>
             <tr>
              <th class="dnone">ID</th>
              <th class="tab desktop" title="Position de la categorie sur la carte">#</th>
              <th>Titre</th>';

    if ($options['show_cat_drink_photo'] == 1) {
      $result['resultat'] .= '<th>Image</th>';
    }
    if ($options['show_cat_drink_description'] == 1) {
      $result['resultat'] .= '<th class="desktop">Description</th>';
    }

    $result['resultat'] .= '<th class="desktop"># Boissons</th>';
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
      if ($options['show_cat_drink_photo'] == 1) {
        $result['resultat'] .= '<td class="dnone">' . $row['photo_id'] . '</td>';
        $result['resultat'] .= '<td class="td-team">' . DrinksCat::getImage($pdo, $row['photo_id'], $ua['name']) . '</td>';
      }
      if ($options['show_cat_drink_description'] == 1) {
        $result['resultat'] .= '<td class="desktop">' . substr($row['description'], 0, 45) . '...' . '</td>';
      }
      $result['resultat'] .= '<td class="desktop">' . DrinksCat::getNbDrinks($pdo, $row['id']) . '</td>';

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

      $page_query = $pdo->query('SELECT * FROM boissons_categories ORDER BY position ASC');
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
  } else {

    $req = $pdo->prepare('UPDATE boissons_categories SET est_publie = :publie WHERE id = :id');

    $req->bindParam(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':publie', 0);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Catégorie Retirée');
    postJournal($pdo, $user, 1, 'Catégorie Retirée', 'Catégorie Retirée # ' . $id);

    $record_per_page = 10;
    $page = 0;
    $ua = getBrowser();


    if (isset($_POST['page'])) {

      $page = $_POST['page'];
    } else {

      $page = 1;
    }

    $start_from = ($page - 1) * $record_per_page;

    $query = $pdo->query("SELECT * FROM boissons_categories ORDER BY position ASC LIMIT $start_from,$record_per_page");



    $result['resultat'] = '<table>

           <thead>
             <tr>
              <th class="dnone">ID</th>
              <th class="tab desktop" title="Position de la categorie sur la carte">#</th>
              <th>Titre</th>';

    if ($options['show_cat_drink_photo'] == 1) {
      $result['resultat'] .= '<th>Image</th>';
    }
    if ($options['show_cat_drink_description'] == 1) {
      $result['resultat'] .= '<th class="desktop">Description</th>';
    }

    $result['resultat'] .= '<th class="desktop"># Boissons</th>';
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
      if ($options['show_cat_drink_photo'] == 1) {
        $result['resultat'] .= '<td class="dnone">' . $row['photo_id'] . '</td>';
        $result['resultat'] .= '<td class="td-team">' . DrinksCat::getImage($pdo, $row['photo_id'], $ua['name']) . '</td>';
      }
      if ($options['show_cat_drink_description'] == 1) {
        $result['resultat'] .= '<td class="desktop">' . substr($row['description'], 0, 45) . '...' . '</td>';
      }
      $result['resultat'] .= '<td class="desktop">' . DrinksCat::getNbDrinks($pdo, $row['id']) . '</td>';

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

      $page_query = $pdo->query('SELECT * FROM boissons_categories ORDER BY position ASC');
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
