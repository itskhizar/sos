<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Dynamic Online Admission Application Wizard — admissions-apply.php
 * Fully Validated Multi-Step Form with Class-Dependent Document Requirements
 */
$pageTitle     = 'Online Admission Application 2026–27';
$pageDesc      = 'Complete your online admission application for SOS Hermann Gmeiner School & College. Multi-step validated form with dynamic class document uploads.';
$pageCanonical = 'http://localhost/sos/admissions-apply.php';

require_once 'include/classes/database.php';
global $database;

$activeSession = $database->getActiveSession();
$classes = $database->getAllClasses();

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="py-16 bg-[#faf9f6]">
  <div class="max-w-[1000px] mx-auto px-4 sm:px-6">
    
    <!-- Application Header Context -->
    <div class="text-center mb-10">
      <span class="eyebrow-text block mb-2 font-bold text-[#C6892E] tracking-wider uppercase text-xs">
        Academic Session <?= $activeSession ? htmlspecialchars($activeSession['session_name']) : '2026–2027' ?>
      </span>
      <h1 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-3">Online Admission Application</h1>
      <p class="text-sm md:text-base text-[#54524B] max-w-2xl mx-auto">
        Please complete all steps carefully. Document requirements dynamically adapt according to the applying grade (Nursery through FSc).
      </p>
    </div>

    <!-- Multi-Step Stepper Navigation -->
    <div class="w-full mb-10">
      <div class="flex justify-between items-center relative px-2">
        <!-- Progress Line Background -->
        <div class="absolute left-6 right-6 top-1/2 -translate-y-1/2 h-1 bg-[#EAE7DE] z-0 rounded-full"></div>
        <!-- Active Progress Line -->
        <div class="absolute left-6 top-1/2 -translate-y-1/2 h-1 bg-[#C6892E] z-0 rounded-full transition-all duration-500" id="progress-bar" style="width: 0%;"></div>

        <!-- Step 1 -->
        <div class="relative z-10 flex flex-col items-center gap-2 group cursor-pointer" onclick="goToStep(1)">
          <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-[#C6892E] text-white shadow-sm step-indicator" id="step-dot-1">1</div>
          <span class="text-xs font-bold text-[#C6892E] hidden sm:block" id="step-label-1">Applicant</span>
        </div>

        <!-- Step 2 -->
        <div class="relative z-10 flex flex-col items-center gap-2 group cursor-pointer" onclick="goToStep(2)">
          <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-white border border-[#EAE7DE] text-[#54524B] shadow-xs step-indicator" id="step-dot-2">2</div>
          <span class="text-xs font-medium text-[#54524B] hidden sm:block" id="step-label-2">Parent / Guardian</span>
        </div>

        <!-- Step 3 -->
        <div class="relative z-10 flex flex-col items-center gap-2 group cursor-pointer" onclick="goToStep(3)">
          <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-white border border-[#EAE7DE] text-[#54524B] shadow-xs step-indicator" id="step-dot-3">3</div>
          <span class="text-xs font-medium text-[#54524B] hidden sm:block" id="step-label-3">Academic Selection</span>
        </div>

        <!-- Step 4 -->
        <div class="relative z-10 flex flex-col items-center gap-2 group cursor-pointer" onclick="goToStep(4)">
          <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-white border border-[#EAE7DE] text-[#54524B] shadow-xs step-indicator" id="step-dot-4">4</div>
          <span class="text-xs font-medium text-[#54524B] hidden sm:block" id="step-label-4">Required Documents</span>
        </div>

        <!-- Step 5 -->
        <div class="relative z-10 flex flex-col items-center gap-2 group cursor-pointer" onclick="goToStep(5)">
          <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-white border border-[#EAE7DE] text-[#54524B] shadow-xs step-indicator" id="step-dot-5">5</div>
          <span class="text-xs font-medium text-[#54524B] hidden sm:block" id="step-label-5">Review &amp; Submit</span>
        </div>
      </div>
    </div>

    <!-- Wizard Form Card -->
    <div class="bg-white rounded-xl border border-[#EAE7DE] p-6 sm:p-10 shadow-sm relative">
      <form id="admission-wizard-form" action="process.php" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="public_admission" value="1"/>
        <input type="hidden" name="session_id" value="<?= $activeSession ? intval($activeSession['session_id']) : 0 ?>"/>

        <!-- ================= STEP 1: APPLICANT STUDENT INFO ================= -->
        <div class="wizard-step block" id="wizard-step-1">
          <div class="border-b border-[#EAE7DE] pb-4 mb-6">
            <h2 class="font-serif text-xl font-bold text-[#132644]">1. Applicant Student Information</h2>
            <p class="text-xs text-[#54524B] mt-0.5">Please provide personal identification details for the candidate student.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="s_first_name">Student First Name <span class="text-red-500">*</span></label>
              <input class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="s_first_name" name="s_first_name" placeholder="e.g. Muhammad Bilal" type="text" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please enter candidate's first name.</span>
            </div>
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="s_last_name">Student Last / Family Name <span class="text-red-500">*</span></label>
              <input class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="s_last_name" name="s_last_name" placeholder="e.g. Khan" type="text" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please enter candidate's family / last name.</span>
            </div>

            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="s_dob">Date of Birth <span class="text-red-500">*</span></label>
              <input class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="s_dob" name="s_dob" type="date" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please provide date of birth.</span>
            </div>
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="s_gender">Gender <span class="text-red-500">*</span></label>
              <select class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="s_gender" name="s_gender" required>
                <option value="">Select gender...</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please select gender.</span>
            </div>

            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="s_blood">Blood Group</label>
              <select class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="s_blood" name="blood_group">
                <option value="">Unknown / Optional</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="s_religion">Religion</label>
              <input class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="s_religion" name="religion" value="Islam" type="text"/>
            </div>
          </div>
        </div>

        <!-- ================= STEP 2: PARENT / GUARDIAN & CONTACT ================= -->
        <div class="wizard-step hidden" id="wizard-step-2">
          <div class="border-b border-[#EAE7DE] pb-4 mb-6">
            <h2 class="font-serif text-xl font-bold text-[#132644]">2. Parent / Guardian Details</h2>
            <p class="text-xs text-[#54524B] mt-0.5">Contact coordinates for institutional notifications, test schedules, and fee vouchers.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="father_name">Father / Guardian Full Name <span class="text-red-500">*</span></label>
              <input class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="father_name" name="father_name" placeholder="e.g. Tariq Mehmood" type="text" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Father / Guardian name is required.</span>
            </div>
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="father_cnic">Father / Guardian CNIC <span class="text-red-500">*</span></label>
              <input class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none font-mono" id="father_cnic" name="father_cnic" placeholder="13503-xxxxxxx-x" type="text" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Valid CNIC is required.</span>
            </div>

            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="father_phone">Active Mobile Number (For SMS Alerts) <span class="text-red-500">*</span></label>
              <input class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none font-mono" id="father_phone" name="father_phone" placeholder="0300 1234567" type="tel" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please enter active phone number.</span>
            </div>
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="email">Active Email Address <span class="text-red-500">*</span></label>
              <input class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="email" name="email" placeholder="guardian@example.com" type="email" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please enter valid email address.</span>
            </div>

            <div class="sm:col-span-2">
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="home_address">Permanent / Present Residential Address <span class="text-red-500">*</span></label>
              <textarea class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="home_address" name="home_address" placeholder="Village / Street, Mohallah, Tehsil & District (e.g. Dhodial, Mansehra)" rows="2" required></textarea>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Residential address is required.</span>
            </div>
          </div>
        </div>

        <!-- ================= STEP 3: ACADEMIC SELECTION ================= -->
        <div class="wizard-step hidden" id="wizard-step-3">
          <div class="border-b border-[#EAE7DE] pb-4 mb-6">
            <h2 class="font-serif text-xl font-bold text-[#132644]">3. Academic Level Selection</h2>
            <p class="text-xs text-[#54524B] mt-0.5">Select the target class for enrollment and provide academic background.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="target_class_id">Applying For Class / Grade <span class="text-red-500">*</span></label>
              <select class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none font-semibold text-[#132644]" id="target_class_id" name="class_id" onchange="handleClassChange(this)" required>
                <option value="">Select target grade…</option>
                <?php foreach ($classes as $c): ?>
                  <option value="<?= $c['class_id'] ?>" data-name="<?= htmlspecialchars($c['class_name']) ?>" data-level="<?= $c['class_id'] ?>">
                    <?= htmlspecialchars($c['class_name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please select the target class.</span>
            </div>

            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="prev_school">Previous School / College Attended</label>
              <input class="w-full rounded-lg border border-[#EAE7DE] bg-white text-sm p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644] outline-none" id="prev_school" name="prev_school" placeholder="Leave blank if applying for Nursery / First Admission" type="text"/>
            </div>

            <div class="sm:col-span-2 p-4 bg-amber-50/60 border border-amber-200/60 rounded-lg flex items-center gap-3">
              <span class="material-symbols-outlined text-[#C6892E] text-[24px]">info</span>
              <p class="text-xs text-[#885200] leading-relaxed">
                <strong>Dynamic Curriculum Notice:</strong> The next step will automatically customize required document attachments based on your selected grade level.
              </p>
            </div>
          </div>
        </div>

        <!-- ================= STEP 4: DYNAMIC DOCUMENT UPLOADS ================= -->
        <div class="wizard-step hidden" id="wizard-step-4">
          <div class="border-b border-[#EAE7DE] pb-4 mb-6">
            <h2 class="font-serif text-xl font-bold text-[#132644]">4. Required Document Attachments</h2>
            <p class="text-xs text-[#54524B] mt-0.5">Upload clear, scanned copies or clear mobile photos (JPG, PNG, PDF up to 4MB each).</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- 1. Student Passport Photo (Required for ALL) -->
            <div class="border-2 border-dashed border-[#EAE7DE] hover:border-[#C6892E] rounded-xl p-5 text-center bg-gray-50/50 transition-colors">
              <span class="material-symbols-outlined text-3xl text-[#C6892E] mb-1">account_box</span>
              <p class="text-xs font-bold text-[#132644]">Student Passport-Size Photograph <span class="text-red-500">*</span></p>
              <p class="text-[11px] text-[#747780] mt-0.5">Recent colored picture with blue/white background</p>
              <input type="file" id="doc_photo" name="doc_photo" accept="image/*,.pdf" class="text-xs mt-3 w-full text-center" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please attach student photo.</span>
            </div>

            <!-- 2. Father CNIC Front (Required for ALL) -->
            <div class="border-2 border-dashed border-[#EAE7DE] hover:border-[#C6892E] rounded-xl p-5 text-center bg-gray-50/50 transition-colors">
              <span class="material-symbols-outlined text-3xl text-[#002452] mb-1">id_card</span>
              <p class="text-xs font-bold text-[#132644]">Father / Guardian CNIC (Front Side) <span class="text-red-500">*</span></p>
              <p class="text-[11px] text-[#747780] mt-0.5">Clear scan or camera photo of front side</p>
              <input type="file" id="doc_cnic_front" name="doc_cnic_front" accept="image/*,.pdf" class="text-xs mt-3 w-full text-center" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please attach CNIC Front.</span>
            </div>

            <!-- 3. Father CNIC Back (Required for ALL) -->
            <div class="border-2 border-dashed border-[#EAE7DE] hover:border-[#C6892E] rounded-xl p-5 text-center bg-gray-50/50 transition-colors">
              <span class="material-symbols-outlined text-3xl text-[#002452] mb-1">badge</span>
              <p class="text-xs font-bold text-[#132644]">Father / Guardian CNIC (Back Side) <span class="text-red-500">*</span></p>
              <p class="text-[11px] text-[#747780] mt-0.5">Clear scan or camera photo of back side</p>
              <input type="file" id="doc_cnic_back" name="doc_cnic_back" accept="image/*,.pdf" class="text-xs mt-3 w-full text-center" required/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block">Please attach CNIC Back.</span>
            </div>

            <!-- 4. Dynamic Educational Document (Based on Selected Grade Level) -->
            <div id="dynamic-doc-card" class="border-2 border-dashed border-[#EAE7DE] hover:border-[#C6892E] rounded-xl p-5 text-center bg-gray-50/50 transition-colors">
              <span class="material-symbols-outlined text-3xl text-[#00522d] mb-1" id="dyn-doc-icon">history_edu</span>
              <p class="text-xs font-bold text-[#132644]" id="dyn-doc-title">Previous Class Record Card / Certificate</p>
              <p class="text-[11px] text-[#747780] mt-0.5" id="dyn-doc-desc">Attachment requirements adapt to selected grade level</p>
              <input type="file" id="doc_previous_record" name="doc_previous_record" accept="image/*,.pdf" class="text-xs mt-3 w-full text-center"/>
              <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block" id="dyn-doc-err">Document is required for this grade level.</span>
            </div>
          </div>
        </div>

        <!-- ================= STEP 5: REVIEW & DECLARATION ================= -->
        <div class="wizard-step hidden" id="wizard-step-5">
          <div class="border-b border-[#EAE7DE] pb-4 mb-6">
            <h2 class="font-serif text-xl font-bold text-[#132644]">5. Review &amp; Official Declaration</h2>
            <p class="text-xs text-[#54524B] mt-0.5">Please review your declaration prior to institutional registration.</p>
          </div>

          <div class="bg-[#F7F4EE] border border-[#EAE7DE] rounded-xl p-6 mb-6 space-y-4">
            <div class="flex items-center gap-2.5 text-sm font-bold text-[#132644]">
              <span class="material-symbols-outlined text-[#00522d] text-[22px]">verified</span>
              <span>Application Ready for Official Registration</span>
            </div>
            <p class="text-xs text-[#54524B] leading-relaxed">
              Upon clicking "Submit Application", your entry will be recorded in the SOS Hermann Gmeiner School Management System. An Application Reference ID will be generated immediately for status tracking.
            </p>
            <div class="p-3.5 bg-white rounded-lg border border-[#EAE7DE] text-xs text-[#54524B] flex items-start gap-2.5">
              <span class="material-symbols-outlined text-[#C6892E] text-[18px] shrink-0 mt-0.5">notifications_active</span>
              <p>
                <strong>Note:</strong> Entry test date and admission card will be provided via SMS and accessible through the Tracking Portal. Please make sure your phone and email remain active.
              </p>
            </div>
          </div>

          <!-- Application Summary Snapshot -->
          <div class="bg-white border border-[#EAE7DE] rounded-xl p-5 mb-6 text-xs space-y-2">
            <h4 class="font-bold text-[#132644] uppercase tracking-wider text-[11px] mb-3">Application Snapshot</h4>
            <div class="grid grid-cols-2 gap-2 text-[#44474f]">
              <div>Candidate: <strong id="sum-name" class="text-[#132644]">—</strong></div>
              <div>Applying Grade: <strong id="sum-grade" class="text-[#132644]">—</strong></div>
              <div>Parent / Guardian: <strong id="sum-parent" class="text-[#132644]">—</strong></div>
              <div>Contact Mobile: <strong id="sum-phone" class="text-[#132644]">—</strong></div>
            </div>
          </div>

          <div class="p-4 bg-gray-50 rounded-lg border border-[#EAE7DE] mb-6">
            <label class="flex items-start gap-3 cursor-pointer">
              <input type="checkbox" id="declaration_agreed" name="declaration_agreed" class="mt-1 rounded text-[#C6892E] focus:ring-[#C6892E]" required/>
              <span class="text-xs text-[#1C1C1A] leading-relaxed">
                I solemnly affirm that the particulars stated above and uploaded documents are authentic and genuine. I agree to abide by the disciplinary and academic policies of SOS Hermann Gmeiner School &amp; College, Dhodial.
              </span>
            </label>
            <span class="field-error hidden text-[11px] text-red-600 font-semibold mt-1 block" id="dec-err">You must agree to the declaration before submission.</span>
          </div>
        </div>

        <!-- Wizard Navigation Action Buttons -->
        <div class="flex justify-between items-center pt-6 border-t border-[#EAE7DE] mt-6">
          <button type="button" id="prev-btn" onclick="prevStep()" class="border border-[#132644] text-[#132644] hover:bg-[#132644]/5 px-6 py-2.5 rounded-lg font-semibold text-xs md:text-sm transition-colors hidden">
            &larr; Back
          </button>
          <div class="ml-auto flex gap-4">
            <button type="button" id="next-btn" onclick="nextStep()" class="bg-[#132644] hover:bg-[#0C1B30] text-white px-8 py-2.5 rounded-lg font-semibold text-xs md:text-sm transition-all shadow-sm">
              Continue to Step 2 &rarr;
            </button>
            <button type="submit" name="application" id="submit-btn" class="bg-[#C6892E] hover:bg-[#b27824] text-white px-8 py-2.5 rounded-lg font-semibold text-xs md:text-sm transition-all shadow-sm hidden">
              Submit Application
            </button>
          </div>
        </div>

      </form>
    </div>

  </div>
</main>

<script>
let currentStepNum = 1;
const totalSteps = 5;

// Dynamic Document Configuration Based on Grade
function handleClassChange(selectEl) {
  const selectedOpt = selectEl.options[selectEl.selectedIndex];
  const className = selectedOpt ? selectedOpt.getAttribute('data-name') || selectedOpt.text : '';
  const dynTitle = document.getElementById('dyn-doc-title');
  const dynDesc = document.getElementById('dyn-doc-desc');
  const dynInput = document.getElementById('doc_previous_record');
  const dynErr = document.getElementById('dyn-doc-err');
  const dynIcon = document.getElementById('dyn-doc-icon');

  if (!className || className.includes('Select')) return;

  const lower = className.toLowerCase();

  if (lower.includes('nursery') || lower.includes('prep') || lower.includes('kg')) {
    // Early childhood
    dynTitle.innerHTML = 'Previous Progress Report <span class="text-xs text-gray-500 font-normal">(Optional for Nursery/KG)</span>';
    dynDesc.textContent = 'Not required for fresh early-childhood admission';
    dynInput.required = false;
    dynInput.classList.remove('border-red-500');
    dynIcon.textContent = 'child_care';
  } else if (lower.includes('class 1') || lower.includes('class 2') || lower.includes('class 3') || lower.includes('class 4') || lower.includes('class 5')) {
    // Primary Wing
    dynTitle.innerHTML = 'Previous Class Result / Progress Sheet <span class="text-red-500">*</span>';
    dynDesc.textContent = 'Attach last grade passed report card from previous school';
    dynInput.required = true;
    dynIcon.textContent = 'history_edu';
  } else if (lower.includes('class 6') || lower.includes('class 7') || lower.includes('class 8') || lower.includes('class 9') || lower.includes('class 10')) {
    // Middle / Matric
    dynTitle.innerHTML = 'Previous Class Result / School Leaving Certificate <span class="text-red-500">*</span>';
    dynDesc.textContent = 'Required: Previous class result sheet & school leaving certificate';
    dynInput.required = true;
    dynIcon.textContent = 'school';
  } else if (lower.includes('11') || lower.includes('12') || lower.includes('fsc') || lower.includes('intermediate') || lower.includes('fa')) {
    // College Wing (FSc / HSSC)
    dynTitle.innerHTML = 'Matriculation (SSC) Result Card / DMC Certificate <span class="text-red-500">*</span>';
    dynDesc.textContent = 'Required: Official BISE Matriculation Detailed Marks Certificate (DMC)';
    dynInput.required = true;
    dynIcon.textContent = 'workspace_premium';
  } else {
    dynTitle.innerHTML = 'Previous Academic Transcript / Certificate <span class="text-red-500">*</span>';
    dynDesc.textContent = 'Attach previous educational progress certificate';
    dynInput.required = true;
  }
}

// Validation for Current Step
function validateStep(step) {
  const container = document.getElementById(`wizard-step-${step}`);
  if (!container) return true;

  let isValid = true;
  let firstInvalid = null;

  // Find all required inputs and selects in this step
  const requiredFields = container.querySelectorAll('input[required], select[required], textarea[required]');
  requiredFields.forEach(field => {
    let fieldValid = true;

    if (field.type === 'file') {
      if (field.required && (!field.files || field.files.length === 0)) {
        fieldValid = false;
      }
    } else if (field.type === 'checkbox') {
      if (field.required && !field.checked) {
        fieldValid = false;
      }
    } else {
      if (!field.value || field.value.trim() === '') {
        fieldValid = false;
      }
    }

    // Toggle error message
    const errSpan = field.parentElement ? field.parentElement.querySelector('.field-error') : null;
    if (!fieldValid) {
      isValid = false;
      field.classList.add('border-red-500', 'bg-red-50/20');
      if (errSpan) errSpan.classList.remove('hidden');
      if (!firstInvalid) firstInvalid = field;
    } else {
      field.classList.remove('border-red-500', 'bg-red-50/20');
      if (errSpan) errSpan.classList.add('hidden');
    }
  });

  if (!isValid && firstInvalid) {
    firstInvalid.focus();
    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  return isValid;
}

// Stepper visual updates
function updateStepper(step) {
  currentStepNum = step;

  // Populate Snapshot on Step 5
  if (step === 5) {
    const fName = document.getElementById('s_first_name').value.trim();
    const lName = document.getElementById('s_last_name').value.trim();
    document.getElementById('sum-name').textContent = `${fName} ${lName}` || '—';

    const clSelect = document.getElementById('target_class_id');
    const clText = clSelect.options[clSelect.selectedIndex] ? clSelect.options[clSelect.selectedIndex].text : '—';
    document.getElementById('sum-grade').textContent = clText;

    document.getElementById('sum-parent').textContent = document.getElementById('father_name').value.trim() || '—';
    document.getElementById('sum-phone').textContent = document.getElementById('father_phone').value.trim() || '—';
  }
  
  // Update step visibility
  for (let i = 1; i <= totalSteps; i++) {
    const stepEl = document.getElementById(`wizard-step-${i}`);
    const dot = document.getElementById(`step-dot-${i}`);
    const label = document.getElementById(`step-label-${i}`);
    
    if (i === step) {
      stepEl.classList.remove('hidden');
      stepEl.classList.add('block');
      dot.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-[#C6892E] text-white shadow-sm';
      if (label) { label.className = 'text-xs font-bold text-[#C6892E] hidden sm:block'; }
    } else if (i < step) {
      stepEl.classList.add('hidden');
      stepEl.classList.remove('block');
      dot.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-[#00522d] text-white shadow-xs';
      dot.innerHTML = '<span class="material-symbols-outlined text-[18px]">check</span>';
      if (label) { label.className = 'text-xs font-semibold text-[#00522d] hidden sm:block'; }
    } else {
      stepEl.classList.add('hidden');
      stepEl.classList.remove('block');
      dot.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-white border border-[#EAE7DE] text-[#54524B] shadow-xs';
      dot.textContent = i;
      if (label) { label.className = 'text-xs font-medium text-[#54524B] hidden sm:block'; }
    }
  }

  // Update progress line
  const progressPercent = ((step - 1) / (totalSteps - 1)) * 100;
  document.getElementById('progress-bar').style.width = `${progressPercent}%`;

  // Buttons
  const prevBtn = document.getElementById('prev-btn');
  const nextBtn = document.getElementById('next-btn');
  const submitBtn = document.getElementById('submit-btn');

  if (step === 1) {
    prevBtn.classList.add('hidden');
  } else {
    prevBtn.classList.remove('hidden');
  }

  if (step === totalSteps) {
    nextBtn.classList.add('hidden');
    submitBtn.classList.remove('hidden');
  } else {
    nextBtn.classList.remove('hidden');
    nextBtn.textContent = `Continue to Step ${step + 1} →`;
    submitBtn.classList.add('hidden');
  }

  window.scrollTo({ top: 140, behavior: 'smooth' });
}

function nextStep() {
  if (validateStep(currentStepNum)) {
    if (currentStepNum < totalSteps) {
      updateStepper(currentStepNum + 1);
    }
  }
}

function prevStep() {
  if (currentStepNum > 1) {
    updateStepper(currentStepNum - 1);
  }
}

function goToStep(targetStep) {
  if (targetStep < currentStepNum) {
    updateStepper(targetStep);
  } else if (targetStep > currentStepNum) {
    // Only allow if current step is valid
    if (validateStep(currentStepNum)) {
      updateStepper(targetStep);
    }
  }
}

// Real-time input listeners to clear validation errors
document.addEventListener('DOMContentLoaded', () => {
  const inputs = document.querySelectorAll('input, select, textarea');
  inputs.forEach(inp => {
    inp.addEventListener('input', () => {
      inp.classList.remove('border-red-500', 'bg-red-50/20');
      const err = inp.parentElement ? inp.parentElement.querySelector('.field-error') : null;
      if (err) err.classList.add('hidden');
    });
  });

  // Init class selection if already selected
  const clSelect = document.getElementById('target_class_id');
  if (clSelect && clSelect.value) {
    handleClassChange(clSelect);
  }
});
</script>

<?php include 'includes/footer.php'; ?>
