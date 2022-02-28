<?php

namespace Sign;


class Signature
{

  public static function getSignature()
  {

    $output = '<div style="position: absolute; bottom: 0;z-index:1; left:50%; display: flex; justify-content: center;  font-size:10px;">
                <p class="sign__txt"> Site développé par  </p>
                <a class="sign__link" href="https://webupdesign.fr/" >
                  <img class="sign__img" style="max-width: 50px;"src="https://webupdesign.fr/wp-content/uploads/2021/06/logo_transparent_background-2-e1623922869188.png">
                </a>
              </div>';

    echo $output;
  }
}

//background-color:#f1f5f9;