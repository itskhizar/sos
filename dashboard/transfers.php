<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Transfers — dashboard/transfers.php
 * Module 11: Student Transfers (Between sections, classes, campuses)
 */
$dashTitle   = 'Student Transfers';
$currentDash = 'transfers';
include 'includes/header.php';
?>

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Student Section & Class Transfers</h2>
    <p class="text-sm text-[#44474f] mt-1">Process intra-school section shifts, subject group migrations, and transfer certificate requests.</p>
  </div>
  <button onclick="document.getElementById('modal-transfer-student').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
    Initiate Transfer
  </button>
</div>

<!-- Transfers Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row md:items-center justify-between gap-3">
    <div class="relative flex-1 max-w-md">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" placeholder="Search by student, class or transfer ID..." class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </div>
    <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-xs bg-white text-[#44474f]">
      <option>All Types</option>
      <option>Section Reassignment</option>
      <option>Science / Arts Stream Shift</option>
      <option>Inter-Campus Migration</option>
    </select>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10.5px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-4 py-3 text-left font-semibold">Transfer ID</th>
          <th class="px-4 py-3 text-left font-semibold">Student Name</th>
          <th class="px-4 py-3 text-left font-semibold">Origin (From)</th>
          <th class="px-4 py-3 text-left font-semibold">Destination (To)</th>
          <th class="px-4 py-3 text-left font-semibold">Reason</th>
          <th class="px-4 py-3 text-left font-semibold">Transfer Date</th>
          <th class="px-4 py-3 text-left font-semibold">Authorized By</th>
          <th class="px-4 py-3 text-center font-semibold">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php
        $transfers = [
          ['TR-2027-012', 'Sara Malik', 'Class I - Section B', 'Class I - Section A', 'Parent work commute request', '2027-01-12', 'Academic Coordinator', 'Approved'],
          ['TR-2027-011', 'Hamza Tariq', 'Class IV - Section B', 'Class IV - Section A', 'Sibling schedule alignment', '2027-01-09', 'Principal Office', 'Approved'],
          ['TR-2027-010', 'Fatima Noor', 'Class IX (Arts)', 'Class IX (Bio-Science)', 'Attained qualifying entry score', '2027-01-08', 'Vice Principal', 'Approved'],
          ['TR-2027-009', 'Kashif Ali', 'Class V - Section B', 'Class V - Section A', 'Class size re-balancing', '2027-01-05', 'Academic Coordinator', 'Approved'],
        ];
        foreach ($transfers as [$tId, $sName, $from, $to, $reason, $tDate, $auth, $status]):
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-4 py-3.5 font-mono text-xs font-semibold text-[#002452]"><?= $tId ?></td>
          <td class="px-4 py-3.5 font-semibold text-[#1b1c1a]"><?= $sName ?></td>
          <td class="px-4 py-3.5 text-xs text-[#ba1a1a] font-medium"><?= $from ?></td>
          <td class="px-4 py-3.5 text-xs text-[#00522d] font-bold"><?= $to ?></td>
          <td class="px-4 py-3.5 text-xs text-[#44474f] max-w-xs truncate"><?= $reason ?></td>
          <td class="px-4 py-3.5 text-xs text-[#747780]"><?= $tDate ?></td>
          <td class="px-4 py-3.5 text-xs text-[#44474f]"><?= $auth ?></td>
          <td class="px-4 py-3.5 text-center">
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">
              <span class="w-1.5 h-1.5 rounded-full bg-[#00522d]"></span> <?= $status ?>
            </span>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Initiate Transfer -->
<div id="modal-transfer-student" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative">
    <button onclick="document.getElementById('modal-transfer-student').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Transfer Student</h3>
    <p class="text-xs text-[#44474f] mb-5">Move a student from their current class or section.</p>

    <form class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Student Enrollment</label>
        <input type="text" placeholder="Search student name or roll #..." class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Target Class</label>
          <select class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
            <option>Class I</option><option>Class II</option><option>Class III</option>
            <option>Class IV</option><option>Class V</option><option>Class VI</option>
            <option>Class VII</option><option>Class VIII</option><option>Class IX</option><option>Class X</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Target Section</label>
          <select class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
            <option>Section A</option>
            <option>Section B</option>
            <option>Section C</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Reason / Justification</label>
        <textarea rows="3" placeholder="Explain the rationale for transfer request..." class="w-full px-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] resize-none"></textarea>
      </div>

      <div class="flex gap-3 pt-3">
        <button type="button" onclick="document.getElementById('modal-transfer-student').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Confirm Transfer</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
