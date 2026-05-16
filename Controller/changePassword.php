<?php
require_once __DIR__ . '/useController.php';
$controller = new useController();
$controller->changePassword($_POST);
