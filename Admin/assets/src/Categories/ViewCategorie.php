<?php
require_once __DIR__ . '/../../Config/Init.php';
require_once __DIR__ . '/../../Functions/CategoriesFunctions.php';


$ua = getBrowser();

use App\Categories;
/* #############################################################################

vue d'une categorie a partir categorie.php en Ajax

############################################################################# */

if (isset($_POST['cat_id'])) {

  $result = '';
  $id = $_POST['cat_id'];

  $query = $pdo->query("SELECT * FROM plats_categories WHERE id = '$id'");

  $result .= '<div class="list_container">';



  while ($row = $query->fetch()) {

    $date = str_replace('/', '-', $row['date_enregistrement']);

    if ($options['show_cat_photo']) {
      $result .= '<div class="img-cat-container">' . Categories::getCatPhoto($pdo, $row['photo_id'], $ua['name']) . '</div>';
    }
    $result .= '<ul>';

    $result .= '<li>
                    <h6>ID : </h6>
                    <p>' . $row['id'] . '</p>
                  </li>';
    $result .= '<li>
                    <h6>Titre : </h6>
                    <p>' . $row['titre'] . '</p>
                  </li>';
    if ($options['show_cat_description']) {
      $result .= '<li>
                    <h6>Description : </h6>
                    <p class="description">' . $row['description'] . '</p>
                  </li>';
    }
    if ($options['show_cat_pieces']) {

      $result .= '<li>
                    <h6># Pieces : </h6>
                    <p>' . $row['pieces'] . '</p>
                  </li>';
    }

    if ($options['show_plat_stats']) {

      $result .= '<li>
                    <h6><i class="fas fa-chart-area"></i> Statistique: </h6>
                    <p>0 clicks</p>
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
