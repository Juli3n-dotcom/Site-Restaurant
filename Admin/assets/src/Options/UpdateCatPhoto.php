<?php
require_once __DIR__ . '/../../config/Init.php';

use App\General;

/* #############################################################################

Gestion de l'affichage des photos des categories

############################################################################# */

if (!empty($_POST)) {
  $result = array();
  $publie = $_POST['publie'];
  $user = $user['id_team_member'];

  if ($publie == 0) {

    $req = $pdo->prepare('UPDATE options SET show_cat_photo = :publie');

    $req->bindValue(':publie', 1);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');
    postJournal($pdo, $user, 0, 'Modification d\'option', 'Affichage des images de catégories de plats');

    $query = $pdo->query("SELECT * FROM Options");


    while ($options = $query->fetch()) {

      $result['resultat'] = '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_cat_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_photo_est_publie" name="cat_photo_est_publie" class="est_publie" value=' . $options['show_cat_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_photo_est_publie" name="cat_photo_est_publie" class="est_publie" value=' . $options['show_cat_photo'] . '></td>';
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
      if ($options['show_cat_description'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_desc_est_publie" name="cat_desc_est_publie" class="est_publie" value=' . $options['show_cat_description'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_desc_est_publie" name="cat_desc_est_publie" class="est_publie" value=' . $options['show_cat_description'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card3
      $result['resultat'] .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="fas fa-hashtag"></i>
              <div>
                <h5>Afficher Nombre de pièces</h5>';
      if ($options['show_cat_pieces'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_p_est_publie" name="cat_p_est_publie" class="est_publie" value=' . $options['show_cat_pieces'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_p_est_publie" name="cat_p_est_publie" class="est_publie" value=' . $options['show_cat_pieces'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card4
      $result['resultat'] .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="fa-solid fa-diagram-predecessor"></i>
              <div>
                <h5>Activer sous-catégories</h5>';
      if ($options['show_sous_cat'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="sub_cat_est_publie" name="sub_cat_est_publie" class="est_publie" value=' . $options['show_sous_cat'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="sub_cat_est_publie" name="sub_cat_est_publie" class="est_publie" value=' . $options['show_sous_cat'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
    }
  } else {

    $req = $pdo->prepare('UPDATE options SET show_cat_photo = :publie');

    $req->bindValue(':publie', 0);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');

    postJournal($pdo, $user, 0, 'Modification d\'option', 'Retrait des images de catégories de plats');

    $query = $pdo->query("SELECT * FROM Options");

    while ($options = $query->fetch()) {

      $result['resultat'] = '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_cat_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_photo_est_publie" name="cat_photo_est_publie" class="est_publie" value=' . $options['show_cat_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_photo_est_publie" name="cat_photo_est_publie" class="est_publie" value=' . $options['show_cat_photo'] . '></td>';
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
      if ($options['show_cat_description'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_desc_est_publie" name="cat_desc_est_publie" class="est_publie" value=' . $options['show_cat_description'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_desc_est_publie" name="cat_desc_est_publie" class="est_publie" value=' . $options['show_cat_description'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card3
      $result['resultat'] .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="fas fa-hashtag"></i>
              <div>
                <h5>Afficher Nombre de pièces</h5>';
      if ($options['show_cat_pieces'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_p_est_publie" name="cat_p_est_publie" class="est_publie" value=' . $options['show_cat_pieces'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="cat_p_est_publie" name="cat_p_est_publie" class="est_publie" value=' . $options['show_cat_pieces'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card4
      $result['resultat'] .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="fa-solid fa-diagram-predecessor"></i>
              <div>
                <h5>Activer sous-catégories</h5>';
      if ($options['show_sous_cat'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="sub_cat_est_publie" name="sub_cat_est_publie" class="est_publie" value=' . $options['show_sous_cat'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="sub_cat_est_publie" name="sub_cat_est_publie" class="est_publie" value=' . $options['show_sous_cat'] . '></td>';
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
