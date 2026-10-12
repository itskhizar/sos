<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Admission Applications Management — dashboard/admissions.php
 * Module 3: Complete Applications Management, In-Page Document Verification,
 * Direct Student Conversion, and Bulk Multi-Select Conversion
 */
$dashTitle = 'Admission Applications';
$currentDash = 'admissions';
include 'includes/header.php';

$applications = $database->getAllApplications();
$classes = $database->getAllClasses();

// Preload documents for seamless in-page drawer view
foreach ($applications as &$app) {
    $app['docs'] = $database->getApplicationDocuments($app['application_id']);
}
unset($app);

$totalApps = count($applications);
$pendingReview = count(array_filter($applications, fn($a) => in_array($a['status'], ['Submitted', 'UnderReview'])));
$approvedForTest = count(array_filter($applications, fn($a) => in_array($a['status'], ['Verified', 'Eligible', 'TestScheduled'])));
$enrolledCount = count(array_filter($applications, fn($a) => in_array($a['status'], ['Accepted', 'Enrolled', 'Converted'])));
?>

<!-- Feedback Messages -->
<?php if (isset($_GET['converted'])): ?>
<div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2 shadow-xs">
  <span class="material-symbols-outlined text-emerald-600 text-[20px]">how_to_reg</span>
  <span>Application converted successfully into an official SOS Student record!</span>
</div>
<?php elseif (isset($_GET['bulk_converted'])): ?>
<div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2 shadow-xs">
  <span class="material-symbols-outlined text-emerald-600 text-[20px]">groups</span>
  <span>Bulk action complete: <strong><?= intval($_GET['bulk_converted']) ?></strong> applicants converted into registered students!</span>
</div>
<?php elseif (isset($_GET['status_updated'])): ?>
<div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2 shadow-xs">
  <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
  <span>Application status updated successfully.</span>
</div>
<?php elseif (isset($_GET['verified'])): ?>
<div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2 shadow-xs">
  <span class="material-symbols-outlined text-emerald-600 text-[20px]">verified</span>
  <span>Document verification status updated successfully.</span>
</div>
<?php endif; ?>

<!-- Header & Quick Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h2 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Admission Applications</h2>
    <p class="text-xs md:text-sm text-[#44474f] mt-1">
      Track candidate applications, review attached credentials in-place, and directly convert approved scholars into students.
    </p>
  </div>
  <div class="flex flex-wrap items-center gap-3">
    <a href="../admissions-apply.php" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-[#002452] text-white font-semibold text-xs md:text-sm hover:bg-[#001c40] transition-colors shadow-sm whitespace-nowrap">
      <span class="material-symbols-outlined text-[18px]">open_in_new</span>
      <span>Public Application Form</span>
    </a>
  </div>
</div>

<!-- Stats Metric Strip -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
  <div class="bg-white p-5 rounded-xl border border-[#e3e2df] shadow-xs hover:border-[#002452]/30 transition-all">
    <p class="text-[11px] text-[#747780] font-bold uppercase tracking-wider">Total Received</p>
    <p class="font-serif text-3xl font-bold text-[#1b1c1a] mt-1"><?= $totalApps ?></p>
    <p class="text-[11px] text-[#00522d] mt-1 font-semibold flex items-center gap-1">
      <span class="material-symbols-outlined text-[14px]">inbox</span> All-time intake
    </p>
  </div>

  <div class="bg-white p-5 rounded-xl border border-[#e3e2df] shadow-xs hover:border-[#885200]/30 transition-all">
    <p class="text-[11px] text-[#747780] font-bold uppercase tracking-wider">Pending Review</p>
    <p class="font-serif text-3xl font-bold text-[#885200] mt-1"><?= $pendingReview ?></p>
    <p class="text-[11px] text-[#885200] mt-1 font-semibold">Requires scrutiny</p>
  </div>

  <div class="bg-white p-5 rounded-xl border border-[#e3e2df] shadow-xs hover:border-[#00522d]/30 transition-all">
    <p class="text-[11px] text-[#747780] font-bold uppercase tracking-wider">Eligible / For Test</p>
    <p class="font-serif text-3xl font-bold text-[#00522d] mt-1"><?= $approvedForTest ?></p>
    <p class="text-[11px] text-[#747780] mt-1">Ready for Entry Test</p>
  </div>

  <div class="bg-white p-5 rounded-xl border border-[#e3e2df] shadow-xs hover:border-[#002452]/30 transition-all">
    <p class="text-[11px] text-[#747780] font-bold uppercase tracking-wider">Accepted / Converted</p>
    <p class="font-serif text-3xl font-bold text-[#002452] mt-1"><?= $enrolledCount ?></p>
    <p class="text-[11px] text-[#747780] mt-1">Registered in school</p>
  </div>
</div>

<!-- Modern Interactive Status Tabs -->
<div class="bg-white rounded-xl border border-[#e3e2df] p-1.5 shadow-xs flex flex-wrap items-center gap-1 mb-6">
  <button onclick="filterStatus('all')" id="tab-all" class="status-tab active-tab px-4 py-2 rounded-lg font-semibold text-xs transition-all flex items-center gap-2 bg-[#002452] text-white shadow-xs">
    <span>All Applications</span>
    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 text-white font-bold"><?= $totalApps ?></span>
  </button>
  <button onclick="filterStatus('pending')" id="tab-pending" class="status-tab px-4 py-2 rounded-lg font-medium text-xs text-[#44474f] hover:bg-gray-100 transition-all flex items-center gap-2">
    <span>Pending Review</span>
    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-800 font-bold"><?= $pendingReview ?></span>
  </button>
  <button onclick="filterStatus('verified')" id="tab-verified" class="status-tab px-4 py-2 rounded-lg font-medium text-xs text-[#44474f] hover:bg-gray-100 transition-all flex items-center gap-2">
    <span>Eligible / Verified</span>
    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-blue-100 text-blue-800 font-bold"><?= $approvedForTest ?></span>
  </button>
  <button onclick="filterStatus('enrolled')" id="tab-enrolled" class="status-tab px-4 py-2 rounded-lg font-medium text-xs text-[#44474f] hover:bg-gray-100 transition-all flex items-center gap-2">
    <span>Converted / Registered</span>
    <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-bold"><?= $enrolledCount ?></span>
  </button>
</div>

<!-- Bulk Multi-Action Floating Bar (Appears when items are selected) -->
<form id="bulk-action-form" action="process.php" method="post">
  <div id="bulk-action-bar" class="hidden mb-4 p-3.5 bg-[#002452] text-white rounded-xl shadow-md flex flex-wrap items-center justify-between gap-3 animate-fadeIn">
    <div class="flex items-center gap-3">
      <span class="material-symbols-outlined text-amber-400 text-[22px]">checklist</span>
      <span class="text-xs font-semibold" id="selected-count-label">0 Applications Selected</span>
    </div>
    <div class="flex items-center gap-2">
      <button type="submit" name="bulkconverttostudent" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1.5 shadow-sm" onclick="return confirm('Convert all selected applications directly into official registered SOS Students?')">
        <span class="material-symbols-outlined text-[16px]">person_add</span>
        <span>Convert Selected to Students Directly</span>
      </button>
      <button type="button" onclick="clearAllSelections()" class="px-3 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-medium rounded-lg transition-colors">
        Cancel
      </button>
    </div>
  </div>

  <!-- Applications Table Card -->
  <div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden">
    
    <!-- Table Filter Toolbar -->
    <div class="p-4 border-b border-[#e3e2df] flex flex-col sm:flex-row justify-between items-center gap-3 bg-[#faf9f6]">
      <div class="flex items-center gap-2 w-full sm:w-auto">
        <div class="relative flex-1 sm:w-72">
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
          <input id="search-input" onkeyup="searchTable()" type="text" placeholder="Search applicant, reference ID, father..." class="pl-9 pr-3 py-2 rounded-lg border border-[#e3e2df] text-xs bg-white w-full outline-none focus:border-[#002452]"/>
        </div>
        <select id="grade-filter" onchange="searchTable()" class="px-3 py-2 rounded-lg border border-[#e3e2df] text-xs bg-white outline-none">
          <option value="">All Classes</option>
          <?php foreach ($classes as $c): ?>
            <option value="<?= htmlspecialchars($c['class_name']) ?>"><?= htmlspecialchars($c['class_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex items-center gap-2 text-xs">
        <button type="button" onclick="window.print()" class="px-3.5 py-2 rounded-lg border border-[#e3e2df] bg-white text-[#44474f] hover:bg-gray-100 flex items-center gap-1.5 font-medium transition-colors">
          <span class="material-symbols-outlined text-[16px]">print</span> Print Applications
        </button>
      </div>
    </div>

    <!-- Applications Table -->
    <div class="overflow-x-auto">
      <table id="applications-table" class="w-full text-left text-xs">
        <thead class="bg-[#f4f3f0] text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
          <tr>
            <th class="py-3.5 px-4 text-center w-10">
              <input type="checkbox" id="select-all-checkbox" onchange="toggleSelectAll(this)" class="rounded text-[#002452] cursor-pointer"/>
            </th>
            <th class="py-3.5 px-4">Ref ID</th>
            <th class="py-3.5 px-4">Applicant Candidate</th>
            <th class="py-3.5 px-4">Applying Grade</th>
            <th class="py-3.5 px-4">Father / Guardian</th>
            <th class="py-3.5 px-4">Docs Attached</th>
            <th class="py-3.5 px-4">Status</th>
            <th class="py-3.5 px-6 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
          <?php if (empty($applications)): ?>
          <tr>
            <td colspan="8" class="py-8 text-center text-gray-500">No applications received yet.</td>
          </tr>
          <?php else: foreach ($applications as $app):
            $st = $app['status'] ?? 'Submitted';
            $badgeClass = match($st) {
              'Verified', 'Eligible' => 'bg-blue-100 text-blue-800',
              'TestScheduled' => 'bg-purple-100 text-purple-800',
              'TestPassed', 'Accepted' => 'bg-emerald-100 text-emerald-800',
              'Converted' => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
              'Rejected' => 'bg-red-100 text-red-800',
              default => 'bg-amber-100 text-amber-800'
            };
            $category = match($st) {
              'Verified', 'Eligible', 'TestScheduled' => 'all verified',
              'Accepted', 'Enrolled', 'TestPassed', 'Converted' => 'all enrolled',
              default => 'all pending'
            };
            $docCount = count($app['docs']);
            $verifiedDocCount = count(array_filter($app['docs'], fn($d) => !empty($d['is_verified'])));
          ?>
          <tr class="app-row hover:bg-gray-50/80 transition-colors" data-category="<?= $category ?>" data-grade="<?= htmlspecialchars($app['class_name'] ?? '') ?>">
            <td class="py-4 px-4 text-center">
              <input type="checkbox" name="selected_apps[]" value="<?= $app['application_id'] ?>" class="app-checkbox rounded text-[#002452] cursor-pointer" onchange="updateBulkBar()"/>
            </td>
            <td class="py-4 px-4 font-mono font-bold text-[#002452] whitespace-nowrap">
              <?= htmlspecialchars($app['application_no']) ?>
            </td>
            <td class="py-4 px-4 font-semibold">
              <div class="text-sm font-bold text-[#1b1c1a]"><?= htmlspecialchars($app['student_name']) ?></div>
              <div class="text-[11px] text-[#747780] font-normal"><?= htmlspecialchars($app['gender'] ?: '—') ?> &bull; DOB: <?= !empty($app['date_of_birth']) ? date('d M Y', strtotime($app['date_of_birth'])) : '—' ?></div>
            </td>
            <td class="py-4 px-4 font-semibold text-[#002452]">
              <?= htmlspecialchars($app['class_name'] ?? '—') ?>
              <div class="text-[11px] text-[#747780] font-normal"><?= htmlspecialchars($app['session_name'] ?? '') ?></div>
            </td>
            <td class="py-4 px-4 text-[#44474f]">
              <div class="font-medium"><?= htmlspecialchars($app['parent_name'] ?: 'Guardian') ?></div>
              <div class="text-[11px] text-[#747780] font-mono"><?= htmlspecialchars($app['phone'] ?: '—') ?></div>
            </td>
            <td class="py-4 px-4">
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold <?= ($verifiedDocCount > 0 && $verifiedDocCount === $docCount) ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' ?>">
                <span class="material-symbols-outlined text-[14px]">attachment</span>
                <?= $verifiedDocCount ?> / <?= $docCount ?> Verified
              </span>
            </td>
            <td class="py-4 px-4">
              <span class="px-2.5 py-1 rounded-full text-[11px] font-bold <?= $badgeClass ?>">
                <?= htmlspecialchars($st) ?>
              </span>
            </td>
            <td class="py-4 px-6 text-right whitespace-nowrap">
              <div class="flex items-center justify-end gap-1">
                <!-- Direct Convert to Student Button -->
                <?php if ($st !== 'Converted'): ?>
                <button type="button" onclick="directConvertToStudent(<?= $app['application_id'] ?>, '<?= htmlspecialchars(addslashes($app['student_name'])) ?>')" class="p-1.5 text-emerald-700 hover:bg-emerald-50 rounded-md transition-colors" title="Convert Directly to Registered Student">
                  <span class="material-symbols-outlined text-[19px]">person_add</span>
                </button>
                <?php else: ?>
                <span class="p-1.5 text-emerald-600" title="Already Registered">
                  <span class="material-symbols-outlined text-[19px]">verified</span>
                </span>
                <?php endif; ?>

                <!-- View In-Page Details & Documents -->
                <button type="button" onclick="openAppDetailModal(<?= htmlspecialchars(json_encode($app)) ?>)" class="p-1.5 text-[#002452] hover:bg-[#002452]/8 rounded-md transition-colors" title="View Application & Verify Documents">
                  <span class="material-symbols-outlined text-[19px]">visibility</span>
                </button>

                <!-- Change Status -->
                <button type="button" onclick="openChangeStatusModal(<?= $app['application_id'] ?>, '<?= htmlspecialchars($app['application_no']) ?>', '<?= htmlspecialchars($st) ?>')" class="p-1.5 text-[#885200] hover:bg-[#885200]/8 rounded-md transition-colors" title="Update Status">
                  <span class="material-symbols-outlined text-[19px]">edit_note</span>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</form>

<!-- Modal / Drawer: Application Detail & In-Page Document Verification -->
<div id="modal-app-detail" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 relative max-h-[92vh] overflow-y-auto border border-[#e3e2df]">
    <button onclick="document.getElementById('modal-app-detail').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 p-1">
      <span class="material-symbols-outlined text-[24px]">close</span>
    </button>
    
    <div class="flex items-center gap-3 border-b border-[#e3e2df] pb-4 mb-4">
      <div id="detail-avatar" class="w-14 h-14 rounded-full bg-[#002452] text-white font-serif font-bold text-xl flex items-center justify-center shadow-xs">
        SA
      </div>
      <div>
        <h3 id="detail-name" class="font-serif text-xl font-bold text-[#002452]">Student Candidate</h3>
        <p id="detail-ref" class="text-xs font-mono font-semibold text-[#C6892E]">Ref: APP-XXXX</p>
      </div>
    </div>

    <!-- Candidate Meta Information -->
    <div class="space-y-4 text-xs mb-6">
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-[#faf9f6] p-4 rounded-xl border border-[#e3e2df]">
        <div><span class="text-[#747780]">Applying Class:</span> <strong id="detail-class" class="text-[#002452] block mt-0.5 text-sm">—</strong></div>
        <div><span class="text-[#747780]">Date of Birth:</span> <strong id="detail-dob" class="text-[#1b1c1a] block mt-0.5">—</strong></div>
        <div><span class="text-[#747780]">Gender:</span> <strong id="detail-gender" class="text-[#1b1c1a] block mt-0.5">—</strong></div>
        <div><span class="text-[#747780]">Father / Guardian:</span> <strong id="detail-parent" class="text-[#1b1c1a] block mt-0.5">—</strong></div>
        <div><span class="text-[#747780]">Contact Mobile:</span> <strong id="detail-phone" class="text-[#1b1c1a] block mt-0.5 font-mono">—</strong></div>
        <div><span class="text-[#747780]">Email Address:</span> <strong id="detail-email" class="text-[#1b1c1a] block mt-0.5 truncate">—</strong></div>
        <div class="col-span-2 sm:col-span-3"><span class="text-[#747780]">Address:</span> <strong id="detail-address" class="text-[#1b1c1a] block mt-0.5">—</strong></div>
      </div>
    </div>

    <!-- IN-PAGE DOCUMENT GALLERY & VERIFICATION -->
    <div class="border-t border-[#e3e2df] pt-4 mb-6">
      <h4 class="font-bold text-sm text-[#002452] mb-3 flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[#C6892E] text-[18px]">verified_user</span>
        <span>Attached Admission Documents &amp; In-Line Verification</span>
      </h4>
      <div id="detail-documents-list" class="space-y-3">
        <!-- Rendered dynamically by JS -->
      </div>
    </div>

    <!-- Modal Action Footer -->
    <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-[#e3e2df]">
      <!-- Direct Convert Form -->
      <form action="process.php" method="post" id="detail-convert-form">
        <input type="hidden" name="application_id" id="detail-convert-app-id" value="">
        <input type="hidden" name="return_to" value="admissions.php">
        <button type="submit" name="converttostudent" class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5 shadow-sm" onclick="return confirm('Convert this applicant directly into an official SOS Registered Student?')">
          <span class="material-symbols-outlined text-[16px]">how_to_reg</span>
          <span>Convert Directly to Student</span>
        </button>
      </form>

      <div class="flex items-center gap-2">
        <a id="detail-schedule-test-btn" href="entry-tests.php" class="px-4 py-2.5 bg-[#002452] text-white rounded-lg text-xs font-semibold hover:bg-[#001c40] flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px]">quiz</span>
          <span>Schedule Entry Test</span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Change Application Status -->
<div id="modal-change-status" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button onclick="document.getElementById('modal-change-status').classList.add('hidden')" class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-lg font-bold text-[#002452] mb-1">Update Application Status</h3>
    <p id="status-modal-app" class="text-xs text-[#747780] mb-4">Application APP-XXXX</p>
    <form action="process.php" method="post" class="space-y-4 text-xs">
      <input type="hidden" name="application_id" id="status_app_id" value="">
      <div>
        <label class="block font-semibold text-[#132644] mb-1">New Status *</label>
        <select name="status" id="status_select" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded-lg p-2.5 text-xs outline-none" required>
          <option value="Submitted">Submitted (Initial Intake)</option>
          <option value="UnderReview">Under Review</option>
          <option value="Verified">Verified Documents</option>
          <option value="Eligible">Eligible for Entry Test</option>
          <option value="TestScheduled">Test Scheduled</option>
          <option value="TestPassed">Test Passed</option>
          <option value="Accepted">Accepted / Ready to Enroll</option>
          <option value="Converted">Converted to Student</option>
          <option value="Rejected">Rejected</option>
        </select>
      </div>
      <div>
        <label class="block font-semibold text-[#132644] mb-1">Remarks / Internal Notes</label>
        <textarea name="remarks" rows="3" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded-lg p-2.5 text-xs outline-none" placeholder="Add rationale or requirements..."></textarea>
      </div>
      <div class="flex justify-end gap-2 pt-3 border-t border-[#EAE7DE]">
        <button type="button" onclick="document.getElementById('modal-change-status').classList.add('hidden')" class="px-4 py-2 border rounded-lg font-semibold text-[#54524B]">Cancel</button>
        <button type="submit" name="updateappstatus" class="px-5 py-2 bg-[#002452] text-white rounded-lg font-semibold hover:bg-[#001c40]">Save Status</button>
      </div>
    </form>
  </div>
</div>

<!-- Standalone Hidden Form for Direct Single Conversion -->
<form id="single-convert-form" action="process.php" method="post" class="hidden">
  <input type="hidden" name="application_id" id="single-convert-app-id" value="">
  <input type="hidden" name="return_to" value="admissions.php">
  <input type="hidden" name="converttostudent" value="1">
</form>

<script>
// Filter Tab Mechanism
function filterStatus(status) {
  document.querySelectorAll('.status-tab').forEach(b => {
    b.classList.remove('bg-[#002452]', 'text-white', 'shadow-xs');
    b.classList.add('text-[#44474f]');
  });
  const activeBtn = document.getElementById(`tab-${status}`);
  if (activeBtn) {
    activeBtn.classList.add('bg-[#002452]', 'text-white', 'shadow-xs');
    activeBtn.classList.remove('text-[#44474f]');
  }

  const rows = document.querySelectorAll('.app-row');
  rows.forEach(r => {
    const cat = r.getAttribute('data-category');
    if (status === 'all' || cat.includes(status)) {
      r.style.display = '';
    } else {
      r.style.display = 'none';
    }
  });
}

// Live Search Filter
function searchTable() {
  const query = document.getElementById('search-input').value.toLowerCase();
  const grade = document.getElementById('grade-filter').value.toLowerCase();
  const rows = document.querySelectorAll('.app-row');

  rows.forEach(r => {
    const text = r.innerText.toLowerCase();
    const rowGrade = (r.getAttribute('data-grade') || '').toLowerCase();
    const matchesQuery = text.includes(query);
    const matchesGrade = !grade || rowGrade === grade;

    if (matchesQuery && matchesGrade) {
      r.style.display = '';
    } else {
      r.style.display = 'none';
    }
  });
}

// Bulk Selection Handling
function toggleSelectAll(masterCb) {
  const cbs = document.querySelectorAll('.app-checkbox');
  cbs.forEach(cb => {
    if (cb.closest('tr').style.display !== 'none') {
      cb.checked = masterCb.checked;
    }
  });
  updateBulkBar();
}

function updateBulkBar() {
  const checked = document.querySelectorAll('.app-checkbox:checked');
  const bar = document.getElementById('bulk-action-bar');
  const label = document.getElementById('selected-count-label');

  if (checked.length > 0) {
    bar.classList.remove('hidden');
    label.textContent = `${checked.length} Application${checked.length > 1 ? 's' : ''} Selected`;
  } else {
    bar.classList.add('hidden');
  }
}

function clearAllSelections() {
  document.querySelectorAll('.app-checkbox').forEach(cb => cb.checked = false);
  const master = document.getElementById('select-all-checkbox');
  if (master) master.checked = false;
  updateBulkBar();
}

// Direct 1-Click Conversion
function directConvertToStudent(appId, studentName) {
  if (confirm(`Convert applicant "${studentName}" directly into an officially registered SOS Student?\n\nThis will issue an official Registration Number and create their permanent student profile.`)) {
    document.getElementById('single-convert-app-id').value = appId;
    document.getElementById('single-convert-form').submit();
  }
}

// Open In-Page Full Detail & Document Drawer
function openAppDetailModal(app) {
  document.getElementById('detail-name').textContent = app.student_name || 'Candidate';
  document.getElementById('detail-ref').textContent = `Ref: ${app.application_no}`;
  document.getElementById('detail-class').textContent = app.class_name || '—';
  document.getElementById('detail-dob').textContent = app.date_of_birth ? new Date(app.date_of_birth).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : '—';
  document.getElementById('detail-gender').textContent = app.gender || '—';
  document.getElementById('detail-parent').textContent = app.parent_name || 'Guardian';
  document.getElementById('detail-phone').textContent = app.phone || '—';
  document.getElementById('detail-email').textContent = app.email || '—';
  document.getElementById('detail-address').textContent = app.address || '—';

  // Initials
  const initials = (app.student_name || 'SA').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
  document.getElementById('detail-avatar').textContent = initials;

  // Convert form binding
  document.getElementById('detail-convert-app-id').value = app.application_id;
  document.getElementById('detail-schedule-test-btn').href = `entry-tests.php?app_id=${app.application_id}`;

  // Populate Documents List
  const docList = document.getElementById('detail-documents-list');
  docList.innerHTML = '';

  if (!app.docs || app.docs.length === 0) {
    docList.innerHTML = '<p class="text-gray-400 italic text-center py-4">No documents attached for this application.</p>';
  } else {
    app.docs.forEach(doc => {
      const isVer = parseInt(doc.is_verified) === 1;
      const docItem = document.createElement('div');
      docItem.className = 'p-3 rounded-xl border border-gray-200 bg-gray-50/60 flex items-center justify-between gap-3 text-xs';
      
      docItem.innerHTML = `
        <div class="flex items-center gap-2.5 truncate">
          <span class="material-symbols-outlined text-[20px] text-[#002452]">description</span>
          <div>
            <span class="font-bold text-[#1b1c1a] block">${doc.document_type}</span>
            <a href="../${doc.file_path}" target="_blank" class="text-blue-700 hover:underline flex items-center gap-1 text-[11px] font-medium">
              <span>View / Download File</span>
              <span class="material-symbols-outlined text-[12px]">open_in_new</span>
            </a>
          </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
          <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold ${isVer ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'}">
            ${isVer ? 'Verified' : 'Pending Verification'}
          </span>
          <form action="process.php" method="post" class="inline">
            <input type="hidden" name="admission_doc_id" value="${doc.admission_doc_id}"/>
            <input type="hidden" name="application_id" value="${app.application_id}"/>
            <input type="hidden" name="is_verified" value="${isVer ? 0 : 1}"/>
            <input type="hidden" name="return_to" value="admissions.php"/>
            <button type="submit" name="verifydoc" class="px-2.5 py-1 text-[11px] font-bold rounded ${isVer ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' : 'bg-emerald-700 text-white hover:bg-emerald-800'} transition-colors">
              ${isVer ? 'Mark Unverified' : 'Verify'}
            </button>
          </form>
        </div>
      `;
      docList.appendChild(docItem);
    });
  }

  document.getElementById('modal-app-detail').classList.remove('hidden');
}

// Open Status Modal
function openChangeStatusModal(appId, appNo, currentStatus) {
  document.getElementById('status_app_id').value = appId;
  document.getElementById('status-modal-app').textContent = `Application ${appNo}`;
  document.getElementById('status_select').value = currentStatus;
  document.getElementById('modal-change-status').classList.remove('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
