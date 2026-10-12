<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Application Tracking Page — admissions-track.php
 * Aligned with Stitch Master Design
 */
$pageTitle     = 'Track Your Application — Admission Status';
$pageDesc      = 'Enter your Application ID to check real-time admission progress, entry test dates, verification status, and merit list results.';
$pageCanonical = 'http://localhost/sos/admissions-track.php';

include_once __DIR__ . '/include/classes/database.php';
global $database;

$searchedId = isset($_GET['app_id']) ? htmlspecialchars(trim($_GET['app_id'])) : (isset($_GET['app_no']) ? htmlspecialchars(trim($_GET['app_no'])) : '');
$application = null;
$searched = !empty($searchedId);

if ($searched) {
  $application = $database->getApplicationByNo($searchedId);
  if (!$application && is_numeric($searchedId)) {
    $application = $database->getApplication(intval($searchedId));
  }
}

// Fallback to latest application for showcase if no search query
if (!$application && !$searched) {
  $apps = $database->getAllApplications('', 0, 0);
  if (!empty($apps)) {
    $application = $apps[0];
    $searchedId = $application['application_no'];
  }
}
?>

<main class="py-16 bg-[#faf9f6]">
  <div class="max-w-[1100px] mx-auto px-6">
    
    <!-- Page Header -->
    <div class="text-center mb-10">
      <span class="eyebrow-text block mb-2 font-bold text-[#C6892E] tracking-wider uppercase text-xs">Real-time Portal</span>
      <h1 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-3">Track Your Application</h1>
      <p class="text-sm md:text-base text-[#54524B] max-w-2xl mx-auto">
        Enter your Application Reference ID below to check the real-time status of your admission process, entry test schedule, and verification stage.
      </p>
    </div>

    <?php if (isset($_GET['submitted']) && $_GET['submitted'] == '1'): ?>
    <!-- Submission Success Alert -->
    <div class="mb-10 p-6 bg-emerald-50 border-2 border-emerald-500 rounded-xl max-w-2xl mx-auto shadow-sm">
      <div class="flex items-start gap-4">
        <span class="material-symbols-outlined text-3xl text-emerald-600 shrink-0">check_circle</span>
        <div class="space-y-2">
          <h3 class="font-serif text-lg font-bold text-emerald-900">Application Submitted Successfully!</h3>
          <p class="text-xs sm:text-sm text-emerald-800 leading-relaxed">
            Your entry has been recorded in the SOS Hermann Gmeiner School Management System. Your Application Reference ID is <strong class="font-mono text-emerald-950 font-bold bg-white px-2 py-0.5 rounded border border-emerald-300"><?= htmlspecialchars($searchedId) ?></strong>.
          </p>
          <div class="p-3 bg-white/90 rounded-lg border border-emerald-200 text-xs text-emerald-900 flex items-start gap-2">
            <span class="material-symbols-outlined text-[#C6892E] text-[18px] shrink-0 mt-0.5">phone_android</span>
            <p>
              <strong>Please make sure your phone and email remain active.</strong> Entry test date and admission card will be provided via SMS and accessible through this Tracking Portal.
            </p>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Search Card -->
    <div class="bg-white rounded-[6px] shadow-sm border border-[#EAE7DE] p-6 max-w-2xl mx-auto mb-12">
      <form method="get" action="admissions-track.php" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-grow relative">
          <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#747780]">search</span>
          <input class="w-full pl-12 pr-4 py-3.5 rounded-[6px] border border-[#EAE7DE] focus:border-[#132644] focus:ring-1 focus:ring-[#132644] text-sm text-[#1C1C1A] bg-white outline-none" name="app_id" placeholder="Enter Application ID (e.g., APP-2026-0001)" type="text" value="<?= htmlspecialchars($searchedId) ?>" required/>
        </div>
        <button class="bg-[#C6892E] hover:bg-[#b27824] text-white font-semibold text-sm px-8 py-3.5 rounded-[6px] transition-colors shadow-xs whitespace-nowrap" type="submit">
          Track Status
        </button>
      </form>
    </div>

    <?php if ($application): ?>
    <?php
      $appStatus = $application['status'] ?? 'Submitted';
      // Map status to progress stage (1 to 5)
      // Submitted -> 1, UnderReview -> 2, Eligible -> 3, TestScheduled / Interview -> 4, Accepted / Enrolled -> 5
      $stageNum = 1;
      if (in_array($appStatus, ['UnderReview', 'Received'])) $stageNum = 2;
      else if (in_array($appStatus, ['Eligible', 'Verified'])) $stageNum = 3;
      else if (in_array($appStatus, ['TestScheduled', 'Interview', 'TestCompleted'])) $stageNum = 4;
      else if (in_array($appStatus, ['Accepted', 'Enrolled', 'Admitted'])) $stageNum = 5;
    ?>
    <!-- Result State (Timeline & Applicant Summary) -->
    <div class="bg-white rounded-[6px] shadow-sm border border-[#EAE7DE] p-8 md:p-10 mb-10">
      
      <!-- Applicant Info Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-[#EAE7DE] pb-6 mb-8 gap-4">
        <div>
          <div class="flex items-center gap-3">
            <h2 class="font-serif text-2xl font-bold text-[#132644]"><?= htmlspecialchars($application['student_name']) ?></h2>
            <span class="bg-[#00522d]/10 text-[#00522d] text-xs font-bold px-3 py-1 rounded-full border border-[#00522d]/20"><?= htmlspecialchars($appStatus) ?></span>
          </div>
          <div class="flex flex-wrap gap-3 text-xs text-[#54524B] mt-2">
            <span class="flex items-center gap-1 bg-[#F7F4EE] px-3 py-1 rounded-full border border-[#EAE7DE]">
              <span class="material-symbols-outlined text-[15px] text-[#C6892E]">badge</span> ID: <?= htmlspecialchars($application['application_no']) ?>
            </span>
            <span class="flex items-center gap-1 bg-[#F7F4EE] px-3 py-1 rounded-full border border-[#EAE7DE]">
              <span class="material-symbols-outlined text-[15px] text-[#C6892E]">school</span> Target: <?= htmlspecialchars($application['class_name'] ?? 'Class Level') ?>
            </span>
            <?php if (!empty($application['session_name'])): ?>
            <span class="flex items-center gap-1 bg-[#F7F4EE] px-3 py-1 rounded-full border border-[#EAE7DE]">
              <span class="material-symbols-outlined text-[15px] text-[#002452]">calendar_month</span> <?= htmlspecialchars($application['session_name']) ?>
            </span>
            <?php endif; ?>
          </div>
        </div>
        <div class="text-left sm:text-right">
          <p class="text-xs text-[#54524B] mb-0.5">Application Submitted On</p>
          <p class="text-sm font-bold text-[#132644]"><?= date('M d, Y · h:i A', strtotime($application['created_at'])) ?></p>
        </div>
      </div>

      <!-- Horizontal Step Trail (Progress Bar) -->
      <div class="mb-10">
        <h3 class="font-serif text-lg font-bold text-[#132644] mb-6">Application Progress Timeline</h3>
        
        <div class="relative w-full overflow-x-auto pb-4">
          <div class="flex justify-between items-start min-w-[700px] relative px-4">
            <div class="absolute top-5 left-10 right-10 h-1 bg-[#EAE7DE] z-0"></div>
            <div class="absolute top-5 left-10 h-1 bg-[#00522d] z-0 transition-all duration-500" style="width: <?= min(100, max(5, ($stageNum - 1) * 25)) ?>%;"></div>

            <!-- Stage 1: Submitted -->
            <div class="relative z-10 flex flex-col items-center gap-2 w-28 text-center">
              <div class="w-10 h-10 rounded-full <?= $stageNum >= 1 ? 'bg-[#00522d] text-white' : 'bg-white border-2 border-[#EAE7DE] text-[#747780]' ?> flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[18px]">check</span>
              </div>
              <p class="text-xs font-bold <?= $stageNum >= 1 ? 'text-[#00522d]' : 'text-[#747780]' ?>">Submitted</p>
              <p class="text-[11px] text-[#54524B]"><?= date('M d', strtotime($application['created_at'])) ?></p>
            </div>

            <!-- Stage 2: Received & Under Review -->
            <div class="relative z-10 flex flex-col items-center gap-2 w-28 text-center">
              <div class="w-10 h-10 rounded-full <?= $stageNum >= 2 ? 'bg-[#00522d] text-white' : 'bg-white border-2 border-[#EAE7DE] text-[#747780]' ?> flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[18px]"><?= $stageNum >= 2 ? 'check' : 'pending' ?></span>
              </div>
              <p class="text-xs font-bold <?= $stageNum >= 2 ? 'text-[#00522d]' : 'text-[#747780]' ?>">Screening</p>
              <p class="text-[11px] text-[#54524B]"><?= $stageNum >= 2 ? 'In Review' : 'Pending' ?></p>
            </div>

            <!-- Stage 3: Doc Verification -->
            <div class="relative z-10 flex flex-col items-center gap-2 w-28 text-center">
              <div class="w-10 h-10 rounded-full <?= $stageNum >= 3 ? 'bg-[#00522d] text-white' : 'bg-white border-2 border-[#EAE7DE] text-[#747780]' ?> flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[18px]"><?= $stageNum >= 3 ? 'verified' : 'hourglass_empty' ?></span>
              </div>
              <p class="text-xs font-bold <?= $stageNum >= 3 ? 'text-[#00522d]' : 'text-[#747780]' ?>">Verification</p>
              <p class="text-[11px] text-[#54524B]"><?= $stageNum >= 3 ? 'Eligible' : 'Pending' ?></p>
            </div>

            <!-- Stage 4: Entry Test -->
            <div class="relative z-10 flex flex-col items-center gap-2 w-28 text-center">
              <div class="w-10 h-10 rounded-full <?= $stageNum >= 4 ? ($stageNum > 4 ? 'bg-[#00522d] text-white' : 'bg-[#C6892E] text-white animate-pulse') : 'bg-white border-2 border-[#EAE7DE] text-[#747780]' ?> flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[18px]">edit_calendar</span>
              </div>
              <p class="text-xs font-bold <?= $stageNum >= 4 ? 'text-[#C6892E]' : 'text-[#747780]' ?>">Entry Test</p>
              <p class="text-[11px] text-[#747780]"><?= $stageNum >= 4 ? ($stageNum > 4 ? 'Completed' : 'Scheduled') : 'Pending' ?></p>
            </div>

            <!-- Stage 5: Decision / Admission -->
            <div class="relative z-10 flex flex-col items-center gap-2 w-28 text-center">
              <div class="w-10 h-10 rounded-full <?= $stageNum >= 5 ? 'bg-[#00522d] text-white' : 'bg-white border-2 border-[#EAE7DE] text-[#747780]' ?> flex items-center justify-center shadow-xs">
                <span class="material-symbols-outlined text-[18px]"><?= $stageNum >= 5 ? 'how_to_reg' : 'task_alt' ?></span>
              </div>
              <p class="text-xs font-bold <?= $stageNum >= 5 ? 'text-[#00522d]' : 'text-[#747780]' ?>">Decision</p>
              <p class="text-[11px] text-[#747780]"><?= $stageNum >= 5 ? 'Admitted' : 'Pending' ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Current Action Callout Box -->
      <div class="bg-[#F7F4EE] border-l-4 border-[#C6892E] p-6 rounded-r-[6px]">
        <div class="flex items-start gap-4">
          <span class="material-symbols-outlined text-[#C6892E] text-3xl shrink-0">event_available</span>
          <div>
            <h4 class="font-serif text-lg font-bold text-[#132644] mb-1">Status: <?= htmlspecialchars($appStatus) ?></h4>
            <p class="text-sm text-[#54524B] leading-relaxed mb-3">
              Application for <strong><?= htmlspecialchars($application['student_name']) ?></strong> (<?= htmlspecialchars($application['class_name'] ?? 'Class') ?>) is currently under <strong><?= htmlspecialchars($appStatus) ?></strong> stage.
              <?= !empty($application['remarks']) ? '<br>Note: ' . htmlspecialchars($application['remarks']) : '' ?>
            </p>
            <div class="flex flex-wrap gap-4">
              <a href="entry-test.php" class="bg-[#132644] hover:bg-[#0C1B30] text-white px-5 py-2.5 rounded-[6px] text-xs font-semibold inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">info</span> View Entry Test Syllabus &amp; Pattern
              </a>
              <a href="contact.php" class="border border-[#132644] text-[#132644] hover:bg-[#132644]/5 px-5 py-2.5 rounded-[6px] text-xs font-semibold inline-flex items-center gap-2">
                Contact Admissions Desk
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
    <?php elseif ($searched): ?>
    <div class="bg-white rounded-[6px] shadow-sm border border-[#EAE7DE] p-8 text-center max-w-xl mx-auto mb-10">
      <span class="material-symbols-outlined text-4xl text-[#C6892E] mb-2">search_off</span>
      <h3 class="font-serif text-xl font-bold text-[#132644] mb-2">Application Not Found</h3>
      <p class="text-xs text-[#54524B] leading-relaxed">No application found matching <strong><?= htmlspecialchars($searchedId) ?></strong>. Please double-check your Application Reference ID from your email or SMS acknowledgement slip.</p>
    </div>
    <?php endif; ?>

    <!-- Help & Contacts -->
    <div class="text-center text-xs text-[#54524B]">
      Questions regarding your application status? Contact the Admissions Registrar at <a href="tel:+922345678900" class="text-[#132644] font-bold underline">+92 (234) 567-8900</a> or email <a href="mailto:admissions@soshg.edu" class="text-[#132644] font-bold underline">admissions@soshg.edu</a>.
    </div>

  </div>
</main>

<?php include 'includes/footer.php'; ?>
