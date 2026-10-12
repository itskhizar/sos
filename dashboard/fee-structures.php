<?php
/**
 * Fee Structures — dashboard/fee-structures.php
 */
$dashTitle   = 'Fee Structures';
$currentDash = 'fee-structures';
include 'includes/header.php';

$structures = $database->getFeeStructures();
$sessions = $database->getAllSessions();
$classes = $database->getAllClasses();
$feeTypes = $database->getFeeTypes();
$actSession = $database->getActiveSession();
?>

<?php if(isset($_GET['added']) && $_GET['added'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Fee structure created successfully!</div>
<?php elseif(isset($_GET['added']) && $_GET['added'] == 'error'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm font-medium">Could not create fee structure. Please ensure amount and class are selected.</div>
<?php endif; ?>

<!-- Page Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <p class="text-sm text-[#44474f]">Set up fee schedules and itemized amounts per class and academic session.</p>
  <button onclick="document.getElementById('modal-add-structure').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">add</span>
    Create Fee Structure
  </button>
</div>

<!-- Fee Structure Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mt-6">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex items-center justify-between">
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Active Fee Structures</h3>
    <span class="text-xs text-[#747780]">Amounts in PKR</span>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#002452] text-white text-[11px] uppercase tracking-wider">
        <tr>
          <th class="px-6 py-3.5 text-left font-semibold">Structure Name</th>
          <th class="px-4 py-3.5 text-left font-semibold">Class</th>
          <th class="px-4 py-3.5 text-left font-semibold">Session</th>
          <th class="px-4 py-3.5 text-center font-semibold">Components</th>
          <th class="px-4 py-3.5 text-right font-semibold">Total Amount (PKR)</th>
          <th class="px-4 py-3.5 text-center font-semibold">Effective From</th>
          <th class="px-4 py-3.5 text-center font-semibold">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php if (empty($structures)): ?>
        <tr>
          <td colspan="7" class="px-6 py-8 text-center text-gray-500">
            No fee structures defined yet. Click "Create Fee Structure" to configure class fees.
          </td>
        </tr>
        <?php else: foreach ($structures as $st): ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-6 py-4 font-semibold text-[#1b1c1a]">
            <?= htmlspecialchars($st['structure_name'] ?: 'Fee Structure #' . $st['fee_structure_id']) ?>
          </td>
          <td class="px-4 py-4 text-[#002452] font-semibold"><?= htmlspecialchars($st['class_name'] ?? '—') ?></td>
          <td class="px-4 py-4 text-[#44474f]"><?= htmlspecialchars($st['session_name'] ?? '—') ?></td>
          <td class="px-4 py-4 text-center">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[#002452]/10 text-[#002452]">
              <?= intval($st['item_count']) ?> items
            </span>
          </td>
          <td class="px-4 py-4 text-right font-bold text-[#00522d]">
            Rs. <?= number_format($st['total_amount'] ?? 0) ?>
          </td>
          <td class="px-4 py-4 text-center text-xs text-[#747780]">
            <?= $st['effective_from'] ? date('M d, Y', strtotime($st['effective_from'])) : '—' ?>
          </td>
          <td class="px-4 py-4 text-center">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
              Active
            </span>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ADD STRUCTURE MODAL -->
<div id="modal-add-structure" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl p-6 relative max-h-[90vh] overflow-y-auto">
    <button onclick="document.getElementById('modal-add-structure').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold mb-1">Create Class Fee Structure</h2>
    <p class="text-sm text-[#44474f] mb-6">Attach itemized fee amounts to a class and academic session.</p>
    <form action="process.php" method="post" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Structure Name *</label>
        <input type="text" name="structure_name" placeholder="e.g. Class VI Standard Monthly Fee" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]" required/>
      </div>

      <div class="grid grid-cols-2 gap-4">
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
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Class *</label>
          <select name="class_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white" required>
            <option value="">Select Class…</option>
            <?php foreach ($classes as $c): ?>
              <option value="<?= $c['class_id'] ?>"><?= htmlspecialchars($c['class_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Effective From</label>
        <input type="date" name="effective_from" value="<?= date('Y-m-01') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>

      <div class="border-t border-[#e3e2df] pt-4">
        <h4 class="font-semibold text-xs text-[#132644] uppercase tracking-wide mb-3">Itemized Fee Components</h4>
        <div class="space-y-3">
          <?php foreach ($feeTypes as $idx => $ft): ?>
          <div class="flex items-center gap-3 p-2.5 bg-gray-50 rounded-lg border border-[#e3e2df]">
            <input type="hidden" name="fee_type_id[]" value="<?= $ft['fee_type_id'] ?>">
            <div class="flex-1">
              <span class="font-semibold text-xs text-[#1b1c1a] block"><?= htmlspecialchars($ft['fee_type_name'] ?? $ft['name']) ?></span>
              <span class="text-[10.5px] text-gray-500"><?= !empty($ft['is_recurring']) ? 'Recurring' : 'One-Time' ?></span>
            </div>
            <div class="w-36">
              <input type="number" step="0.01" name="amount[]" placeholder="PKR (0)" class="w-full px-2.5 py-1.5 border border-[#e3e2df] rounded text-xs bg-white text-right"/>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="flex gap-3 pt-4 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-add-structure').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" name="addfeestructure" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Save Fee Structure</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
