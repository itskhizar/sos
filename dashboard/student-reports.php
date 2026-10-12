<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Student Demographics & Academic Reports — dashboard/student-reports.php
 * Module 12: Enrollment stats, gender ratios, SOS village ward percentages, attendance
 */
$dashTitle   = 'Student Reports';
$currentDash = 'student-reports';
include 'includes/header.php';
?>

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Student Demographics & Analytics</h2>
    <p class="text-sm text-[#44474f] mt-1">Enrollment distributions, gender parity indices, SOS Village residency records, and regional diversity.</p>
  </div>
  <div class="flex items-center gap-3">
    <button class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-[#002452] text-[#002452] rounded-lg font-semibold text-sm hover:bg-[#002452]/5 transition-colors shadow-sm">
      <span class="material-symbols-outlined text-[18px]">download</span>
      Export Data
    </button>
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm">
      <span class="material-symbols-outlined text-[18px]">print</span>
      Print Report
    </button>
  </div>
</div>

<!-- Key Demographic KPIs -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#002452]/10 text-[#002452]">
        <span class="material-symbols-outlined text-[22px]">groups</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">Active</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Total Enrolled Scholars</p>
    <p class="font-serif text-3xl font-bold text-[#1b1c1a] mt-1">520</p>
    <p class="text-[11px] text-[#747780] mt-1">Nursery to Class X</p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#885200]/10 text-[#885200]">
        <span class="material-symbols-outlined text-[22px]">home</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#ffdcbb] text-[#673d00]">SOS Village</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">SOS Village Residents</p>
    <p class="font-serif text-3xl font-bold text-[#1b1c1a] mt-1">118</p>
    <p class="text-[11px] text-[#885200] mt-1 font-semibold">22.7% of total student body</p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#004424]/10 text-[#004424]">
        <span class="material-symbols-outlined text-[22px]">wc</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">54% : 46%</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Boys / Girls Ratio</p>
    <p class="font-serif text-3xl font-bold text-[#1b1c1a] mt-1">281 / 239</p>
    <p class="text-[11px] text-[#747780] mt-1">Healthy gender parity</p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#002452]/10 text-[#002452]">
        <span class="material-symbols-outlined text-[22px]">badge</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#002452]/10 text-[#002452]">Capacity</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Overall Campus Utilization</p>
    <p class="font-serif text-3xl font-bold text-[#1b1c1a] mt-1">86.7%</p>
    <p class="text-[11px] text-[#747780] mt-1">Capacity 600 scholars</p>
  </div>
</div>

<!-- Class Breakdown Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-[#e3e2df]">
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Class Strength & Demographic Distribution</h3>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10.5px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-4 py-3 text-left font-semibold">Grade / Class</th>
          <th class="px-4 py-3 text-center font-semibold">Sections</th>
          <th class="px-4 py-3 text-right font-semibold">Male</th>
          <th class="px-4 py-3 text-right font-semibold">Female</th>
          <th class="px-4 py-3 text-right font-semibold">Total Scholars</th>
          <th class="px-4 py-3 text-right font-semibold">SOS Wards</th>
          <th class="px-4 py-3 text-right font-semibold">Private Scholars</th>
          <th class="px-4 py-3 text-right font-semibold">Capacity</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php
        $stats = [
          ['Class X', 2, 45, 38, 83, 19, 64, 90],
          ['Class IX', 2, 47, 39, 86, 21, 65, 90],
          ['Class VIII', 2, 41, 35, 76, 17, 59, 80],
          ['Class VII', 1, 19, 16, 35, 8, 27, 40],
          ['Class VI', 2, 40, 34, 74, 18, 56, 80],
          ['Class V', 2, 33, 30, 63, 14, 49, 70],
          ['Class IV', 1, 15, 12, 27, 5, 22, 35],
          ['Class III', 2, 35, 29, 64, 10, 54, 70],
          ['Class II', 2, 34, 31, 65, 4, 61, 70],
          ['Class I', 3, 50, 45, 95, 2, 93, 105],
        ];
        foreach ($stats as [$gName, $sec, $m, $f, $tot, $sos, $priv, $cap]):
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-4 py-3.5 font-bold text-[#1b1c1a]"><?= $gName ?></td>
          <td class="px-4 py-3.5 text-center font-semibold text-[#002452]"><?= $sec ?></td>
          <td class="px-4 py-3.5 text-right text-[#44474f]"><?= $m ?></td>
          <td class="px-4 py-3.5 text-right text-[#44474f]"><?= $f ?></td>
          <td class="px-4 py-3.5 text-right font-bold text-[#1b1c1a]"><?= $tot ?></td>
          <td class="px-4 py-3.5 text-right font-semibold text-[#885200]"><?= $sos ?></td>
          <td class="px-4 py-3.5 text-right text-[#44474f]"><?= $priv ?></td>
          <td class="px-4 py-3.5 text-right font-medium text-[#747780]"><?= $cap ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
