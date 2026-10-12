<?php
/**
 * Enrollment — dashboard/enrollment.php
 */
$dashTitle   = 'Enrollment';
$currentDash = 'enrollment';
include 'includes/header.php';

$enrollments = $database->getAllEnrollments();
$students = $database->getAllStudents('Active');
$classes = $database->getAllClasses();
$sessions = $database->getAllSessions();
$sections = $database->getAllSections();
$actSession = $database->getActiveSession();

$totalEnr = count($enrollments);
$activeEnr = count(array_filter($enrollments, fn($e) => $e['status'] === 'Active'));
$promotedEnr = count(array_filter($enrollments, fn($e) => in_array($e['status'], ['Promoted', 'Completed'])));
$withdrawnEnr = count(array_filter($enrollments, fn($e) => in_array($e['status'], ['Withdrawn', 'Cancelled'])));
?>

<?php if(isset($_GET['enrolled']) && $_GET['enrolled'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Student enrolled successfully into academic session.</div>
<?php elseif(isset($_GET['enrolled']) && $_GET['enrolled'] == 'error'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm font-medium">Could not enroll student. Please check input data.</div>
<?php endif; ?>

<!-- Page Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <p class="text-sm text-[#44474f]">Manage student enrollments into class sessions and sections.</p>
  <button onclick="document.getElementById('modal-enroll').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">person_add</span>
    Enroll Student
  </button>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-[#002452]/10">
      <span class="material-symbols-outlined text-[22px] text-[#002452]">people</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $totalEnr ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Total Enrolled</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-emerald-50">
      <span class="material-symbols-outlined text-[22px] text-emerald-700">check_circle</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $activeEnr ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Active</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-amber-50">
      <span class="material-symbols-outlined text-[22px] text-amber-700">upgrade</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $promotedEnr ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Promoted / Past Sessions</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-sm flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-gray-100">
      <span class="material-symbols-outlined text-[22px] text-gray-700">archive</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $withdrawnEnr ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Withdrawn / Cancelled</p>
    </div>
  </div>
</div>

<!-- Enrollment Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <!-- Search -->
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row gap-3">
    <div class="relative flex-1">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" id="search-enr" onkeyup="searchEnrollments()" placeholder="Search student name, registration no, class…" class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </div>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm" id="enrollment-table">
      <thead class="bg-[#f4f3f0] text-[10px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-6 py-3 text-left font-semibold">Enrollment No.</th>
          <th class="px-4 py-3 text-left font-semibold">Student</th>
          <th class="px-4 py-3 text-left font-semibold">Class</th>
          <th class="px-4 py-3 text-left font-semibold">Section</th>
          <th class="px-4 py-3 text-left font-semibold">Session</th>
          <th class="px-4 py-3 text-left font-semibold">Enrolled On</th>
          <th class="px-4 py-3 text-center font-semibold">Status</th>
          <th class="px-4 py-3 text-center font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php if (empty($enrollments)): ?>
        <tr>
          <td colspan="8" class="px-6 py-8 text-center text-gray-500">
            No active student enrollments found. Click "Enroll Student" to create an enrollment.
          </td>
        </tr>
        <?php else: foreach ($enrollments as $enr):
          $st = $enr['status'] ?? 'Active';
          $stClass = match($st) {
            'Active' => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
            'Promoted' => 'bg-blue-50 text-blue-800 border border-blue-200',
            default => 'bg-gray-100 text-gray-700 border border-gray-200'
          };
          $stName = trim(($enr['first_name'] ?? '') . ' ' . ($enr['last_name'] ?? ''));
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors enr-row">
          <td class="px-6 py-4 font-mono text-xs text-[#002452] font-semibold"><?= htmlspecialchars($enr['enrollment_no'] ?? 'ENR-' . $enr['enrollment_id']) ?></td>
          <td class="px-4 py-4">
            <span class="font-semibold text-[#1b1c1a] block"><?= htmlspecialchars($stName ?: 'Scholar') ?></span>
            <span class="text-[11px] font-mono text-gray-500"><?= htmlspecialchars($enr['registration_no'] ?? '') ?></span>
          </td>
          <td class="px-4 py-4 text-[#44474f] font-medium"><?= htmlspecialchars($enr['class_name'] ?? '—') ?></td>
          <td class="px-4 py-4 text-center">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#002452]/10 text-[#002452] text-xs font-bold"><?= htmlspecialchars($enr['section_name'] ?? 'A') ?></span>
          </td>
          <td class="px-4 py-4 text-[#44474f]"><?= htmlspecialchars($enr['session_name'] ?? '—') ?></td>
          <td class="px-4 py-4 text-[#747780] text-xs"><?= $enr['enrollment_date'] ? date('M d, Y', strtotime($enr['enrollment_date'])) : '—' ?></td>
          <td class="px-4 py-4 text-center">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold <?= $stClass ?>"><?= htmlspecialchars($st) ?></span>
          </td>
          <td class="px-4 py-4 text-center">
            <a href="students.php?student_id=<?= $enr['student_id'] ?>" class="p-1.5 text-[#002452] hover:bg-[#002452]/8 rounded-md transition-all inline-block" title="View 360 Profile">
              <span class="material-symbols-outlined text-[16px]">visibility</span>
            </a>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ENROLL MODAL -->
<div id="modal-enroll" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative">
    <button onclick="document.getElementById('modal-enroll').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold mb-1">Enroll Registered Student</h2>
    <p class="text-sm text-[#44474f] mb-6">Assign a student to an academic session, class and section.</p>
    <form action="process.php" method="post" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Registered Student *</label>
        <select name="student_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white" required>
          <option value="">Select Student…</option>
          <?php foreach ($students as $st): ?>
            <option value="<?= $st['student_id'] ?>">
              [<?= htmlspecialchars($st['registration_no']) ?>] <?= htmlspecialchars($st['first_name'] . ' ' . $st['last_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
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
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Class *</label>
          <select name="class_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white" required>
            <option value="">Select Class…</option>
            <?php foreach ($classes as $c): ?>
              <option value="<?= $c['class_id'] ?>"><?= htmlspecialchars($c['class_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Section</label>
          <select name="section_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
            <option value="0">Default Section (A)</option>
            <?php foreach ($sections as $sec): ?>
              <option value="<?= $sec['section_id'] ?>"><?= htmlspecialchars($sec['section_name']) ?> (<?= htmlspecialchars($sec['class_name'] ?? '') ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-enroll').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" name="enrollstudent" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Enroll Student</button>
      </div>
    </form>
  </div>
</div>

<script>
function searchEnrollments() {
  const q = document.getElementById('search-enr').value.toLowerCase();
  const rows = document.querySelectorAll('.enr-row');
  rows.forEach(r => {
    r.style.display = r.innerText.toLowerCase().includes(q) ? '' : 'none';
  });
}
</script>

<?php include 'includes/footer.php'; ?>
