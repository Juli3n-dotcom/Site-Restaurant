<?php

require_once __DIR__ . '/../../Config/Init.php';

use App\Notifications;

if (!empty($_POST)) {

  $result = array();


  if (!empty($_POST['email'])) {
    $recup_email = htmlspecialchars($_POST['email']);
    if (filter_var($recup_email, FILTER_VALIDATE_EMAIL)) {
      $mailexist = $pdo->prepare('SELECT id_team_member,prenom FROM team WHERE email = ?');
      $mailexist->execute(array($recup_email));
      $mailexist_count = $mailexist->rowCount();

      if ($mailexist_count == 1) {
        $prenom = $mailexist->fetch(PDO::FETCH_ASSOC);
        $prenom = $prenom['prenom'];

        $_SESSION['recup_email'] = $recup_email;
        $recup_code = '';
        for ($i = 0; $i < 8; $i++) {
          $recup_code .= mt_rand(0, 9);
        }


        $mail_recup_exist = $pdo->prepare('SELECT id FROM recuperation WHERE email = ?');
        $mail_recup_exist->execute(array($recup_email));
        $mail_recup_exist = $mail_recup_exist->rowCount();

        if ($mail_recup_exist == 1) {

          $recup_insert = $pdo->prepare('UPDATE recuperation SET code = ? WHERE email = ?');
          $recup_insert->execute(array($recup_code, $recup_email));
        } else {

          $req2 = $pdo->prepare(
            'INSERT INTO recuperation (email, code)
                    VALUE (:email, :code)'
          );
          $req2->bindParam(':email', $recup_email);
          $req2->bindValue(':code', $recup_code);
          $req2->execute();
        }

        $header = "MIME-Version: 1.0\r\n";
        $header .= 'From:"vandreams.fr"<admin@webupdesign.fr>' . "\n";
        $header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
        $header .= 'Content-Transfer-Encoding: 8bit';
        $message = '
                <html>
                <head>
                  <title>Récupération de mot de passe - Van Dreams.fr</title>
                  <meta charset="utf-8" />
                </head>
                <body>
                  <font color="#303030";>
                    <div align="center">
                      <table width="600px">
                        <tr>
                       <img src="https://webupdesign.fr/wp-content/uploads/2021/07/logo_transparent_background.png" alt="logo" width="200" style="display: block;margin-left: auto;
                       margin-right: auto;">
           <td style="background-color: #FFF;padding: 10% 0; border-radius: 10px; font-size: 20px; text-align:center;box-shadow: 10px 10px 15px rgb(0 0 0 / 5%)">
                            
                            <div align="center">Bonjour <b>' . $prenom . '</b>,</div>
                            <br><br>
                            <div align="center">Voici votre code de récupération:</b>,</div>
                            <br><br>
                            <div align="center" style="width: 30%;
                                                      display: block;
                                                      margin: auto;
                                                      padding: 10px 30px;
                                                      height: auto;
                                                      border: 1px solid;
                                                      background:#11101d;border-radius: 10px;
                                                      font-size: 18px;
                                                      color: #e9f4fb;
                                                      font-weight: 700;">
                            <b>' . $recup_code . '</b></div>
                            <br><br>
                            
                          </td>
                        </tr>
                        <tr>
                          <td align="center">
                            <font size="2">
                              Ceci est un email automatique, merci de ne pas y répondre
                            </font>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </font>
                </body>
                </html>
                ';
        mail($recup_email, "Récupération de mot de passe - webupdesign.fr", $message, $header);
        $result['status'] = true;
        $result['resultat'] = '<form method="post"  id="verifcode" enctype="multipart/form-data">
                                <p class="msg_code">Un code de vérification vous a été envoyé sur : <strong>' . $_SESSION['recup_email'] . '</strong></p>
                                <div class="txt_field">
                                  <input type="number" pattern="[0-9]*"  name="verif_code" value="' . $_SESSION['recup_code'] . '">
                                  <span></span>
                                  <label>Code de vérification</label>
                                </div>

                                <input type="submit" name="submit_verif" value="Valider">

                                <div class="signup_link">
                                  Besoin d\'aider <a href="#" class="help__btn">Help</a>
                                </div>
                              </form>';

        $result['notif'] = Notifications::notif('success', 'Email envoyé');
        postJournal($pdo, NULL, 0, 'Tentative de réinitialisation de mot de passe', 'Email envoyé');
      } else { // si membre inconu
        $result['status'] = false;
        $result['notif'] = Notifications::notif('warning', 'Cette adresse email n\'est pas enregistrée');
        postJournal($pdo, NULL, 3, 'Tentative de réinitialisation de mot de passe', 'Cette adresse email n\'est pas enregistrée');
      }
    } else { // si email non valide
      $result['status'] = false;
      $result['notif'] = Notifications::notif('warning', 'Email non valide.');
      postJournal($pdo, NULL, 3, 'Tentative de réinitialisation de mot de passe', 'Email non valide.');
    }
  } else { // si email est absent
    $result['status'] = false;
    $result['notif'] = Notifications::notif('warning', 'Merci de rentrer une adresse email');
    postJournal($pdo, NULL, 5, 'Tentative de réinitialisation de mot de passe', 'Merci de rentrer une adresse email');
  }

  echo json_encode($result);
}
