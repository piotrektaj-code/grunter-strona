<?php
require_once __DIR__.'/config.php';
function db(): PDO {
 static $pdo=null;
 if (!$pdo) {
  $pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[
   PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
   PDO::ATTR_EMULATE_PREPARES=>false
  ]);
 }
 return $pdo;
}
function h($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function csrf_token(){if(session_status()!==PHP_SESSION_ACTIVE)session_start();if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function csrf_check(){if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(403);exit('Nieprawidłowy token formularza.');}}
