<?php
require_once __DIR__ . '/../../config/Init.php';

if (isset($_POST['cat_id'])) {
  $req = $pdo->prepare("SELECT id, titre FROM boissons_sous_categories WHERE cat_id=" . $_POST['cat_id']);
  $req->execute();
  $subcat = $req->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($subcat);
}
