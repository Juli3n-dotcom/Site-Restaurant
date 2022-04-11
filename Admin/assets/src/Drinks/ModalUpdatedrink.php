<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksFunctions.php';

use App\Drinks;

$ua = getBrowser();
/* #############################################################################

Modale d'Update d'une boisson a partir Drinks.php en Ajax

############################################################################# */

if (isset($_POST['drink_id'])) {

  $result = '';
  $id = $_POST['drink_id'];
  $query = $pdo->query("SELECT * FROM boissons WHERE id = '$id'");

  $result .= '<form action="" method="post" id="update_drink" enctype="multipart/form-data">';

  while ($row = $query->fetch()) {

    $result .= '<input type="hidden" name="update_id" id="update_id" value="' . $row['id'] . '">';
    if ($options['show_drink_photo']) {
      $result .= '<input type="hidden" name="update_img" id="update_img" value="' . $row['photo_id'] . '">';

      $result .= Drinks::getDrinksPhoto($pdo, $row['photo_id'], $ua['name']);

      $result .= '<span class="hiddenFileInput">';
      $result .= '<input type="file" name="new_img" id="new_img" title="Changez image du plat">';
      $result .= '</span>';
    }
    $result .= '<div class="mb-3 mt-4">';
    $result .= '<label for="update_titre">Titre : </label>';
    $result .= '<input type="text"  class="form-control" name="update_titre" id="update_titre" value="' . $row['titre'] . '">';
    $result .= '</div>';

    $result .= '<div class="mb-3 mt-4">';
    $result .= '<label for="update_prix">Prix : </label>';
    $result .= '<input type="text"  class="form-control" name="update_prix" id="update_prix" value="' . $row['prix'] . '">';
    $result .= '</div>';

    $result .= '<div class="mb-3 mt-4">';
    $result .= '<label for="update_description" class="form_label">Description : </label>';
    $result .=  '<textarea 
                      name="update_description" 
                      id="update_description" 
                      class="form-control" 
                      placeholder="indiquez la description du plat">' . $row['description'] . '</textarea>';
    $result .= '</div>';


    $result .= '<div class="mb-3 mt-4">';
    $result .=     '<label for="update_cat" class="form_label">Changer de catégorie : </label>';
    $result .=       '<select class="form-select" name="update_cat" id="update_cat" aria-label="">';
    $result .=         ' <option value="' . $row['cat_id'] . '" selected>' . Drinks::getCat($pdo, $row['cat_id']) . '</option>';
    foreach (getCat($pdo, $row['cat_id']) as $cat) {
      $result .= '<option value="' . $cat['id'] . '">' . $cat['titre'] . '</option>';
    }
    $result .=       ' </select>';
    $result .=     ' </div>';

    if ($options['show_drink_sous_cat']) {
      $result .= '<div class="mb-3 mt-4">';
      $result .=     '<label for="update_souscat" class="form_label">Changer de sous catégorie : </label>';
      $result .=       '<select class="form-select" name="update_souscat" id="update_souscat" aria-label="">';
      if ($row['souscat_id'] != NULL) {
        $result .=         ' <option value="' . $row['souscat_id'] . '" selected>' . Drinks::getSousCat($pdo, $row['souscat_id']) . '</option>';
      } else {
        $result .=         ' <option value=" " selected></option>';
      }
      foreach (getSubCat($pdo, $row['cat_id']) as $subCat) {
        $result .= '<option value="' . $subCat['id'] . '">' . $subCat['titre'] . '</option>';
      }
      $result .=       ' </select>';
      $result .=     ' </div>';
    }

    $result .= '<div class=" mb-3 mt-4 input-block">';
    $result .=        ' <label for="update_haveallergene">Votre boisson contient t\'elle des allergénes ? : </label>';
    $result .=        ' <div>';
    if ($row['have_allergenes']) {
      $result .=           '<input type="checkbox" id="update_haveallergene" name="update_haveallergene" checked>';
    } else {
      $result .=           '<input type="checkbox" id="update_haveallergene" name="update_haveallergene">';
    }

    $result .=           '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
    $result .=           '<div class="input_help">';
    $result .=            ' <p>';
    $result .=               'Vous permet de selectionner les allergénes du plat';
    $result .=             '</p>';
    $result .=           '</div>';
    $result .=        ' </div>';
    $result .=       '</div>';

    // récupération des allergenes déjà présent dans le plat
    $allergenesIdArray = getAllergeneCheck($pdo, $id);


    $result .= '<div class="mb-3 mt-4 update_allergenecontainer ' . ($row['have_allergenes'] ? 'show' : '') . '" id="update_allergenecontainer">';
    foreach (getAllergenes($pdo) as $allergene) {

      $result .=           ' <div class="form-check allergene-element">';

      if (in_array($allergene['id'], $allergenesIdArray)) {
        $result .=           '<input type="checkbox" value="' . $allergene["id"] . '" name="update_allergenes[]" id="update_allergenes" checked>';
      } else {
        $result .=           '<input type="checkbox" value="' . $allergene["id"] . '" name="update_allergenes[]" id="update_allergenes">';
      }

      $result .=           '<label class="form-check-label" for="update_allergenes">' . $allergene['titre'] . '</label>';
      $result .=           '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
      $result .=            '<div class="input_help">';
      $result .=             ' <div>';
      $result .=                '<h6>Description :</h6>';
      $result .=               '<p>';
      $result .=                  'Description :' . $allergene['description'] . '</p>';
      $result .=             '</div>';
      if ($allergene['exclusions'] != null) {
        $result .=                  '<div>';
        $result .=              '<h6>Exclusions : </h6>';
        $result .=              '<p>' . $allergene['exclusions'] . '</p>';
        $result .=           '</div>';
      }
      $result .=       '</div>';
      $result .=      '</div>';
    }
    $result .=    '</div>';



    if ($options['show_drinks_en_avant']) {
      $result .=    ' <div class=" mb-3 mt-4 input-block">';
      $result .=        '<label for="update_en_avant">Mettre en avant la boisson: </label>';
      $result .=         '<div>';
      if ($row['est_en_avant']) {
        $result .= '<input type="checkbox" id="update_en_avant" name="update_en_avant" value="' . $row['est_en_avant'] . '" checked>';
      } else {
        $result .= '<input type="checkbox" id="update_en_avant" name="update_en_avant" value=' . $row['est_en_avant'] . '>';
      }
      $result .=          '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
      $result .=          '<div class="input_help">';
      $result .=             '<p>';
      $result .=             '  Vous permet de promouvoir le plat.';
      $result .=            '</p>';
      $result .=          ' </div>';
      $result .=         '</div>';
      $result .=      '</div>';
    }

    $result .=    ' <div class=" mb-3 mt-4 input-block">';
    $result .=        '<label for="update_publie">Afficher la boisson: </label>';
    $result .=         '<div>';
    if ($row['est_publie']) {
      $result .= '<input type="checkbox" id="update_publie" name="update_publie" value="' . $row['est_publie'] . '" checked>';
    } else {
      $result .= '<input type="checkbox" id="update_publie" name="update_publie" value=' . $row['est_publie'] . '>';
    }
    $result .=          '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
    $result .=          '<div class="input_help">';
    $result .=             '<p>';
    $result .=             '  Cocher cette case pour ajouter pur l\'ajouter au menu.';
    $result .=            '</p>';
    $result .=          ' </div>';
    $result .=         '</div>';
    $result .=      '</div>';

    $result .=    ' <div class=" mb-3 mt-4 input-block">';
    $result .=        '<label for="update_nouveau">Afficher <span class="new"> nouveau</span> sur la boisson:  </label>';
    $result .=         '<div>';
    if ($row['est_nouveau']) {
      $result .= '<input type="checkbox" id="update_nouveau" name="update_nouveau" value="' . $row['est_nouveau'] . '" checked>';
    } else {
      $result .= '<input type="checkbox" id="update_nouveau" name="update_nouveau" value=' . $row['est_nouveau'] . '>';
    }
    $result .=          '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
    $result .=          '<div class="input_help">';
    $result .=             '<p>';
    $result .=             '  vous donner la possibilité d\'afficher le logo <span class="new"> nouveau</span>
                  sur le plat.';
    $result .=            '</p>';
    $result .=          ' </div>';
    $result .=         '</div>';
    $result .=      '</div>';

    $result .= '</div>';
  }



  $result .= '<div class="modal-footer" id="footer-update">';
  $result .= '<button type="submit" name="update_drinks" id="UpdatePlatBtn" class="updateBtn">Modifier</button>';
  $result .= '</div>';
  $result .= '<div class="modal-footer load-update hide" id="load-update">
                <img src="Assets/Images/loader.gif" alt="Loader">
                <p>Chargement ...</p>
            </div>';


  $result .= '</form>';
  echo json_encode($result);
}
