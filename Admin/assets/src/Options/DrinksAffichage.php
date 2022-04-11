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
if ($options['show_drink_photo'] == 1) {

  $output .= '<td> <input type="checkbox" id="drinks_photo_est_publie" name="drinks_photo_est_publie" class="est_publie" value=' . $options['show_drink_photo'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="drinks_photo_est_publie" name="drinks_photo_est_publie" class="est_publie" value=' . $options['show_drink_photo'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
// card2
$output .= '<div class="card__single">
              <div class="card__body">
                <i class="fas fa-chart-area"></i>
              <div>
                <h5>Afficher Stats</h5>';
if ($options['show_drinks_stats'] == 1) {

  $output .= '<td> <input type="checkbox" id="drinks_stats_est_publie" name="drinks_stats_est_publie" class="est_publie" value=' . $options['show_drinks_stats'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="drinks_stats_est_publie" name="drinks_stats_est_publie" class="est_publie" value=' . $options['show_drinks_stats'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';
//card3
$output .= '<div class="card__single">
              <div class="card__body">';
if ($options['show_drinks_en_avant']) {
  $output .= ' <i class="fa-solid fa-star"></i>';
} else {
  $output .= ' <i class="fa-regular fa-star"></i>';
}
$output .= '<div>
                <h5>Afficher Mise en avant</h5>';
if ($options['show_drinks_en_avant'] == 1) {

  $output .= '<td> <input type="checkbox" id="drinks_en_avant_est_publie" name="drinks_en_avant_est_publie" class="est_publie" value=' . $options['show_drinks_en_avant'] . ' checked></td>';
} else {

  $output .= '<td> <input type="checkbox" id="drinks_en_avant_est_publie" name="drinks_en_avant_est_publie" class="est_publie" value=' . $options['show_drinks_en_avant'] . '></td>';
}
$output .= '</div>
            </div>
            <div class="card__footer">
            </div>
          </div>';

echo $output;
