<?php

namespace Sign;


class Signature
{

  public static function getSignature()
  {

    $output = '<div style="position: absolute; bottom: 0;z-index:1; width: 100%; display: flex; justify-content: center;  font-size:10px;">
                <p> Site développé par  </p>
                <a href="https://webupdesign.fr/" />
                  <img style="max-width: 50px;"src="https://webupdesign.fr/wp-content/uploads/2021/06/logo_transparent_background-2-e1623922869188.png">
              </div>';

    echo $output;
  }
}

//background-color:#f1f5f9;