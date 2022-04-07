<?php
require_once __DIR__ . '/../../Config/Init.php';
require_once __DIR__ . '/../../Functions/AllergenesFunctions.php';

use App\General;
use App\Allergenes;

$user = $user['id_team_member'];
/* #############################################################################

Delete d'un allergene de plats à partir Allergenes.php en Ajax

#1 Confirmation du suppresion

############################################################################# */

if (!empty($_POST)) {

  $result = array();
  $id = $_POST['id'];
  $confirme = 'on';

  if (($_POST['confirmedelete']) !== $confirme) {

    $result['status'] = false;
    $result['notif'] = General::notif('error', 'Merci de confirmer la suppression');
    postJournal($pdo, $user, 4, 'Tentative de suppresion d\'un allergene', 'Suppression non confirmée');
  } else {

    $data = $pdo->query("SELECT * FROM allergenes WHERE id = '$id'");
    $data = $data->fetch(PDO::FETCH_ASSOC);
    $titre = $data['titre'];


    $req = $pdo->exec("DELETE FROM allergenes WHERE id = '$id'");

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Allergene supprimée');
    postJournal($pdo, $user, 2, 'Allergene supprimée', 'Allergene ' . $titre . ' supprimé');

    $record_per_page = 10;
    $page = 0;

    if (isset($_POST['page'])) {

      $page = $_POST['page'];
    } else {

      $page = 1;
    }

    $start_from = ($page - 1) * $record_per_page;

    $query = $pdo->query("SELECT * FROM allergenes ORDER BY titre ASC LIMIT $start_from,$record_per_page");



    $result['resultat'] = '<table>

          <thead>
            <tr>
              <th class="dnone">ID</th>
              <th>Titre</th>';

    $result['resultat'] .= '<th class="desktop">Description</th>';
    $result['resultat'] .= '<th class="desktop">Exclusions</th>';
    $result['resultat'] .= '<th class="desktop"># Plats</th>';
    $result['resultat'] .= ' <th>Actions</th>';
    $result['resultat'] .= ' </tr></thead>';
    $result['resultat'] .= '<tbody class="page_list">';

    while ($row = $query->fetch()) {


      $result['resultat'] .= '<td id="' . $row['id'] . '"class="dnone">' . $row['id'] . '</td>';
      $result['resultat'] .= '<td><strong>' . $row['titre'] . '</strong></td>';
      $result['resultat'] .= '<td class="desktop description">' . $row['description'] .  '</td>';
      $result['resultat'] .= '<td class="desktop description">' . $row['exclusions'] .  '</td>';
      $result['resultat'] .= '<td class="desktop">' . Allergenes::getNbPlats($pdo, $row['id']) . '</td>';
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
    if (countAller($pdo) > 10) {
      $result['resultat'] .= '<br /><div class="custom_pagination">';

      $page_query = $pdo->query('SELECT * FROM allergenes ORDER BY titre ASC');
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
