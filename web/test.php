<?php 
ini_set('display_errors', TRUE);
error_reporting(E_ALL);

use App\Installer;

require '../src/Installer.php';

Installer::postCreateProject();