<?php
function getDbConnection()
{
  if (!class_exists('PDO')) {
    return null;
  }

  $host = 'localhost';
  $database = 'tyt';
  $username = 'root';
  $password = '';

  $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";

  $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

  try {
    return new PDO($dsn, $username, $password, $options);
  } catch (Throwable $exception) {
    if (!class_exists('mysqli')) {
      return null;
    }

    mysqli_report(MYSQLI_REPORT_OFF);
    $setupCheck = new mysqli($host, $username, $password);

    if ($setupCheck->connect_error) {
      return null;
    }

    $setupCheck->close();

    ob_start();
    include __DIR__ . '/Backend/DatabaseSetup.php';
    include __DIR__ . '/Backend/InsertTests.php';
    ob_end_clean();

    try {
      return new PDO($dsn, $username, $password, $options);
    } catch (Throwable $retryException) {
      return null;
    }

    return null;
  }
}
