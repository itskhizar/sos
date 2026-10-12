<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Careers & Recruitment Management — dashboard/careers.php
 * Module: Manage Faculty & Staff Vacancies, Job Applications & Candidate Pipeline
 */
$dashTitle   = 'Careers & Job Postings';
$currentDash = 'careers';
include 'includes/header.php';

$jobs = $database->getAllJobs();
$applications = $database->getJobApplications();

// Calculate Stats
$totalJobs = count($jobs);
$openJobs = count(array_filter($jobs, fn($j) => $j['status'] === 'Open' && strtotime($j['last_date_to_apply']) >= strtotime('today')));
$totalApplicants = count($applications);
$shortlisted = count(array_filter($applications, fn($a) => in_array($a['status'], ['Shortlisted', 'Interviewed', 'Selected'])));
?>

<!-- Page Header & Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Careers &amp; Job Postings</h1>
    <p class="text-xs md:text-sm text-[#54524B] mt-0.5">Publish faculty and administrative vacancies, review online applicant resumes, and manage recruitment pipeline.</p>
  </div>
  <div class="flex gap-2.5">
    <button onclick="document.getElementById('modal-add-job').classList.remove('hidden')" 
      class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#002452] text-white font-semibold text-xs md:text-sm rounded-lg hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
      <span class="material-symbols-outlined text-[18px]">add</span>
      <span>Post New Vacancy</span>
    </button>
  </div>
</div>

<!-- Alerts / Feedback -->
<?php if (isset($_GET['added']) && $_GET['added'] === 'true'): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
    <span class="font-semibold">New job vacancy posted successfully and is now active on the public website!</span>
  </div>
<?php elseif (isset($_GET['updated']) && $_GET['updated'] === 'true'): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
    <span class="font-semibold">Job vacancy details updated successfully.</span>
  </div>
<?php elseif (isset($_GET['deleted']) && $_GET['deleted'] === 'true'): ?>
  <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-blue-600 text-[20px]">delete</span>
    <span class="font-semibold">Job vacancy removed from listings.</span>
  </div>
<?php elseif (isset($_GET['app_status_updated'])): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">how_to_reg</span>
    <span class="font-semibold">Candidate recruitment status updated in pipeline.</span>
  </div>
<?php endif; ?>

<!-- Stats Overview Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-[#002452]/10 text-[#002452]">
      <span class="material-symbols-outlined text-[24px]">work</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $totalJobs ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Total Positions Listed</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-emerald-50 text-[#00522d]">
      <span class="material-symbols-outlined text-[24px]">campaign</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $openJobs ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Currently Open on Web</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-amber-50 text-[#885200]">
      <span class="material-symbols-outlined text-[24px]">description</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $totalApplicants ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Applications Received</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-blue-50 text-blue-800">
      <span class="material-symbols-outlined text-[24px]">badge</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $shortlisted ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Shortlisted / Pipeline</p>
    </div>
  </div>
</div>

<!-- SECTION 1: Active Job Openings Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden mb-8">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex items-center justify-between bg-[#fbfaf8]">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-[#002452] text-[20px]">list_alt</span>
      <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Active Career Vacancies</h3>
    </div>
    <span class="text-xs text-gray-500 font-semibold"><?= count($jobs) ?> Vacancies Configured</span>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-gray-50 text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
        <tr>
          <th class="py-3.5 px-6">Job Title &amp; Department</th>
          <th class="py-3.5 px-4">Employment Type</th>
          <th class="py-3.5 px-4">Qualification / Experience</th>
          <th class="py-3.5 px-4 text-center">Vacancies</th>
          <th class="py-3.5 px-4">Last Date to Apply</th>
          <th class="py-3.5 px-4 text-center">Applicants</th>
          <th class="py-3.5 px-4 text-center">Status</th>
          <th class="py-3.5 px-6 text-center">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
        <?php if (empty($jobs)): ?>
          <tr><td colspan="8" class="py-8 text-center text-[#747780]">No job openings found. Click "Post New Vacancy" above to post an employment opening.</td></tr>
        <?php else: foreach ($jobs as $job): 
          $isExpired = strtotime($job['last_date_to_apply']) < strtotime('today');
          $isOpen = ($job['status'] === 'Open' && !$isExpired);
          $stClass = $isOpen ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700';
          $stLabel = $isOpen ? 'Active / Open' : ($isExpired ? 'Expired' : 'Closed');
        ?>
        <tr class="hover:bg-gray-50/80 transition-colors">
          <td class="py-4 px-6 font-semibold">
            <span class="text-sm font-bold text-[#002452] block"><?= htmlspecialchars($job['job_title']) ?></span>
            <span class="text-[11px] text-[#747780]"><?= htmlspecialchars($job['department']) ?></span>
          </td>
          <td class="py-4 px-4 font-medium text-gray-700">
            <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 border border-gray-200 text-[10.5px]">
              <?= htmlspecialchars($job['employment_type']) ?>
            </span>
          </td>
          <td class="py-4 px-4 text-gray-600">
            <div class="font-medium text-[#1b1c1a]"><?= htmlspecialchars($job['qualification'] ?: 'As per rules') ?></div>
            <div class="text-[11px] text-gray-500"><?= htmlspecialchars($job['experience_required'] ?: 'Fresh / Experienced') ?></div>
          </td>
          <td class="py-4 px-4 text-center font-bold text-[#002452]">
            <?= intval($job['vacancies']) ?>
          </td>
          <td class="py-4 px-4 font-mono">
            <span class="<?= $isExpired ? 'text-red-700 font-bold' : 'text-gray-700' ?>">
              <?= date('d M Y', strtotime($job['last_date_to_apply'])) ?>
            </span>
          </td>
          <td class="py-4 px-4 text-center">
            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-[#002452]/10 text-[#002452]">
              <?= intval($job['applicant_count']) ?>
            </span>
          </td>
          <td class="py-4 px-4 text-center">
            <span class="inline-flex px-2 py-0.5 rounded-full text-[10.5px] font-bold <?= $stClass ?>">
              <?= $stLabel ?>
            </span>
          </td>
          <td class="py-4 px-6 text-center">
            <div class="flex items-center justify-center gap-2">
              <button onclick="editJob(<?= htmlspecialchars(json_encode($job)) ?>)" 
                class="p-1.5 text-[#002452] hover:bg-gray-100 rounded transition-colors" title="Edit Vacancy">
                <span class="material-symbols-outlined text-[17px]">edit</span>
              </button>
              <form action="process.php" method="post" onsubmit="return confirm('Delete this job vacancy permanently?')" class="inline">
                <input type="hidden" name="deletejob" value="1"/>
                <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>"/>
                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors" title="Delete">
                  <span class="material-symbols-outlined text-[17px]">delete</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- SECTION 2: Online Applicant Resumes & Pipeline -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden mb-12">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex items-center justify-between bg-[#fbfaf8]">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-[#C6892E] text-[20px]">folder_shared</span>
      <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Job Applications &amp; Resumes Received</h3>
    </div>
    <span class="text-xs text-gray-500 font-semibold"><?= count($applications) ?> Submissions</span>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-gray-50 text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
        <tr>
          <th class="py-3.5 px-6">Applicant Name</th>
          <th class="py-3.5 px-4">Applied Position</th>
          <th class="py-3.5 px-4">Contact Details</th>
          <th class="py-3.5 px-4">Qualification &amp; Experience</th>
          <th class="py-3.5 px-4 text-center">Resume / CV</th>
          <th class="py-3.5 px-4">Applied Date</th>
          <th class="py-3.5 px-6 text-center">Hiring Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
        <?php if (empty($applications)): ?>
          <tr><td colspan="7" class="py-8 text-center text-[#747780]">No job applications submitted yet.</td></tr>
        <?php else: foreach ($applications as $app): 
          $badge = match($app['status']) {
            'Selected' => 'bg-emerald-100 text-emerald-800',
            'Interviewed' => 'bg-blue-100 text-blue-800',
            'Shortlisted' => 'bg-purple-100 text-purple-800',
            'Rejected' => 'bg-red-100 text-red-800',
            default => 'bg-amber-100 text-amber-800'
          };
        ?>
        <tr class="hover:bg-gray-50/80 transition-colors">
          <td class="py-4 px-6 font-semibold">
            <span class="text-sm font-bold text-[#1b1c1a] block"><?= htmlspecialchars($app['applicant_name']) ?></span>
            <span class="text-[10.5px] text-gray-500 font-mono">App #<?= $app['application_id'] ?></span>
          </td>
          <td class="py-4 px-4">
            <span class="font-bold text-[#002452] block"><?= htmlspecialchars($app['job_title']) ?></span>
            <span class="text-[11px] text-gray-500"><?= htmlspecialchars($app['department']) ?></span>
          </td>
          <td class="py-4 px-4 text-gray-600">
            <div class="font-mono"><?= htmlspecialchars($app['phone'] ?: '—') ?></div>
            <div class="text-[11px] text-gray-500"><?= htmlspecialchars($app['email'] ?: '—') ?></div>
          </td>
          <td class="py-4 px-4 text-gray-700">
            <div class="font-medium"><?= htmlspecialchars($app['qualification'] ?: '—') ?></div>
            <div class="text-[11px] text-gray-500"><?= htmlspecialchars($app['experience'] ?: '—') ?></div>
          </td>
          <td class="py-4 px-4 text-center">
            <?php if (!empty($app['resume_path'])): ?>
              <a href="../<?= htmlspecialchars($app['resume_path']) ?>" target="_blank" 
                class="px-2.5 py-1 bg-[#002452]/10 hover:bg-[#002452]/20 text-[#002452] rounded-md font-bold text-[11px] inline-flex items-center gap-1 transition-colors">
                <span class="material-symbols-outlined text-[14px]">download</span>
                <span>View CV</span>
              </a>
            <?php else: ?>
              <span class="text-gray-400 italic">No File</span>
            <?php endif; ?>
          </td>
          <td class="py-4 px-4 font-mono text-gray-500">
            <?= date('d M Y, h:i A', strtotime($app['applied_at'])) ?>
          </td>
          <td class="py-4 px-6 text-center">
            <form action="process.php" method="post" class="flex items-center justify-center gap-1.5">
              <input type="hidden" name="updatejobappstatus" value="1"/>
              <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>"/>
              <select name="status" onchange="this.form.submit()" 
                class="text-xs font-semibold py-1 px-2 rounded-lg border border-gray-300 bg-white outline-none <?= $badge ?>">
                <option value="Received" <?= $app['status'] === 'Received' ? 'selected' : '' ?>>Received</option>
                <option value="Shortlisted" <?= $app['status'] === 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                <option value="Interviewed" <?= $app['status'] === 'Interviewed' ? 'selected' : '' ?>>Interviewed</option>
                <option value="Selected" <?= $app['status'] === 'Selected' ? 'selected' : '' ?>>Selected</option>
                <option value="Rejected" <?= $app['status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
              </select>
            </form>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL: POST NEW JOB OPENING -->
<div id="modal-add-job" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl p-6 relative max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Post Career Opportunity / Job Opening</h3>
      <button onclick="document.getElementById('modal-add-job').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="space-y-4">
      <input type="hidden" name="addjob" value="1"/>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Job Title <span class="text-red-500">*</span></label>
        <input type="text" name="job_title" placeholder="e.g. Senior Physics Lecturer / Primary English Teacher" required 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Department <span class="text-red-500">*</span></label>
          <select name="department" required class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 bg-white outline-none">
            <option value="Faculty - Sciences">Faculty - Sciences</option>
            <option value="Faculty - Humanities">Faculty - Humanities</option>
            <option value="Primary Wing">Primary Wing</option>
            <option value="Middle Wing">Middle Wing</option>
            <option value="College Section (FSc / FA)">College Section (FSc / FA)</option>
            <option value="Administration & Operations">Administration &amp; Operations</option>
            <option value="Finance & Accounts">Finance &amp; Accounts</option>
            <option value="IT & Laboratory">IT &amp; Laboratory</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Employment Type <span class="text-red-500">*</span></label>
          <select name="employment_type" required class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 bg-white outline-none">
            <option value="Full-time">Full-time</option>
            <option value="Part-time">Part-time</option>
            <option value="Contract">Contract</option>
            <option value="Visiting">Visiting</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Vacancies <span class="text-red-500">*</span></label>
          <input type="number" name="vacancies" value="1" min="1" required 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Experience Required</label>
          <input type="text" name="experience_required" placeholder="e.g. 2-3 Years" 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Deadline Date <span class="text-red-500">*</span></label>
          <input type="date" name="last_date_to_apply" value="<?= date('Y-m-d', strtotime('+30 days')) ?>" required 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Required Qualification <span class="text-red-500">*</span></label>
        <input type="text" name="qualification" placeholder="e.g. M.Sc / BS in Physics with B.Ed" required 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Job Overview / Description</label>
        <textarea name="description" rows="3" placeholder="Brief outline of duties and subject scope..." required 
          class="w-full text-xs border border-gray-300 rounded-lg p-2.5 outline-none focus:border-[#002452]"></textarea>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Key Requirements / Eligibility</label>
        <textarea name="requirements" rows="2" placeholder="Specific pedagogical experience, English fluency, etc." 
          class="w-full text-xs border border-gray-300 rounded-lg p-2.5 outline-none focus:border-[#002452]"></textarea>
      </div>

      <div class="flex justify-end gap-2 pt-3 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-add-job').classList.add('hidden')" 
          class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-xs font-bold rounded-lg shadow-sm">Publish Vacancy</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: EDIT JOB OPENING -->
<div id="modal-edit-job" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl p-6 relative max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Edit Career Vacancy</h3>
      <button onclick="document.getElementById('modal-edit-job').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="space-y-4">
      <input type="hidden" name="updatejob" value="1"/>
      <input type="hidden" name="job_id" id="edit-job-id" value=""/>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Job Title <span class="text-red-500">*</span></label>
        <input type="text" name="job_title" id="edit-job-title" required 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Department</label>
          <input type="text" name="department" id="edit-job-dept" required 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Status</label>
          <select name="status" id="edit-job-status" required class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 bg-white outline-none">
            <option value="Open">Open</option>
            <option value="Closed">Closed</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Vacancies</label>
          <input type="number" name="vacancies" id="edit-job-vacancies" min="1" required 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Experience</label>
          <input type="text" name="experience_required" id="edit-job-experience" 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Deadline Date</label>
          <input type="date" name="last_date_to_apply" id="edit-job-deadline" required 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Required Qualification</label>
        <input type="text" name="qualification" id="edit-job-qualification" required 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Job Description</label>
        <textarea name="description" id="edit-job-description" rows="3" required 
          class="w-full text-xs border border-gray-300 rounded-lg p-2.5 outline-none focus:border-[#002452]"></textarea>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Requirements</label>
        <textarea name="requirements" id="edit-job-requirements" rows="2" 
          class="w-full text-xs border border-gray-300 rounded-lg p-2.5 outline-none focus:border-[#002452]"></textarea>
      </div>

      <div class="flex justify-end gap-2 pt-3 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-edit-job').classList.add('hidden')" 
          class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-xs font-bold rounded-lg shadow-sm">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
function editJob(job) {
  document.getElementById('edit-job-id').value = job.job_id;
  document.getElementById('edit-job-title').value = job.job_title;
  document.getElementById('edit-job-dept').value = job.department;
  document.getElementById('edit-job-status').value = job.status;
  document.getElementById('edit-job-vacancies').value = job.vacancies;
  document.getElementById('edit-job-experience').value = job.experience_required || '';
  document.getElementById('edit-job-deadline').value = job.last_date_to_apply;
  document.getElementById('edit-job-qualification').value = job.qualification || '';
  document.getElementById('edit-job-description').value = job.description || '';
  document.getElementById('edit-job-requirements').value = job.requirements || '';

  document.getElementById('modal-edit-job').classList.remove('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
