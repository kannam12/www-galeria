<?php
    session_start();

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    define("IN_INDEX", 1);

    //dołączanie pliku dostępu do bazy
    include("config.inc.php");
    //łączenie z bazą danych
    if (isset($config) && is_array($config)) {

        try {
            $dbh = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4', $config['db_user'], $config['db_password']);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Nie mozna polaczyc sie z baza danych: " . $e->getMessage();
            exit();
        }
    } else {
        exit("Nie znaleziono konfiguracji bazy danych.");
    }
    //dołączenie preg-replace
    include("functions.inc.php");

    //wyświetlanie różnych dozwolonych podstron    
    $allowed_pages = ['main', 'add', 'description', 'categories', 'edit'];

    if (isset($_GET['page']) && $_GET['page'] && in_array($_GET['page'], $allowed_pages)) {
        if (file_exists($_GET['page'] . '.php')) {
            include($_GET['page'] . '.php');
        } else {
            print 'Plik ' . $_GET['page'] . '.php nie istnieje.';
        }
    } else {
        include('main.php');
    }
?>

<!DOCTYPE html>
<html>
  <head>

    <meta charset="utf-8">
    <title>Some Nice Pics</title>

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- ładowanie biblioteki jQuery z serwera CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>        
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	
    <!-- właściwe renderowanie Bootstrapa na urz. mobilnych -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 

  </head>

  <body>
    <!-- układ paska nawigacyjnego -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="https://scontent-waw1-1.xx.fbcdn.net/v/t1.15752-9/70781505_654279881759757_2301926926752677888_n.png?_nc_cat=102&_nc_sid=b96e70&_nc_ohc=tOVLMxPIgA8AX9QLEq0&_nc_ht=scontent-waw1-1.xx&oh=23162c92ce18976b9f7bb6c86f87b9a1&oe=5EF3E374" width="30" height="30" class="d-inline-block align-top" alt="">
          Some Nice Pics</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav" id="menu-buttons">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Strona główna</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/categories">Kategorie</a>
            </li>
			      <li class="nav-item">
              <a class="nav-link" href="/add">Dodaj zdjęcie</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/description">Instrukcja</a>
            </li>
          </ul>
        </div>
      </div>
    </nav> 
    <!-- układ stopki --> 
    <div class="stopka">
      <footer class="footer">
        <div class="container">
          <span class="text-muted">Aktualna data: <?php print date('Y-m-d'); ?> </span>
          <a class="footer-signature">By KA</a>
        </div>
      </footer>
    </div>
  </body>
</html>