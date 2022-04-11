<?php
require_once __DIR__ . '/../../Config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksFunctions.php';

use App\General;
use App\Drinks;

$user = $user['id_team_member'];
/* #############################################################################

Delete d'une boisson à partir Drinks.php en Ajax

#1 Confirmation du suppresion
#2 récupération des informations de la boissons
#3 récupération des boissons avec une position supérieur 
#4 update des positions des autres boissons
#5 si image, suppression des images 
#6 suppression de la boisson
#7 suppression allergenes
#8 retour AJAX

############################################################################# */

if (!empty($_POST)) {

  $result = array();
  $id = $_POST['id'];
  $confirme = 'on';

  if (($_POST['confirmedelete']) !== $confirme) {
    #1
    $result['status'] = false;
    $result['notif'] = General::notif('error', 'Merci de confirmer la suppression');
    postJournal($pdo, $user, 4, 'Tentative de suppresion d\'une boisson', 'Suppression non confirmée');
  } else {
    #2
    $data = $pdo->query("SELECT * FROM boissons WHERE id = '$id'");
    $plat = $data->fetch(PDO::FETCH_ASSOC);
    $titre = $plat['titre'];
    $pics = $plat['photo_id'];
    $position = $plat['position'];

    #3
    $req = $pdo->query("SELECT id, position FROM boissons WHERE position > $position");
    $upPlat = $req->fetchAll(PDO::FETCH_ASSOC);

    #4
    foreach ($upPlat as $Plats) {
      $updatePos = $pdo->prepare('UPDATE boissons SET position = :position WHERE id=' . $Plats['id']);
      $updatePos->bindValue(':position', --$Plats['position']);
      $updatePos->execute();
    }

    #5
    if ($pics !== null) {

      //suppresion des images de plat

      $data = $pdo->query("SELECT * FROM plats_photo WHERE id_photo = '$pics'");
      $photo = $data->fetch(PDO::FETCH_ASSOC);

      $file = __DIR__ . '/../../../../Global/Uploads/';

      $dir = opendir($file);
      unlink($file . $photo['img__jpeg']);
      unlink($file . $photo['img__webp']);
      closedir($dir);

      $pathOriginal = __DIR__ . '/../../../../Global/Uploads/Originals/';

      $dirOriginal = opendir($pathOriginal);
      unlink($pathOriginal . $photo['original']);
      closedir($dirOriginal);


      $req1 = $pdo->exec("DELETE FROM plats_photo WHERE id_photo = '$pics'");
    }


    #6
    $req3 = $pdo->exec("DELETE FROM boissons WHERE id = '$id'");

    #7
    $query = $pdo->prepare("SELECT * FROM boissons_allergenes_liaison WHERE boisson_id = '$id'");
    $drink = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($drink as $a) {
      $liaisonID = $a['id'];
      $req4 = $pdo->exec("DELETE FROM boisson_allergenes_liaison WHERE id = '$liaisonID'");
    }

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Boisson supprimée');
    postJournal($pdo, $user, 2, 'Boisson supprimée', 'Boisson ' . $titre . ' supprimée');

    #8 - Retour AJAX 
    $ua = getBrowser();

    $record_per_page = 10;
    $page = 0;

    if (isset($_POST['page'])) {

      $page = $_POST['page'];
    } else {

      $page = 1;
    }

    $start_from = ($page - 1) * $record_per_page;

    $query = $pdo->query("SELECT * FROM boissons ORDER BY cat_id, titre ASC LIMIT $start_from,$record_per_page");

    $result['nbdrinks'] = countDrinks($pdo);

    $result['resultat'] = '<table>

          <thead>
            <tr>
              <th class="dnone">ID</th>';
    //$result['resultat'] .= '<th class="tab desktop" title="Position de l\'element sur la carte">#</th>'
    $result['resultat'] .= '<th>Titre</th>';
    if ($options['show_drink_photo'] == 1) {
      $result['resultat'] .= '<th>Image</th>';
    }
    $result['resultat'] .= '<th class="desktop">Prix</th>';
    $result['resultat'] .= '<th class="desktop">Description</th>';
    $result['resultat'] .= '<th class="tab desktop">Catégorie</th>';
    if ($options['show_drinks_stats'] == 1) {
      $result['resultat'] .= '<th class="desktop"><i class="fas fa-chart-area"></i></th>';
    }
    if ($options['show_drinks_en_avant'] == 1) {
      $result['resultat'] .= '<th class="tab desktop">Mise en avant</th>';
    }
    $result['resultat'] .= '<th class="tab desktop">Afficher</th>
              <th>Actions</th>';
    $result['resultat'] .= ' </tr></thead>';
    $result['resultat'] .= '<tbody class="page_list">';

    while ($row = $query->fetch()) {

      //$result['resultat'] .= '<tr data-position="' . $row['position'] . '" data-id="' . $row['id'] . '">';
      // $result['resultat'] .= '<td class="tab desktop">' . $row['position'] . '</td>';
      $result['resultat'] .= '<td id="' . $row['id'] . '"class="dnone">' . $row['id'] . '</td>';
      $result['resultat'] .= '<td class="plat_title"><strong>' . $row['titre'] . '</strong>' . ($row['est_nouveau'] ? '<span class="new"> nouveau</span>' : '') . '</td>';
      if ($options['show_drink_photo'] == 1) {
        $result['resultat'] .= '<td class="dnone cat_pics">' . $row['photo_id'] . '</td>';
        $result['resultat'] .= '<td class="td-team">' . Drinks::getImage($pdo, $row['photo_id'], $ua['name']) . '</td>';
      }
      $result['resultat'] .= '<td class="desktop">' . $row['prix'] . ' €</td>';
      $result['resultat'] .= '<td class="desktop description">' . $row['description'] . '</td>';
      $result['resultat'] .= '<td class="tab desktop"><strong>' . Drinks::getCat($pdo, $row['cat_id']) . '</strong></td>';
      if ($options['show_drinks_stats'] == 1) {
        $result['resultat'] .= '<td class="desktop">0</td>';
      }
      if ($options['show_drinks_en_avant'] == 1) {
        $result['resultat'] .= '<th class="tab desktop">' . Drinks::getEstEnAvant($pdo, $row['id']) . '</th>';
      }
      if ($row['est_publie'] == 1) {

        $result['resultat'] .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . '></td>';
      }
      $result['resultat'] .= '<td class="member_action">';
      $result['resultat'] .= '<div class="member_action-container">';
      $result['resultat'] .= '<a href="FichePlat.php?id=' . $row['id'] . '" class="linkbtn"><i class="fa-regular fa-eye"></i></a>';
      $result['resultat'] .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
      $result['resultat'] .= '<input type="button" class="editbtn" id="' . $row['id'] . '"></input>';
      $result['resultat'] .= '<input type="button" class="deletebtn"></input>';
      $result['resultat'] .= '</div>';
      $result['resultat'] .= '</td>';
      $result['resultat'] .= '</tr>';
    }

    $result['resultat'] .= '</tbody></table>';
    if (countDrinks($pdo) > 10) {
      $result['resultat'] .= '<br /><div class="custom_pagination">';

      $page_query = $pdo->query('SELECT * FROM boissons ORDER BY cat_id, titre ASC');
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
  }
  // Return result 
  echo json_encode($result);
}
