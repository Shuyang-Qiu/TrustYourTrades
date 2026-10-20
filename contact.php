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
$formStatus = '';
$formStatusClass = '';
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

$site = [
  'name' => 'TrustYourTrades',
  'tagline' => 'Trust the Work. Trust the Trade.',
  'logo' => './Images/logo.png',
  'footer' => [
    'CS 268 · TrustYourTrades · Front-End Progress Submission',
    'Contact page completed with HTML, CSS, and JavaScript interactions.',
  ],
];

$navItems = [
  ['label' => 'Home', 'href' => 'home.php', 'active' => false],
  ['label' => 'About', 'href' => 'about.php', 'active' => false],
  ['label' => 'Services', 'href' => 'search.php', 'active' => false],
  ['label' => 'Contact', 'href' => 'contact.php', 'active' => true],
  ['label' => 'Profile', 'href' => './Backend/profile.php', 'active' => false],
  $isLoggedIn
    ? ['label' => 'Logout', 'href' => './Backend/logout.php', 'active' => false]
    : ['label' => 'Login', 'href' => 'login.php', 'active' => false],
];

$hero = [
  'eyebrow' => 'CONTACT PAGE',
  'title' => 'Questions, technical issues, or feedback?',
  'body' => 'This page gives users a clear way to contact support and send comments through a front-end contact form.',
  'image' => './Images/contact-support.webp',
  'image_alt' => 'Customer support representative',
];

$supportInfo = [
  'title' => 'Technical Support',
  'details' => [
    'Email' => 'support@trustyourtrades.com',
    'Phone' => '(800) 555-0148',
    'Hours' => 'Monday to Friday, 8:00 AM – 5:00 PM',
  ],
  'body' => 'Users can contact support for login issues, page errors, account questions, or contractor/client profile problems.',
];

$benefits = [
  'Verified and trusted contractors',
  'Easy service search and booking',
  'Transparent pricing and reviews',
  'Secure and reliable platform',
  'Dedicated customer support',
];

$formFields = [
  [
    'label' => 'Full Name *',
    'type' => 'text',
    'id' => 'name',
    'name' => 'name',
    'placeholder' => 'Enter your name',
    'required' => true,
  ],
  [
    'label' => 'Email *',
    'type' => 'email',
    'id' => 'email',
    'name' => 'email',
    'placeholder' => 'Enter your email',
    'required' => true,
  ],
  [
    'label' => 'Phone Number',
    'type' => 'tel',
    'id' => 'phone',
    'name' => 'phone',
    'placeholder' => 'Optional',
    'required' => false,
  ],
];

$roleOptions = ['Client', 'Contractor', 'Visitor'];
$contactFormAction = $_SERVER['PHP_SELF'];

$faqItems = [
  [
    'question' => 'How do I become a contractor on the site?',
    'answer' => 'Create an account, choose the contractor role, and complete your profile details.',
    'active' => true,
  ],
  [
    'question' => 'Can clients leave reviews?',
    'answer' => 'Yes. The front-end includes a contractor review section that displays client feedback.',
    'active' => false,
  ],
  [
    'question' => 'Does this page connect to a database yet?',
    'answer' => 'Not yet. This assignment focuses on the front-end. Database work is the next project stage.',
    'active' => false,
  ],
];

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

    $db->exec(
      'CREATE TABLE IF NOT EXISTS faqs (
        faq_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        question VARCHAR(255) NOT NULL,
        answer TEXT NOT NULL,
        display_order INT NOT NULL DEFAULT 0,
        is_active BOOLEAN NOT NULL DEFAULT TRUE
      )'
    );
  } catch (Throwable $exception) {
    $db = null;
  }
}

if ($db) {
  try {
    $faqRows = $db->query(
      'SELECT question, answer FROM faqs WHERE is_active = TRUE ORDER BY display_order, faq_id'
    )->fetchAll();

    if ($faqRows) {
      $faqItems = array_map(function ($row, $index) {
        return [
          'question' => $row['question'],
          'answer' => $row['answer'],
          'active' => $index === 0,
        ];
      }, $faqRows, array_keys($faqRows));
    }
  } catch (Throwable $exception) {
    $db = null;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullName = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $role = trim($_POST['role'] ?? 'Visitor');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if ($fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $subject === '' || $message === '') {
    $formStatus = 'Please complete the required fields before submitting.';
    $formStatusClass = 'error';
  } elseif (!$db) {
    $formStatus = 'Database is not ready yet. Please import the SQL files first.';
    $formStatusClass = 'error';
  } else {
    try {
      $stmt = $db->prepare(
        'INSERT INTO contact_messages (full_name, email, phone, user_role, subject, message)
         VALUES (:full_name, :email, :phone, :user_role, :subject, :message)'
      );

      $stmt->execute([
        ':full_name' => $fullName,
        ':email' => $email,
        ':phone' => $phone !== '' ? $phone : null,
        ':user_role' => in_array($role, $roleOptions, true) ? $role : 'Visitor',
        ':subject' => $subject,
        ':message' => $message,
      ]);

      $formStatus = 'Your message was submitted successfully.';
      $formStatusClass = 'success';
    } catch (Throwable $exception) {
      $formStatus = 'Database is not ready yet. Please import the SQL files first.';
      $formStatusClass = 'error';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($site['name']) ?> | Contact</title>
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
        <p class="eyebrow"><?= e($hero['eyebrow']) ?></p>
        <h1><?= e($hero['title']) ?></h1>
        <p><?= e($hero['body']) ?></p>
      </div>
      <div class="page-hero__visual">
        <img src="<?= e($hero['image']) ?>" alt="<?= e($hero['image_alt']) ?>" />
      </div>
    </section>

    <section class="section container reveal">
      <div class="card-grid two-col align-start">
        <article class="feature-card">
          <h3><?= e($supportInfo['title']) ?></h3>
          <ul class="plain-list">
            <?php foreach ($supportInfo['details'] as $label => $value): ?>
              <li><strong><?= e($label) ?>:</strong> <?= e($value) ?></li>
            <?php endforeach; ?>
          </ul>
          <p><?= e($supportInfo['body']) ?></p>
        </article>

        <article class="feature-card">
          <h3>Why Choose <?= e($site['name']) ?></h3>
          <ul class="check-list">
            <?php foreach ($benefits as $benefit): ?>
              <li>✔ <?= e($benefit) ?></li>
            <?php endforeach; ?>
          </ul>
        </article>
      </div>
    </section>

    <section class="section container reveal">
      <div class="contact-layout">
        <article class="form-card wide-card">
          <div class="section-heading left-align">
            <p class="eyebrow">SEND A MESSAGE</p>
            <h2>Contact Form</h2>
          </div>

          <form id="contactForm" method="post" action="<?= e($contactFormAction) ?>" novalidate>
            <div class="form-grid">
              <?php foreach (array_slice($formFields, 0, 2) as $field): ?>
                <div class="form-group">
                  <label for="<?= e($field['id']) ?>"><?= e($field['label']) ?></label>
                  <input
                    type="<?= e($field['type']) ?>"
                    id="<?= e($field['id']) ?>"
                    name="<?= e($field['name']) ?>"
                    placeholder="<?= e($field['placeholder']) ?>"
                    <?= $field['required'] ? 'required' : '' ?>>
                  <small class="error-text"></small>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="form-grid">
              <div class="form-group">
                <?php $phoneField = $formFields[2]; ?>
                <label for="<?= e($phoneField['id']) ?>"><?= e($phoneField['label']) ?></label>
                <input
                  type="<?= e($phoneField['type']) ?>"
                  id="<?= e($phoneField['id']) ?>"
                  name="<?= e($phoneField['name']) ?>"
                  placeholder="<?= e($phoneField['placeholder']) ?>">
                <small class="error-text"></small>
              </div>

              <div class="form-group">
                <label for="role">I am a</label>
                <select id="role" name="role">
                  <?php foreach ($roleOptions as $role): ?>
                    <option value="<?= e($role) ?>"><?= e($role) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="subject">Subject *</label>
              <input type="text" id="subject" name="subject" placeholder="What is this about?" required>
              <small class="error-text"></small>
            </div>

            <div class="form-group">
              <label for="message">Message *</label>
              <textarea id="message" name="message" rows="6" placeholder="Write your questions or comments here..." required></textarea>
              <small class="error-text"></small>
            </div>

            <button type="submit" class="btn btn-primary">Submit Message</button>
            <p id="formMessage" class="form-message <?= e($formStatusClass) ?>" aria-live="polite"><?= e($formStatus) ?></p>
          </form>
        </article>

        <aside class="faq-card">
          <div class="section-heading left-align">
            <p class="eyebrow">HELP SECTION</p>
            <h2>FAQ</h2>
          </div>

          <?php foreach ($faqItems as $item): ?>
            <div class="faq-item <?= $item['active'] ? 'active' : '' ?>">
              <button class="faq-question" type="button"><?= e($item['question']) ?></button>
              <div class="faq-answer">
                <p><?= e($item['answer']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </aside>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-inner">
      <?php foreach ($site['footer'] as $line): ?>
        <p><?= e($line) ?></p>
      <?php endforeach; ?>
    </div>
  </footer>

  <script src="script.js"></script>
</body>

</html>
