<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Master Dashboard Header & Collapsible Sidebar — dashboard/includes/header.php
 * Fully unified, smooth, responsive navigation with bidirectional Expand/Collapse
 */
if (!isset($session) || !is_object($session)) {
    include_once dirname(dirname(__DIR__)) . '/include/classes/session.php';
}
if (!$session->logged_in) {
    header('Location: ../login.php');
    exit;
}

$username = $session->username;
$result = $database->clientdata($username);
$userlevel = is_array($result) ? $result['userlevel'] : 0;
$full_name = is_array($result) && !empty($result['full_name']) ? $result['full_name'] : $username;
$email = is_array($result) && !empty($result['email']) ? $result['email'] : '';
$activeAcademic = $database->getActiveSession();
$activeSessionLabel = is_array($activeAcademic) && !empty($activeAcademic['session_name'])
    ? $activeAcademic['session_name']
    : 'No active session';
$dashTitle   = isset($dashTitle)   ? $dashTitle   : 'Dashboard Overview';
$currentDash = isset($currentDash) ? $currentDash : 'dashboard';

// Determine active group to keep accordion automatically open
$academicSetupPages = ['sessions', 'setup', 'classes', 'sections'];
$admissionsPages    = ['enquiries', 'admissions', 'admission-cycles', 'admission-applications', 'entry-tests', 'verifications', 'admission-history'];
$studentsPages      = ['students', 'enrollment', 'promotions', 'guardians', 'student-documents'];

$feesPages          = ['fees', 'fee-structures', 'fee-types', 'concessions', 'monthly-charges', 'adjustments', 'fines', 'other-charges'];
$challansPages      = ['challans', 'payments', 'receipts'];

$reportsPages       = ['reports', 'fee-reports', 'student-reports', 'audit-logs'];
$systemPages        = ['users', 'roles', 'settings'];

$isAcademicSetupOpen = in_array($currentDash, $academicSetupPages);
$isAdmissionsOpen    = in_array($currentDash, $admissionsPages);
$isStudentsOpen      = in_array($currentDash, $studentsPages);

$isFeesOpen          = in_array($currentDash, $feesPages);
$isChallansOpen      = in_array($currentDash, $challansPages);

$isReportsOpen       = in_array($currentDash, $reportsPages);
$isSystemOpen        = in_array($currentDash, $systemPages);

$isAcademicGroupOpen = $isAcademicSetupOpen || $isAdmissionsOpen || $isStudentsOpen;
$isFinanceGroupOpen  = $isFeesOpen || $isChallansOpen;
$isIntellGroupOpen   = $isReportsOpen || $isSystemOpen;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($dashTitle) ?> — SOS Hermann Gmeiner Admin Portal</title>

<!-- Fonts: Fraunces Serif & Work Sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

<!-- Tailwind Play CDN for utility classes -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<style>
  :root{
    --navy:#132644; --navy-deep:#0C1B30; --navy-mid:#1D3A63;
    --gold:#C6892E; --gold-light:#E6B366; --sky-blue:#4FB6E8;
    --green:#2E8B57; --green-bg:#E7F3EC; --red:#C0392B; --red-bg:#FBEAE8;
    --amber:#B9791F; --amber-bg:#FBF0DE;
    --cream:#F7F4EE; --ink:#1C1C1A; --ink-soft:#54524B; --line:#EAE7DE;
  }
  *{box-sizing:border-box;}
  body{margin:0; font-family:'Work Sans',sans-serif; color:var(--ink); background:var(--cream); display:flex; height:100vh; overflow:hidden;}
  h1, h2, h3, .font-fraunces { font-family:'Fraunces', serif; }

  /* SIDEBAR EXPANDED (262px) & COLLAPSED (72px) */
  .sidebar{width:262px; background:var(--navy); color:#fff; display:flex; flex-direction:column; flex-shrink:0; transition:width .28s cubic-bezier(.4,0,.2,1); overflow:hidden; z-index:40;}
  .sidebar.collapsed{width:72px;}
  
  .side-head{display:flex; align-items:center; gap:10px; padding:18px 16px; border-bottom:1px solid rgba(255,255,255,.1); min-height:71px; position:relative;}
  .crest{width:36px; height:36px; border-radius:8px; background:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0; cursor:pointer; transition:transform .15s; overflow:hidden; padding:2px; box-shadow:0 1px 4px rgba(0,0,0,.2);}
  .crest:hover{transform:scale(1.05);}
  .crest img{width:100%; height:100%; object-fit:contain; border-radius:6px;}
  .side-title{font-family:'Fraunces',serif; font-size:15px; font-weight:600; line-height:1.2; white-space:nowrap; overflow:hidden;}
  .side-title small{display:block; font-family:'Work Sans'; font-size:10.5px; font-weight:400; color:#9AA8C0; max-width:160px; overflow:hidden; text-overflow:ellipsis;}
  
  .collapse-btn{margin-left:auto; background:rgba(255,255,255,.08); border:none; width:28px; height:28px; border-radius:6px; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background .15s, transform .28s;}
  .collapse-btn:hover{background:rgba(255,255,255,.2);}
  .collapse-btn svg{width:15px; height:15px; transition:transform .28s ease;}
  
  /* When Collapsed: explicitly hide text elements, reposition toggle to remain 100% accessible */
  .sidebar.collapsed .side-head{padding:18px 8px; justify-content:center;}
  .sidebar.collapsed .side-title{display:none !important;}
  .sidebar.collapsed .collapse-btn{
    margin-left:0;
    position:absolute;
    right:4px;
    top:22px;
    width:22px;
    height:22px;
    background:rgba(255,255,255,.15);
  }
  .sidebar.collapsed .collapse-btn svg{transform:rotate(180deg);}

  .session-pill{margin:12px 16px; padding:9px 12px; background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12); border-radius:6px; display:flex; align-items:center; gap:8px; font-size:12.5px; white-space:nowrap; user-select:none;}
  .session-pill .dot{width:7px; height:7px; border-radius:50%; background:#5FD98A; flex-shrink:0;}
  .sidebar.collapsed .session-pill{padding:9px 0; justify-content:center; margin:12px 10px;}
  .sidebar.collapsed .session-pill span:not(.dot){display:none !important;}

  nav.side-nav{flex:1; overflow-y:auto; padding:6px 10px 14px;}
  nav.side-nav::-webkit-scrollbar { width: 4px; }
  nav.side-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }

  .nav-top-item{display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:6px; text-decoration:none; color:#fff; font-size:14px; font-weight:500; margin-bottom:4px; white-space:nowrap; transition:background .15s;}
  .nav-top-item.active{background:var(--gold); color:#fff; font-weight:600;}
  .nav-top-item:hover:not(.active){background:rgba(255,255,255,.07);}
  .nav-top-item svg{width:18px; height:18px; flex-shrink:0;}
  .sidebar.collapsed .nav-top-item span{display:none !important;}
  .sidebar.collapsed .nav-top-item{justify-content:center; padding:10px 0;}

  .group{margin-top:14px;}
  .group-label{font-size:10.5px; font-weight:600; letter-spacing:.06em; color:#7C8AA6; padding:0 12px 6px; white-space:nowrap; text-transform:uppercase;}
  .sidebar.collapsed .group-label{display:none !important;}
  
  .group-head{display:flex; align-items:center; gap:12px; padding:9px 12px; border-radius:6px; cursor:pointer; font-size:14px; color:#DCE3F0; white-space:nowrap; user-select:none; transition:background .15s;}
  .group-head:hover{background:rgba(255,255,255,.07);}
  .group-head svg.gi{width:18px; height:18px; flex-shrink:0;}
  .group-head .lbl{flex:1;}
  .badge{background:var(--gold); color:#fff; font-size:11px; font-weight:700; padding:1.5px 7px; border-radius:10px; margin-right:4px;}
  .chev{width:14px; height:14px; transition:transform .22s ease; flex-shrink:0;}
  .group-head.open .chev{transform:rotate(90deg);}
  
  .sub{max-height:0; overflow:hidden; transition:max-height .28s cubic-bezier(0.4, 0, 0.2, 1);}
  .sub.open{max-height:450px;}
  .sub a{display:block; padding:7px 12px 7px 42px; font-size:13.5px; color:#B7C1D6; text-decoration:none; white-space:nowrap; border-radius:4px; transition:all .15s;}
  .sub a:hover{color:#fff; background:rgba(255,255,255,.05);}
  .sub a.active{color:var(--gold-light); font-weight:600; background:rgba(198,137,46,.14);}
  
  .sidebar.collapsed .group-head span, 
  .sidebar.collapsed .badge, 
  .sidebar.collapsed .sub, 
  .sidebar.collapsed .chev{display:none !important;}
  .sidebar.collapsed .group-head{justify-content:center; padding:10px 0;}

  /* Sidebar Footer */
  .side-foot{border-top:1px solid rgba(255,255,255,.1); padding:10px;}
  .ext-link{display:flex; align-items:center; gap:12px; padding:8px 12px; border-radius:6px; color:#DCE3F0; text-decoration:none; font-size:13px; white-space:nowrap; transition:all .15s;}
  .ext-link:hover{background:rgba(255,255,255,.07); color:#fff;}
  .ext-link svg{width:16px; height:16px; flex-shrink:0;}
  .ext-link.logout-link{color:#fca5a5;}
  .ext-link.logout-link:hover{background:rgba(239,68,68,.12); color:#ef4444;}
  
  .sidebar.collapsed .ext-link span{display:none !important;}
  .sidebar.collapsed .ext-link{justify-content:center; padding:9px 0;}
  
  .user-row{display:flex; align-items:center; gap:10px; padding:8px 12px; margin-top:2px; border-radius:6px;}
  .avatar{width:32px; height:32px; border-radius:50%; background:var(--gold); display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:600; flex-shrink:0; color:#fff;}
  .user-meta{font-size:12.5px; line-height:1.3; white-space:nowrap; overflow:hidden;}
  .user-meta .name{font-weight:600; color:#fff;}
  .user-meta .mail{color:#9AA8C0; font-size:11px;}
  .sidebar.collapsed .user-meta{display:none !important;}
  .sidebar.collapsed .user-row{justify-content:center; padding:8px 0;}

  /* MAIN CANVAS & TOPBAR */
  .main{flex:1; display:flex; flex-direction:column; overflow-y:auto; background:var(--cream); min-width:0; position:relative;}
  .topbar{display:flex; align-items:center; gap:14px; padding:14px 28px; background:#fff; border-bottom:1px solid var(--line); position:sticky; top:0; z-index:30; min-height:71px;}
  .topbar h1{font-family:'Fraunces',serif; font-size:18px; color:var(--navy); margin:0; white-space:nowrap;}
  
  .topbar-toggle-btn{background:none; border:1px solid var(--line); border-radius:6px; padding:6px; color:var(--navy); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s;}
  .topbar-toggle-btn:hover{background:var(--cream); border-color:var(--gold);}
  .topbar-toggle-btn svg{width:18px; height:18px;}

  .mobile-menu-btn{display:none; background:none; border:none; color:var(--navy); cursor:pointer; padding:4px;}
  @media(max-width:992px){
    .mobile-menu-btn{display:flex; align-items:center;}
    .sidebar{position:fixed; left:0; top:0; bottom:0; transform:translateX(-100%); transition:transform .3s ease, width .28s ease;}
    .sidebar.mobile-active{transform:translateX(0);}
    .sidebar-backdrop{position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:35; display:none;}
    .sidebar-backdrop.active{display:block;}
    .content{padding:20px 16px 36px !important;}
    .topbar{padding:12px 16px;}
    .search{width:180px !important;}
  }

  .search{margin-left:auto; display:flex; align-items:center; gap:8px; background:var(--cream); border:1px solid var(--line); border-radius:6px; padding:8px 12px; width:260px; transition:border-color .15s, box-shadow .15s;}
  .search:focus-within{border-color:var(--gold); box-shadow:0 0 0 2px rgba(198,137,46,.15);}
  .search svg{width:15px; height:15px; color:#9A968A; flex-shrink:0;}
  .search input{border:none; background:none; outline:none; font-size:13px; flex:1; color:var(--ink); min-width:0;}
  .kbd{font-size:10px; color:#9A968A; border:1px solid var(--line); border-radius:4px; padding:1px 5px; cursor:pointer; user-select:none;}
  
  .session-select{position:relative; display:flex; align-items:center; gap:6px; background:#fff; border:1px solid var(--line); border-radius:6px; padding:8px 12px; font-size:13px; font-weight:500; color:var(--navy); cursor:pointer; user-select:none; transition:border-color .15s;}
  .session-select:hover{border-color:var(--gold);}
  .session-select svg{width:14px; height:14px; flex-shrink:0;}
  
  .session-menu{display:none; position:absolute; top:calc(100% + 6px); right:0; width:160px; background:#fff; border:1px solid var(--line); border-radius:6px; box-shadow:0 10px 25px rgba(0,0,0,.1); z-index:50; overflow:hidden;}
  .session-menu.show{display:block;}
  .session-menu a{display:flex; align-items:center; justify-content:space-between; padding:9px 12px; font-size:12.5px; color:var(--ink); text-decoration:none; transition:background .15s;}
  .session-menu a:hover{background:var(--cream); color:var(--navy);}
  .session-menu a.active{font-weight:600; color:var(--gold); background:rgba(198,137,46,.08);}

  .icon-btn{width:36px; height:36px; border-radius:6px; border:1px solid var(--line); background:#fff; display:flex; align-items:center; justify-content:center; position:relative; cursor:pointer; transition:background .15s;}
  .icon-btn:hover{background:#F7F4EE;}
  .icon-btn svg{width:16px; height:16px; color:var(--ink-soft);}
  .dot-red{position:absolute; top:7px; right:8px; width:6px; height:6px; border-radius:50%; background:var(--red);}
  
  .user-chip{display:flex; align-items:center; gap:8px; padding:5px 12px 5px 6px; border-radius:20px; border:1px solid var(--line); background:#fff; cursor:pointer; font-size:13px; font-weight:500; color:var(--navy); text-decoration:none; transition:border-color .15s;}
  .user-chip:hover{border-color:var(--gold);}
  .user-chip .avatar{width:26px; height:26px; font-size:11px;}

  /* CONTENT WRAPPER */
  .content{padding:26px 28px 40px; flex:1;}
  .crumb{font-size:12.5px; color:#9A968A; margin-bottom:14px; display:flex; align-items:center; gap:6px;}
  .page-head{display:flex; align-items:flex-start; justify-content:space-between; gap:20px; margin-bottom:22px; flex-wrap:wrap;}
  .page-head h2{font-family:'Fraunces',serif; font-size:26px; color:var(--navy); margin:0 0 4px;}
  .page-head p{font-size:14px; color:var(--ink-soft); margin:0;}
  .qa{display:flex; gap:10px; flex-wrap:wrap;}
  .btn{border-radius:6px; padding:9px 16px; font-size:13.5px; font-weight:600; display:inline-flex; align-items:center; gap:7px; cursor:pointer; text-decoration:none; white-space:nowrap; border:1px solid transparent; transition:all .15s;}
  .btn svg{width:15px; height:15px;}
  .btn-primary{background:var(--navy); color:#fff;}
  .btn-primary:hover{background:var(--navy-deep); transform:translateY(-1px);}
  .btn-outline{background:#fff; border-color:var(--line); color:var(--navy);}
  .btn-outline:hover{border-color:var(--navy); background:rgba(19,38,68,.03);}

  /* KPI METRICS */
  .kpi-grid{display:grid; grid-template-columns:repeat(3,1fr); gap:18px; margin-bottom:22px;}
  @media(max-width:1024px){.kpi-grid{grid-template-columns:repeat(2,1fr);}}
  @media(max-width:640px){.kpi-grid{grid-template-columns:1fr;}}
  
  .kpi{background:#fff; border:1px solid var(--line); border-radius:8px; padding:20px; transition:transform .2s ease, box-shadow .2s ease;}
  .kpi:hover{transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,0,0,.04);}
  .kpi-top{display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;}
  .kpi-icon{width:38px; height:38px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;}
  .kpi-icon svg{width:19px; height:19px;}
  .trend{font-size:11.5px; font-weight:700; padding:3px 8px; border-radius:20px; display:flex; align-items:center; gap:3px;}
  .trend svg{width:11px; height:11px;}
  .trend.up{background:var(--green-bg); color:var(--green);}
  .trend.down-good{background:var(--green-bg); color:var(--green);}
  .trend.down-bad{background:var(--red-bg); color:var(--red);}
  .trend.flat{background:#F0EFEA; color:#8A867A;}
  .kpi-label{font-size:11.5px; letter-spacing:.03em; color:#8A867A; font-weight:600; text-transform:uppercase; margin-bottom:4px;}
  .kpi-num{font-family:'Fraunces',serif; font-size:28px; color:var(--navy); font-weight:600;}
  .kpi-sub{font-size:11.5px; color:#B08A2E; font-weight:600; margin-top:6px; display:flex; align-items:center; gap:4px;}

  /* LOWER PANELS */
  .lower-grid{display:grid; grid-template-columns:2fr 1fr; gap:18px;}
  @media(max-width:992px){.lower-grid{grid-template-columns:1fr;}}
  .panel{background:#fff; border:1px solid var(--line); border-radius:8px; padding:22px;}
  .panel-head{display:flex; align-items:center; justify-content:space-between; margin-bottom:18px;}
  .panel-head h3{font-family:'Fraunces',serif; font-size:16px; color:var(--navy); margin:0;}
  .view-all{font-size:12.5px; color:var(--navy); font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:4px;}
  .view-all:hover{color:var(--gold);}
  .bars{display:flex; align-items:flex-end; gap:14px; height:180px; padding-top:10px;}
  .bar-col{flex:1; display:flex; flex-direction:column; align-items:center; height:100%; justify-content:flex-end; gap:6px;}
  .bar{width:100%; background:var(--navy); border-radius:4px 4px 0 0; min-height:8px; transition:height .4s ease;}
  .bar.light{background:#C7CEDC;}
  .bar-lbl{font-size:10.5px; color:var(--ink-soft); font-weight:500;}

  .activity{list-style:none; margin:0; padding:0;}
  .activity li{display:flex; gap:12px; padding:12px 0; border-bottom:1px solid var(--line);}
  .activity li:last-child{border-bottom:none; padding-bottom:0;}
  .a-icon{width:32px; height:32px; border-radius:50%; background:var(--cream); display:flex; align-items:center; justify-content:center; flex-shrink:0;}
  .a-icon svg{width:15px; height:15px; color:var(--navy);}
  .a-text{font-size:13px; line-height:1.4;}
  .a-time{font-size:11.5px; color:#9A968A; margin-top:2px;}
</style>
</head>
<body>

<!-- Backdrop for Mobile Navigation -->
<div class="sidebar-backdrop" id="sidebar-backdrop" onclick="toggleMobileSidebar()"></div>

<!-- SIDEBAR COMPONENT (Expanded ~262px, Collapsed ~72px) -->
<aside class="sidebar" id="sidebar">
  
  <!-- Sidebar Header with Crest Logo, Wordmark, and Collapse Chevron Button -->
  <div class="side-head">
    <div class="crest" onclick="toggleSidebar()" title="Toggle Sidebar (Ctrl+B)">
      <img src="../assets/images/logo.jpg" alt="SOS Hermann Gmeiner School Logo"/>
    </div>
    <div class="side-title">
      Admin Portal
      <small>SOS Hermann Gmeiner</small>
    </div>
    <button class="collapse-btn" id="collapse-toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar (Ctrl+B)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
        <path d="M15 6l-6 6 6 6"/>
      </svg>
    </button>
  </div>

  <!-- Read-only Session Status Pill -->
  <div class="session-pill" title="Active Academic Session">
    <span class="dot"></span>
    <span><?= htmlspecialchars($activeSessionLabel) ?> active</span>
  </div>

  <!-- Sidebar Navigation with Labeled Groups & Accordion -->
  <nav class="side-nav">
    <!-- Top-level Dashboard Item -->
    <a class="nav-top-item <?= ($currentDash === 'dashboard' || $currentDash === 'index') ? 'active' : '' ?>" href="index.php" title="Dashboard">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/>
        <rect x="14" y="14" width="7" height="7" rx="1"/>
      </svg>
      <span>Dashboard</span>
    </a>

    <!-- GROUP 1: Academic Structure -->
    <?php if ($session->hasPermission('academic_setup', 'view') || $session->hasPermission('admissions', 'view') || $session->hasPermission('students', 'view')): ?>
    <div class="group">
      <div class="group-label">Academic Structure</div>

      <!-- Academic Setup -->
      <?php if ($session->hasPermission('academic_setup', 'view')): ?>
      <div class="group-head <?= $isAcademicSetupOpen ? 'open' : '' ?>" onclick="toggleAccordion(this)" title="Academic Setup">
        <svg class="gi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
          <path d="M6 12v5c3 3 9 3 12 0v-5"/>
        </svg>
        <span class="lbl">Academic Setup</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
      </div>
      <div class="sub <?= $isAcademicSetupOpen ? 'open' : '' ?>">
        <a href="sessions.php" class="<?= ($currentDash === 'sessions' || $currentDash === 'setup') ? 'active' : '' ?>">Sessions</a>
        <a href="classes.php" class="<?= $currentDash === 'classes' ? 'active' : '' ?>">Classes</a>
        <a href="sections.php" class="<?= $currentDash === 'sections' ? 'active' : '' ?>">Sections</a>
      </div>
      <?php endif; ?>

      <!-- Admissions & Enquiries -->
      <?php if ($session->hasPermission('admissions', 'view') || $session->hasPermission('enquiries', 'view') || $session->hasPermission('entry_tests', 'view')): ?>
      <div class="group-head <?= $isAdmissionsOpen ? 'open' : '' ?>" onclick="toggleAccordion(this)" title="Admissions">
        <svg class="gi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21a8 8 0 1 0-16 0"/>
          <circle cx="12" cy="8" r="5"/>
        </svg>
        <span class="lbl">Admissions</span>
        <span class="badge">CRM</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
      </div>
      <div class="sub <?= $isAdmissionsOpen ? 'open' : '' ?>">
        <?php if ($session->hasPermission('enquiries', 'view')): ?>
        <a href="enquiries.php" class="<?= $currentDash === 'enquiries' ? 'active font-semibold' : '' ?>" style="<?= $currentDash === 'enquiries' ? 'color:var(--gold-light);' : '' ?>">
          <span>Enquiries &amp; CRM</span>
          <span style="background:var(--gold); color:#fff; font-size:10px; font-weight:700; padding:1px 5px; border-radius:8px; margin-left:6px;">NEW</span>
        </a>
        <?php endif; ?>
        <?php if ($session->hasPermission('admissions', 'view')): ?>
        <a href="admissions.php" class="<?= $currentDash === 'admissions' ? 'active' : '' ?>">Admission Applications</a>
        <a href="admission-cycles.php" class="<?= $currentDash === 'admission-cycles' ? 'active' : '' ?>">Cycles &amp; Open Admissions</a>
        <a href="admission-history.php" class="<?= $currentDash === 'admission-history' ? 'active' : '' ?>">Admissions History</a>
        <?php endif; ?>
        <?php if ($session->hasPermission('entry_tests', 'view')): ?>
        <a href="entry-tests.php" class="<?= $currentDash === 'entry-tests' ? 'active' : '' ?>">Entry Tests</a>
        <?php endif; ?>
        <a href="careers.php" class="<?= $currentDash === 'careers' ? 'active' : '' ?>">Careers &amp; Job Postings</a>
      </div>
      <?php endif; ?>

      <!-- Students -->
      <?php if ($session->hasPermission('students', 'view') || $session->hasPermission('enrollment', 'view')): ?>
      <div class="group-head <?= $isStudentsOpen ? 'open' : '' ?>" onclick="toggleAccordion(this)" title="Students">
        <svg class="gi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
        </svg>
        <span class="lbl">Students</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
      </div>
      <div class="sub <?= $isStudentsOpen ? 'open' : '' ?>">
        <?php if ($session->hasPermission('students', 'view')): ?>
        <a href="students.php" class="<?= $currentDash === 'students' ? 'active' : '' ?>">All Students</a>
        <a href="guardians.php" class="<?= $currentDash === 'guardians' ? 'active' : '' ?>">Guardians &amp; SOS Home</a>
        <a href="student-documents.php" class="<?= $currentDash === 'student-documents' ? 'active' : '' ?>">Document Vault</a>
        <?php endif; ?>
        <?php if ($session->hasPermission('enrollment', 'view')): ?>
        <a href="enrollment.php" class="<?= $currentDash === 'enrollment' ? 'active' : '' ?>">Enrollment Form</a>
        <a href="promotions.php" class="<?= $currentDash === 'promotions' ? 'active' : '' ?>">Promotion &amp; Transition</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- GROUP 2: Financial Operations -->
    <?php if ($session->hasPermission('fee_structure', 'view') || $session->hasPermission('fee_charges', 'view') || $session->hasPermission('challans', 'view') || $session->hasPermission('payments', 'view')): ?>
    <div class="group">
      <div class="group-label">Financial Operations</div>

      <!-- Fee Management -->
      <?php if ($session->hasPermission('fee_structure', 'view') || $session->hasPermission('fee_charges', 'view') || $session->hasPermission('concessions', 'view') || $session->hasPermission('fines', 'view')): ?>
      <div class="group-head <?= $isFeesOpen ? 'open' : '' ?>" onclick="toggleAccordion(this)" title="Fee Management">
        <svg class="gi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="5" width="20" height="14" rx="2"/>
          <path d="M2 10h20"/>
        </svg>
        <span class="lbl">Fee Management</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
      </div>
      <div class="sub <?= $isFeesOpen ? 'open' : '' ?>">
        <a href="fee-types.php" class="<?= $currentDash === 'fee-types' ? 'active' : '' ?>">Fee Categories</a>
        <a href="fee-structures.php" class="<?= $currentDash === 'fee-structures' ? 'active' : '' ?>">Fee Structures</a>
        <a href="fees.php" class="<?= $currentDash === 'fees' ? 'active' : '' ?>">Monthly Billing</a>
        <a href="concessions.php" class="<?= $currentDash === 'concessions' ? 'active' : '' ?>">Scholarships &amp; Concessions</a>
        <a href="adjustments.php" class="<?= $currentDash === 'adjustments' ? 'active' : '' ?>">Adjustments &amp; Waivers</a>
        <a href="fines.php" class="<?= $currentDash === 'fines' ? 'active' : '' ?>">Late Fines &amp; Dues</a>
      </div>
      <?php endif; ?>

      <!-- Challans & Banking -->
      <?php if ($session->hasPermission('challans', 'view') || $session->hasPermission('payments', 'view')): ?>
      <div class="group-head <?= $isChallansOpen ? 'open' : '' ?>" onclick="toggleAccordion(this)" title="Challans & Banking">
        <svg class="gi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 4h16v4H4zM4 12h16v8H4z"/>
        </svg>
        <span class="lbl">Challans &amp; Banking</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
      </div>
      <div class="sub <?= $isChallansOpen ? 'open' : '' ?>">
        <a href="challans.php" class="<?= $currentDash === 'challans' ? 'active' : '' ?>">Challans (3-Part)</a>
        <a href="payments.php" class="<?= $currentDash === 'payments' ? 'active' : '' ?>">Pending Bank Verification</a>
        <a href="receipts.php" class="<?= $currentDash === 'receipts' ? 'active' : '' ?>">Official Receipts</a>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- GROUP 3: Intelligence & System -->
    <div class="group">
      <div class="group-label">Intelligence &amp; System</div>

      <!-- Reports & Analytics -->
      <?php if ($session->hasPermission('reports', 'view') || $session->hasPermission('audit_logs', 'view')): ?>
      <div class="group-head <?= $isReportsOpen ? 'open' : '' ?>" onclick="toggleAccordion(this)" title="Reports & Analytics">
        <svg class="gi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 3v18h18"/>
          <path d="M18 17V9M13 17V5M8 17v-3"/>
        </svg>
        <span class="lbl">Reports &amp; Analytics</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
      </div>
      <div class="sub <?= $isReportsOpen ? 'open' : '' ?>">
        <a href="reports.php" class="<?= $currentDash === 'reports' ? 'active' : '' ?>">Comprehensive Analytics</a>
        <a href="fee-reports.php" class="<?= $currentDash === 'fee-reports' ? 'active' : '' ?>">Fee Collection &amp; Defaulters</a>
        <a href="student-reports.php" class="<?= $currentDash === 'student-reports' ? 'active' : '' ?>">Enrollment &amp; Retention</a>
        <a href="audit-logs.php" class="<?= $currentDash === 'audit-logs' ? 'active' : '' ?>">Audit Trail</a>
      </div>
      <?php endif; ?>

      <!-- System Control -->
      <?php if ($session->hasPermission('users', 'view') || $session->hasPermission('roles', 'view') || $session->hasPermission('settings', 'view')): ?>
      <div class="group-head <?= $isSystemOpen ? 'open' : '' ?>" onclick="toggleAccordion(this)" title="System Control">
        <svg class="gi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3"/>
          <path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/>
        </svg>
        <span class="lbl">System Control</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
      </div>
      <div class="sub <?= $isSystemOpen ? 'open' : '' ?>">
        <?php if ($session->hasPermission('users', 'view')): ?>
        <a href="users.php" class="<?= $currentDash === 'users' ? 'active' : '' ?>">User Accounts</a>
        <?php endif; ?>
        <?php if ($session->hasPermission('roles', 'view')): ?>
        <a href="roles.php" class="<?= $currentDash === 'roles' ? 'active' : '' ?>">RBAC Roles &amp; Permissions</a>
        <?php endif; ?>
        <?php if ($session->hasPermission('settings', 'view')): ?>
        <a href="settings.php" class="<?= $currentDash === 'settings' ? 'active' : '' ?>">Campus Settings</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Sidebar Footer with External Link, User Row, and Logout Link -->
  <div class="side-foot">
    <a class="ext-link" href="../index.php" target="_blank" title="View Public Website">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
        <path d="M15 3h6v6M10 14L21 3"/>
      </svg>
      <span>View Public Website</span>
    </a>

    <a class="ext-link logout-link" href="process.php" title="Sign Out of Portal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      <span>Sign Out / Log Out</span>
    </a>

    <div class="user-row" title="Logged in as <?= htmlspecialchars($session->role_name) ?>">
      <div class="avatar"><?= htmlspecialchars(strtoupper(substr($full_name, 0, 1) ?: 'A')) ?></div>
      <div class="user-meta">
        <div class="name"><?php echo htmlspecialchars($full_name); ?></div>
        <div class="mail"><?php echo htmlspecialchars($session->role_name ?: 'Administrator'); ?></div>
      </div>
    </div>
  </div>
</aside>

<!-- MAIN APPLICATION CANVAS -->
<div class="main">
  
  <!-- TOPBAR (Single Source of Truth for Session Switcher) -->
  <div class="topbar">
    <!-- Mobile Hamburger Toggle -->
    <button class="mobile-menu-btn" onclick="toggleMobileSidebar()" aria-label="Toggle navigation">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <!-- Topbar Sidebar Expand/Collapse Toggle Button -->
    <button class="topbar-toggle-btn hidden lg:flex" onclick="toggleSidebar()" title="Toggle Sidebar (Ctrl+B)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="18" height="18" rx="2"/>
        <path d="M9 3v18"/>
      </svg>
    </button>

    <!-- Page Title -->
    <h1><?= htmlspecialchars($dashTitle) ?></h1>

    <!-- Global Quick Search with ⌘K Shortcut Chip -->
    <div class="search">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/>
        <path d="M21 21l-4.3-4.3"/>
      </svg>
      <input id="dash-search-input" placeholder="Quick search students, applications…" />
      <span class="kbd" onclick="document.getElementById('dash-search-input').focus()">⌘K</span>
    </div>

    <!-- Active Academic Session Selector (The Single Source of Truth) -->
    <div class="session-select" onclick="toggleSessionDropdown(event)">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <path d="M16 2v4M8 2v4M3 10h18"/>
      </svg>
      <span id="selected-session-label"><?= htmlspecialchars($activeSessionLabel) ?></span>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 9l6 6 6-6"/>
      </svg>
      <div class="session-menu" id="session-dropdown-menu">
        <a href="#" class="active" onclick="switchSession('2026–27', event)"><span>2026–27 (Active)</span><span>✓</span></a>
        <a href="#" onclick="switchSession('2025–26', event)"><span>2025–26 (Archive)</span></a>
        <a href="#" onclick="switchSession('2024–25', event)"><span>2024–25 (Archive)</span></a>
      </div>
    </div>

    <!-- Notifications Bell with Unread Red Dot -->
    <button class="icon-btn" title="Pending Notifications" onclick="alert('3 new notifications:\n• 4 applicants submitted docs\n• Bank deposit batch ready\n• New session fee checklist')">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.7 21a2 2 0 0 1-3.4 0"/>
      </svg>
      <span class="dot-red"></span>
    </button>

    <!-- User Avatar Chip -->
    <a href="#" class="user-chip" title="Account settings">
      <div class="avatar">A</div>
      <span><?php echo $full_name; ?></span>
    </a>
  </div>

  <!-- Content Container opened here; closed in footer.php -->
  <div class="content">
