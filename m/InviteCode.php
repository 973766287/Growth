<?php
require_once('load.php');


if (isset($_REQUEST['action'])) {
    switch (trim($_REQUEST['action'])) {
        case 'activation':
            $app->action('supplier', 'ajax_activation', $_POST);
            break;
	
    }
    exit;
}


    $app->action('supplier', 'InviteCodeActivation');

?>