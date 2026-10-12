<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Events & Academic Calendar Page — events.php
 * Aligned with Stitch Master Design
 */
$pageTitle     = 'School Events & Academic Calendar 2026–27';
$pageDesc      = 'Stay connected with upcoming school events, sports galas, examinations, science exhibitions, and annual parent-teacher conferences at SOS Hermann Gmeiner.';
$pageKeywords  = 'events SOS Hermann Gmeiner, academic calendar 2026-27, science fair, sports gala Dhodial';
$pageCanonical = 'http://localhost/sos/events.php';

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="pb-24 overflow-x-hidden">
  <!-- Page Header (Signature Navy Hero) -->
  <section class="relative min-h-[300px] sm:min-h-[340px] md:h-[360px] flex items-center justify-center bg-[#132644] overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-25 mix-blend-multiply" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAxNXUMO23vpmymVjEpTnTknQRP-cQTS6dglNC8Lu2b_9Y0QAFwie303_jwVZOqMTJ_Csbw2ypSRSNiSFgFAfChsLMdlZNTUJtDHt_Wzpa8BdiW0QhWp0ERlJvVxPFIY8GS0H0GmXt5UrD_6168um4ZuZE61OWKOzP8BjBjLrYNzqIE5jEZVX7R1VNPyjsGuay_mHqYMW2eQ3BcdXDIEs2fHj-aQhRKEkFoMZk6W0eh7mlnGOpZsCHX')"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#132644] via-transparent to-[#132644]/70"></div>
    <div class="relative z-10 text-center px-4 sm:px-6 max-w-3xl mx-auto py-12">
      <div class="inline-flex items-center gap-2 border border-[#C6892E]/40 bg-[#C6892E]/15 px-3.5 py-1.5 rounded-full text-[12px] font-semibold text-[#F7F4EE] mb-4 shadow-xs">
        <span class="w-2 h-2 rounded-full bg-[#C6892E]"></span>
        <span>SCHEDULE &amp; DATES · ACADEMIC CALENDAR</span>
      </div>
      <h1 class="font-serif text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-3 tracking-tight">School Events &amp; Calendar</h1>
      <p class="text-[#E0E6EF] text-[13.5px] sm:text-base max-w-2xl mx-auto leading-relaxed">Join us for upcoming activities, academic milestones, exhibitions, and community gatherings that make SOS Hermann Gmeiner special.</p>
    </div>
  </section>
  
  <div class="pt-10"></div>

  <!-- Filter Controls -->
  <section class="max-w-[1400px] mx-auto px-6 md:px-12 mb-10 flex flex-col sm:flex-row justify-between items-center gap-4 border-b border-[#EAE7DE] pb-3">
    <div class="flex gap-6">
      <button class="text-sm font-bold text-[#132644] border-b-2 border-[#132644] pb-2">Upcoming Events</button>
      <button class="text-sm font-medium text-[#54524B] hover:text-[#132644] pb-2 transition-colors">Past Events</button>
    </div>
    <a href="resources.php" class="flex items-center gap-2 text-xs font-semibold text-[#132644] hover:bg-[#F7F4EE] px-4 py-2 rounded-[6px] transition-colors border border-[#EAE7DE]">
      <span class="material-symbols-outlined text-[18px]">calendar_month</span> Download Full Calendar (PDF)
    </a>
  </section>

  <!-- Event Grid -->
  <section class="max-w-[1400px] mx-auto px-6 md:px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    
    <!-- Event 1 -->
    <article class="bg-white rounded-[6px] border border-[#EAE7DE] shadow-xs hover:shadow-md transition-all overflow-hidden flex flex-col group">
      <div class="h-48 w-full relative overflow-hidden">
        <img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" alt="Annual Science Fair" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAxNXUMO23vpmymVjEpTnTknQRP-cQTS6dglNC8Lu2b_9Y0QAFwie303_jwVZOqMTJ_Csbw2ypSRSNiSFgFAfChsLMdlZNTUJtDHt_Wzpa8BdiW0QhWp0ERlJvVxPFIY8GS0H0GmXt5UrD_6168um4ZuZE61OWKOzP8BjBjLrYNzqIE5jEZVX7R1VNPyjsGuay_mHqYMW2eQ3BcdXDIEs2fHj-aQhRKEkFoMZk6W0eh7mlnGOpZsCHX"/>
        <div class="absolute top-3 left-3 bg-[#132644] text-white rounded-[4px] px-3 py-1.5 flex flex-col items-center justify-center min-w-[56px] shadow-sm">
          <span class="text-[10px] uppercase font-bold text-[#C6892E]">Nov</span>
          <span class="font-serif text-lg font-bold leading-none">24</span>
        </div>
      </div>
      <div class="p-6 flex flex-col flex-grow">
        <h2 class="font-serif text-xl font-bold text-[#132644] mb-2 group-hover:text-[#C6892E] transition-colors">Annual Cultural &amp; Arts Exhibition</h2>
        <div class="flex items-center gap-1.5 text-xs text-[#54524B] mb-1">
          <span class="material-symbols-outlined text-[16px] text-[#C6892E]">schedule</span> 09:00 AM – 04:00 PM
        </div>
        <div class="flex items-center gap-1.5 text-xs text-[#54524B] mb-4">
          <span class="material-symbols-outlined text-[16px] text-[#C6892E]">location_on</span> Main Campus Auditorium
        </div>
        <p class="text-xs text-[#54524B] leading-relaxed mb-6 flex-grow">
          Explore innovative artistic works, traditional crafts, drama sketches, and cultural exhibits designed by students across all wings.
        </p>
        <a href="#" class="ghost-link text-xs mt-auto">
          Event Details <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
      </div>
    </article>

    <!-- Event 2 -->
    <article class="bg-white rounded-[6px] border border-[#EAE7DE] shadow-xs hover:shadow-md transition-all overflow-hidden flex flex-col group">
      <div class="h-48 w-full relative overflow-hidden">
        <img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" alt="Examinations" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDFub_RbohzEi3ejSSClo-G4tStClxXNLW1HqrLumc9drbWz1b2bZ47J5sPDoZZgI5P-hTIq6KrUZZMdM6jguILzSxa1LqIDXZJm65d32-0yEyiKzG8hyBlPPTosyErUXNdWY1cpvfvZNRYEPPYXDR-KV7mR3rj7FfnVZd9D52MP1BGmF6W5xGGcC-Sog33WAmlNr219dXPvkUSoBVzvCsr1gxQMY8m6fMGwvxEpgiVFaBvCfSc29xJ"/>
        <div class="absolute top-3 left-3 bg-[#132644] text-white rounded-[4px] px-3 py-1.5 flex flex-col items-center justify-center min-w-[56px] shadow-sm">
          <span class="text-[10px] uppercase font-bold text-[#C6892E]">Dec</span>
          <span class="font-serif text-lg font-bold leading-none">10</span>
        </div>
      </div>
      <div class="p-6 flex flex-col flex-grow">
        <h2 class="font-serif text-xl font-bold text-[#132644] mb-2 group-hover:text-[#C6892E] transition-colors">First Term Final Examinations</h2>
        <div class="flex items-center gap-1.5 text-xs text-[#54524B] mb-1">
          <span class="material-symbols-outlined text-[16px] text-[#C6892E]">schedule</span> 08:30 AM – 11:30 AM
        </div>
        <div class="flex items-center gap-1.5 text-xs text-[#54524B] mb-4">
          <span class="material-symbols-outlined text-[16px] text-[#C6892E]">location_on</span> All Examination Wings
        </div>
        <p class="text-xs text-[#54524B] leading-relaxed mb-6 flex-grow">
          Comprehensive terminal evaluations for Classes 1 through 12. Date sheets and roll number slips are published on the portal.
        </p>
        <a href="#" class="ghost-link text-xs mt-auto">
          Exam Schedule <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
      </div>
    </article>

    <!-- Event 3 -->
    <article class="bg-white rounded-[6px] border border-[#EAE7DE] shadow-xs hover:shadow-md transition-all overflow-hidden flex flex-col group">
      <div class="h-48 w-full relative overflow-hidden">
        <img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" alt="Alumni Homecoming" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkSZsS-Iqlvm4WA2I4tdngLjBR3KhU44dpiOTGAN-v4HyPc_9if4moawq8yehh0gSIm4cLrha7Tl1_6lnP7teFWAXwPxBITOhjADP70dbJORhfivcTygwyAK0G_1E-TQif_g5DTghKktZXDJhP_M01nfCzfLOMmB7WPTc3gqtN3iNl95p2_SSgGD84xHPKOpTAQcukpqJVHF-2XpKkCYb61rKOpIk59vrBSGAwF03ixJsJVia5Muwp"/>
        <div class="absolute top-3 left-3 bg-[#132644] text-white rounded-[4px] px-3 py-1.5 flex flex-col items-center justify-center min-w-[56px] shadow-sm">
          <span class="text-[10px] uppercase font-bold text-[#C6892E]">Dec</span>
          <span class="font-serif text-lg font-bold leading-none">15</span>
        </div>
      </div>
      <div class="p-6 flex flex-col flex-grow">
        <h2 class="font-serif text-xl font-bold text-[#132644] mb-2 group-hover:text-[#C6892E] transition-colors">Annual Alumni Homecoming 2026</h2>
        <div class="flex items-center gap-1.5 text-xs text-[#54524B] mb-1">
          <span class="material-symbols-outlined text-[16px] text-[#C6892E]">schedule</span> 05:00 PM Onwards
        </div>
        <div class="flex items-center gap-1.5 text-xs text-[#54524B] mb-4">
          <span class="material-symbols-outlined text-[16px] text-[#C6892E]">location_on</span> Central Campus Courtyard
        </div>
        <p class="text-xs text-[#54524B] leading-relaxed mb-6 flex-grow">
          Welcoming back graduates from all past four decades to celebrate institutional heritage, network, and mentor current students.
        </p>
        <a href="#" class="ghost-link text-xs mt-auto">
          Alumni Registration <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
      </div>
    </article>

  </section>
</main>

<?php include 'includes/footer.php'; ?>
