<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksFunctions.php';

use App\Drinks;

$ua = getBrowser();

$record_per_page = 10;
$page = 0;
$output = '';

if (isset($_POST['page'])) {

  $page = $_POST['page'];
} else {

  $page = 1;
}

$start_from = ($page - 1) * $record_per_page;

$query = $pdo->query("SELECT * FROM boissons ORDER BY cat_id, titre ASC LIMIT $start_from,$record_per_page");



$output .= '<table>

          <thead>
            <tr>
              <th class="dnone">ID</th>';
//$output .= '<th class="tab desktop" title="Position de l\'element sur la carte">#</th>'
$output .= '<th>Titre</th>';
if ($options['show_drink_photo'] == 1) {
  $output .= '<th>Image</th>';
}
$output .= '<th class="desktop">Prix</th>';
$output .= '<th class="desktop">Description</th>';
$output .= '<th class="tab desktop">Catégorie</th>';
if ($options['show_drinks_stats'] == 1) {
  $output .= '<th class="desktop"><i class="fas fa-chart-area"></i></th>';
}
if ($options['show_drinks_en_avant'] == 1) {
  $output .= '<th class="tab desktop">Mise en avant</th>';
}
$output .= '<th class="tab desktop">Afficher</th>
              <th>Actions</th>';
$output .= ' </tr></thead>';
$output .= '<tbody class="page_list">';

while ($row = $query->fetch()) {

  //$output .= '<tr data-position="' . $row['position'] . '" data-id="' . $row['id'] . '">';
  // $output .= '<td class="tab desktop">' . $row['position'] . '</td>';
  $output .= '<td id="' . $row['id'] . '"class="dnone">' . $row['id'] . '</td>';
  $output .= '<td class="plat_title"><strong>' . $row['titre'] . '</strong>' . ($row['est_nouveau'] ? '<span class="new"> nouveau</span>' : '') . '</td>';
  if ($options['show_drink_photo'] == 1) {
    $output .= '<td class="dnone cat_pics">' . $row['photo_id'] . '</td>';
    $output .= '<td class="td-team">' . Drinks::getImage($pdo, $row['photo_id'], $ua['name']) . '</td>';
  }
  $output .= '<td class="desktop">' . $row['prix'] . ' €</td>';
  $output .= '<td class="desktop description">' . $row['description'] . '</td>';
  $output .= '<td class="tab desktop"><strong>' . Drinks::getCat($pdo, $row['cat_id']) . '</strong></td>';
  if ($options['show_drinks_stats'] == 1) {
    $output .= '<td class="desktop">0</td>';
  }
  if ($options['show_drinks_en_avant'] == 1) {
    $output .= '<td class="tab desktop">' . Drinks::getEstEnAvant($pdo, $row['id']) . '</td>';
  }
  if ($row['est_publie'] == 1) {

    $output .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . ' checked></td>';
  } else {

    $output .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . '></td>';
  }
  $output .= '<td class="member_action">';
  $output .= '<div class="member_action-container">';
  $output .= '<a href="FicheDrink.php?id=' . $row['id'] . '" class="linkbtn"><i class="fa-regular fa-eye"></i></a>';
  $output .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
  $output .= '<input type="button" class="editbtn" id="' . $row['id'] . '"></input>';
  $output .= '<input type="button" class="deletebtn"></input>';
  $output .= '</div>';
  $output .= '</td>';
  $output .= '</tr>';
}

$output .= '</tbody></table>';
if (countDrinks($pdo) > 10) {
  $output .= '<br /><div class="custom_pagination">';

  $page_query = $pdo->query('SELECT * FROM boissons ORDER BY cat_id, titre ASC');
  $total_records = $page_query->rowCount();
  $total_pages = ceil($total_records / $record_per_page);


  $output .= '<ul class="pagination">';

  if ($page > 1) {
    $previous = $page - 1;
    $output .= '<li class="pagination_link" id="' . $previous . '"><span class="page-link"><i class="fas fa-caret-left"></i> Précédent</span></li>';
  }

  if ($page < $total_pages) {
    $page++;
    $output .= '<li class="pagination_link" id="' . $page . '"><span class="page-link">Suivant <i class="fas fa-caret-right"></i></span></li>';
  }

  $output .= '</ul>';

  $output .= '</div>';
}



echo $output;
