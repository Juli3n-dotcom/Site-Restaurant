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

// Count du nombre de team member dans la BDD
function countTeam(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM team');
  $team = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbteam = count($team);

  return $nbteam;
}
