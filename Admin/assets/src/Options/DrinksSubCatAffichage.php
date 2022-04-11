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
if ($options['show_sub_cat_drink_photo'] == 1) {

  $output .= '<td> <input type="checkbox" id="show_sub_cat_drink_photo" name="show_sub_cat_drink_photo" class="est_publie" value=' . $options['show_sub_cat_drink_photo'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="show_sub_cat_drink_photo" name="show_sub_cat_drink_photo" class="est_publie" value=' . $options['show_sub_cat_drink_photo'] . '></td>';
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
if ($options['show_sub_cat_drink_description'] == 1) {

  $output .= '<td> <input type="checkbox" id="show_sub_cat_drink_description" name="show_sub_cat_drink_description" class="est_publie" value=' . $options['show_sub_cat_drink_description'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="show_sub_cat_drink_description" name="show_sub_cat_drink_description" class="est_publie" value=' . $options['show_sub_cat_drink_description'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';



echo $output;
