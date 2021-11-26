<?php

namespace App;

class History
{

  public static function getStatut($statut)
  {
    if ($statut == 0) {
      return "<td class='history_statut add'><div class='add'><i class='fas fa-plus'></i> ADD</div></td>";
    } elseif ($statut == 1) {
      return "<td class='history_statut update'><div class='update'><i class='fas fa-edit'></i> UPDATE</div></td>";
    } elseif ($statut == 2) {
      return "<td class='history_statut delete'><div class='delete'><i class='fas fa-trash-alt'></i> DELETE</div></td>";
    } elseif ($statut == 3) {
      return "<td class='history_statut info'><div class='info'><i class='fas fa-info'></i> INFO</div></td>";
    } elseif ($statut == 4) {
      return "<td class='history_statut error'><div class='error'> <i class='fas fa-times'></i> ERROR</div></td>";
    } elseif ($statut == 5) {
      return "<td class='history_statut warning'><div class='warning'> <i class='fas fa-exclamation-triangle'></i> WARNING</div></td>";
    }
  }

  public static function getMembre($pdo, $id)
  {
    $data = $pdo->query("SELECT * from team WHERE id_team_member = '$id'");
    $member = $data->fetch($pdo::FETCH_ASSOC);

    return $member['username'];
  }
}
