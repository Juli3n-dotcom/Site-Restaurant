<?php

// Count du nombre de catégorie dans la BDD
function countPlats(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM plats');
  $plats = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbPlats = count($plats);

  return $nbPlats;
}

//récupération des catégories 
function getCat(PDO $pdo): array
{
  $req = $pdo->query(
    'SELECT *
       FROM plats_categories'
  );
  $cat = $req->fetchAll(PDO::FETCH_ASSOC);
  return $cat;
}

//récupération des sous catégories 
function getSubCat(PDO $pdo, INt $id): array
{
  $req = $pdo->query(
    "SELECT *
       FROM plats_sous_categories
       WHERE cat_id = '$id'
  "
  );
  $subCat = $req->fetchAll(PDO::FETCH_ASSOC);
  return $subCat;
}

// récupération des allergenes
function getAllergenes(PDO $pdo): array
{
  $req = $pdo->query(
    'SELECT *
          FROM allergenes
          ORDER BY titre ASC
        '
  );
  $allergene = $req->fetchAll(PDO::FETCH_ASSOC);
  return $allergene;
}

//vérification sur category existe déja
function getPlatBy(PDO $pdo, string $colonne, $valeur): ?array
{
  $req = $pdo->prepare(sprintf(
    'SELECT *
       FROM plats
       WHERE %s = :valeur',
    $colonne
  ));

  $req->bindParam(':valeur', $valeur);
  $req->execute();

  $plat = $req->fetch(PDO::FETCH_ASSOC);
  return $plat ?: null;
}

function getPlatByID(PDO $pdo, $id): ?array
{

  //Vérification de la valeur de $id
  if (!ctype_digit($id)) {
    return null;
  }

  $req = $pdo->prepare(
    'SELECT * 
      from plats
      WHERE id = :id'
  );

  $req->bindParam(':id', $id, PDO::PARAM_INT);
  $req->execute();

  $plat = $req->fetch(PDO::FETCH_ASSOC);
  return $plat ?: null;
}

//select la position la plus grande
function getLastPosition(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM plats');
  $count = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbCat = count($count);

  if ($nbCat > 0) {
    $query = $pdo->query('SELECT * FROM plats ORDER BY position DESC LIMIT 1');
    $cat = $query->fetch();

    return ++$cat['position'];
  } else {
    return  ++$nbCat;
  }
}

// get allergene in plat
function getAllergenePlat(PDO $pdo, int $id)
{
  $query = $pdo->query("SELECT a.titre, a.id, l.allergene_id
                          FROM plats_allergenes_liaison l
                          LEFT JOIN allergenes a ON l.allergene_id = a.id
                          WHERE l.plat_id = '$id'
                          ORDER BY a.titre ASC
                         ");
  $allergenes = $query->fetchAll(PDO::FETCH_ASSOC);
  return $allergenes;
}

// get allergene is check
function getAllergeneCheck(PDO $pdo, int $id)
{
  $req = $pdo->prepare("SELECT *
                          FROM plats_allergenes_liaison
                          WHERE plat_id = :plat_id 
                         ");
  $req->bindParam(':plat_id', $id);
  $req->execute();

  $allergenes = $req->fetchAll(PDO::FETCH_ASSOC);
  $allergenesIdArray = array();
  foreach ($allergenes as $idAllergene) {
    array_push($allergenesIdArray, $idAllergene['allergene_id']);
  }

  return $allergenesIdArray;
}
