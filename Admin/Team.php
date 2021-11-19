<?php
$page_title = 'Team';
include __DIR__ . '/assets/includes/Header_admin.php';
?>

<?php include __DIR__ . '/../global/includes/flash.php'; ?>

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
        <!-- <?php endif; ?> -->
      </div>

      <div class="table-responsive" id="table">

        <tr>
          <td>1</td>
          <td class="dnone">1</td>
          <td>Quentier</td>
          <td>Julien</td>
          <td class="td-team">
            <div class='img-profil' style='background-image: url(assets/images/profil.svg)'></div>
          </td>
          <td><a href="mailto:" class="email_member">julien@webupdesign.fr</a></td>
          <td class="dnone"><i></i></td><!--  object non visible pour récupérétion-->
          <td>
            <p class="badge admin">Admin</p>

          </td>

          <td class="dnone"><i></i></td><!--  object non visible pour récupérétion-->
          <td>
            <p class="badge success confirmation">Oui</p>


          </td>

          <td class="member_action">
            <div class="member_action-container">
              <input type="button" class="viewbtn" name="view" id=""></input>
              <input type="button" class="editbtn" id=""></input>
              <input type="button" class="deletebtn"></input>
            </div>
          </td>
        </tr>




        </tbody>


        </table>
      </div>

    </div>
  </div>
</section>


<!-- ############################################## ***** Modal add team member ***** ########################################################## -->
<?php if ($Membre['statut'] == 0) : ?>

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
                <option value="<?= ROLE_GERANT ?>">GERANT</option>
                <option value="<?= ROLE_EDITEUR ?>">Editeur</option>
              </select>
            </div>


            <div class="modal-footer">
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


<?php endif; ?>

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
  include __DIR__ . '/assets/includes/Footer_admin.php';
  ?>