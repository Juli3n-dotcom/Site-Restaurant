<?php

namespace App;

class TeamStatut
{

  public static function getStatutBy($pdo, string $colonne, string $valeur): ?array
  {
    $req = $pdo->prepare(sprintf(
      'SELECT *
       FROM team_statut
       WHERE %s = :valeur',
      $colonne
    ));

    $req->bindParam(':valeur', $valeur);
    $req->execute();

    $statut = $req->fetch();
    return $statut ?: null;
  }
}
