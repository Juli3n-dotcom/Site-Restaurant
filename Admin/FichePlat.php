<?php
require_once __DIR__ . '/Assets/Config/Init.php';
require_once __DIR__ . '/Assets/Functions/PlatsFunctions.php';

use App\General;
use App\GeneralClass;
use App\Plats;

$plat =  getPlatByID($pdo, $_GET['id'] ?? null);

$page_title = $plat['titre'];
$ua = getBrowser();
$allergenesIdArray = getAllergeneCheck($pdo, $plat['id']);
include __DIR__ . '/Assets/Includes/HeaderAdmin.php';
?>

<div class="notif" id='notif'></div>

<section id="container_plat" class="container_plat">
  <form action="" method="post" enctype="multipart/form-data" id="update_plat">
    <input type="hidden" name="update_id" id="update_id" value="<?= $plat['id']; ?>">

    <div class="container-fluid plat_container" id="plat_container">
      <div class="row">


        <div class="col-sm-9 col-md-8 left_part">

          <div class="mb-3">
            <label for="update_titre" class="form-label">Titre :</label>
            <input type="text" class="form-control" name="update_titre" id="update_titre" placeholder="Votre titre" value="<?= $plat['titre']; ?>">
          </div>

          <div class="mb-3 mt-4">
            <label for="update_prix">Prix : </label>
            <input type="text" class="form-control" name="update_prix" id="update_prix" value="<?= $plat['prix']; ?>">
          </div>

          <div class="mb-3 mt-4">
            <label for="update_description" class="form_label">Description : </label>
            <textarea rows="5" cols="33" name="update_description" id="update_description" class="form-control" placeholder="indiquez la description du plat"><?= $plat['description']; ?></textarea>
          </div>

          <div class="mb-3 mt-4">
            <label for="update_est_epice" class="form_label">Informations alimentaire : </label>
            <div class="plat_container-element">

              <div class="input-block">
                <label for="update_est_epice">S' agit il d'un plat épicé: </label>
                <div>
                  <input type="checkbox" id="update_est_epice" name="update_est_epice" class="est_epice" value="<?= $plat['est_epice']; ?>" <?= ($plat['est_epice'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est épicé.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3 mt-4 update_epicelevel <?= ($plat['est_epice'] ? 'show' : '') ?>" id="update_epicelevel">
                <label for="epicelevel">Niveau d'épices :</label>
                <div class="form-check-container">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="updateepicelevel" id="updateepicelevel1" value="1" <?= ($plat['epice_level'] == 1 ? 'checked' : '') ?>>
                    <label class="form-check-label" for="updateepicelevel">
                      Un peu épicé <?php GeneralClass::getNbPiment(1, $ua['name']) ?>
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="updateepicelevel" id="updateepicelevel2" value="2" <?= ($plat['epice_level'] == 2 ? 'checked' : '') ?>>
                    <label class="form-check-label" for="updateepicelevel">
                      Moyennement épicé <?php GeneralClass::getNbPiment(2, $ua['name']) ?>
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="updateepicelevel" id="updateepicelevel3" value="3" <?= ($plat['epice_level'] == 3 ? 'checked' : '') ?>>
                    <label class="form-check-label" for="epicelevel">
                      Trés épicé <?php GeneralClass::getNbPiment(3, $ua['name']) ?>
                    </label>
                  </div>
                </div>
              </div>

              <div class=" mb-3 mt-4 input-block">
                <label>Votre plat contient t'il des allergénes ? : </label>
                <div>
                  <input type="checkbox" id="update_haveallergene" name="update_haveallergene" value="<?= $plat['have_allergenes']; ?>" <?= ($plat['have_allergenes'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Vous permet de selectionner les allergénes du plat
                    </p>
                  </div>
                </div>
              </div>

              <div class="mb-3 mt-4 update_allergenecontainer <?= ($plat['have_allergenes'] ? 'show' : '') ?>" id="update_allergenecontainer">
                <?php foreach (getAllergenes($pdo) as $allergene) : ?>
                  <div class="form-check allergene-element">
                    <input type="checkbox" value="<?= $allergene["id"] ?>" name="update_allergenes[]" id="update_allergenes" <?= (in_array($allergene['id'], $allergenesIdArray) ? 'checked' : '') ?>>
                    <label class="form-check-label" for="update_allergenes"><?= $allergene['titre'] ?></label>
                    <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                    <div class="input_help">
                      <div>
                        <h6>Description :</h6>
                        <p><?= $allergene['description'] ?></p>
                      </div>
                      <?php if ($allergene['exclusions']) : ?>
                        <div>
                          <h6>Exclusions :</h6>
                          <p><?= $allergene['exclusions'] ?></p>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

            </div>
          </div>

          <div class="mb-3 mt-4">
            <label class="form_label">Régimes alimentaires : </label>
            <div class="plat_container-element" id="regimes_alimentaire">

              <div class=" mb-3  input-block">
                <label for="update_vege">S'agit il d'un plat végétarien: </label>
                <div>
                  <input type="checkbox" id="update_vege" name="update_vege" class="est_vege" value="<?= $plat['est_vege']; ?>" <?= ($plat['est_vege'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est végétarien.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3  input-block">
                <label for="update_vegan">S'agit il d'un plat végan: </label>
                <div>
                  <input type="checkbox" id="update_vegan" name="update_vegan" value="<?= $plat['est_vegan']; ?>" <?= ($plat['est_vegan'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est végan.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3  input-block">
                <label for="update_halal">S'agit il d'un plat halal: </label>
                <div>
                  <input type="checkbox" id="update_halal" name="update_halal" value="<?= $plat['est_halal']; ?>" <?= ($plat['est_halal'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est halal.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3  input-block">
                <label for="update_casher">S'agit il d'un plat casher: </label>
                <div>
                  <input type="checkbox" id="update_casher" name="update_casher" value="<?= $plat['est_casher']; ?>" <?= ($plat['est_casher'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si le plat est cacher.
                    </p>
                  </div>
                </div>
              </div>


            </div>
          </div>

          <div class="mb-3 mt-4 submit_container">
            <button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier le plat</button>
            <div class="load-update hide" id="load-update">
              <img src="Assets/Images/loader.gif" alt="Loader">
              <p>Chargement ...</p>
            </div>
          </div>

        </div> <!--  fin left part-->



        <div class="col-sm-3 col-md-4 right_part">


          <label class="label_container"> Etat et visibilité : </label>
          <div class="mb-3 plat_container-element">

            <div class=" mb-3 mt-4 input-block">
              <label for="update_publie">Afficher le plat: </label>
              <div>
                <input type="checkbox" id="update_publie" name="update_publie" value="<?= $plat['est_publie']; ?>" <?= ($plat['est_publie'] ? 'checked' : '') ?>>
                <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                <div class="input_help">
                  <p>Cocher cette case pour ajouter le plat au menu</p>
                </div>
              </div>
            </div>

            <?php if ($options['show_plat_en_avant']) : ?>
              <div class=" mb-3 mt-4 input-block">
                <label for="update_en_avant">Mettre en avant le plat: </label>
                <div>
                  <input type="checkbox" id="update_en_avant" name="update_en_avant" value="<?= $plat['est_en_avant'] ?>" <?= ($plat['est_en_avant'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Vous permet de promouvoir le plat.
                    </p>
                  </div>
                </div>
              </div>
            <?php endif; ?>


            <div class=" mb-3 mt-4 input-block">
              <label for="update_nouveau">Afficher <span class="new"> nouveau</span> sur le plat: </label>
              <div>
                <input type="checkbox" id="update_nouveau" name="update_nouveau" value="<?= $plat['est_nouveau'] ?>" <?= ($plat['est_nouveau'] ? 'checked' : '') ?>>
                <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                <div class="input_help">
                  <p>
                    vous donner la possibilité d' afficher le logo <span class="new"> nouveau</span>
                    sur le plat.
                  </p>
                </div>
              </div>
            </div>

          </div>

          <label class="label_container">Catégorie: </label>
          <div class="mb-3 plat_container-element">
            <div class=" mb-3 mt-4 input-block text_block">
              <Label>Catégorie :</Label>
              <select class="form-select" name="update_cat" id="update_cat" aria-label="">
                <option value="<?= $plat['cat_id'] ?>" selected><?= Plats::getCat($pdo, $plat['cat_id']) ?></option>
                <?php foreach (getCat($pdo, $plat['cat_id']) as $cat) : ?>
                  <option value="<?= $cat['id'] ?>"><?= $cat['titre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <?php if ($options['show_sous_cat']) : ?>
              <div class=" mb-3 mt-4 input-block text_block">
                <Label>Sous-catégorie :</Label>
                <select class="form-select" name="update_souscat" id="update_souscat" aria-label="">
                  <option value="<?= $plat['souscat_id'] ?>" selected><?= Plats::getSousCat($pdo, $plat['souscat_id']) ?></option>
                  <?php foreach (getSubCat($pdo, $plat['cat_id']) as $subCat) : ?>
                    <option value="<?= $subCat['id'] ?>"><?= $subCat['titre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            <?php endif; ?>
          </div>

          <?php if ($options['show_plat_photo']) : ?>
            <label class="label_container">Photo : </label>
            <div class="mb-3 plat_container-element">
              <input type="hidden" name="update_img" id="update_img" value="<?= $plat['photo_id']; ?>">
              <div class="img-cat-container"><?= Plats::getPlatPhoto($pdo, $plat['photo_id'], $ua['name']); ?></div>
              <input type="file" name="new_img" id="new_img" title="Changez image du plat" placeholder="indiquez la description du plat">
              <label for="new_img">Changer la photo</label>
            </div>
          <?php endif; ?>

          <label class="label_container">Informations : </label>
          <div class="mb-3 plat_container-element">


            <div class=" mb-3 mt-4 input-block text_block">
              <Label>Crée par :</Label>
              <p><?= General::getMembre($pdo, $plat['author_id']) ?></p>
            </div>

            <div class=" mb-3 mt-4 input-block text_block">
              <Label>Crée le :</Label>
              <time><?= date('d-m-Y', strtotime($plat['date_enregistrement']))  ?></time>
            </div>

            <?php if ($plat['date_modification']) : ?>
              <div class=" mb-3 mt-4 input-block text_block">
                <Label>Modifié le :</Label>
                <time><?= date('d-m-Y', strtotime($plat['date_modification']))  ?></time>
              </div>
            <?php endif; ?>

          </div>


          <div class="mb-3 mt-4 submit_container">
            <button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier le plat</button>
            <div class="load-update hide" id="load-update">
              <img src="Assets/Images/loader.gif" alt="Loader">
              <p>Chargement ...</p>
            </div>
          </div>

        </div>

      </div>
    </div>

  </form>
</section>


<?php
include __DIR__ . '/Assets/Includes/FooterAdmin.php';
?>