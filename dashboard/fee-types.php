<?php
/**
 * Fee Types — dashboard/fee-types.php
 */
$dashTitle   = 'Fee Types';
$currentDash = 'fee-types';
include 'includes/header.php';

$feeTypes = $database->getFeeTypes();
?>

<?php if(isset($_GET['added']) && $_GET['added'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Fee type created successfully.</div>
<?php elseif(isset($_GET['added']) && $_GET['added'] == 'error'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm font-medium">Could not create fee type. Please check inputs.</div>
<?php endif; ?>

<!-- Page Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <p class="text-sm text-[#44474f]">Define and manage the categories of fees charged to students.</p>
  <button onclick="document.getElementById('modal-add-type').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">add</span>
    Add Fee Type
  </button>
</div>

<!-- Fee Types Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-4">
  <?php if (empty($feeTypes)): ?>
  <div class="col-span-full bg-white rounded-xl border border-[#e3e2df] p-8 text-center text-gray-500">
    No fee categories found. Click "Add Fee Type" to create categories such as Tuition Fee, Admission Fee, Exam Fee, etc.
  </div>
  <?php else: foreach ($feeTypes as $ft):
    $isRec = !empty($ft['is_recurring']);
  ?>
  <div class="bg-white rounded-xl border border-[#e3e2df] p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
    <div class="flex items-start justify-between mb-3">
      <div class="p-2.5 rounded-xl bg-[#002452]/10">
        <span class="material-symbols-outlined text-[22px] text-[#002452]">payments</span>
      </div>
      <div class="flex items-center gap-2">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold <?= $isRec ? 'bg-[#002452]/10 text-[#002452]' : 'bg-[#ffdcbb] text-[#673d00]' ?>">
          <?= $isRec ? 'Recurring' : 'One-Time' ?>
        </span>
        <span class="w-2 h-2 rounded-full bg-[#00522d]" title="Active"></span>
      </div>
    </div>
    <h3 class="font-semibold text-[#1b1c1a] text-sm mb-1"><?= htmlspecialchars($ft['name'] ?? $ft['fee_type_name'] ?? 'Fee') ?></h3>
    <p class="text-xs text-[#44474f] leading-relaxed mb-4"><?= htmlspecialchars($ft['description'] ?: 'No description provided.') ?></p>
    <div class="flex items-center justify-between border-t border-[#e3e2df] pt-3 text-xs text-[#00522d] font-medium">
      <span>Active Category</span>
      <span class="text-gray-400 font-mono text-[11px]">ID #<?= $ft['fee_type_id'] ?></span>
    </div>
  </div>
  <?php endforeach; endif; ?>

  <!-- Add New Card -->
  <button onclick="document.getElementById('modal-add-type').classList.remove('hidden')"
    class="border-2 border-dashed border-[#e3e2df] rounded-xl p-5 flex flex-col items-center justify-center gap-3 hover:border-[#002452]/40 hover:bg-[#002452]/3 transition-all text-[#c4c6d0] hover:text-[#002452] min-h-[160px]">
    <span class="material-symbols-outlined text-[32px]">add_circle</span>
    <span class="text-sm font-medium">Add New Fee Type</span>
  </button>
</div>

<!-- ADD FEE TYPE MODAL -->
<div id="modal-add-type" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button onclick="document.getElementById('modal-add-type').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold mb-1">Add Fee Type</h2>
    <p class="text-sm text-[#44474f] mb-6">Define a new fee category for billing.</p>
    <form action="process.php" method="post" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Fee Type Name *</label>
        <input type="text" name="name" placeholder="e.g. Monthly Tuition Fee" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]" required/>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Charge Frequency</label>
        <select name="is_recurring" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option value="1">Recurring (e.g. Monthly / Exam)</option>
          <option value="0">One-Time (e.g. Admission / Security)</option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Description</label>
        <textarea name="description" rows="3" placeholder="Brief description…" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] resize-none"></textarea>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-add-type').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" name="addfeetype" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Save Fee Type</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
