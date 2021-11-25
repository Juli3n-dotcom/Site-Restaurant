<?php
require_once __DIR__ . '/../../../global/config/Bootstrap.php';

require_once __DIR__ . '/FunctionsGlobal.php';

require_once __DIR__ . '/../../vendor/autoload.php';

require_once __DIR__ . '/../class/Notifications.php';

require_once __DIR__ . '/../class/Team.php';


//  require_once __DIR__ . '/../functions/auth.php';

 

// if(!stripos($_SERVER['REQUEST_URI'], 'connexion') && !stripos($_SERVER['REQUEST_URI'], 'login_member.php')){
//     $Membre = getMembre($pdo, $_GET['id_team_member'] ?? null);

//     if($Membre === null){
//         header('Location: connexion');
//         exit();
//         }
// }