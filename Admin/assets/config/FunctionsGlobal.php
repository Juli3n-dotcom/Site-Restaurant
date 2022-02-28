<?php
require_once __DIR__ . '/Init.php';

function postJournal(PDO $pdo, $member, INT $statut, string $title, string $contenu)
{

  $req = $pdo->prepare('INSERT INTO journal(
                                              titre,
                                              member_id,
                                              contenu,
                                              statut,
                                              date_enregistrement)
                                              VALUES(
                                                :titre,
                                                :member_id,
                                                :contenu,
                                                :statut,
                                                :date
                                              )');
  $req->bindParam(':titre', $title);
  $req->bindParam(':member_id', $member);
  $req->bindParam(':contenu', $contenu);
  $req->bindValue(':statut', $statut);
  $req->bindValue(':date', (new DateTime())->format('Y-m-d H:i:s'));
  $req->execute();
}

function getUser(PDO $pdo, string $token)
{
  $req = $pdo->prepare(
    'SELECT *
       FROM team
       WHERE token = :token'
  );
  $req->bindParam(':token', $token);
  $req->execute();

  $user = $req->fetch(PDO::FETCH_ASSOC);
  return $user ?: null;
}


function getOptions(PDO $pdo)
{
  $req = $pdo->prepare(
    'SELECT *
       FROM options'
  );

  $req->execute();

  $options = $req->fetch(PDO::FETCH_ASSOC);
  return $options;
}
