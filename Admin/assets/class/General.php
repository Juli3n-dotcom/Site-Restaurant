<?php

namespace App;

class General
{
  public static function getMembre($pdo, $id)
  {
    if ($id != NULL) {
      $data = $pdo->query("SELECT * from team WHERE id_team_member = '$id'");
      $member = $data->fetch($pdo::FETCH_ASSOC);

      return $member['username'];
    } else {
      return '';
    }
  }

  public static function notif(string $class, string $message)
  {
    $output = '<div class="notification">';
    $output .= '<div class="card ' . $class . '">';
    $output .= '<div class="content">';
    $output .= '<div class="img"></div>';
    $output .= '<div class="details">';
    $output .= ' <span class="name">' . $class . '</span>';
    $output .= '<p>' . $message . '</p>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<span class="close-btn">';
    $output .= '<i class="fas fa-times"></i>';
    $output .= ' </span>';
    $output .= '</div>';
    $output .= '</div>';


    $output .= '<script>
    $(document).ready(function (){
        setTimeout(function(){
        $(".notification").addClass("hide");
        $(".notification").removeClass("show");
        },3000);
      $(".close-btn").click(function(){
        $(".notification").addClass("hide");
        $(".notification").removeClass("show"); 
      });
      setTimeout(function(){
        $(".notification").remove();
        },5000);
    })</script>';

    return  $output;
  }
}
