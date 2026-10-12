<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Enquiries & Prospect CRM — dashboard/enquiries.php
 * Mandatory Architecture: Website/HR Enquiry → Follow-up → Admission Application → Student → Enrollment
 */
$dashTitle   = 'Enquiries & CRM';
$currentDash = 'enquiries';
include 'includes/header.php';

$enquiries = $database->getAllEnquiries();
$allClasses = $database->getAllClasses();
$allSessions = $database->getAllSessions();
$actSession = $database->getActiveSession();

$totalEnquiries = count($enquiries);
$needsFollowup = count(array_filter($enquiries, fn($e) => in_array($e['status'], ['New', 'Contacted', 'FollowUp', 'Follow-up Scheduled'])));
$convertedCount = count(array_filter($enquiries, fn($e) => $e['status'] === 'Converted' || !empty($e['converted_application_id'])));
$closedCount = count(array_filter($enquiries, fn($e) => in_array($e['status'], ['Closed', 'NotInterested'])));

$srcCounts = [
  'Website' => 0,
  'Walk-in' => 0,
  'Phone' => 0,
  'HR Outreach' => 0
];
foreach ($enquiries as $e) {
  $s = $e['source'] ?? 'Website';
  if (isset($srcCounts[$s])) $srcCounts[$s]++;
}
?>

<!-- Breadcrumb -->
<div class="crumb">
  <span>Admissions</span>
  <span>/</span>
  <span style="color:var(--navy); font-weight:500;">Enquiries &amp; Prospect CRM</span>
</div>

<!-- Page Title & Header Actions -->
<div class="page-head">
  <div>
    <h2>Enquiry &amp; Prospect Management</h2>
    <p>Track, qualify, and convert prospective student inquiries into official Admission Applications.</p>
  </div>
  <div class="qa">
    <button onclick="openNewEnquiryModal()" class="btn btn-primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 5v14M5 12h14"/>
      </svg>
      <span>Log New Enquiry</span>
    </button>
    <button onclick="alert('Exporting enquiry data for Session 2026-27 to Excel/CSV...')" class="btn btn-outline">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
      </svg>
      <span>Export Enquiries</span>
    </button>
  </div>
</div>

<!-- Architecture Rule Callout (Mandatory SRS Rule) -->
<div class="bg-[#F7F4EE] border border-[#EAE7DE] rounded-lg p-4 mb-6 shadow-xs">
  <div class="flex items-start gap-3">
    <div class="w-8 h-8 rounded-full bg-[#C6892E]/20 text-[#C6892E] flex items-center justify-center shrink-0 mt-0.5">
      <span class="material-symbols-outlined text-[18px]">account_tree</span>
    </div>
    <div class="text-xs text-[#54524B] leading-relaxed">
      <strong class="text-[#132644] font-semibold text-[13px] block mb-0.5">
        Core Architectural Rule Enforced:
      </strong>
      An enquiry is a contact/prospect record and <u>must never directly become a student</u>.
      When prospective parents become serious, HR converts the enquiry into an <strong>Admission Application</strong>. Only after application approval and entry test clearance is the <strong>Student Registered</strong>, followed by <strong>Class Enrollment</strong> and <strong>Fee Structure Assignment</strong>. The original enquiry remains preserved for reporting and audit history.
    </div>
  </div>
</div>

<!-- Pipeline Metrics (4 Cards) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="kpi !p-4">
    <div class="flex items-center justify-between mb-2">
      <span class="text-xs uppercase font-semibold text-[#8A867A]">Total Enquiries</span>
      <span class="p-1.5 rounded bg-blue-50 text-blue-700"><span class="material-symbols-outlined text-[18px]">inbox</span></span>
    </div>
    <div class="kpi-num !text-2xl"><?= $totalEnquiries ?></div>
    <p class="text-[11.5px] text-[#54524B] mt-1">Total recorded inquiries</p>
  </div>

  <div class="kpi !p-4">
    <div class="flex items-center justify-between mb-2">
      <span class="text-xs uppercase font-semibold text-[#8A867A]">Needs Follow-up</span>
      <span class="p-1.5 rounded bg-amber-50 text-amber-700"><span class="material-symbols-outlined text-[18px]">notification_important</span></span>
    </div>
    <div class="kpi-num !text-2xl text-amber-800"><?= $needsFollowup ?></div>
    <p class="text-[11.5px] text-amber-700 font-medium mt-1">Pending action / callback</p>
  </div>

  <div class="kpi !p-4">
    <div class="flex items-center justify-between mb-2">
      <span class="text-xs uppercase font-semibold text-[#8A867A]">Converted to Applications</span>
      <span class="p-1.5 rounded bg-emerald-50 text-emerald-700"><span class="material-symbols-outlined text-[18px]">how_to_reg</span></span>
    </div>
    <div class="kpi-num !text-2xl text-emerald-800"><?= $convertedCount ?></div>
    <p class="text-[11.5px] text-emerald-700 font-semibold mt-1">
      <?= $totalEnquiries > 0 ? round(($convertedCount / $totalEnquiries) * 100, 1) : 0 ?>% conversion rate
    </p>
  </div>

  <div class="kpi !p-4">
    <div class="flex items-center justify-between mb-2">
      <span class="text-xs uppercase font-semibold text-[#8A867A]">Closed / Not Interested</span>
      <span class="p-1.5 rounded bg-gray-50 text-gray-700"><span class="material-symbols-outlined text-[18px]">archive</span></span>
    </div>
    <div class="kpi-num !text-2xl text-gray-700"><?= $closedCount ?></div>
    <p class="text-[11.5px] text-[#54524B] mt-1">Inquiries archived</p>
  </div>
</div>

<!-- Source Filter Tabs & Search Bar -->
<div class="bg-white rounded-lg border border-[#EAE7DE] p-4 mb-6 shadow-xs flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
  <!-- Source Tabs -->
  <div class="flex flex-wrap gap-1.5">
    <button onclick="filterSource('all', this)" class="source-tab active px-3 py-1.5 rounded-md text-xs font-semibold bg-[#132644] text-white transition-colors">
      All Sources (<?= $totalEnquiries ?>)
    </button>
    <button onclick="filterSource('Website', this)" class="source-tab px-3 py-1.5 rounded-md text-xs font-medium text-[#54524B] hover:bg-[#F7F4EE] transition-colors">
      Website (<?= $srcCounts['Website'] ?>)
    </button>
    <button onclick="filterSource('Walk-in', this)" class="source-tab px-3 py-1.5 rounded-md text-xs font-medium text-[#54524B] hover:bg-[#F7F4EE] transition-colors">
      Walk-in Desk (<?= $srcCounts['Walk-in'] ?>)
    </button>
    <button onclick="filterSource('Phone', this)" class="source-tab px-3 py-1.5 rounded-md text-xs font-medium text-[#54524B] hover:bg-[#F7F4EE] transition-colors">
      Phone Calls (<?= $srcCounts['Phone'] ?>)
    </button>
    <button onclick="filterSource('HR Outreach', this)" class="source-tab px-3 py-1.5 rounded-md text-xs font-medium text-[#54524B] hover:bg-[#F7F4EE] transition-colors">
      HR Outreach (<?= $srcCounts['HR Outreach'] ?>)
    </button>
  </div>

  <!-- Realtime Search -->
  <div class="w-full md:w-72 relative">
    <input type="text" id="enquiry-search" onkeyup="searchEnquiries()" placeholder="Search by name, ENQ#, phone..." class="w-full pl-9 pr-3 py-1.5 text-xs bg-[#F7F4EE] border border-[#EAE7DE] rounded-md focus:outline-none focus:border-[#C6892E]"/>
    <span class="material-symbols-outlined text-[16px] text-gray-400 absolute left-2.5 top-2">search</span>
  </div>
</div>

<!-- Enquiries Data Table -->
<div class="bg-white rounded-lg border border-[#EAE7DE] shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs" id="enquiries-table">
      <thead class="bg-[#F7F4EE] text-[#54524B] uppercase tracking-wider font-semibold border-b border-[#EAE7DE]">
        <tr>
          <th class="py-3.5 px-4">Enquiry No &amp; Date</th>
          <th class="py-3.5 px-4">Source &amp; Type</th>
          <th class="py-3.5 px-4">Parent / Guardian</th>
          <th class="py-3.5 px-4">Student &amp; Class</th>
          <th class="py-3.5 px-4">Status &amp; Follow-up</th>
          <th class="py-3.5 px-4">Contact Phone</th>
          <th class="py-3.5 px-4 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#EAE7DE] text-gray-700">
        <?php if (empty($enquiries)): ?>
        <tr>
          <td colspan="7" class="py-8 text-center text-gray-500">
            No inquiries recorded yet. Click "Log New Enquiry" to add the first lead.
          </td>
        </tr>
        <?php else: foreach ($enquiries as $enq):
          $src = $enq['source'] ?? 'Website';
          $srcBadge = match($src) {
            'Website' => 'bg-sky-50 text-sky-800 border-sky-200',
            'Walk-in' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'Phone' => 'bg-purple-50 text-purple-800 border-purple-200',
            default => 'bg-indigo-50 text-indigo-800 border-indigo-200'
          };
          $srcIcon = match($src) {
            'Website' => 'language',
            'Walk-in' => 'storefront',
            'Phone' => 'call',
            default => 'campaign'
          };
          $st = $enq['status'] ?? 'New';
          $stBadge = match($st) {
            'Converted' => 'bg-green-50 text-green-800 border-green-200',
            'FollowUp', 'Follow-up Scheduled' => 'bg-amber-50 text-amber-800 border-amber-200',
            'Closed', 'NotInterested' => 'bg-gray-100 text-gray-700 border-gray-200',
            default => 'bg-blue-50 text-blue-800 border-blue-200'
          };
        ?>
        <tr class="hover:bg-[#F7F4EE]/60 transition-colors enquiry-row" data-source="<?= htmlspecialchars($src) ?>">
          <!-- Enquiry No & Date -->
          <td class="py-3.5 px-4 whitespace-nowrap">
            <span class="font-mono font-bold text-[#132644] block"><?= htmlspecialchars($enq['enquiry_no']) ?></span>
            <span class="text-[11px] text-[#747780]">
              <?= date('M d, Y', strtotime($enq['created_at'])) ?>
              <?php if (!empty($enq['session_name'])): ?> · <?= htmlspecialchars($enq['session_name']) ?><?php endif; ?>
            </span>
          </td>

          <!-- Source & Type -->
          <td class="py-3.5 px-4 whitespace-nowrap">
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded border <?= $srcBadge ?> mb-1">
              <span class="material-symbols-outlined text-[13px]"><?= $srcIcon ?></span>
              <?= htmlspecialchars($src) ?>
            </span>
            <span class="block text-[11px] text-[#54524B] font-medium"><?= htmlspecialchars($enq['enquiry_type'] ?? 'Admission') ?></span>
          </td>

          <!-- Parent / Guardian Contact -->
          <td class="py-3.5 px-4 whitespace-nowrap">
            <span class="font-semibold text-[#132644] block"><?= htmlspecialchars($enq['parent_name'] ?? '—') ?></span>
            <span class="text-[11px] text-[#54524B] block"><?= htmlspecialchars($enq['phone'] ?? '—') ?></span>
            <span class="text-[11px] text-[#747780]"><?= htmlspecialchars($enq['email'] ?? '') ?></span>
          </td>

          <!-- Student & Class -->
          <td class="py-3.5 px-4 whitespace-nowrap">
            <span class="font-semibold text-[#132644] block"><?= htmlspecialchars($enq['student_name'] ?? '—') ?></span>
            <span class="inline-block bg-[#132644]/5 text-[#132644] font-medium px-2 py-0.5 rounded text-[11px] mt-0.5">
              <?= htmlspecialchars($enq['class_name'] ?? 'General') ?>
            </span>
          </td>

          <!-- Status & Next Follow-up -->
          <td class="py-3.5 px-4 whitespace-nowrap">
            <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded border <?= $stBadge ?>">
              <?= htmlspecialchars($st) ?>
            </span>
            <?php if (!empty($enq['converted_application_id'])): ?>
              <span class="block text-[11px] text-emerald-800 font-mono mt-1 font-semibold">
                → App #<?= intval($enq['converted_application_id']) ?>
              </span>
            <?php else: ?>
              <span class="block text-[11px] text-[#747780] mt-1">
                Follow-ups: <strong><?= intval($enq['followup_count'] ?? 0) ?></strong>
              </span>
            <?php endif; ?>
          </td>

          <!-- Contact Phone -->
          <td class="py-3.5 px-4 whitespace-nowrap text-[#54524B] font-mono text-xs">
            <?= htmlspecialchars($enq['phone'] ?: '—') ?>
          </td>

          <!-- Actions -->
          <td class="py-3.5 px-4 whitespace-nowrap text-right space-x-1">
            <button onclick="openFollowupModal(<?= $enq['enquiry_id'] ?>, '<?= htmlspecialchars($enq['enquiry_no']) ?>', '<?= addslashes($enq['parent_name'] ?? '') ?>')" class="px-2.5 py-1 text-[11.5px] font-medium text-[#132644] border border-[#132644]/30 hover:bg-[#132644]/5 rounded transition-colors" title="Log communication or call note">
              Follow-up
            </button>
            <?php if (empty($enq['converted_application_id']) && $st !== 'Converted'): ?>
            <button onclick="openConvertModal(<?= $enq['enquiry_id'] ?>, '<?= htmlspecialchars($enq['enquiry_no']) ?>', '<?= addslashes($enq['student_name'] ?? '') ?>', '<?= addslashes($enq['class_name'] ?? '') ?>')" class="px-2.5 py-1 text-[11.5px] font-semibold text-white bg-[#C6892E] hover:bg-[#b27824] rounded shadow-xs transition-colors" title="Convert prospect into Admission Application">
              Convert → App
            </button>
            <?php else: ?>
            <a href="admissions.php" class="px-2.5 py-1 text-[11.5px] font-semibold text-emerald-700 bg-emerald-50 rounded border border-emerald-200">
              View App
            </a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ======================================================================
     MODAL 1: Log New Enquiry
     ====================================================================== -->
<div id="new-enquiry-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-2xl max-w-xl w-full border border-[#EAE7DE] overflow-hidden">
    <div class="bg-[#132644] text-white px-6 py-4 flex items-center justify-between">
      <h3 class="font-serif text-lg font-bold">Log New Prospect Enquiry</h3>
      <button onclick="closeModal('new-enquiry-modal')" class="text-white/70 hover:text-white">✕</button>
    </div>
    <form action="process.php" method="post" class="p-6 space-y-4 text-xs">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Source *</label>
          <select name="source" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" required>
            <option value="Website">Website Form</option>
            <option value="Walk-in" selected>Walk-in Campus Desk</option>
            <option value="Phone">Phone Inquiry</option>
            <option value="HR Outreach">HR Outreach / Campaign</option>
          </select>
        </div>
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Enquiry Type *</label>
          <select name="enquiry_type" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" required>
            <option value="Admission" selected>Admission Prospect</option>
            <option value="Fee Inquiry">Fee Structure Inquiry</option>
            <option value="General">General Information</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Parent / Guardian Name *</label>
          <input type="text" name="parent_name" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" placeholder="e.g. Tariq Mehmood" required/>
        </div>
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Student Candidate Name *</label>
          <input type="text" name="student_name" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" placeholder="e.g. Daniyal Tariq" required/>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Phone / Mobile No *</label>
          <input type="text" name="phone" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" placeholder="+92 (300) 000-0000" required/>
        </div>
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Email Address</label>
          <input type="email" name="email" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" placeholder="parent@example.com"/>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Interested Class *</label>
          <select name="requested_class_id" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" required>
            <option value="">Select Class…</option>
            <?php foreach ($allClasses as $c): ?>
              <option value="<?= $c['class_id'] ?>"><?= htmlspecialchars($c['class_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Academic Session *</label>
          <select name="requested_session_id" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" required>
            <?php foreach ($allSessions as $s):
              $sel = ($actSession && $actSession['session_id'] == $s['session_id']) ? 'selected' : '';
            ?>
              <option value="<?= $s['session_id'] ?>" <?= $sel ?>><?= htmlspecialchars($s['session_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div>
        <label class="block font-semibold text-[#132644] mb-1">Message / Notes</label>
        <textarea name="message" rows="3" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" placeholder="Record inquiry specifics, fee questions, or background..."></textarea>
      </div>

      <div class="flex justify-end gap-2 pt-3 border-t border-[#EAE7DE]">
        <button type="button" onclick="closeModal('new-enquiry-modal')" class="px-4 py-2 border rounded font-semibold text-[#54524B]">Cancel</button>
        <button type="submit" name="addenquiry" class="px-5 py-2 bg-[#132644] text-white rounded font-semibold hover:bg-[#132644]/90 transition-colors">Save Enquiry Record</button>
      </div>
    </form>
  </div>
</div>

<!-- ======================================================================
     MODAL 2: Log Follow-up
     ====================================================================== -->
<div id="followup-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full border border-[#EAE7DE] overflow-hidden">
    <div class="bg-[#132644] text-white px-6 py-4 flex items-center justify-between">
      <div>
        <h3 class="font-serif text-lg font-bold">Log Enquiry Follow-up</h3>
        <p class="text-xs text-[#9AA8C0]" id="followup-modal-sub">ENQ-XXXX-XXXX</p>
      </div>
      <button onclick="closeModal('followup-modal')" class="text-white/70 hover:text-white">✕</button>
    </div>
    <form action="process.php" method="post" class="p-6 space-y-4 text-xs">
      <input type="hidden" name="enquiry_id" id="followup_enquiry_id" value=""/>
      <div>
        <label class="block font-semibold text-[#132644] mb-1">Contact Method *</label>
        <select name="method" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" required>
          <option value="Phone Call">Phone Call</option>
          <option value="WhatsApp">WhatsApp Message</option>
          <option value="In-Person Meeting">In-Person Campus Meeting</option>
          <option value="Email">Official Email</option>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Follow-up Date</label>
          <input type="text" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" value="<?= date('M d, Y') ?>" readonly/>
        </div>
        <div>
          <label class="block font-semibold text-[#132644] mb-1">Next Follow-up Date</label>
          <input type="date" name="next_followup_date" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2"/>
        </div>
      </div>

      <div>
        <label class="block font-semibold text-[#132644] mb-1">Discussion Notes *</label>
        <textarea name="notes" rows="3" class="w-full bg-[#F7F4EE] border border-[#EAE7DE] rounded p-2" placeholder="Record parent response, interest level, questions answered..." required></textarea>
      </div>

      <div class="flex justify-end gap-2 pt-3 border-t border-[#EAE7DE]">
        <button type="button" onclick="closeModal('followup-modal')" class="px-4 py-2 border rounded font-semibold text-[#54524B]">Cancel</button>
        <button type="submit" name="addenquiryfollowup" class="px-5 py-2 bg-[#132644] text-white rounded font-semibold hover:bg-[#132644]/90 transition-colors">Save Follow-up Note</button>
      </div>
    </form>
  </div>
</div>

<!-- ======================================================================
     MODAL 3: Convert to Admission Application
     ====================================================================== -->
<div id="convert-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full border border-[#EAE7DE] overflow-hidden">
    <div class="bg-[#C6892E] text-white px-6 py-4 flex items-center justify-between">
      <div>
        <h3 class="font-serif text-lg font-bold">Convert Enquiry to Admission Application</h3>
        <p class="text-xs text-white/90" id="convert-modal-target">ENQ-XXXX-XXXX</p>
      </div>
      <button onclick="closeModal('convert-modal')" class="text-white/70 hover:text-white">✕</button>
    </div>
    <form action="process.php" method="post" class="p-6 space-y-4 text-xs">
      <input type="hidden" name="enquiry_id" id="convert_enquiry_id" value=""/>
      <div class="bg-amber-50 border border-amber-200 rounded p-3 text-amber-800 leading-relaxed">
        <strong>Pipeline Conversion Notice:</strong><br/>
        Converting this enquiry will generate a formal <strong>Admission Application (APP-YYYY-XXXX)</strong>.
        The original enquiry record will remain preserved in history with an active reference link.
        <em>(Note: An enquiry never converts directly into a student until entry test clearance and registration).</em>
      </div>

      <div class="space-y-2 text-[#54524B]">
        <p>• Candidate: <strong class="text-[#132644]" id="convert-cand-name">—</strong></p>
        <p>• Target Class: <strong class="text-[#132644]" id="convert-cand-class">—</strong></p>
      </div>

      <div class="flex justify-end gap-2 pt-4 border-t border-[#EAE7DE]">
        <button type="button" onclick="closeModal('convert-modal')" class="px-4 py-2 border rounded font-semibold text-[#54524B]">Cancel</button>
        <button type="submit" name="convertenquiry" class="px-5 py-2 bg-[#C6892E] hover:bg-[#b27824] text-white rounded font-semibold shadow-xs transition-colors">
          Confirm &amp; Generate Application
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function filterSource(source, btn) {
  document.querySelectorAll('.source-tab').forEach(b => {
    b.classList.remove('bg-[#132644]', 'text-white');
    b.classList.add('text-[#54524B]');
  });
  btn.classList.add('bg-[#132644]', 'text-white');
  btn.classList.remove('text-[#54524B]');

  const rows = document.querySelectorAll('.enquiry-row');
  rows.forEach(r => {
    if (source === 'all' || r.getAttribute('data-source') === source) {
      r.style.display = '';
    } else {
      r.style.display = 'none';
    }
  });
}

function searchEnquiries() {
  const q = document.getElementById('enquiry-search').value.toLowerCase();
  const rows = document.querySelectorAll('.enquiry-row');
  rows.forEach(r => {
    const txt = r.innerText.toLowerCase();
    r.style.display = txt.includes(q) ? '' : 'none';
  });
}

function openNewEnquiryModal() {
  document.getElementById('new-enquiry-modal').classList.remove('hidden');
}

function openFollowupModal(enqId, enqNo, parentName) {
  document.getElementById('followup_enquiry_id').value = enqId;
  document.getElementById('followup-modal-sub').innerText = `${enqNo} · ${parentName}`;
  document.getElementById('followup-modal').classList.remove('hidden');
}

function openConvertModal(enqId, enqNo, studentName, className) {
  document.getElementById('convert_enquiry_id').value = enqId;
  document.getElementById('convert-modal-target').innerText = enqNo;
  document.getElementById('convert-cand-name').innerText = studentName;
  document.getElementById('convert-cand-class').innerText = className;
  document.getElementById('convert-modal').classList.remove('hidden');
}

function closeModal(id) {
  document.getElementById(id).classList.add('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
