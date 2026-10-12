<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Fines Management — dashboard/fines.php
 * Module 10: Fine Management (Late fee, discipline, property damage, waivers)
 */
$dashTitle   = 'Fines Management';
$currentDash = 'fines';
include 'includes/header.php';

$fines = $database->getAllFines();
$activeEnrollments = $database->getAllEnrollments(0, 0, 'Active');

$pendingSum = 0;
$collectedSum = 0;
$waivedSum = 0;
foreach ($fines as $f) {
  $amt = floatval($f['amount']);
  if ($f['status'] === 'Pending') $pendingSum += $amt;
  else if ($f['status'] === 'Paid') $collectedSum += $amt;
  else if ($f['status'] === 'Waived') $waivedSum += $amt;
}
?>

<?php if(isset($_GET['added']) && $_GET['added'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Fine recorded successfully.</div>
<?php elseif(isset($_GET['waived']) && $_GET['waived'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Fine waived by administration successfully.</div>
<?php elseif(isset($_GET['added']) && $_GET['added'] == 'error'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm font-medium">Could not impose fine. Please check amount and student.</div>
<?php endif; ?>

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Disciplinary &amp; Late Fines</h2>
    <p class="text-sm text-[#44474f] mt-1">Impose, track, and process waivers for late fee submissions, damaged property, or library overdues.</p>
  </div>
  <button onclick="document.getElementById('modal-issue-fine').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">gavel</span>
    Issue Fine
  </button>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#ba1a1a]/10 text-[#ba1a1a]">
        <span class="material-symbols-outlined text-[22px]">gavel</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#ffdad6] text-[#93000a]">Uncollected</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Total Pending Fines</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">Rs. <?= number_format($pendingSum) ?></p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#004424]/10 text-[#004424]">
        <span class="material-symbols-outlined text-[22px]">check_circle</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">Received</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Fines Collected</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">Rs. <?= number_format($collectedSum) ?></p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#885200]/10 text-[#885200]">
        <span class="material-symbols-outlined text-[22px]">verified</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#ffdcbb] text-[#673d00]">Relief</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Waived by Administration</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1">Rs. <?= number_format($waivedSum) ?></p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#002452]/10 text-[#002452]">
        <span class="material-symbols-outlined text-[22px]">format_list_bulleted</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#002452]/10 text-[#002452]">Total Logs</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Fine Incidents</p>
    <p class="font-serif text-2xl font-bold text-[#1b1c1a] mt-1"><?= count($fines) ?></p>
  </div>
</div>

<!-- Fines Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mt-6">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex items-center justify-between">
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Fines Ledger</h3>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10.5px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-6 py-3 text-left font-semibold">Fine Ref #</th>
          <th class="px-4 py-3 text-left font-semibold">Student Name</th>
          <th class="px-4 py-3 text-left font-semibold">Class</th>
          <th class="px-4 py-3 text-right font-semibold">Amount</th>
          <th class="px-4 py-3 text-left font-semibold">Date Imposed</th>
          <th class="px-4 py-3 text-left font-semibold">Reason</th>
          <th class="px-4 py-3 text-center font-semibold">Status</th>
          <th class="px-4 py-3 text-right font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php if (empty($fines)): ?>
        <tr>
          <td colspan="8" class="px-6 py-8 text-center text-gray-500">
            No fines recorded. Click "Issue Fine" if needed.
          </td>
        </tr>
        <?php else: foreach ($fines as $fn):
          $st = $fn['status'] ?? 'Pending';
          $stBadge = match($st) {
            'Paid' => 'bg-emerald-100 text-emerald-800',
            'Waived' => 'bg-blue-100 text-blue-800',
            default => 'bg-amber-100 text-amber-800'
          };
          $stName = trim(($fn['first_name'] ?? '') . ' ' . ($fn['last_name'] ?? ''));
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-6 py-4 font-mono text-xs font-semibold text-[#002452]">FN-<?= str_pad($fn['fine_id'], 5, '0', STR_PAD_LEFT) ?></td>
          <td class="px-4 py-4">
            <span class="font-semibold block"><?= htmlspecialchars($stName ?: 'Scholar') ?></span>
            <span class="text-[11px] text-gray-500 font-mono"><?= htmlspecialchars($fn['registration_no'] ?? '') ?></span>
          </td>
          <td class="px-4 py-4 text-[#44474f] font-medium"><?= htmlspecialchars($fn['class_name'] ?? '—') ?></td>
          <td class="px-4 py-4 text-right font-bold text-[#ba1a1a]">Rs. <?= number_format($fn['amount']) ?></td>
          <td class="px-4 py-4 text-[#747780] text-xs"><?= $fn['fine_date'] ? date('M d, Y', strtotime($fn['fine_date'])) : '—' ?></td>
          <td class="px-4 py-4 text-gray-600 max-w-xs truncate"><?= htmlspecialchars($fn['reason'] ?: 'Late fee / surcharge') ?></td>
          <td class="px-4 py-4 text-center">
            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold <?= $stBadge ?>">
              <?= htmlspecialchars($st) ?>
            </span>
          </td>
          <td class="px-4 py-4 text-right">
            <?php if ($st === 'Pending'): ?>
            <form action="process.php" method="post" class="inline" onsubmit="return confirm('Are you sure you want to waive this fine?');">
              <input type="hidden" name="fine_id" value="<?= $fn['fine_id'] ?>">
              <button type="submit" name="waivefine" class="px-2.5 py-1 text-xs font-semibold text-[#885200] border border-[#885200]/30 hover:bg-[#885200]/10 rounded">
                Waive Fine
              </button>
            </form>
            <?php else: ?>
            <span class="text-xs text-gray-400">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Issue Fine -->
<div id="modal-issue-fine" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button onclick="document.getElementById('modal-issue-fine').classList.add('hidden')" class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Issue Disciplinary / Late Fine</h3>
    <p class="text-xs text-[#44474f] mb-5">Record a fine charge against an enrolled student.</p>

    <form action="process.php" method="post" class="space-y-4 text-xs">
      <div>
        <label class="block font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Select Student *</label>
        <select name="enrollment_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-xs focus:outline-none focus:border-[#002452] bg-white" required>
          <option value="">Select Enrolled Scholar…</option>
          <?php foreach ($activeEnrollments as $enr): ?>
            <option value="<?= $enr['enrollment_id'] ?>">
              [<?= htmlspecialchars($enr['registration_no'] ?? '') ?>] <?= htmlspecialchars(($enr['first_name'] ?? '') . ' ' . ($enr['last_name'] ?? '')) ?> (<?= htmlspecialchars($enr['class_name'] ?? '') ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Fine Amount (PKR) *</label>
        <input type="number" step="0.01" name="amount" placeholder="e.g. 500" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-xs focus:outline-none focus:border-[#002452]" required/>
      </div>

      <div>
        <label class="block font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Reason / Details *</label>
        <textarea name="reason" rows="3" placeholder="Specify fine cause (e.g. Late fee submission, library overdue, laboratory damage)..." class="w-full px-3 py-2 border border-[#e3e2df] rounded-lg text-xs focus:outline-none focus:border-[#002452] resize-none" required></textarea>
      </div>

      <div class="flex gap-3 pt-3">
        <button type="button" onclick="document.getElementById('modal-issue-fine').classList.add('hidden')" class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" name="addfine" class="flex-1 px-4 py-2.5 bg-[#ba1a1a] text-white rounded-lg text-sm font-semibold hover:bg-[#93000a]">Impose Fine</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
