<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Careers Page — careers.php
 * Dynamic Faculty & Staff Recruitment Portal
 */
$pageTitle     = 'Careers — Join Our Academic Faculty & Team';
$pageDesc      = 'Explore career opportunities for teachers, lecturers, and administrative professionals at SOS Hermann Gmeiner School & College, Dhodial.';
$pageKeywords  = 'careers SOS Hermann Gmeiner, teaching jobs Dhodial, lecturer jobs Mansehra';
$pageCanonical = 'http://localhost/sos/careers.php';

include 'includes/head.php';
include 'includes/header.php';

// Fetch open jobs dynamically from database
$openJobs = $database->getOpenJobs();
?>

<main class="pb-24">
  <!-- Page Hero Header (Rich Brand Styling) -->
  <header class="bg-[#132644] py-16 lg:py-20 text-white relative overflow-hidden border-b border-[#EAE7DE]">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10 text-center">
      <div class="border border-[#C6892E]/40 bg-[#C6892E]/15 px-3.5 py-1.5 rounded-full text-[13px] font-medium w-fit mx-auto flex items-center gap-2 mb-4 shadow-xs">
        <span class="w-2 h-2 rounded-full bg-[#C6892E]"></span>
        <span class="text-[#F7F4EE]">Academic &amp; Administrative Faculty Recruitment</span>
      </div>
      <h1 class="font-serif text-3xl md:text-5xl font-bold text-white mb-4 tracking-tight">
        Join Our Academic Faculty &amp; Team
      </h1>
      <p class="text-base text-[#DCE3F0] max-w-2xl mx-auto leading-relaxed">
        Shape the future with Hermann Gmeiner School &amp; College. We pair genuine academic ambition with character mentorship, providing our faculty with purposeful work and continuous professional growth.
      </p>
    </div>
  </header>

  <div class="max-w-[1400px] mx-auto px-6 md:px-12 py-16 space-y-16">

    <!-- Feedback Banner -->
    <?php if (isset($_GET['applied']) && $_GET['applied'] === 'true'): ?>
      <div class="p-5 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 shadow-sm flex items-start gap-3">
        <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0 mt-0.5">check_circle</span>
        <div>
          <h4 class="font-serif font-bold text-base text-emerald-950">Application Submitted Successfully!</h4>
          <p class="text-xs md:text-sm text-emerald-800 mt-1">
            Thank you for applying. Your resume and credentials have been received by the SOS Hermann Gmeiner HR &amp; Academic Committee. Shortlisted candidates will be contacted via telephone and email for teaching demonstrations and interviews.
          </p>
        </div>
      </div>
    <?php elseif (isset($_GET['error'])): ?>
      <div class="p-4 rounded-xl bg-red-50 border border-red-300 text-red-900 shadow-sm flex items-center gap-3">
        <span class="material-symbols-outlined text-red-600 text-2xl shrink-0">error</span>
        <p class="text-xs md:text-sm">Could not process job application. Please check that your resume file is in PDF or DOC format and try again.</p>
      </div>
    <?php endif; ?>

    <!-- Active Openings Section -->
    <section id="open-positions">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8 border-b border-[#EAE7DE] pb-4">
        <div>
          <span class="eyebrow-text block mb-1">Open Positions</span>
          <h2 class="font-serif text-2xl md:text-3xl font-bold text-[#132644]">Available Vacancies</h2>
        </div>
        <span class="text-xs text-[#54524B] font-semibold bg-[#F7F4EE] border border-[#EAE7DE] px-3.5 py-1.5 rounded-full">
          <?= count($openJobs) ?> Open Positions
        </span>
      </div>

      <?php if (empty($openJobs)): ?>
        <div class="p-12 text-center bg-white rounded-xl border border-[#EAE7DE] shadow-xs">
          <span class="material-symbols-outlined text-5xl text-gray-400 mb-3">work_off</span>
          <h3 class="font-serif text-xl font-bold text-[#132644] mb-2">No Active Vacancies at This Time</h3>
          <p class="text-sm text-[#54524B] max-w-lg mx-auto">
            We currently do not have open job openings. Please check back periodically as new faculty and administrative opportunities are announced here.
          </p>
        </div>
      <?php else: ?>
        <div class="grid grid-cols-1 gap-6">
          <?php foreach ($openJobs as $job): 
            $deadline = date('F d, Y', strtotime($job['last_date_to_apply']));
            $daysLeft = ceil((strtotime($job['last_date_to_apply']) - time()) / 86400);
          ?>
          <div class="p-6 md:p-8 bg-white rounded-xl border border-[#EAE7DE] shadow-xs hover:border-[#C6892E] hover:shadow-md transition-all flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 group">
            <div class="space-y-3 flex-grow">
              <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="bg-[#132644]/10 text-[#132644] font-bold px-3 py-1 rounded-md"><?= htmlspecialchars($job['department']) ?></span>
                <span class="bg-[#F7F4EE] text-[#54524B] font-medium px-3 py-1 rounded-md border border-[#EAE7DE]"><?= htmlspecialchars($job['employment_type']) ?></span>
                <?php if ($job['vacancies'] > 1): ?>
                  <span class="bg-blue-50 text-blue-800 font-semibold px-2.5 py-1 rounded-md"><?= intval($job['vacancies']) ?> Vacancies</span>
                <?php endif; ?>
                <?php if ($daysLeft > 0 && $daysLeft <= 10): ?>
                  <span class="bg-amber-100 text-amber-800 font-bold px-2.5 py-1 rounded-md">Closing in <?= $daysLeft ?> days</span>
                <?php endif; ?>
              </div>
              
              <h3 class="font-serif text-2xl font-bold text-[#132644] group-hover:text-[#C6892E] transition-colors">
                <?= htmlspecialchars($job['job_title']) ?>
              </h3>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-700 bg-gray-50/70 p-3 rounded-lg border border-gray-100 max-w-3xl">
                <div><strong class="text-gray-500 font-medium">Qualification:</strong> <?= htmlspecialchars($job['qualification'] ?: 'Relevant Degree') ?></div>
                <div><strong class="text-gray-500 font-medium">Experience:</strong> <?= htmlspecialchars($job['experience_required'] ?: 'Fresh / Experienced') ?></div>
              </div>

              <p class="text-sm text-[#54524B] leading-relaxed max-w-3xl">
                <?= nl2br(htmlspecialchars($job['description'])) ?>
              </p>

              <?php if (!empty($job['requirements'])): ?>
                <div class="text-xs text-gray-600 max-w-3xl">
                  <strong class="text-[#132644]">Key Requirements:</strong> <?= htmlspecialchars($job['requirements']) ?>
                </div>
              <?php endif; ?>

              <div class="flex items-center gap-4 text-xs text-[#747780] pt-1">
                <span>Application Deadline: <strong class="text-red-700 font-bold"><?= $deadline ?></strong></span>
                <span>·</span>
                <span>Location: <strong>Dhodial, Mansehra Campus</strong></span>
              </div>
            </div>

            <div class="shrink-0 w-full lg:w-auto">
              <button onclick="openApplyModal(<?= htmlspecialchars(json_encode($job)) ?>)" 
                class="w-full lg:w-auto bg-[#C6892E] hover:bg-[#b27824] text-white px-7 py-3.5 rounded-lg text-xs font-bold whitespace-nowrap transition-colors inline-flex items-center justify-center gap-2 shadow-sm">
                <span>Apply Online Now</span>
                <span class="material-symbols-outlined text-[16px]">send</span>
              </button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <!-- Why Teach at SOS (3 Value Pillars) -->
    <section class="bg-[#F7F4EE] border border-[#EAE7DE] rounded-xl p-8 lg:p-12">
      <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="eyebrow-text block mb-1">Our Environment</span>
        <h3 class="font-serif text-2xl md:text-3xl font-bold text-[#132644]">Why Teach at SOS Hermann Gmeiner?</h3>
        <p class="text-xs md:text-sm text-[#54524B] mt-2">Join a supportive community where academic rigor is grounded in compassion and values.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
        <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs">
          <div class="w-12 h-12 rounded-full bg-[#132644]/10 text-[#132644] flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-2xl">workspace_premium</span>
          </div>
          <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">Competitive Benefits</h4>
          <p class="text-xs text-[#54524B] leading-relaxed">Attractive salary structure aligned with qualifications, annual increments, institutional medical benefits, and professional allowances.</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs">
          <div class="w-12 h-12 rounded-full bg-[#C6892E]/15 text-[#C6892E] flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-2xl">model_training</span>
          </div>
          <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">Continuous Development</h4>
          <p class="text-xs text-[#54524B] leading-relaxed">Regular faculty pedagogy workshops, international SOS Children's Villages training programs, and curriculum certifications.</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs">
          <div class="w-12 h-12 rounded-full bg-[#2E8B57]/10 text-[#2E8B57] flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-2xl">diversity_1</span>
          </div>
          <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">Purpose-Driven Impact</h4>
          <p class="text-xs text-[#54524B] leading-relaxed">Mentor students across academic and co-curricular programs, shaping future leaders with strong moral foundations.</p>
        </div>
      </div>
    </section>

    <!-- Recruitment Flow Strip -->
    <section class="border border-[#EAE7DE] rounded-xl p-8 bg-white">
      <div class="text-center max-w-xl mx-auto mb-8">
        <span class="eyebrow-text block mb-1">Our Process</span>
        <h3 class="font-serif text-xl md:text-2xl font-bold text-[#132644]">Faculty Recruitment Stages</h3>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-center">
        <div class="p-4 bg-[#F7F4EE] rounded-lg border border-[#EAE7DE]">
          <span class="text-xs font-bold text-[#C6892E] block mb-1">STAGE 1</span>
          <h4 class="font-serif text-sm font-bold text-[#132644]">Online Submission</h4>
          <p class="text-[11px] text-[#54524B] mt-1">Submit your details and CV directly through our careers portal.</p>
        </div>
        <div class="p-4 bg-[#F7F4EE] rounded-lg border border-[#EAE7DE]">
          <span class="text-xs font-bold text-[#C6892E] block mb-1">STAGE 2</span>
          <h4 class="font-serif text-sm font-bold text-[#132644]">Committee Screening</h4>
          <p class="text-[11px] text-[#54524B] mt-1">Academic committee reviews subject expertise and teaching experience.</p>
        </div>
        <div class="p-4 bg-[#F7F4EE] rounded-lg border border-[#EAE7DE]">
          <span class="text-xs font-bold text-[#C6892E] block mb-1">STAGE 3</span>
          <h4 class="font-serif text-sm font-bold text-[#132644]">Teaching Demo &amp; Test</h4>
          <p class="text-[11px] text-[#54524B] mt-1">Classroom presentation demo evaluated by subject heads.</p>
        </div>
        <div class="p-4 bg-[#F7F4EE] rounded-lg border border-[#EAE7DE]">
          <span class="text-xs font-bold text-[#C6892E] block mb-1">STAGE 4</span>
          <h4 class="font-serif text-sm font-bold text-[#132644]">Formal Interview &amp; Offer</h4>
          <p class="text-[11px] text-[#54524B] mt-1">Final interview with Principal and formal appointment offer.</p>
        </div>
      </div>
    </section>

  </div>
</main>

<!-- MODAL: ONLINE JOB APPLICATION -->
<div id="modal-apply" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl p-6 md:p-8 relative max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between pb-4 border-b border-gray-200 mb-5">
      <div>
        <h3 class="font-serif text-xl font-bold text-[#132644]">Online Job Application</h3>
        <p class="text-xs text-gray-500 mt-0.5" id="modal-job-title-display">Applying for position</p>
      </div>
      <button onclick="closeApplyModal()" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[22px]">close</span>
      </button>
    </div>

    <form action="process.php" method="post" enctype="multipart/form-data" class="space-y-4">
      <input type="hidden" name="apply_job" value="1"/>
      <input type="hidden" name="job_id" id="apply-job-id" value=""/>

      <div>
        <label class="block text-xs font-bold text-[#132644] mb-1">Full Legal Name <span class="text-red-500">*</span></label>
        <input type="text" name="applicant_name" required placeholder="e.g. Dr. Muhammad Tariq" 
          class="w-full text-xs border border-gray-300 rounded-lg px-3.5 py-2.5 outline-none focus:border-[#C6892E]"/>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-[#132644] mb-1">Email Address <span class="text-red-500">*</span></label>
          <input type="email" name="email" required placeholder="tariq@example.com" 
            class="w-full text-xs border border-gray-300 rounded-lg px-3.5 py-2.5 outline-none focus:border-[#C6892E]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#132644] mb-1">Mobile Contact Phone <span class="text-red-500">*</span></label>
          <input type="text" name="phone" required placeholder="0300-1234567" 
            class="w-full text-xs border border-gray-300 rounded-lg px-3.5 py-2.5 outline-none focus:border-[#C6892E]"/>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-[#132644] mb-1">Highest Qualification <span class="text-red-500">*</span></label>
          <input type="text" name="qualification" required placeholder="e.g. M.Phil in Physics" 
            class="w-full text-xs border border-gray-300 rounded-lg px-3.5 py-2.5 outline-none focus:border-[#C6892E]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#132644] mb-1">Years of Relevant Experience <span class="text-red-500">*</span></label>
          <input type="text" name="experience" required placeholder="e.g. 4 Years" 
            class="w-full text-xs border border-gray-300 rounded-lg px-3.5 py-2.5 outline-none focus:border-[#C6892E]"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#132644] mb-1">Upload Resume / CV (PDF or DOC) <span class="text-red-500">*</span></label>
        <input type="file" name="resume_file" required accept=".pdf,.doc,.docx" 
          class="w-full text-xs border border-gray-300 rounded-lg px-3.5 py-2 outline-none focus:border-[#C6892E] file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:bg-[#132644] file:text-white"/>
        <p class="text-[10.5px] text-gray-500 mt-1">Please ensure your CV includes educational credentials, past institutions, and references.</p>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#132644] mb-1">Cover Note / Brief Statement of Interest</label>
        <textarea name="cover_letter" rows="3" placeholder="Briefly introduce your pedagogical philosophy or motivation to join SOS Hermann Gmeiner..." 
          class="w-full text-xs border border-gray-300 rounded-lg p-3 outline-none focus:border-[#C6892E]"></textarea>
      </div>

      <div class="flex justify-end gap-3 pt-3 border-t border-gray-200">
        <button type="button" onclick="closeApplyModal()" 
          class="px-4 py-2.5 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" 
          class="px-6 py-2.5 bg-[#C6892E] hover:bg-[#b27824] text-white text-xs font-bold rounded-lg shadow-sm">Submit Application</button>
      </div>
    </form>
  </div>
</div>

<script>
function openApplyModal(job) {
  document.getElementById('apply-job-id').value = job.job_id;
  document.getElementById('modal-job-title-display').textContent = 'Applying for: ' + job.job_title + ' (' + job.department + ')';
  document.getElementById('modal-apply').classList.remove('hidden');
}

function closeApplyModal() {
  document.getElementById('modal-apply').classList.add('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
