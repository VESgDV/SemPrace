<?php

$request = trim($_SERVER['REQUEST_URI'], '/');


switch ($request) {
    case 'login':
        header('Location: ../php/login.php');
        break;
    case 'profil':
        header('Location: ../php/profil.php');
        break;
    case 'resume':
        header('Location: ../php/resume.php');
        break;
    case 'zpravy':
        header('Location: ../php/zpravy.php');
        break;
    case 'admin_panel':
        header('Location: ../php/admin_panel.php');
        break;
    case 'logout':
        header('Location: ../php/logout.php');
        break;
    case 'registrace':
        header('Location: ../php/registrace.php');
        break;
    case 'login_process':
        header('Location: ../back/login_process.php');
        break;
    case 'send_message':
        header('Location: ../back/send_message.php');
        break;
    case 'view_message':
        header('Location: ../php/view_message.php');
        break;
    case 'update_profile':
        header('Location: ../back/update_profile.php');
        break;   
    case 'edit_user':
        header('Location: ../back/edit_user.php');
        break;     
    case 'change_password':
        header('Location: ../php/change_password.php');
        break;
    case 'submit_password_change':
        header('Location: ../back/submit_password_change.php');
        break;
    case 'submit_registration':
        header('Location: ../back/submit_registration.php');
        break;                        
    default:
        header('Location: ../php/index.php');
        break;
}
exit;
