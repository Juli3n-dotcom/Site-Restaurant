<?php
require_once __DIR__ . '/../../config/Bootstrap.php';
require_once __DIR__ . '/../../functions/TeamFunctions.php';

use App\History;

/* #############################################################################

vue d'un element d'history a partir History.php en Ajax

############################################################################# */

if (isset($_POST['history_id'])) {

  $result = '';
  $id = $_POST['history_id'];

  $query = $pdo->query("SELECT * FROM journal WHERE id = '$id'");

  $result .= '<div class="list_container history">';

  $result .= '<ul>';

  while ($row = $query->fetch()) {

    $date = str_replace('/', '-', $row['date_enregistrement']);

    $result .= '<li>
                    <h6>ID : </h6>
                    <p>' . $row['id'] . '</p>
                  </li>';
    $result .= '<li>
          <h6>Status : </h6>
              <div class="history_statut">' . History::getStatut($row['statut']) . '</div>
            </li>';
    $result .= '<li>
                    <h6>Titre : </h6>
                    <p>' . $row['titre'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Contenu : </h6>
                    <p>' . $row['contenu'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Date : </h6>
                    <p>' . date('d-m-Y', strtotime($date)) . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Username : </h6>
                    <p>' . History::getMembre($pdo, $row['member_id']) . '</p>
                </li>';
  }

  $result .= '</ul>';

  $result .= '</div>';


  echo $result;
}
