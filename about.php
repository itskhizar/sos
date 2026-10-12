<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * About Us Page — about.php
 * Pixel-Perfect reproduction matching Stitch Master Design & Brand Guidelines
 */
$pageTitle     = 'About Us — Legacy, Mission & Leadership';
$pageDesc      = 'Learn about SOS Hermann Gmeiner School & College, Dhodial — our 40-year history, mission, vision, core values, leadership team, and our affiliation with SOS Children\'s Villages Pakistan.';
$pageKeywords  = 'about SOS Hermann Gmeiner School, school history Dhodial, principal message, leadership team, SOS Children\'s Villages Pakistan';
$pageCanonical = 'http://localhost/sos/about.php';

include 'includes/head.php';
include 'includes/header.php';
?>

<main>
  <!-- ================================================================
       1. HEADER BANNER (Parallax Navy with Breadcrumbs)
       ================================================================ -->
  <section class="relative min-h-[320px] lg:h-[380px] flex items-center justify-center overflow-hidden bg-[#132644]">
    <div class="absolute inset-0 bg-cover bg-center w-full h-full opacity-20 mix-blend-luminosity" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBzxTbHOPTTqWCXyhFRD_zykF1Tyo6CJlApXg76ogF1E-PRby-rbI-iLlLdiEojiuaAogUp1h_P0uRjLA3buu4Jo-00oMB-slcHHC-pm54Vvo7mHG2pO9SBOi77oeevW_4PRf8Ezq1fD58-SwK4NPsBKCTSB_T2bstwtkWwV6L48WSltr3MwuNKOynZYqekvW72_PfEoJhEuJeLUbnt60DnigwrmAPZupebkWKyK5kBxjySc-XR6QrE')"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#132644] via-transparent to-[#132644]/80"></div>
    <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
      <span class="inline-flex items-center gap-2 border border-[#C6892E]/40 bg-[#C6892E]/15 px-3.5 py-1.5 rounded-full text-xs font-semibold text-[#F7F4EE] mb-4">
        <span class="w-2 h-2 rounded-full bg-[#C6892E]"></span>
        Est. 1982 · Dhodial, Mansehra
      </span>
      <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight leading-tight">
        A Legacy of Values, Intellect &amp; Compassion
      </h1>
      <nav class="flex justify-center items-center gap-2 text-xs font-semibold text-white/80">
        <a class="hover:text-white transition-colors" href="index.php">Home</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-[#C6892E]">About Us</span>
      </nav>
    </div>
  </section>

  <!-- ================================================================
       2. FLOATING METRICS STRIP
       ================================================================ -->
  <div class="px-6 md:px-12 relative z-20">
    <div class="bg-white rounded-[8px] shadow-lg border border-[#EAE7DE] max-w-[1280px] mx-auto p-6 md:p-8 relative -mt-10 md:-mt-12 mb-16">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-0 divide-y md:divide-y-0 md:divide-x divide-[#EAE7DE] text-center">
        <div class="px-4 py-2">
          <h3 class="font-serif text-3xl lg:text-4xl font-bold text-[#132644] mb-1">42+</h3>
          <p class="text-[12px] uppercase tracking-wider text-[#54524B] font-semibold">Years of Heritage</p>
        </div>
        <div class="px-4 py-2">
          <h3 class="font-serif text-3xl lg:text-4xl font-bold text-[#132644] mb-1">1,500+</h3>
          <p class="text-[12px] uppercase tracking-wider text-[#54524B] font-semibold">Enrolled Scholars</p>
        </div>
        <div class="px-4 py-2 pt-4 md:pt-2">
          <h3 class="font-serif text-3xl lg:text-4xl font-bold text-[#132644] mb-1">100%</h3>
          <p class="text-[12px] uppercase tracking-wider text-[#54524B] font-semibold">BISE Matric Passing</p>
        </div>
        <div class="px-4 py-2 pt-4 md:pt-2">
          <h3 class="font-serif text-3xl lg:text-4xl font-bold text-[#132644] mb-1">12:1</h3>
          <p class="text-[12px] uppercase tracking-wider text-[#54524B] font-semibold">Student–Faculty Ratio</p>
        </div>
      </div>
    </div>
  </div>

  <!-- ================================================================
       3. MISSION & VISION (Side-by-Side Architectural Cards)
       ================================================================ -->
  <section class="py-16 px-6 md:px-12 max-w-[1400px] mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <!-- Mission Card -->
      <div class="bg-[#faf9f6] p-8 md:p-10 rounded-xl border border-[#EAE7DE] shadow-xs hover:border-[#132644]/30 transition-all flex flex-col justify-between">
        <div>
          <div class="w-14 h-14 bg-[#132644]/10 rounded-xl flex items-center justify-center mb-6 text-[#132644]">
            <span class="material-symbols-outlined text-[30px]">explore</span>
          </div>
          <span class="eyebrow-text block mb-1">Our Purpose</span>
          <h3 class="font-serif text-2xl font-bold text-[#132644] mb-4">Institutional Mission</h3>
          <p class="text-[15px] text-[#54524B] leading-relaxed">
            To empower students with an unshakeable academic foundation, cultivate critical and independent thinking, and instil deep moral character within a secure, family-like environment that readies young minds for higher education and responsible global citizenship.
          </p>
        </div>
        <div class="mt-6 pt-6 border-t border-[#EAE7DE]/70 flex items-center gap-2 text-xs font-bold text-[#132644]">
          <span class="material-symbols-outlined text-[16px] text-[#C6892E]">check_circle</span>
          Character · Curiosity · Resilience
        </div>
      </div>

      <!-- Vision Card -->
      <div class="bg-[#faf9f6] p-8 md:p-10 rounded-xl border border-[#EAE7DE] shadow-xs hover:border-[#C6892E]/40 transition-all flex flex-col justify-between">
        <div>
          <div class="w-14 h-14 bg-[#C6892E]/15 rounded-xl flex items-center justify-center mb-6 text-[#885200]">
            <span class="material-symbols-outlined text-[30px]">visibility</span>
          </div>
          <span class="eyebrow-text block mb-1">Our Horizon</span>
          <h3 class="font-serif text-2xl font-bold text-[#132644] mb-4">Strategic Vision</h3>
          <p class="text-[15px] text-[#54524B] leading-relaxed">
            To stand as the benchmark of educational distinction across Khyber Pakhtunkhwa, where scientific inquiry and creative ambition are seamlessly harmonized with the humanitarian spirit of Hermann Gmeiner, leaving no scholar behind.
          </p>
        </div>
        <div class="mt-6 pt-6 border-t border-[#EAE7DE]/70 flex items-center gap-2 text-xs font-bold text-[#885200]">
          <span class="material-symbols-outlined text-[16px] text-[#C6892E]">check_circle</span>
          Equity · Leadership · Intellectual Rigor
        </div>
      </div>

    </div>
  </section>

  <!-- ================================================================
       4. FOUR DECADES OF HERITAGE (Interactive Timeline)
       ================================================================ -->
  <section class="py-20 bg-[#F7F4EE] border-y border-[#EAE7DE]">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12">
      <div class="text-center mb-16 max-w-3xl mx-auto">
        <span class="eyebrow-text block mb-2">Our Heritage</span>
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-3">Our 40-Year Journey</h2>
        <p class="text-base text-[#54524B]">From humble foundations to a regional center of academic and moral excellence in Dhodial.</p>
      </div>

      <!-- Clean Grid Timeline -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Milestone 1 -->
        <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs relative flex flex-col justify-between group hover:border-[#C6892E]/50 transition-all">
          <div>
            <div class="flex items-center justify-between mb-4">
              <span class="text-lg font-serif font-bold text-[#885200] bg-[#fead4d]/15 px-3 py-1 rounded-md">1982</span>
              <span class="material-symbols-outlined text-[#747780] text-[20px]">flag</span>
            </div>
            <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">Foundation Stone</h4>
            <p class="text-xs text-[#54524B] leading-relaxed">
              Established under the auspices of SOS Children's Villages Pakistan, offering quality schooling to orphaned children and the surrounding Dhodial village community.
            </p>
          </div>
        </div>

        <!-- Milestone 2 -->
        <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs relative flex flex-col justify-between group hover:border-[#C6892E]/50 transition-all">
          <div>
            <div class="flex items-center justify-between mb-4">
              <span class="text-lg font-serif font-bold text-[#885200] bg-[#fead4d]/15 px-3 py-1 rounded-md">1995</span>
              <span class="material-symbols-outlined text-[#747780] text-[20px]">school</span>
            </div>
            <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">College Wing Expansion</h4>
            <p class="text-xs text-[#54524B] leading-relaxed">
              Expanded to intermediate science classes, introducing BISE-affiliated FSc Pre-Medical and Pre-Engineering disciplines with specialized laboratories.
            </p>
          </div>
        </div>

        <!-- Milestone 3 -->
        <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs relative flex flex-col justify-between group hover:border-[#C6892E]/50 transition-all">
          <div>
            <div class="flex items-center justify-between mb-4">
              <span class="text-lg font-serif font-bold text-[#885200] bg-[#fead4d]/15 px-3 py-1 rounded-md">2010</span>
              <span class="material-symbols-outlined text-[#747780] text-[20px]">apartment</span>
            </div>
            <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">Modernized Campus</h4>
            <p class="text-xs text-[#54524B] leading-relaxed">
              Inauguration of the expansive modern campus block with contemporary IT suites, multi-discipline science facilities, and broad athletic grounds.
            </p>
          </div>
        </div>

        <!-- Milestone 4 -->
        <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs relative flex flex-col justify-between group hover:border-[#C6892E]/50 transition-all">
          <div>
            <div class="flex items-center justify-between mb-4">
              <span class="text-lg font-serif font-bold text-[#002452] bg-[#002452]/10 px-3 py-1 rounded-md">Present</span>
              <span class="material-symbols-outlined text-[#002452] text-[20px]">star</span>
            </div>
            <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">A Proven Leader</h4>
            <p class="text-xs text-[#54524B] leading-relaxed">
              Enrolling over 1,500 scholars annually, boasting 100% board matriculation clearance, and celebrating alumni contributing to engineering, medicine, and public service.
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ================================================================
       5. CORE ETHICAL VALUES (4 Cards)
       ================================================================ -->
  <section class="py-20 px-6 md:px-12 max-w-[1400px] mx-auto">
    <div class="text-center mb-16 max-w-2xl mx-auto">
      <span class="eyebrow-text block mb-2">Our Guiding Pillars</span>
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-3">Institutional Values</h2>
      <p class="text-base text-[#54524B]">The ethical code woven into our daily curriculum and extracurricular mentorship.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white p-8 rounded-xl border border-[#EAE7DE] shadow-xs text-center flex flex-col items-center hover:-translate-y-1 transition-transform">
        <div class="w-14 h-14 rounded-full bg-[#C6892E]/15 flex items-center justify-center text-[#885200] mb-4">
          <span class="material-symbols-outlined text-[28px]">verified_user</span>
        </div>
        <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">Integrity First</h4>
        <p class="text-xs text-[#54524B] leading-relaxed">Instilling principled honesty, academic transparency, and courage to stand for moral truth.</p>
      </div>

      <div class="bg-white p-8 rounded-xl border border-[#EAE7DE] shadow-xs text-center flex flex-col items-center hover:-translate-y-1 transition-transform">
        <div class="w-14 h-14 rounded-full bg-[#132644]/10 flex items-center justify-center text-[#132644] mb-4">
          <span class="material-symbols-outlined text-[28px]">emoji_events</span>
        </div>
        <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">Academic Excellence</h4>
        <p class="text-xs text-[#54524B] leading-relaxed">Inspiring every scholar to achieve their highest intellectual and cognitive potential.</p>
      </div>

      <div class="bg-white p-8 rounded-xl border border-[#EAE7DE] shadow-xs text-center flex flex-col items-center hover:-translate-y-1 transition-transform">
        <div class="w-14 h-14 rounded-full bg-[#004424]/10 flex items-center justify-center text-[#004424] mb-4">
          <span class="material-symbols-outlined text-[28px]">favorite</span>
        </div>
        <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">Compassion &amp; Empathy</h4>
        <p class="text-xs text-[#54524B] leading-relaxed">Cultivating an inclusive, caring second-home for orphans, village scholars, and day students alike.</p>
      </div>

      <div class="bg-white p-8 rounded-xl border border-[#EAE7DE] shadow-xs text-center flex flex-col items-center hover:-translate-y-1 transition-transform">
        <div class="w-14 h-14 rounded-full bg-[#885200]/10 flex items-center justify-center text-[#885200] mb-4">
          <span class="material-symbols-outlined text-[28px]">diversity_3</span>
        </div>
        <h4 class="font-serif text-lg font-bold text-[#132644] mb-2">Community Responsibility</h4>
        <p class="text-xs text-[#54524B] leading-relaxed">Fostering deep communal awareness and duty towards civic uplift and environmental care.</p>
      </div>
    </div>
  </section>

  <!-- ================================================================
       6. PRINCIPAL'S DESK (Portrait & Philosophy)
       ================================================================ -->
  <section class="py-20 bg-[#F7F4EE] border-t border-[#EAE7DE]">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12">
      <div class="bg-white rounded-2xl border border-[#EAE7DE] p-8 lg:p-12 shadow-sm flex flex-col lg:flex-row gap-10 items-center">
        <!-- Portrait -->
        <div class="lg:w-1/3 flex flex-col items-center shrink-0">
          <div class="relative w-48 h-48 rounded-full overflow-hidden border-4 border-white shadow-lg mb-4">
            <img alt="Dr. Sarah Ahmad, Principal" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBYDI1kFZIU5XNqshzsaQdRSFTrvQpvckxxIk6VZ18aF166i59lyt9_C5_EMri3jDprNypwdZLmqo4OWyD4afi-iM0RvOKHmYbRzevGxLN3_T40IKIEYSiGtaFzDS_2OAMcFyW3QwZ77vSydW4kiTKGLFK6T2Bs6vK5DCq8k99knHXa2gWHvwED9vEemPKzKBnZSaePIwirUAoyMgAkqp0EcmWQaZRpX-wjxZ1y_VA8kZ8fq6Rf1zOE"/>
          </div>
          <div class="text-center">
            <h4 class="font-serif text-xl font-bold text-[#132644]">Dr. Sarah Ahmad</h4>
            <p class="text-xs font-semibold text-[#C6892E] mt-0.5">Principal · PhD Education, 18+ Yrs Leadership</p>
          </div>
        </div>

        <!-- Message Quote -->
        <div class="lg:w-2/3 space-y-4 text-[#54524B] text-[15px] leading-relaxed">
          <span class="eyebrow-text block mb-1">From the Principal's Desk</span>
          <h3 class="font-serif text-2xl lg:text-3xl font-bold text-[#132644] mb-3">
            "Education is the deliberate art of empowering character and purpose."
          </h3>
          <p>
            Welcome to SOS Hermann Gmeiner School &amp; College, Dhodial. For more than forty years, our campus has remained a sanctuary of learning, hope, and academic aspiration nestled in the serene foothills of Mansehra.
          </p>
          <p>
            We believe that true education transcends rote textbook memorization. It is about teaching scholars how to formulate inquiries, test hypotheses with scientific rigour, and treat one another with genuine human compassion. Every child who enters our gates is embraced as family.
          </p>
          <div class="pt-3 flex flex-wrap items-center gap-4">
            <a href="contact.php" class="bg-[#132644] text-white px-5 py-2.5 rounded-lg text-xs font-semibold hover:bg-[#001c40] transition-colors flex items-center gap-2">
              <span class="material-symbols-outlined text-[16px]">mail</span>
              Contact Principal's Office
            </a>
            <a href="academics.php" class="ghost-link text-xs">
              Explore Academic Curriculum <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================================================================
       7. LEADERSHIP & ACADEMIC GOVERNORS
       ================================================================ -->
  <section class="py-20 px-6 md:px-12 max-w-[1400px] mx-auto">
    <div class="text-center mb-16 max-w-2xl mx-auto">
      <span class="eyebrow-text block mb-2">School Governance</span>
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-3">Academic Leadership</h2>
      <p class="text-base text-[#54524B]">Experienced educationalists and administrators supervising excellence and student welfare.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs text-center">
        <div class="w-20 h-20 rounded-full bg-[#132644]/10 text-[#132644] font-serif font-bold text-2xl flex items-center justify-center mx-auto mb-4">
          SA
        </div>
        <h4 class="font-serif text-lg font-bold text-[#132644]">Dr. Sarah Ahmad</h4>
        <p class="text-xs text-[#C6892E] font-semibold mt-0.5">Principal</p>
        <p class="text-xs text-[#747780] mt-2">Overseeing strategic development, curriculum calibration, and staff mentorship.</p>
      </div>

      <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs text-center">
        <div class="w-20 h-20 rounded-full bg-[#885200]/10 text-[#885200] font-serif font-bold text-2xl flex items-center justify-center mx-auto mb-4">
          TM
        </div>
        <h4 class="font-serif text-lg font-bold text-[#132644]">Prof. Tariq Mehmood</h4>
        <p class="text-xs text-[#C6892E] font-semibold mt-0.5">Vice Principal (College)</p>
        <p class="text-xs text-[#747780] mt-2">Supervising intermediate sciences, BISE compliance, and college admissions.</p>
      </div>

      <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs text-center">
        <div class="w-20 h-20 rounded-full bg-[#004424]/10 text-[#004424] font-serif font-bold text-2xl flex items-center justify-center mx-auto mb-4">
          FN
        </div>
        <h4 class="font-serif text-lg font-bold text-[#132644]">Mrs. Fatima Noor</h4>
        <p class="text-xs text-[#C6892E] font-semibold mt-0.5">Academic Coordinator (Junior)</p>
        <p class="text-xs text-[#747780] mt-2">Directing early childhood literacy, numeracy frameworks, and faculty development.</p>
      </div>

      <div class="bg-white p-6 rounded-xl border border-[#EAE7DE] shadow-xs text-center">
        <div class="w-20 h-20 rounded-full bg-[#132644]/10 text-[#132644] font-serif font-bold text-2xl flex items-center justify-center mx-auto mb-4">
          MA
        </div>
        <h4 class="font-serif text-lg font-bold text-[#132644]">Mr. Muhammad Asif</h4>
        <p class="text-xs text-[#C6892E] font-semibold mt-0.5">Controller of Examinations</p>
        <p class="text-xs text-[#747780] mt-2">Managing terminal assessments, board certifications, and result audits.</p>
      </div>
    </div>
  </section>

  <!-- ================================================================
       8. SOS AFFILIATION BANNER (Gold & Navy CTA)
       ================================================================ -->
  <section class="py-16 bg-[#132644] text-white text-center">
    <div class="max-w-[1000px] mx-auto px-6">
      <span class="inline-flex items-center gap-2 border border-[#C6892E]/40 bg-[#C6892E]/15 px-3.5 py-1.5 rounded-full text-xs font-semibold text-[#F7F4EE] mb-4">
        SOS Children's Villages Pakistan Affiliated Project
      </span>
      <h3 class="font-serif text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-4">
        Discover Education Anchored in Real Purpose
      </h3>
      <p class="text-sm md:text-base text-[#E0E6EF] max-w-2xl mx-auto mb-8 leading-relaxed">
        Join a vibrant academic family committed to moral integrity, scientific inquiry, and purposeful leadership. Admissions for the upcoming academic session are now open.
      </p>
      <div class="flex flex-wrap gap-4 justify-center">
        <a href="admissions-apply.php" class="bg-[#C6892E] hover:bg-[#b27824] text-white px-6 py-3 rounded-lg font-semibold text-sm transition-all shadow-sm hover:scale-[1.02]">
          Apply for Admission
        </a>
        <a href="campus.php" class="border border-white/50 hover:border-white text-white px-6 py-3 rounded-lg font-semibold text-sm transition-colors hover:bg-white/10">
          Explore Campus Facilities
        </a>
      </div>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
