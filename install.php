<?php
require __DIR__.'/db.php';
session_start();
$msg='';
try {
 db()->exec("CREATE TABLE IF NOT EXISTS admins (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(80) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
 db()->exec("CREATE TABLE IF NOT EXISTS properties (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(180) NOT NULL, location VARCHAR(180) NOT NULL, area DECIMAL(8,2) NULL, plot_area VARCHAR(80) NULL, rooms INT NULL, price VARCHAR(80) NULL, status ENUM('Dostępny','Zarezerwowany','Sprzedany') NOT NULL DEFAULT 'Dostępny', description TEXT, image_url VARCHAR(500), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
 db()->exec("CREATE TABLE IF NOT EXISTS inquiries (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(120) NOT NULL, phone VARCHAR(60) NOT NULL, email VARCHAR(180), message TEXT NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
 if($_SERVER['REQUEST_METHOD']==='POST'){
  if(count(db()->query('SELECT id FROM admins')->fetchAll())>0) throw new Exception('Administrator został już utworzony. Usuń install.php z serwera.');
  $u=trim($_POST['username']??'');$p=$_POST['password']??'';
  if(strlen($u)<4 || strlen($p)<12) throw new Exception('Login min. 4 znaki, hasło min. 12 znaków.');
  $st=db()->prepare('INSERT INTO admins(username,password_hash) VALUES(?,?)');$st->execute([$u,password_hash($p,PASSWORD_DEFAULT)]);
  $msg='Konto administratora utworzone. Usuń install.php z serwera i przejdź do admin.php.';
 }
} catch(Throwable $e){$msg=$e->getMessage();}
?><!doctype html><html lang="pl"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Instalacja GRUNTER</title><link rel="stylesheet" href="style.css"><body class="install"><main class="panel"><h1>Instalacja GRUNTER</h1><p><?=h($msg)?></p><?php if(!count(db()->query('SELECT id FROM admins')->fetchAll())): ?><form method="post"><label>Login administratora<input name="username" required minlength="4"></label><label>Hasło (minimum 12 znaków)<input type="password" name="password" required minlength="12"></label><button>Utwórz konto</button></form><?php endif;?></main></body></html>
