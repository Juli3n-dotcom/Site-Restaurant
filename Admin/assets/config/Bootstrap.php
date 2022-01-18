<?php
require_once __DIR__ . '/../../../Global/Config/Bootstrap.php';

require_once __DIR__ . '/FunctionsGlobal.php';

require_once __DIR__ . '/../../../vendor/autoload.php';

require_once __DIR__ . '/../Functions/Auth.php';

require_once __DIR__ . '/../Class/Notifications.php';

require_once __DIR__ . '/../Class/Team.php';

require_once __DIR__ . '/../Class/History.php';

if (!stripos($_SERVER['REQUEST_URI'], 'connexion') && !stripos($_SERVER['REQUEST_URI'], 'Login.php') && !stripos($_SERVER['REQUEST_URI'], 'LostPassWord.php')) {

  if ($_COOKIE['SESSION']) {
    $token = htmlspecialchars($_COOKIE['SESSION']);
    $user = getUser($pdo, $token);
    $_SESSION['team'] = $user;
  } else {
    header('Location: Login.php');
  }
}
