<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Fee Challans Management — dashboard/challans.php
 * Module 09: Challans & 3-Part Bank Slip Printing
 */
$dashTitle = 'Fee Challans';
$currentDash = 'challans';
include 'includes/header.php';

$filterStatus = $_GET['status'] ?? '';
$filterSearch = $_GET['search'] ?? '';

$challans = $database->getAllChallans($filterStatus, $filterSearch);
$activeStudents = $database->getAllStudents('Active');
$activeSession = $database->getActiveSession();
?>

<!-- Header & Quick Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
  <div>
    <div class="flex items-center gap-2 text-xs text-[#54524B] mb-1">
      <span>Fees</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#002452] font-semibold">Challans</span>
    </div>
    <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Fee Challans</h1>
    <p class="text-xs text-[#54524B] mt-0.5">3-part banking slips, ledger receivables, and verified payment links.</p>
  </div>
  <div class="flex gap-3">
    <a href="fees.php" class="flex items-center gap-2 px-4 py-2 rounded bg-white text-[#002452] border border-[#002452] font-semibold text-xs md:text-sm hover:bg-[#002452]/5 transition-colors shadow-xs">
      <span class="material-symbols-outlined text-[18px]">receipt_long</span>
      Monthly Billing Run
    </a>
    <button onclick="document.getElementById('modal-generate-challan').classList.remove('hidden')" class="flex items-center gap-2 px-4 py-2 rounded bg-[#002452] text-white font-semibold text-xs md:text-sm hover:bg-[#002452]/90 transition-colors shadow-sm">
      <span class="material-symbols-outlined text-[18px]">add</span>
      Generate Student Challan
    </button>
  </div>
</div>

<?php if (isset($_GET['generated'])): ?>
  <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-[18px]">check_circle</span>
      <span>Fee challan generated successfully.</span>
    </div>
    <a href="challans.php" class="underline font-bold">Dismiss</a>
  </div>
<?php endif; ?>

<!-- Filter Bar -->
<form method="get" action="challans.php" class="bg-white rounded-xl border border-[#e3e2df] p-4 shadow-xs grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
  <div>
    <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Search Student / Challan #</label>
    <input type="text" name="search" value="<?= htmlspecialchars($filterSearch) ?>" placeholder="Search by name, CH #..." class="w-full px-3 py-2 rounded-lg border border-[#c4c6d0] text-xs bg-white outline-none focus:border-[#002452]"/>
  </div>
  <div>
    <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Status</label>
    <select name="status" class="w-full px-3 py-2 rounded-lg border border-[#c4c6d0] text-xs bg-white outline-none">
      <option value="">All Statuses</option>
      <option value="Unpaid" <?= $filterStatus === 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
      <option value="Paid" <?= $filterStatus === 'Paid' ? 'selected' : '' ?>>Paid</option>
      <option value="PartiallyPaid" <?= $filterStatus === 'PartiallyPaid' ? 'selected' : '' ?>>Partially Paid</option>
      <option value="Overdue" <?= $filterStatus === 'Overdue' ? 'selected' : '' ?>>Overdue</option>
    </select>
  </div>
  <div>
    <button type="submit" class="w-full bg-[#885200] hover:bg-[#704200] text-white py-2 rounded-lg font-semibold text-xs transition-colors">
      Apply Filter
    </button>
  </div>
  <div>
    <a href="challans.php" class="block text-center w-full bg-gray-100 hover:bg-gray-200 text-[#44474f] py-2 rounded-lg font-semibold text-xs transition-colors">
      Clear Filter
    </a>
  </div>
</form>

<!-- Challans Table Card -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-gray-50 text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
        <tr>
          <th class="py-3.5 px-6">Challan #</th>
          <th class="py-3.5 px-6">Student</th>
          <th class="py-3.5 px-6">Class / Sec</th>
          <th class="py-3.5 px-6">Issue Date</th>
          <th class="py-3.5 px-6">Due Date</th>
          <th class="py-3.5 px-6">Payable</th>
          <th class="py-3.5 px-6">Status</th>
          <th class="py-3.5 px-6 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
        <?php if (empty($challans)): ?>
          <tr>
            <td colspan="8" class="py-12 text-center text-[#747780]">
              <span class="material-symbols-outlined text-4xl mb-1 text-[#c4c6d0]">receipt_long</span>
              <p>No fee challans found matching current filters.</p>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($challans as $ch): 
            $stBadge = 'bg-amber-100 text-amber-800';
            if ($ch['status'] === 'Paid') $stBadge = 'bg-emerald-100 text-emerald-800';
            else if ($ch['status'] === 'Overdue') $stBadge = 'bg-red-100 text-red-800';
            else if ($ch['status'] === 'PartiallyPaid') $stBadge = 'bg-blue-100 text-blue-800';

            $studentName = htmlspecialchars($ch['first_name'] . ' ' . $ch['last_name']);
            $className = htmlspecialchars(($ch['class_name'] ?? 'Class') . ($ch['section_name'] ? ' - ' . $ch['section_name'] : ''));
          ?>
          <tr class="hover:bg-gray-50/80 transition-colors">
            <td class="py-4 px-6 font-bold text-[#002452] font-mono"><?= htmlspecialchars($ch['challan_no']) ?></td>
            <td class="py-4 px-6 font-medium">
              <?= $studentName ?>
              <span class="block text-[11px] text-[#747780] font-mono"><?= htmlspecialchars($ch['registration_no']) ?></span>
            </td>
            <td class="py-4 px-6"><?= $className ?></td>
            <td class="py-4 px-6 text-[#747780]"><?= date('M d, Y', strtotime($ch['issue_date'])) ?></td>
            <td class="py-4 px-6 text-[#747780]"><?= date('M d, Y', strtotime($ch['due_date'])) ?></td>
            <td class="py-4 px-6 font-bold text-[#1b1c1a]">Rs. <?= number_format(floatval($ch['total_payable'] ?? $ch['requested_amount'] ?? 0)) ?></td>
            <td class="py-4 px-6">
              <span class="px-2.5 py-1 rounded-full text-[11px] font-bold <?= $stBadge ?>"><?= htmlspecialchars($ch['status']) ?></span>
            </td>
            <td class="py-4 px-6 text-right">
              <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="previewChallan(<?= htmlspecialchars(json_encode($ch)) ?>)" class="p-1.5 text-[#002452] hover:bg-gray-100 rounded transition-colors" title="Print 3-Part Bank Challan">
                  <span class="material-symbols-outlined text-[18px]">print</span>
                </button>
                <?php if ($ch['status'] !== 'Paid'): ?>
                  <a href="payments.php?challan_id=<?= $ch['challan_id'] ?>" class="p-1.5 text-[#00522d] hover:bg-gray-100 rounded transition-colors" title="Record Payment">
                    <span class="material-symbols-outlined text-[18px]">payments</span>
                  </a>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="p-4 border-t border-[#e3e2df] flex justify-between items-center text-xs text-[#747780] bg-gray-50/50">
    <span>Total Challans: <?= count($challans) ?></span>
    <span class="font-medium text-[#002452]">Habib Bank Limited · Dhodial Branch (0142)</span>
  </div>
</div>

<!-- Modal: Generate Student Challan -->
<div id="modal-generate-challan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative max-h-[90vh] overflow-y-auto">
    <button onclick="document.getElementById('modal-generate-challan').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Issue Student Fee Challan</h3>
    <p class="text-xs text-[#44474f] mb-5">Generates a payable 3-part bank slip aggregating active fee charges, fines, and concessions.</p>

    <form action="process.php" method="post" class="space-y-4">
      <input type="hidden" name="generatechallan" value="1"/>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Select Registered Student <span class="text-red-500">*</span></label>
        <select name="student_id" required class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option value="">-- Choose active student --</option>
          <?php foreach ($activeStudents as $st): ?>
            <option value="<?= $st['student_id'] ?>">
              <?= htmlspecialchars($st['registration_no'] . ' - ' . $st['first_name'] . ' ' . $st['last_name'] . ' (' . ($st['current_class_name'] ?? 'Class') . ')') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Challan Due Date <span class="text-red-500">*</span></label>
        <input type="date" name="due_date" value="<?= date('Y-m-d', strtotime('+15 days')) ?>" required class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white"/>
      </div>

      <div class="p-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-xs">
        <strong>Bank Details:</strong> Deposit slips will be drawn against Habib Bank Limited (HBL) Dhodial Branch account <code>0142-79012345-03</code>.
      </div>

      <div class="flex gap-3 pt-3">
        <button type="button" onclick="document.getElementById('modal-generate-challan').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Generate Challan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Printable 3-Part Bank Challan Preview -->
<div id="modal-print-challan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl p-6 relative max-h-[95vh] overflow-y-auto">
    <div class="flex justify-between items-center pb-4 border-b border-[#e3e2df] mb-6 print:hidden">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-2xl text-[#002452]">print</span>
        <h3 class="font-serif text-xl font-bold text-[#002452]">Printable 3-Part Bank Challan</h3>
      </div>
      <div class="flex items-center gap-3">
        <button onclick="window.print()" class="px-4 py-2 bg-[#002452] text-white font-semibold text-xs rounded-lg hover:bg-[#001a3d] flex items-center gap-1.5 shadow-sm">
          <span class="material-symbols-outlined text-[16px]">print</span> Print Challan
        </button>
        <button onclick="document.getElementById('modal-print-challan').classList.add('hidden')" class="p-2 text-[#747780] hover:text-[#1b1c1a]">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
    </div>

    <!-- Printable Content Container (3 Copies Side by Side) -->
    <div id="challan-printable-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 border border-dashed border-gray-300 p-4 bg-white text-[11px] leading-tight">
      
      <!-- Copy Generator Function via JS -->
      <?php foreach (['BANK COPY', 'SCHOOL COPY', 'STUDENT COPY'] as $copyTitle): ?>
        <div class="border border-gray-400 p-3 flex flex-col justify-between bg-white rounded">
          <div>
            <!-- Header -->
            <div class="text-center pb-2 border-b border-gray-300 mb-2">
              <p class="font-bold uppercase tracking-wider text-[10px] text-gray-500"><?= $copyTitle ?></p>
              <h4 class="font-serif font-bold text-xs text-[#002452] mt-0.5">SOS Hermann Gmeiner School &amp; College</h4>
              <p class="text-[9.5px] text-gray-600">Dhodial, Mansehra, KP</p>
              <div class="bg-gray-100 py-1 px-2 rounded mt-1.5 font-mono text-[9.5px]">
                <strong>HBL Dhodial Branch (0142)</strong><br>
                A/C: 0142-79012345-03
              </div>
            </div>

            <!-- Details -->
            <div class="space-y-1 py-1 text-[10.5px]">
              <div class="flex justify-between"><span class="text-gray-600">Challan No:</span> <strong class="font-mono text-[#002452] preview-ch-no">CH-2026-0000</strong></div>
              <div class="flex justify-between"><span class="text-gray-600">Issue Date:</span> <span class="preview-issue-date">2026-05-01</span></div>
              <div class="flex justify-between"><span class="text-gray-600">Due Date:</span> <strong class="text-red-700 preview-due-date">2026-05-15</strong></div>
              <div class="border-t border-gray-200 my-1"></div>
              <div class="flex justify-between"><span class="text-gray-600">Reg No:</span> <span class="font-mono preview-reg-no">SOS-2026-0000</span></div>
              <div class="flex justify-between"><span class="text-gray-600">Student:</span> <strong class="preview-student-name">Student Name</strong></div>
              <div class="flex justify-between"><span class="text-gray-600">Class:</span> <span class="preview-class-name">Class Level</span></div>
            </div>

            <!-- Breakdown Table -->
            <table class="w-full mt-2 border border-gray-300 text-[10px]">
              <thead class="bg-gray-100 font-bold border-b border-gray-300">
                <tr><th class="p-1 text-left">Particulars</th><th class="p-1 text-right">Amount (PKR)</th></tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr><td class="p-1 text-gray-700">Tuition &amp; Educational Charges</td><td class="p-1 text-right font-mono preview-fee-base">5,000</td></tr>
                <tr><td class="p-1 text-gray-700">Fines / Surcharges</td><td class="p-1 text-right font-mono preview-fee-fine">0</td></tr>
                <tr><td class="p-1 text-emerald-700">Scholarship / Concession</td><td class="p-1 text-right font-mono text-emerald-700 preview-fee-concession">0</td></tr>
              </tbody>
              <tfoot class="border-t-2 border-gray-400 bg-gray-50 font-bold">
                <tr><td class="p-1.5 text-left text-[#002452]">Net Payable:</td><td class="p-1.5 text-right font-mono text-xs text-[#002452] preview-fee-total">Rs. 5,000</td></tr>
              </tfoot>
            </table>
          </div>

          <!-- Signatures -->
          <div class="mt-6 pt-4 border-t border-gray-300 grid grid-cols-2 text-center text-[9px] text-gray-600">
            <div>
              <div class="border-b border-gray-400 w-3/4 mx-auto mb-1"></div>
              <span>Depositor Sign</span>
            </div>
            <div>
              <div class="border-b border-gray-400 w-3/4 mx-auto mb-1"></div>
              <span>Bank Officer / Cashier</span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</div>

<script>
function previewChallan(ch) {
  const modal = document.getElementById('modal-print-challan');
  const studentName = (ch.first_name || '') + ' ' + (ch.last_name || '');
  const className = (ch.class_name || 'Class') + (ch.section_name ? ' - ' + ch.section_name : '');
  
  modal.querySelectorAll('.preview-ch-no').forEach(el => el.textContent = ch.challan_no || 'N/A');
  modal.querySelectorAll('.preview-issue-date').forEach(el => el.textContent = ch.issue_date || '');
  modal.querySelectorAll('.preview-due-date').forEach(el => el.textContent = ch.due_date || '');
  modal.querySelectorAll('.preview-reg-no').forEach(el => el.textContent = ch.registration_no || '');
  modal.querySelectorAll('.preview-student-name').forEach(el => el.textContent = studentName);
  modal.querySelectorAll('.preview-class-name').forEach(el => el.textContent = className);
  
  modal.querySelectorAll('.preview-fee-base').forEach(el => el.textContent = Number(ch.total_payable || 0).toLocaleString());
  modal.querySelectorAll('.preview-fee-fine').forEach(el => el.textContent = '0');
  modal.querySelectorAll('.preview-fee-concession').forEach(el => el.textContent = '0');
  modal.querySelectorAll('.preview-fee-total').forEach(el => el.textContent = 'Rs. ' + Number(ch.total_payable || 0).toLocaleString());

  modal.classList.remove('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
