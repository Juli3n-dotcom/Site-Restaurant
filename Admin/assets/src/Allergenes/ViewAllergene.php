<?php
require_once __DIR__ . '/../../Config/Init.php';

/* #############################################################################

vue d'un Allergenes a partir Allergenes.php en Ajax

############################################################################# */

if (isset($_POST['aller_id'])) {

  $result = '';
  $id = $_POST['aller_id'];

  $query = $pdo->query("SELECT * FROM allergenes WHERE id = '$id'");

  $result .= '<div class="list_container allergenes">';



  while ($row = $query->fetch()) {

    $date = str_replace('/', '-', $row['date_enregistrement']);

    $result .= '<ul>';

    $result .= '<li>
                    <h6>ID : </h6>
                    <p>' . $row['id'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Titre : </h6>
                    <p>' . $row['titre'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Description : </h6>
                    <p class="description">' . $row['description'] . '</p>
                  </li>';
    if ($row['exclusions'] !== null) {
      $result .= '<li>
                    <h6>Exclusions : </h6>
                    <p class="description">' . $row['exclusions'] . '</p>
                  </li>';
    }


    $result .= '</ul>';

    $result .= '</div>';
  }
  echo $result;
}
