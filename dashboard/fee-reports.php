<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Fee Reports — dashboard/fee-reports.php
 * Module 12: Financial & Fee Analytics, Defaulters, Collection Breakdowns
 */
$dashTitle   = 'Fee Reports';
$currentDash = 'fee-reports';
include 'includes/header.php';
?>

<!-- Header & Export Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Financial & Fee Intelligence Reports</h2>
    <p class="text-sm text-[#44474f] mt-1">Monthly collection analytics, outstanding fee defaulter lists, and concession audits.</p>
  </div>
  <div class="flex items-center gap-3">
    <button class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-[#002452] text-[#002452] rounded-lg font-semibold text-sm hover:bg-[#002452]/5 transition-colors shadow-sm">
      <span class="material-symbols-outlined text-[18px]">download</span>
      Export Excel
    </button>
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm">
      <span class="material-symbols-outlined text-[18px]">print</span>
      Print Report
    </button>
  </div>
</div>

<!-- Filters Bar -->
<div class="bg-white rounded-xl border border-[#e3e2df] p-4 shadow-sm flex flex-col md:flex-row gap-3">
  <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-sm bg-white focus:outline-none focus:border-[#002452]">
    <option>Session: 2026-2027</option>
    <option>Session: 2025-2026</option>
  </select>
  <input type="month" value="2027-01" class="px-3 py-2 border border-[#e3e2df] rounded-lg text-sm bg-white focus:outline-none focus:border-[#002452]"/>
  <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-sm bg-white focus:outline-none focus:border-[#002452]">
    <option>All Classes</option>
    <option>Class X</option>
    <option>Class IX</option>
    <option>Middle (VI - VIII)</option>
    <option>Primary (I - V)</option>
    <option>Early Years (Nursery, KG)</option>
  </select>
</div>

<!-- Financial KPI Summary -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Gross Billed</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">Rs. 1,850,000</p>
    <p class="text-[11px] text-[#747780] mt-1">520 enrolled scholars</p>
  </div>
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <p class="text-xs text-[#00522d] font-semibold uppercase tracking-wider">Total Realized (Paid)</p>
    <p class="font-serif text-2xl font-bold text-[#00522d] mt-1">Rs. 1,482,000</p>
    <p class="text-[11px] text-[#00522d] mt-1 font-semibold">80.1% collection efficiency</p>
  </div>
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <p class="text-xs text-[#885200] font-semibold uppercase tracking-wider">Total Concessions</p>
    <p class="font-serif text-2xl font-bold text-[#885200] mt-1">Rs. 245,000</p>
    <p class="text-[11px] text-[#747780] mt-1">68 sponsored students</p>
  </div>
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <p class="text-xs text-[#ba1a1a] font-semibold uppercase tracking-wider">Defaulters / Arrears</p>
    <p class="font-serif text-2xl font-bold text-[#ba1a1a] mt-1">Rs. 123,000</p>
    <p class="text-[11px] text-[#ba1a1a] mt-1 font-semibold">32 overdue challans</p>
  </div>
</div>

<!-- Class-wise Fee Realization Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-[#e3e2df]">
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Class-Wise Collection Breakdown (January 2027)</h3>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#002452] text-white text-[11px] uppercase tracking-wider">
        <tr>
          <th class="px-4 py-3 text-left font-semibold">Class Group</th>
          <th class="px-4 py-3 text-right font-semibold">Students</th>
          <th class="px-4 py-3 text-right font-semibold">Gross Demand</th>
          <th class="px-4 py-3 text-right font-semibold">Concessions</th>
          <th class="px-4 py-3 text-right font-semibold">Net Collectable</th>
          <th class="px-4 py-3 text-right font-semibold">Collected</th>
          <th class="px-4 py-3 text-right font-semibold">Outstanding</th>
          <th class="px-4 py-3 text-center font-semibold">Recovery %</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php
        $classReports = [
          ['Class X', 83, 498000, 48000, 450000, 395000, 55000, 87.7],
          ['Class IX', 86, 516000, 52000, 464000, 410000, 54000, 88.3],
          ['Class VIII', 76, 380000, 35000, 345000, 298000, 47000, 86.3],
          ['Class VII', 35, 175000, 20000, 155000, 140000, 15000, 90.3],
          ['Class VI', 74, 370000, 40000, 330000, 285000, 45000, 86.3],
          ['Class V', 63, 252000, 25000, 227000, 205000, 22000, 90.3],
          ['Class IV', 27, 108000, 10000, 98000, 92000, 6000, 93.8],
          ['Class III', 64, 256000, 28000, 228000, 210000, 18000, 92.1],
          ['Class II', 65, 227500, 22000, 205500, 192000, 13500, 93.4],
          ['Class I', 95, 332500, 35000, 297500, 280000, 17500, 94.1],
        ];
        foreach ($classReports as [$cName, $cCount, $gross, $conc, $net, $paid, $due, $rec]):
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-4 py-3.5 font-bold text-[#1b1c1a]"><?= $cName ?></td>
          <td class="px-4 py-3.5 text-right text-[#44474f]"><?= $cCount ?></td>
          <td class="px-4 py-3.5 text-right font-medium text-[#1b1c1a]">Rs. <?= number_format($gross) ?></td>
          <td class="px-4 py-3.5 text-right text-[#885200]">Rs. <?= number_format($conc) ?></td>
          <td class="px-4 py-3.5 text-right font-semibold text-[#002452]">Rs. <?= number_format($net) ?></td>
          <td class="px-4 py-3.5 text-right font-bold text-[#00522d]">Rs. <?= number_format($paid) ?></td>
          <td class="px-4 py-3.5 text-right font-bold text-[#ba1a1a]">Rs. <?= number_format($due) ?></td>
          <td class="px-4 py-3.5 text-center">
            <div class="inline-flex items-center gap-1.5">
              <div class="w-12 bg-gray-200 rounded-full h-2">
                <div class="bg-[#002452] h-2 rounded-full" style="width: <?= $rec ?>%"></div>
              </div>
              <span class="text-xs font-semibold text-[#1b1c1a]"><?= $rec ?>%</span>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
