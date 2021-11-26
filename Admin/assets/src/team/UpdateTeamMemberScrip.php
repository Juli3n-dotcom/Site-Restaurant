<?php
require_once __DIR__ . '/../../config/Bootstrap.php';
require_once __DIR__ . '/../../functions/TeamFunctions.php';

use App\Notifications;
use App\Team;
/* #############################################################################

Update d'un member a partir team.php en Ajax

############################################################################# */

$result = array();

if (!empty($_POST)) {

  $id = $_POST['update_id'];
  $civilite = $_POST['update_civilite'];
  $username = htmlspecialchars($_POST['update_username']);
  $name = htmlspecialchars($_POST['update_name']);
  $fname = htmlspecialchars($_POST['update_prenom']);
  $email = htmlspecialchars($_POST['update_email']);
  $statut = $_POST['update_statut'];
  $confirme = $_POST['update_confirme'];

  $data = $pdo->query("SELECT * FROM team WHERE id_team_member = '$id'");
  $thisMember = $data->fetch(PDO::FETCH_ASSOC);

  // debut de la requete d'update
  $param = FALSE;
  $requete = 'UPDATE team SET ';

  //modification du nom
  if (empty($name)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque le nom');
    postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'Nom manquant');
  } elseif (!preg_match('~^[a-zA-Z- ]+$~', $name)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! le nom est pas bon');
    postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'Nom non valide');
  } elseif ($name !== $thisMember['nom']) {
    $requete .= 'nom = :nom';
    $param = TRUE;
  }

  //modification du prénom
  if (empty($fname)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque le prénom');
    postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'Prénom manquant');
  } elseif (!preg_match('~^[a-zA-Z- ]+$~', $fname)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! le prénom est pas bon');
    postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'Prénom non valide');
  } elseif ($fname !== $thisMember['prenom']) {

    if ($param == TRUE) {

      $requete .= ', prenom = :prenom';
    } else {

      $requete .= 'prenom = :prenom';
    }

    $param = TRUE;
  }

  //modification de l'username
  if (empty($username)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque le pseudo');
    postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'Pseudo manquant');
  } elseif ($username !== $thisMember['username']) {

    if (getMemberBy($pdo, 'username', $username) !== null) {
      $result['status'] = false;
      $result['notif'] = Notifications::notif('warning', 'pseudo déjà utilisé');
      postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'pseudo déjà utilisé');
    } elseif (!preg_match('~^[a-zA-Z0-9_-]+$~', $username)) {
      $result['status'] = false;
      $result['notif'] = Notifications::notif('warning', 'oups! le pseudo est pas bon');
      postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'Pseudo non valide');
    } else {

      if ($param == TRUE) {

        $requete .= ', username = :username';
      } else {

        $requete .= 'username = :username';
      }

      $param = TRUE;
    }
  }

  //modification de l'email
  if (empty($email)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque le mail');
    postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'Email manquant');
  } elseif ($email !== $thisMember['email']) {

    if (getMemberBy($pdo, 'email', $email) !== null) {

      $result['status'] = false;
      $result['notif'] = Notifications::notif('warning', 'email déjà utilisé');
      postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'Email déjà utilisé');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

      $result['status'] = false;
      $result['notif'] = Notifications::notif('warning', 'email non valide');
      postJournal($pdo, 3, 5, 'Tentative de modification d\'un membre', 'email non valide');
    } else {

      if ($param == TRUE) {

        $requete .= ', email = :email';
      } else {

        $requete .= 'email = :email';
      }

      $param = TRUE;
    }
  }

  //modification Civilite
  if ($civilite !== $thisMember['civilite']) {

    if ($param == TRUE) {

      $requete .= ', civilite = :civilite';
    } else {

      $requete .= 'civilite = :civilite';
    }

    $param = TRUE;
  }

  //modification Statut
  if ($statut !== $thisMember['statut']) {

    if ($param == TRUE) {

      $requete .= ', statut = :statut';
    } else {

      $requete .= 'statut = :statut';
    }

    $param = TRUE;
  }

  //modification Confirmation
  if ($confirme !== $thisMember['confirmation']) {

    if ($param == TRUE) {

      $requete .= ', confirmation = :confirmation';
    } else {

      $requete .= 'confirmation = :confirmation';
    }

    $param = TRUE;
  }

  //lancement de la requete
  $requete .= ' WHERE id_team_member = ' . $id;

  // préparation de la requete
  $req_update = $pdo->prepare($requete);

  if ($name !== $thisMember['nom']) {
    $req_update->bindParam(':nom', $name);
  }
  if ($fname !== $thisMember['prenom']) {
    $req_update->bindParam(':prenom', $fname);
  }
  if ($username !== $thisMember['username']) {
    $req_update->bindParam(':username', $username);
  }
  if ($email !== $thisMember['email']) {
    $req_update->bindParam(':email', $email);
  }
  if ($civilite !== $thisMember['civilite']) {
    $req_update->bindValue(':civilite', $civilite);
  }
  if ($statut !== $thisMember['statut']) {
    $req_update->bindValue(':statut', $statut);
  }
  if ($confirme !== $thisMember['confirmation']) {
    $req_update->bindValue(':confirmation', $confirme);
  }

  $req_update->execute();

  $result['status'] = true;
  $result['notif'] = Notifications::notif('success', 'Membre modifiée');
  $data = $pdo->query("SELECT username FROM team WHERE id_team_member = '$id'");
  $username = $data->fetch(PDO::FETCH_ASSOC);
  postJournal($pdo, 3, 1, 'Membre Modifié', $username['username'] . ' a été Modifié');


  $record_per_page = 10;
  $page = '';

  if (isset($_POST['page'])) {

    $page = $_POST['page'];
  } else {

    $page = 1;
  }

  $start_from = ($page - 1) * $record_per_page;

  $query = $pdo->query("SELECT * FROM team ORDER BY id_team_member DESC LIMIT $start_from,$record_per_page");
  $result['resultat'] = '<table>

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
  $result['resultat'] .= '<th>Confirmation</th>
              <th>Actions</th>';
  //prévoir else $Membre['statut']
  // $result['resultat'] .= '<th>Action</th>';
  //prévoir fin elseif $Membre['statut']
  $result['resultat'] .= ' </tr>
          </thead>';
  $result['resultat'] .= '<tbody';

  while ($row = $query->fetch()) {
    $result['resultat'] .= '<tr>';
    $result['resultat'] .= '<td>' . $row['id_team_member'] . '</td>';
    $result['resultat'] .= '<td class="dnone">' . $row['id_team_member'] . '</td>';
    $result['resultat'] .= '<td>' . $row['nom'] . '</td>';
    $result['resultat'] .= '<td>' . $row['prenom'] . '</td>';
    $result['resultat'] .= '<td class="td-team">' . Team::getProfil($pdo, $row['photo_id'], $row['civilite']) . '</td>';
    $result['resultat'] .= '<td><a href="mailto:' . $row['email'] . '" class="email_member">' . $row['email'] . '</a></td>';
    $result['resultat'] .= '<td>' . Team::getStatut($row['statut']) . '</td>';
    $result['resultat'] .= '<td>' . Team::getConfirmation($row['confirmation']) . '</td>';
    $result['resultat'] .= '<td class="member_action">';
    $result['resultat'] .= '<div class="member_action-container">';
    $result['resultat'] .= '<input type="button" class="viewbtn" name="view" id="' . $row['id_team_member'] . '"></input>';
    $result['resultat'] .= '<input type="button" class="editbtn" id="' . $row['id_team_member'] . '"></input>';
    $result['resultat'] .= '<input type="button" class="deletebtn"></input>';
    $result['resultat'] .= '</div>';
    $result['resultat'] .= '</td>';
    $result['resultat'] .= '</tr>';
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
