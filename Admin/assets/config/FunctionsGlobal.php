<?php

function postJournal(PDO $pdo, INT $member, INT $statut, string $title, string $contenu)
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
