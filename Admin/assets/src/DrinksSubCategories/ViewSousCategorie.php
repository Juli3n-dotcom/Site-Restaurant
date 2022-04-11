<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksSubCategoriesFunctions.php';


$ua = getBrowser();

use App\General;
use App\DrinksSubCategories;
/* #############################################################################

vue d'une categorie a partir DrinksSub.php en Ajax

############################################################################# */

if (isset($_POST['cat_id'])) {

  $result = '';
  $id = $_POST['cat_id'];

  $query = $pdo->query("SELECT * FROM boissons_sous_categories WHERE id = '$id'");

  $result .= '<div class="list_container">';



  while ($row = $query->fetch()) {

    $date = str_replace('/', '-', $row['date_enregistrement']);

    if ($options['show_sub_cat_drink_photo']) {
      $result .= '<div class="img-cat-container">' . DrinksSubCategories::getCatPhoto($pdo, $row['photo_id'], $ua['name']) . '</div>';
    }
    $result .= '<ul>';

    $result .= '<li>
                    <h6>ID : </h6>
                    <p>' . $row['id'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Créer par : </h6>
                    <p>' . General::getMembre($pdo, $row['author_id']) . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Titre : </h6>
                    <p>' . $row['titre'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Catégorie Parente : </h6>
                    <p>' . DrinksSubCategories::getParent($pdo, $row['cat_id'])  . '</p>
                  </li>';
    if ($options['show_cat_drink_description']) {
      $result .= '<li>
                    <h6>Description : </h6>
                    <p class="description">' . $row['description'] . '</p>
                  </li>';
    }
    $result .= '<li>
                    <h6>Publié : </h6>';
    $result .= ($row['est_publie'] == 1) ? '<p>OUI</p>' : '<p>NON</p>';
    $result .= '</li>';
    $result .= '<li>
                    <h6>Créee : </h6>
                    <p>' . date('d-m-Y', strtotime($date)) . '</p>
                  </li>';
  }

  $result .= '</ul>';

  $result .= '</div>';


  echo $result;
}
