<?php
require_once __DIR__ . '/../../config/Bootstrap.php';
require_once __DIR__ . '/../../functions/TeamFunctions.php';

use App\Notifications;
use App\Team;
/* #############################################################################

Ajout d'un member a partir team.php en Ajax

############################################################################# */

if (!empty($_POST)) {

  $result = array();
  $civilite = $_POST['add_civilite'];
  $name = htmlspecialchars($_POST['add_name_member']);
  $fname = htmlspecialchars($_POST['add_prenom_member']);
  $email = htmlspecialchars($_POST['add_email_member']);
  $statut = $_POST['add_statut'];

  if (!preg_match('~^[a-zA-Z- ]+$~', $name)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque le nom');
    postJournal($pdo, 3, 5, 'Tentative d\'ajout d\'un membre', 'Nom manquant');
  } elseif (!preg_match('~^[a-zA-Z- ]+$~', $fname)) {

    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque le prénom');
    postJournal($pdo, 3, 5, 'Tentative d\'ajout d\'un membre', 'Prénom manquant');
  } elseif (getMemberBy($pdo, 'email', $email) !== null) {

    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'email déjà utilisé');
    postJournal($pdo, 3, 5, 'Tentative d\'ajout d\'un membre', 'Email déjà utilisé');
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'email non valide ou manquant');
    postJournal($pdo, 3, 5, 'Tentative d\'ajout d\'un membre', 'Email non valide');
  } else {

    //création d'un mot de passe aléatoire
    function passgen($nbChar)
    {
      $chaine = "mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0@#";
      srand((float)microtime() * 1000000);
      $pass = '';
      for ($i = 0; $i < $nbChar; $i++) {
        $pass .= $chaine[rand() % strlen($chaine)];
      }
      return $pass;
    }

    $mdp = passgen(8);
    $hash = password_hash($mdp, PASSWORD_DEFAULT);

    //création de l'username 
    $a = $fname[0];
    $explode_name = explode(' ', $name);
    $username = strtolower($a . $explode_name[0]);

    //autres valeurs
    $token = bin2hex(random_bytes(16));
    $date = (new DateTime())->format('Y-m-d H:i:s');


    // requete SQL
    $req = $pdo->prepare(
      'INSERT INTO team (
                civilite,
                username,
                nom,
                prenom,
                email,
                password,
                photo_id,
                statut,
                date_enregistrement,
                last_login,
                confirmation,
                token)
            VALUES (
                :civilite,
                :username,
                :nom,
                :prenom,
                :email,
                :password,
                :photo_id,
                :statut,
                :date,
                :last,
                :confirmation,
                :token)'
    );
    // $result['status'] = false;
    // $result['test'] = 'civilite= ' . $civilite . ' username= ' . $username . ' nom= ' . $name . ' prenom= ' . $fname . ' email= ' . $email . ' hash= ' . $hash . ' 	statut= ' . $statut . ' date= ' . $date . ' token= ' . $token;

    $req->bindParam(':civilite', $civilite);
    $req->bindParam(':username', $username);
    $req->bindParam(':nom', $name);
    $req->bindParam(':prenom', $fname);
    $req->bindParam(':email', $email);
    $req->bindParam(':password', $hash);
    $req->bindValue(':photo_id', NULL);
    $req->bindValue(':statut', $statut);
    $req->bindValue(':date', $date);
    $req->bindValue(':last', NULL);
    $req->bindValue(':confirmation', 0);
    $req->bindParam(':token', $token);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = Notifications::notif('success', 'Nouveau membre ajouté');

    $new_member = $pdo->lastInsertId();
    $data = $pdo->query("SELECT username FROM team WHERE id_team_member = '$new_member'");
    $username = $data->fetch(PDO::FETCH_ASSOC);

    postJournal($pdo, 3, 0, 'Nouveau membre de l\'équipe', $username['username'] . ' a été ajouté');

    $record_per_page = 10;
    $page = 0;

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

    if (
      $page > 1
    ) {
      $previous = $page - 1;
      $result['resultat'] .= '<li class="pagination_link" id="' . $previous . '"><span class="page-link"><i class="fas fa-caret-left"></i> Précédent</span></li>';
    }

    if ($page < $total_pages) {
      $page++;
      $result['resultat'] .= '<li class="pagination_link" id="' . $page . '"><span class="page-link">Suivant <i class="fas fa-caret-right"></i></span></li>';
    }

    $result['resultat'] .= '</ul>';

    $result['resultat'] .= '</div>';
  } //fin else

  // Return result 
  echo json_encode($result);
} // 
