<?php

function getMemberBy(PDO $pdo, string $colonne, $valeur): ?array
{

  $req = $pdo->prepare(sprintf(
    'SELECT *
       FROM team
       WHERE %s = :valeur',
    $colonne
  ));

  $req->bindParam(':valeur', $valeur);
  $req->execute();

  $utilisateur = $req->fetch(PDO::FETCH_ASSOC);
  return $utilisateur ?: null;
}
