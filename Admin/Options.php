<?php
require_once __DIR__ . '/Assets/Config/Init.php';

$page_title = 'Options';
include __DIR__ . '/Assets/Includes/HeaderAdmin.php';
?>



<div class="notif" id='notif'></div>


<section id="options__cat__plat">

  <div class="option-grid">
    <div class="recent__grid">
      <div class="grid__card">

        <div class="card__header">
          <h3>Categories des plats </h3>
        </div>

      </div>
    </div>
  </div>

  <div class="dash__cards" id="cat_plat">
    <!-- retour ajax cat -->
  </div>

</section>

<section id="options__plats">

  <div class="option-grid">
    <div class="recent__grid">
      <div class="grid__card">

        <div class="card__header">
          <h3>Plats </h3>
        </div>

      </div>
    </div>
  </div>

  <div class="dash__cards" id="plats">
    <!-- retour ajax cat -->
  </div>

</section>


<?php
include __DIR__ . '/Assets/Includes/FooterAdmin.php';
?>