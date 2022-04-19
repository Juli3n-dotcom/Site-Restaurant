<?php
require_once __DIR__ . '/Assets/Config/Init.php';


use App\General;
use App\GeneralClass;


$page_title = 'Préférence';
$ua = getBrowser();
$infos = getRestoInfos($pdo);
$optionsResto = getOptionsResto($pdo, $infos['id']);
include __DIR__ . '/Assets/Includes/HeaderAdmin.php';
?>

<div class="notif" id='notif'></div>

<section id="options_settings" class="options_settings">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Général</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">Social</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="regimes-tab" data-toggle="tab" href="#regimes" role="tab" aria-controls="regimes" aria-selected="false">Options Alimentaires</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="infos-tab" data-toggle="tab" href="#infos" role="tab" aria-controls="infos" aria-selected="false">Informations</a>
    </li>
  </ul>


  <!-- Tab panes -->
  <div id="container_settings">
    <form method="post" enctype="multipart/form-data" id="update_settings">
      <input type="hidden" name="update_id" id="update_id" value="<?= $infos['token']; ?>">
      <input type="hidden" name="tab_id" id="tab_id" value="home-tab">

      <div class="tab-content">

        <!-- GENERAL -->
        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div class="mb-3 mt-4">
            <div class="container-fluid settings_container" id="settings_container">
              <div class="row">
                <div class="col-sm-9 col-md-8 left_part">

                  <div class="mb-3">
                    <label for="update_name" class="form-label">Nom de votre restaurant :</label>
                    <input type="text" class="form-control" name="update_name" id="update_name" placeholder="Le nom de votre restaurant" value="<?= $infos['name'] ?>">
                  </div>

                  <div class="mb-3 mt-4">
                    <label class="form_label">Coordonnées : </label>
                    <div class="settings_container-element">
                      <div class="mb-3 mt-4">
                        <label for="update_adresse">Adresse : </label>
                        <input type="text" class="form-control" name="update_adresse" id="update_adresse" placeholder="L'adresse de votre restaurant" value="<?= $infos['adresse'] ?>">
                      </div>
                      <div class="mb-3 mt-4">
                        <label for="update_complements">Compléments d'adresse : </label>
                        <input type="text" class="form-control" name="update_complements" id="update_complements" value="<?= $infos['complements'] ?>">
                      </div>
                      <div class="mb-3 mt-4">
                        <label for="update_cp">Code postal : </label>
                        <input type="text" class="form-control" name="update_cp" id="update_cp" placeholder="Indiquer Code postal de votre restaurant" value="<?= $infos['cp'] ?>">
                      </div>
                      <div class="mb-3 mt-4">
                        <label for="update_city">Ville : </label>
                        <input type="text" class="form-control" name="update_city" id="update_city" placeholder="indiquer la ville de votre restaurant" value="<?= $infos['ville'] ?>">
                      </div>
                      <div class="mb-3 mt-4">
                        <label for="update_email">Email : </label>
                        <input type="email" class="form-control" name="update_email" id="update_email" placeholder="Adresse email principale de votre restaurant" value="<?= $infos['email'] ?>">
                      </div>
                      <div class="mb-3 mt-4">
                        <label for="update_phone">téléphone : </label>
                        <input type="tel" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" class="form-control" name="update_phone" id="update_phone" placeholder="Numéro de téléphone principal de votre restaurant" value="<?= $infos['telephone'] ?>">
                      </div>
                    </div>
                  </div>

                  <div class="mb-3 mt-4">
                    <label for="update_description" class="form_label">Informations Légale : </label>
                    <div class="settings_container-element">
                      <div class="mb-3 mt-4">
                        <label for="update_siret">SIRET : </label>
                        <input type="text" class="form-control" name="update_siret" id="update_siret" placeholder="le numéro de SIRET de votre restaurant" value="<?= $infos['siret'] ?>">
                      </div>
                      <div class="mb-3 mt-4">
                        <label for="update_tva">Numéro de TVA : </label>
                        <input type="text" class="form-control" name="update_tva" id="update_tva" placeholder="le numéro de TVA de votre restaurant" value="<?= $infos['tva'] ?>">
                      </div>
                    </div>
                  </div>


                </div> <!--  fin left part-->

                <div class="col-sm-3 col-md-4 right_part">



                  <label class="label_container">Logo : </label>
                  <div class="mb-3 settings_container-element">
                    <input type="hidden" name="update_img" id="update_img" value="<?= $infos['photo_id']; ?>">
                    <div class="img-cat-container"><?= GeneralClass::getLogo($pdo, $infos['photo_id'], $ua['name']); ?></div>
                    <input type="file" name="new_img" id="new_img" title="Changez image du plat" placeholder="indiquez la description du plat">
                    <label for="new_img">Changer le logo</label>
                  </div>

                </div>

              </div>
            </div>
          </div>
        </div>

        <!--  SOCIAL-->
        <div class="tab-pane" id="social" role="tabpanel" aria-labelledby="social-tab">
          <div class="mb-3 mt-4">
            <div class="rs_label-container">
              <label class="label_container">Réseaux sociaux : </label>
              <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>

              <div class="input_help">
                <p>
                  Copiez l'url de vos profils dans les champs corresponants
                </p>
              </div>
            </div>

            <div class="mb-3 settings_container-element">
              <div class="mb-4  input-block">
                <label for="update_insta"><i class="fa-brands fa-instagram"></i> Instagram : </label>
                <div>
                  <input type="text" class="form-control" id="update_insta" name="update_insta" value="<?= $infos['insta'] ?>">
                </div>
              </div>
              <div class=" mb-3  input-block">
                <label for="update_fb"><i class="fa-brands fa-facebook-square"></i> facebook :</label>
                <div>
                  <input type="text" class="form-control" id="update_fb" name="update_fb" value="<?= $infos['facebook'] ?>">
                </div>
              </div>
              <div class=" mb-3  input-block">
                <label for="update_pinterest"><i class="fa-brands fa-pinterest-square"></i> Pinterest :</label>
                <div>
                  <input type="text" class="form-control" id="update_pinterest" name="update_pinterest" value="<?= $infos['pinterest'] ?>">
                </div>
              </div>
              <div class=" mb-3  input-block">
                <label for="update_twitter"><i class="fa-brands fa-twitter-square"></i> Twitter :</label>
                <div>
                  <input type="text" class="form-control" id="update_twitter" name="update_twitter" value="<?= $infos['twitter'] ?>">
                </div>
              </div>
              <div class=" mb-3  input-block">
                <label for="update_snap"><i class="fa-brands fa-snapchat-square"></i> Snapchat :</label>
                <div>
                  <input type="text" class="form-control" id="update_snap" name="update_snap" value="<?= $infos['snap'] ?>">
                </div>
              </div>
              <div class=" mb-3  input-block">
                <label for="update_tiktok"><i class="fa-brands fa-tiktok"></i> TikTok :</label>
                <div>
                  <input type="text" class="form-control" id="update_tiktok" name="update_tiktok" value="<?= $infos['tiktok'] ?>">
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- REGIME ALIMENTAIRE -->
        <div class="tab-pane" id="regimes" role="tabpanel" aria-labelledby="regimes-tab">
          <div class="mb-3 mt-4">
            <label for="update_est_epice" class="form_label">Informations alimentaire : </label>
            <div class="settings_container-element">
              <div class="input-block">
                <label for="update_est_epice">Votre restaurant propose t'il des spécialités épicées: </label>
                <div>
                  <input type="checkbox" id="update_est_epice" name="update_est_epice" class="est_epice" value="<?= $optionsResto['est_epice']; ?>" <?= ($optionsResto['est_epice'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si votre restaurant propose des plats épicée.
                    </p>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="mb-3 mt-4">
            <label class="form_label">Régimes alimentaires : </label>
            <div class="settings_container-element" id="regimes_alimentaire">

              <div class=" mb-3  input-block">
                <label for="update_vege">Spécialités végétarienne: </label>
                <div>
                  <input type="checkbox" id="update_vege" name="update_vege" class="est_vege" value="<?= $optionsResto['est_vege']; ?>" <?= ($optionsResto['est_vege'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si votre restaurant propose des spécialités végétarienne.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3  input-block">
                <label for="update_vegan">Spécialités végan: </label>
                <div>
                  <input type="checkbox" id="update_vegan" name="update_vegan" value="<?= $optionsResto['est_vegan']; ?>" <?= ($optionsResto['est_vegan'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si votre restaurant propose des spécialités vegan.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3  input-block">
                <label for="update_halal">Spécialités halal: </label>
                <div>
                  <input type="checkbox" id="update_halal" name="update_halal" value="<?= $optionsResto['est_halal']; ?>" <?= ($optionsResto['est_halal'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si votre restaurant propose des spécialités halal.
                    </p>
                  </div>
                </div>
              </div>

              <div class=" mb-3  input-block">
                <label for="update_casher">Spécialités casher: </label>
                <div>
                  <input type="checkbox" id="update_casher" name="update_casher" value="<?= $optionsResto['est_casher']; ?>" <?= ($optionsResto['est_casher'] ? 'checked' : '') ?>>
                  <span class="show_helper"><i class="fa-solid fa-circle-question"></i></span>
                  <div class="input_help">
                    <p>
                      Cocher cette case si votre restaurant propose des spécialités casher
                    </p>
                  </div>
                </div>
              </div>


            </div>
          </div>
        </div>

        <!-- INFORMATIONS -->
        <div class="tab-pane" id="infos" role="tabpanel" aria-labelledby="infos-tab">
          <div class="mb-3 mt-4">
            <label class="label_container">Informations : </label>
            <div class="mb-3 settings_container-element">


              <div class=" mb-3 mt-4 input-block text_block">
                <Label>Crée par :</Label>
                <p><?= General::getMembre($pdo, $infos['author_id']) ?></p>
              </div>

              <div class=" mb-3 mt-4 input-block text_block">
                <Label>Crée le :</Label>
                <time><?= date('d-m-Y', strtotime($infos['date_enregistrement']))  ?></time>
              </div>

              <?php if ($infos['date_modification']) : ?>
                <div class=" mb-3 mt-4 input-block text_block">
                  <Label>Modifié le :</Label>
                  <time><?= date('d-m-Y', strtotime($infos['date_modification']))  ?></time>
                </div>
              <?php endif; ?>

            </div>
          </div>
        </div>

      </div>

      <div class="mb-3 mt-4 submit_container">
        <button type="submit" name="update_plat" id="UpdatePlatBtn" class="updateBtn">Modifier les informations</button>
        <div class="load-update hide" id="load-update">
          <img src="Assets/Images/loader.gif" alt="Loader">
          <p>Chargement ...</p>
        </div>
      </div>

    </form>
  </div>
</section>

<?php
include __DIR__ . '/Assets/Includes/FooterAdmin.php';
?>