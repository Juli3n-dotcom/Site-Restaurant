<?php

namespace App;

class SousCategories

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

  public static function getParent($pdo, $cat_id)
  {
    $data = $pdo->query("SELECT * from plats_categories WHERE id = '$cat_id'");
    $parent = $data->fetch($pdo::FETCH_ASSOC);
    return $parent['titre'];
  }

  public static function getCatPhoto($pdo, $photo_id, $ua)
  {

    if ($photo_id != NULL) {
      $data = $pdo->query("SELECT * from plats_photo WHERE id_photo = '$photo_id'");
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

  public static function getNbPlats($pdo, $id)
  {
    $data = $pdo->query("SELECT count(*) as nb from plats WHERE souscat_id = '$id'");
    $data = $data->fetch();

    $count = $data['nb'];
    return $count;
  }
}
