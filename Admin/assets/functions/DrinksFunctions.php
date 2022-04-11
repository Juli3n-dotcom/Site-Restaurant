<?php

// Count du nombre de catégorie dans la BDD
function countDrinks(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM boissons');
  $Drinks = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbDrinks = count($Drinks);

  return $nbDrinks;
}

//vérification sur category existe déja
function getDrinkBy(PDO $pdo, string $colonne, $valeur): ?array
{
  $req = $pdo->prepare(sprintf(
    'SELECT *
       FROM boissons
       WHERE %s = :valeur',
    $colonne
  ));

  $req->bindParam(':valeur', $valeur);
  $req->execute();

  $drink = $req->fetch(PDO::FETCH_ASSOC);
  return $drink ?: null;
}

//select la position la plus grande
function getLastPosition(PDO $pdo)
{
  $req = $pdo->query('SELECT * FROM boissons');
  $count = $req->fetchAll(PDO::FETCH_ASSOC);
  $nbCat = count($count);

  if ($nbCat > 0) {
    $query = $pdo->query('SELECT * FROM boissons ORDER BY position DESC LIMIT 1');
    $cat = $query->fetch();

    return ++$cat['position'];
  } else {
    return  ++$nbCat;
  }
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


//récupération des catégories 
function getCat(PDO $pdo): array
{
  $req = $pdo->query(
    'SELECT *
       FROM boissons_categories'
  );
  $cat = $req->fetchAll(PDO::FETCH_ASSOC);
  return $cat;
}

//récupération des sous catégories 
function getSubCat(PDO $pdo, INt $id): array
{
  $req = $pdo->query(
    "SELECT *
       FROM boissons_sous_categories
       WHERE cat_id = '$id'
  "
  );
  $subCat = $req->fetchAll(PDO::FETCH_ASSOC);
  return $subCat;
}

// get allergene in plat
function getAllergeneDrinks(PDO $pdo, int $id)
{
  $query = $pdo->query("SELECT a.titre, a.id, l.allergene_id
                          FROM boissons_allergenes_liaison l
                          LEFT JOIN allergenes a ON l.allergene_id = a.id
                          WHERE l.boisson_id = '$id'
                          ORDER BY a.titre ASC
                         ");
  $allergenes = $query->fetchAll(PDO::FETCH_ASSOC);
  return $allergenes;
}


// get allergene is check
function getAllergeneCheck(PDO $pdo, int $id)
{
  $req = $pdo->prepare("SELECT *
                          FROM boissons_allergenes_liaison
                          WHERE boisson_id = :boisson_id 
                         ");
  $req->bindParam(':boisson_id', $id);
  $req->execute();

  $allergenes = $req->fetchAll(PDO::FETCH_ASSOC);
  $allergenesIdArray = array();
  foreach ($allergenes as $idAllergene) {
    array_push($allergenesIdArray, $idAllergene['allergene_id']);
  }

  return $allergenesIdArray;
}
