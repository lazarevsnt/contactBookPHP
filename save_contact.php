<?php
require_once __DIR__ . '/admin/includes/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}