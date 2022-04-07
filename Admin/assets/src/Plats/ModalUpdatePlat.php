<?php
require_once __DIR__ . '/../../config/Init.php';
require_once __DIR__ . '/../../Functions/PlatsFunctions.php';


use App\General;
use App\Plats;

$ua = getBrowser();
/* #############################################################################

Modale d'Update d'un plat a partir Plats.php en Ajax

############################################################################# */

if (isset($_POST['plat_id'])) {

  $result = '';
  $id = $_POST['plat_id'];
  $query = $pdo->query("SELECT * FROM plats WHERE id = '$id'");

  $result .= '<form action="" method="post" id="update_plat" enctype="multipart/form-data">';

  while ($row = $query->fetch()) {

    $result .= '<input type="hidden" name="update_id" id="update_id" value="' . $row['id'] . '">';
    if ($options['show_plat_photo']) {
      $result .= '<input type="hidden" name="update_img" id="update_img" value="' . $row['photo_id'] . '">';

      $result .= Plats::getPlatPhoto($pdo, $row['photo_id'], $ua['name']);

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
    $result .=         ' <option value="' . $row['cat_id'] . '" selected>' . Plats::getCat($pdo, $row['cat_id']) . '</option>';
    foreach (getCat($pdo, $row['cat_id']) as $cat) {
      $result .= '<option value="' . $cat['id'] . '">' . $cat['titre'] . '</option>';
    }
    $result .=       ' </select>';
    $result .=     ' </div>';

    if ($options['show_sous_cat']) {
      $result .= '<div class="mb-3 mt-4">';
      $result .=     '<label for="update_souscat" class="form_label">Changer de sous catégorie : </label>';
      $result .=       '<select class="form-select" name="update_souscat" id="update_souscat" aria-label="">';
      if ($row['souscat_id'] != NULL) {
        $result .=         ' <option value="' . $row['souscat_id'] . '" selected>' . Plats::getSousCat($pdo, $row['souscat_id']) . '</option>';
      } else {
        $result .=         ' <option value=" " selected></option>';
      }
      foreach (getSubCat($pdo, $row['cat_id']) as $subCat) {
        $result .= '<option value="' . $subCat['id'] . '">' . $subCat['titre'] . '</option>';
      }
      $result .=       ' </select>';
      $result .=     ' </div>';
    }


    $result .=    ' <div class=" mb-3 mt-4 input-block">';
    $result .=        '<label for="update_est_epice">S\'agit il d\'un plat épicé: </label>';
    $result .=         '<div>';
    if ($row['est_epice']) {
      $result .= '<input type="checkbox" id="update_est_epice" name="update_est_epice" value="' . $row['est_epice'] . '" checked>';
    } else {
      $result .= '<input type="checkbox" id="update_est_epice" name="update_est_epice" value=' . $row['est_epice'] . '>';
    }
    $result .=          '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
    $result .=          '<div class="input_help">';
    $result .=             '<p>';
    $result .=             ' Cocher cette case si le plat est épicé.';
    $result .=            '</p>';
    $result .=          ' </div>';
    $result .=         '</div>';
    $result .=      '</div>';

    $result .=  '<div class="mb-3 mt-4 update_epicelevelcontainer ' . ($row['est_epice'] ? 'show' : '') . '" id="update_epicelevelcontainer">';
    $result .=        ' <label for="updateepicelevel">Niveau d\'épices :</label>';
    $result .=       ' <div class="form-check">';
    $result .=          '<input class="form-check-input" type="radio" name="updateepicelevel" id="updateepicelevel1" value="1" ' . ($row['epice_level'] == 1 ? 'checked' : '') . '>';
    $result .=          '<label class="form-check-label" for="updateepicelevel">  Un peu épicé ' . Plats::getNbPiment(1, $ua["name"]) . '</label>';
    $result .=       ' </div>';
    $result .=       ' <div class="form-check">';
    $result .=          ' <input class="form-check-input" type="radio" name="updateepicelevel" id="updateepicelevel2" value="2" ' . ($row['epice_level'] == 2 ? 'checked' : '') . '>';
    $result .=           '<label class="form-check-label" for="updateepicelevel"> Moyennement épicé ' . Plats::getNbPiment(2, $ua["name"]) . '</label>';
    $result .=         '</div>';
    $result .=         '<div class="form-check">';
    $result .=           '<input class="form-check-input" type="radio" name="updateepicelevel" id="updateepicelevel3" value="3" ' . ($row['epice_level'] == 3 ? 'checked' : '') . '>';
    $result .=           '<label class="form-check-label" for="updateepicelevel">Trés épicé ' . Plats::getNbPiment(3, $ua["name"]) . '</label>';
    $result .=         '</div>';
    $result .=       '</div>';

    $result .= '<div class=" mb-3 mt-4 input-block">';
    $result .=        ' <label for="update_haveallergene">Votre plat contient t\'il des allergénes ? : </label>';
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


    $result .=    ' <div class=" mb-3 mt-4 input-block">';
    $result .=        '<label for="update_vege">S\'agit il d\'un plat végétarien: </label>';
    $result .=         '<div>';
    if ($row['est_vege']) {
      $result .= '<input type="checkbox" id="update_vege" name="update_vege" value="' . $row['est_vege'] . '" checked>';
    } else {
      $result .= '<input type="checkbox" id="update_vege" name="update_vege" value=' . $row['est_vege'] . '>';
    }
    $result .=          '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
    $result .=          '<div class="input_help">';
    $result .=             '<p>';
    $result .=             '  Cocher cette case si le plat est végétarien.';
    $result .=            '</p>';
    $result .=          ' </div>';
    $result .=         '</div>';
    $result .=      '</div>';

    $result .=    ' <div class=" mb-3 mt-4 input-block">';
    $result .=        '<label for="update_vegan">S\'agit il d\'un plat végan: </label>';
    $result .=         '<div>';
    if ($row['est_vegan']) {
      $result .= '<input type="checkbox" id="update_vegan" name="update_vegan" value="' . $row['est_vegan'] . '" checked>';
    } else {
      $result .= '<input type="checkbox" id="update_vegan" name="update_vegan" value=' . $row['est_vegan'] . '>';
    }
    $result .=          '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
    $result .=          '<div class="input_help">';
    $result .=             '<p>';
    $result .=             '  Cocher cette case si le plat est végan.';
    $result .=            '</p>';
    $result .=          ' </div>';
    $result .=         '</div>';
    $result .=      '</div>';

    $result .=    ' <div class=" mb-3 mt-4 input-block">';
    $result .=        '<label for="update_halal">S\'agit il d\'un plat halal: </label>';
    $result .=         '<div>';
    if ($row['est_halal']) {
      $result .= '<input type="checkbox" id="update_halal" name="update_halal" value="' . $row['est_halal'] . '" checked>';
    } else {
      $result .= '<input type="checkbox" id="update_halal" name="update_halal" value=' . $row['est_halal'] . '>';
    }
    $result .=          '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
    $result .=          '<div class="input_help">';
    $result .=             '<p>';
    $result .=             ' Cocher cette case si le plat est halal.';
    $result .=            '</p>';
    $result .=          ' </div>';
    $result .=         '</div>';
    $result .=      '</div>';

    $result .=    ' <div class=" mb-3 mt-4 input-block">';
    $result .=        '<label for="update_casher">S\'agit il d\'un plat casher: </label>';
    $result .=         '<div>';
    if ($row['est_casher']) {
      $result .= '<input type="checkbox" id="update_casher" name="update_casher" value="' . $row['est_casher'] . '" checked>';
    } else {
      $result .= '<input type="checkbox" id="update_casher" name="update_casher" value=' . $row['est_casher'] . '>';
    }
    $result .=          '<span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>';
    $result .=          '<div class="input_help">';
    $result .=             '<p>';
    $result .=             '  Cocher cette case si le plat est cacher.';
    $result .=            '</p>';
    $result .=          ' </div>';
    $result .=         '</div>';
    $result .=      '</div>';

    if ($options['show_plat_en_avant']) {
      $result .=    ' <div class=" mb-3 mt-4 input-block">';
      $result .=        '<label for="update_en_avant">Mettre en avant le plat: </label>';
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
    $result .=        '<label for="update_publie">Afficher le plat: </label>';
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
    $result .=        '<label for="update_nouveau">Afficher <span class="new"> nouveau</span> sur le plat:  </label>';
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
  $result .= '<button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier</button>';
  $result .= '</div>';
  $result .= '<div class="modal-footer load-update hide" id="load-update">
                <img src="Assets/Images/loader.gif" alt="Loader">
                <p>Chargement ...</p>
            </div>';


  $result .= '</form>';
  echo json_encode($result);
}
