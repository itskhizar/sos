<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Monthly Fee Charges & Billing Ledger — dashboard/fees.php
 */
$dashTitle = 'Monthly Fee Charges';
$currentDash = 'fees';
include 'includes/header.php';

$feeCharges = $database->getAllFeeCharges();
$sessions = $database->getAllSessions();
$classes = $database->getAllClasses();
$actSession = $database->getActiveSession();

$grossDemand = 0;
$collected = 0;
$concessions = 0;
$outstanding = 0;
foreach ($feeCharges as $fc) {
  $grossDemand += floatval($fc['original_amount']);
  $collected += floatval($fc['paid_amount']);
  $concessions += floatval($fc['concession_amount']);
  $outstanding += floatval($fc['outstanding_amount']);
}

$totalCharges = count($feeCharges);
$paidCharges = count(array_filter($feeCharges, fn($f) => $f['status'] === 'Paid'));
$pendingCharges = count(array_filter($feeCharges, fn($f) => in_array($f['status'], ['Generated', 'Pending'])));
$overdueCharges = count(array_filter($feeCharges, fn($f) => $f['status'] === 'Overdue'));
?>

<?php if(isset($_GET['generated']) && $_GET['generated'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Monthly fee generation complete! <?= intval($_GET['count'] ?? 0) ?> fee records generated across active scholars.</div>
<?php endif; ?>

<!-- Header & Quick Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Monthly Fee Billing &amp; Charges</h2>
    <p class="text-xs md:text-sm text-[#44474f] mt-1">Generate monthly student demand notes, track realization, and view active concession deductions.</p>
  </div>
  <div class="flex flex-wrap items-center gap-3">
    <a href="concessions.php" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-[#002452] text-[#002452] rounded-lg font-semibold text-xs md:text-sm hover:bg-[#002452]/5 transition-colors shadow-sm whitespace-nowrap">
      <span class="material-symbols-outlined text-[18px]">discount</span>
      Manage Concessions
    </a>
    <button onclick="document.getElementById('modal-generate-monthly').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-xs md:text-sm hover:bg-[#001c40] transition-colors shadow-sm whitespace-nowrap">
      <span class="material-symbols-outlined text-[18px]">receipt_long</span>
      Generate Monthly Billing
    </button>
  </div>
</div>

<!-- Fee Stats Strip -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
  <div class="bg-white p-5 rounded-xl border border-[#e3e2df] shadow-xs">
    <p class="text-[11px] text-[#747780] font-bold uppercase tracking-wider">Gross Demand</p>
    <p class="font-serif text-2xl md:text-3xl font-bold text-[#1b1c1a] mt-1">Rs. <?= number_format($grossDemand) ?></p>
    <p class="text-[11px] text-[#747780] mt-1"><?= $totalCharges ?> billing items</p>
  </div>
  <div class="bg-white p-5 rounded-xl border border-[#e3e2df] shadow-xs">
    <p class="text-[11px] text-[#747780] font-bold uppercase tracking-wider">Realized / Collected</p>
    <p class="font-serif text-2xl md:text-3xl font-bold text-[#00522d] mt-1">Rs. <?= number_format($collected) ?></p>
    <p class="text-[11px] text-[#00522d] mt-1 font-semibold">
      <?= $grossDemand > 0 ? round(($collected / $grossDemand) * 100, 1) : 0 ?>% realization
    </p>
  </div>
  <div class="bg-white p-5 rounded-xl border border-[#e3e2df] shadow-xs">
    <p class="text-[11px] text-[#747780] font-bold uppercase tracking-wider">Concessions Applied</p>
    <p class="font-serif text-2xl md:text-3xl font-bold text-[#885200] mt-1">Rs. <?= number_format($concessions) ?></p>
    <p class="text-[11px] text-[#747780] mt-1">Scholarships &amp; Orphan aid</p>
  </div>
  <div class="bg-white p-5 rounded-xl border border-[#e3e2df] shadow-xs">
    <p class="text-[11px] text-[#747780] font-bold uppercase tracking-wider">Outstanding Dues</p>
    <p class="font-serif text-2xl md:text-3xl font-bold text-[#ba1a1a] mt-1">Rs. <?= number_format($outstanding) ?></p>
    <p class="text-[11px] text-[#ba1a1a] mt-1 font-semibold"><?= $pendingCharges + $overdueCharges ?> pending records</p>
  </div>
</div>

<!-- Modern Interactive Tab Bar -->
<div class="bg-white rounded-xl border border-[#e3e2df] p-1.5 shadow-xs flex flex-wrap items-center gap-1 mt-6">
  <button onclick="filterBilling('all')" id="tab-billing-all" class="billing-tab active-tab px-4 py-2 rounded-lg font-semibold text-xs transition-all flex items-center gap-2 bg-[#002452] text-white shadow-xs">
    All Charges
    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-white font-bold"><?= $totalCharges ?></span>
  </button>
  <button onclick="filterBilling('paid')" id="tab-billing-paid" class="billing-tab px-4 py-2 rounded-lg font-medium text-xs text-[#44474f] hover:bg-gray-100 transition-all flex items-center gap-2">
    Paid
    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-bold"><?= $paidCharges ?></span>
  </button>
  <button onclick="filterBilling('pending')" id="tab-billing-pending" class="billing-tab px-4 py-2 rounded-lg font-medium text-xs text-[#44474f] hover:bg-gray-100 transition-all flex items-center gap-2">
    Pending
    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-800 font-bold"><?= $pendingCharges ?></span>
  </button>
  <button onclick="filterBilling('overdue')" id="tab-billing-overdue" class="billing-tab px-4 py-2 rounded-lg font-medium text-xs text-[#44474f] hover:bg-gray-100 transition-all flex items-center gap-2">
    Overdue
    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-red-100 text-red-800 font-bold"><?= $overdueCharges ?></span>
  </button>
</div>

<!-- Ledger Table Card -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden mt-4">
  <div class="p-4 border-b border-[#e3e2df] flex flex-col sm:flex-row justify-between items-center gap-3 bg-[#faf9f6]">
    <div class="relative flex-1 w-full sm:w-80">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input id="fee-search-input" onkeyup="searchFeeTable()" type="text" placeholder="Search by student name or reg #..." class="pl-9 pr-3 py-2 rounded-lg border border-[#e3e2df] text-xs bg-white w-full outline-none focus:border-[#002452]"/>
    </div>
    <div class="flex items-center gap-2 text-xs">
      <a href="challans.php" class="px-3.5 py-2 rounded-lg border border-[#e3e2df] bg-white text-[#44474f] hover:bg-gray-100 font-medium">
        View Issued Challans →
      </a>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table id="fee-table" class="w-full text-left text-xs">
      <thead class="bg-[#f4f3f0] text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
        <tr>
          <th class="py-3.5 px-6">Charge Ref</th>
          <th class="py-3.5 px-6">Student Name</th>
          <th class="py-3.5 px-6">Class</th>
          <th class="py-3.5 px-6">Fee Component</th>
          <th class="py-3.5 px-6 text-right">Standard Fee</th>
          <th class="py-3.5 px-6 text-right">Concession</th>
          <th class="py-3.5 px-6 text-right">Net Payable</th>
          <th class="py-3.5 px-6 text-center">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
        <?php if (empty($feeCharges)): ?>
        <tr>
          <td colspan="8" class="py-8 text-center text-gray-500">
            No fee charges generated yet. Click "Generate Monthly Billing" to batch process this month's fee demand.
          </td>
        </tr>
        <?php else: foreach ($feeCharges as $fc):
          $st = $fc['status'] ?? 'Generated';
          $badge = match($st) {
            'Paid' => 'bg-emerald-100 text-emerald-800',
            'Overdue' => 'bg-red-100 text-red-800',
            default => 'bg-amber-100 text-amber-800'
          };
          $category = strtolower($st);
          $stName = trim(($fc['first_name'] ?? '') . ' ' . ($fc['last_name'] ?? ''));
        ?>
        <tr class="fee-row hover:bg-gray-50/80 transition-colors" data-category="all <?= $category ?>">
          <td class="py-4 px-6 font-mono text-[11px] text-[#002452] font-semibold">
            FC-<?= str_pad($fc['charge_id'], 5, '0', STR_PAD_LEFT) ?>
            <span class="block text-[10px] text-gray-500"><?= $fc['billing_month'] ? date('M Y', strtotime($fc['billing_month'])) : '—' ?></span>
          </td>
          <td class="py-4 px-6">
            <span class="font-semibold block"><?= htmlspecialchars($stName ?: 'Scholar') ?></span>
            <span class="text-[11px] text-gray-500 font-mono"><?= htmlspecialchars($fc['registration_no'] ?? '') ?></span>
          </td>
          <td class="py-4 px-6 text-[#44474f] font-medium"><?= htmlspecialchars($fc['class_name'] ?? '—') ?></td>
          <td class="py-4 px-6 text-[#54524B]"><?= htmlspecialchars($fc['fee_type_name'] ?? 'Tuition') ?></td>
          <td class="py-4 px-6 text-right font-medium text-gray-600">Rs. <?= number_format($fc['original_amount']) ?></td>
          <td class="py-4 px-6 text-right font-semibold text-[#885200]">
            <?= floatval($fc['concession_amount']) > 0 ? '- Rs. ' . number_format($fc['concession_amount']) : '—' ?>
          </td>
          <td class="py-4 px-6 text-right font-bold text-[#002452]">Rs. <?= number_format($fc['net_amount']) ?></td>
          <td class="py-4 px-6 text-center">
            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold <?= $badge ?>">
              <?= htmlspecialchars($st) ?>
            </span>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Generate Monthly Billing -->
<div id="modal-generate-monthly" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button onclick="document.getElementById('modal-generate-monthly').classList.add('hidden')" class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Generate Monthly Fee Run</h3>
    <p class="text-xs text-[#44474f] mb-5">Batch process monthly fee charges for active student enrollments.</p>

    <form action="process.php" method="post" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Academic Session *</label>
        <select name="session_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white" required>
          <?php foreach ($sessions as $s):
            $sel = ($actSession && $actSession['session_id'] == $s['session_id']) ? 'selected' : '';
          ?>
            <option value="<?= $s['session_id'] ?>" <?= $sel ?>><?= htmlspecialchars($s['session_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Target Class (Optional)</label>
        <select name="class_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option value="0">All Classes (School-wide)</option>
          <?php foreach ($classes as $c): ?>
            <option value="<?= $c['class_id'] ?>"><?= htmlspecialchars($c['class_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Target Billing Month *</label>
        <input type="month" name="billing_month" value="<?= date('Y-m') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white" required/>
      </div>

      <div class="flex gap-3 pt-3">
        <button type="button" onclick="document.getElementById('modal-generate-monthly').classList.add('hidden')" class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" name="createmonthlyfees" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001c40]">Execute Billing Run</button>
      </div>
    </form>
  </div>
</div>

<script>
function filterBilling(status) {
  const tabs = document.querySelectorAll('.billing-tab');
  tabs.forEach(t => {
    t.classList.remove('bg-[#002452]', 'text-white', 'shadow-xs', 'font-semibold');
    t.classList.add('text-[#44474f]', 'font-medium');
  });

  const activeTab = document.getElementById('tab-billing-' + status);
  if (activeTab) {
    activeTab.classList.add('bg-[#002452]', 'text-white', 'shadow-xs', 'font-semibold');
    activeTab.classList.remove('text-[#44474f]', 'font-medium');
  }

  const rows = document.querySelectorAll('.fee-row');
  rows.forEach(r => {
    if (status === 'all' || r.dataset.category.includes(status)) {
      r.style.display = '';
    } else {
      r.style.display = 'none';
    }
  });
}

function searchFeeTable() {
  const query = document.getElementById('fee-search-input').value.toLowerCase();
  const rows = document.querySelectorAll('.fee-row');

  rows.forEach(r => {
    const text = r.textContent.toLowerCase();
    r.style.display = text.includes(query) ? '' : 'none';
  });
}
</script>

<?php include 'includes/footer.php'; ?>
