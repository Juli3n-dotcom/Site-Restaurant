<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksFunctions.php';

use App\General;
use App\Drinks;

/* #############################################################################

Update de la mise en avant d'un plat a partir Plats.php en Ajax

############################################################################# */

$user = $user['id_team_member'];

if (!empty($_POST)) {

  $result = array();
  $id = $_POST['id'];
  $est_en_avant = $_POST['est_en_avant'];

  if ($est_en_avant == 0) {

    $req = $pdo->prepare('UPDATE boissons SET est_en_avant = :en_avant WHERE id = :id');

    $req->bindParam(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':en_avant', 1);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Boissons mise en avant');
    postJournal($pdo, $user, 1, 'Boissons mise en avant', 'Boissons mise en avant # ' . $id);

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

    $result['nbdrinks'] = countDrinks($pdo);

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
  } else {

    $req = $pdo->prepare('UPDATE boissons SET est_en_avant = :en_avant WHERE id = :id');

    $req->bindParam(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':en_avant', 0);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'La boisson n\'est plus mise en avant');
    postJournal($pdo, $user, 1, 'mise en avant', 'La boisson #' . $id . ' n\'est plus mise en avant');

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

    $result['nbdrinks'] = countDrinks($pdo);

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
}
