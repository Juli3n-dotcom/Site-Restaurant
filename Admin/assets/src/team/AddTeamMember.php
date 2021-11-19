<?php
require_once __DIR__ . '/../../config/Bootstrap.php';

use App\Notifications;
use App\TeamFunctions;


/* #############################################################################

Ajout d'un member a partir team.php en Ajax

############################################################################# */

if (!empty($_POST)) {

  $result = array();

  if (!preg_match('~^[a-zA-Z-]+$~', $_POST['add_name_member'])) {

    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque le nom');
  } elseif (!preg_match('~^[a-zA-Z-]+$~', $_POST['add_prenom_member'])) {

    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque le prénom');
  } elseif (TeamFunctions::getMemberBy($pdo, 'email', $_POST['add_email_member']) !== null) {

    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'email déjà utilisé');
  } elseif (!filter_var($_POST['add_email_member'], FILTER_VALIDATE_EMAIL)) {

    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'email non valide ou manquant');
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

    //création de l'username + création du name
    $first_name = $_POST['add_prenom_member'];
    $a = $first_name[0];
    $explode_name = explode(' ', $_POST['add_name_member']);
    $explode_fn = explode(' ', $_POST['add_prenom_member']);

    $username = strtolower($a . $explode_name[0]);
    $name = 'Fr' . $explode_fn[0] . $explode_name[0] . bin2hex(random_bytes(6));

    //autres valeurs
    $token = bin2hex(random_bytes(16));
    $civilite = $_POST['add_civilite'];
    $statut = $_POST['add_statut'];

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
                token,
                name
            )
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
                :token,
                :name
            )'
    );

    $req->bindParam(':civilite', $civilite);
    $req->bindParam(':username', $username);
    $req->bindParam(':nom', $_POST['add_name_member']);
    $req->bindParam(':prenom', $_POST['add_prenom_member']);
    $req->bindParam(':email', $_POST['add_email_member']);
    $req->bindParam(':password', $hash);
    $req->bindValue(':photo_id', NULL);
    $req->bindValue(':statut', $statut);
    $req->bindValue(':date', (new DateTime())->format('Y-m-d H:i:s'));
    $req->bindValue(':last', NULL);
    $req->bindValue(':confirmation', 0);
    $req->bindParam(':token', $token);
    $req->bindParam(':name', $name);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = Notifications::notif('success', 'Nouveau membre ajouté');

    //retour ajax card
    $result['cards'] = '<div class="card__single">
      <div class="card__body">
        <i class="fas fa-user-shield"></i>
        <div>
          <h5>Admin</h5>
          <h4></h4>
        </div>
      </div>
      <div class="card__footer">
        <a href="">View all</a>
      </div>
  </div>';

    $result['cards'] .= '<div class="card__single">
      <div class="card__body">
        <i class="fas fa-user"></i>
        <div>
          <h5>User</h5>
          <h4></h4>
        </div>
      </div>
      <div class="card__footer">
        <a href="">View all</a>
    </div>
  </div>';

    $result['cards'] .= '<div class="card__single">
      <div class="card__body">
        <i class="fas fa-user-edit"></i>
        <div>
            <h5>Editeur</h5>
            <h4></h4>
        </div>
      </div>
      <div class="card__footer">
        <a href="">View all</a>
      </div>
    </div>';

    // préparation retour Ajax
    $query = $pdo->query('SELECT * FROM team');

    //retour ajax table
    $result['resultat'] = '<table>';

    $result['resultat'] .= '<thead>
                <tr>
                  <th>ID</th>
                  <th class="dnone">Civilité</th>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Photo</th>
                  <th>Email</th>
                  <th>Status</th>';
    if ($Membre['statut'] == 0) {
      $result['resultat'] .= '<th>Confirmation</th>';
      $result['resultat'] .= '<th>Actions</th>';
    } else {
      $result['resultat'] .= '<th>Action</th>';
    }

    $result['resultat'] .=  '</tr>
            </thead>';

    $result['resultat'] .= '<tbody>';

    while ($member = $query->fetch()) {

      $result['resultat'] .= '<tr>';
      $result['resultat'] .= '<td>' . $member['id_team_member'] . '</td>';
      $result['resultat'] .= '<td class="dnone">' . $member['civilite'] . '</td>';
      $result['resultat'] .= '<td>' . $member['nom'] . '</td>';
      $result['resultat'] .= '<td>' . $member['prenom'] . '</td>';
      $result['resultat'] .= ' <td class="td-team">';
      if ($member['photo_id'] == NULL) {
        if ($member['civilite'] == 0) {
          $result['resultat'] .= '<div class="img-profil" style="background-image: url(assets/img/male.svg)"></div>';
        } elseif ($member['civilite'] == 1) {
          $result['resultat'] .= '<div class="img-profil" style="background-image: url(assets/img/female.svg)"></div>';
        } else {
          $result['resultat'] .= '<div class="img-profil" style="background-image: url(assets/img/profil.svg)"></div>';
        }
      } else {
        $result['resultat'] .= '<div class="img-profil" style="background-image: url(assets/uploads/ )"></div>';
      }
      $result['resultat'] .= '</td>';
      $result['resultat'] .= '<td><a href="mailto:' . $member['email'] . '" class="email_member">' . $member['email'] . '</a></td>';

      $result['resultat'] .= '<td>';
      if ($member['statut'] == 0) {
        $result['resultat'] .= '<p class="badge admin">Admin</p>';
      } elseif ($member['statut'] == 1) {
        $result['resultat'] .= '<p class="badge user">User</p>';
      } else {
        $result['resultat'] .= '<p class="badge editer">Editeur</p>';
      }
      $result['resultat'] .= '</td>';

      if ($Membre['statut'] == 0) {
        $result['resultat'] .= '<td class="dnone"><i>' . $member['confirmation'] . '</i></td>';
        $result['resultat'] .= '<td>';
        if ($member['confirmation'] == 0) {
          $result['resultat'] .= '<p class="badge danger confirmation">Non</p>';
        } else {
          $result['resultat'] .= '<p class="badge success confirmation">Oui</p>';
        }
        $result['resultat'] .= '</td>';
      }

      $result['resultat'] .= '<td class="member_action">';
      $result['resultat'] .= '<input type="button" class="viewbtn" name="view" id="' . $member['id_team_member'] . '"></input>';

      if ($Membre['statut'] == 0) {
        $result['resultat'] .= '<input type="button" class="editbtn" id="' . $member['id_team_member'] . '"></input>';
        $result['resultat'] .= '<input type="button" class="deletebtn"></input>';
      }
      $result['resultat'] .= '</td>';

      $result['resultat'] .= '</tr>';
    }

    $result['resultat'] .= '</tbody>';

    $result['resultat'] .= '</table>';
  } // fin else


  // Return result 
  echo json_encode($result);
} // 
