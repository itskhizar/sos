<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Bank Payments — dashboard/payments.php
 * Module 10: Bank-based collections, deposit tracking, verification, and receipt creation
 */
$dashTitle   = 'Bank Payments';
$currentDash = 'payments';
include 'includes/header.php';

$filterStatus = $_GET['status'] ?? '';
$filterSearch = $_GET['search'] ?? '';
$preselectedChallanId = intval($_GET['challan_id'] ?? 0);

$payments = $database->getAllPayments($filterStatus);
$unpaidChallans = $database->getAllChallans('Unpaid');

// Compute stats
$totalCollected = 0;
$pendingCount = 0;
$hblCollected = 0;
foreach ($payments as $p) {
  if ($p['verification_status'] === 'Verified') {
    $totalCollected += floatval($p['amount']);
    if (stripos($p['bank_name'], 'HBL') !== false || stripos($p['bank_name'], 'Habib') !== false) {
      $hblCollected += floatval($p['amount']);
    }
  } else {
    $pendingCount++;
  }
}
?>

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Bank Payments &amp; Collections</h2>
    <p class="text-sm text-[#44474f] mt-1">Record bank teller receipts, online transfers, and assign payments to student fee challans.</p>
  </div>
  <div class="flex flex-wrap items-center gap-3">
    <a href="receipts.php"
      class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-[#002452] text-[#002452] rounded-lg font-semibold text-sm hover:bg-[#002452]/5 transition-colors shadow-sm">
      <span class="material-symbols-outlined text-[18px]">receipt</span>
      Official Receipts
    </a>
    <button onclick="document.getElementById('modal-record-payment').classList.remove('hidden')"
      class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
      <span class="material-symbols-outlined text-[18px]">add</span>
      Record Payment
    </button>
  </div>
</div>

<?php if (isset($_GET['recorded'])): ?>
  <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-[18px]">check_circle</span>
      <span>Payment recorded successfully. Receipt has been generated.</span>
    </div>
    <a href="payments.php" class="underline font-bold">Dismiss</a>
  </div>
<?php endif; ?>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#002452]/10 text-[#002452]">
        <span class="material-symbols-outlined text-[22px]">account_balance</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">Verified Total</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Total Received</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">Rs. <?= number_format($totalCollected) ?></p>
    <p class="text-[11px] text-[#747780] mt-1"><?= count($payments) ?> total payment records</p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#885200]/10 text-[#885200]">
        <span class="material-symbols-outlined text-[22px]">hourglass_empty</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#ffdcbb] text-[#673d00]">Awaiting Audit</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Pending Clearance</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1"><?= $pendingCount ?></p>
    <p class="text-[11px] text-[#747780] mt-1">Deposits requiring verification</p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#004424]/10 text-[#004424]">
        <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">HBL Dhodial</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">HBL Collections</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">Rs. <?= number_format($hblCollected) ?></p>
    <p class="text-[11px] text-[#747780] mt-1">Branch Code 0142</p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#ba1a1a]/10 text-[#ba1a1a]">
        <span class="material-symbols-outlined text-[22px]">pending_actions</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#ffdad6] text-[#93000a]">Unpaid</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Unpaid Challans</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1"><?= count($unpaidChallans) ?></p>
    <p class="text-[11px] text-[#747780] mt-1">Ready for payment receipt</p>
  </div>
</div>

<!-- Payments List -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row md:items-center justify-between gap-3">
    <form method="get" action="payments.php" class="relative flex-1 max-w-md">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" name="search" value="<?= htmlspecialchars($filterSearch) ?>" placeholder="Search by Challan #, Student, Bank Ref #..." class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </form>
    <div class="flex items-center gap-2">
      <form method="get" action="payments.php" class="flex gap-2">
        <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-[#e3e2df] rounded-lg text-xs bg-white text-[#44474f]">
          <option value="">All Verification Status</option>
          <option value="Verified" <?= $filterStatus === 'Verified' ? 'selected' : '' ?>>Verified</option>
          <option value="Pending" <?= $filterStatus === 'Pending' ? 'selected' : '' ?>>Pending</option>
        </select>
      </form>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10.5px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-4 py-3 text-left font-semibold">Payment ID</th>
          <th class="px-4 py-3 text-left font-semibold">Student / Challan</th>
          <th class="px-4 py-3 text-left font-semibold">Bank &amp; Branch</th>
          <th class="px-4 py-3 text-left font-semibold">Bank Ref / Slip #</th>
          <th class="px-4 py-3 text-right font-semibold">Amount Paid</th>
          <th class="px-4 py-3 text-left font-semibold">Payment Date</th>
          <th class="px-4 py-3 text-center font-semibold">Verification</th>
          <th class="px-4 py-3 text-center font-semibold">Receipt</th>
          <th class="px-4 py-3 text-center font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php if (empty($payments)): ?>
          <tr>
            <td colspan="9" class="py-12 text-center text-[#747780]">
              <span class="material-symbols-outlined text-4xl mb-1 text-[#c4c6d0]">account_balance_wallet</span>
              <p>No payment records found.</p>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($payments as $p): 
            $sName = htmlspecialchars($p['first_name'] . ' ' . $p['last_name']);
            $chNo = htmlspecialchars($p['challan_no']);
            $rcpNo = $p['receipt_no'] ?? null;
          ?>
          <tr class="hover:bg-[#faf9f6] transition-colors">
            <td class="px-4 py-3.5 font-mono text-xs font-semibold text-[#002452]">PAY-<?= str_pad($p['payment_id'], 4, '0', STR_PAD_LEFT) ?></td>
            <td class="px-4 py-3.5">
              <span class="font-semibold text-[#1b1c1a] block"><?= $sName ?></span>
              <span class="font-mono text-xs text-[#747780]"><?= $chNo ?></span>
            </td>
            <td class="px-4 py-3.5 text-xs text-[#44474f]"><?= htmlspecialchars($p['bank_name'] ?: 'Cash Counter') ?></td>
            <td class="px-4 py-3.5 font-mono text-xs text-[#747780]"><?= htmlspecialchars($p['bank_reference_no'] ?: '—') ?></td>
            <td class="px-4 py-3.5 text-right font-bold text-[#002452]">Rs. <?= number_format($p['amount']) ?></td>
            <td class="px-4 py-3.5 text-xs text-[#44474f]"><?= date('M d, Y', strtotime($p['payment_date'])) ?></td>
            <td class="px-4 py-3.5 text-center">
              <?php if ($p['verification_status'] === 'Verified'): ?>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">
                  <span class="w-1.5 h-1.5 rounded-full bg-[#00522d]"></span> Verified
                </span>
              <?php else: ?>
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#ffdcbb] text-[#673d00]">
                  Pending
                </span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3.5 text-center font-mono text-xs text-[#885200]">
              <a href="receipts.php?payment_id=<?= $p['payment_id'] ?>" class="hover:underline font-semibold">
                <?= htmlspecialchars($rcpNo ?: ('RCP-' . $p['payment_id'])) ?>
              </a>
            </td>
            <td class="px-4 py-3.5 text-center">
              <div class="flex items-center justify-center gap-1">
                <a href="receipts.php?payment_id=<?= $p['payment_id'] ?>" class="p-1.5 text-[#002452] hover:bg-[#002452]/8 rounded-md transition-all" title="View / Print Receipt">
                  <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                </a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Record Payment -->
<div id="modal-record-payment" class="<?= $preselectedChallanId > 0 ? '' : 'hidden' ?> fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative max-h-[90vh] overflow-y-auto">
    <button onclick="document.getElementById('modal-record-payment').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Record Bank Fee Payment</h3>
    <p class="text-xs text-[#44474f] mb-5">Enter bank teller receipt or transfer reference details.</p>

    <form action="process.php" method="post" class="space-y-4">
      <input type="hidden" name="recordpayment" value="1"/>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Select Fee Challan <span class="text-red-500">*</span></label>
        <select name="challan_id" required onchange="autofillAmount(this)" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option value="">-- Choose unpaid challan --</option>
          <?php foreach ($unpaidChallans as $uch): ?>
            <option value="<?= $uch['challan_id'] ?>" data-amount="<?= $uch['total_payable'] ?>" <?= $preselectedChallanId == $uch['challan_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($uch['challan_no'] . ' - ' . $uch['first_name'] . ' ' . $uch['last_name'] . ' (Rs. ' . number_format($uch['total_payable']) . ')') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Payment Method</label>
          <select name="payment_method" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
            <option value="Bank">Bank Deposit</option>
            <option value="Online">Online Bank Transfer</option>
            <option value="Cash">Cash Counter</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Bank &amp; Branch</label>
          <input type="text" name="bank_name" value="HBL Dhodial Branch (0142)" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Deposit Slip / Ref #</label>
          <input type="text" name="bank_reference_no" placeholder="Bank teller slip ID" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Amount Received (PKR) <span class="text-red-500">*</span></label>
          <input type="number" step="0.01" name="amount" id="pay_amount" required placeholder="e.g. 5000" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Payment Deposit Date</label>
        <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white"/>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Remarks</label>
        <input type="text" name="remarks" placeholder="Optional audit memo" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>

      <div class="flex gap-3 pt-3">
        <button type="button" onclick="document.getElementById('modal-record-payment').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Save &amp; Issue Receipt</button>
      </div>
    </form>
  </div>
</div>

<script>
function autofillAmount(selectEl) {
  const opt = selectEl.options[selectEl.selectedIndex];
  if (opt && opt.dataset.amount) {
    document.getElementById('pay_amount').value = opt.dataset.amount;
  }
}
</script>

<?php include 'includes/footer.php'; ?>
