<?php

namespace App;

class Categories

{

  public static function getImage($pdo, $photo_id, $ua)
  {

    if ($photo_id != NULL) {
      $data = $pdo->query("SELECT * from plats_photo_categories WHERE id_photo = '$photo_id'");
      $photo = $data->fetch($pdo::FETCH_ASSOC);

      if ($ua == 'Safari') {
        return "<div class='img-profil' style='background-image: url(../Global/Uploads/" . $photo['img__jpeg'] . " )'></div>";
      } else {
        return "<div class='img-profil' style='background-image: url(../Global/Uploads/" . $photo['img__webp'] . " )'></div>";
      }
    } else {
      return "<div class='img-profil' style='background-image: url(Assets/Images/noimg.png)'></div>";
    }
  }

  public static function getCatPhoto($pdo, $photo_id, $ua)
  {

    if ($photo_id != NULL) {
      $data = $pdo->query("SELECT * from plats_photo_categories WHERE id_photo = '$photo_id'");
      $photo = $data->fetch($pdo::FETCH_ASSOC);

      if ($ua == 'Safari') {
        return "<img class='img-cat' src='../Global/Uploads/" . $photo['img__jpeg'] . "' alt='image catégorie' with='100px'>";
      } else {
        return "<img class='img-cat' src='../Global/Uploads/" . $photo['img__webp'] . "'alt='image catégorie' with='100px'>";
      }
    } else {
      return "<img class='img-cat' src='Assets/Images/noimg.png' alt='image catégorie' with='200px'>";
    }
  }
}
