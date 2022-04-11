<?php
require_once __DIR__ . '/../../Config/Init.php';
require_once __DIR__ . '/../../Functions/DrinksFunctions.php';

use App\General;
use App\Drinks;

/* #############################################################################

Ajout d'une boisson à partir Drinks.php en Ajax

#1 - Récupération des infos, et stockage dans des variables pour
#2 - Validation du titre
#3 - Validation du prix
#4 - Validation de la descritions
#5 - Vérification de la catégorie
#6 - Vérification de la photo
#7 - Vérification de la sous catégories
#8 - if option photo is active, enregistrement de la photo de
  #8.1 - chargement de l'image
  #8.2 - Récupération des infos de la photo
  #8.3 - vérification du téléchargement de la photo
  #8.4 - vérification si c'est bien une image
  #8.8 - vérification du format
  #8.6 - enregistrement du fichier + génération du nom
  #8.7 - vérification de l'enregistrement
  #8.8 - Création des différents formats de la photo
  #8.9 - Enregistrement en BDD des photos
#9 - récupération de l'ID de l'image si option photo is active
#10 - récupération de la position du dernier element
#11 - enregistrement en BDD de la boisson
#12 - Gestion des allergenes
#13 - Notification de réussite
#14 - Retour AJAX

############################################################################# */

$user = $user['id_team_member'];

if (!empty($_POST)) {
  #1 - Récupération des infos, et stockage dans des variables pour
  $result = array();
  $titre = htmlspecialchars($_POST['add_title']);
  $price = htmlspecialchars($_POST['add_price']);
  $prix = str_replace(',', '.', $price);
  $description = htmlspecialchars($_POST['add_description']);
  $sous_cat = NULL;


  #2 - Validation du titre
  if (empty($titre) && !preg_match('~^[a-zA-Z- ]+$~', $titre)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le titre');
    postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une boisson', 'Titre manquant');
  } else if (getDrinkBy($pdo, 'titre', $titre) !== null) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups ce plat existe déjà');
    postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une boisson', 'Boisson déjà existante');
    #3 - Validation du prix
  } else if (empty($prix)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque le prix');
    postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une boisson', 'Prix manquant');
    #4 - Validation de la descritions
  } else if (empty($description)) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'oups! il manque la description');
    postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une boisson', 'Description manquante');
    #5 - Vérification de la catégorie
  } else if (empty($_POST['cat'])) {
    $result['status'] = false;
    $result['notif'] = General::notif('warning', 'Merci de sélectionner une catégorie');
    postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une boisson', 'Catégorie manquante');
    #6 - Vérification de la photo
  } else if ($options['show_drink_photo']) {
    if (empty($_FILES['add_img']['name'])) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'oups il manque la photo de la boisson');
      postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une boissons', 'oups il manque la photo de la boisson');
      #7 - Vérification de la sous catégories
    }
  } else if (($options['show_drink_sous_cat'])) {
    if (empty($_POST['sous_cat'])) {
      $result['status'] = false;
      $result['notif'] = General::notif('warning', 'Merci de sélectionner une sous-catégorie');
      postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une boisson', 'Sous-catégorie manquante');
    } else {
      $sous_cat = $_POST['sous_cat'];
    }
  } else {

    if ($options['show_drink_photo']) {
      #8.2 - Récupération des infos de la photo
      $extension = pathinfo($_FILES['add_img']['name'], PATHINFO_EXTENSION);
      $pathOriginal = __DIR__ . '/../../../../Global/Uploads/Originals';
      $allowTypes = array('jpg', 'png', 'jpeg');
      #8.3 - vérification du téléchargement de la photo
      if ($_FILES['add_img']['error'] !== UPLOAD_ERR_OK) {

        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['add_img']['error']);
        postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une boisson', 'Probléme lors de l\'envoi du fichier.code ' . $_FILES['add_img']['error']);
        #8.4 - vérification si c'est bien une image
      } elseif ($_FILES['add_img']['size'] < 12) {

        $result['status'] = false;
        $result['notif'] = General::notif('error', 'Le fichier envoyé n\'est pas une image');
        postJournal($pdo, $user, 4, 'Tentative d\'ajout d\'une boisson', 'Le fichier envoyé n\'est pas une image');
        #8.5 - vérification du format
      } elseif (!in_array($extension, $allowTypes)) {
        $result['status'] = false;
        $result['notif'] = General::notif('warning', 'Format d\'image non pris en charge');
        postJournal($pdo, $user, 5, 'Tentative d\'ajout d\'une boisson', 'Format d\'image non pris en charge');
      } else {
        #8.6 - enregistrement du fichier + génération du nom      
        do {
          $filename = bin2hex(random_bytes(16));
          $complete_path = $pathOriginal . '/' . $filename . '.' . $extension;
        } while (file_exists($complete_path));
        #8.7 - vérification de l'enregistrement  
        if (!move_uploaded_file($_FILES['add_img']['tmp_name'], $complete_path)) {
          $result['status'] = false;
          $result['notif'] = General::notif('error', 'La photo n\'a pas pu être enregistrée');
          postJournal($pdo, $user, 4, 'Tentative d\'ajout d\'une boisson ', 'La photo n\'a pas pu être enregistrée');
        } else {
          #8.8 - Création des différents formats de la photo          
          if (file_exists($complete_path)) {

            $path = __DIR__ . '/../../../../Global/Uploads';

            if ($extension == 'png') {

              $quality = 50;

              $image = imagecreatefrompng($complete_path);
              ob_start();
              imagewebp($image, $path . '/' . $filename . '.webp');
              imagejpeg($image, $path . '/' . $filename . '.jpg', $quality);
              ob_end_clean();
              imagedestroy($image);
            } elseif ($extension == 'jpg' || $extension = 'jpeg') {
              copy($complete_path, $path . '/' . $filename . '.' . $extension);
              $image = imagecreatefromjpeg($complete_path);
              ob_start();
              imagewebp($image, $path . '/' . $filename . '.webp');
              ob_end_clean();
              imagedestroy($image);
            }
          }
          #8.9 - Enregistrement en BDD des photos
          $req1 = $pdo->prepare(
            'INSERT INTO plats_photo(img__jpeg, img__webp, original)
                         VALUES (:img__jpeg,:img__webp,:original)'
          );

          if ($extension == 'png') {
            $req1->bindValue('img__jpeg', $filename . '.jpg');
            $req1->bindValue('img__webp',  $filename . '.webp');
          } elseif ($extension == 'jpg' || $extension = 'jpeg') {

            $req1->bindValue('img__jpeg',  $filename . '.' . $extension);
            $req1->bindValue('img__webp',  $filename . '.webp');
          }

          $req1->bindValue('original', $filename . '.' . $extension);
          $req1->execute();
        }
      }
    }

    #9 - récupération de l'ID de l'image si option photo is active
    if ($options['show_drink_photo']) {
      $img = $pdo->lastInsertId();
    } else {
      $img = NULL;
    }
    #10 - récupération de la position du dernier element
    $lastPosition = getLastPosition($pdo);

    #11 - enregistrement en BDD de la boisson
    $req2 = $pdo->prepare('INSERT INTO boissons(titre,
                                            prix,
                                            description,
                                            author_id,
                                            cat_id,
                                            souscat_id,
                                            have_allergenes,
                                            photo_id,
                                            est_en_avant,
                                            est_nouveau,
                                            est_publie,
                                            position,
                                            date_enregistrement,
                                            date_modification)
                                      VALUES(:titre,
                                      :prix,
                                      :description,
                                      :author_id,
                                      :cat_id,
                                      :souscat_id,
                                      :have_allergenes,
                                      :photo_id,
                                      :est_en_avant,
                                      :est_nouveau,
                                      :publie,
                                      :position,
                                      :date,
                                      :update)');

    $req2->bindParam(':titre', $titre);
    $req2->bindParam(':prix', $prix);
    $req2->bindParam(':description', $description);
    $req2->bindValue(':author_id', $user);
    $req2->bindParam(':cat_id', $_POST['cat'], PDO::PARAM_INT);
    $req2->bindParam(':souscat_id', $sous_cat, PDO::PARAM_INT);
    $req2->bindValue(':have_allergenes', isset($_POST['haveAllergene']), PDO::PARAM_BOOL);
    $req2->bindValue(':photo_id', $img);
    if ($options['show_drinks_en_avant']) {
      $req2->bindValue(':est_en_avant', isset($_POST['est_en_avant']), PDO::PARAM_BOOL);
    } else {
      $req2->bindValue(':est_en_avant', 0);
    }
    $req2->bindValue(':est_en_avant', isset($_POST['est_en_avant']), PDO::PARAM_BOOL);
    $req2->bindValue(':est_nouveau', isset($_POST['est_nouveau']), PDO::PARAM_BOOL);
    $req2->bindValue(':publie', 1);
    $req2->bindValue(':position', $lastPosition);
    $req2->bindValue(':date', (new DateTime())->format('Y-m-d H:i:s'));
    $req2->bindValue(':update', NULL);
    $req2->execute();

    #12 - Gestion des allergenes
    if (isset($_POST['haveAllergene'])) {
      $allergenes =  $_POST['allergenes'];
      $id = $pdo->lastInsertId();

      foreach ($allergenes as $a) {
        $allergene = $a;
        $req3 = $pdo->prepare('INSERT INTO boissons_allergenes_liaison
                              (boisson_id,allergene_id)VALUES(:boisson_id,:allergene_id)');
        $req3->bindParam(':boisson_id', $id, PDO::PARAM_INT);
        $req3->bindParam(':allergene_id', $a, PDO::PARAM_INT);
        $req3->execute();
      }
    }

    #13 - Notification de réussite   
    $result['status'] = true;
    $result['notif'] = General::notif('success', 'Nouvelle boisson ajoutée');
    postJournal($pdo, $user, 0, 'Nouvelle boisson', 'Nouvelle boisson ajoutée');

    #14 - Retour AJAX 
    $ua = getBrowser();

    $record_per_page = 10;
    $page = 0;

    if (isset($_POST['page'])) {

      $page = $_POST['page'];
    } else {

      $page = 1;
    }

    $start_from = ($page - 1) * $record_per_page;

    $query = $pdo->query("SELECT * FROM boissons ORDER BY cat_id, titre ASC LIMIT $start_from,$record_per_page");

    $result['nbdrinks'] = countDrinks($pdo);

    $result['resultat'] = '<table>

          <thead>
            <tr>
              <th class="dnone">ID</th>';
    //$result['resultat'] .= '<th class="tab desktop" title="Position de l\'element sur la carte">#</th>'
    $result['resultat'] .= '<th>Titre</th>';
    if ($options['show_drink_photo'] == 1) {
      $result['resultat'] .= '<th>Image</th>';
    }
    $result['resultat'] .= '<th class="desktop">Prix</th>';
    $result['resultat'] .= '<th class="desktop">Description</th>';
    $result['resultat'] .= '<th class="tab desktop">Catégorie</th>';
    if ($options['show_drinks_stats'] == 1) {
      $result['resultat'] .= '<th class="desktop"><i class="fas fa-chart-area"></i></th>';
    }
    if ($options['show_drinks_en_avant'] == 1) {
      $result['resultat'] .= '<th class="tab desktop">Mise en avant</th>';
    }
    $result['resultat'] .= '<th class="tab desktop">Afficher</th>
              <th>Actions</th>';
    $result['resultat'] .= ' </tr></thead>';
    $result['resultat'] .= '<tbody class="page_list">';

    while ($row = $query->fetch()) {

      //$result['resultat'] .= '<tr data-position="' . $row['position'] . '" data-id="' . $row['id'] . '">';
      // $result['resultat'] .= '<td class="tab desktop">' . $row['position'] . '</td>';
      $result['resultat'] .= '<td id="' . $row['id'] . '"class="dnone">' . $row['id'] . '</td>';
      $result['resultat'] .= '<td class="plat_title"><strong>' . $row['titre'] . '</strong>' . ($row['est_nouveau'] ? '<span class="new"> nouveau</span>' : '') . '</td>';
      if ($options['show_drink_photo'] == 1) {
        $result['resultat'] .= '<td class="dnone cat_pics">' . $row['photo_id'] . '</td>';
        $result['resultat'] .= '<td class="td-team">' . Drinks::getImage($pdo, $row['photo_id'], $ua['name']) . '</td>';
      }
      $result['resultat'] .= '<td class="desktop">' . $row['prix'] . ' €</td>';
      $result['resultat'] .= '<td class="desktop description">' . $row['description'] . '</td>';
      $result['resultat'] .= '<td class="tab desktop"><strong>' . Drinks::getCat($pdo, $row['cat_id']) . '</strong></td>';
      if ($options['show_drinks_stats'] == 1) {
        $result['resultat'] .= '<td class="desktop">0</td>';
      }
      if ($options['show_drinks_en_avant'] == 1) {
        $result['resultat'] .= '<th class="tab desktop">' . Drinks::getEstEnAvant($pdo, $row['id']) . '</th>';
      }
      if ($row['est_publie'] == 1) {

        $result['resultat'] .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . ' checked></td>';
      } else {

        $result['resultat'] .= '<td class="tab desktop"> <input type="checkbox" id="est_publie" name="est_publie" class="est_publie" value=' . $row['est_publie'] . '></td>';
      }
      $result['resultat'] .= '<td class="member_action">';
      $result['resultat'] .= '<div class="member_action-container">';
      $result['resultat'] .= '<a href="FichePlat.php?id=' . $row['id'] . '" class="linkbtn"><i class="fa-regular fa-eye"></i></a>';
      $result['resultat'] .= '<input type="button" class="viewbtn" name="view" id="' . $row['id'] . '"></input>';
      $result['resultat'] .= '<input type="button" class="editbtn" id="' . $row['id'] . '"></input>';
      $result['resultat'] .= '<input type="button" class="deletebtn"></input>';
      $result['resultat'] .= '</div>';
      $result['resultat'] .= '</td>';
      $result['resultat'] .= '</tr>';
    }

    $result['resultat'] .= '</tbody></table>';
    if (countDrinks($pdo) > 10) {
      $result['resultat'] .= '<br /><div class="custom_pagination">';

      $page_query = $pdo->query('SELECT * FROM boissons ORDER BY cat_id, titre ASC');
      $total_records = $page_query->rowCount();
      $total_pages = ceil($total_records / $record_per_page);


      $result['resultat'] .= '<ul class="pagination">';

      if ($page > 1) {
        $previous = $page - 1;
        $result['resultat'] .= '<li class="pagination_link" id="' . $previous . '"><span class="page-link"><i class="fas fa-caret-left"></i> Précédent</span></li>';
      }

      if ($page < $total_pages) {
        $page++;
        $result['resultat'] .= '<li class="pagination_link" id="' . $page . '"><span class="page-link">Suivant <i class="fas fa-caret-right"></i></span></li>';
      }

      $result['resultat'] .= '</ul>';

      $result['resultat'] .= '</div>';
    }
  }

  // Return result 
  echo json_encode($result);
}
