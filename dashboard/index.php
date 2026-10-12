<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Main Dashboard Overview — dashboard/index.php
 * Strictly designed per Claude Master Redesign Specifications with Enhanced Stats & Pipeline
 */
include_once dirname(__DIR__) . '/include/classes/session.php';
if ($session->logged_in) {
$dashTitle   = 'Dashboard Overview';
$currentDash = 'dashboard';
include 'includes/header.php';
}else{
    header('location: ../login.php');
}

?>

<?php
$kpis = $database->getDashboardKPIs();
$activeSession = $database->getActiveSession();
$sessionName = $activeSession ? htmlspecialchars($activeSession['session_name']) : '2026–2027';

// Pipeline metrics
$qEnq = $database->query("SELECT COUNT(*) as c FROM " . TBL_ENQUIRIES);
$totalEnq = ($qEnq) ? intval(mysqli_fetch_assoc($qEnq)['c']) : 0;

$qApps = $database->query("SELECT COUNT(*) as c FROM " . TBL_APPLICATIONS);
$totalApps = ($qApps) ? intval(mysqli_fetch_assoc($qApps)['c']) : 0;

$qCandidates = $database->query("SELECT COUNT(*) as c FROM " . TBL_ENTRY_TEST_CANDIDATES);
$totalCandidates = ($qCandidates) ? intval(mysqli_fetch_assoc($qCandidates)['c']) : 0;

$qDocs = $database->query("SELECT COUNT(*) as c FROM " . TBL_ADMISSION_DOCS . " WHERE is_verified = 1");
$totalDocs = ($qDocs) ? intval(mysqli_fetch_assoc($qDocs)['c']) : 0;

$qStudents = $database->query("SELECT COUNT(*) as c FROM " . TBL_STUDENTS);
$totalStudents = ($qStudents) ? intval(mysqli_fetch_assoc($qStudents)['c']) : 0;

$qEnrolled = $database->query("SELECT COUNT(*) as c FROM " . TBL_ENROLLMENTS . " WHERE status = 'Active'");
$totalEnrolled = ($qEnrolled) ? intval(mysqli_fetch_assoc($qEnrolled)['c']) : 0;

// Class bar chart distribution
$classDist = [];
$qClasses = $database->query("SELECT c.class_name, COUNT(e.enrollment_id) as cnt 
                              FROM " . TBL_CLASSES . " c 
                              LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON c.class_id = cs.class_id 
                              LEFT JOIN " . TBL_ENROLLMENTS . " e ON cs.class_session_id = e.class_session_id AND e.status = 'Active' 
                              WHERE c.is_active = 1 
                              GROUP BY c.class_id 
                              ORDER BY c.class_order ASC, c.class_id ASC LIMIT 8");
if ($qClasses) {
  while ($row = mysqli_fetch_assoc($qClasses)) {
    $classDist[] = $row;
  }
}
$maxCnt = 1;
foreach ($classDist as $cd) {
  if (intval($cd['cnt']) > $maxCnt) $maxCnt = intval($cd['cnt']);
}

// Recent audit activities
$recentLogs = $database->getAuditLogs(5);
?>

<!-- Breadcrumb -->
<div class="crumb">
  <span><?php echo htmlspecialchars($session->username); ?></span>
  <span>/</span>
  <span style="color:var(--navy); font-weight:500;">Dashboard Overview</span>
</div>

<!-- Personalized Page Header & Quick Actions -->
<div class="page-head">
  <div>
    <h2>Welcome back, <?php echo htmlspecialchars($session->fullname ?: $session->username); ?></h2>
    <p>Operational summary for academic session <strong><?= $sessionName ?></strong>.</p>
  </div>
  <div class="qa">
    <!-- Solid Navy Primary Action Button -->
    <a class="btn btn-primary" href="enquiries.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 5v14M5 12h14"/>
      </svg>
      <span>New Enquiry / Admission</span>
    </a>
    
    <!-- Outline Secondary Action Buttons -->
    <a class="btn btn-outline" href="fees.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="16" rx="2"/>
        <path d="M3 9h18"/>
      </svg>
      <span>Generate Fees</span>
    </a>
    <a class="btn btn-outline" href="payments.php">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 12l2 2 4-4"/>
        <circle cx="12" cy="12" r="10"/>
      </svg>
      <span>Verify Payments</span>
    </a>
  </div>
</div>

<!-- 6 KPI CARDS (3-Column Grid) -->
<div class="kpi-grid">
  
  <!-- KPI 1: Total Active Students (Neutral / Blue-tint) -->
  <div class="kpi">
    <div class="kpi-top">
      <div class="kpi-icon" style="background:#EAF0FA;">
        <svg viewBox="0 0 24 24" fill="none" stroke="#2E5AAC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
          <path d="M6 12v5c3 3 9 3 12 0v-5"/>
        </svg>
      </div>
      <div class="trend up">
        <span class="text-xs font-semibold">Active</span>
      </div>
    </div>
    <div class="kpi-label">Total active students</div>
    <div class="kpi-num"><?= number_format($kpis['active_students']) ?></div>
    <p class="text-[11.5px] text-[#54524B] mt-1">Permanent person registry</p>
  </div>

  <!-- KPI 2: Active Enrollments (Healthy Growth / Green-tint) -->
  <div class="kpi">
    <div class="kpi-top">
      <div class="kpi-icon" style="background:var(--green-bg);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M19 8v6M22 11h-6"/>
        </svg>
      </div>
      <div class="trend up">
        <span class="text-xs font-semibold">Enrolled</span>
      </div>
    </div>
    <div class="kpi-label">Enrolled in current session</div>
    <div class="kpi-num"><?= number_format($totalEnrolled) ?></div>
    <p class="text-[11.5px] text-[#2E8B57] font-medium mt-1">Allocated to class &amp; section</p>
  </div>

  <!-- KPI 3: Pending Applications -->
  <div class="kpi">
    <div class="kpi-top">
      <div class="kpi-icon" style="background:var(--amber-bg);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 11l3 3L22 4"/>
          <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
        </svg>
      </div>
      <div class="trend flat">
        <span>Review</span>
      </div>
    </div>
    <div class="kpi-label">Pending applications</div>
    <div class="kpi-num"><?= number_format($kpis['pending_admissions']) ?></div>
    <div class="kpi-sub">
      <a href="admissions.php" style="color:var(--gold); text-decoration:none;" class="hover:underline flex items-center gap-1 font-semibold">
        <span>→ review admissions queue</span>
      </a>
    </div>
  </div>

  <!-- KPI 4: Total Fees Collected (Green-tint) -->
  <div class="kpi">
    <div class="kpi-top">
      <div class="kpi-icon" style="background:var(--green-bg);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="5" width="20" height="14" rx="2"/>
          <path d="M2 10h20"/>
        </svg>
      </div>
      <div class="trend up">
        <span class="text-xs font-semibold">Verified</span>
      </div>
    </div>
    <div class="kpi-label">Total fees collected</div>
    <div class="kpi-num">Rs. <?= number_format($kpis['total_collected']) ?></div>
    <p class="text-[11.5px] text-[#2E8B57] font-medium mt-1">Bank cleared collections</p>
  </div>

  <!-- KPI 5: Outstanding Dues -->
  <div class="kpi">
    <div class="kpi-top">
      <div class="kpi-icon" style="background:var(--red-bg);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="1" y1="1" x2="23" y2="23"/>
          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
        </svg>
      </div>
      <div class="trend down-bad" style="background:var(--amber-bg); color:var(--amber);">
        <span>Receivable</span>
      </div>
    </div>
    <div class="kpi-label">Outstanding fee dues</div>
    <div class="kpi-num">Rs. <?= number_format($kpis['total_outstanding']) ?></div>
    <p class="text-[11.5px] text-[#54524B] mt-1">Pending student ledger demand</p>
  </div>

  <!-- KPI 6: Scheduled Tests & Inquiries -->
  <div class="kpi">
    <div class="kpi-top">
      <div class="kpi-icon" style="background:var(--amber-bg);">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M8 2v4M16 2v4M3 10h18"/>
          <rect x="3" y="4" width="18" height="18" rx="2"/>
        </svg>
      </div>
      <div class="trend flat">
        <span>Pipeline</span>
      </div>
    </div>
    <div class="kpi-label">Scheduled entry tests</div>
    <div class="kpi-num"><?= number_format($kpis['upcoming_tests']) ?></div>
    <div class="kpi-sub">
      <a href="entry-tests.php" style="color:var(--gold); text-decoration:none;" class="hover:underline flex items-center gap-1 font-semibold">
        <span>→ view test schedules</span>
      </a>
    </div>
  </div>

</div>

<!-- Prospect & Admissions Pipeline Strip -->
<div class="bg-white border border-[#EAE7DE] rounded-lg p-4 mb-6 shadow-xs">
  <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-3">
    <div>
      <span class="text-xs font-bold uppercase tracking-wider text-[#C6892E] block mb-0.5">Enquiry to Enrollment Pipeline</span>
      <h3 class="font-serif text-sm md:text-base font-bold text-[#132644]">Academic Session <?= $sessionName ?> Conversion Flow</h3>
    </div>
    <a href="enquiries.php" class="text-xs font-semibold text-[#132644] hover:text-[#C6892E] flex items-center gap-1">
      <span>Manage Enquiries CRM</span>
      <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
    </a>
  </div>

  <!-- Pipeline Stages -->
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 text-center text-xs">
    <div class="bg-[#F7F4EE] border border-[#EAE7DE] p-2.5 rounded">
      <span class="text-[11px] text-[#747780] block">1. Inquiries Logged</span>
      <strong class="text-base text-[#132644]"><?= $totalEnq ?></strong>
      <span class="text-[10px] text-gray-500 block">Web / Walk / Phone</span>
    </div>
    <div class="bg-[#F7F4EE] border border-[#EAE7DE] p-2.5 rounded">
      <span class="text-[11px] text-[#747780] block">2. Applications</span>
      <strong class="text-base text-[#132644]"><?= $totalApps ?></strong>
      <span class="text-[10px] text-emerald-600 font-medium block"><?= $totalEnq > 0 ? round(($totalApps / $totalEnq) * 100, 1) : 0 ?>% Converted</span>
    </div>
    <div class="bg-[#F7F4EE] border border-[#EAE7DE] p-2.5 rounded">
      <span class="text-[11px] text-[#747780] block">3. Tests Scheduled</span>
      <strong class="text-base text-[#132644]"><?= $totalCandidates ?></strong>
      <span class="text-[10px] text-gray-500 block">Candidates</span>
    </div>
    <div class="bg-[#F7F4EE] border border-[#EAE7DE] p-2.5 rounded">
      <span class="text-[11px] text-[#747780] block">4. Docs Verified</span>
      <strong class="text-base text-[#132644]"><?= $totalDocs ?></strong>
      <span class="text-[10px] text-emerald-700 font-medium block">Approved</span>
    </div>
    <div class="bg-[#F7F4EE] border border-[#EAE7DE] p-2.5 rounded">
      <span class="text-[11px] text-[#747780] block">5. Registered</span>
      <strong class="text-base text-[#132644]"><?= $totalStudents ?></strong>
      <span class="text-[10px] text-gray-500 block">Permanent Record</span>
    </div>
    <div class="bg-emerald-50 border border-emerald-200 p-2.5 rounded">
      <span class="text-[11px] text-emerald-800 block">6. Active Enrolled</span>
      <strong class="text-base text-emerald-800"><?= $totalEnrolled ?></strong>
      <span class="text-[10px] text-emerald-700 font-bold block">Class &amp; Section</span>
    </div>
  </div>
</div>

<!-- LOWER TWO-COLUMN GRID -->
<div class="lower-grid">
  
  <!-- Left Panel: Students by Class (Bar Chart with Interactive Details) -->
  <div class="panel">
    <div class="panel-head">
      <div>
        <h3>Students by class</h3>
        <p class="text-xs text-[#747780] mt-0.5">Distribution across Wings (Pre-School, Elementary, High &amp; College)</p>
      </div>
      <a class="view-all" href="enrollment.php">Enrollments →</a>
    </div>
    <div class="bars">
      <?php if (empty($classDist)): ?>
        <div class="py-8 text-center text-xs text-[#747780] w-full">No active class enrollments recorded yet.</div>
      <?php else: ?>
        <?php foreach ($classDist as $cIdx => $cItem): 
          $cnt = intval($cItem['cnt']);
          $heightPct = max(15, min(100, round(($cnt / $maxCnt) * 100)));
          $isLight = ($cIdx % 2 == 0);
        ?>
        <div class="bar-col">
          <span class="text-[10px] font-bold text-[#132644] mb-1"><?= $cnt ?></span>
          <div class="bar <?= $isLight ? 'light' : '' ?>" style="height:<?= $heightPct ?>%;" title="<?= htmlspecialchars($cItem['class_name']) ?>: <?= $cnt ?> Scholars"></div>
          <span class="bar-lbl"><?= htmlspecialchars(substr($cItem['class_name'], 0, 4)) ?></span>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <!-- Legend -->
    <div class="flex items-center justify-between text-xs text-[#747780] pt-4 mt-2 border-t border-[#EAE7DE]">
      <div class="flex items-center gap-4">
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#132644]"></span> Primary / Secondary</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#C7CEDC]"></span> Junior / Foundation</span>
      </div>
      <span class="font-medium">Total: <strong><?= $totalEnrolled ?> Enrolled</strong></span>
    </div>
  </div>

  <!-- Right Panel: Recent Activity Timeline -->
  <div class="panel">
    <div class="panel-head">
      <div>
        <h3>Recent activity</h3>
        <p class="text-xs text-[#747780] mt-0.5">Forensic system event feed</p>
      </div>
      <a class="view-all" href="audit-logs.php">View all →</a>
    </div>
    <ul class="activity">
      <?php if (empty($recentLogs)): ?>
        <li class="py-4 text-center text-xs text-[#747780]">No recent activity logged.</li>
      <?php else: ?>
        <?php foreach ($recentLogs as $log): 
          $uName = htmlspecialchars($log['user_id'] ?: 'System');
          $act = htmlspecialchars($log['action']);
          $desc = htmlspecialchars($log['new_values'] ?: ($log['old_values'] ?: $log['action']));
          $timeStr = date('d-M h:i A', strtotime($log['timestamp']));
        ?>
        <li>
          <div class="a-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <div class="a-text"><b><?= $uName ?>:</b> <?= $desc ?></div>
            <div class="a-time flex items-center gap-2">
              <span><?= $timeStr ?></span>
              <span class="text-emerald-700 font-semibold">• <?= htmlspecialchars($log['module']) ?></span>
            </div>
          </div>
        </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>

</div>

<?php include 'includes/footer.php'; ?>
