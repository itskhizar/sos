<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Leave Records — dashboard/leave-records.php
 * Module 11: Student Leave Records (Medical, casual, approvals)
 */
$dashTitle   = 'Leave Records';
$currentDash = 'leave-records';
include 'includes/header.php';
?>

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Student Leave Applications</h2>
    <p class="text-sm text-[#44474f] mt-1">Review student medical and casual absence requests submitted by parents and guardians.</p>
  </div>
  <button onclick="document.getElementById('modal-submit-leave').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">event_available</span>
    Submit Leave
  </button>
</div>

<!-- Leave Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row md:items-center justify-between gap-3">
    <div class="relative flex-1 max-w-md">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" placeholder="Search by student, class or parent name..." class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </div>
    <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-xs bg-white text-[#44474f]">
      <option>All Status</option>
      <option>Pending</option>
      <option>Approved</option>
      <option>Rejected</option>
    </select>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10.5px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-4 py-3 text-left font-semibold">Student Name</th>
          <th class="px-4 py-3 text-left font-semibold">Class / Section</th>
          <th class="px-4 py-3 text-left font-semibold">Leave Period</th>
          <th class="px-4 py-3 text-right font-semibold">Days</th>
          <th class="px-4 py-3 text-left font-semibold">Type & Reason</th>
          <th class="px-4 py-3 text-center font-semibold">Status</th>
          <th class="px-4 py-3 text-center font-semibold">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php
        $leaves = [
          ['Bilal Ahmad', 'Class VI - A', '15 Jan 2027 to 17 Jan 2027', 3, 'Medical · Seasonal viral fever with clinic cert', 'Pending'],
          ['Sara Malik', 'Class I - B', '12 Jan 2027 to 13 Jan 2027', 2, 'Casual · Family wedding attendance out of station', 'Approved'],
          ['Fatima Noor', 'Class IX - A', '08 Jan 2027 to 08 Jan 2027', 1, 'Medical · Dental appointment in Abbottabad', 'Approved'],
          ['Hamza Tariq', 'Class IV - A', '05 Jan 2027 to 09 Jan 2027', 5, 'Casual · Unexplained absence without notice', 'Rejected'],
        ];
        foreach ($leaves as [$sName, $sClass, $period, $days, $reason, $status]):
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-4 py-3.5 font-semibold text-[#1b1c1a]"><?= $sName ?></td>
          <td class="px-4 py-3.5 text-xs text-[#44474f]"><?= $sClass ?></td>
          <td class="px-4 py-3.5 text-xs font-mono text-[#002452]"><?= $period ?></td>
          <td class="px-4 py-3.5 text-right font-bold text-[#1b1c1a]"><?= $days ?></td>
          <td class="px-4 py-3.5 text-xs text-[#44474f] max-w-sm"><?= $reason ?></td>
          <td class="px-4 py-3.5 text-center">
            <?php if ($status === 'Approved'): ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">Approved</span>
            <?php elseif ($status === 'Rejected'): ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#ffdad6] text-[#93000a]">Rejected</span>
            <?php else: ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#ffdcbb] text-[#673d00]">Pending</span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3.5 text-center">
            <div class="flex items-center justify-center gap-1">
              <?php if ($status === 'Pending'): ?>
                <button class="p-1 text-[#00522d] hover:bg-[#9af6b8]/30 rounded" title="Approve">
                  <span class="material-symbols-outlined text-[17px]">check_circle</span>
                </button>
                <button class="p-1 text-[#ba1a1a] hover:bg-[#ffdad6]/40 rounded" title="Reject">
                  <span class="material-symbols-outlined text-[17px]">cancel</span>
                </button>
              <?php endif; ?>
              <button class="p-1 text-[#44474f] hover:text-[#002452] rounded" title="View details">
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

<!-- Modal: Submit Leave -->
<div id="modal-submit-leave" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button onclick="document.getElementById('modal-submit-leave').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Submit Student Leave</h3>
    <p class="text-xs text-[#44474f] mb-5">Record parental notice for anticipated student absence.</p>

    <form class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Student</label>
        <input type="text" placeholder="Search student name or roll #..." class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Leave From</label>
          <input type="date" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Leave To</label>
          <input type="date" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Leave Type</label>
        <select class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option>Medical Leave (Sick / Treatment)</option>
          <option>Casual Leave (Family Event)</option>
          <option>Emergency Leave</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Reason & Doctor's Advice</label>
        <textarea rows="3" placeholder="Provide note regarding illness or purpose of absence..." class="w-full px-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] resize-none"></textarea>
      </div>

      <div class="flex gap-3 pt-3">
        <button type="button" onclick="document.getElementById('modal-submit-leave').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Submit Request</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
