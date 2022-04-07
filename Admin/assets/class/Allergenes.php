<?php

namespace App;

class Allergenes
{
  public static function getNbPlats($pdo, $id)
  {
    $data = $pdo->query("SELECT count(*) as nb from plats_allergenes_liaison WHERE allergene_id = '$id'");
    $data = $data->fetch();

    $count = $data['nb'];
    return $count;
  }
}
