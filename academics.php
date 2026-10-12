<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Academics Page — academics.php
 * Aligned with Stitch Master Design
 */
$pageTitle     = 'Academics — Educational Philosophy & Curriculum';
$pageDesc      = 'Comprehensive curriculum from Nursery to College (FSc) under BISE Abbottabad, structured paths to excellence, modern teaching methodologies, and continuous assessment.';
$pageKeywords  = 'academics SOS Hermann Gmeiner, curriculum Dhodial, pre-primary, middle wing, FSc college Dhodial';
$pageCanonical = 'http://localhost/sos/academics.php';

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="pb-24">
  <!-- 1. Header Banner -->
  <section class="relative h-[360px] flex items-center justify-center bg-[#132644] overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-multiply" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDFub_RbohzEi3ejSSClo-G4tStClxXNLW1HqrLumc9drbWz1b2bZ47J5sPDoZZgI5P-hTIq6KrUZZMdM6jguILzSxa1LqIDXZJm65d32-0yEyiKzG8hyBlPPTosyErUXNdWY1cpvfvZNRYEPPYXDR-KV7mR3rj7FfnVZd9D52MP1BGmF6W5xGGcC-Sog33WAmlNr219dXPvkUSoBVzvCsr1gxQMY8m6fMGwvxEpgiVFaBvCfSc29xJ')"></div>
    <div class="relative z-10 text-center px-6">
      <span class="eyebrow-text block mb-2 text-[#C6892E]">Academic Excellence</span>
      <h1 class="font-serif text-3xl md:text-5xl font-bold text-white mb-2">Curriculum &amp; Academic Wings</h1>
      <p class="text-[#E0E6EF] text-base max-w-xl mx-auto">Nurturing minds through a comprehensive, progressive curriculum anchored in curiosity and critical rigor.</p>
    </div>
  </section>

  <!-- 2. Educational Philosophy -->
  <section class="py-20 px-6 md:px-12 max-w-[1400px] mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div>
        <span class="eyebrow-text block mb-2">Our Foundation</span>
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-4">Our Educational Philosophy</h2>
        <p class="text-base text-[#54524B] leading-relaxed mb-4">
          At SOS Hermann Gmeiner School &amp; College, we believe in fostering an environment where academic rigor meets holistic development. Our approach goes beyond traditional learning, encouraging critical thinking, creativity, and a lifelong passion for knowledge.
        </p>
        <p class="text-base text-[#54524B] leading-relaxed mb-6">
          We align our curriculum with national standards and the Board of Intermediate and Secondary Education (BISE) Abbottabad, while integrating innovative teaching methodologies to prepare students for higher university education and leadership.
        </p>
        <div class="flex gap-4">
          <a href="admissions-apply.php" class="bg-[#C6892E] hover:bg-[#b27824] text-white px-6 py-3 rounded-[6px] font-semibold text-sm transition-colors shadow-xs">
            Apply for Admission
          </a>
          <a href="campus.php" class="border border-[#132644] text-[#132644] hover:bg-[#132644]/5 px-6 py-3 rounded-[6px] font-semibold text-sm transition-colors">
            Explore Facilities
          </a>
        </div>
      </div>

      <div class="relative h-72 md:h-96 rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-md">
        <img class="w-full h-full object-cover" alt="Classroom Interaction" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4Nx_gG-ickJ4z34ljAPXEmjchaQwa_gh0s6twf6uVYWBRh2ewg8hcd9FCfoaQJDD_Jzp-MQ8C1HGXMBkwjQXXksVc_c3C6dfjvg1JrxHseWUT6YLCnuhvxEtJfwBJ8YkBx1hfJw6hnFQppyBKfsWXU4mxWGu-8xH4Ya1sHqe1RzVkpAdnqDhUmmouEEf08dDOSjzVcpAQQUirwBR3MS0L2YAPV6mVMCe9zcLuEGlSs2yZzM0tRYJV"/>
      </div>
    </div>
  </section>

  <!-- 3. Classes & Grades Bento Grid -->
  <section class="py-20 bg-[#F7F4EE] border-y border-[#EAE7DE]">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12">
      <div class="text-center mb-16">
        <span class="eyebrow-text block mb-2">Structured Progression</span>
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-3">Academic Wings &amp; Classes</h2>
        <p class="text-base text-[#54524B] max-w-xl mx-auto">Calibrated to inspire and challenge students at every stage of their development.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Pre-Primary -->
        <div class="bg-white p-6 rounded-[6px] border border-[#EAE7DE] shadow-xs flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-full bg-[#132644]/10 flex items-center justify-center mb-4 text-[#132644]">
              <span class="material-symbols-outlined text-2xl">toys</span>
            </div>
            <span class="inline-block px-3 py-1 bg-[#00522d]/10 text-[#00522d] rounded-full text-xs font-bold mb-3">Ages 3–5</span>
            <h3 class="font-serif text-xl font-bold text-[#132644] mb-2">Pre-Primary Wing</h3>
            <p class="text-xs text-[#54524B] leading-relaxed mb-4">Focuses on foundational social literacy, motor skills, and sensory play-based learning activities in Nursery and KG.</p>
          </div>
          <span class="text-xs font-semibold text-[#C6892E]">Nursery &amp; Kindergarten</span>
        </div>

        <!-- Primary -->
        <div class="bg-white p-6 rounded-[6px] border border-[#EAE7DE] shadow-xs flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-full bg-[#132644]/10 flex items-center justify-center mb-4 text-[#132644]">
              <span class="material-symbols-outlined text-2xl">menu_book</span>
            </div>
            <span class="inline-block px-3 py-1 bg-[#132644]/10 text-[#132644] rounded-full text-xs font-bold mb-3">Grades 1–5</span>
            <h3 class="font-serif text-xl font-bold text-[#132644] mb-2">Primary Wing</h3>
            <p class="text-xs text-[#54524B] leading-relaxed mb-4">Building core competencies in mathematics, science, language literacy, Islamiyat, and collaborative group inquiry.</p>
          </div>
          <span class="text-xs font-semibold text-[#C6892E]">Classes 1 through 5</span>
        </div>

        <!-- Middle -->
        <div class="bg-white p-6 rounded-[6px] border border-[#EAE7DE] shadow-xs flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-full bg-[#132644]/10 flex items-center justify-center mb-4 text-[#132644]">
              <span class="material-symbols-outlined text-2xl">science</span>
            </div>
            <span class="inline-block px-3 py-1 bg-[#C6892E]/15 text-[#885200] rounded-full text-xs font-bold mb-3">Grades 6–8</span>
            <h3 class="font-serif text-xl font-bold text-[#132644] mb-2">Middle Wing</h3>
            <p class="text-xs text-[#54524B] leading-relaxed mb-4">Subject specialization, computer science labs, analytical problem-solving, and building independent study habits.</p>
          </div>
          <span class="text-xs font-semibold text-[#C6892E]">Classes 6 through 8</span>
        </div>

        <!-- Secondary & College -->
        <div class="bg-white p-6 rounded-[6px] border border-[#EAE7DE] shadow-xs flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-full bg-[#132644]/10 flex items-center justify-center mb-4 text-[#132644]">
              <span class="material-symbols-outlined text-2xl">school</span>
            </div>
            <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold mb-3">Grades 9–12</span>
            <h3 class="font-serif text-xl font-bold text-[#132644] mb-2">College Wing</h3>
            <p class="text-xs text-[#54524B] leading-relaxed mb-4">Matriculation and FSc Pre-Medical / Pre-Engineering / Humanities streams with intensive board exam coaching.</p>
          </div>
          <span class="text-xs font-semibold text-[#C6892E]">Matric &amp; Intermediate</span>
        </div>

      </div>
    </div>
  </section>

  <!-- 4. Methodology & Assessment (2 Columns) -->
  <section class="py-20 px-6 md:px-12 max-w-[1400px] mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      
      <!-- Methodology -->
      <div>
        <span class="eyebrow-text block mb-2">How We Teach</span>
        <h2 class="font-serif text-3xl font-bold text-[#132644] mb-6">Teaching Methodology</h2>
        <div class="space-y-4">
          <div class="flex gap-4 items-start p-5 bg-white rounded-[6px] border border-[#EAE7DE] shadow-xs">
            <span class="material-symbols-outlined text-[#C6892E] text-3xl shrink-0 mt-0.5">extension</span>
            <div>
              <h4 class="font-bold text-sm text-[#132644] mb-1">Activity-Based Experiential Learning</h4>
              <p class="text-xs text-[#54524B] leading-relaxed">We move beyond rote memorization by integrating hands-on scientific experiments, classroom projects, and interactive exercises.</p>
            </div>
          </div>

          <div class="flex gap-4 items-start p-5 bg-white rounded-[6px] border border-[#EAE7DE] shadow-xs">
            <span class="material-symbols-outlined text-[#C6892E] text-3xl shrink-0 mt-0.5">devices</span>
            <div>
              <h4 class="font-bold text-sm text-[#132644] mb-1">Smart Interactive Classrooms</h4>
              <p class="text-xs text-[#54524B] leading-relaxed">Technology is seamlessly incorporated into daily lessons with digital panels, multimedia audio-visual resources, and coding labs.</p>
            </div>
          </div>

          <div class="flex gap-4 items-start p-5 bg-white rounded-[6px] border border-[#EAE7DE] shadow-xs">
            <span class="material-symbols-outlined text-[#C6892E] text-3xl shrink-0 mt-0.5">psychology</span>
            <div>
              <h4 class="font-bold text-sm text-[#132644] mb-1">Critical Thinking &amp; Socratic Inquiry</h4>
              <p class="text-xs text-[#54524B] leading-relaxed">Encouraging debate, analytical question formulation, and real-world problem solving across all subjects.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Assessment System -->
      <div>
        <span class="eyebrow-text block mb-2">Evaluation</span>
        <h2 class="font-serif text-3xl font-bold text-[#132644] mb-6">Assessment &amp; Grading</h2>
        <div class="bg-[#132644] text-white p-8 rounded-[6px] relative overflow-hidden h-full flex flex-col justify-between">
          <div class="space-y-4 relative z-10">
            <p class="text-sm text-[#E0E6EF] leading-relaxed mb-6">
              Our evaluation process is continuous and comprehensive, designed to evaluate mastery while identifying individual areas for supportive tutoring.
            </p>
            <div class="space-y-3">
              <div class="flex items-center gap-3 text-sm">
                <span class="material-symbols-outlined text-[#C6892E]">check_circle</span>
                <span>Monthly formative unit assessments</span>
              </div>
              <div class="flex items-center gap-3 text-sm">
                <span class="material-symbols-outlined text-[#C6892E]">check_circle</span>
                <span>Mid-term and Final Terminal Examinations</span>
              </div>
              <div class="flex items-center gap-3 text-sm">
                <span class="material-symbols-outlined text-[#C6892E]">check_circle</span>
                <span>Regular Parent-Teacher consultations &amp; portal tracking</span>
              </div>
              <div class="flex items-center gap-3 text-sm">
                <span class="material-symbols-outlined text-[#C6892E]">check_circle</span>
                <span>BISE Abbottabad affiliated board evaluation</span>
              </div>
            </div>
          </div>

          <div class="pt-8 border-t border-white/15 mt-6 relative z-10">
            <a href="events.php" class="inline-flex items-center gap-2 text-[#C6892E] hover:text-white font-semibold text-xs transition-colors">
              View Academic Calendar 2026–27 <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
          </div>
        </div>
      </div>

    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
