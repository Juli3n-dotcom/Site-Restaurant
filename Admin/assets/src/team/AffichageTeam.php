<?php

require_once __DIR__ . '/../../config/Bootstrap.php';


use App\Team;

$record_per_page = 10;
$page = 0;
$output = '';

if (isset($_POST['page'])) {

  $page = $_POST['page'];
} else {

  $page = 1;
}

$start_from = ($page - 1) * $record_per_page;

$query = $pdo->query("SELECT * FROM team ORDER BY id_team_member DESC LIMIT $start_from,$record_per_page");

$output .= '<table>

          <thead>
            <tr>
              <th>ID</th>
              <th class="dnone">Civilité</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Photo</th>
              <th>Email</th>
              <th>Statut</th>';
//if ($Membre['statut'] == 0)         
$output .= '<th>Confirmation</th>
              <th>Actions</th>';
//prévoir else $Membre['statut']
// $output .= '<th>Action</th>';
//prévoir fin elseif $Membre['statut']
$output .= ' </tr>
          </thead>';
$output .= '<tbody';

while ($row = $query->fetch()) {
  $output .= '<tr>';
  $output .= '<td>' . $row['id_team_member'] . '</td>';
  $output .= '<td class="dnone">' . $row['id_team_member'] . '</td>';
  $output .= '<td>' . $row['nom'] . '</td>';
  $output .= '<td>' . $row['prenom'] . '</td>';
  $output .= '<td class="td-team">' . Team::getProfil($pdo, $row['photo_id'], $row['civilite']) . '</td>';
  $output .= '<td><a href="mailto:' . $row['email'] . '" class="email_member">' . $row['email'] . '</a></td>';
  $output .= '<td>' . Team::getStatut($row['statut']) . '</td>';
  $output .= '<td>' . Team::getConfirmation($row['confirmation']) . '</td>';
  $output .= '<td class="member_action">';
  $output .= '<div class="member_action-container">';
  $output .= '<input type="button" class="viewbtn" name="view" id="' . $row['id_team_member'] . '"></input>';
  $output .= '<input type="button" class="editbtn" id="' . $row['id_team_member'] . '"></input>';
  $output .= '<input type="button" class="deletebtn"></input>';
  $output .= '</div>';
  $output .= '</td>';
  $output .= '</tr>';
}
$output .= '</tbody></table><br /><div  class="custom_pagination">';

$page_query = $pdo->query('SELECT * FROM team ORDER BY id_team_member DESC');
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
