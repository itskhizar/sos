<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Reports & Analytics Hub — dashboard/reports.php
 * Aligned with Stitch Master Design
 */
$dashTitle = 'Reports Hub';
$currentDash = 'reports';
include 'includes/header.php';
?>

<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
  <div>
    <div class="flex items-center gap-2 text-xs text-[#54524B] mb-1">
      <span>Analytics</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#002452] font-semibold">Reports Hub</span>
    </div>
    <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Reports &amp; Analytics Hub</h1>
  </div>
  <button class="flex items-center gap-2 px-4 py-2 rounded bg-[#002452] text-white font-semibold text-xs md:text-sm hover:bg-[#002452]/90 transition-colors shadow-sm">
    <span class="material-symbols-outlined text-[18px]">download</span>
    Generate Annual Audit Pack
  </button>
</div>

<!-- Report Categories Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  
  <!-- Financial Reports -->
  <div class="bg-white rounded-xl border border-[#e3e2df] p-6 shadow-xs flex flex-col justify-between">
    <div>
      <div class="w-12 h-12 rounded-lg bg-[#002c15]/10 text-[#002c15] flex items-center justify-center mb-4">
        <span class="material-symbols-outlined text-[26px]">account_balance</span>
      </div>
      <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-2">Financial &amp; Revenue Reports</h3>
      <p class="text-xs text-[#54524B] leading-relaxed mb-6">Comprehensive monthly fee reconciliations, bank receipts verification, and fee defaulters ledger.</p>
    </div>
    <div class="space-y-2 border-t border-[#e3e2df] pt-4">
      <a href="#" class="flex justify-between items-center text-xs font-semibold text-[#002452] hover:text-[#885200] p-2 hover:bg-gray-50 rounded">
        <span>Monthly Fee Collection Ledger</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
      <a href="#" class="flex justify-between items-center text-xs font-semibold text-[#002452] hover:text-[#885200] p-2 hover:bg-gray-50 rounded">
        <span>Outstanding Defaulters Summary</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
      <a href="#" class="flex justify-between items-center text-xs font-semibold text-[#002452] hover:text-[#885200] p-2 hover:bg-gray-50 rounded">
        <span>SOS Beneficiary Waivers Audit</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
    </div>
  </div>

  <!-- Academic & Enrollment -->
  <div class="bg-white rounded-xl border border-[#e3e2df] p-6 shadow-xs flex flex-col justify-between">
    <div>
      <div class="w-12 h-12 rounded-lg bg-[#002452]/10 text-[#002452] flex items-center justify-center mb-4">
        <span class="material-symbols-outlined text-[26px]">school</span>
      </div>
      <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-2">Academic &amp; Student Reports</h3>
      <p class="text-xs text-[#54524B] leading-relaxed mb-6">Class strength ratios, BISE board examinations passing results, and student attendance analytics.</p>
    </div>
    <div class="space-y-2 border-t border-[#e3e2df] pt-4">
      <a href="#" class="flex justify-between items-center text-xs font-semibold text-[#002452] hover:text-[#885200] p-2 hover:bg-gray-50 rounded">
        <span>Enrollment by Class &amp; Gender</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
      <a href="#" class="flex justify-between items-center text-xs font-semibold text-[#002452] hover:text-[#885200] p-2 hover:bg-gray-50 rounded">
        <span>BISE Board Exam Performance (2024)</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
      <a href="#" class="flex justify-between items-center text-xs font-semibold text-[#002452] hover:text-[#885200] p-2 hover:bg-gray-50 rounded">
        <span>Term-wise Attendance Analytics</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
    </div>
  </div>

  <!-- Admissions & Intake -->
  <div class="bg-white rounded-xl border border-[#e3e2df] p-6 shadow-xs flex flex-col justify-between">
    <div>
      <div class="w-12 h-12 rounded-lg bg-[#885200]/10 text-[#885200] flex items-center justify-center mb-4">
        <span class="material-symbols-outlined text-[26px]">person_add</span>
      </div>
      <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-2">Admissions &amp; Intake</h3>
      <p class="text-xs text-[#54524B] leading-relaxed mb-6">Registration conversion funnels, test score distribution curve, and quota seat allocations.</p>
    </div>
    <div class="space-y-2 border-t border-[#e3e2df] pt-4">
      <a href="#" class="flex justify-between items-center text-xs font-semibold text-[#002452] hover:text-[#885200] p-2 hover:bg-gray-50 rounded">
        <span>Session 2026–27 Intake Funnel</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
      <a href="#" class="flex justify-between items-center text-xs font-semibold text-[#002452] hover:text-[#885200] p-2 hover:bg-gray-50 rounded">
        <span>Entrance Test Score Distribution</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
      <a href="#" class="flex justify-between items-center text-xs font-semibold text-[#002452] hover:text-[#885200] p-2 hover:bg-gray-50 rounded">
        <span>Document Verification Turnaround</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
    </div>
  </div>

</div>

<?php include 'includes/footer.php'; ?>
