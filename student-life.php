<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Student Life Page — student-life.php
 * Aligned with Stitch Master Design
 */
$pageTitle     = 'Student Life — Clubs, Sports & Community';
$pageDesc      = 'Discover the vibrant student life at SOS Hermann Gmeiner School & College — parliamentary debate societies, robotics, science competitions, athletics, arts, and community service.';
$pageKeywords  = 'student life SOS Hermann Gmeiner, school sports, debate club, science olympiad, arts, Dhodial';
$pageCanonical = 'http://localhost/sos/student-life.php';

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="pb-24">
  <!-- 1. Header Banner -->
  <section class="relative h-[380px] flex items-center justify-center bg-[#132644] overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-multiply" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCLcChGFtPQ7k6vX4PiQ3lyOp46gIWKN8vqCx0XCruxlxKQvHK-fcPug23NR8E_p9XEKd7I39SufoPdSqZnVgsNNN3SDvIACIDYPaCWdcmhyTR1WFPPPMI_MDPS5YJ9dccLjDl6dXj4OynwEOS4xUH9eRTrsvSR-r7rMxNoOvETnzLIT3QAAm5Hzyc8KFETOET3GA6eeaK2t2t9QskHlR3tOeWi6o4Q-P4gYq8qKzTaU126Xw6tkLdb')"></div>
    <div class="relative z-10 text-center px-6">
      <span class="eyebrow-text block mb-2 text-[#C6892E]">Holistic Growth</span>
      <h1 class="font-serif text-3xl md:text-5xl font-bold text-white mb-2">Life Beyond the Classroom</h1>
      <p class="text-[#E0E6EF] text-base max-w-xl mx-auto">Cultivating leadership, character, teamwork, and artistic expression through dynamic extracurricular experiences.</p>
    </div>
  </section>

  <div class="max-w-[1400px] mx-auto px-6 md:px-12 py-16 space-y-20">

    <!-- 2. Core Pillars of Student Life (4 Cards) -->
    <section>
      <div class="text-center mb-12">
        <span class="eyebrow-text block mb-2">Our Activities</span>
        <h2 class="font-serif text-3xl font-bold text-[#132644] mb-3">Co-Curricular Exploration</h2>
        <p class="text-base text-[#54524B] max-w-2xl mx-auto">Every student is encouraged to participate in at least two active clubs, cultivating passions that last a lifetime.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="locked-card text-center flex flex-col items-center">
          <div class="w-12 h-12 rounded-full bg-[#132644]/10 flex items-center justify-center text-[#132644] mb-4">
            <span class="material-symbols-outlined text-2xl">record_voice_over</span>
          </div>
          <h3 class="font-serif text-lg font-bold text-[#132644] mb-2">Debate &amp; Speech</h3>
          <p class="text-xs text-[#54524B] leading-relaxed">National championship parliamentary debate society, English and Urdu declamation, Model UN participation.</p>
        </div>

        <div class="locked-card text-center flex flex-col items-center">
          <div class="w-12 h-12 rounded-full bg-[#C6892E]/15 flex items-center justify-center text-[#C6892E] mb-4">
            <span class="material-symbols-outlined text-2xl">precision_manufacturing</span>
          </div>
          <h3 class="font-serif text-lg font-bold text-[#132644] mb-2">Robotics &amp; Coding</h3>
          <p class="text-xs text-[#54524B] leading-relaxed">Hands-on micro-controller programming, LEGO robotics competitions, and STEM innovation challenges.</p>
        </div>

        <div class="locked-card text-center flex flex-col items-center">
          <div class="w-12 h-12 rounded-full bg-[#002c15]/10 flex items-center justify-center text-[#002c15] mb-4">
            <span class="material-symbols-outlined text-2xl">palette</span>
          </div>
          <h3 class="font-serif text-lg font-bold text-[#132644] mb-2">Arts &amp; Calligraphy</h3>
          <p class="text-xs text-[#54524B] leading-relaxed">Painting, sketching, Islamic calligraphy, pottery workshops, and annual public campus art exhibitions.</p>
        </div>

        <div class="locked-card text-center flex flex-col items-center">
          <div class="w-12 h-12 rounded-full bg-[#132644]/10 flex items-center justify-center text-[#132644] mb-4">
            <span class="material-symbols-outlined text-2xl">volunteer_activism</span>
          </div>
          <h3 class="font-serif text-lg font-bold text-[#132644] mb-2">Community Service</h3>
          <p class="text-xs text-[#54524B] leading-relaxed">Tree plantation drives, environmental cleanups, book collection campaigns, and SOS family mentoring.</p>
        </div>
      </div>
    </section>

    <!-- 3. Featured Highlights Grid (3 Large Photo Cards) -->
    <section>
      <div class="text-center mb-12">
        <span class="eyebrow-text block mb-2">Recent Accomplishments</span>
        <h2 class="font-serif text-3xl font-bold text-[#132644] mb-3">Student Achievements</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Highlight 1 -->
        <div class="locked-card !p-0 overflow-hidden flex flex-col group">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="Debate Champions" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDM0iNib9aDqsUcM6H919UjVYjAFHADuhGlUVI3v5x59pjSSuiZgSEyJLn1TjjmJ7fuMEwk1dNkhUsA52xe9Ota9rO_goL5QXQPegUlinGHMqMSxVXGkjVDjwyO6hH1C69Eg8JhKxb-CkqJqbQlrkP11pHifPtZWxc3deeU8OxHMFcC_WjST-uKPBFdBLJUk5dtUGyeV_mzbgom2fjMUWutZiP_j99SXOnWZPErs2Rv2kC_U7id13tf"/>
            <span class="absolute top-3 left-3 bg-[#132644]/90 text-white text-[11px] font-semibold uppercase px-2.5 py-1 rounded-[4px]">Debate Society</span>
          </div>
          <div class="p-6">
            <h3 class="font-serif text-lg font-bold text-[#132644] mb-2">National Debate Champions 2024</h3>
            <p class="text-xs text-[#54524B] leading-relaxed">Our senior parliamentary debate society secured top honors across 32 competing regional colleges in Peshawar.</p>
          </div>
        </div>

        <!-- Highlight 2 -->
        <div class="locked-card !p-0 overflow-hidden flex flex-col group">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="Science Fair" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDEkznc5d_1aHrcmSTX1HYdcWsAqAR4MtRJZbTOIEJn5zu8YIN0cyXW6wUB9NLxNgrtZJAP3T93qiRKemJKANwALTQXJHdtM1jFaw-KoGxawiVxMPB5Y27TCvjaOzo2y887jK_Ni-i5rlcd4zGDT0kmtsGriPzUO7htu8BDD3RfQ9-CyeBxByrrbCEXLFppxgXqF0gTvbvewRyZS9Bc8bBH7CiXpWVGiHUzflCihTGyEyLejI_O9iwL"/>
            <span class="absolute top-3 left-3 bg-[#132644]/90 text-white text-[11px] font-semibold uppercase px-2.5 py-1 rounded-[4px]">Science Olympiad</span>
          </div>
          <div class="p-6">
            <h3 class="font-serif text-lg font-bold text-[#132644] mb-2">Regional Science Fair Winners</h3>
            <p class="text-xs text-[#54524B] leading-relaxed">Innovative plant-genetics and robotics water purification systems engineered by Class 10 won unanimous top recognition.</p>
          </div>
        </div>

        <!-- Highlight 3 -->
        <div class="locked-card !p-0 overflow-hidden flex flex-col group">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="Sports Gala" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCLcChGFtPQ7k6vX4PiQ3lyOp46gIWKN8vqCx0XCruxlxKQvHK-fcPug23NR8E_p9XEKd7I39SufoPdSqZnVgsNNN3SDvIACIDYPaCWdcmhyTR1WFPPPMI_MDPS5YJ9dccLjDl6dXj4OynwEOS4xUH9eRTrsvSR-r7rMxNoOvETnzLIT3QAAm5Hzyc8KFETOET3GA6eeaK2t2t9QskHlR3tOeWi6o4Q-P4gYq8qKzTaU126Xw6tkLdb"/>
            <span class="absolute top-3 left-3 bg-[#132644]/90 text-white text-[11px] font-semibold uppercase px-2.5 py-1 rounded-[4px]">Athletics</span>
          </div>
          <div class="p-6">
            <h3 class="font-serif text-lg font-bold text-[#132644] mb-2">Annual Sports Gala 2024</h3>
            <p class="text-xs text-[#54524B] leading-relaxed">A high-spirited 3-day showcase of track, athletics, team football, solidarity, and physical sportsmanship.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. Athletics Program -->
    <section class="bg-[#F7F4EE] border border-[#EAE7DE] rounded-[6px] p-8 lg:p-12">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div>
          <span class="eyebrow-text block mb-2">Athletic Excellence</span>
          <h3 class="font-serif text-3xl font-bold text-[#132644] mb-4">Sports &amp; Physical Fitness</h3>
          <p class="text-sm text-[#54524B] leading-relaxed mb-4">
            Physical health and character go hand-in-hand. Our full-size football turf, cricket pitches, basketball courts, and 400m running tracks offer students daily opportunities to compete, stay fit, and learn resilience.
          </p>
          <div class="grid grid-cols-2 gap-3 text-xs font-semibold text-[#132644]">
            <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[#C6892E]">sports_soccer</span> Football &amp; Futsal</div>
            <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[#C6892E]">sports_cricket</span> Inter-School Cricket</div>
            <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[#C6892E]">sports_basketball</span> Basketball Courts</div>
            <div class="flex items-center gap-2"><span class="material-symbols-outlined text-[#C6892E]">directions_run</span> Track &amp; Field Athletics</div>
          </div>
        </div>
        <div class="rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-md h-64 md:h-80">
          <img class="w-full h-full object-cover" alt="School Sports" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAY7yktvMU3DPtizQZxAVDd9_43NUxnaGTQpAbSkoKdk0ixIbnKmdyZs2PQ4OhuLOrC7T7wEW8p9WURl6jCwopruCSQ9QP5yHKvRqmQxNtBUMADNiPJK2WcDZfs6xehEEFptsidNfesQgbP25DG7oL7UvdWPXMuNir31NIdfkJAcaHfZMn2BR7NHxDLzRvK3qU0nEKqHj3YGSFC62py_BOseCU1K1Wqu9bvIOODOmCMp5Dn6AwfK8dc"/>
        </div>
      </div>
    </section>

  </div>
</main>

<?php include 'includes/footer.php'; ?>
