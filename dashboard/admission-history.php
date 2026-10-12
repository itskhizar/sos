<?php
/**
 * Admission Status History — dashboard/admission-history.php
 */
$dashTitle   = 'Admission Status History';
$currentDash = 'admission-history';
include 'includes/header.php';
?>

<!-- Filters -->
<div class="bg-white rounded-xl border border-[#e3e2df] p-4 shadow-sm">
  <div class="flex flex-col md:flex-row gap-3">
    <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-sm bg-white focus:outline-none focus:border-[#002452]">
      <option>All Cycles</option>
      <option>2027-28</option>
      <option>2026-27</option>
    </select>
    <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-sm bg-white focus:outline-none focus:border-[#002452]">
      <option>All Statuses</option>
      <option>Pending</option>
      <option>Verified</option>
      <option>Approved</option>
      <option>Rejected</option>
      <option>Cancelled</option>
    </select>
    <div class="relative flex-1">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" placeholder="Search by name or application ID…" class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </div>
    <input type="date" class="px-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
  </div>
</div>

<!-- History Timeline Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex items-center justify-between">
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Status Change History</h3>
    <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-[#e3e2df] rounded-lg text-xs font-semibold text-[#44474f] hover:bg-[#f4f3f0] transition-colors">
      <span class="material-symbols-outlined text-[15px]">download</span>
      Export
    </button>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-6 py-3 text-left font-semibold">Application ID</th>
          <th class="px-4 py-3 text-left font-semibold">Applicant</th>
          <th class="px-4 py-3 text-left font-semibold">Previous Status</th>
          <th class="px-4 py-3 text-left font-semibold">New Status</th>
          <th class="px-4 py-3 text-left font-semibold">Changed By</th>
          <th class="px-4 py-3 text-left font-semibold">Remarks</th>
          <th class="px-4 py-3 text-left font-semibold">Date & Time</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php
        $history = [
          ['APP-2027-001','Ahmad Bilal',    'Pending',  'Verified',  'Admin',  'Documents verified', '2027-01-10 09:15'],
          ['APP-2027-001','Ahmad Bilal',    'Verified', 'Approved',  'Admin',  'Entry test passed',  '2027-01-16 14:30'],
          ['APP-2027-002','Sara Malik',     'Pending',  'Verified',  'Admin',  'All docs OK',        '2027-01-11 10:00'],
          ['APP-2027-002','Sara Malik',     'Verified', 'Approved',  'Admin',  'Test score 92%',     '2027-01-16 14:45'],
          ['APP-2027-003','Hassan Ali',     'Pending',  'Verified',  'Staff1', 'Documents verified', '2027-01-12 11:20'],
          ['APP-2027-003','Hassan Ali',     'Verified', 'Rejected',  'Admin',  'Failed entry test',  '2027-01-16 15:00'],
          ['APP-2027-004','Fatima Noor',    'Pending',  'Pending',   'System', 'Application received','2027-01-05 08:00'],
          ['APP-2026-088','Usman Khan',     'Approved', 'Cancelled', 'Admin',  'Withdrew application','2026-06-01 09:30'],
        ];
        $statusColors = [
          'Pending'   => 'bg-[#ffdcbb] text-[#673d00]',
          'Verified'  => 'bg-[#002452]/10 text-[#002452]',
          'Approved'  => 'bg-[#9af6b8]/40 text-[#00522d]',
          'Rejected'  => 'bg-[#ffdad6] text-[#93000a]',
          'Cancelled' => 'bg-[#e3e2df] text-[#44474f]',
        ];
        foreach ($history as [$id,$name,$prev,$next,$by,$remarks,$dt]):
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-6 py-4 font-mono text-xs text-[#002452] font-semibold"><?= $id ?></td>
          <td class="px-4 py-4 font-semibold text-[#1b1c1a]"><?= $name ?></td>
          <td class="px-4 py-4">
            <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold <?= $statusColors[$prev] ?>"><?= $prev ?></span>
          </td>
          <td class="px-4 py-4">
            <div class="flex items-center gap-1.5">
              <span class="material-symbols-outlined text-[14px] text-[#c4c6d0]">arrow_forward</span>
              <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold <?= $statusColors[$next] ?>"><?= $next ?></span>
            </div>
          </td>
          <td class="px-4 py-4 text-[#44474f]"><?= $by ?></td>
          <td class="px-4 py-4 text-[#44474f] max-w-[200px] truncate"><?= $remarks ?></td>
          <td class="px-4 py-4 text-[#747780] text-xs whitespace-nowrap"><?= $dt ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <!-- Pagination -->
  <div class="px-6 py-3 border-t border-[#e3e2df] flex items-center justify-between text-xs text-[#44474f]">
    <span>Showing 8 of 248 records</span>
    <div class="flex items-center gap-1">
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0] disabled:opacity-50" disabled>Prev</button>
      <button class="px-2.5 py-1 rounded bg-[#002452] text-white">1</button>
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0]">2</button>
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0]">3</button>
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0]">Next</button>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
