<?php

namespace App;

class GeneralClass

{
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
