<?php

namespace App;

class GeneralClass

{

  public static function getLogo($pdo, $photo_id, $ua)
  {

    if ($photo_id != NULL) {
      $data = $pdo->query("SELECT * from resto_photo WHERE id_photo = '$photo_id'");
      $photo = $data->fetch($pdo::FETCH_ASSOC);

      if ($ua == 'Safari') {
        return "<img class='img__logo' src='../Global/Uploads/" . $photo['img__jpeg'] . "' alt='logo' with='50px'>";
      } else {
        return "<img class='img__logo' src='../Global/Uploads/" . $photo['img__webp'] . "'alt='logo' with='50px'>";
      }
    } else {
      return "<img class='img__logo' src='Assets/Images/noimg.png' alt='image non disponible' with='50px'>";
    }
  }
  public static function getNbPiment($nb, $ua)
  {
    if ($ua == "Safari") {
      for ($i = 0; $i < $nb; $i++) {
        echo '<span><img src="../Global/Images/piment.png"  class="piment" style="max-width: 20px;" alt="image piment" /></span>';
      }
    } else {
      for ($i = 0; $i < $nb; $i++) {
        echo '<img src="../Global/Images/piment.webp"  class="piment" style="max-width: 20px;" alt="image piment" /></span>';
      }
    }
  }
}
