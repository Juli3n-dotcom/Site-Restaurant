<?php

require_once __DIR__ . '/../../Config/Bootstrap.php';

use App\Notifications;

/* #############################################################################

Login script à partir de Login.php en Ajax

############################################################################# */

if (!empty($_POST)) {

  $result = array();
  $username = htmlspecialchars($_POST['username']);
  $mdp = htmlspecialchars($_POST['current-password']);

  if (empty($username)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque le pseudo');
    postJournal($pdo, NULL, 5, 'Tentative de connexion', 'oups! il manque le pseudo');
  } elseif (empty($mdp)) {
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'oups! il manque votre mot de passe');
    postJournal($pdo, NULL, 5, 'Tentative de connexion', 'oups! il manque votre mot de passe');
  } else {
    $req = $pdo->prepare(
      'SELECT * FROM team WHERE username = :username OR email = :email'
    );

    $req->bindParam(':username', $_POST['username']);
    $req->bindParam(':email', $_POST['username']);
    $req->execute();
    $tmember = $req->fetch(PDO::FETCH_ASSOC);

    if (!$tmember) {

      $result['status'] = false;
      $result['notif'] = Notifications::notif('error', 'Membre inconnu');
      postJournal($pdo, NULL, 4, 'Tentative de connexion', 'Membre inconnu');
    } elseif (!$tmember['confirmation']) {

      $result['status'] = false;
      $result['notif'] = Notifications::notif('info', 'Merci de confirmer votre compte');
      postJournal($pdo, $tmember['id_team_member'], 5, 'Tentative de connexion', 'Compte non confirmé');
    } elseif (!password_verify($mdp, $tmember['password'])) {

      $result['status'] = false;
      $result['notif'] = Notifications::notif('error', 'Mot de passe erroné');
      postJournal($pdo, $tmember['id_team_member'], 4, 'Tentative de connexion', 'Mot de passe erroné');
    } else {
      postJournal($pdo, $tmember['id_team_member'], 3, 'Connexion', $tmember['username'] . ' s\'est connecté');


      $id = $tmember['id_team_member'];

      $req_update = $pdo->prepare(
        'UPDATE team SET
                last_login = :date
                WHERE id_team_member = :id'
      );
      $req_update->bindParam(':id', $id, PDO::PARAM_INT);
      $req_update->bindValue(':date', (new DateTime())->format('Y-m-d H:i:s'));
      $req_update->execute();

      $result['cookie'] = $tmember['token'];
      session_write_close();
      $result['status'] = true;
      $result['session'] = $tmember;
      $result['location'] = 'Index.php';
    }
  }

  echo json_encode($result);
}
