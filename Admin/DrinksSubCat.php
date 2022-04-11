<?php
require_once __DIR__ . '/Assets/Config/Init.php';
require_once __DIR__ . '/Assets/Functions/DrinksSubCategoriesFunctions.php';

$page_title = 'Sous Catégories de Boissons';
$ua = getBrowser();
include __DIR__ . '/Assets/Includes/HeaderAdmin.php';
?>

<div class="notif" id='notif'></div>


<section class="grid">
  <div class="recent__grid">
    <div class="grid__card">



      <div class="card__header">
        <h3><?= $page_title ?> : <span id="nbdrinkcat"><?= countSousCat($pdo) ?></span></h3>


        <div class="btn-container">
          <button id="add_cat">
            <i class="fas fa-plus"></i>
            Ajouter
          </button>
          <button id="add_cat_min">
            <i class="fas fa-plus"></i>
          </button>
        </div>


      </div>


      <div class="table-responsive" id="table">
        <!-- retour ajax -->
      </div>


    </div>
  </div>
</section>

<!-- ############################################## ***** Modal add ***** ########################################################## -->


<div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter une sous catégorie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data" id="add_cat_form">

          <div class="mb-3 mt-4">
            <label for="add_title">Titre: </label>
            <input type="text" name="add_title" id="add_title" class="form-control">
          </div>

          <div class="mb-3 mt-4">
            <label for="cat" class="form_label">Catégories : </label>
            <select class="form-select" name="cat" aria-label="">
              <option value="0" disabled selected>Choisir une categorie :</option>
              <?php foreach (getCatParent($pdo) as $cat) : ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['titre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <?php if ($options['show_cat_drink_description']) : ?>
            <div class="mb-3 mt-4">
              <label for="add_description" class="form_label">Description : </label>
              <textarea name="add_description" id="add_description" class="form-control" placeholder="indiquez la description de la catégorie"></textarea>
            </div>
          <?php endif; ?>


          <?php if ($options['show_sub_cat_drink_photo']) : ?>
            <div class="mb-3 mt-4">
              <label for="add_img" class="form_label">Image : </label>
              <input type="file" name="add_img" id="add_img" class="form-control">
            </div>
          <?php endif; ?>


          <div class="modal-footer" id="footer-action">
            <input type="reset" class="resetBtn" id="resetBtn" value="Effacer">
            <button type="submit" name="add_cat" class="addBtn">Ajouter</button>
          </div>
          <div class="modal-footer hide" id="load-add">
            <img src="Assets/Images/loader.gif" alt="Loader">
            <p>Chargement ...</p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ############################################## ***** Modal view Sous catégorie ***** ########################################################## -->


<div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Catégorie détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="cat_detail">
        <div class="list_container">

        </div>
      </div>
    </div>
  </div>
</div>

<!-- ############################################## ***** Modal delete Sous Categorie ***** ########################################################## -->


<div class="modal fade" id="deletemodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Suppresion Sous Categorie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="delete_cat">
          <input type="hidden" name="delete_id" id="delete_id">

          <p>Etes vous sur de vouloir supprimer cette categorie?</p>

          <input type="checkbox" id="confirmedelete" name="confirmedelete" class="confirmedelete">
          <label for="confirmedelete">OUI</label>

          <div class="modal-footer">
            <button type="submit" name="deletecat" id="deletecat" class="disabledBtn" disabled="true">Supprimer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- ############################################## ***** Modal edit Sous Categorie ***** ########################################################## -->


<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modification catégorie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="update_modal">

      </div>
    </div>
  </div>
</div>


<?php
include __DIR__ . '/Assets/Includes/FooterAdmin.php';
?>