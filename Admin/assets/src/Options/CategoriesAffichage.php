<?php

require_once __DIR__ . '/../../config/Init.php';

$output = '';

$data = $pdo->query("SELECT * FROM Options");
$options = $data->fetch(PDO::FETCH_ASSOC);

$output .= '<div class="card__single">
              <div class="card__body">
                <i class="far fa-image"></i>
              <div>
                <h5>Afficher Photo</h5>';
if ($options['show_cat_photo'] == 1) {

  $output .= '<td> <input type="checkbox" id="cat_photo_est_publie" name="cat_photo_est_publie" class="est_publie" value=' . $options['show_cat_photo'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="cat_photo_est_publie" name="cat_photo_est_publie" class="est_publie" value=' . $options['show_cat_photo'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
// card2
$output .= '<div class="card__single">
              <div class="card__body">
                <i class="far fa-keyboard"></i>
              <div>
                <h5>Afficher Description</h5>';
if ($options['show_cat_description'] == 1) {

  $output .= '<td> <input type="checkbox" id="cat_desc_est_publie" name="cat_desc_est_publie" class="est_publie" value=' . $options['show_cat_description'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="cat_desc_est_publie" name="cat_desc_est_publie" class="est_publie" value=' . $options['show_cat_description'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
//card3
$output .= '<div class="card__single">
              <div class="card__body">
                <i class="fas fa-hashtag"></i>
              <div>
                <h5>Afficher Nombre de pièces</h5>';
if ($options['show_cat_pieces'] == 1) {

  $output .= '<td> <input type="checkbox" id="cat_p_est_publie" name="cat_p_est_publie" class="est_publie" value=' . $options['show_cat_pieces'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="cat_p_est_publie" name="cat_p_est_publie" class="est_publie" value=' . $options['show_cat_pieces'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';

echo $output;
