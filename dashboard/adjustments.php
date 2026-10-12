<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Fee Adjustments — dashboard/adjustments.php
 * Module 8: Fee Adjustments (Addition/Deduction, approval workflows)
 */
$dashTitle   = 'Fee Adjustments';
$currentDash = 'adjustments';
include 'includes/header.php';
?>

<!-- Header & Quick Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Fee Adjustments</h2>
    <p class="text-sm text-[#44474f] mt-1">Record discretionary debit/credit adjustments on student monthly billing accounts.</p>
  </div>
  <button onclick="document.getElementById('modal-new-adjustment').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">add_circle</span>
    New Adjustment
  </button>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#004424]/10 text-[#004424]">
        <span class="material-symbols-outlined text-[22px]">trending_down</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">Credit</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Total Deductions</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">Rs. 84,500</p>
    <p class="text-[11px] text-[#747780] mt-1">22 student accounts</p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#885200]/10 text-[#885200]">
        <span class="material-symbols-outlined text-[22px]">trending_up</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#ffdcbb] text-[#673d00]">Debit</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Total Additions</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">Rs. 32,200</p>
    <p class="text-[11px] text-[#747780] mt-1">9 student accounts</p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#002452]/10 text-[#002452]">
        <span class="material-symbols-outlined text-[22px]">task_alt</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">Approved</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Approved Requests</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">28</p>
    <p class="text-[11px] text-[#747780] mt-1">By accounts supervisor</p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#ba1a1a]/10 text-[#ba1a1a]">
        <span class="material-symbols-outlined text-[22px]">hourglass_top</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#ffdad6] text-[#93000a]">Pending</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Awaiting Principal Sign</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">3</p>
    <p class="text-[11px] text-[#747780] mt-1">Needs verification</p>
  </div>
</div>

<!-- Adjustments Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row md:items-center justify-between gap-3">
    <div class="relative flex-1 max-w-md">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" placeholder="Search by student, reason or ref ID..." class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </div>
    <div class="flex items-center gap-2">
      <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-xs bg-white text-[#44474f]">
        <option>All Types</option>
        <option>Deduction (Credit)</option>
        <option>Addition (Debit)</option>
      </select>
      <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-xs bg-white text-[#44474f]">
        <option>All Status</option>
        <option>Approved</option>
        <option>Pending</option>
        <option>Rejected</option>
      </select>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10.5px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-4 py-3 text-left font-semibold">Ref ID</th>
          <th class="px-4 py-3 text-left font-semibold">Student</th>
          <th class="px-4 py-3 text-left font-semibold">Billing Month</th>
          <th class="px-4 py-3 text-left font-semibold">Type</th>
          <th class="px-4 py-3 text-right font-semibold">Amount</th>
          <th class="px-4 py-3 text-left font-semibold">Reason</th>
          <th class="px-4 py-3 text-left font-semibold">Approved By</th>
          <th class="px-4 py-3 text-center font-semibold">Status</th>
          <th class="px-4 py-3 text-center font-semibold">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php
        $adjustments = [
          ['ADJ-2027-019', 'Ahmad Bilal (Class VI)', 'Jan 2027', 'Deduction', 1500, 'Duplicate sports fee reversal', 'Principal Office', 'Approved'],
          ['ADJ-2027-018', 'Sara Malik (Class I)', 'Jan 2027', 'Addition', 800, 'Lost ID Card re-issuance fee', 'Admin Staff', 'Approved'],
          ['ADJ-2027-017', 'Hamza Tariq (Class IV)', 'Jan 2027', 'Deduction', 2000, 'Late fee waiver due to medical leave', 'Principal Office', 'Approved'],
          ['ADJ-2027-016', 'Zainab Hussain (KG)', 'Jan 2027', 'Deduction', 1200, 'Mid-month enrollment pro-rated adjustment', 'Accounts Office', 'Approved'],
          ['ADJ-2027-015', 'Kashif Ali (Class V)', 'Feb 2027', 'Deduction', 2500, 'Special hardship assistance recommendation', 'Pending Approval', 'Pending'],
          ['ADJ-2027-014', 'Usman Khan (Class IX)', 'Feb 2027', 'Addition', 1500, 'Lab equipment break charge assessment', 'Pending Approval', 'Pending'],
        ];
        foreach ($adjustments as [$ref, $stu, $month, $type, $amt, $reason, $approver, $status]):
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-4 py-3.5 font-mono text-xs font-semibold text-[#002452]"><?= $ref ?></td>
          <td class="px-4 py-3.5 font-semibold text-[#1b1c1a]"><?= $stu ?></td>
          <td class="px-4 py-3.5 text-[#44474f] text-xs"><?= $month ?></td>
          <td class="px-4 py-3.5">
            <?php if ($type === 'Deduction'): ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded bg-[#004424]/10 text-[#004424]">
                <span class="material-symbols-outlined text-[13px]">remove</span> Deduction
              </span>
            <?php else: ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded bg-[#885200]/10 text-[#885200]">
                <span class="material-symbols-outlined text-[13px]">add</span> Addition
              </span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3.5 text-right font-bold <?= $type === 'Deduction' ? 'text-[#004424]' : 'text-[#885200]' ?>">
            <?= $type === 'Deduction' ? '- ' : '+ ' ?>Rs. <?= number_format($amt) ?>
          </td>
          <td class="px-4 py-3.5 text-xs text-[#44474f] max-w-xs truncate"><?= $reason ?></td>
          <td class="px-4 py-3.5 text-xs text-[#747780]"><?= $approver ?></td>
          <td class="px-4 py-3.5 text-center">
            <?php if ($status === 'Approved'): ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">
                <span class="w-1.5 h-1.5 rounded-full bg-[#00522d]"></span> Approved
              </span>
            <?php else: ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#ffdad6] text-[#93000a]">
                Pending
              </span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3.5 text-center">
            <div class="flex items-center justify-center gap-1">
              <?php if ($status === 'Pending'): ?>
                <button class="p-1 text-[#00522d] hover:bg-[#9af6b8]/30 rounded" title="Approve">
                  <span class="material-symbols-outlined text-[17px]">check_circle</span>
                </button>
              <?php endif; ?>
              <button class="p-1 text-[#44474f] hover:text-[#002452] rounded" title="View Detail">
                <span class="material-symbols-outlined text-[17px]">visibility</span>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: New Adjustment -->
<div id="modal-new-adjustment" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button onclick="document.getElementById('modal-new-adjustment').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Create Fee Adjustment</h3>
    <p class="text-xs text-[#44474f] mb-5">Debit or credit adjustment towards student monthly charge.</p>

    <form class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Student Enrollment</label>
        <input type="text" placeholder="Search student name or roll #..." class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Adjustment Type</label>
          <select class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
            <option>Deduction (Credit / Relief)</option>
            <option>Addition (Debit / Extra)</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Amount (PKR)</label>
          <input type="number" placeholder="e.g. 1500" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Target Billing Month</label>
        <input type="month" value="2027-02" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white"/>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Reason / Justification</label>
        <textarea rows="3" placeholder="State reason, approval reference or committee authorization..." class="w-full px-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] resize-none"></textarea>
      </div>

      <div class="flex gap-3 pt-3">
        <button type="button" onclick="document.getElementById('modal-new-adjustment').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Submit Adjustment</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
