<?php

// Count du nombre de catégorie dans la BDD
function countCat(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM boissons_categories');
  $cat = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbCat = count($cat);

  return $nbCat;
}

//vérification sur category existe déja
function getCatBy(PDO $pdo, string $colonne, $valeur): ?array
{
  $req = $pdo->prepare(sprintf(
    'SELECT *
       FROM boissons_categories
       WHERE %s = :valeur',
    $colonne
  ));

  $req->bindParam(':valeur', $valeur);
  $req->execute();

  $cat = $req->fetch(PDO::FETCH_ASSOC);
  return $cat ?: null;
}


//select la position la plus grande
function getLastPosition(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM boissons_categories');
  $count = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbCat = count($count);

  if ($nbCat > 0) {
    $query = $pdo->query('SELECT * FROM boissons_categories ORDER BY position DESC LIMIT 1');
    $cat = $query->fetch();

    return ++$cat['position'];
  } else {
    return  ++$nbCat;
  }
}
