<?php

namespace App;

class Plats

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
    $data = $pdo->query("SELECT * from plats_categories WHERE id = '$cat_id'");
    $parent = $data->fetch($pdo::FETCH_ASSOC);
    return $parent['titre'];
  }

  public static function getSousCat($pdo, $cat_id)
  {
    $data = $pdo->query("SELECT * from plats_sous_categories WHERE id = '$cat_id'");
    $parent = $data->fetch($pdo::FETCH_ASSOC);
    return $parent['titre'];
  }

  public static function getEstEnAvant($pdo, $id)
  {
    $data = $pdo->query("SELECT * from plats WHERE id = '$id'");
    $plat = $data->fetch($pdo::FETCH_ASSOC);

    if ($plat['est_en_avant']) {
      return '<input type="checkbox" id="est_en_avant" name="est_en_avant" class="est_en_avant" value=' . $plat['est_en_avant'] . ' checked>';
    } else {
      return '<input type="checkbox" id="est_en_avant" name="est_en_avant" class="est_en_avant" value=' . $plat['est_en_avant'] . '>';
    }
  }

  public static function getPlatPhoto($pdo, $photo_id, $ua)
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

  public static function countAllergenes($pdo, $id)
  {
    $data = $pdo->query("SELECT count(*) as nb FROM plats_allergenes_liaison WHERE plat_id = '$id'");
    $count = $data->fetch($pdo::FETCH_ASSOC);
    return $count['nb'];
  }

  public static function getNbPiment($nb, $ua)
  {
    if ($ua == "Safari") {
      for ($i = 0; $i < $nb; $i++) {
        return '<span><img src="../Global/Images/piment.png"  class="piment" style="max-width: 20px;" alt="image piment" /></span>';
      }
    } else {
      for ($i = 0; $i < $nb; $i++) {
        return '<img src="../Global/Images/piment.webp"  class="piment" style="max-width: 20px;" alt="image piment" /></span>';
      }
    }
  }
}
