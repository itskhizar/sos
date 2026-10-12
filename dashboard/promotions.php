<?php
/**
 * Promotions — dashboard/promotions.php
 */
$dashTitle   = 'Promotions';
$currentDash = 'promotions';
include 'includes/header.php';

$promotedHistory = $database->getAllEnrollments(0, 0, 'Promoted');
$activeStudents = $database->getAllStudents('Active');
$classes = $database->getAllClasses();
$sessions = $database->getAllSessions();
$sections = $database->getAllSections();
$actSession = $database->getActiveSession();
?>

<?php if(isset($_GET['promoted']) && $_GET['promoted'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Student promoted successfully! Past enrollment preserved in history.</div>
<?php elseif(isset($_GET['promoted']) && $_GET['promoted'] == 'error'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm font-medium">Could not promote student. Please verify student and target class/session.</div>
<?php endif; ?>

<!-- Page Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <p class="text-sm text-[#44474f]">Promote students from one academic session to the next class/section.</p>
  <div class="flex items-center gap-3">
    <button onclick="document.getElementById('modal-promote').classList.remove('hidden')"
      class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
      <span class="material-symbols-outlined text-[18px]">upgrade</span>
      Promote Student
    </button>
  </div>
</div>

<!-- Info Banner -->
<div class="bg-[#002452]/5 border border-[#002452]/20 rounded-xl p-4 flex items-start gap-3 mt-4">
  <span class="material-symbols-outlined text-[#002452] text-[22px] shrink-0 mt-0.5">info</span>
  <div>
    <p class="text-sm font-semibold text-[#002452]">Year-End Promotion Workflow &amp; Historical Preservation</p>
    <p class="text-xs text-[#44474f] mt-1">Per SOS Architectural Rules, promotion never deletes or overwrites the past class enrollment. The student receives a brand new enrollment record for the target session, while the past session's record remains vaulted as 'Promoted'.</p>
  </div>
</div>

<!-- Promotion Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mt-6">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex items-center justify-between">
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Promotion History Log</h3>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-6 py-3 text-left font-semibold">Student</th>
          <th class="px-4 py-3 text-left font-semibold">Registration No</th>
          <th class="px-4 py-3 text-left font-semibold">Session</th>
          <th class="px-4 py-3 text-left font-semibold">Completed Class</th>
          <th class="px-4 py-3 text-left font-semibold">Section</th>
          <th class="px-4 py-3 text-left font-semibold">Date Completed</th>
          <th class="px-4 py-3 text-center font-semibold">Archive Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php if (empty($promotedHistory)): ?>
        <tr>
          <td colspan="7" class="px-6 py-8 text-center text-gray-500">
            No promoted student records yet. Click "Promote Student" to advance a scholar to the next academic session.
          </td>
        </tr>
        <?php else: foreach ($promotedHistory as $p):
          $name = trim(($p['first_name'] ?? '') . ' ' . ($p['last_name'] ?? ''));
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-6 py-4 font-semibold text-[#1b1c1a]"><?= htmlspecialchars($name ?: 'Scholar') ?></td>
          <td class="px-4 py-4 font-mono text-xs text-[#002452]"><?= htmlspecialchars($p['registration_no'] ?? '') ?></td>
          <td class="px-4 py-4 text-[#44474f]"><?= htmlspecialchars($p['session_name'] ?? '—') ?></td>
          <td class="px-4 py-4 text-[#44474f] font-medium"><?= htmlspecialchars($p['class_name'] ?? '—') ?></td>
          <td class="px-4 py-4"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#e3e2df] text-[#44474f] text-xs font-bold"><?= htmlspecialchars($p['section_name'] ?? 'A') ?></span></td>
          <td class="px-4 py-4 text-[#747780] text-xs"><?= $p['updated_at'] ? date('M d, Y', strtotime($p['updated_at'])) : '—' ?></td>
          <td class="px-4 py-4 text-center">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200">
              Promoted &amp; Preserved
            </span>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- PROMOTE MODAL -->
<div id="modal-promote" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl p-6 relative">
    <button onclick="document.getElementById('modal-promote').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold mb-1">Promote Student to Next Session</h2>
    <p class="text-sm text-[#44474f] mb-6">Create a new academic enrollment without deleting past history.</p>
    <form action="process.php" method="post" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Select Student *</label>
        <select name="student_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white" required>
          <option value="">Select Active Student…</option>
          <?php foreach ($activeStudents as $st): ?>
            <option value="<?= $st['student_id'] ?>">
              [<?= htmlspecialchars($st['registration_no']) ?>] <?= htmlspecialchars($st['first_name'] . ' ' . $st['last_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Target Session *</label>
          <select name="target_session_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white" required>
            <?php foreach ($sessions as $s): ?>
              <option value="<?= $s['session_id'] ?>"><?= htmlspecialchars($s['session_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Target Class *</label>
          <select name="target_class_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white" required>
            <option value="">Select Next Class…</option>
            <?php foreach ($classes as $c): ?>
              <option value="<?= $c['class_id'] ?>"><?= htmlspecialchars($c['class_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Target Section</label>
        <select name="target_section_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option value="0">Default Section (A)</option>
          <?php foreach ($sections as $sec): ?>
            <option value="<?= $sec['section_id'] ?>"><?= htmlspecialchars($sec['section_name']) ?> (<?= htmlspecialchars($sec['class_name'] ?? '') ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-promote').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" name="promotestudent" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Promote Student</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
