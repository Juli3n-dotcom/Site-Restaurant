<?php

require_once __DIR__ . '/../../config/Init.php';

$output = '';

$data = $pdo->query("SELECT * FROM Options");
$options = $data->fetch(PDO::FETCH_ASSOC);

$output .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
if ($options['show_cat_drink_photo'] == 1) {

  $output .= '<td> <input type="checkbox" id="show_cat_drink_photo" name="show_cat_drink_photo" class="est_publie" value=' . $options['show_cat_drink_photo'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="show_cat_drink_photo" name="show_cat_drink_photo" class="est_publie" value=' . $options['show_cat_drink_photo'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
// card2
$output .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="far fa-keyboard"></i>
              <div>
                <h5>Afficher Description</h5>';
if ($options['show_cat_drink_description'] == 1) {

  $output .= '<td> <input type="checkbox" id="show_cat_drink_description" name="show_cat_drink_description" class="est_publie" value=' . $options['show_cat_drink_description'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="show_cat_drink_description" name="show_cat_drink_description" class="est_publie" value=' . $options['show_cat_drink_description'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';

//card3
$output .= '<div class="card__single card_visites">
              <div class="card__body">
                <i class="fa-solid fa-diagram-predecessor"></i>
              <div>
                <h5>Activer sous-catégories</h5>';
if ($options['show_drink_sous_cat'] == 1) {

  $output .= '<td> <input type="checkbox" id="show_drink_sous_cat" name="show_drink_sous_cat" class="est_publie" value=' . $options['show_drink_sous_cat'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="show_drink_sous_cat" name="show_drink_sous_cat" class="est_publie" value=' . $options['show_drink_sous_cat'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';

echo $output;
