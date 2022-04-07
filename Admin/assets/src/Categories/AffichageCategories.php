<?php

require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/CategoriesFunctions.php';

use App\Categories;

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

$query = $pdo->query("SELECT * FROM plats_categories ORDER BY position ASC LIMIT $start_from,$record_per_page");



$output .= '<table>

          <thead>
            <tr>
              <th class="dnone">ID</th>
              <th class="tab desktop" title="Position de la categorie sur la carte">#</th>
              <th>Titre</th>';
if ($options['show_cat_photo'] == 1) {
  $output .= '<th >Image</th>';
}
if ($options['show_cat_description'] == 1) {
  $output .= '<th class="desktop">Description</th>';
}
if ($options['show_cat_pieces'] == 1) {
  $output .= '<th class="desktop"># Pièces</th>';
}
$output .= '<th class="desktop"># Plats</th>';
if ($options['show_cat_stats'] == 1) {
  $output .= '<th class="desktop"><i class="fas fa-chart-area"></i></th>';
}
$output .= '<th class="tab desktop">Afficher</th>
              <th>Actions</th>';
$output .= ' </tr></thead>';
$output .= '<tbody class="page_list">';

while ($row = $query->fetch()) {

  $output .= '<tr data-position="' . $row['position'] . '" data-id="' . $row['id'] . '">';
  $output .= '<td class="tab desktop">' . $row['position'] . '</td>';
  $output .= '<td id="' . $row['id'] . '"class="dnone">' . $row['id'] . '</td>';
  $output .= '<td><strong>' . $row['titre'] . '</strong></td>';
  if ($options['show_cat_photo'] == 1) {
    $output .= '<td class="dnone cat_pics">' . $row['photo_id'] . '</td>';
    $output .= '<td class="td-team">' . Categories::getImage($pdo, $row['photo_id'], $ua['name']) . '</td>';
  }
  if ($options['show_cat_description'] == 1) {
    $output .= '<td class="desktop">' . substr($row['description'], 0, 45) .  '</td>';
  }
  if ($options['show_cat_pieces'] == 1) {
    $output .= '<td class="desktop">' . $row['pieces'] . '</td>';
  }
  $output .= '<td class="desktop">' . Categories::getNbPlats($pdo, $row['id']) . '</td>';
  if ($options['show_cat_stats'] == 1) {
    $output .= '<td class="desktop">0</td>';
  }
  if ($row['est_publie'] == 1) {

    $output .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . ' checked></td>';
  } else {

    $output .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . '></td>';
  }
  $output .= '<td class="member_action">';
  $output .= '<div class="member_action-container">';
  $output .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
  $output .= '<input type="button" class="editbtn" id="' . $row['id'] . '"></input>';
  $output .= '<input type="button" class="deletebtn"></input>';
  $output .= '</div>';
  $output .= '</td>';
  $output .= '</tr>';
}

$output .= '</tbody></table>';
if (countCat($pdo) > 10) {
  $output .= '<br /><div class="custom_pagination">';

  $page_query = $pdo->query('SELECT * FROM plats_categories ORDER BY position ASC');
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
