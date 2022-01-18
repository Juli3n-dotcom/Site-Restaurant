<?php

/* #############################################################################

Modal pour demandé de l'aide \ visible sur l'ensemble de la partie Admin

############################################################################# */
?>

<div class="modal fade" id="helpmodal" tabindex="-1" aria-labelledby="helpmodal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Demander de l'aide</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="help_detail">
        <div class="list_container">

          <form action="" method="post" enctype="multipart/form-data" id="help__form">

            <div class="mb-3 mt-4">
              <label for="name">Votre Nom: </label>
              <input type="text" name="help__name" id="help__name" class="form-control">
            </div>

            <div class="mb-3 mt-4">
              <label for="email">Votre Email: </label>
              <input type="email" name="help__email" id="help__email" class="form-control">
            </div>

            <div class="mb-3 mt-4">
              <label for="subject">Sujet: </label>
              <input type="text" name="help__subject" id="help__subject" class="form-control">
            </div>

            <div class="mb-3 mt-4">
              <label for="message">Votre Message: </label>
              <textarea name="help__message" id="help__message" class="form-control" cols="40" rows="10"></textarea>
            </div>

            <div class="modal-footer">
              <button type="submit" name="help__submit" class="addBtn">Envoyer</button>
            </div>


          </form>

        </div>
      </div>
    </div>
  </div>