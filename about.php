<?php
session_start();

if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}

$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

$site = [
  'name' => 'TrustYourTrades',
  'tagline' => 'Trust the Work. Trust the Trade.',
  'logo' => './Images/logo.png',
  'footer' => [
    'CS 268 · TrustYourTrades · Front-End Progress Submission',
    'Designed to match the project slide style and team timeline.',
  ],
];

$navItems = [
  ['label' => 'Home', 'href' => 'home.php', 'active' => false],
  ['label' => 'About', 'href' => 'about.php', 'active' => true],
  ['label' => 'Services', 'href' => 'search.php', 'active' => false],
  ['label' => 'Contact', 'href' => 'contact.php', 'active' => false],
  ['label' => 'Profile', 'href' => './Backend/profile.php', 'active' => false],
  $isLoggedIn
    ? ['label' => 'Logout', 'href' => './Backend/logout.php', 'active' => false]
    : ['label' => 'Login', 'href' => 'login.php', 'active' => false],
];

$hero = [
  'eyebrow' => 'ABOUT PAGE',
  'title' => 'Built from a real need in the contractor community.',
  'body' => 'TrustYourTrades is a marketplace website where independent contractors can list their services and clients can search, compare, and connect with trusted local professionals.',
  'image' => './Images/about-team.webp',
  'image_alt' => 'Team members discussing a project',
  'actions' => [
    ['label' => 'View Contractor Demo', 'href' => 'contractor_profile.php', 'class' => 'btn btn-primary'],
    ['label' => 'Contact Us', 'href' => 'contact.php', 'class' => 'btn btn-secondary'],
  ],
];

$overviewCards = [
  [
    'title' => 'The Concept',
    'body' => 'The idea for TrustYourTrades came from a group member’s family connection with independent contractors. The team noticed that there were many skilled workers, but fewer simple platforms focused on connecting them with clients in an organized way.',
  ],
  [
    'title' => 'The Goal',
    'body' => 'Our goal is to create a website that is easy to use, visually clear, and useful for both clients and contractors. The front-end focuses on navigation, profile display, communication, and service information.',
  ],
];

$timelineItems = [
  [
    'status' => 'complete',
    'title' => 'Website Design Block-Out',
    'body' => 'Completed. The base page structure and overall layout were planned and designed.',
  ],
  [
    'status' => 'complete',
    'title' => 'Content Insertion',
    'body' => 'Completed. Text, images, and page sections have been added to the front-end pages.',
  ],
  [
    'status' => 'complete',
    'title' => 'General Styling',
    'body' => 'Completed. HTML, CSS, and JavaScript styling now follow the navy-and-gold PowerPoint design.',
  ],
  [
    'status' => 'current',
    'title' => 'Front-End Status on April 21',
    'body' => 'The front-end pages are completed for this assignment. The next stage is database creation and implementation.',
  ],
];

$assignedPages = [
  [
    'title' => 'About Page',
    'body' => 'Project introduction, concept, team purpose, and website progress summary.',
  ],
  [
    'title' => 'Contact Page',
    'body' => 'Support details, contact form, FAQ section, and front-end form validation.',
  ],
  [
    'title' => 'Contractor Subpages',
    'body' => 'Profile, services, and calendar sections with tab interaction and responsive design.',
  ],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($site['name']) ?> | About</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./Stylesheets/about.css" />
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
    <section class="page-hero container reveal">
      <div class="page-hero__text">
        <p class="eyebrow"><?= e($hero['eyebrow']) ?></p>
        <h1><?= e($hero['title']) ?></h1>
        <p><?= e($hero['body']) ?></p>
        <div class="hero-actions">
          <?php foreach ($hero['actions'] as $action): ?>
            <a class="<?= e($action['class']) ?>" href="<?= e($action['href']) ?>"><?= e($action['label']) ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="page-hero__visual">
        <img src="<?= e($hero['image']) ?>" alt="<?= e($hero['image_alt']) ?>" />
      </div>
    </section>

    <section class="section container reveal">
      <div class="section-heading">
        <p class="eyebrow">PROJECT OVERVIEW</p>
        <h2>What this website is trying to solve</h2>
      </div>
      <div class="card-grid two-col">
        <?php foreach ($overviewCards as $card): ?>
          <article class="feature-card">
            <h3><?= e($card['title']) ?></h3>
            <p><?= e($card['body']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="section container reveal">
      <div class="section-heading">
        <p class="eyebrow">TEAM + WORKFLOW</p>
        <h2>Current progress based on the project timeline</h2>
      </div>

      <div class="timeline-card">
        <?php foreach ($timelineItems as $item): ?>
          <div class="timeline-row <?= e($item['status']) ?>">
            <span class="status-dot"></span>
            <div>
              <h3><?= e($item['title']) ?></h3>
              <p><?= e($item['body']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="section container reveal">
      <div class="section-heading">
        <p class="eyebrow">YOUR ASSIGNED PAGES</p>
        <h2>Shuyang’s completed front-end responsibilities</h2>
      </div>
      <div class="card-grid three-col compact-cards">
        <?php foreach ($assignedPages as $page): ?>
          <article class="mini-card">
            <h3><?= e($page['title']) ?></h3>
            <p><?= e($page['body']) ?></p>
          </article>
        <?php endforeach; ?>
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
