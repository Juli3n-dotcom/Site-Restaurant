<?php

//récupération des catégories
function getCat(PDO $pdo): array
{
  $req = $pdo->query(
    'SELECT *
       FROM plats_Categories ORDER BY position ASC '
  );
  $cat = $req->fetchAll(PDO::FETCH_ASSOC);
  return $cat;
}

//vérification sur category existe déja
function getCatBy(PDO $pdo, string $colonne, $valeur): ?array
{
  $req = $pdo->prepare(sprintf(
    'SELECT *
       FROM plats_Categories
       WHERE %s = :valeur',
    $colonne
  ));

  $req->bindParam(':valeur', $valeur);
  $req->execute();

  $cat = $req->fetch(PDO::FETCH_ASSOC);
  return $cat ?: null;
}

// Count du nombre de catégorie dans la BDD
function countCat(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM plats_Categories');
  $cat = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbCat = count($cat);

  return $nbCat;
}

//select la position la plus grande
function getLastPosition(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM plats_Categories');
  $count = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbCat = count($count);

  if ($nbCat > 0) {
    $query = $pdo->query('SELECT * FROM plats_Categories ORDER BY position DESC LIMIT 1');
    $cat = $query->fetch();

    return ++$cat['position'];
  } else {
    return  ++$nbCat;
  }
}
