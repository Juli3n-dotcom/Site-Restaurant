<?php

namespace App;

class Team
{

  public static function getProfil($pdo, $photo_id, $civilite)
  {

    if ($photo_id == NULL) {
      if ($civilite == 0) {
        return "<div class='img-profil' style='background-image: url(Assets/Images/male.svg)'></div>";
      } elseif ($civilite == 1) {
        return "<div class='img-profil' style='background-image: url(Assets/Images/female.svg)'></div>";
      } else {
        return "<div class='img-profil' style='background-image: url(Assets/Images/profil.svg)'></div>";
      }
    } else {
      $data = $pdo->query("SELECT * from team_photo WHERE id_photo = '$photo_id'");
      $photo = $data->fetch($pdo::FETCH_ASSOC);

      return "<div class='img-profil' style='background-image: url(Assets/Uploads/" . $photo . " )'></div>";
    }
  }

  public static function getPhoto($pdo, $photo_id, $civilite)
  {

    if ($photo_id == NULL) {
      if ($civilite == 0) {
        return "Assets/Images/male.svg";
      } elseif ($civilite == 1) {
        return "Assets/Images/female.svg";
      } else {
        return "Assets/Images/profil.svg";
      }
    } else {
      $data = $pdo->query("SELECT * from team_photo WHERE id_photo = '$photo_id'");
      $photo = $data->fetch($pdo::FETCH_ASSOC);

      return "Assets/Uploads/" . $photo . "";
    }
  }

  public static function getPoste($statut)
  {
    if ($statut == 0) {
      return 'Admin';
    } else if ($statut == 1) {
      return 'Gérant';
    } else {
      return 'Editeur';
    }
  }

  public static function getStatut($statut)
  {
    if ($statut == 0) {
      return '<p class="badge admin">Admin</p>';
    } else if ($statut == 1) {
      return '<p class="badge user">Gérant</p>';
    } else {
      return '<p class="badge editer">Editeur</p>';
    }
  }

  public static function getConfirmation($confirmation)
  {
    if ($confirmation == 0) {
      return '<p class="badge danger confirmation">Non</p>';
    } else {
      return '<p class="badge success confirmation">Oui</p>';
    }
  }


  public static function getCivilite($civilite)
  {
    if ($civilite == 0) {
      return 'Monsieur';
    } elseif ($civilite == 1) {
      return 'Madame';
    } else {
      return 'Autre';
    }
  }
}
