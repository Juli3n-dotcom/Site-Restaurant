<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= 'BackOffice' ?> | <?= 'Fair' ?></title>
  <link rel="icon" href="assets/images/dashboard.svg">
  <link rel="apple-touch-icon" href="assets/images/dashboard.svg">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

  <!-- other css -->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.min.css">
  <script src="https://kit.fontawesome.com/3760b9e264.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <!-- CSS -->

  <link href="assets/style/style.css" rel="stylesheet" type="text/css">

</head>

<body>

  <section class="sidebar close">

    <div class="logo_content">
      <div class="logo">
        <i class='bx bxs-dashboard'></i>
        <div class="logo_name">Dashboard</div>
      </div>
    </div>
    <i class='bx bx-menu' id="header_btn"></i>

    <ul class="nav-links">

      <li>
        <a href="#">
          <i class="fas fa-home"></i>
          <span class="link_name">Accueil</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="index.php">Accueil</a></li>
        </ul>
      </li>
      <li>
        <div class="icon-link">
          <a href="#">
            <i class="fas fa-tachometer-alt"></i>
            <span class="link_name">Dashboard</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Dashboard</a></li>
          <li><a href="#">Visites</a></li>
          <li><a href="#">Visites</a></li>
          <li><a href="#">Visites</a></li>
        </ul>
      </li>
      <li>
        <div class="icon-link">
          <a href="#">
            <i class='bx bx-book-alt'></i>
            <span class="link_name">Posts</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Posts</a></li>
          <li><a href="#">Web Design</a></li>
          <li><a href="#">Login Form</a></li>
          <li><a href="#">Card Design</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-pie-chart-alt-2'></i>
          <span class="link_name">Analytics</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Analytics</a></li>
        </ul>
      </li>
      <li>
        <a href="Team.php">
          <i class="fas fa-users"></i>
          <span class="link_name">Team</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="Team.php">Team</a></li>
        </ul>
      </li>

      <li>
        <a href="#">
          <i class='bx bx-compass'></i>
          <span class="link_name">Explore</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Explore</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-history'></i>
          <span class="link_name">History</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">History</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class="fas fa-cog"></i>
          <span class="link_name">Setting</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Setting</a></li>
        </ul>
      </li>

    </ul>

    <div class="profile_content">
      <div class="profile">
        <div class="profile_details">
          <img src="assets/uploads/profil.png" alt="">
          <div class="name_job">
            <div class="name">Ju QUENTIER</div>
            <div class="job">CEO</div>
          </div>
        </div>
        <i class="fa fa-sign-out" aria-hidden="true" id="log_out"></i>
      </div>
    </div>

  </section>

  <header>
    <div class="search__wrapper">

    </div>
    <div class="social-icons">
      <span class="ti-bell"></span>
      <span class="ti-comment"></span>
      <!-- Menu USER -->
      <div class="member_menu-action">
        <div class="profile" onclick="menuTeamToggle();">
          <img src='assets/images/profil.svg' alt='photo_profil_other'>
        </div>
        <div class="member_menu">
          <!-- <h3><?= $Membre['prenom'] ?> <?= $Membre['nom'] ?></h3> -->
          <h3>Julien QUENTIER</h3>
          <ul>
            <li>
              <i class='bx bxs-user-circle'></i>
              <a href="my-profil"> Mon Profil</a>
            </li>
            <li>
              <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
              <a href="update-profil">Modifier Profil</a>
            </li>
            <li>
              <i class="fa fa-info-circle" aria-hidden="true"></i>
              <a href="#"> Help</a>
            </li>
            <li>
              <i class="fa fa-sign-out" aria-hidden="true"></i>
              <a href="assets/scripts/login/logout.php"> Déconnexion</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <main id="main">
    <h1 class="page__title"><?= $page_title ?></h1>