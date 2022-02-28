<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../functions/TeamFunctions.php';

use App\Notifications;
use App\Team;

/* #############################################################################

delete d'un member a partir team.php en Ajax

############################################################################# */



if (!empty($_POST)) {

  $result = array();
  $id = $_POST['id'];
  $confirme = 'on';



  if (($_POST['confirmedelete']) !== $confirme) {

    $result['status'] = false;
    $result['notif'] = Notifications::notif('error', 'Merci de confirmer la suppression');
    postJournal($pdo, 3, 4, 'Tentative de suppresion d\'un membre', 'Tentative de suppresion sans confirmation');
  } else {

    //recherche si il existe une photo de profil
    $data = $pdo->query("SELECT photo_id FROM team WHERE id_team_member = '$id'");
    $img_id = $data->fetch(PDO::FETCH_ASSOC);

    // si image de profil on supprime la photo
    if ($img_id['photo_id'] !== null) {

      $img = $img_id['photo_id'];

      $data = $pdo->query("SELECT photo FROM team_photo WHERE id_photo = '$img'");
      $photo = $data->fetch(PDO::FETCH_ASSOC);


      $file = __DIR__ . '/../../uploads/';
      $dir = opendir($file);
      unlink($file . $photo['photo']);
      closedir($dir);

      $req1 = $pdo->exec("DELETE FROM team_photo WHERE id_photo = '$img'");
    }

    $data = $pdo->query("SELECT username FROM team WHERE id_team_member = '$id'");
    $username = $data->fetch(PDO::FETCH_ASSOC);

    //suppresion du membre de la BDD
    $req2 = $pdo->exec("DELETE FROM team WHERE id_team_member = '$id'");

    $result['status'] = true;
    $result['notif'] = Notifications::notif('success', 'Membre supprimé');
    postJournal($pdo, 3, 2, 'Suppresion membre de l\'équipe',  $username['username'] . ' a été supprimé');


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

    $result['resultat']  = '<table>

          <thead>
            <tr>
              <th class="dnone">Civilité</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Photo</th>
              <th>Email</th>
              <th>Statut</th>';
    //if ($Membre['statut'] == 0)         
    $result['resultat']  .= '<th>Confirmation</th>
              <th>Actions</th>';
    //prévoir else $Membre['statut']
    // $result['resultat']  .= '<th>Action</th>';
    //prévoir fin elseif $Membre['statut']
    $result['resultat']  .= ' </tr>
          </thead>';
    $result['resultat']  .= '<tbody';

    while ($row = $query->fetch()) {
      $result['resultat']  .= '<tr>';
      $result['resultat']  .= '<td class="dnone">' . $row['id_team_member'] . '</td>';
      $result['resultat']  .= '<td>' . $row['nom'] . '</td>';
      $result['resultat']  .= '<td>' . $row['prenom'] . '</td>';
      $result['resultat']  .= '<td class="td-team">' . Team::getProfil($pdo, $row['photo_id'], $row['civilite']) . '</td>';
      $result['resultat']  .= '<td><a href="mailto:' . $row['email'] . '" class="email_member">' . $row['email'] . '</a></td>';
      $result['resultat']  .= '<td>' . Team::getStatut($row['statut']) . '</td>';
      $result['resultat']  .= '<td>' . Team::getConfirmation($row['confirmation']) . '</td>';
      $result['resultat']  .= '<td class="member_action">';
      $result['resultat']  .= '<div class="member_action-container">';
      $result['resultat']  .= '<input type="button" class="viewbtn" name="view" id="' . $row['id_team_member'] . '"></input>';
      $result['resultat']  .= '<input type="button" class="editbtn" id="' . $row['id_team_member'] . '"></input>';
      $result['resultat']  .= '<input type="button" class="deletebtn"></input>';
      $result['resultat']  .= '</div>';
      $result['resultat']  .= '</td>';
      $result['resultat']  .= '</tr>';
    }
    $result['resultat'] .= '</tbody></table><br /><div  class="custom_pagination">';

    $page_query = $pdo->query('SELECT * FROM team ORDER BY id_team_member DESC');
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

  // Return result 
  echo json_encode($result);
}
