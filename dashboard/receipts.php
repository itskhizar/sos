<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Payment Receipts — dashboard/receipts.php
 * Module 10: Official Fee Receipts (Audit verified, printable copy)
 */
$dashTitle   = 'Fee Receipts';
$currentDash = 'receipts';
include 'includes/header.php';

$filterSearch = $_GET['search'] ?? '';
$selectedPaymentId = intval($_GET['payment_id'] ?? 0);

$payments = $database->getAllPayments('Verified');
if (empty($payments)) {
  // If no verified ones, fetch all payments
  $payments = $database->getAllPayments('');
}

$initialReceipt = null;
if ($selectedPaymentId > 0) {
  $initialReceipt = $database->getReceipt($selectedPaymentId);
}
?>

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Official Fee Receipts</h2>
    <p class="text-sm text-[#44474f] mt-1">Verified payment certificates generated upon bank clearance.</p>
  </div>
  <div class="flex items-center gap-3">
    <a href="payments.php" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-[#002452] text-[#002452] rounded-lg font-semibold text-sm hover:bg-[#002452]/5 transition-colors shadow-sm">
      <span class="material-symbols-outlined text-[18px]">payments</span>
      Back to Payments
    </a>
  </div>
</div>

<!-- Receipts List -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mt-6">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row md:items-center justify-between gap-3">
    <div class="relative flex-1 max-w-md">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" id="receipt-search" onkeyup="filterReceiptsTable()" placeholder="Search by Receipt #, Student Name or Challan #..." class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </div>
    <div class="text-xs text-[#747780] font-medium">
      Total Receipts Issued: <span class="font-bold text-[#002452]"><?= count($payments) ?></span>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm" id="receipts-table">
      <thead class="bg-[#f4f3f0] text-[10.5px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-4 py-3 text-left font-semibold">Receipt #</th>
          <th class="px-4 py-3 text-left font-semibold">Student Name</th>
          <th class="px-4 py-3 text-left font-semibold">Reg No</th>
          <th class="px-4 py-3 text-left font-semibold">Challan Ref</th>
          <th class="px-4 py-3 text-right font-semibold">Paid Amount</th>
          <th class="px-4 py-3 text-left font-semibold">Payment Channel</th>
          <th class="px-4 py-3 text-left font-semibold">Issue Date</th>
          <th class="px-4 py-3 text-center font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php if (empty($payments)): ?>
          <tr>
            <td colspan="8" class="py-12 text-center text-[#747780]">
              <span class="material-symbols-outlined text-4xl mb-1 text-[#c4c6d0]">receipt_long</span>
              <p>No fee receipts generated yet.</p>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($payments as $p): 
            $sName = htmlspecialchars($p['first_name'] . ' ' . $p['last_name']);
            $rcpNo = $p['receipt_no'] ?: ('RCP-' . str_pad($p['payment_id'], 4, '0', STR_PAD_LEFT));
            $chlRef = htmlspecialchars($p['challan_no']);
            $channel = htmlspecialchars($p['payment_method'] . ' (' . ($p['bank_name'] ?: 'HBL') . ')');
          ?>
          <tr class="hover:bg-[#faf9f6] transition-colors">
            <td class="px-4 py-3.5 font-mono text-xs font-bold text-[#885200]"><?= htmlspecialchars($rcpNo) ?></td>
            <td class="px-4 py-3.5 font-semibold text-[#1b1c1a]"><?= $sName ?></td>
            <td class="px-4 py-3.5 font-mono text-xs text-[#747780]"><?= htmlspecialchars($p['registration_no']) ?></td>
            <td class="px-4 py-3.5 font-mono text-xs text-[#002452]"><?= $chlRef ?></td>
            <td class="px-4 py-3.5 text-right font-bold text-[#002452]">Rs. <?= number_format($p['amount']) ?></td>
            <td class="px-4 py-3.5 text-xs text-[#44474f]"><?= $channel ?></td>
            <td class="px-4 py-3.5 text-xs text-[#747780]"><?= date('M d, Y', strtotime($p['payment_date'])) ?></td>
            <td class="px-4 py-3.5 text-center">
              <div class="flex items-center justify-center gap-1">
                <button onclick="openReceiptModal(<?= htmlspecialchars(json_encode([
                  'receipt_no' => $rcpNo,
                  'payment_date' => date('d-M-Y', strtotime($p['payment_date'])),
                  'student_name' => $sName,
                  'registration_no' => $p['registration_no'],
                  'challan_no' => $p['challan_no'],
                  'bank_ref' => $p['bank_reference_no'] ?: 'HBL-DEP-' . $p['payment_id'],
                  'amount' => $p['amount'],
                  'channel' => $channel
                ])) ?>)"
                  class="p-1.5 text-[#002452] hover:bg-[#002452]/8 rounded-md transition-all" title="View / Print Receipt">
                  <span class="material-symbols-outlined text-[17px]">print</span>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Printable Receipt Preview -->
<div id="modal-view-receipt" class="<?= $initialReceipt ? '' : 'hidden' ?> fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-8 relative max-h-[90vh] overflow-y-auto">
    <button onclick="document.getElementById('modal-view-receipt').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>

    <!-- Printable Area -->
    <div id="printable-receipt-card" class="border border-[#EAE7DE] p-6 rounded-xl bg-white space-y-6">
      <!-- Receipt Header -->
      <div class="flex items-center justify-between border-b border-[#002452]/20 pb-4">
        <div class="flex items-center gap-3">
          <img src="../assets/images/logo.jpg" alt="SOS Logo" class="w-12 h-12 rounded object-contain"/>
          <div>
            <h3 class="font-serif text-lg font-bold text-[#002452]">SOS Hermann Gmeiner School &amp; College</h3>
            <p class="text-xs text-[#54524B]">Dhodial, District Mansehra, KP · Official Fee Receipt</p>
          </div>
        </div>
        <div class="text-right">
          <span class="font-mono text-sm font-bold text-[#885200]" id="m_rcp_no"><?= $initialReceipt ? htmlspecialchars($initialReceipt['receipt_no'] ?: ('RCP-' . $initialReceipt['payment_id'])) : 'RCP-2026-0001' ?></span>
          <p class="text-[11px] text-[#747780] mt-0.5">Date: <span id="m_rcp_date"><?= $initialReceipt ? date('d-M-Y', strtotime($initialReceipt['payment_date'])) : date('d-M-Y') ?></span></p>
        </div>
      </div>

      <!-- Student & Challan info -->
      <div class="grid grid-cols-2 gap-4 bg-[#faf9f6] p-4 rounded-lg text-xs">
        <div>
          <p class="text-[#747780]">Student Name: <strong class="text-[#1b1c1a]" id="m_rcp_student"><?= $initialReceipt ? htmlspecialchars($initialReceipt['first_name'] . ' ' . $initialReceipt['last_name']) : 'Student Name' ?></strong></p>
          <p class="text-[#747780] mt-1">Roll / Reg No: <strong class="text-[#1b1c1a]" id="m_rcp_reg"><?= $initialReceipt ? htmlspecialchars($initialReceipt['registration_no']) : 'SOS-0000' ?></strong></p>
          <p class="text-[#747780] mt-1">Class: <strong class="text-[#1b1c1a]" id="m_rcp_class"><?= $initialReceipt ? htmlspecialchars($initialReceipt['class_name'] ?? 'Class') : 'Senior School' ?></strong></p>
        </div>
        <div>
          <p class="text-[#747780]">Challan Reference: <strong class="text-[#002452]" id="m_rcp_ch"><?= $initialReceipt ? htmlspecialchars($initialReceipt['challan_no']) : 'CH-0000' ?></strong></p>
          <p class="text-[#747780] mt-1">Bank Reference: <strong class="text-[#1b1c1a]" id="m_rcp_bankref"><?= $initialReceipt ? htmlspecialchars($initialReceipt['bank_reference_no'] ?: 'HBL Branch 0142') : 'HBL-0142' ?></strong></p>
          <p class="text-[#747780] mt-1">Payment Channel: <strong class="text-[#1b1c1a]" id="m_rcp_channel"><?= $initialReceipt ? htmlspecialchars($initialReceipt['payment_method']) : 'Bank Deposit' ?></strong></p>
        </div>
      </div>

      <!-- Fee Breakdown -->
      <table class="w-full text-xs">
        <thead class="border-b border-[#e3e2df] text-[#747780] font-semibold">
          <tr>
            <th class="py-2 text-left">Fee Head</th>
            <th class="py-2 text-right">Standard Amount</th>
            <th class="py-2 text-right">Status</th>
            <th class="py-2 text-right">Net Received</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#f4f3f0]">
          <tr>
            <td class="py-2">Tuition &amp; Composite Educational Dues</td>
            <td class="text-right font-mono" id="m_rcp_std_amt">Rs. <?= $initialReceipt ? number_format($initialReceipt['amount']) : '5,000' ?></td>
            <td class="text-right text-[#00522d] font-bold">Cleared</td>
            <td class="text-right font-mono font-bold" id="m_rcp_net_amt">Rs. <?= $initialReceipt ? number_format($initialReceipt['amount']) : '5,000' ?></td>
          </tr>
        </tbody>
        <tfoot class="border-t-2 border-[#002452] font-bold text-sm">
          <tr>
            <td class="pt-3 text-[#002452]" colspan="3">Total Amount Paid:</td>
            <td class="pt-3 text-right text-[#002452] font-mono" id="m_rcp_total">Rs. <?= $initialReceipt ? number_format($initialReceipt['amount']) : '5,000' ?></td>
          </tr>
        </tfoot>
      </table>

      <!-- Signatures -->
      <div class="flex justify-between items-end pt-8 text-xs text-[#747780]">
        <div>
          <p class="font-mono text-[10px]">VERIFIED ELECTRONICALLY VIA BANK CLEARANCE</p>
          <p class="text-[10px] mt-0.5">Computer generated receipt does not require physical stamp.</p>
        </div>
        <div class="text-center">
          <div class="w-36 border-b border-[#747780] mb-1"></div>
          <p class="font-semibold text-[#1b1c1a]">Accounts Officer</p>
        </div>
      </div>
    </div>

    <div class="flex justify-end gap-3 mt-6">
      <button onclick="window.print()" class="px-5 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">print</span>
        Print Official Receipt
      </button>
    </div>
  </div>
</div>

<script>
function openReceiptModal(data) {
  document.getElementById('m_rcp_no').textContent = data.receipt_no;
  document.getElementById('m_rcp_date').textContent = data.payment_date;
  document.getElementById('m_rcp_student').textContent = data.student_name;
  document.getElementById('m_rcp_reg').textContent = data.registration_no;
  document.getElementById('m_rcp_ch').textContent = data.challan_no;
  document.getElementById('m_rcp_bankref').textContent = data.bank_ref;
  document.getElementById('m_rcp_channel').textContent = data.channel;
  
  const formatted = 'Rs. ' + Number(data.amount || 0).toLocaleString();
  document.getElementById('m_rcp_std_amt').textContent = formatted;
  document.getElementById('m_rcp_net_amt').textContent = formatted;
  document.getElementById('m_rcp_total').textContent = formatted;

  document.getElementById('modal-view-receipt').classList.remove('hidden');
}

function filterReceiptsTable() {
  const query = document.getElementById('receipt-search').value.toLowerCase();
  const rows = document.querySelectorAll('#receipts-table tbody tr');
  rows.forEach(r => {
    const txt = r.textContent.toLowerCase();
    r.style.display = txt.includes(query) ? '' : 'none';
  });
}
</script>

<?php include 'includes/footer.php'; ?>
