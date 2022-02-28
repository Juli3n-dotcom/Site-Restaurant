<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/CategoriesFunctions.php';

use App\Categories;

$ua = getBrowser();


/* #############################################################################

Modale d'Update d'une categories a partir categorie.php en Ajax

############################################################################# */

if (isset($_POST['cat_id'])) {

  $result = '';
  $id = $_POST['cat_id'];
  $query = $pdo->query("SELECT * FROM plats_categories WHERE id = '$id'");

  $result .= '<form action="" method="post" id="update_cat" enctype="multipart/form-data">';

  while ($row = $query->fetch()) {

    $result .= '<input type="hidden" name="update_id" id="update_id" value="' . $row['id'] . '">';
    if ($options['show_cat_photo']) {
      $result .= '<input type="hidden" name="update_img" id="update_img" value="' . $row['photo_id'] . '">';

      $result .= Categories::getCatPhoto($pdo, $row['photo_id'], $ua['name']);

      $result .= '<span class="hiddenFileInput">';
      $result .= '<input type="file" name="new_img" id="new_img" title="Changez image de la categorie">';
      $result .= '</span>';
    }


    $result .= '<div class="mb-3 mt-4">';
    $result .= '<label for="update_titre">Titre : </label>';
    $result .= '<input type="text"  class="form-control" name="update_titre" id="update_titre" value="' . $row['titre'] . '">';
    $result .= '</div>';

    if ($options['show_cat_description']) {
      $result .= '<div class="mb-3 mt-4">';
      $result .= '<label for="add_description" class="form_label">Description : </label>';
      $result .=  '<textarea 
                      name="update_description" 
                      id="update_description" 
                      class="form-control" 
                      placeholder="indiquez la description de la catégorie">' . $row['description'] . '</textarea>';
      $result .= '</div>';
    }
    if ($options['show_cat_pieces']) {
      $result .= '<div class="mb-3 mt-4">';
      $result .= ' <label for="update_pieces" class="form_label">Nombre de pieces : </label>';
      $result .= ' <input type="number" name="update_pieces" id="update_pieces" class="form-control" value="' . $row['pieces'] . '">';
    }
    $result .=  '</div>';

    $result .= '<div class="mb-3 mt-4">';
    $result .= '<label for="est publié" class="form_label">Afficher : </label>';
    if ($row['est_publie']) {
      $result .= '<td> <input type="checkbox" id="update_publie" name="update_publie" class="update_publie" value=' . $row['est_publie'] . ' checked></td>';
    } else {
      $result .= '<td> <input type="checkbox" id="update_publie" name="update_publie" class="update_publie" value=' . $row['est_publie'] . '></td>';
    }
    $result .= '</div>';
  }



  $result .= '<div class="modal-footer" id="footer-update">';
  $result .= '<button type="submit" name="update_cat" id="UpdateCatBtn" class="updateBtn">Modifier</button>';
  $result .= '</div>';
  $result .= '<div class="modal-footer load-update hide" id="load-update">
                <img src="Assets/Images/loader.gif" alt="Loader">
                <p>Chargement ...</p>
            </div>';


  $result .= '</form>';
  echo $result;
}
