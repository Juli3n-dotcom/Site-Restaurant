<?php

namespace App;

class History
{

  public static function getStatut($statut)
  {
    if ($statut == 0) {
      return "<td class='history_statut add'><div class='add history_txt'><i class='fas fa-plus'></i> SUCCESS</div></td>";
    } elseif ($statut == 1) {
      return "<td class='history_statut update'><div class='update history_txt'><i class='fas fa-edit'></i> UPDATE</div></td>";
    } elseif ($statut == 2) {
      return "<td class='history_statut delete'><div class='delete history_txt'><i class='fas fa-trash-alt'></i> DELETE</div></td>";
    } elseif ($statut == 3) {
      return "<td class='history_statut info'><div class='info history_txt'><i class='fas fa-info'></i> INFO</div></td>";
    } elseif ($statut == 4) {
      return "<td class='history_statut error'><div class='error history_txt'> <i class='fas fa-times'></i> ERROR</div></td>";
    } elseif ($statut == 5) {
      return "<td class='history_statut warning'><div class='warning history_txt'> <i class='fas fa-exclamation-triangle'></i> WARNING</div></td>";
    }
  }
}
