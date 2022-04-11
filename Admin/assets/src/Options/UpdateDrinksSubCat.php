<?php
require_once __DIR__ . '/../../config/Init.php';

use App\General;

/* #############################################################################

Gestion de l'affichage des photos des categories de boissons

############################################################################# */

if (!empty($_POST)) {
  $result = array();
  $publie = $_POST['publie'];
  $user = $user['id_team_member'];

  if ($publie == 0) {

    $req = $pdo->prepare('UPDATE options SET show_drink_sous_cat = :publie');

    $req->bindValue(':publie', 1);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');
    postJournal($pdo, $user, 0, 'Modification d\'option', 'Affichage des images de catégories de boissons');

    $query = $pdo->query("SELECT * FROM Options");

    while ($options = $query->fetch()) {

      $result['subcat'] = TRUE;

      $result['menu'] = '<div class="icon-link">
          <a href="#">
            <i class="bx bxs-drink"></i>

            <span class="link_name">Boissons</span>

          </a>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="Drinks.php">Boissons</a></li>
          <li><a href="DrinksCat.php">Catégories</a></li>';
      if ($options['show_drink_sous_cat']) {
        $result['menu'] .=         '<li><a href="DrinksSubCat.php">Sous Catégories</a></li>';
      }
      $result['menu'] .=     '</ul>';

      $result['resultat'] = '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_cat_drink_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_cat_drink_photo" name="show_cat_drink_photo" class="est_publie" value=' . $options['show_cat_drink_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_cat_drink_photo" name="show_cat_drink_photo" class="est_publie" value=' . $options['show_cat_drink_photo'] . '></td>';
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
      if ($options['show_cat_drink_description'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_cat_drink_description" name="show_cat_drink_description" class="est_publie" value=' . $options['show_cat_drink_description'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_cat_drink_description" name="show_cat_drink_description" class="est_publie" value=' . $options['show_cat_drink_description'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card3
      $result['resultat'] .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="fa-solid fa-diagram-predecessor"></i>
              <div>
                <h5>Activer sous-catégories</h5>';
      if ($options['show_drink_sous_cat'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_drink_sous_cat" name="show_drink_sous_cat" class="est_publie" value=' . $options['show_drink_sous_cat'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_drink_sous_cat" name="show_drink_sous_cat" class="est_publie" value=' . $options['show_drink_sous_cat'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
    }
  } else {

    $req = $pdo->prepare('UPDATE options SET show_drink_sous_cat = :publie');

    $req->bindValue(':publie', 0);
    $req->execute();

    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Modification effectuée');

    postJournal($pdo, $user, 0, 'Modification d\'option', 'Retrait des images de catégories de boissons');

    $query = $pdo->query("SELECT * FROM Options");

    while ($options = $query->fetch()) {

      $result['subcat'] = FALSE;

      $result['menu'] = '<div class="icon-link">
          <a href="#">
            <i class="bx bxs-drink"></i>

            <span class="link_name">Boissons</span>

          </a>
          <i class="bx bxs-chevron-down arrow"></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="Drinks.php">Boissons</a></li>
          <li><a href="DrinksCat.php">Catégories</a></li>';
      if ($options['show_drink_sous_cat']) {
        $result['menu'] .=         '<li><a href="DrinksSubCat.php">Sous Catégories</a></li>';
      }
      $result['menu'] .=     '</ul>';

      $result['resultat'] = '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
      if ($options['show_cat_drink_photo'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_cat_drink_photo" name="show_cat_drink_photo" class="est_publie" value=' . $options['show_cat_drink_photo'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_cat_drink_photo" name="show_cat_drink_photo" class="est_publie" value=' . $options['show_cat_drink_photo'] . '></td>';
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
      if ($options['show_cat_drink_description'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_cat_drink_description" name="show_cat_drink_description" class="est_publie" value=' . $options['show_cat_drink_description'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_cat_drink_description" name="show_cat_drink_description" class="est_publie" value=' . $options['show_cat_drink_description'] . '></td>';
      }
      $result['resultat'] .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
      //card3
      $result['resultat'] .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="fa-solid fa-diagram-predecessor"></i>
              <div>
                <h5>Activer sous-catégories</h5>';
      if ($options['show_drink_sous_cat'] == 1) {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_drink_sous_cat" name="show_drink_sous_cat" class="est_publie" value=' . $options['show_drink_sous_cat'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td> <input type="checkbox" id="show_drink_sous_cat" name="show_drink_sous_cat" class="est_publie" value=' . $options['show_drink_sous_cat'] . '></td>';
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
