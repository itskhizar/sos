<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Students Directory & 360-Degree Profile — dashboard/students.php
 * Module 5: Permanent Student Records, 360-Degree Profile View, Documents & Dynamic Fee Challans
 */
$dashTitle   = 'Students Directory';
$currentDash = 'students';
include 'includes/header.php';

$search = $_GET['q'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$classFilter = intval($_GET['class_id'] ?? 0);

$classes = $database->getAllClasses();
$students = $database->getAllStudents($statusFilter, $classFilter, 0, $search);

// 360 Profile inspection
$profileStudentId = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
$profile = $profileStudentId ? $database->getStudentProfileFull($profileStudentId) : null;

// Determine student photo if present in documents
$studentPhoto = null;
if ($profile && !empty($profile['documents'])) {
    foreach ($profile['documents'] as $doc) {
        if (stripos($doc['document_type'], 'photo') !== false || stripos($doc['document_type'], 'picture') !== false) {
            $studentPhoto = $doc['file_path'];
            break;
        }
    }
}
?>

<!-- Header & Quick Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
  <div>
    <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Registered Students Directory</h1>
    <p class="text-xs md:text-sm text-[#54524B] mt-0.5">Permanent institutional student registry with complete 360-degree academic records, documents, and fee ledgers.</p>
  </div>
  <div class="flex gap-2.5">
    <a href="admissions.php" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#002452] text-white font-semibold text-xs md:text-sm rounded-lg hover:bg-[#001a3d] transition-colors shadow-sm">
      <span class="material-symbols-outlined text-[18px]">person_add</span>
      <span>Admissions Pipeline</span>
    </a>
  </div>
</div>

<!-- Alerts / Notifications -->
<?php if (isset($_GET['created'])): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">verified</span>
    <span class="font-semibold">Student officially registered! Registration number issued and permanent student profile created.</span>
  </div>
<?php elseif (isset($_GET['challan_id']) && isset($_GET['generated'])): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center justify-between shadow-xs">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-emerald-600 text-[20px]">receipt_long</span>
      <span class="font-semibold">Fee Challan successfully generated! You can now print the 3-part bank deposit slip below.</span>
    </div>
    <a href="students.php?student_id=<?= $profileStudentId ?>" class="text-xs text-emerald-700 underline font-bold">Dismiss</a>
  </div>
<?php elseif (isset($_GET['doc_uploaded'])): ?>
  <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-blue-600 text-[20px]">upload_file</span>
    <span class="font-semibold">Document successfully uploaded and attached to the student's institutional record.</span>
  </div>
<?php endif; ?>

<!-- Quick Search & Filters Bar -->
<div class="bg-white rounded-xl border border-[#e3e2df] p-4 mb-6 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
  <form method="get" action="students.php" class="flex flex-wrap items-center gap-3 w-full md:w-auto flex-1">
    <div class="relative flex-1 min-w-[240px]">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search by Reg #, Name, Phone..." 
        class="w-full pl-9 pr-3 py-2 border border-[#c4c6d0] rounded-lg text-xs bg-white outline-none focus:border-[#002452]"/>
    </div>
    <select name="class_id" onchange="this.form.submit()" class="px-3 py-2 border border-[#c4c6d0] rounded-lg text-xs bg-white text-[#44474f] outline-none">
      <option value="0">All Classes</option>
      <?php foreach ($classes as $cl): ?>
        <option value="<?= $cl['class_id'] ?>" <?= ($classFilter == $cl['class_id']) ? 'selected' : '' ?>><?= htmlspecialchars($cl['class_name']) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-[#c4c6d0] rounded-lg text-xs bg-white text-[#44474f] outline-none">
      <option value="">All Statuses</option>
      <option value="Active" <?= ($statusFilter === 'Active') ? 'selected' : '' ?>>Active</option>
      <option value="Inactive" <?= ($statusFilter === 'Inactive') ? 'selected' : '' ?>>Inactive</option>
      <option value="Graduated" <?= ($statusFilter === 'Graduated') ? 'selected' : '' ?>>Graduated</option>
      <option value="Transferred" <?= ($statusFilter === 'Transferred') ? 'selected' : '' ?>>Transferred</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-[#002452] text-white text-xs font-semibold rounded-lg">Filter</button>
    <?php if ($search || $classFilter || $statusFilter): ?>
      <a href="students.php" class="text-xs text-[#885200] font-semibold hover:underline">Clear Filters</a>
    <?php endif; ?>
  </form>
  <span class="text-xs font-semibold text-[#885200] whitespace-nowrap"><?= count($students) ?> Students Listed</span>
</div>

<!-- If Profile is Selected, Display Comprehensive 360 View -->
<?php if ($profile): 
  $fullName = trim($profile['first_name'] . ' ' . $profile['last_name']);
  $totalCharges = array_sum(array_column($profile['charges'], 'net_amount'));
  $totalPaid = array_sum(array_column($profile['payments'], 'amount'));
  $totalDue = $profile['total_outstanding'] ?? 0;
?>
<div class="bg-white rounded-2xl border-2 border-[#C6892E]/60 shadow-xl overflow-hidden mb-10 transition-all">
  <!-- Profile Header Banner -->
  <div class="bg-gradient-to-r from-[#002452] to-[#001736] text-white p-6 md:p-8">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
      <div class="flex items-center gap-5">
        <?php if ($studentPhoto): ?>
          <img src="../<?= htmlspecialchars($studentPhoto) ?>" alt="<?= htmlspecialchars($fullName) ?>" class="w-20 h-20 md:w-24 md:h-24 rounded-2xl object-cover border-4 border-white/20 shadow-lg"/>
        <?php else: ?>
          <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-[#C6892E] text-white flex items-center justify-center font-serif text-3xl font-bold border-4 border-white/20 shadow-lg">
            <?= strtoupper(substr($profile['first_name'], 0, 1)) ?>
          </div>
        <?php endif; ?>
        <div>
          <div class="flex items-center gap-3">
            <h2 class="font-serif text-2xl md:text-3xl font-bold"><?= htmlspecialchars($fullName) ?></h2>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
              <?= htmlspecialchars($profile['status']) ?>
            </span>
          </div>
          <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs md:text-sm text-gray-300 mt-1 font-mono">
            <span class="text-[#f5d996] font-bold">Registration / Roll #: <?= htmlspecialchars($profile['registration_no']) ?></span>
            <span>&bull;</span>
            <span>Grade: <?= htmlspecialchars($profile['current_enrollment']['class_name'] ?? 'Not Enrolled') ?><?= !empty($profile['current_enrollment']['section_name']) ? ' (' . $profile['current_enrollment']['section_name'] . ')' : '' ?></span>
            <?php if (!empty($profile['current_enrollment']['session_name'])): ?>
              <span>&bull;</span>
              <span>Session: <?= htmlspecialchars($profile['current_enrollment']['session_name']) ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center gap-2 self-end md:self-center">
        <button onclick="document.getElementById('modal-issue-challan').classList.remove('hidden')" 
          class="px-4 py-2 bg-[#C6892E] hover:bg-[#b27824] text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5 shadow-md">
          <span class="material-symbols-outlined text-[16px]">receipt_long</span>
          <span>Issue Fee Challan</span>
        </button>
        <button onclick="document.getElementById('modal-upload-doc').classList.remove('hidden')" 
          class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5 border border-white/20">
          <span class="material-symbols-outlined text-[16px]">upload_file</span>
          <span>Attach Document</span>
        </button>
        <a href="students.php<?= $search ? '?q=' . urlencode($search) : '' ?>" 
          class="p-2 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" title="Close Profile View">
          <span class="material-symbols-outlined text-[20px]">close</span>
        </a>
      </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6 pt-6 border-t border-white/10">
      <div class="bg-white/5 rounded-xl p-3 border border-white/10">
        <span class="text-[10px] uppercase font-bold text-gray-400 block tracking-wider">Total Charges</span>
        <span class="text-base md:text-lg font-bold text-white">Rs. <?= number_format($totalCharges) ?></span>
      </div>
      <div class="bg-white/5 rounded-xl p-3 border border-white/10">
        <span class="text-[10px] uppercase font-bold text-emerald-400 block tracking-wider">Paid Amount</span>
        <span class="text-base md:text-lg font-bold text-emerald-300">Rs. <?= number_format($totalPaid) ?></span>
      </div>
      <div class="bg-white/5 rounded-xl p-3 border border-white/10">
        <span class="text-[10px] uppercase font-bold text-amber-400 block tracking-wider">Outstanding Dues</span>
        <span class="text-base md:text-lg font-bold text-amber-300">Rs. <?= number_format($totalDue) ?></span>
      </div>
      <div class="bg-white/5 rounded-xl p-3 border border-white/10">
        <span class="text-[10px] uppercase font-bold text-blue-300 block tracking-wider">Attached Documents</span>
        <span class="text-base md:text-lg font-bold text-blue-200"><?= count($profile['documents']) ?> Files</span>
      </div>
    </div>
  </div>

  <!-- Detail Tabs Navigation -->
  <div class="border-b border-[#e3e2df] bg-[#faf9f6] px-6 flex gap-4 overflow-x-auto text-xs font-bold text-[#44474f]">
    <button onclick="switchTab('tab-bio')" id="btn-tab-bio" class="tab-btn py-3.5 border-b-2 border-[#002452] text-[#002452] flex items-center gap-1.5 whitespace-nowrap">
      <span class="material-symbols-outlined text-[16px]">person</span>
      <span>Personal &amp; Guardian Info</span>
    </button>
    <button onclick="switchTab('tab-docs')" id="btn-tab-docs" class="tab-btn py-3.5 border-b-2 border-transparent hover:text-[#002452] flex items-center gap-1.5 whitespace-nowrap">
      <span class="material-symbols-outlined text-[16px]">folder_shared</span>
      <span>Student Documents (<?= count($profile['documents']) ?>)</span>
    </button>
    <button onclick="switchTab('tab-academic')" id="btn-tab-academic" class="tab-btn py-3.5 border-b-2 border-transparent hover:text-[#002452] flex items-center gap-1.5 whitespace-nowrap">
      <span class="material-symbols-outlined text-[16px]">school</span>
      <span>Academic Enrollments (<?= count($profile['enrollments']) ?>)</span>
    </button>
    <button onclick="switchTab('tab-finance')" id="btn-tab-finance" class="tab-btn py-3.5 border-b-2 border-transparent hover:text-[#002452] flex items-center gap-1.5 whitespace-nowrap">
      <span class="material-symbols-outlined text-[16px]">payments</span>
      <span>Fee Ledger &amp; Payments</span>
    </button>
    <button onclick="switchTab('tab-challans')" id="btn-tab-challans" class="tab-btn py-3.5 border-b-2 border-transparent hover:text-[#002452] flex items-center gap-1.5 whitespace-nowrap">
      <span class="material-symbols-outlined text-[16px]">receipt_long</span>
      <span>Bank Challans (<?= count($profile['challans']) ?>)</span>
    </button>
  </div>

  <!-- Tab Contents -->
  <div class="p-6 md:p-8">
    
    <!-- TAB 1: BIO & GUARDIAN -->
    <div id="tab-bio" class="tab-content space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Student Details -->
        <div class="bg-gray-50/70 p-5 rounded-xl border border-gray-200">
          <h3 class="font-serif text-base font-bold text-[#002452] mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#C6892E] text-[18px]">badge</span>
            Student Personal Details
          </h3>
          <div class="space-y-2 text-xs">
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Full Name:</span><span class="font-bold text-[#1b1c1a]"><?= htmlspecialchars($fullName) ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Registration No:</span><span class="font-mono font-bold text-[#002452]"><?= htmlspecialchars($profile['registration_no']) ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Date of Birth:</span><span class="font-semibold text-[#1b1c1a]"><?= !empty($profile['date_of_birth']) ? date('d M Y', strtotime($profile['date_of_birth'])) : '—' ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Gender:</span><span class="font-semibold text-[#1b1c1a]"><?= htmlspecialchars($profile['gender'] ?: '—') ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Blood Group:</span><span class="font-semibold text-[#1b1c1a]"><?= htmlspecialchars($profile['blood_group'] ?: '—') ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Religion / Nationality:</span><span class="font-semibold text-[#1b1c1a]"><?= htmlspecialchars($profile['religion'] ?: 'Islam') ?> &bull; <?= htmlspecialchars($profile['nationality'] ?: 'Pakistani') ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Primary Contact:</span><span class="font-semibold text-[#1b1c1a]"><?= htmlspecialchars($profile['phone'] ?: '—') ?></span></div>
            <div class="flex justify-between py-1"><span class="text-gray-500 font-medium">Address:</span><span class="font-semibold text-[#1b1c1a] text-right"><?= htmlspecialchars($profile['address'] ?: 'Dhodial, Mansehra') ?></span></div>
          </div>
        </div>

        <!-- Guardian & Admission Info -->
        <div class="bg-gray-50/70 p-5 rounded-xl border border-gray-200">
          <h3 class="font-serif text-base font-bold text-[#002452] mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#C6892E] text-[18px]">family_restroom</span>
            Guardian &amp; Background Information
          </h3>
          <div class="space-y-2 text-xs">
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Father / Guardian Name:</span><span class="font-bold text-[#1b1c1a]"><?= htmlspecialchars($profile['parent_name'] ?: 'Guardian') ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Father / Guardian CNIC:</span><span class="font-mono font-bold text-[#002452]"><?= htmlspecialchars($profile['parent_cnic'] ?: '—') ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Guardian Phone:</span><span class="font-semibold text-[#1b1c1a]"><?= htmlspecialchars($profile['parent_phone'] ?: $profile['phone'] ?: '—') ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Guardian Email:</span><span class="font-semibold text-[#1b1c1a]"><?= htmlspecialchars($profile['parent_email'] ?: '—') ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Application Reference:</span><span class="font-mono text-[#885200] font-bold"><?= htmlspecialchars($profile['application_no'] ?: 'Direct Entry') ?></span></div>
            <div class="flex justify-between py-1 border-b border-gray-200"><span class="text-gray-500 font-medium">Previous Institution:</span><span class="font-semibold text-[#1b1c1a]"><?= htmlspecialchars($profile['previous_school'] ?: 'N/A (Early Childhood)') ?></span></div>
            <div class="flex justify-between py-1"><span class="text-gray-500 font-medium">Previous Grade &amp; Result:</span><span class="font-semibold text-[#1b1c1a]"><?= htmlspecialchars($profile['previous_class'] ?: 'N/A') ?> (<?= htmlspecialchars($profile['previous_marks_percentage'] ?: '—') ?>%)</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 2: ATTACHED DOCUMENTS -->
    <div id="tab-docs" class="tab-content hidden space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-serif text-base font-bold text-[#002452]">Official Attached Student Documents</h3>
          <p class="text-xs text-[#54524B]">Student photographs, CNIC cards, and academic certificates verified during admission.</p>
        </div>
        <button onclick="document.getElementById('modal-upload-doc').classList.remove('hidden')" 
          class="px-3 py-1.5 bg-[#002452] text-white rounded-lg text-xs font-semibold flex items-center gap-1">
          <span class="material-symbols-outlined text-[16px]">add</span>
          <span>Upload New File</span>
        </button>
      </div>

      <?php if (empty($profile['documents'])): ?>
        <div class="p-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-300">
          <span class="material-symbols-outlined text-4xl text-gray-400 mb-2">folder_off</span>
          <p class="text-xs text-gray-500">No documents currently on file for this student.</p>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <?php foreach ($profile['documents'] as $doc): 
            $docType = $doc['document_type'];
            $filePath = $doc['file_path'];
            $isImg = preg_match('/\.(jpg|jpeg|png|webp)$/i', $filePath);
          ?>
          <div class="p-4 rounded-xl border border-[#e3e2df] bg-white shadow-xs flex flex-col justify-between hover:border-[#002452] transition-colors">
            <div>
              <div class="flex items-center justify-between mb-3">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#002452]/10 text-[#002452] uppercase tracking-wider">
                  <?= htmlspecialchars($doc['source'] === 'admission' ? 'Admission Record' : 'Student Record') ?>
                </span>
                <?php if (!empty($doc['is_verified'])): ?>
                  <span class="inline-flex items-center gap-1 text-[11px] text-emerald-700 font-bold">
                    <span class="material-symbols-outlined text-[14px]">verified</span> Verified
                  </span>
                <?php else: ?>
                  <span class="text-[11px] text-amber-700 font-bold">Unverified</span>
                <?php endif; ?>
              </div>
              
              <h4 class="font-bold text-sm text-[#1b1c1a] mb-1"><?= htmlspecialchars($docType) ?></h4>
              <p class="text-[11px] text-gray-500">Uploaded: <?= date('d M Y, h:i A', strtotime($doc['uploaded_on'])) ?></p>

              <?php if ($isImg): ?>
                <div class="mt-3 rounded-lg overflow-hidden border border-gray-200 h-28 bg-gray-50 flex items-center justify-center">
                  <img src="../<?= htmlspecialchars($filePath) ?>" alt="<?= htmlspecialchars($docType) ?>" class="h-full w-full object-cover"/>
                </div>
              <?php else: ?>
                <div class="mt-3 rounded-lg border border-gray-200 h-28 bg-gray-50 flex flex-col items-center justify-center text-gray-400">
                  <span class="material-symbols-outlined text-3xl">description</span>
                  <span class="text-[10px] mt-1 font-mono">Document File</span>
                </div>
              <?php endif; ?>
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
              <a href="../<?= htmlspecialchars($filePath) ?>" target="_blank" 
                class="inline-flex items-center gap-1 text-xs font-bold text-[#002452] hover:underline">
                <span class="material-symbols-outlined text-[15px]">visibility</span> View Full Document
              </a>
              <a href="../<?= htmlspecialchars($filePath) ?>" download 
                class="p-1 text-gray-500 hover:text-gray-800" title="Download">
                <span class="material-symbols-outlined text-[16px]">download</span>
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- TAB 3: ACADEMIC ENROLLMENTS -->
    <div id="tab-academic" class="tab-content hidden space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-serif text-base font-bold text-[#002452]">Academic Session Enrollments</h3>
          <p class="text-xs text-[#54524B]">Current and historical class enrollments throughout institutional tenure.</p>
        </div>
        <a href="enrollment.php" class="px-3 py-1.5 bg-[#002452] text-white rounded-lg text-xs font-semibold flex items-center gap-1">
          <span class="material-symbols-outlined text-[16px]">add</span>
          <span>Enroll into New Session</span>
        </a>
      </div>

      <div class="overflow-x-auto border border-[#e3e2df] rounded-xl">
        <table class="w-full text-left text-xs">
          <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
            <tr>
              <th class="py-3 px-4">Enrollment #</th>
              <th class="py-3 px-4">Academic Session</th>
              <th class="py-3 px-4">Class</th>
              <th class="py-3 px-4">Section</th>
              <th class="py-3 px-4">Enrolled On</th>
              <th class="py-3 px-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#e3e2df]">
            <?php if (empty($profile['enrollments'])): ?>
              <tr><td colspan="6" class="py-6 text-center text-gray-500">No active or historical enrollment records found.</td></tr>
            <?php else: foreach ($profile['enrollments'] as $enr): ?>
              <tr>
                <td class="py-3 px-4 font-mono font-bold text-[#002452]"><?= htmlspecialchars($enr['enrollment_no'] ?: 'ENR-' . $enr['enrollment_id']) ?></td>
                <td class="py-3 px-4 font-semibold text-[#1b1c1a]"><?= htmlspecialchars($enr['session_name'] ?: 'Session') ?></td>
                <td class="py-3 px-4 font-bold text-[#002452]"><?= htmlspecialchars($enr['class_name'] ?: 'Class') ?></td>
                <td class="py-3 px-4"><?= htmlspecialchars($enr['section_name'] ?: 'A') ?></td>
                <td class="py-3 px-4 text-gray-500"><?= $enr['enrollment_date'] ? date('d M Y', strtotime($enr['enrollment_date'])) : '—' ?></td>
                <td class="py-3 px-4 text-center">
                  <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold <?= ($enr['status'] === 'Active') ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' ?>">
                    <?= htmlspecialchars($enr['status']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- TAB 4: FINANCIAL LEDGER & PAYMENTS -->
    <div id="tab-finance" class="tab-content hidden space-y-6">
      <div>
        <h3 class="font-serif text-base font-bold text-[#002452]">Student Financial Ledger &amp; Fee Charges</h3>
        <p class="text-xs text-[#54524B]">Itemized monthly billing charges, tuition fees, surcharges, and payment receipts.</p>
      </div>

      <!-- Charges Breakdown -->
      <div class="overflow-x-auto border border-[#e3e2df] rounded-xl mb-6">
        <table class="w-full text-left text-xs">
          <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
            <tr>
              <th class="py-3 px-4">Fee Particulars</th>
              <th class="py-3 px-4">Billing Month</th>
              <th class="py-3 px-4">Due Date</th>
              <th class="py-3 px-4 text-right">Net Amount</th>
              <th class="py-3 px-4 text-right">Outstanding</th>
              <th class="py-3 px-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#e3e2df]">
            <?php if (empty($profile['charges'])): ?>
              <tr><td colspan="6" class="py-6 text-center text-gray-500">No fee charges recorded yet for this student.</td></tr>
            <?php else: foreach ($profile['charges'] as $chg): ?>
              <tr>
                <td class="py-3 px-4 font-bold text-[#002452]"><?= htmlspecialchars($chg['fee_type_name']) ?></td>
                <td class="py-3 px-4 font-mono"><?= htmlspecialchars($chg['billing_month'] ?: '—') ?></td>
                <td class="py-3 px-4 text-gray-500"><?= $chg['due_date'] ? date('d M Y', strtotime($chg['due_date'])) : '—' ?></td>
                <td class="py-3 px-4 text-right font-bold text-[#1b1c1a]">Rs. <?= number_format($chg['net_amount']) ?></td>
                <td class="py-3 px-4 text-right font-bold text-red-700">Rs. <?= number_format($chg['outstanding_amount']) ?></td>
                <td class="py-3 px-4 text-center">
                  <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold <?= ($chg['status'] === 'Paid') ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                    <?= htmlspecialchars($chg['status']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Payment Receipts -->
      <div>
        <h4 class="font-bold text-sm text-[#002452] mb-2 flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px] text-emerald-600">verified</span>
          Verified Payment Receipts
        </h4>
        <div class="overflow-x-auto border border-[#e3e2df] rounded-xl">
          <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
              <tr>
                <th class="py-3 px-4">Receipt #</th>
                <th class="py-3 px-4">Challan Ref</th>
                <th class="py-3 px-4">Payment Date</th>
                <th class="py-3 px-4">Payment Method</th>
                <th class="py-3 px-4 text-right">Amount Paid</th>
                <th class="py-3 px-4 text-center">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e2df]">
              <?php if (empty($profile['payments'])): ?>
                <tr><td colspan="6" class="py-6 text-center text-gray-500">No payment transactions recorded for this student yet.</td></tr>
              <?php else: foreach ($profile['payments'] as $pmt): ?>
                <tr>
                  <td class="py-3 px-4 font-mono font-bold text-[#002452]"><?= htmlspecialchars($pmt['receipt_no'] ?: 'REC-' . $pmt['payment_id']) ?></td>
                  <td class="py-3 px-4 font-mono"><?= htmlspecialchars($pmt['challan_no'] ?: '—') ?></td>
                  <td class="py-3 px-4"><?= date('d M Y', strtotime($pmt['payment_date'])) ?></td>
                  <td class="py-3 px-4 font-semibold"><?= htmlspecialchars($pmt['payment_method']) ?></td>
                  <td class="py-3 px-4 text-right font-bold text-emerald-700">Rs. <?= number_format($pmt['amount']) ?></td>
                  <td class="py-3 px-4 text-center">
                    <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-100 text-emerald-800">
                      <?= htmlspecialchars($pmt['status'] ?: 'Verified') ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- TAB 5: BANK CHALLANS & DIRECT PRINT -->
    <div id="tab-challans" class="tab-content hidden space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-serif text-base font-bold text-[#002452]">3-Part Bank Fee Challans</h3>
          <p class="text-xs text-[#54524B]">Printable deposit slips for Habib Bank Limited (HBL) with Bank, School, and Student copies.</p>
        </div>
        <button onclick="document.getElementById('modal-issue-challan').classList.remove('hidden')" 
          class="px-4 py-2 bg-[#C6892E] hover:bg-[#b27824] text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5 shadow-sm">
          <span class="material-symbols-outlined text-[16px]">receipt_long</span>
          <span>Generate New Challan</span>
        </button>
      </div>

      <div class="overflow-x-auto border border-[#e3e2df] rounded-xl">
        <table class="w-full text-left text-xs">
          <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
            <tr>
              <th class="py-3.5 px-6">Challan #</th>
              <th class="py-3.5 px-6">Issue Date</th>
              <th class="py-3.5 px-6">Due Date</th>
              <th class="py-3.5 px-6 text-right">Payable Amount</th>
              <th class="py-3.5 px-6 text-center">Status</th>
              <th class="py-3.5 px-6 text-center">Direct Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[#e3e2df]">
            <?php if (empty($profile['challans'])): ?>
              <tr><td colspan="6" class="py-8 text-center text-gray-500">No challans issued for this student. Click "Generate New Challan" above to generate one dynamically.</td></tr>
            <?php else: foreach ($profile['challans'] as $ch): 
              $isPaid = ($ch['status'] === 'Paid');
              $stBadge = $isPaid ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800';
            ?>
            <tr class="hover:bg-gray-50/80 transition-colors">
              <td class="py-4 px-6 font-mono font-bold text-[#002452]"><?= htmlspecialchars($ch['challan_no']) ?></td>
              <td class="py-4 px-6 text-gray-600"><?= date('M d, Y', strtotime($ch['issue_date'])) ?></td>
              <td class="py-4 px-6 font-semibold text-red-700"><?= date('M d, Y', strtotime($ch['due_date'])) ?></td>
              <td class="py-4 px-6 text-right font-bold text-[#1b1c1a]">Rs. <?= number_format($ch['requested_amount'] ?? $ch['total_payable'] ?? 0) ?></td>
              <td class="py-4 px-6 text-center">
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold <?= $stBadge ?>"><?= htmlspecialchars($ch['status']) ?></span>
              </td>
              <td class="py-4 px-6 text-center">
                <div class="flex items-center justify-center gap-2">
                  <button type="button" onclick="previewChallan(<?= htmlspecialchars(json_encode($ch)) ?>)" 
                    class="px-3 py-1.5 bg-[#002452] hover:bg-[#001a3d] text-white rounded-lg text-xs font-semibold flex items-center gap-1 shadow-xs transition-colors" title="Print 3-Part Bank Challan">
                    <span class="material-symbols-outlined text-[15px]">print</span>
                    <span>Print Challan</span>
                  </button>
                  <?php if (!$isPaid): ?>
                    <a href="payments.php?challan_id=<?= $ch['challan_id'] ?>" 
                      class="px-2.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold flex items-center gap-1 shadow-xs transition-colors" title="Record Bank Payment">
                      <span class="material-symbols-outlined text-[15px]">payments</span>
                      <span>Pay</span>
                    </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
<?php endif; ?>

<!-- Students Registry Table View -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden mb-12">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex items-center justify-between bg-[#fbfaf8]">
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Official Student Registry</h3>
    <span class="text-xs text-gray-500 font-semibold"><?= count($students) ?> Students Recorded</span>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-gray-50 text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
        <tr>
          <th class="py-3.5 px-6">Reg # (Roll #)</th>
          <th class="py-3.5 px-6">Student Name</th>
          <th class="py-3.5 px-4">Class &amp; Section</th>
          <th class="py-3.5 px-4">Guardian / Father</th>
          <th class="py-3.5 px-4">Contact Phone</th>
          <th class="py-3.5 px-4 text-center">Status</th>
          <th class="py-3.5 px-6 text-center">360 View</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
        <?php if (empty($students)): ?>
          <tr><td colspan="7" class="py-8 text-center text-[#747780]">No student records found matching filter criteria.</td></tr>
        <?php else: foreach ($students as $st): 
          $fullName = trim($st['first_name'] . ' ' . $st['last_name']);
          $initial = strtoupper(substr($st['first_name'], 0, 1));
          $isRowSelected = ($profile && $profile['student_id'] == $st['student_id']);
        ?>
        <tr class="hover:bg-gray-50/80 transition-colors <?= $isRowSelected ? 'bg-amber-50/60 font-semibold' : '' ?>">
          <td class="py-4 px-6 font-mono font-bold text-[#002452]"><?= htmlspecialchars($st['registration_no']) ?></td>
          <td class="py-4 px-6 font-medium">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-[#002452]/10 text-[#002452] flex items-center justify-center font-bold text-xs shrink-0">
                <?= $initial ?>
              </div>
              <div>
                <span class="font-semibold text-sm"><?= htmlspecialchars($fullName) ?></span>
                <div class="text-[11px] text-[#747780]"><?= htmlspecialchars($st['gender'] ?: 'Male') ?> &bull; <?= htmlspecialchars($st['blood_group'] ?: '—') ?></div>
              </div>
            </div>
          </td>
          <td class="py-4 px-4 text-xs font-semibold text-[#002452]">
            <?= htmlspecialchars($st['class_name'] ?: 'Not Enrolled') ?>
            <?php if (!empty($st['section_name'])): ?>
              <span class="text-[#747780] font-normal">(<?= htmlspecialchars($st['section_name']) ?>)</span>
            <?php endif; ?>
          </td>
          <td class="py-4 px-4 text-xs text-[#44474f]"><?= htmlspecialchars($st['parent_name'] ?: 'Guardian') ?></td>
          <td class="py-4 px-4 text-xs text-[#747780] font-mono"><?= htmlspecialchars($st['phone'] ?: '—') ?></td>
          <td class="py-4 px-4 text-center">
            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10.5px] font-semibold bg-emerald-100 text-emerald-800">
              <?= htmlspecialchars($st['status']) ?>
            </span>
          </td>
          <td class="py-4 px-6 text-center">
            <a href="students.php?student_id=<?= $st['student_id'] ?><?= $search ? "&q=" . urlencode($search) : '' ?>" 
              class="px-3.5 py-1.5 bg-[#002452] hover:bg-[#001a3d] text-white rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-xs transition-colors">
              <span>View Profile</span>
              <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL: ISSUE NEW CHALLAN FOR STUDENT -->
<?php if ($profile): ?>
<div id="modal-issue-challan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Generate Dynamic Fee Challan</h3>
      <button onclick="document.getElementById('modal-issue-challan').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="space-y-4">
      <input type="hidden" name="generatechallan" value="1"/>
      <input type="hidden" name="student_id" value="<?= $profile['student_id'] ?>"/>
      <input type="hidden" name="enrollment_id" value="<?= $profile['current_enrollment']['enrollment_id'] ?? 0 ?>"/>
      <input type="hidden" name="return_to" value="students.php"/>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Target Student</label>
        <div class="p-2.5 bg-gray-50 rounded-lg border border-gray-200 text-xs font-semibold text-[#002452]">
          [<?= htmlspecialchars($profile['registration_no']) ?>] <?= htmlspecialchars($fullName) ?>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Due Date <span class="text-red-500">*</span></label>
        <input type="date" name="due_date" value="<?= date('Y-m-d', strtotime('+15 days')) ?>" required 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
      </div>

      <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 text-amber-900 text-xs">
        <p class="font-semibold mb-1">Notice on Dynamic Challan Calculation:</p>
        <p>This challan will aggregate all currently outstanding fee charges, tuition balances, and pending fines for this student automatically.</p>
      </div>

      <div class="flex justify-end gap-2 pt-2 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-issue-challan').classList.add('hidden')" 
          class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-xs font-bold rounded-lg shadow-sm">Issue Challan Slip</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: UPLOAD STUDENT DOCUMENT -->
<div id="modal-upload-doc" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Attach Student Document</h3>
      <button onclick="document.getElementById('modal-upload-doc').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" enctype="multipart/form-data" class="space-y-4">
      <input type="hidden" name="uploadstudentdoc" value="1"/>
      <input type="hidden" name="student_id" value="<?= $profile['student_id'] ?>"/>
      <input type="hidden" name="return_to" value="students.php?student_id=<?= $profile['student_id'] ?>"/>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Document Category <span class="text-red-500">*</span></label>
        <select name="document_type" required class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 bg-white outline-none">
          <option value="Student Photograph">Student Photograph (Passport Size)</option>
          <option value="Father CNIC Front">Father / Guardian CNIC (Front Side)</option>
          <option value="Father CNIC Back">Father / Guardian CNIC (Back Side)</option>
          <option value="Previous Grade Result Certificate">Previous Grade Result Certificate / DMC</option>
          <option value="Matriculation Certificate">Matriculation SSC Certificate</option>
          <option value="Character Certificate">School Leaving / Character Certificate</option>
          <option value="Medical Certificate">Medical Fitness Certificate</option>
          <option value="Other Institutional Document">Other Institutional Document</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Select File (JPG, PNG, PDF) <span class="text-red-500">*</span></label>
        <input type="file" name="document_file" required accept=".jpg,.jpeg,.png,.pdf,.webp" 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452] file:mr-3 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:bg-[#002452] file:text-white"/>
      </div>

      <div class="flex justify-end gap-2 pt-2 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-upload-doc').classList.add('hidden')" 
          class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-xs font-bold rounded-lg shadow-sm">Upload &amp; Attach</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- MODAL: PRINTABLE 3-PART BANK CHALLAN (HBL TEMPLATE) -->
<div id="modal-print-challan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl p-6 relative max-h-[95vh] overflow-y-auto">
    <div class="flex items-center justify-between pb-4 border-b border-[#e3e2df] mb-4">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-2xl text-[#002452]">print</span>
        <h3 class="font-serif text-xl font-bold text-[#002452]">Printable 3-Part Bank Challan</h3>
      </div>
      <div class="flex items-center gap-3">
        <button onclick="window.print()" class="px-4 py-2 bg-[#002452] text-white font-semibold text-xs rounded-lg hover:bg-[#001a3d] flex items-center gap-1.5 shadow-sm">
          <span class="material-symbols-outlined text-[16px]">print</span> Print Challan
        </button>
        <button onclick="document.getElementById('modal-print-challan').classList.add('hidden')" class="p-2 text-[#747780] hover:text-[#1b1c1a]">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
    </div>

    <!-- Printable Content Container (3 Copies Side by Side) -->
    <div id="challan-printable-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 border border-dashed border-gray-300 p-4 bg-white text-[11px] leading-tight">
      <?php foreach (['BANK COPY', 'SCHOOL COPY', 'STUDENT COPY'] as $copyTitle): ?>
        <div class="border border-gray-400 p-3 flex flex-col justify-between bg-white rounded">
          <div>
            <!-- Header -->
            <div class="text-center pb-2 border-b border-gray-300 mb-2">
              <p class="font-bold uppercase tracking-wider text-[10px] text-gray-500"><?= $copyTitle ?></p>
              <h4 class="font-serif font-bold text-xs text-[#002452] mt-0.5">SOS Hermann Gmeiner School &amp; College</h4>
              <p class="text-[9.5px] text-gray-600">Dhodial, Mansehra, KP</p>
              <div class="bg-gray-100 py-1 px-2 rounded mt-1.5 font-mono text-[9.5px]">
                <strong>HBL Dhodial Branch (0142)</strong><br>
                A/C: 0142-79012345-03
              </div>
            </div>

            <!-- Details -->
            <div class="space-y-1 py-1 text-[10.5px]">
              <div class="flex justify-between"><span class="text-gray-600">Challan No:</span> <strong class="font-mono text-[#002452] preview-ch-no">CH-2026-0000</strong></div>
              <div class="flex justify-between"><span class="text-gray-600">Issue Date:</span> <span class="preview-issue-date">2026-05-01</span></div>
              <div class="flex justify-between"><span class="text-gray-600">Due Date:</span> <strong class="text-red-700 preview-due-date">2026-05-15</strong></div>
              <div class="border-t border-gray-200 my-1"></div>
              <div class="flex justify-between"><span class="text-gray-600">Reg No:</span> <span class="font-mono preview-reg-no">SOS-2026-0000</span></div>
              <div class="flex justify-between"><span class="text-gray-600">Student:</span> <strong class="preview-student-name">Student Name</strong></div>
              <div class="flex justify-between"><span class="text-gray-600">Class:</span> <span class="preview-class-name">Class Level</span></div>
            </div>

            <!-- Breakdown Table -->
            <table class="w-full mt-2 border border-gray-300 text-[10px]">
              <thead class="bg-gray-100 font-bold border-b border-gray-300">
                <tr><th class="p-1 text-left">Particulars</th><th class="p-1 text-right">Amount (PKR)</th></tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr><td class="p-1 text-gray-700">Tuition &amp; Educational Charges</td><td class="p-1 text-right font-mono preview-fee-base">5,000</td></tr>
                <tr><td class="p-1 text-gray-700">Fines / Surcharges</td><td class="p-1 text-right font-mono preview-fee-fine">0</td></tr>
                <tr><td class="p-1 text-emerald-700">Scholarship / Concession</td><td class="p-1 text-right font-mono text-emerald-700 preview-fee-concession">0</td></tr>
              </tbody>
              <tfoot class="border-t-2 border-gray-400 bg-gray-50 font-bold">
                <tr><td class="p-1.5 text-left text-[#002452]">Net Payable:</td><td class="p-1.5 text-right font-mono text-xs text-[#002452] preview-fee-total">Rs. 5,000</td></tr>
              </tfoot>
            </table>
          </div>

          <!-- Signatures -->
          <div class="mt-6 pt-4 border-t border-gray-300 grid grid-cols-2 text-center text-[9px] text-gray-600">
            <div>
              <div class="border-b border-gray-400 w-3/4 mx-auto mb-1"></div>
              <span>Depositor Sign</span>
            </div>
            <div>
              <div class="border-b border-gray-400 w-3/4 mx-auto mb-1"></div>
              <span>Bank Officer / Cashier</span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
function switchTab(tabId) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('border-[#002452]', 'text-[#002452]');
    btn.classList.add('border-transparent');
  });
  
  const target = document.getElementById(tabId);
  if (target) target.classList.remove('hidden');

  const activeBtn = document.getElementById('btn-' + tabId);
  if (activeBtn) {
    activeBtn.classList.add('border-[#002452]', 'text-[#002452]');
    activeBtn.classList.remove('border-transparent');
  }
}

function previewChallan(ch) {
  const modal = document.getElementById('modal-print-challan');
  const studentName = (ch.first_name || '<?= htmlspecialchars(addslashes($fullName ?? "")) ?>') + ' ' + (ch.last_name || '');
  const className = (ch.class_name || '<?= htmlspecialchars(addslashes($profile["current_enrollment"]["class_name"] ?? "Class")) ?>') + (ch.section_name ? ' - ' + ch.section_name : '');
  const regNo = ch.registration_no || '<?= htmlspecialchars(addslashes($profile["registration_no"] ?? "")) ?>';
  const payable = Number(ch.requested_amount || ch.total_payable || 0);

  modal.querySelectorAll('.preview-ch-no').forEach(el => el.textContent = ch.challan_no || 'N/A');
  modal.querySelectorAll('.preview-issue-date').forEach(el => el.textContent = ch.issue_date || '<?= date("Y-m-d") ?>');
  modal.querySelectorAll('.preview-due-date').forEach(el => el.textContent = ch.due_date || '<?= date("Y-m-d", strtotime("+15 days")) ?>');
  modal.querySelectorAll('.preview-reg-no').forEach(el => el.textContent = regNo);
  modal.querySelectorAll('.preview-student-name').forEach(el => el.textContent = studentName.trim());
  modal.querySelectorAll('.preview-class-name').forEach(el => el.textContent = className);
  
  modal.querySelectorAll('.preview-fee-base').forEach(el => el.textContent = payable.toLocaleString());
  modal.querySelectorAll('.preview-fee-fine').forEach(el => el.textContent = '0');
  modal.querySelectorAll('.preview-fee-concession').forEach(el => el.textContent = '0');
  modal.querySelectorAll('.preview-fee-total').forEach(el => el.textContent = 'Rs. ' + payable.toLocaleString());

  modal.classList.remove('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
