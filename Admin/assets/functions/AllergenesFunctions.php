<?php

// Count du nombre de catégorie dans la BDD
function countAller(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM allergenes');
  $cat = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbCat = count($cat);

  return $nbCat;
}

//vérification sur category existe déja
function getAllergeneBy(PDO $pdo, string $colonne, $valeur): ?array
{
  $req = $pdo->prepare(sprintf(
    'SELECT *
       FROM allergenes
       WHERE %s = :valeur',
    $colonne
  ));

  $req->bindParam(':valeur', $valeur);
  $req->execute();

  $cat = $req->fetch(PDO::FETCH_ASSOC);
  return $cat ?: null;
}
