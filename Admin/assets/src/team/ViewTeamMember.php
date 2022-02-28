<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../functions/TeamFunctions.php';

use App\Team;
/* #############################################################################

vue d'un member a partir team.php en Ajax

############################################################################# */


if (isset($_POST['member_id'])) {

  $result = '';
  $id = $_POST['member_id'];

  $query = $pdo->query("SELECT * FROM team WHERE id_team_member = '$id'");

  $result .= '<div class="list_container">';

  $result .= '<ul>';

  while ($row = $query->fetch()) {

    $date = str_replace('/', '-', $row['date_enregistrement']);
    $last_date = str_replace('/', '-', $row['last_login']);

    $result .= '<li>
                    <h6>ID : </h6>
                    <p>' . $row['id_team_member'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Nom : </h6>
                    <p>' . $row['nom'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Prénom : </h6>
                    <p>' . $row['prenom'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Email : </h6>
                    <p>' . $row['email'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Username : </h6>
                    <p>' . $row['username'] . '</p>
                </li>';
    $result .= '<li>';
    $result .= '<h6>Status : </h6>';
    $result .= Team::getStatut($row['statut']);

    $result .= '</li>';

    // if ($Membre['statut'] == 0) {

    $result .= '<li>';
    $result .= '<h6>Confirmation : </h6>';
    $result .= '<p>';

    $result .= Team::getConfirmation($row['confirmation']);

    $result .= '</p>';
    $result .= '</li>';

    $result .= '<li>
                    <h6>Derniére connexion : </h6>
                    <p>' . date('d-m-Y', strtotime($last_date)) . '</p>
                  </li>';
    $result .= '<li>
                      <h6>Date d\'enregistrement : </h6>
                      <p>' . date('d-m-Y', strtotime($date)) . '</p>
                    </li>';
    // }
  }

  $result .= '</ul>';

  $result .= '</div>';


  echo $result;
}
