<?php
require_once __DIR__ . '/../../config/Init.php';

use App\General;

/* #############################################################################

Gestion de l'affichage des photos de sous catégories de boissons

############################################################################# */

if (!empty($_POST)) {
  $result = array();
  $publie = $_POST['publie'];
  $user = $user['id_team_member'];

  if ($publie == 0) {

    $req = $pdo->prepare('UPDATE options SET show_sub_cat_drink_photo = :publie');

    $req->bindValue(':publie', 1);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');
    postJournal($pdo, $user, 0, 'Modification d\'option', 'Affichage des images des sous catégories de boissons');

    $query = $pdo->query("SELECT * FROM Options");


    while ($options = $query->fetch()) {

      $result['resultat'] = '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_sub_cat_drink_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_sub_cat_drink_photo" name="show_sub_cat_drink_photo" class="est_publie" value=' . $options['show_sub_cat_drink_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_sub_cat_drink_photo" name="show_sub_cat_drink_photo" class="est_publie" value=' . $options['show_sub_cat_drink_photo'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      // card2
      $result['resultat'] .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-keyboard"></i>
              <div>
                <h5>Afficher Description</h5>';
      if ($options['show_sub_cat_drink_description'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_sub_cat_drink_description" name="show_sub_cat_drink_description" class="est_publie" value=' . $options['show_sub_cat_drink_description'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_sub_cat_drink_description" name="show_sub_cat_drink_description" class="est_publie" value=' . $options['show_sub_cat_drink_description'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
    }
  } else {

    $req = $pdo->prepare('UPDATE options SET show_sub_cat_drink_photo = :publie');

    $req->bindValue(':publie', 0);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');
    postJournal($pdo, $user, 0, 'Modification d\'option', 'Retrait des images des sous catégories de boissons');

    $query = $pdo->query("SELECT * FROM Options");

    while ($options = $query->fetch()) {

      $result['resultat'] = '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_sub_cat_drink_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_sub_cat_drink_photo" name="show_sub_cat_drink_photo" class="est_publie" value=' . $options['show_sub_cat_drink_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_sub_cat_drink_photo" name="show_sub_cat_drink_photo" class="est_publie" value=' . $options['show_sub_cat_drink_photo'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      // card2
      $result['resultat'] .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-keyboard"></i>
              <div>
                <h5>Afficher Description</h5>';
      if ($options['show_sub_cat_drink_description'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_sub_cat_drink_description" name="show_sub_cat_drink_description" class="est_publie" value=' . $options['show_sub_cat_drink_description'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_sub_cat_drink_description" name="show_sub_cat_drink_description" class="est_publie" value=' . $options['show_sub_cat_drink_description'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
    }
  }



  echo json_encode($result);;
}
