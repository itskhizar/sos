<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Entry Tests Management — dashboard/entry-tests.php
 * Module 4: Dynamic Entry Test Scheduling, Candidate Assignment & Result Processing
 */
$dashTitle   = 'Entry Tests & Assessment';
$currentDash = 'entry-tests';
include 'includes/header.php';

$sessions = $database->getAllSessions();
$classes = $database->getAllClasses();
$tests = $database->getAllEntryTests();

$selectedTestId = isset($_GET['test_id']) ? intval($_GET['test_id']) : ($tests[0]['test_id'] ?? 0);
$selectedTest = $selectedTestId ? $database->getEntryTest($selectedTestId) : null;
$candidates = $selectedTestId ? $database->getTestCandidates($selectedTestId) : [];

// If viewing a test, also find eligible applicants who can be assigned
$eligibleApplicants = [];
if ($selectedTest) {
    $eligibleApplicants = $database->getEligibleApplicantsForTest($selectedTest['session_id'], $selectedTest['class_id']);
    // Filter out already assigned
    $assignedAppIds = array_column($candidates, 'application_id');
    $eligibleApplicants = array_filter($eligibleApplicants, function($a) use ($assignedAppIds) {
        return !in_array($a['application_id'], $assignedAppIds);
    });
}

// Compute Stats
$totalTests = count($tests);
$totalCandidates = array_sum(array_column($tests, 'total_candidates'));
$totalPassed = array_sum(array_column($tests, 'passed_count'));
$totalFailed = array_sum(array_column($tests, 'failed_count'));
?>

<!-- Page Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Entry Tests &amp; Candidate Assessment</h2>
    <p class="text-sm text-[#44474f] mt-1">Schedule tests by session and grade, assign eligible applicants, evaluate marks, and convert qualifiers to students.</p>
  </div>
  <button onclick="document.getElementById('modal-schedule-test').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">add</span>
    Schedule New Test
  </button>
</div>

<!-- Feedback Messages -->
<?php if (isset($_GET['scheduled'])): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
    <span>Entry test slot successfully created and opened for candidate scheduling!</span>
  </div>
<?php elseif (isset($_GET['candidate_assigned'])): ?>
  <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg flex items-center gap-2">
    <span class="material-symbols-outlined text-blue-600 text-[20px]">how_to_reg</span>
    <span>Candidate successfully assigned with test roll number and venue slip details!</span>
  </div>
<?php elseif (isset($_GET['result_saved'])): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">verified</span>
    <span>Candidate marks recorded! Evaluation result status updated in the admission pipeline.</span>
  </div>
<?php endif; ?>

<!-- Stats Overview -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-[#002452]/10 text-[#002452]">
      <span class="material-symbols-outlined text-[24px]">quiz</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $totalTests ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Scheduled Test Slots</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-amber-50 text-[#885200]">
      <span class="material-symbols-outlined text-[24px]">how_to_reg</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $totalCandidates ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Assigned Candidates</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-emerald-50 text-[#00522d]">
      <span class="material-symbols-outlined text-[24px]">check_circle</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $totalPassed ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Passed / Eligible</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-red-50 text-red-700">
      <span class="material-symbols-outlined text-[24px]">cancel</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $totalFailed ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Unsuccessful / Retest</p>
    </div>
  </div>
</div>

<!-- Scheduled Tests List -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mb-8">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex items-center justify-between bg-[#fbfaf8]">
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Active Entry Test Sessions</h3>
    <span class="text-xs text-[#54524B]"><?= count($tests) ?> Total Slots Configured</span>
  </div>
  
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[11px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-6 py-3.5 text-left font-semibold">Test Title &amp; Subject</th>
          <th class="px-4 py-3.5 text-left font-semibold">Session &amp; Grade</th>
          <th class="px-4 py-3.5 text-left font-semibold">Date &amp; Timing</th>
          <th class="px-4 py-3.5 text-left font-semibold">Venue</th>
          <th class="px-4 py-3.5 text-center font-semibold">Marks (Pass / Total)</th>
          <th class="px-4 py-3.5 text-center font-semibold">Candidates</th>
          <th class="px-4 py-3.5 text-center font-semibold">Status</th>
          <th class="px-4 py-3.5 text-center font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php if (empty($tests)): ?>
          <tr><td colspan="8" class="px-6 py-8 text-center text-[#747780]">No entry tests currently scheduled. Click 'Schedule New Test' to configure a testing slot.</td></tr>
        <?php else: foreach ($tests as $t): 
          $isSelected = ($t['test_id'] == $selectedTestId);
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors <?= $isSelected ? 'bg-amber-50/40' : '' ?>">
          <td class="px-6 py-3.5 font-semibold text-[#1b1c1a]">
            <div><?= htmlspecialchars($t['test_name']) ?></div>
            <span class="text-xs text-[#747780] font-normal"><?= htmlspecialchars($t['subject']) ?></span>
          </td>
          <td class="px-4 py-3.5 text-xs text-[#44474f]">
            <span class="font-semibold text-[#002452]"><?= htmlspecialchars($t['class_name'] ?: 'Grade') ?></span>
            <div class="text-[#747780]"><?= htmlspecialchars($t['session_name'] ?: 'Current') ?></div>
          </td>
          <td class="px-4 py-3.5 text-xs text-[#44474f]">
            <div class="font-medium"><?= date('d M Y', strtotime($t['test_date'])) ?></div>
            <div class="text-[#747780]"><?= date('h:i A', strtotime($t['start_time'])) ?> – <?= date('h:i A', strtotime($t['end_time'])) ?></div>
          </td>
          <td class="px-4 py-3.5 text-xs text-[#44474f]"><?= htmlspecialchars($t['venue']) ?></td>
          <td class="px-4 py-3.5 text-center text-xs font-semibold text-[#1b1c1a]">
            <?= intval($t['passing_marks']) ?> / <?= intval($t['total_marks']) ?>
          </td>
          <td class="px-4 py-3.5 text-center text-xs">
            <span class="font-bold text-[#002452]"><?= intval($t['total_candidates']) ?></span>
            <div class="text-[10.5px] text-emerald-700"><?= intval($t['passed_count']) ?> pass</div>
          </td>
          <td class="px-4 py-3.5 text-center">
            <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#002452]/10 text-[#002452]">
              <?= htmlspecialchars($t['status']) ?>
            </span>
          </td>
          <td class="px-4 py-3.5 text-center">
            <a href="entry-tests.php?test_id=<?= $t['test_id'] ?>" 
              class="px-3 py-1.5 bg-[#002452] hover:bg-[#001a3d] text-white rounded-md text-xs font-semibold transition-colors inline-flex items-center gap-1">
              <span>Manage Candidates</span>
              <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($selectedTest): ?>
<!-- Candidate Management Section for Selected Test -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mb-8">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#fbfaf8]">
    <div>
      <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">
        Candidate Roster: <?= htmlspecialchars($selectedTest['test_name']) ?> (<?= htmlspecialchars($selectedTest['class_name']) ?>)
      </h3>
      <p class="text-xs text-[#54524B] mt-0.5">
        Venue: <?= htmlspecialchars($selectedTest['venue']) ?> &nbsp;|&nbsp; Date: <?= date('d M Y', strtotime($selectedTest['test_date'])) ?> &nbsp;|&nbsp; Pass Mark: <?= intval($selectedTest['passing_marks']) ?>/<?= intval($selectedTest['total_marks']) ?>
      </p>
    </div>
    
    <?php if (!empty($eligibleApplicants)): ?>
      <button onclick="document.getElementById('modal-assign-candidate').classList.remove('hidden')" 
        class="px-3.5 py-2 bg-[#C6892E] hover:bg-[#b27824] text-white text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5 shadow-xs">
        <span class="material-symbols-outlined text-[16px]">group_add</span>
        <span>Assign Eligible Applicants (<?= count($eligibleApplicants) ?> available)</span>
      </button>
    <?php endif; ?>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[11px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-6 py-3.5 text-left font-semibold">Candidate Name &amp; Application</th>
          <th class="px-4 py-3.5 text-left font-semibold">Roll Number</th>
          <th class="px-4 py-3.5 text-left font-semibold">Guardian / Contact</th>
          <th class="px-4 py-3.5 text-center font-semibold">Marks Obtained</th>
          <th class="px-4 py-3.5 text-center font-semibold">Percentage</th>
          <th class="px-4 py-3.5 text-center font-semibold">Status</th>
          <th class="px-6 py-3.5 text-center font-semibold">Admission Conversion</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php if (empty($candidates)): ?>
          <tr><td colspan="7" class="px-6 py-8 text-center text-[#747780]">No applicants assigned to this test yet. Click 'Assign Eligible Applicants' to schedule students.</td></tr>
        <?php else: foreach ($candidates as $c): 
          $rCls = ($c['result_status'] === 'Pass') ? 'bg-emerald-100 text-emerald-800' : (($c['result_status'] === 'Fail') ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700');
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-6 py-3.5 font-semibold text-[#1b1c1a]">
            <div><?= htmlspecialchars($c['student_name']) ?></div>
            <span class="text-xs text-[#747780] font-mono"><?= htmlspecialchars($c['application_no']) ?></span>
          </td>
          <td class="px-4 py-3.5 font-mono text-xs text-[#002452] font-bold"><?= htmlspecialchars($c['roll_number']) ?></td>
          <td class="px-4 py-3.5 text-xs text-[#44474f]">
            <div><?= htmlspecialchars($c['parent_name'] ?: 'Guardian') ?></div>
            <div class="text-[#747780]"><?= htmlspecialchars($c['phone'] ?: '—') ?></div>
          </td>
          
          <!-- Score Input Form -->
          <td class="px-4 py-3.5 text-center">
            <form action="process.php" method="post" class="flex items-center justify-center gap-2">
              <input type="hidden" name="candidate_id" value="<?= $c['candidate_id'] ?>"/>
              <input type="hidden" name="test_id" value="<?= $selectedTest['test_id'] ?>"/>
              <input type="number" step="0.5" max="<?= $selectedTest['total_marks'] ?>" min="0" 
                name="marks_obtained" value="<?= $c['marks_obtained'] !== null ? $c['marks_obtained'] : '' ?>" 
                placeholder="Marks" required 
                class="w-20 px-2 py-1 text-center font-semibold text-sm border border-[#c4c6d0] rounded-md focus:border-[#002452] outline-none"/>
              <button type="submit" name="recordtestresult" class="p-1 bg-[#002452] hover:bg-[#001a3d] text-white rounded-md" title="Save Marks">
                <span class="material-symbols-outlined text-[16px]">check</span>
              </button>
            </form>
          </td>

          <td class="px-4 py-3.5 text-center text-xs font-semibold text-[#1b1c1a]">
            <?= $c['percentage'] !== null ? ($c['percentage'] . '%') : '—' ?>
          </td>

          <td class="px-4 py-3.5 text-center">
            <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold <?= $rCls ?>">
              <?= htmlspecialchars($c['result_status']) ?>
            </span>
          </td>

          <!-- Convert to Student Action Button -->
          <td class="px-6 py-3.5 text-center">
            <?php if ($c['result_status'] === 'Pass'): ?>
              <?php if ($c['app_status'] === 'Converted'): ?>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                  <span class="material-symbols-outlined text-[15px]">verified</span> Registered Student
                </span>
              <?php else: ?>
                <form action="process.php" method="post" onsubmit="return confirm('Convert this applicant into an officially registered SOS Student?')">
                  <input type="hidden" name="application_id" value="<?= $c['application_id'] ?>"/>
                  <button type="submit" name="converttostudent" 
                    class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-md shadow-xs transition-colors flex items-center gap-1 mx-auto">
                    <span class="material-symbols-outlined text-[15px]">person_check</span>
                    <span>Issue Registration No</span>
                  </button>
                </form>
              <?php endif; ?>
            <?php else: ?>
              <span class="text-xs text-[#747780]">Pending Clearance</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<!-- Modal: Schedule New Test -->
<div id="modal-schedule-test" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#e3e2df]">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Schedule Entry Assessment Test</h3>
      <button onclick="document.getElementById('modal-schedule-test').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="flex flex-col gap-4">
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Test Title</label>
        <input type="text" name="test_name" placeholder="e.g. Admission Test Round 1 (Class VI)" required 
          class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-[#002452]"/>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Academic Session</label>
          <select name="session_id" required class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 bg-white">
            <?php foreach ($sessions as $s): ?>
              <option value="<?= $s['session_id'] ?>" <?= $s['is_active'] ? 'selected' : '' ?>><?= htmlspecialchars($s['session_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Target Class / Grade</label>
          <select name="class_id" required class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 bg-white">
            <?php foreach ($classes as $cl): ?>
              <option value="<?= $cl['class_id'] ?>"><?= htmlspecialchars($cl['class_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Subject(s)</label>
          <input type="text" name="subject" placeholder="English, Maths, Science" required 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Venue / Hall</label>
          <input type="text" name="venue" placeholder="Main Exam Hall - Block A" required 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-[#002452]"/>
        </div>
      </div>
      <div class="grid grid-cols-3 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Test Date</label>
          <input type="date" name="test_date" required value="<?= date('Y-m-d', strtotime('+3 days')) ?>" 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Start Time</label>
          <input type="time" name="start_time" required value="09:00" 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">End Time</label>
          <input type="time" name="end_time" required value="11:30" 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-[#002452]"/>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Total Marks</label>
          <input type="number" name="total_marks" required value="100" 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Passing Threshold</label>
          <input type="number" name="passing_marks" required value="50" 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-[#002452]"/>
        </div>
      </div>
      <div class="flex justify-end gap-2 pt-3 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-schedule-test').classList.add('hidden')" 
          class="px-4 py-2 border border-[#e3e2df] text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" name="scheduletest" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-sm font-semibold rounded-lg shadow-sm">Save Test Slot</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Assign Candidate -->
<?php if ($selectedTest && !empty($eligibleApplicants)): ?>
<div id="modal-assign-candidate" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#e3e2df]">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Assign Applicant to Test Slot</h3>
      <button onclick="document.getElementById('modal-assign-candidate').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="flex flex-col gap-4">
      <input type="hidden" name="test_id" value="<?= $selectedTest['test_id'] ?>"/>
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Select Eligible Applicant</label>
        <select name="application_id" required class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 bg-white">
          <?php foreach ($eligibleApplicants as $ea): ?>
            <option value="<?= $ea['application_id'] ?>">
              <?= htmlspecialchars($ea['student_name']) ?> (<?= htmlspecialchars($ea['application_no']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Assigned Roll Number</label>
        <input type="text" name="roll_number" placeholder="Leave blank to auto-generate" 
          class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 outline-none focus:ring-1 focus:ring-[#002452]"/>
      </div>
      <div class="flex justify-end gap-2 pt-3 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-assign-candidate').classList.add('hidden')" 
          class="px-4 py-2 border border-[#e3e2df] text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" name="assigntestcandidate" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-sm font-semibold rounded-lg shadow-sm">Assign Applicant</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
