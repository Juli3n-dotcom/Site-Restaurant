<?php
require_once __DIR__ . '/../../config/Bootstrap.php';

use App\History;

$record_per_page = 20;
$page = 0;
$result = array();

if (isset($_POST['page'])) {

  $page = $_POST['page'];
} else {

  $page = 1;
}

$start_from = ($page - 1) * $record_per_page;

$query = $pdo->query("SELECT * FROM journal WHERE statut = 2 ORDER BY id DESC LIMIT $start_from,$record_per_page");

$result['resultat'] = '<table>

  <thead>
    <tr>
      <th>ID</th>
      <th>Statut</th>
      <th>Membre</th>
      <th>Titre</th>
      <th>Contenu</th>
      <th>Date</th>
      <th>Actions</th>';
$result['resultat'] .= '
    </tr>
  </thead>';
$result['resultat'] .= '<tbody';
while ($row = $query->fetch()) {

  $date = str_replace('/', '-', $row['date_enregistrement']);

  $result['resultat'] .= '<tr>';
  $result['resultat'] .= '<td>' . $row['id'] . '</td>';
  $result['resultat'] .= History::getStatut($row['statut']);
  $result['resultat'] .= '<td>' . History::getMembre($pdo, $row['member_id']) . '</td>';
  $result['resultat'] .= '<td>' . $row['titre'] . '</td>';
  $result['resultat'] .= '<td>' . $row['contenu'] . '</td>';
  $result['resultat'] .= '<td>' . date('d-m-Y', strtotime($date)) . '</td>';
  $result['resultat'] .= '<td class="member_action">';
  $result['resultat'] .= '<div class="member_action-container">';
  $result['resultat'] .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
  $result['resultat'] .= '</div>';
  $result['resultat'] .= '</td>';
  $result['resultat'] .= '</tr>';
}
$result['resultat'] .= '</tbody>
</table><br />
<div class="custom_pagination">';

$page_query = $pdo->query('SELECT * FROM journal WHERE statut = 2 ORDER BY id DESC');
$total_records = $page_query->rowCount();
$total_pages = ceil($total_records / $record_per_page);

$result['resultat'] .= '<ul class="pagination">';

if ($page > 1) {
  $previous = $page - 1;
  $result['resultat'] .= '<li class="pagination_link_delete" id="' . $previous . '"><span class="page-link"><i class="fas fa-caret-left"></i> Précédent</span></li>';
}

$result['resultat'] .= '<li class="reset_link" id="resetHistory"><input type="reset" class="resetBtn" id="resetBtn" value="Reset"></li>';


if ($page < $total_pages) {
  $page++;
  $result['resultat'] .= '<li class="pagination_link_delete" id="' . $page . '"><span class="page-link">Suivant <i class="fas fa-caret-right"></i></span></li>';
}
$result['resultat'] .= '</ul>';
$result['resultat'] .= '</div>';


// Return result 
echo json_encode($result);
