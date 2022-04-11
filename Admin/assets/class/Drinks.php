<?php

namespace App;

class Drinks
{
  public static function getImage($pdo, $photo_id, $ua)
  {

    if ($photo_id != NULL) {
      $data = $pdo->query("SELECT * from plats_photo WHERE id_photo = '$photo_id'");
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

  public static function getCat($pdo, $cat_id)
  {
    $data = $pdo->query("SELECT * from boissons_categories WHERE id = '$cat_id'");
    $parent = $data->fetch($pdo::FETCH_ASSOC);
    return $parent['titre'];
  }

  public static function getSousCat($pdo, $cat_id)
  {
    $data = $pdo->query("SELECT * from boissons_sous_categories WHERE id = '$cat_id'");
    $parent = $data->fetch($pdo::FETCH_ASSOC);
    return $parent['titre'];
  }

  public static function getEstEnAvant($pdo, $id)
  {
    $data = $pdo->query("SELECT * from boissons WHERE id = '$id'");
    $plat = $data->fetch($pdo::FETCH_ASSOC);

    if ($plat['est_en_avant']) {
      return '<input type="checkbox" id="est_en_avant" name="est_en_avant" class="est_en_avant" value=' . $plat['est_en_avant'] . ' checked>';
    } else {
      return '<input type="checkbox" id="est_en_avant" name="est_en_avant" class="est_en_avant" value=' . $plat['est_en_avant'] . '>';
    }
  }

  public static function getDrinksPhoto($pdo, $photo_id, $ua)
  {

    if ($photo_id != NULL) {
      $data = $pdo->query("SELECT * from plats_photo WHERE id_photo = '$photo_id'");
      $photo = $data->fetch($pdo::FETCH_ASSOC);

      if ($ua == 'Safari') {
        return "<img class='img-plat' src='../Global/Uploads/" . $photo['img__jpeg'] . "' alt='image catégorie' with='100px'>";
      } else {
        return "<img class='img-plat' src='../Global/Uploads/" . $photo['img__webp'] . "'alt='image catégorie' with='100px'>";
      }
    } else {
      return "<img class='img-plat' src='Assets/Images/noimg.png' alt='image catégorie' with='200px'>";
    }
  }
}
