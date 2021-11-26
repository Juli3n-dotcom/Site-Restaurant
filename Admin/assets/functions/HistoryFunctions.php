<?php

//count du nombre de history element add
function countHistoryAdd(PDO $pdo)
{
  $query = $pdo->query("SELECT count(*) as nb from journal WHERE statut = 0");
  $data = $query->fetch();

  $count = $data['nb'];
  return $count;
}

//count du nombre de history element update
function countHistoryUpdate(PDO $pdo)
{
  $query = $pdo->query("SELECT count(*) as nb from journal WHERE statut = 1");
  $data = $query->fetch();

  $count = $data['nb'];
  return $count;
}

//count du nombre de history element delete
function countHistoryDelete(PDO $pdo)
{
  $query = $pdo->query("SELECT count(*) as nb from journal WHERE statut = 2");
  $data = $query->fetch();

  $count = $data['nb'];
  return $count;
}

//count du nombre de history element info
function countHistoryInfo(PDO $pdo)
{
  $query = $pdo->query("SELECT count(*) as nb from journal WHERE statut = 3");
  $data = $query->fetch();

  $count = $data['nb'];
  return $count;
}

//count du nombre de history element Error
function countHistoryError(PDO $pdo)
{
  $query = $pdo->query("SELECT count(*) as nb from journal WHERE statut = 4");
  $data = $query->fetch();

  $count = $data['nb'];
  return $count;
}

//count du nombre de history element Warning
function countHistoryWarning(PDO $pdo)
{
  $query = $pdo->query("SELECT count(*) as nb from journal WHERE statut = 5");
  $data = $query->fetch();

  $count = $data['nb'];
  return $count;
}
