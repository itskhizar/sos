<?php
/**
 * SOS Hermann Gmeiner Public School & College Dhodial
 * Global Header Partial — includes/header.php
 */
if (!isset($siteUrl)) {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    if (basename($dir) === 'dashboard') {
        $dir = rtrim(dirname($dir), '/\\');
    }
    $siteUrl = $protocol . '://' . $host . $dir;
}
$activeAdmissionCycle = null;
if (file_exists(__DIR__ . '/../include/classes/database.php')) {
    require_once __DIR__ . '/../include/classes/database.php';
    global $database;
    if (isset($database)) {
        $activeAdmissionCycle = $database->getActiveAdmissionCycle();
    }
}
$currentFile = basename($_SERVER['PHP_SELF']);
?>

<?php if ($activeAdmissionCycle): ?>
<!-- Dynamic Admissions Open Notification Banner (Automatically hidden once date is over) -->
<div id="sos-admission-alert-banner" class="bg-gradient-to-r from-[#885200] via-[#C6892E] to-[#885200] text-white px-4 py-2.5 sm:px-6 border-b border-[#C6892E]/30 relative z-50 shadow-md">
  <div class="max-w-[1600px] mx-auto flex flex-col md:flex-row items-center justify-between gap-2.5 text-xs sm:text-sm">
    <div class="flex items-center gap-2.5 sm:gap-3 text-center md:text-left">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/20 text-white font-bold text-[11px] uppercase tracking-wider shadow-xs shrink-0">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Admissions Open
      </span>
      <p class="font-medium text-white/95 leading-snug">
        <strong><?= htmlspecialchars($activeAdmissionCycle['cycle_name']) ?></strong>
        <span class="hidden lg:inline text-white/80"> — Online applications are now being accepted.</span>
        <span class="inline-block bg-black/20 px-2 py-0.5 rounded font-semibold text-white ml-1.5 text-[11px] sm:text-xs">
          Last Date: <?= date('M d, Y', strtotime($activeAdmissionCycle['end_date'])) ?>
        </span>
      </p>
    </div>
    <div class="flex items-center gap-2.5 shrink-0">
      <a href="<?= $siteUrl ?>/admissions-apply.php" class="bg-[#132644] hover:bg-[#001c40] text-white font-bold px-3.5 py-1.5 rounded text-xs transition-transform hover:scale-105 shadow-sm inline-flex items-center gap-1">
        <span>Apply Online</span>
        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
      </a>
      <button onclick="document.getElementById('sos-admission-alert-banner').style.display='none'" class="text-white/70 hover:text-white p-1" title="Dismiss notice">
        <span class="material-symbols-outlined text-[16px]">close</span>
      </button>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Utility Top Bar -->
<div class="bg-[#132644] text-white text-[11.5px] sm:text-[13px] py-2 sm:py-2.5 px-4 sm:px-6 md:px-12 border-b border-white/10 relative z-50">
  <div class="max-w-[1600px] mx-auto flex justify-between items-center gap-2">
    <div class="flex items-center gap-3 sm:gap-6 text-white/90 min-w-0">
      <a href="tel:+922345678900" class="flex items-center gap-1.5 hover:text-[#C6892E] transition-colors whitespace-nowrap">
        <span class="material-symbols-outlined text-[15px] text-[#C6892E]">call</span>
        <span class="hidden xs:inline">+92 (234) 567-8900</span>
        <span class="xs:hidden">Call Us</span>
      </a>
      <a href="mailto:admissions@soshg.edu" class="hidden sm:flex items-center gap-1.5 hover:text-[#C6892E] transition-colors whitespace-nowrap">
        <span class="material-symbols-outlined text-[15px] text-[#C6892E]">mail</span>
        <span>admissions@soshg.edu</span>
      </a>
      <span class="hidden lg:inline text-white/30">|</span>
      <span class="hidden lg:inline text-white/80 truncate">Project of SOS Children's Villages Pakistan</span>
    </div>
    <div class="flex items-center gap-3 sm:gap-4 text-white/90 shrink-0">
      <a href="<?= $siteUrl ?>/careers.php" class="flex items-center gap-1 hover:text-[#C6892E] transition-colors text-[11.5px] sm:text-[13px]">
        <span class="material-symbols-outlined text-[14px] text-[#C6892E]">work</span>
        <span>Careers</span>
      </a>
      <span class="text-white/30">|</span>
      <a class="flex items-center gap-1 hover:text-[#C6892E] transition-colors text-[11.5px] sm:text-[13px]" href="<?= $siteUrl ?>/login.php">
        <span class="material-symbols-outlined text-[14px] text-[#C6892E]">login</span>
        <span>Sign In</span>
      </a>
    </div>
  </div>
</div>

<?php
$navItems = [
  ['title' => 'Home', 'url' => "$siteUrl/index.php", 'icon' => 'home', 'match' => ($currentFile === 'index.php')],
  ['title' => 'About', 'url' => "$siteUrl/about.php", 'icon' => 'info', 'match' => ($currentFile === 'about.php')],
  ['title' => 'Academics', 'url' => "$siteUrl/academics.php", 'icon' => 'school', 'match' => ($currentFile === 'academics.php')],
  ['title' => 'Admissions', 'url' => "$siteUrl/admissions.php", 'icon' => 'assignment_turned_in', 'match' => (strpos($currentFile, 'admission') !== false || $currentFile === 'entry-test.php')],
  ['title' => 'Campus', 'url' => "$siteUrl/campus.php", 'icon' => 'apartment', 'match' => ($currentFile === 'campus.php')],
  ['title' => 'Student Life', 'url' => "$siteUrl/student-life.php", 'icon' => 'sports_soccer', 'match' => ($currentFile === 'student-life.php')],
  ['title' => 'News & Events', 'url' => "$siteUrl/news.php", 'icon' => 'newspaper', 'match' => ($currentFile === 'news.php' || $currentFile === 'events.php')],
  ['title' => 'Careers', 'url' => "$siteUrl/careers.php", 'icon' => 'work', 'match' => ($currentFile === 'careers.php')],
  ['title' => 'Contact', 'url' => "$siteUrl/contact.php", 'icon' => 'contact_support', 'match' => ($currentFile === 'contact.php')],
];
?>

<!-- Header Navigation -->
<header class="bg-white sticky top-0 w-full z-40 border-b border-[#EAE7DE] shadow-xs">
  <div class="max-w-[1600px] mx-auto flex justify-between items-center h-16 sm:h-20 px-4 sm:px-6 md:px-12">
    <!-- Brand Lockup: SOS Hermann Gmeiner Public School & College Dhodial -->
    <a class="flex items-center gap-2.5 sm:gap-3.5 group min-w-0" href="<?= $siteUrl ?>/index.php" title="SOS Hermann Gmeiner Public School &amp; College Dhodial">
      <img alt="SOS Hermann Gmeiner School Logo" class="h-9 w-9 sm:h-[42px] sm:w-[42px] rounded-lg shrink-0 object-contain shadow-xs transition-transform group-hover:scale-105" src="<?= $siteUrl ?>/assets/images/logo.jpg"/>
      <div class="flex flex-col min-w-0 justify-center">
        <span class="font-fraunces text-[14.5px] xs:text-[16px] sm:text-[18px] md:text-[20px] font-bold text-[#132644] leading-tight tracking-tight truncate">
          SOS Hermann Gmeiner
        </span>
        <span class="text-[10px] sm:text-[11px] md:text-[12px] font-medium text-[#54524B] tracking-normal leading-tight truncate">
          Public School &amp; College · Dhodial
        </span>
      </div>
    </a>

    <!-- Desktop Nav with animated hover & active underline -->
    <nav class="hidden xl:flex items-center gap-6 2xl:gap-7 text-[14px] 2xl:text-[14.5px] font-medium text-[#1C1C1A]">
      <?php foreach ($navItems as $item): ?>
        <a href="<?= $item['url'] ?>" class="relative py-2 transition-colors duration-200 group <?= $item['match'] ? 'text-[#C6892E] font-semibold' : 'text-[#54524B] hover:text-[#132644]' ?>">
          <span><?= $item['title'] ?></span>
          <span class="absolute bottom-0 left-0 h-[2.5px] bg-[#C6892E] rounded-full transition-all duration-300 ease-out <?= $item['match'] ? 'w-full' : 'w-0 group-hover:w-full' ?>"></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- Header Actions (Responsive Buttons & Menu Toggle) -->
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
      <!-- Responsive Gold CTA: Smaller & cleaner on mobile, full width on desktop -->
      <a href="<?= $siteUrl ?>/admissions-apply.php" class="bg-[#C6892E] hover:bg-[#b27824] text-white px-2.5 py-1.5 sm:px-5 sm:py-2.5 rounded-[6px] text-[12px] sm:text-[14px] font-semibold shadow-xs transition-all hover:scale-[1.02] whitespace-nowrap inline-flex items-center gap-1 sm:gap-1.5">
        <span class="hidden sm:inline">Apply for admission</span>
        <span class="sm:hidden">Apply</span>
        <span class="material-symbols-outlined text-[14px] sm:text-[16px]">arrow_forward</span>
      </a>

      <!-- Mobile Menu Toggle Button -->
      <button id="mobile-menu-btn" aria-label="Open navigation menu" aria-expanded="false" class="xl:hidden w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-[#132644] rounded-lg border border-[#EAE7DE] hover:bg-[#F7F4EE] transition-colors">
        <span class="material-symbols-outlined text-[22px] sm:text-[26px]" id="mobile-menu-icon">menu</span>
      </button>
    </div>
  </div>

  <!-- Mobile Backdrop -->
  <div id="mobile-menu-backdrop" class="fixed inset-0 bg-[#0C1B30]/60 z-50 opacity-0 pointer-events-none transition-opacity duration-300 xl:hidden"></div>

  <!-- Mobile Slide-out Drawer Navigation -->
  <div id="mobile-menu-drawer" class="fixed top-0 right-0 bottom-0 w-[300px] max-w-[85vw] bg-white z-50 transform translate-x-full transition-transform duration-300 ease-in-out shadow-2xl flex flex-col xl:hidden" aria-hidden="true">
    <!-- Drawer Header -->
    <div class="p-4 sm:p-5 border-b border-[#EAE7DE] flex items-center justify-between bg-[#F7F4EE]">
      <div class="flex items-center gap-2.5 min-w-0">
        <img alt="SOS Hermann Gmeiner Logo" class="h-8 w-8 rounded-lg object-contain shadow-xs" src="<?= $siteUrl ?>/assets/images/logo.jpg"/>
        <div class="flex flex-col min-w-0">
          <span class="font-fraunces text-[14px] font-bold text-[#132644] leading-tight truncate">SOS Hermann Gmeiner</span>
          <span class="text-[10px] text-[#54524B]">Public School &amp; College</span>
        </div>
      </div>
      <button id="mobile-menu-close" aria-label="Close navigation menu" class="w-8 h-8 flex items-center justify-center rounded-lg text-[#54524B] hover:text-[#132644] hover:bg-white transition-colors">
        <span class="material-symbols-outlined text-[22px]">close</span>
      </button>
    </div>

    <!-- Scrollable Nav Items -->
    <div class="flex-1 overflow-y-auto px-4 py-3 space-y-1">
      <?php foreach ($navItems as $item): ?>
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] transition-colors <?= $item['match'] ? 'bg-[#C6892E]/10 text-[#C6892E] font-semibold border-l-4 border-[#C6892E]' : 'text-[#1C1C1A] hover:bg-[#F7F4EE] hover:text-[#132644]' ?>" href="<?= $item['url'] ?>">
          <span class="material-symbols-outlined text-[19px] <?= $item['match'] ? 'text-[#C6892E]' : 'text-[#8A867A]' ?>"><?= $item['icon'] ?></span>
          <span class="flex-1"><?= $item['title'] ?></span>
          <?php if ($item['match']): ?>
            <span class="w-1.5 h-1.5 rounded-full bg-[#C6892E]"></span>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Drawer Footer Actions -->
    <div class="p-4 border-t border-[#EAE7DE] bg-white space-y-2.5">
      <a href="<?= $siteUrl ?>/admissions-apply.php" class="w-full text-center bg-[#C6892E] hover:bg-[#b27824] text-white px-4 py-2.5 rounded-[6px] text-[13.5px] font-semibold shadow-xs transition-colors flex items-center justify-center gap-2">
        <span>Apply for admission</span>
        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
      </a>
      <a href="<?= $siteUrl ?>/login.php" class="w-full flex items-center justify-center gap-2 border border-[#132644] text-[#132644] hover:bg-[#132644]/5 px-4 py-2 rounded-[6px] text-[13px] font-semibold transition-colors">
        <span class="material-symbols-outlined text-[16px]">login</span>
        <span>Sign In to Portal</span>
      </a>
    </div>
  </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const btnOpen = document.getElementById('mobile-menu-btn');
  const btnClose = document.getElementById('mobile-menu-close');
  const drawer = document.getElementById('mobile-menu-drawer');
  const backdrop = document.getElementById('mobile-menu-backdrop');

  function openMenu() {
    if (!drawer || !backdrop) return;
    drawer.classList.remove('translate-x-full');
    backdrop.classList.remove('opacity-0', 'pointer-events-none');
    backdrop.classList.add('opacity-100');
    document.body.style.overflow = 'hidden';
    if (btnOpen) btnOpen.setAttribute('aria-expanded', 'true');
  }

  function closeMenu() {
    if (!drawer || !backdrop) return;
    drawer.classList.add('translate-x-full');
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0', 'pointer-events-none');
    document.body.style.overflow = '';
    if (btnOpen) btnOpen.setAttribute('aria-expanded', 'false');
  }

  if (btnOpen) btnOpen.addEventListener('click', openMenu);
  if (btnClose) btnClose.addEventListener('click', closeMenu);
  if (backdrop) backdrop.addEventListener('click', closeMenu);

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenu();
  });
});
</script>
