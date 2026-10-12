<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Concessions Management — dashboard/concessions.php
 * Module 7: Concessions (Percent / Fixed, Types, Approvals)
 */
$dashTitle   = 'Fee Concessions';
$currentDash = 'concessions';
include 'includes/header.php';

$concessions = $database->getAllConcessions();
$concessionTypes = $database->getConcessionTypes();
$activeEnrollments = $database->getAllEnrollments(0, 0, 'Active');

$totalConcessions = count($concessions);
$sosWardsCount = count(array_filter($concessions, fn($c) => floatval($c['value']) == 100 || stripos($c['concession_type_name'] ?? '', 'SOS') !== false));
$totalValue = array_sum(array_column($concessions, 'value'));
?>

<?php if(isset($_GET['added']) && $_GET['added'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Concession policy granted to student enrollment successfully!</div>
<?php elseif(isset($_GET['added']) && $_GET['added'] == 'error'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm font-medium">Could not grant concession. Please verify enrollment and discount value.</div>
<?php endif; ?>

<!-- Header & Quick Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Fee Concessions &amp; Scholarships</h2>
    <p class="text-sm text-[#44474f] mt-1">Manage student fee discounts, SOS ward waivers, sibling concessions, and merit awards.</p>
  </div>
  <div class="flex flex-wrap items-center gap-3">
    <button onclick="document.getElementById('modal-grant-concession').classList.remove('hidden')"
      class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
      <span class="material-symbols-outlined text-[18px]">add</span>
      Grant Concession
    </button>
  </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#002452]/10 text-[#002452]">
        <span class="material-symbols-outlined text-[22px]">discount</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">Active</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Total Concessions</p>
    <p class="font-serif text-3xl font-bold text-[#1b1c1a] mt-1"><?= $totalConcessions ?></p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#885200]/10 text-[#885200]">
        <span class="material-symbols-outlined text-[22px]">family_restroom</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#ffdcbb] text-[#673d00]">SOS Wards</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">100% Free Wards</p>
    <p class="font-serif text-3xl font-bold text-[#1b1c1a] mt-1"><?= $sosWardsCount ?></p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-[#004424]/10 text-[#004424]">
        <span class="material-symbols-outlined text-[22px]">savings</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">Active Policies</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Concession Schemes</p>
    <p class="font-serif text-3xl font-bold text-[#1b1c1a] mt-1"><?= count($concessionTypes) ?></p>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <div class="p-2.5 rounded-lg bg-blue-50 text-blue-700">
        <span class="material-symbols-outlined text-[22px]">verified</span>
      </div>
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">Enrolled</span>
    </div>
    <p class="text-xs text-[#44474f] font-semibold uppercase tracking-wider">Active Scholars</p>
    <p class="font-serif text-3xl font-bold text-[#1b1c1a] mt-1"><?= count($activeEnrollments) ?></p>
  </div>
</div>

<!-- Tabs for Types vs Active Grants -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mt-6">
  <div class="flex border-b border-[#e3e2df] px-6">
    <button id="tab-btn-grants" onclick="showTab('grants')" class="px-5 py-3.5 border-b-2 border-[#002452] font-semibold text-sm text-[#002452] flex items-center gap-2">
      <span class="material-symbols-outlined text-[18px]">verified_user</span>
      Assigned Student Concessions (<?= $totalConcessions ?>)
    </button>
    <button id="tab-btn-types" onclick="showTab('types')" class="px-5 py-3.5 border-b-2 border-transparent font-medium text-sm text-[#44474f] hover:text-[#002452] flex items-center gap-2">
      <span class="material-symbols-outlined text-[18px]">category</span>
      Concession Schemes (<?= count($concessionTypes) ?>)
    </button>
  </div>

  <!-- Tab 1: Active Student Grants -->
  <div id="tab-grants" class="p-6">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead class="bg-[#f4f3f0] text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
          <tr>
            <th class="py-3 px-4">Student</th>
            <th class="py-3 px-4">Reg No</th>
            <th class="py-3 px-4">Class</th>
            <th class="py-3 px-4">Scheme / Type</th>
            <th class="py-3 px-4 text-right">Discount Value</th>
            <th class="py-3 px-4">Effective Dates</th>
            <th class="py-3 px-4">Reason / Notes</th>
            <th class="py-3 px-4 text-center">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#e3e2df]">
          <?php if (empty($concessions)): ?>
          <tr>
            <td colspan="8" class="py-8 text-center text-gray-500">
              No concessions granted yet. Click "Grant Concession" to assign a discount scheme to an enrolled student.
            </td>
          </tr>
          <?php else: foreach ($concessions as $c):
            $stName = trim(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? ''));
          ?>
          <tr class="hover:bg-gray-50/80 transition-colors">
            <td class="py-3.5 px-4 font-semibold text-[#1b1c1a]"><?= htmlspecialchars($stName ?: 'Scholar') ?></td>
            <td class="py-3.5 px-4 font-mono text-[#002452] font-semibold"><?= htmlspecialchars($c['registration_no'] ?? '') ?></td>
            <td class="py-3.5 px-4 text-[#44474f] font-medium"><?= htmlspecialchars($c['class_name'] ?? '—') ?></td>
            <td class="py-3.5 px-4 font-semibold text-[#885200]"><?= htmlspecialchars($c['concession_type_name'] ?? 'Concession') ?></td>
            <td class="py-3.5 px-4 text-right font-bold text-[#00522d]">
              <?= floatval($c['value']) ?>%
            </td>
            <td class="py-3.5 px-4 text-[#747780]">
              <?= $c['effective_from'] ? date('M Y', strtotime($c['effective_from'])) : '—' ?>
              <?= $c['effective_to'] ? ' to ' . date('M Y', strtotime($c['effective_to'])) : ' (Ongoing)' ?>
            </td>
            <td class="py-3.5 px-4 text-gray-600 max-w-xs truncate"><?= htmlspecialchars($c['reason'] ?: '—') ?></td>
            <td class="py-3.5 px-4 text-center">
              <span class="px-2 py-0.5 rounded-full text-[10.5px] font-semibold bg-emerald-100 text-emerald-800">
                Active
              </span>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tab 2: Concession Types -->
  <div id="tab-types" class="p-6 hidden">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <?php foreach ($concessionTypes as $ct): ?>
      <div class="p-4 rounded-xl border border-[#e3e2df] bg-gray-50/50">
        <h4 class="font-serif font-bold text-[#002452] text-sm mb-1"><?= htmlspecialchars($ct['type_name'] ?? $ct['concession_type_name'] ?? 'Concession') ?></h4>
        <p class="text-xs text-gray-500 mb-3"><?= htmlspecialchars($ct['description'] ?? 'Standard institutional concession scheme.') ?></p>
        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#002452]/10 text-[#002452]">
          <?= htmlspecialchars($ct['discount_type'] ?? 'Percentage') ?>: <?= floatval($ct['default_percentage'] ?? 0) ?>%
        </span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Modal: Grant Concession -->
<div id="modal-grant-concession" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative">
    <button onclick="document.getElementById('modal-grant-concession').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Grant Student Fee Concession</h3>
    <p class="text-xs text-[#44474f] mb-5">Attach an approved fee waiver or scholarship to an enrolled scholar.</p>

    <form action="process.php" method="post" class="space-y-4 text-xs">
      <div>
        <label class="block font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Enrolled Student *</label>
        <select name="enrollment_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-xs focus:outline-none focus:border-[#002452] bg-white" required>
          <option value="">Select Enrolled Student…</option>
          <?php foreach ($activeEnrollments as $enr): ?>
            <option value="<?= $enr['enrollment_id'] ?>">
              [<?= htmlspecialchars($enr['registration_no'] ?? '') ?>] <?= htmlspecialchars(($enr['first_name'] ?? '') . ' ' . ($enr['last_name'] ?? '')) ?> (<?= htmlspecialchars($enr['class_name'] ?? '') ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Concession Type *</label>
          <select name="concession_type_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-xs focus:outline-none focus:border-[#002452] bg-white" required>
            <?php foreach ($concessionTypes as $ct): ?>
              <option value="<?= $ct['concession_type_id'] ?>"><?= htmlspecialchars($ct['type_name'] ?? $ct['concession_type_name'] ?? 'Concession') ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Discount Value (% or PKR) *</label>
          <input type="number" step="0.01" name="value" placeholder="e.g. 50 (for 50%) or 100" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-xs focus:outline-none focus:border-[#002452]" required/>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Effective From</label>
          <input type="date" name="effective_from" value="<?= date('Y-m-01') ?>" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-xs focus:outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Effective To</label>
          <input type="date" name="effective_to" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-xs focus:outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <div>
        <label class="block font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Approval Remarks / Reason</label>
        <textarea name="reason" rows="3" placeholder="Reference approval committee note, orphan verification, or guardian hardship..." class="w-full px-3 py-2 border border-[#e3e2df] rounded-lg text-xs focus:outline-none focus:border-[#002452] resize-none"></textarea>
      </div>

      <div class="flex gap-3 pt-3">
        <button type="button" onclick="document.getElementById('modal-grant-concession').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" name="addconcession" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Grant Concession</button>
      </div>
    </form>
  </div>
</div>

<script>
function showTab(tab) {
  const grantsTab = document.getElementById('tab-grants');
  const typesTab = document.getElementById('tab-types');
  const btnGrants = document.getElementById('tab-btn-grants');
  const btnTypes = document.getElementById('tab-btn-types');

  if (tab === 'grants') {
    grantsTab.classList.remove('hidden');
    typesTab.classList.add('hidden');
    btnGrants.classList.add('border-[#002452]', 'text-[#002452]', 'font-semibold');
    btnGrants.classList.remove('border-transparent', 'text-[#44474f]');
    btnTypes.classList.remove('border-[#002452]', 'text-[#002452]', 'font-semibold');
    btnTypes.classList.add('border-transparent', 'text-[#44474f]');
  } else {
    grantsTab.classList.add('hidden');
    typesTab.classList.remove('hidden');
    btnTypes.classList.add('border-[#002452]', 'text-[#002452]', 'font-semibold');
    btnTypes.classList.remove('border-transparent', 'text-[#44474f]');
    btnGrants.classList.remove('border-[#002452]', 'text-[#002452]', 'font-semibold');
    btnGrants.classList.add('border-transparent', 'text-[#44474f]');
  }
}
</script>

<?php include 'includes/footer.php'; ?>
