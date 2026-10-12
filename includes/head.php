<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Global Head Partial — includes/head.php
 */
$siteName     = 'SOS Hermann Gmeiner Public School & College Dhodial';
if (!isset($siteUrl)) {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    if (basename($dir) === 'dashboard') {
        $dir = rtrim(dirname($dir), '/\\');
    }
    $siteUrl = $protocol . '://' . $host . $dir;
}
$defaultDesc  = 'SOS Hermann Gmeiner Public School & College, Dhodial — A premier institution under SOS Children\'s Villages Pakistan offering Nursery to Class 12 education anchored in character, values, and academic excellence.';
$defaultKW    = 'SOS Hermann Gmeiner Public School & College Dhodial, Dhodial school, Mansehra college, SOS Children\'s Villages Pakistan, admissions 2026-27';

$pageTitle    = isset($pageTitle) ? $pageTitle . ' · ' . $siteName : $siteName;
$pageDesc     = isset($pageDesc) ? $pageDesc : $defaultDesc;
$pageKeywords = isset($pageKeywords) ? $pageKeywords : $defaultKW;
$pageCanonical= isset($pageCanonical) ? $pageCanonical : $siteUrl . '/' . basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html class="scroll-smooth light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=5.0" name="viewport"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

  <!-- Primary SEO Meta Tags -->
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="title" content="<?= htmlspecialchars($pageTitle) ?>"/>
  <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>"/>
  <meta name="keywords" content="<?= htmlspecialchars($pageKeywords) ?>"/>
  <meta name="author" content="SOS Hermann Gmeiner School &amp; College"/>
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
  <link rel="canonical" href="<?= htmlspecialchars($pageCanonical) ?>"/>

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website"/>
  <meta property="og:url" content="<?= htmlspecialchars($pageCanonical) ?>"/>
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>"/>
  <meta property="og:description" content="<?= htmlspecialchars($pageDesc) ?>"/>
  <meta property="og:image" content="https://lh3.googleusercontent.com/aida-public/AB6AXuDkSZsS-Iqlvm4WA2I4tdngLjBR3KhU44dpiOTGAN-v4HyPc_9if4moawq8yehh0gSIm4cLrha7Tl1_6lnP7teFWAXwPxBITOhjADP70dbJORhfivcTygwyAK0G_1E-TQif_g5DTghKktZXDJhP_M01nfCzfLOMmB7WPTc3gqtN3iNl95p2_SSgGD84xHPKOpTAQcukpqJVHF-2XpKkCYb61rKOpIk59vrBSGAwF03ixJsJVia5Muwp"/>
  <meta property="og:site_name" content="<?= htmlspecialchars($siteName) ?>"/>

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image"/>
  <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>"/>
  <meta name="twitter:description" content="<?= htmlspecialchars($pageDesc) ?>"/>
  <meta name="twitter:image" content="https://lh3.googleusercontent.com/aida-public/AB6AXuDkSZsS-Iqlvm4WA2I4tdngLjBR3KhU44dpiOTGAN-v4HyPc_9if4moawq8yehh0gSIm4cLrha7Tl1_6lnP7teFWAXwPxBITOhjADP70dbJORhfivcTygwyAK0G_1E-TQif_g5DTghKktZXDJhP_M01nfCzfLOMmB7WPTc3gqtN3iNl95p2_SSgGD84xHPKOpTAQcukpqJVHF-2XpKkCYb61rKOpIk59vrBSGAwF03ixJsJVia5Muwp"/>

  <!-- Structured Data JSON-LD -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "School",
    "name": "SOS Hermann Gmeiner Public School & College Dhodial",
    "url": "<?= $siteUrl ?>",
    "logo": "<?= $siteUrl ?>/assets/images/logo.jpg",
    "description": "<?= addslashes($defaultDesc) ?>",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Dhodial, Mansehra",
      "addressLocality": "Dhodial",
      "addressRegion": "Khyber Pakhtunkhwa",
      "addressCountry": "PK"
    },
    "telephone": "+92-234-567-8900",
    "parentOrganization": {
      "@type": "Organization",
      "name": "SOS Children's Villages Pakistan"
    }
  }
  </script>

  <!-- Google Fonts & Material Symbols -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&amp;family=Source+Serif+4:opsz,wght@8..60,400;8..60,600;8..60,700&amp;family=Inter:wght@400;500;600;700&amp;family=Work+Sans:wght@400;500;600&amp;display=swap" rel="stylesheet"/>

  <!-- Tailwind CSS Play CDN -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script id="tailwind-config">
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          primary: "#132644",
          "primary-deep": "#0C1B30",
          gold: "#C6892E",
          "gold-hover": "#b27824",
          cream: "#F7F4EE",
          ink: "#1C1C1A",
          "ink-muted": "#54524B",
          "border-subtle": "#EAE7DE",
          surface: "#FFFFFF",
          "surface-container-low": "#f4f3f0",
          "surface-container": "#efeeeb",
          "surface-container-high": "#e9e8e5",
          "surface-container-highest": "#e3e2df",
        },
        fontFamily: {
          serif: ["Fraunces", "\"Source Serif 4\"", "Georgia", "serif"],
          fraunces: ["Fraunces", "Georgia", "serif"],
          sans: ["Inter", "Work Sans", "system-ui", "sans-serif"],
        },
        maxWidth: {
          site: "1600px",
        }
      }
    }
  }
  </script>

  <!-- Global Master CSS -->
  <style>
  body {
    font-family: 'Inter', sans-serif;
    color: #1C1C1A;
    background-color: #FFFFFF;
  }
  h1, h2, .font-fraunces, .font-serif {
    font-family: 'Fraunces', serif;
  }
  .h1-hero {
    font-size: 45px;
    line-height: 1.18;
  }
  @media (max-width: 768px) {
    .h1-hero {
      font-size: 34px;
      line-height: 1.22;
    }
  }
  .h2-title {
    font-size: 32px;
    line-height: 1.25;
  }
  .h3-card {
    font-size: 18px;
    line-height: 1.35;
  }
  .body-standard {
    font-size: 16px;
    line-height: 1.65;
  }
  .eyebrow-text {
    font-size: 13px;
    font-weight: 500;
    color: #C6892E;
    text-transform: none;
  }
  .ghost-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 15px;
    font-weight: 600;
    color: #132644;
    transition: color 0.2s ease, transform 0.2s ease;
    text-decoration: none;
  }
  .ghost-link:hover {
    color: #C6892E;
  }
  .ghost-link .material-symbols-outlined {
    font-size: 18px;
    transition: transform 0.2s ease;
  }
  .ghost-link:hover .material-symbols-outlined {
    transform: translateX(4px);
  }
  .locked-card {
    background-color: #FFFFFF;
    border-radius: 6px;
    border: 1px solid #EAE7DE;
    padding: 24px;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  }
  .locked-card:hover {
    border-color: #C6892E40;
    box-shadow: 0 10px 25px -5px rgba(19, 38, 68, 0.08);
  }
  .btn-gold {
    background-color: #C6892E;
    color: #FFFFFF;
    font-weight: 600;
    border-radius: 6px;
    transition: background-color 0.2s ease, transform 0.15s ease;
  }
  .btn-gold:hover {
    background-color: #b27824;
  }
  .btn-navy {
    background-color: #132644;
    color: #FFFFFF;
    font-weight: 600;
    border-radius: 6px;
    transition: background-color 0.2s ease;
  }
  .btn-navy:hover {
    background-color: #0C1B30;
  }
  .btn-outline-navy {
    border: 1px solid #132644;
    color: #132644;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.2s ease;
  }
  .btn-outline-navy:hover {
    background-color: rgba(19, 38, 68, 0.05);
  }
  </style>

  <!-- Google AdSense Ready Placeholder -->
  <!--
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXXXXXXXXXXXX" crossorigin="anonymous"></script>
  -->
</head>
<body class="antialiased selection:bg-gold/20 selection:text-primary <?= isset($bodyClass) ? htmlspecialchars($bodyClass) : '' ?>">
