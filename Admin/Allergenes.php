<?php
require_once __DIR__ . '/Assets/Config/Init.php';
require_once __DIR__ . '/Assets/Functions/PlatsFunctions.php';
$page_title = 'Allergènes';
include __DIR__ . '/Assets/Includes/HeaderAdmin.php';
?>

<div class="notif" id='notif'></div>


<section class="grid">
  <div class="recent__grid">
    <div class="grid__card">

      <div class="card__header">
        <h3>Listes des Allergènes</h3>



        <button id="add_aller">
          <i class="fas fa-plus"></i>
          Ajouter
        </button>
        <button id="add_aller_min">
          <i class="fas fa-plus"></i>
        </button>

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
        <h5 class="modal-title" id="exampleModalLabel">Ajouter un allergènes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data" id="add_allergene">

          <div class=" mb-3 mt-4">
            <label for="add_title">Titre: </label>
            <input type="text" name="add_title" id="add_title" class="form-control">
          </div>


          <div class="mb-3 mt-4">
            <label for="add_description" class="form_label">Description : </label>
            <textarea name="add_description" id="add_description" class="form-control" placeholder="indiquez la description"></textarea>
          </div>

          <div class="mb-3 mt-4">
            <label for="add_exclusions" class="form_label">Exclusions : </label>
            <textarea name="add_exclusions" id="add_exclusions" class="form-control" placeholder="indiquez les exclusions"></textarea>
          </div>


          <div class="modal-footer" id="footer-action">
            <input type="reset" class="resetBtn" id="resetBtn" value="Effacer">
            <button type="submit" name="add_allergene" class="addBtn">Ajouter</button>
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

<!-- ############################################## ***** Modal view Allergenes ***** ########################################################## -->


<div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Allergene détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="aller_detail">
        <div class="list_container">

        </div>
      </div>
    </div>
  </div>
</div>

<!-- ############################################## ***** Modal delete Allergene ***** ########################################################## -->


<div class="modal fade" id="deletemodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Suppresion Allergene</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="delete_aller">
          <input type="hidden" name="delete_id" id="delete_id">

          <p>Etes vous sur de vouloir supprimer cet allergene?</p>

          <input type="checkbox" id="confirmedelete" name="confirmedelete" class="confirmedelete">
          <label for="confirmedelete">OUI</label>

          <div class="modal-footer">
            <button type="submit" name="deletealler" id="deletealler" class="disabledBtn" disabled="true">Supprimer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ############################################## ***** Modal edit Allergene ***** ########################################################## -->


<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modification Allergene</h5>
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