<?php
require_once __DIR__ . '/../../config/Init.php';

/* #############################################################################

Modale d'Update d'un allergene a partir Allergenes.php en Ajax

############################################################################# */

if (isset($_POST['aller_id'])) {

  $result = '';
  $id = $_POST['aller_id'];
  $query = $pdo->query("SELECT * FROM allergenes WHERE id = '$id'");

  $result .= '<form action="" method="post" id="update_aller" enctype="multipart/form-data">';

  while ($row = $query->fetch()) {

    $result .= '<input type="hidden" name="update_id" id="update_id" value="' . $row['id'] . '">';

    $result .= '<div class="mb-3 mt-4">';
    $result .= '<label for="update_titre">Titre : </label>';
    $result .= '<input type="text"  class="form-control" name="update_titre" id="update_titre" value="' . $row['titre'] . '">';
    $result .= '</div>';

    $result .= '<div class="mb-3 mt-4">';
    $result .= '<label for="add_description" class="form_label">Description : </label>';
    $result .=  '<textarea 
                      name="update_description" 
                      id="update_description" 
                      class="form-control" 
                      placeholder="indiquez la description de l\'Allergene">' . $row['description'] . '</textarea>';
    $result .= '</div>';

    $result .= '<div class="mb-3 mt-4">';
    $result .= '<label for="add_exclusions" class="form_label">exclusions : </label>';
    $result .=  '<textarea 
                      name="update_exclusions" 
                      id="update_exclusions" 
                      class="form-control" 
                      placeholder="indiquez les exclusions de l\'Allergene">' . $row['exclusions'] . '</textarea>';
    $result .= '</div>';

    $result .= '</div>';
  }



  $result .= '<div class="modal-footer" id="footer-update">';
  $result .= '<button type="submit" name="update_aller" id="UpdateAllerBtn" class="updateBtn">Modifier</button>';
  $result .= '</div>';
  $result .= '<div class="modal-footer load-update hide" id="load-update">
                <img src="Assets/Images/loader.gif" alt="Loader">
                <p>Chargement ...</p>
            </div>';


  $result .= '</form>';
  echo $result;
}
