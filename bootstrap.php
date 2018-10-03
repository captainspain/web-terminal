<?php

require_once __DIR__ . '/vendor/autoload.php';

session_start();

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
