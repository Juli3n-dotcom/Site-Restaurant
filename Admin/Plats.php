<?php
require_once __DIR__ . '/Assets/Config/Init.php';
require_once __DIR__ . '/Assets/Functions/PlatsFunctions.php';

use App\GeneralClass;

$page_title = 'Plats';
$ua = getBrowser();
include __DIR__ . '/Assets/Includes/HeaderAdmin.php';
?>

<div class="notif" id='notif'></div>


<section class="grid">
  <div class="recent__grid">
    <div class="grid__card">



      <div class="card__header">
        <h3><?= $page_title ?> : <span id="nbplats"><?= countPlats($pdo) ?></span></h3>


        <div class="btn-container">
          <button id="add_plat">
            <i class="fas fa-plus"></i>
            Ajouter
          </button>
          <button id="add_plat_min">
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
        <h5 class="modal-title" id="exampleModalLabel">Ajouter un plat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data" id="add_plat_form">

          <div class=" mb-3 mt-4">
            <label for="add_title">Titre: </label>
            <input type="text" name="add_title" id="add_title" class="form-control">
          </div>

          <?php if ($options['show_plat_photo']) : ?>
            <div class="mb-3 mt-4">
              <label for="add_img" class="form_label">Image : </label>
              <input type="file" name="add_img" id="add_img" class="form-control">
            </div>
          <?php endif; ?>

          <div class=" mb-3 mt-4">
            <label for="add_price">Prix: </label>
            <input type="currency" min="0" max="10000" step="1" name="add_price" id="add_price" class="form-control">
          </div>

          <div class=" mb-3 mt-4">
            <label for="add_description" class="form_label">Description : </label>
            <textarea name="add_description" id="add_description" class="form-control" placeholder="indiquez la description"></textarea>
          </div>

          <div class="mb-3 mt-4">
            <label for="cat" class="form_label">Catégories : </label>
            <select class="form-select" name="cat" id="cat" aria-label="">
              <option value="0" disabled selected>Choisir une categorie :</option>
              <?php foreach (getCat($pdo) as $cat) : ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['titre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <?php if ($options['show_sous_cat']) : ?>
            <div class="mb-3 mt-4" id="subCat">
              <label for="sous_cat" class="form_label">Sous Catégories : </label>
              <select class="form-select" name="sous_cat" id="sous_cat" aria-label="">
                <option value="0" disabled selected>Choisir une sous categorie :</option>

              </select>
            </div>
          <?php endif; ?>

          <div class=" mb-3 mt-4 input-block">
            <label for="est_epice">S' agit il d'un plat épicé: </label>
            <div>
              <input type="checkbox" id="est_epice" name="est_epice" class="est_epice">
              <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
              <div class="input_help">
                <p>
                  Cocher cette case si le plat est épicé.
                </p>
              </div>
            </div>
          </div>

          <div class=" mb-3 mt-4 epicelevelcontainer" id="epicelevelcontainer">
            <label for="epicelevel">Niveau d'épices :</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="epicelevel" id="epicelevel1" value="1" checked>
              <label class="form-check-label" for="epicelevel">
                Un peu épicé <?php GeneralClass::getNbPiment(1, $ua['name']) ?>
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="epicelevel" id="epicelevel2" value="2">
              <label class="form-check-label" for="epicelevel">
                Moyennement épicé <?php GeneralClass::getNbPiment(2, $ua['name']) ?>
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="epicelevel" id="epicelevel3" value="3">
              <label class="form-check-label" for="epicelevel">
                Trés épicé <?php GeneralClass::getNbPiment(3, $ua['name']) ?>
              </label>
            </div>
          </div>

          <div class=" mb-3 mt-4 input-block">
            <label>Votre plat contient t'il des allergénes ? : </label>
            <div>
              <input type="checkbox" id="haveAllergene" name="haveAllergene">
              <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
              <div class="input_help">
                <p>
                  Vous permet de selectionner les allergénes du plat
                </p>
              </div>
            </div>
          </div>

          <div class="mb-3 mt-4 allergenecontainer" id="allergenecontainer">
            <?php foreach (getAllergenes($pdo) as $allergene) : ?>
              <div class="form-check allergene-element">
                <input type="checkbox" value="<?= $allergene['id']; ?>" name="allergenes[]" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                  <?= $allergene['titre']; ?>
                </label>
                <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                <div class="input_help">
                  <div>
                    <h6>Description :</h6>
                    <p>
                      Description :
                      <?= $allergene['description']; ?>
                    </p>
                  </div>
                  <?php if ($allergene['exclusions'] != null) : ?>
                    <div>
                      <h6>Exclusions : </h6>
                      <p>
                        <?= $allergene['exclusions']; ?>
                      </p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class=" mb-3 mt-4 input-block">
            <label for="est_vege">S'agit il d'un plat végétarien: </label>
            <div>
              <input type="checkbox" id="est_vege" name="est_vege" class="est_vege">
              <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
              <div class="input_help">
                <p>
                  Cocher cette case si le plat est végétarien.
                </p>
              </div>
            </div>
          </div>

          <div class=" mb-3 mt-4 input-block">
            <label for="est_vege">S'agit il d'un plat végan: </label>
            <div>
              <input type="checkbox" id="est_vegan" name="est_vegan">
              <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
              <div class="input_help">
                <p>
                  Cocher cette case si le plat est végan.
                </p>
              </div>
            </div>
          </div>

          <div class=" mb-3 mt-4 input-block">
            <label for="est_halal">S'agit il d'un plat halal: </label>
            <div>
              <input type="checkbox" id="est_halal" name="est_halal">
              <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
              <div class="input_help">
                <p>
                  Cocher cette case si le plat est halal.
                </p>
              </div>
            </div>
          </div>

          <div class=" mb-3 mt-4 input-block">
            <label for="est_casher">S'agit il d'un plat casher: </label>
            <div>
              <input type="checkbox" id="est_casher" name="est_casher">
              <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
              <div class="input_help">
                <p>
                  Cocher cette case si le plat est cacher.
                </p>
              </div>
            </div>
          </div>

          <div class=" mb-3 mt-4 input-block">
            <label for="est_nouveau">Afficher <span class="new"> nouveau</span> sur le plat: </label>
            <div>
              <input type="checkbox" id="est_nouveau" name="est_nouveau">
              <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
              <div class="input_help">
                <p>
                  vous donner la possibilité d'afficher le logo <span class="new"> nouveau</span>
                  sur le plat.
                </p>
              </div>
            </div>
          </div>
          <?php if ($options['show_plat_en_avant']) : ?>
            <div class=" mb-3 mt-4 input-block">
              <label for="est_en_avant">Mettre en avant le plat: </label>
              <div>
                <input type="checkbox" id="est_en_avant" name="est_en_avant">
                <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                <div class="input_help">
                  <p>
                    Vous permet de promouvoir le plat.
                  </p>
                </div>
              </div>
            </div>
          <?php endif; ?>



          <div class="modal-footer" id="footer-action">
            <input type="reset" class="resetBtn" id="resetBtn" value="Effacer">
            <button type="submit" name="add_plat" class="addBtn">Ajouter</button>
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

<!-- ############################################## ***** Modal delete Plat ***** ########################################################## -->


<div class="modal fade" id="deletemodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Suppresion Plat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="delete_plat">
          <input type="hidden" name="delete_id" id="delete_id">

          <p>Etes vous sur de vouloir supprimer ce plat?</p>

          <input type="checkbox" id="confirmedelete" name="confirmedelete" class="confirmedelete">
          <label for="confirmedelete">OUI</label>

          <div class="modal-footer">
            <button type="submit" name="deleteplat" id="deleteplat" class="disabledBtn" disabled="true">Supprimer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ############################################## ***** Modal view Plat ***** ########################################################## -->


<div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Plat détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="plat_detail">
        <div class="list_container">

        </div>
      </div>
    </div>
  </div>
</div>

<!-- ############################################## ***** Modal edit plat ***** ########################################################## -->


<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modification Plat</h5>
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