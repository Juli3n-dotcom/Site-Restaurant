<?php

namespace App;

class TeamFunctions
{

  // public static function getMemberBy(PDO $pdo, string $colonne, $valeur): ?array
  // {
  //   $req = $pdo->prepare(sprintf(
  //     'SELECT *
  //      FROM team
  //      WHERE %s = :valeur',
  //     $colonne
  //   ));

  //   $req->bindParam(':valeur', $valeur);
  //   $req->execute();

  //   $utilisateur = $req->fetch(PDO::FETCH_ASSOC);
  //   return $utilisateur ?: null;
  // }


  public function getProfil($pdo, int $photo_id, int $civilite)
  {

    if ($photo_id == NULL) {
      if ($civilite == 0) {
        echo "<div class='img-profil' style='background-image: url(assets/img/male.svg)'></div>";
      } elseif ($civilite == 1) {
        echo "<div class='img-profil' style='background-image: url(assets/img/female.svg)'></div>";
      } else {
        echo "<div class='img-profil' style='background-image: url(assets/img/profil.svg)'></div>";
      }
    } else {
      $data = $pdo->query("SELECT * from team_photo WHERE id_photo = '$photo_id'");
      $photo = $data->fetch($pdo::FETCH_ASSOC);

      echo "<div class='img-profil' style='background-image: url(assets/uploads/" . $photo . " )'></div>";
    }
  }
}
