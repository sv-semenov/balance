<?php
include_once('db.php');
include_once('model.php');

class AjaxController {
    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function getUsers() {
        $users = $this->userModel->getAllUsers();
        echo json_encode($users);
    }
}


$controller = new AjaxController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'getUsers') {
    $controller->getUsers();
}
?>