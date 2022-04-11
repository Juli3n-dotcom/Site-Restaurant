<?php
require_once __DIR__ . '/../../config/Init.php';

use App\General;

/* #############################################################################

Gestion de l'affichage des photos des boissons

############################################################################# */

if (!empty($_POST)) {
  $result = array();
  $publie = $_POST['publie'];
  $user = $user['id_team_member'];

  if ($publie == 0) {

    $req = $pdo->prepare('UPDATE options SET show_drink_photo = :publie');

    $req->bindValue(':publie', 1);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');
    postJournal($pdo, $user, 0, 'Modification d\'option', 'Affichage des images des boissons');

    $query = $pdo->query("SELECT * FROM Options");


    while ($options = $query->fetch()) {

      $result['resultat'] = '<div class="card__single">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_drink_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_photo_est_publie" name="drinks_photo_est_publie" class="est_publie" value=' . $options['show_drink_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_photo_est_publie" name="drinks_photo_est_publie" class="est_publie" value=' . $options['show_drink_photo'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      // card2
      $result['resultat'] .= '<div class="card__single">
              <div class="card__body">
                <i class="fas fa-chart-area"></i>
              <div>
                <h5>Afficher Stats</h5>';
      if ($options['show_drinks_stats'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_stats_est_publie" name="drinks_stats_est_publie" class="est_publie" value=' . $options['show_drinks_stats'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_stats_est_publie" name="drinks_stats_est_publie" class="est_publie" value=' . $options['show_drinks_stats'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card3
      $result['resultat'] .= '<div class="card__single">
              <div class="card__body">';
      if ($options['show_drinks_en_avant']) {
        $result['resultat'] .= ' <i class="fa-solid fa-star"></i>';
      } else {
        $result['resultat'] .= ' <i class="fa-regular fa-star"></i>';
      }
      $result['resultat'] .= '<div>
                <h5>Afficher Mise en avant</h5>';
      if ($options['show_drinks_en_avant'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_en_avant_est_publie" name="drinks_en_avant_est_publie" class="est_publie" value=' . $options['show_drinks_en_avant'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_en_avant_est_publie" name="drinks_en_avant_est_publie" class="est_publie" value=' . $options['show_drinks_en_avant'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
    }
  } else {

    $req = $pdo->prepare('UPDATE options SET show_drink_photo = :publie');

    $req->bindValue(':publie', 0);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');

    postJournal($pdo, $user, 0, 'Modification d\'option', 'Retrait des images des boissons');

    $query = $pdo->query("SELECT * FROM Options");

    while ($options = $query->fetch()) {

      $result['resultat'] = '<div class="card__single">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_drink_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_photo_est_publie" name="drinks_photo_est_publie" class="est_publie" value=' . $options['show_drink_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_photo_est_publie" name="drinks_photo_est_publie" class="est_publie" value=' . $options['show_drink_photo'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      // card2
      $result['resultat'] .= '<div class="card__single">
              <div class="card__body">
                <i class="fas fa-chart-area"></i>
              <div>
                <h5>Afficher Stats</h5>';
      if ($options['show_drinks_stats'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_stats_est_publie" name="drinks_stats_est_publie" class="est_publie" value=' . $options['show_drinks_stats'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_stats_est_publie" name="drinks_stats_est_publie" class="est_publie" value=' . $options['show_drinks_stats'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card3
      $result['resultat'] .= '<div class="card__single">
              <div class="card__body">';
      if ($options['show_drinks_en_avant']) {
        $result['resultat'] .= ' <i class="fa-solid fa-star"></i>';
      } else {
        $result['resultat'] .= ' <i class="fa-regular fa-star"></i>';
      }
      $result['resultat'] .= '<div>
                <h5>Afficher Mise en avant</h5>';
      if ($options['show_drinks_en_avant'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_en_avant_est_publie" name="drinks_en_avant_est_publie" class="est_publie" value=' . $options['show_drinks_en_avant'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="drinks_en_avant_est_publie" name="drinks_en_avant_est_publie" class="est_publie" value=' . $options['show_drinks_en_avant'] . '></td>';
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
