<?php
require_once __DIR__ . '/assets/config/Bootstrap.php';
$page_title = 'Team';
include __DIR__ . '/assets/includes/HeaderAdmin.php';
?>

<!-- <?php include __DIR__ . '/../global/includes/flash.php'; ?> -->

<div class="notif" id='notif'></div>




<section class="grid">
  <div class="recent__grid">
    <div class="grid__card">

      <div class="card__header">
        <h3>Team Members </h3>

        <!-- <?php if ($Membre['statut'] == 0) : ?> -->
        <button id="add_team_member">
          <i class="fas fa-user-plus"></i>
          Ajouter
        </button>
        <button id="add_team_member_min">
          <i class="fas fa-user-plus"></i>
        </button>
        <!-- <?php endif; ?> -->
      </div>

      <div class="table-responsive" id="table">
        <!-- retour ajax -->
      </div>

    </div>
  </div>
</section>


<!-- ############################################## ***** Modal add team member ***** ########################################################## -->
<!-- <?php if ($Membre['statut'] == 0) : ?> -->

<div class="modal fade" id="addmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajouter Team Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data" id="add_member">

          <div class="mb-3 mt-4">
            <label class="" for="statut">Civilité :</label>
            <select class="custom-select" name="add_civilite" id="add_civilite">
              <option>...</option>
              <option value="<?= FEMME ?>">Madame</option>
              <option value="<?= HOMME ?>">Monsieur</option>
              <option value="<?= AUTRE ?>">Autre</option>
            </select>
          </div>

          <div class="mb-3 mt-4">
            <label for="add_name_member">Nom: </label>
            <input type="text" name="add_name_member" id="add_name_member" class="form-control">
          </div>

          <div class="mb-3 mt-4">
            <label for="add_prenom_member">Prenom: </label>
            <input type="text" name="add_prenom_member" id="add_prenom_member" class="form-control">
          </div>

          <div class="mb-3 mt-4">
            <label for="add_email_member">Email: </label>
            <input type="email" name="add_email_member" id="add_email_member" class="form-control">
          </div>


          <div class="mb-3 mt-4">
            <label class="" for="add_statut">Statut :</label>
            <select class="custom-select" name="add_statut" id="add_statut">
              <option>...</option>
              <option value="<?= ROLE_ADMIN ?>">Admin</option>
              <option value="<?= ROLE_GERANT ?>">Gérant</option>
              <option value="<?= ROLE_EDITEUR ?>">Editeur</option>
            </select>
          </div>



          <div class="modal-footer">
            <input type="reset" class="resetBtn" id="resetBtn" value="Effacer">
            <button type="submit" name="add_team_member" class="addBtn">Ajouter</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ############################################## ***** Modal delete team member ***** ########################################################## -->


<div class="modal fade" id="deletemodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Team Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="delete_member">
          <input type="hidden" name="delete_id" id="delete_id">

          <p>Etes vous sur de vouloir supprimer cette personne?</p>

          <input type="checkbox" id="confirmedelete" name="confirmedelete" class="confirmedelete">
          <label for="confirmedelete">OUI</label>

          <div class="modal-footer">
            <button type="submit" name="deletemember" id="deletemember" class="disabledBtn" disabled="true">Supprimer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- ############################################## ***** Modal edit team member ***** ########################################################## -->


<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Team Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="update_modal">

      </div>
    </div>
  </div>
</div>


<!-- <?php endif; ?> -->

<!-- ############################################## ***** Modal view member ***** ########################################################## -->


<div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Member détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="member_detail">
        <div class="list_container">

        </div>
      </div>
    </div>
  </div>


  <?php
  include __DIR__ . '/assets/includes/FooterAdmin.php';
  ?>