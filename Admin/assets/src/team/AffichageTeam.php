<?php

require_once __DIR__ . '/../../config/Bootstrap.php';


$record_per_page = 10;
$page = '';
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
  $output .= '<td>0</td>';
  $output .= '<td class="member_action">';
  $output .= '<div class="member_action-container">';
  $output .= '<input type="button" class="viewbtn" name="view" id="' . $row['id_team_member'] . '"></input>';
  $output .= '<input type="button" class="editbtn" id="' . $row['id_team_member'] . '"></input>';
  $output .= '<input type="button" class="deletebtn"></input>';
  $output .= '</div>';
  $output .= '</td>';
  $output .= '</tr>';
}
$output .= '</tbody>

        </table>';
