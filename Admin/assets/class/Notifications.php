<?php

namespace App;

class Notifications
{

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
