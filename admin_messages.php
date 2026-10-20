<?php
session_start();

if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}

require_once __DIR__ . '/db.php';

$db = getDbConnection();
$messages = [];
$statusMessage = '';
$statusClass = '';
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

if ($db) {
  try {
    $db->exec(
      'CREATE TABLE IF NOT EXISTS contact_messages (
        message_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NULL,
        user_role VARCHAR(50) NOT NULL DEFAULT "Visitor",
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN NOT NULL DEFAULT FALSE,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
      )'
    );
  } catch (Throwable $exception) {
    $db = null;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $db) {
  $messageId = isset($_POST['message_id']) ? (int) $_POST['message_id'] : 0;
  $isRead = isset($_POST['is_read']) ? (int) $_POST['is_read'] : 0;

  if ($messageId > 0) {
    try {
      $stmt = $db->prepare(
        'UPDATE contact_messages
         SET is_read = :is_read
         WHERE message_id = :message_id'
      );
      $stmt->execute([
        ':is_read' => $isRead,
        ':message_id' => $messageId,
      ]);

      $statusMessage = 'Message status updated.';
      $statusClass = 'success';
    } catch (Throwable $exception) {
      $statusMessage = 'Unable to update this message.';
      $statusClass = 'error';
    }
  }
}

if ($db) {
  try {
    $messages = $db->query(
      'SELECT message_id, full_name, email, phone, user_role, subject, message, is_read, created_at
       FROM contact_messages
       ORDER BY created_at DESC, message_id DESC'
    )->fetchAll();
  } catch (Throwable $exception) {
    $statusMessage = 'Database is not ready yet. Please check the contact_messages table.';
    $statusClass = 'error';
  }
} else {
  $statusMessage = 'Database connection failed. Please check db.php and XAMPP MySQL.';
  $statusClass = 'error';
}

$site = [
  'name' => 'TrustYourTrades',
  'tagline' => 'Trust the Work. Trust the Trade.',
  'logo' => './Images/logo.png',
];

$navItems = [
  ['label' => 'Home', 'href' => 'home.php', 'active' => false],
  ['label' => 'About', 'href' => 'about.php', 'active' => false],
  ['label' => 'Services', 'href' => 'search.php', 'active' => false],
  ['label' => 'Contact', 'href' => 'contact.php', 'active' => false],
  ['label' => 'Profile', 'href' => './Backend/profile.php', 'active' => false],
  $isLoggedIn
    ? ['label' => 'Logout', 'href' => './Backend/logout.php', 'active' => false]
    : ['label' => 'Login', 'href' => 'login.php', 'active' => false],
  ['label' => 'Admin Messages', 'href' => 'admin_messages.php', 'active' => true],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($site['name']) ?> | Admin Messages</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./Stylesheets/contact.css" />
</head>

<body>
  <header class="site-header">
    <div class="top-bar"></div>
    <div class="header-inner container">
      <a class="brand" href="about.php">
        <img src="<?= e($site['logo']) ?>" alt="<?= e($site['name']) ?> logo" class="logo" />
        <div>
          <span class="brand-name"><?= e($site['name']) ?></span>
          <span class="brand-tagline"><?= e($site['tagline']) ?></span>
        </div>
      </a>

      <button class="menu-toggle" aria-label="Open navigation">☰</button>

      <nav class="main-nav">
        <?php foreach ($navItems as $item): ?>
          <a href="<?= e($item['href']) ?>" class="<?= $item['active'] ? 'active' : '' ?>"><?= e($item['label']) ?></a>
        <?php endforeach; ?>
      </nav>
    </div>
  </header>

  <main>
    <section class="page-hero container reveal slim-hero">
      <div class="page-hero__text">
        <p class="eyebrow">ADMIN PAGE</p>
        <h1>Contact Messages</h1>
        <p>Review messages submitted from the TrustYourTrades contact form.</p>
      </div>
    </section>

    <section class="section container reveal">
      <article class="form-card wide-card">
        <div class="section-heading left-align">
          <p class="eyebrow">MESSAGE INBOX</p>
          <h2><?= count($messages) ?> Submitted Message<?= count($messages) === 1 ? '' : 's' ?></h2>
        </div>

        <?php if ($statusMessage): ?>
          <p class="form-message <?= e($statusClass) ?>"><?= e($statusMessage) ?></p>
        <?php endif; ?>

        <?php if (!$messages): ?>
          <p class="empty-state">No contact messages have been submitted yet.</p>
        <?php endif; ?>

        <div class="message-list">
          <?php foreach ($messages as $message): ?>
            <article class="message-card <?= $message['is_read'] ? 'is-read' : 'is-unread' ?>">
              <div class="message-card__header">
                <div>
                  <p class="eyebrow"><?= $message['is_read'] ? 'READ' : 'NEW MESSAGE' ?></p>
                  <h3><?= e($message['subject']) ?></h3>
                </div>
                <form method="post" action="admin_messages.php">
                  <input type="hidden" name="message_id" value="<?= e($message['message_id']) ?>">
                  <input type="hidden" name="is_read" value="<?= $message['is_read'] ? '0' : '1' ?>">
                  <button type="submit" class="btn btn-secondary">
                    <?= $message['is_read'] ? 'Mark as Unread' : 'Mark as Read' ?>
                  </button>
                </form>
              </div>

              <dl class="message-meta">
                <div>
                  <dt>Name</dt>
                  <dd><?= e($message['full_name']) ?></dd>
                </div>
                <div>
                  <dt>Email</dt>
                  <dd><a href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a></dd>
                </div>
                <div>
                  <dt>Phone</dt>
                  <dd><?= e($message['phone'] ?: 'Not provided') ?></dd>
                </div>
                <div>
                  <dt>Role</dt>
                  <dd><?= e($message['user_role']) ?></dd>
                </div>
                <div>
                  <dt>Submitted</dt>
                  <dd><?= e($message['created_at']) ?></dd>
                </div>
              </dl>

              <p class="message-body"><?= nl2br(e($message['message'])) ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </article>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <p>CS 268 · TrustYourTrades · Admin Message Review</p>
      <p>Contact form submissions displayed from the MySQL database.</p>
    </div>
  </footer>

  <script src="script.js"></script>
</body>

</html>
