<?php

require_once __DIR__ . '/../../Config/Init.php';

// use App\Notifications;




if (isset($_POST['submit_verif'], $_POST['verif_code'])) {
  $result = array();
  $email = $_SESSION['recup_email'];

  $result['test'] = 'test';

  // //récupération du membre
  // $req = $pdo->prepare(
  //   'SELECT * FROM team WHERE email = :email'
  // );
  // $req->bindParam(':email', $_POST['username']);
  // $req->execute();
  // $tmember = $req->fetch(PDO::FETCH_ASSOC);

  // if (!empty($_POST['verif_code'])) {
  //   $verif_code = htmlspecialchars($_POST['verif_code']);
  //   $verif_req = $pdo->prepare('SELECT id FROM recuperation WHERE email = ? AND code = ?');
  //   $verif_req->execute(array($email, $verif_code));
  //   $verif_req = $verif_req->rowCount();
  //   if ($verif_req == 1) {
  //     $up_req = $pdo->prepare('UPDATE recuperation set confirm = 1 WHERE email = ?');
  //     $up_req->execute(array($_SESSION['recup_email']));

  //     header("Login.php");
  //   } else { // si code invalide
  //     $result['status'] = false;
  //     $result['notif'] = Notifications::notif('warning', 'Code Invalide');
  //     postJournal($pdo, $tmember['id_team_member'], 4, 'Tentative de réinitialisation de mot de passe', 'Code Invalide');
  //   }
  // } else { // si pas de code de validation
  //   $result['status'] = false;
  //   $result['notif'] = Notifications::notif('warning', 'Veuillez entre votre code de confirmation');
  //   postJournal($pdo, $tmember['id_team_member'], 3, 'Tentative de réinitialisation de mot de passe', 'Veuillez entre votre code de confirmation');
  // }


  echo json_encode($result);
}
