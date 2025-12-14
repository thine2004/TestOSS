<?php
require_once 'config.php';
session_destroy();
session_start();
setFlash('success', 'You have been logged out.');
redirect('login.php');
