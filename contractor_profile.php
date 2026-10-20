<?php
session_start();

if (!function_exists('e')) {
  function e($value)
  {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
  }
}

require_once __DIR__ . '/Backend/functions.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit();
}

$currentUser = get_user($_SESSION['username']);
$currentUserId = (int) $_SESSION['user_id'];
$userServices = get_services($currentUserId);
$userReviews = get_reviews($currentUserId);
$averageRating = get_average_rating($currentUserId);
$isLoggedIn = true;

$site = [
  'name' => 'TrustYourTrades',
  'tagline' => 'Trust the Work. Trust the Trade.',
  'logo' => './Images/logo.png',
  'footer' => [
    'CS 268 · TrustYourTrades · Front-End Progress Submission',
    'Contractor profile, and services pages completed in the front-end stage.',
  ],
];

$navItems = [
  ['label' => 'Home', 'href' => 'home.php', 'active' => false],
  ['label' => 'About', 'href' => 'about.php', 'active' => false],
  ['label' => 'Services', 'href' => 'search.php', 'active' => false],
  ['label' => 'Contact', 'href' => 'contact.php', 'active' => false],
  ['label' => 'Profile', 'href' => './Backend/profile.php', 'active' => true],
  ['label' => 'Logout', 'href' => './Backend/logout.php', 'active' => false],
];

$contractor = [
  'name' => 'John Doe',
  'avatar' => './Images/contractor-placeholder.webp',
  'avatar_alt' => 'Contractor portrait',
  'eyebrow' => 'CONTRACTOR SUBPAGES',
  'role' => 'General Contractor · Landscaping · Plumbing',
  'description' => 'Example contractor profile for the TrustYourTrades front-end. This page demonstrates the profile, and services sections assigned in the team division of work.',
  'meta' => ['Eau Claire, WI', '4.8 Rating', 'Available This Week'],
  'overview' => [
    'Name' => 'John Doe',
    'Primary Role' => 'Independent Contractor',
    'Location' => 'Eau Claire, Wisconsin',
    'Experience' => '6 Years',
    'Status' => 'Accepting New Clients',
  ],
  'about' => 'John provides residential trade services with a focus on reliability, communication, and flexible scheduling. This front-end profile is designed to show what contractor information could look like before database integration is added.',
];

$stats = [
  ['number' => '12', 'label' => 'Completed Jobs'],
  ['number' => '7', 'label' => 'Services Listed'],
  ['number' => '3', 'label' => 'Open Time Slots'],
];

$tabs = [
  ['id' => 'profile', 'label' => 'Profile', 'active' => true, 'href' => null],
  ['id' => 'services', 'label' => 'Services', 'active' => false, 'href' => 'contractor_services.php'],
];

$contractorLinks = [
  ['label' => 'Reviews', 'href' => 'contractor_reviews.php'],
  ['label' => 'Certificates/Insurance', 'href' => 'contractor_certificates.php'],
  ['label' => 'Invoices', 'href' => 'contractor_invoices.php'],
];

$services = [
  [
    'name' => 'Lawn Mowing',
    'description' => 'Routine lawn cutting and trimming for residential properties.',
    'price' => '$12/hour',
    'keywords' => 'lawn mowing landscaping outdoor yard',
  ],
  [
    'name' => 'Pipe Repair',
    'description' => 'Pipe inspection and leak repair for home plumbing issues.',
    'price' => '$40/hour',
    'keywords' => 'plumbing pipe repair sink bathroom kitchen',
  ],
  [
    'name' => 'Yard Cleanup',
    'description' => 'Leaf cleanup, seasonal outdoor maintenance, and debris removal.',
    'price' => '$18/hour',
    'keywords' => 'landscaping cleanup mulch seasonal',
  ],
  [
    'name' => 'Fixture Installation',
    'description' => 'Installation of sinks, faucets, and other basic home fixtures.',
    'price' => '$30/hour',
    'keywords' => 'installation fixture plumbing contractor',
  ],
];

$availability = [
  ['day' => 'Mon', 'time' => '9:00 AM – 1:00 PM', 'status' => 'available'],
  ['day' => 'Tue', 'time' => 'Booked', 'status' => 'booked'],
  ['day' => 'Wed', 'time' => '1:00 PM – 5:00 PM', 'status' => 'available'],
  ['day' => 'Thu', 'time' => 'Booked', 'status' => 'booked'],
  ['day' => 'Fri', 'time' => '10:00 AM – 3:00 PM', 'status' => 'available'],
  ['day' => 'Sat', 'time' => 'Booked', 'status' => 'booked'],
  ['day' => 'Sun', 'time' => '12:00 PM – 4:00 PM', 'status' => 'available'],
];

$progressCards = [
  [
    'title' => 'Profile Layout',
    'body' => 'Completed hero section, contractor summary, and responsive content blocks.',
  ],
  [
    'title' => 'Services Section',
    'body' => 'Added service cards and JavaScript filtering for a stronger front-end demo.',
  ],
];

$fullName = trim(($currentUser['first_name'] ?? '') . ' ' . ($currentUser['last_name'] ?? ''));
$fullName = $fullName !== '' ? $fullName : $currentUser['username'];
$roleLabel = !empty($currentUser['is_contractor']) ? 'Contractor' : 'Client';
$serviceCount = count($userServices);
$reviewCount = count($userReviews);
$openSlots = 0;
$nextServiceDate = null;

foreach ($userServices as $service) {
  if (!empty($service['service_time']) && strtotime($service['service_time']) >= strtotime('today')) {
    $openSlots += 1;
    if ($nextServiceDate === null || strtotime($service['service_time']) < strtotime($nextServiceDate)) {
      $nextServiceDate = $service['service_time'];
    }
  }
}

$contractor = [
  'name' => $fullName,
  'avatar' => './Images/contractor-placeholder.webp',
  'avatar_alt' => $fullName . ' portrait',
  'eyebrow' => 'CONTRACTOR PROFILE',
  'role' => $roleLabel,
  'description' => 'Manage your TrustYourTrades profile, listed services, reviews, certificates, and invoices from these profile pages.',
  'meta' => [
    'Username: ' . $currentUser['username'],
    $averageRating ? round((float) $averageRating, 1) . ' Rating' : 'Not yet rated',
    $openSlots > 0 ? $openSlots . ' upcoming service slots' : 'No upcoming service slots',
  ],
  'overview' => [
    'Name' => $fullName,
    'Username' => $currentUser['username'],
    'Account Type' => $roleLabel,
    'Member #' => $currentUserId,
    'Next Service' => $nextServiceDate ? date('M d, Y g:i A', strtotime($nextServiceDate)) : 'None scheduled',
  ],
  'about' => 'This profile is connected to the same tyt database and session used by the rest of the project.',
];

$stats = [
  ['number' => (string) $reviewCount, 'label' => 'Reviews'],
  ['number' => (string) $serviceCount, 'label' => 'Services Listed'],
  ['number' => (string) $openSlots, 'label' => 'Upcoming Slots'],
];

if ($userServices) {
  $services = array_map(function ($service) {
    $location = trim(($service['service_city'] ?? '') . ', ' . ($service['service_state'] ?? ''), ' ,');

    return [
      'name' => $service['service_name'],
      'description' => $service['service_desc'],
      'price' => !empty($service['service_time']) ? date('M d, Y g:i A', strtotime($service['service_time'])) : 'Time not set',
      'keywords' => strtolower(($service['service_name'] ?? '') . ' ' . ($service['service_desc'] ?? '') . ' ' . $location),
    ];
  }, $userServices);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($site['name']) ?> | Contractor Profile</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./Stylesheets/contractor_profile.css" />
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
    <section class="profile-hero container reveal">
      <div class="profile-hero__main">
        <img src="<?= e($contractor['avatar']) ?>" alt="<?= e($contractor['avatar_alt']) ?>" class="profile-avatar" />
        <div>
          <p class="eyebrow"><?= e($contractor['eyebrow']) ?></p>
          <h1><?= e($contractor['name']) ?></h1>
          <p class="profile-role"><?= e($contractor['role']) ?></p>
          <p class="profile-description"><?= e($contractor['description']) ?></p>
          <div class="profile-meta">
            <?php foreach ($contractor['meta'] as $meta): ?>
              <span><?= e($meta) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="profile-hero__side">
        <?php foreach ($stats as $stat): ?>
          <div class="stat-box">
            <h3><?= e($stat['number']) ?></h3>
            <p><?= e($stat['label']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="section container reveal">
      <div class="tabs-card">
        <div class="tab-buttons">
          <?php foreach ($tabs as $tab): ?>
            <?php if (!empty($tab['href'])): ?>
              <a class="tab-btn tab-link <?= $tab['active'] ? 'active' : '' ?>" href="<?= e($tab['href']) ?>"><?= e($tab['label']) ?></a>
            <?php else: ?>
              <button class="tab-btn <?= $tab['active'] ? 'active' : '' ?>" data-tab="<?= e($tab['id']) ?>"><?= e($tab['label']) ?></button>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php foreach ($contractorLinks as $link): ?>
            <a class="tab-btn tab-link" href="<?= e($link['href']) ?>" style="text-decoration: none;"><?= e($link['label']) ?></a>
          <?php endforeach; ?>
        </div>

        <div class="tab-content active" id="profile">
          <div class="card-grid two-col align-start">
            <article class="feature-card">
              <h3>Profile Overview</h3>
              <ul class="plain-list">
                <?php foreach ($contractor['overview'] as $label => $value): ?>
                  <li><strong><?= e($label) ?>:</strong> <?= e($value) ?></li>
                <?php endforeach; ?>
              </ul>
            </article>

            <article class="feature-card">
              <h3>About This Contractor</h3>
              <p><?= e($contractor['about']) ?></p>
            </article>
          </div>
        </div>

      </div>
    </section>

    <section class="section container reveal">
      <div class="section-heading">
        <p class="eyebrow">FRONT-END PROGRESS</p>
        <h2>What was finished for this submission</h2>
      </div>
      <div class="card-grid three-col compact-cards">
        <?php foreach ($progressCards as $card): ?>
          <article class="mini-card">
            <h3><?= e($card['title']) ?></h3>
            <p><?= e($card['body']) ?></p>
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
