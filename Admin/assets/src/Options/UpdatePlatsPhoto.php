<?php
require_once __DIR__ . '/../../config/Init.php';

use App\General;

/* #############################################################################

Gestion de l'affichage des photos des plats

############################################################################# */

if (!empty($_POST)) {
  $result = array();
  $publie = $_POST['publie'];
  $user = $user['id_team_member'];

  if ($publie == 0) {

    $req = $pdo->prepare('UPDATE options SET show_plat_photo = :publie');

    $req->bindValue(':publie', 1);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');
    postJournal($pdo, $user, 0, 'Modification d\'option', 'Affichage des images des plats');

    $query = $pdo->query("SELECT * FROM Options");


    while ($options = $query->fetch()) {

      $result['resultat'] = '<div class="card__single">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_plat_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_photo_est_publie" name="plat_photo_est_publie" class="est_publie" value=' . $options['show_plat_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_photo_est_publie" name="plat_photo_est_publie" class="est_publie" value=' . $options['show_plat_photo'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      // card2
      $result['resultat'] .=
        '<div class="card__single">
              <div class="card__body">
               <i class="fas fa-chart-area"></i>
              <div>
                <h5>Afficher Stats</h5>';
      if ($options['show_plat_stats'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_stats_est_publie" name="plat_stats_est_publie" class="est_publie" value=' . $options['show_plat_stats'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_stats_est_publie" name="plat_stats_est_publie" class="est_publie" value=' . $options['show_plat_stats'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card3
      $result['resultat'] .= '<div class="card__single">
              <div class="card__body">';
      if ($options['show_plat_en_avant']) {
        $result['resultat'] .= ' <i class="fa-solid fa-star"></i>';
      } else {
        $result['resultat'] .= ' <i class="fa-regular fa-star"></i>';
      }
      $result['resultat'] .= '<div>
                <h5>Afficher Mise en avant</h5>';
      if ($options['show_plat_en_avant'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_en_avant_est_publie" name="plat_en_avant_est_publie" class="est_publie" value=' . $options['show_plat_en_avant'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_en_avant_est_publie" name="plat_en_avant_est_publie" class="est_publie" value=' . $options['show_plat_en_avant'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
    }
  } else {

    $req = $pdo->prepare('UPDATE options SET show_plat_photo = :publie');

    $req->bindValue(':publie', 0);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');

    postJournal($pdo, $user, 0, 'Modification d\'option', 'Retrait des images des plats');

    $query = $pdo->query("SELECT * FROM Options");

    while ($options = $query->fetch()) {

      $result['resultat'] = '<div class="card__single">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_plat_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_photo_est_publie" name="cat_photo_est_publie" class="est_publie" value=' . $options['show_plat_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_photo_est_publie" name="cat_photo_est_publie" class="est_publie" value=' . $options['show_plat_photo'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      // card2
      $result['resultat'] .=
        '<div class="card__single">
              <div class="card__body">
               <i class="fas fa-chart-area"></i>
              <div>
               <h5>Afficher Stats</h5>';
      if ($options['show_plat_stats'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_stats_est_publie" name="plat_stats_est_publie" class="est_publie" value=' . $options['show_plat_stats'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_stats_est_publie" name="plat_stats_est_publie" class="est_publie" value=' . $options['show_plat_stats'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card3
      $result['resultat'] .= '<div class="card__single">
              <div class="card__body">';
      if ($options['show_plat_en_avant']) {
        $result['resultat'] .= ' <i class="fa-solid fa-star"></i>';
      } else {
        $result['resultat'] .= ' <i class="fa-regular fa-star"></i>';
      }
      $result['resultat'] .= '<div>
                <h5>Afficher Mise en avant</h5>';
      if ($options['show_plat_en_avant'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_en_avant_est_publie" name="plat_en_avant_est_publie" class="est_publie" value=' . $options['show_plat_en_avant'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="plat_en_avant_est_publie" name="plat_en_avant_est_publie" class="est_publie" value=' . $options['show_plat_en_avant'] . '></td>';
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
