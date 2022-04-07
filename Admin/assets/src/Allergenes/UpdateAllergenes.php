<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/AllergenesFunctions.php';

use App\General;
use App\Allergenes;

/* #############################################################################

Update d'un allergene a partir Allergenes.php en Ajax

############################################################################# */

$user = $user['id_team_member'];

$result = array();

if (!empty($_POST)) {

  $id = $_POST['update_id'];
  $titre =  htmlspecialchars($_POST['update_titre']);
  $description = htmlspecialchars($_POST['update_description']);
  if (htmlspecialchars($_POST['update_description'])) {
    $exclusions = htmlspecialchars($_POST['update_description']);
  } else {
    $exclusions = NULL;
  }

  $exclusions = htmlspecialchars($_POST['update_exclusions']);


  $data = $pdo->query("SELECT * FROM allergenes WHERE id = '$id'");
  $thisAller = $data->fetch(PDO::FETCH_ASSOC);

  // debut de la requete d'update
  $param = FALSE;
  $requete = 'UPDATE allergenes SET ';

  if (empty($titre)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le titre');
    postJournal($pdo, $user, 5, 'Tentative de modification d\'un allergene', 'Titre manquant');
  } else {

    if ($titre !== $thisAller['titre']) {
      if (!preg_match('~^[a-zàâçéèêëîïôûùüÿñæœA-Z- ]+$~', $titre)) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'oups! des caractéres ne sont pas autorisé');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'un allergene', 'Caractére non autorisé');
      } else if (getAllergeneBy($pdo, 'titre', $titre) !== null) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'oups cet allergene existe déjà');
        postJournal($pdo, $user, 5, 'Tentative de modification d\'un allergene', 'Catégorie déjà existante');
      } else {
        $requete .= ' titre = :titre';

        $param = TRUE;
      }
    }
  }


  if ($description !== $thisAller['description']) {
    if ($param == TRUE) {

      $requete .= ', description = :description';
    } else {

      $requete .= 'description = :description';
    }

    $param = TRUE;
  }

  if ($exclusions !== $thisAller['exclusions']) {
    if ($param == TRUE) {

      $requete .= ', exclusions = :exclusions';
    } else {

      $requete .= 'exclusions = :exclusions';
    }

    $param = TRUE;
  }

  //lancement de la requete
  $requete .= ' WHERE id= ' . $id;

  //préparation de la requete
  $req_update = $pdo->prepare($requete);

  if ($titre !== $thisAller['titre']) {
    $req_update->bindParam(':titre', $titre);
  }
  if ($description !== $thisAller['description']) {
    $req_update->bindParam(':description', $description);
  }
  if ($exclusions !== $thisAller['exclusions']) {
    $req_update->bindParam(':exclusions', $exclusions);
  }
  $req_update->execute();

  $result['status'] = true;
  $result['notif'] = General::notif('success', 'Allergene modifié');
  postJournal($pdo, $user, 1, 'Allergene modifiée', 'Allergene modifié # ' . $thisAller['id']);

  $record_per_page = 10;
  $page = 0;

  if (isset($_POST['page'])) {

    $page = $_POST['page'];
  } else {

    $page = 1;
  }

  $start_from = ($page - 1) * $record_per_page;

  $query = $pdo->query("SELECT * FROM allergenes ORDER BY titre ASC LIMIT $start_from,$record_per_page");



  $result['resultat'] = '<table>

          <thead>
            <tr>
              <th class="dnone">ID</th>
              <th>Titre</th>';

  $result['resultat'] .= '<th class="desktop">Description</th>';
  $result['resultat'] .= '<th class="desktop">Exclusions</th>';
  $result['resultat'] .= '<th class="desktop"># Plats</th>';
  $result['resultat'] .= ' <th>Actions</th>';
  $result['resultat'] .= ' </tr></thead>';
  $result['resultat'] .= '<tbody class="page_list">';

  while ($row = $query->fetch()) {


    $result['resultat'] .= '<td id="' . $row['id'] . '"class="dnone">' . $row['id'] . '</td>';
    $result['resultat'] .= '<td><strong>' . $row['titre'] . '</strong></td>';
    $result['resultat'] .= '<td class="desktop description">' . $row['description'] .  '</td>';
    $result['resultat'] .= '<td class="desktop description">' . $row['exclusions'] .  '</td>';
    $result['resultat'] .= '<td class="desktop">' . Allergenes::getNbPlats($pdo, $row['id']) . '</td>';
    $result['resultat'] .= '<td class="member_action">';
    $result['resultat'] .= '<div class="member_action-container">';
    $result['resultat'] .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
    $result['resultat'] .= '<input type="button" class="editbtn" id="' . $row['id'] . '"></input>';
    $result['resultat'] .= '<input type="button" class="deletebtn"></input>';
    $result['resultat'] .= '</div>';
    $result['resultat'] .= '</td>';
    $result['resultat'] .= '</tr>';
  }

  $result['resultat'] .= '</tbody></table>';
  if (countAller($pdo) > 10) {
    $result['resultat'] .= '<br /><div class="custom_pagination">';

    $page_query = $pdo->query('SELECT * FROM allergenes ORDER BY titre ASC');
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
} // fin if $_POST Global

// Return result 
echo json_encode($result);
