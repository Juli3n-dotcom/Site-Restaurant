<?php

require_once __DIR__ . '/../../config/Init.php';

use App\History;

$record_per_page = 20;
$page = 0;
$output = '';

if (isset($_POST['page'])) {

  $page = $_POST['page'];
} else {

  $page = 1;
}

$start_from = ($page - 1) * $record_per_page;

$query = $pdo->query("SELECT * FROM journal ORDER BY id DESC LIMIT $start_from,$record_per_page");

$output .= '<table>

          <thead>
            <tr>
              <th>ID</th>
              <th>Statut</th>
              <th>Membre</th>
              <th>Titre</th>
              <th>Contenu</th>
              <th>Date</th>
              <th>Actions</th>';
$output .= ' </tr>
          </thead>';
$output .= '<tbody';

while ($row = $query->fetch()) {

  $date = str_replace('/', '-', $row['date_enregistrement']);

  $output .= '<tr>';
  $output .= '<td>' . $row['id'] . '</td>';
  $output .= History::getStatut($row['statut']);
  $output .= '<td>' . History::getMembre($pdo, $row['member_id']) . '</td>';
  $output .= '<td>' . $row['titre'] . '</td>';
  $output .= '<td>' . $row['contenu'] . '</td>';
  $output .= '<td>' . date('d-m-Y', strtotime($date)) . '</td>';
  $output .= '<td class="member_action">';
  $output .= '<div class="member_action-container">';
  $output .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
  $output .= '</div>';
  $output .= '</td>';
  $output .= '</tr>';
}
$output .= '</tbody></table><br /><div  class="custom_pagination">';

$page_query = $pdo->query('SELECT * FROM journal ORDER BY id DESC');
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


echo $output;
