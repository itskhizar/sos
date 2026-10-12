<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Admissions Overview Page — admissions.php
 * Aligned with Stitch Master Design
 */
$pageTitle     = 'Admissions 2026–27 — Guidelines & Process';
$pageDesc      = 'Learn about the admission process, eligibility criteria, required documents, important dates, and entry test for SOS Hermann Gmeiner School & College, Dhodial.';
$pageKeywords  = 'admissions SOS Hermann Gmeiner, admission guidelines, eligibility criteria, entry test Dhodial, school admission 2026-27';
$pageCanonical = 'http://localhost/sos/admissions.php';

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="pb-24">
  <!-- Page Header Banner -->
  <section class="relative h-[380px] w-full flex items-center justify-center bg-[#132644] overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-multiply" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCPEA1bul2DvPr0V6qgQcPVr1OB6IWfuwyprzYps6x2ByXjHewrFeGK736LBQUu4jpqcxlcpAvdBfOM6F3HJrV_KSgjhm6G6QBnXf4aK1zeyUkVADVYB_nt6SrBTJi2FsXwwKY1oL9-E3YbseOu7SpPYK4cYYsW5f6qBtabTnLl_YUcOV5Bx55u501wOW4cHS08zfFF4jSKP4TQkcdzDho3JcbV3l4o947lWfOB9OXuJ5ZdjCqv9ZDw')"></div>
    <div class="relative z-10 text-center px-6 flex flex-col items-center gap-4">
      <div class="inline-flex items-center gap-2 bg-[#004424]/90 backdrop-blur text-[#5cb67e] px-4 py-1 rounded-full text-xs font-semibold shadow-sm border border-[#7ed99e]/30">
        <span class="material-symbols-outlined text-[16px]">check_circle</span>
        <span>Admissions Open: 2026–2027</span>
      </div>
      <h1 class="font-serif text-3xl md:text-5xl font-bold text-white tracking-tight">Admissions Guidelines</h1>
      <p class="text-[#E0E6EF] text-base md:text-lg max-w-2xl">Join a community dedicated to academic excellence, character development, and lifelong learning.</p>
      <div class="flex flex-wrap gap-4 mt-2">
        <a href="admissions-apply.php" class="bg-[#C6892E] hover:bg-[#b27824] text-white px-6 py-3 rounded-[6px] font-semibold text-sm shadow-sm transition-colors">
          Apply Online Now
        </a>
        <a href="admissions-track.php" class="border border-white/60 hover:border-white hover:bg-white/10 text-white px-6 py-3 rounded-[6px] font-medium text-sm transition-colors">
          Track Your Application
        </a>
      </div>
    </div>
  </section>

  <div class="max-w-[1400px] mx-auto px-6 md:px-12 py-16 space-y-20">

    <!-- 2. Admission Journey Stepper -->
    <section>
      <div class="text-center mb-12">
        <span class="eyebrow-text block mb-2">Step-by-step guidance</span>
        <h2 class="font-serif text-3xl font-bold text-[#132644] mb-3">The Admission Journey</h2>
        <p class="text-[15px] text-[#54524B] max-w-3xl mx-auto">A transparent, merit-based process designed to identify students who will thrive in our academic environment.</p>
      </div>
      
      <div class="relative w-full overflow-x-auto pb-4">
        <div class="flex justify-between items-start min-w-[900px] relative px-6">
          <!-- Connecting Line -->
          <div class="absolute top-6 left-16 right-16 h-[2px] bg-[#EAE7DE] z-0"></div>
          
          <!-- Steps -->
          <?php
          $journey = [
            ['description', '1. Application Submitted', 'Fill out the online application wizard.'],
            ['verified', '2. Document Verification', 'Office checks submitted credentials.'],
            ['rule', '3. Eligibility Review', 'Academic standing verification.'],
            ['edit_note', '4. Entry Test', 'Candidate sits for scheduled test.'],
            ['groups', '5. Interview & Offer', 'Orientation interview and merit list.'],
            ['how_to_reg', '6. Enrollment Confirmed', 'Fee challan paid & admission finalized.']
          ];
          foreach ($journey as $i => [$icon, $title, $desc]):
          ?>
          <div class="relative z-10 flex flex-col items-center gap-2 w-36 text-center group">
            <div class="w-12 h-12 rounded-full <?= $i === 0 ? 'bg-[#132644] text-white' : 'bg-white text-[#132644] border-2 border-[#EAE7DE]' ?> flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform">
              <span class="material-symbols-outlined text-[22px]"><?= $icon ?></span>
            </div>
            <span class="text-xs font-bold text-[#132644]"><?= $title ?></span>
            <span class="text-[11px] text-[#54524B] leading-tight"><?= $desc ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- 3. Important Dates & Eligibility -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
      
      <!-- Important Dates (Cream Card) -->
      <div class="bg-[#F7F4EE] border border-[#EAE7DE] rounded-[6px] p-8 shadow-xs">
        <span class="eyebrow-text block mb-2">Session 2026–27 Schedule</span>
        <h3 class="font-serif text-2xl font-bold text-[#132644] mb-6">Important Dates &amp; Deadlines</h3>
        
        <div class="space-y-4">
          <div class="flex items-center justify-between p-4 bg-white rounded-[6px] border border-[#EAE7DE]">
            <div>
              <h4 class="font-bold text-sm text-[#132644]">Application Registrations Open</h4>
              <p class="text-xs text-[#54524B]">Online and in-person registration starts</p>
            </div>
            <span class="text-xs font-bold text-[#C6892E] bg-[#C6892E]/10 px-3 py-1.5 rounded-full whitespace-nowrap">May 15, 2026</span>
          </div>

          <div class="flex items-center justify-between p-4 bg-white rounded-[6px] border border-[#EAE7DE]">
            <div>
              <h4 class="font-bold text-sm text-[#132644]">Application Submission Deadline</h4>
              <p class="text-xs text-[#54524B]">Last date for complete application packets</p>
            </div>
            <span class="text-xs font-bold text-red-600 bg-red-50 px-3 py-1.5 rounded-full whitespace-nowrap">July 31, 2026</span>
          </div>

          <div class="flex items-center justify-between p-4 bg-white rounded-[6px] border border-[#EAE7DE]">
            <div>
              <h4 class="font-bold text-sm text-[#132644]">Entrance Assessment Test</h4>
              <p class="text-xs text-[#54524B]">Classes 6 through 11 examinations</p>
            </div>
            <span class="text-xs font-bold text-[#132644] bg-gray-100 px-3 py-1.5 rounded-full whitespace-nowrap">August 10, 2026</span>
          </div>

          <div class="flex items-center justify-between p-4 bg-white rounded-[6px] border border-[#EAE7DE]">
            <div>
              <h4 class="font-bold text-sm text-[#132644]">Merit List Announcement</h4>
              <p class="text-xs text-[#54524B]">Published on portal &amp; campus board</p>
            </div>
            <span class="text-xs font-bold text-[#00522d] bg-green-50 px-3 py-1.5 rounded-full whitespace-nowrap">August 20, 2026</span>
          </div>
        </div>

        <div class="mt-6 pt-6 border-t border-[#EAE7DE] flex justify-between items-center">
          <span class="text-xs text-[#54524B]">Need to prepare for the test?</span>
          <a href="entry-test.php" class="ghost-link text-xs">Test Syllabus &amp; Tips <span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
        </div>
      </div>

      <!-- Eligibility Criteria -->
      <div class="bg-white border border-[#EAE7DE] rounded-[6px] p-8 shadow-xs">
        <span class="eyebrow-text block mb-2">Requirements</span>
        <h3 class="font-serif text-2xl font-bold text-[#132644] mb-6">Eligibility by Grade Level</h3>

        <div class="space-y-4">
          <div class="p-4 rounded-[6px] border border-[#EAE7DE] bg-gray-50/50">
            <h4 class="font-bold text-sm text-[#132644] mb-1">Primary Wing (Nursery to Class 5)</h4>
            <p class="text-xs text-[#54524B] leading-relaxed">Minimum age for Nursery: 3.5 years by July 31. Basic developmental and behavioral readiness assessment conducted with parents present.</p>
          </div>

          <div class="p-4 rounded-[6px] border border-[#EAE7DE] bg-gray-50/50">
            <h4 class="font-bold text-sm text-[#132644] mb-1">Middle Wing (Classes 6 to 8)</h4>
            <p class="text-xs text-[#54524B] leading-relaxed">Successful completion of previous academic grade from a recognized school. Written assessment in English, Mathematics, and Urdu.</p>
          </div>

          <div class="p-4 rounded-[6px] border border-[#EAE7DE] bg-gray-50/50">
            <h4 class="font-bold text-sm text-[#132644] mb-1">Secondary &amp; College (Classes 9 to 12)</h4>
            <p class="text-xs text-[#54524B] leading-relaxed">Minimum 60% marks in previous board examination. Science and Pre-Medical/Pre-Engineering applicants must clear the institutional entrance assessment.</p>
          </div>
        </div>

        <div class="mt-6">
          <a href="admissions-apply.php" class="w-full bg-[#C6892E] hover:bg-[#b27824] text-white py-3 rounded-[6px] font-semibold text-sm shadow-xs transition-colors text-center inline-block">
            Start Your Online Application
          </a>
        </div>
      </div>

    </div>

    <!-- 4. Required Documents Checklist -->
    <section class="bg-[#F7F4EE] border border-[#EAE7DE] rounded-[6px] p-8 lg:p-10">
      <div class="text-center max-w-2xl mx-auto mb-8">
        <span class="eyebrow-text block mb-2">Checklist</span>
        <h3 class="font-serif text-2xl font-bold text-[#132644] mb-2">Required Application Documents</h3>
        <p class="text-sm text-[#54524B]">Please keep digital copies ready to upload during your online application submission.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-[6px] border border-[#EAE7DE] flex items-start gap-3">
          <span class="material-symbols-outlined text-[#C6892E] text-[22px] shrink-0">badge</span>
          <div>
            <h4 class="text-xs font-bold text-[#132644]">Child B-Form / Birth Certificate</h4>
            <p class="text-[11px] text-[#54524B]">NADRA issued official copy</p>
          </div>
        </div>

        <div class="bg-white p-4 rounded-[6px] border border-[#EAE7DE] flex items-start gap-3">
          <span class="material-symbols-outlined text-[#C6892E] text-[22px] shrink-0">id_card</span>
          <div>
            <h4 class="text-xs font-bold text-[#132644]">Parent/Guardian CNIC</h4>
            <p class="text-[11px] text-[#54524B]">Front and back clear copy</p>
          </div>
        </div>

        <div class="bg-white p-4 rounded-[6px] border border-[#EAE7DE] flex items-start gap-3">
          <span class="material-symbols-outlined text-[#C6892E] text-[22px] shrink-0">school</span>
          <div>
            <h4 class="text-xs font-bold text-[#132644]">School Leaving Certificate</h4>
            <p class="text-[11px] text-[#54524B]">From previous institution</p>
          </div>
        </div>

        <div class="bg-white p-4 rounded-[6px] border border-[#EAE7DE] flex items-start gap-3">
          <span class="material-symbols-outlined text-[#C6892E] text-[22px] shrink-0">photo_camera</span>
          <div>
            <h4 class="text-xs font-bold text-[#132644]">Passport-size Photographs</h4>
            <p class="text-[11px] text-[#54524B]">4 recent passport photographs</p>
          </div>
        </div>
      </div>
    </section>

    <!-- 5. FAQs -->
    <section>
      <div class="text-center mb-10">
        <span class="eyebrow-text block mb-2">Got questions?</span>
        <h3 class="font-serif text-3xl font-bold text-[#132644]">Frequently Asked Questions</h3>
      </div>

      <div class="max-w-3xl mx-auto space-y-4">
        <div class="locked-card !p-5">
          <h4 class="font-serif text-base font-bold text-[#132644] mb-2">Can I save my application and complete it later?</h4>
          <p class="text-sm text-[#54524B] leading-relaxed">Yes. Our multi-step online application generates a tracking token that allows you to resume completion before final submission.</p>
        </div>

        <div class="locked-card !p-5">
          <h4 class="font-serif text-base font-bold text-[#132644] mb-2">Is there financial aid or fee concession available?</h4>
          <p class="text-sm text-[#54524B] leading-relaxed">Yes. Under the charter of SOS Children's Villages Pakistan, deserving and orphan students receive generous merit and need-based tuition waivers.</p>
        </div>

        <div class="locked-card !p-5">
          <h4 class="font-serif text-base font-bold text-[#132644] mb-2">How will I know my test results and admission status?</h4>
          <p class="text-sm text-[#54524B] leading-relaxed">You can track your real-time status online at our <a href="admissions-track.php" class="text-[#C6892E] font-semibold underline">Tracking Portal</a> anytime using your Application ID.</p>
        </div>
      </div>
    </section>

  </div>
</main>

<?php include 'includes/footer.php'; ?>
