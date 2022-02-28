<?php
require_once __DIR__ . '/Assets/Config/Init.php';
require_once __DIR__ . '/Assets/Functions/HistoryFunctions.php';
$page_title = 'History';
include __DIR__ . '/Assets/Includes/HeaderAdmin.php';
?>


<div class="notif" id='notif'></div>

<section>
  <div class="dash__cards" id="cards">

    <div class="card__single card_visites">
      <div class="card__body">
        <i class="fas fa-plus"></i>
        <div>
          <h5>SUCCESS</h5>
          <h4><?= countHistoryAdd($pdo) ?></h4>
        </div>
      </div>
      <div class="card__footer">
        <input type="button" name="allAdd" id="allAdd" value="View All">
      </div>
    </div>

    <div class="card__single card_visites">
      <div class="card__body">
        <i class="fas fa-edit"></i>
        <div>
          <h5>UPDATE</h5>
          <h4><?= countHistoryUpdate($pdo) ?></h4>
        </div>
      </div>
      <div class="card__footer">
        <input type="button" name="allUpdate" id="allUpdate" value="View All">
      </div>
    </div>

    <div class="card__single card_visites">
      <div class="card__body">
        <i class="fas fa-trash-alt"></i>
        <div>
          <h5>DELETE</h5>
          <h4><?= countHistoryDelete($pdo) ?></h4>
        </div>
      </div>
      <div class="card__footer">
        <input type="button" name="allDelete" id="allDelete" value="View All">
      </div>
    </div>

    <div class="card__single card_visites">
      <div class="card__body">
        <i class="fas fa-info"></i>
        <div>
          <h5>INFO</h5>
          <h4><?= countHistoryInfo($pdo) ?></h4>
        </div>
      </div>
      <div class="card__footer">
        <input type="button" name="allInfo" id="allInfo" value="View All">
      </div>
    </div>

    <div class="card__single card_visites">
      <div class="card__body">
        <i class="fas fa-times"></i>
        <div>
          <h5>ERROR</h5>
          <h4><?= countHistoryError($pdo) ?></h4>
        </div>
      </div>
      <div class="card__footer">
        <input type="button" name="allError" id="allError" value="View All">
      </div>
    </div>

    <div class="card__single card_visites">
      <div class="card__body">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
          <h5>WARNING</h5>
          <h4><?= countHistoryWarning($pdo) ?></h4>
        </div>
      </div>
      <div class="card__footer">
        <input type="button" name="allWarning" id="allWarning" value="View All">
      </div>
    </div>

  </div>
</section>

<section class="grid">
  <div class="recent__grid">
    <div class="grid__card">

      <div class="card__header">
        <h3>Journal </h3>
      </div>

      <div class="table-responsive" id="table">
        <!-- retour ajax -->
      </div>

    </div>
  </div>
</section>

<!-- ############################################## ***** Modal view History ***** ########################################################## -->


<div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">History détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="history_detail">
        <div class="list_container">

        </div>
      </div>
    </div>
  </div>

  <?php
  include __DIR__ . '/Assets/Includes/FooterAdmin.php';
  ?>