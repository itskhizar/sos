<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * News & Announcements Page — news.php
 * Aligned with Stitch Master Design
 */
$pageTitle     = 'News & Announcements — Campus Updates';
$pageDesc      = 'Stay informed with the latest updates, academic milestones, and essential notices from the SOS Hermann Gmeiner community, Dhodial.';
$pageKeywords  = 'news SOS Hermann Gmeiner, school announcements, admissions news, exam notices, Dhodial notices';
$pageCanonical = 'http://localhost/sos/news.php';

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="pb-24 overflow-x-hidden">
  <!-- Page Header (Signature Navy Hero) -->
  <section class="relative min-h-[300px] sm:min-h-[340px] md:h-[360px] flex items-center justify-center bg-[#132644] overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-25 mix-blend-multiply" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCFTRTEoQ3wvGd7OHTKXYvPUv5cK4K0X-36dWdqeJp_ps2eTb2Yt5jTfttxg-EYv5cEjZYSW8mchALnK3uEY8zNRls3tDGcYNRBL5sxmmKL8YN8eDkms9J8zcgL_cuhLLqYx65ScO-pTJED7uUrNkBbuICw9DZ95p88GTRltr-jvc3ExxPMwcGNeb1RBahMUFWdWfkODjT5S-zQBSuttasrPChzsNDZiP4MdGMWui185RJRW-2vvubN')"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#132644] via-transparent to-[#132644]/70"></div>
    <div class="relative z-10 text-center px-4 sm:px-6 max-w-3xl mx-auto py-12">
      <div class="inline-flex items-center gap-2 border border-[#C6892E]/40 bg-[#C6892E]/15 px-3.5 py-1.5 rounded-full text-[12px] font-semibold text-[#F7F4EE] mb-4 shadow-xs">
        <span class="w-2 h-2 rounded-full bg-[#C6892E]"></span>
        <span>UPDATES &amp; NOTICES · CAMPUS BULLETINS</span>
      </div>
      <h1 class="font-serif text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-3 tracking-tight">News &amp; Announcements</h1>
      <p class="text-[#E0E6EF] text-[13.5px] sm:text-base max-w-2xl mx-auto leading-relaxed">Stay informed with the latest updates, academic milestones, circulars, and essential notices from the SOS Hermann Gmeiner community.</p>
    </div>
  </section>

  <!-- Main Content Layout (2 Columns) -->
  <div class="max-w-[1400px] mx-auto px-6 md:px-12 py-16">
    <div class="flex flex-col lg:flex-row gap-12">
      
      <!-- Left Column: News Feed -->
      <div class="flex-1 w-full lg:w-2/3">
        <!-- Category Filter Tabs -->
        <div class="flex overflow-x-auto gap-3 pb-3 mb-8 border-b border-[#EAE7DE]">
          <button class="px-4 py-2 text-xs font-bold text-[#132644] border-b-2 border-[#132644] whitespace-nowrap">All Updates</button>
          <button class="px-4 py-2 text-xs font-medium text-[#54524B] hover:text-[#132644] transition-colors whitespace-nowrap">Admissions</button>
          <button class="px-4 py-2 text-xs font-medium text-[#54524B] hover:text-[#132644] transition-colors whitespace-nowrap">Academics</button>
          <button class="px-4 py-2 text-xs font-medium text-[#54524B] hover:text-[#132644] transition-colors whitespace-nowrap">Exam Notices</button>
          <button class="px-4 py-2 text-xs font-medium text-[#54524B] hover:text-[#132644] transition-colors whitespace-nowrap">General</button>
        </div>

        <!-- Featured News Card -->
        <article class="bg-white rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-sm mb-10 group">
          <div class="w-full h-64 md:h-80 relative overflow-hidden">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Admissions Announcement" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCFTRTEoQ3wvGd7OHTKXYvPUv5cK4K0X-36dWdqeJp_ps2eTb2Yt5jTfttxg-EYv5cEjZYSW8mchALnK3uEY8zNRls3tDGcYNRBL5sxmmKL8YN8eDkms9J8zcgL_cuhLLqYx65ScO-pTJED7uUrNkBbuICw9DZ95p88GTRltr-jvc3ExxPMwcGNeb1RBahMUFWdWfkODjT5S-zQBSuttasrPChzsNDZiP4MdGMWui185RJRW-2vvubN"/>
            <div class="absolute top-4 left-4 bg-white/95 backdrop-blur px-3 py-1 rounded text-xs font-bold text-[#132644] uppercase shadow-xs">
              Featured Notice
            </div>
          </div>
          <div class="p-8">
            <div class="flex items-center gap-3 text-xs text-[#54524B] mb-2">
              <span class="material-symbols-outlined text-[16px] text-[#C6892E]">calendar_today</span>
              <time>May 15, 2026</time>
              <span>·</span>
              <span class="text-[#C6892E] font-semibold">Admissions</span>
            </div>
            <h2 class="font-serif text-2xl font-bold text-[#132644] mb-3 group-hover:text-[#C6892E] transition-colors leading-snug">
              Admissions for Session 2026–27 Now Open Across All Grades
            </h2>
            <p class="text-sm text-[#54524B] leading-relaxed mb-6">
              Registrations are officially open for academic session 2026–27 from Nursery to FSc. Prospective parents and guardians can now submit forms through our newly launched digital portal or obtain physical admission packets from the campus admissions office.
            </p>
            <a href="admissions-apply.php" class="ghost-link text-sm">
              Apply for Admission <span class="material-symbols-outlined">arrow_forward</span>
            </a>
          </div>
        </article>

        <!-- News Feed Grid -->
        <div class="space-y-6">
          
          <!-- News Row 1 -->
          <div class="p-6 bg-white rounded-[6px] border border-[#EAE7DE] shadow-xs flex flex-col sm:flex-row gap-5 items-start group hover:border-[#C6892E]/40 transition-all">
            <img class="w-full sm:w-28 h-28 rounded-[4px] object-cover shrink-0" alt="STEM Lab" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhfs7C0Ci_yUwJ4K8Gwk394q2c2PZxLcefj4x1iL3VS-1MQAWFNtwVQ91af6pB_Z8dreYP8yHw-93j0OaxFP5wiOMdA0SNNH1n2Im8cII1XDchcf16Ko7Xa2BbOvPzG0ZNqETKW8vFsZsdq4HtAd7pExWJPlq3Ut5Aq5Cf4FZkgk15GnullCQFkIzOhcbqlwggPjCjmB1e32neynhVYk_djFQ_nJ22BtUG98joaSbdEsCe8Z4D0vB6"/>
            <div class="flex-grow">
              <div class="flex items-center gap-3 text-xs text-[#54524B] mb-1">
                <span>May 02, 2026</span>
                <span>·</span>
                <span class="text-[#C6892E] font-semibold">Campus</span>
              </div>
              <h3 class="font-serif text-lg font-bold text-[#132644] group-hover:text-[#C6892E] transition-colors mb-1">
                Inauguration of New STEM Exploration &amp; Robotics Center
              </h3>
              <p class="text-xs text-[#54524B] leading-relaxed line-clamp-2">
                Equipped with cutting-edge 3D printing equipment, coding pods, and experiential physics workstations to foster early engineering inquiry.
              </p>
            </div>
          </div>

          <!-- News Row 2 -->
          <div class="p-6 bg-white rounded-[6px] border border-[#EAE7DE] shadow-xs flex flex-col sm:flex-row gap-5 items-start group hover:border-[#C6892E]/40 transition-all">
            <img class="w-full sm:w-28 h-28 rounded-[4px] object-cover shrink-0" alt="PTM Conference" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBn_EJ3wtGP_n4UgT0G1kKkrc3SvNxUJXHNjv7RSaD05EVr7WykOtWZGU2MVfGNTyvDWi5I_tBzW90Uptl_7egKoIxwoNMVSa7L98gTQcX_v7Tqzj8kHPkHdR-9oafXqFMXUP6VPKvTiLTOzvy-SgSc0CLYDXZR2_wt413d2qRd8PrlTQofB-k6yMTGduoppdjx4031P478aH_0FIlAI-T9Gw4gr_4dEC8mnIOgdAJ-rBvKZ3dg2YGE"/>
            <div class="flex-grow">
              <div class="flex items-center gap-3 text-xs text-[#54524B] mb-1">
                <span>April 24, 2026</span>
                <span>·</span>
                <span class="text-[#C6892E] font-semibold">Academics</span>
              </div>
              <h3 class="font-serif text-lg font-bold text-[#132644] group-hover:text-[#C6892E] transition-colors mb-1">
                Term-End Parent-Teacher Conference Schedule Published
              </h3>
              <p class="text-xs text-[#54524B] leading-relaxed line-clamp-2">
                Individual progress consultations with homeroom faculty and subject teachers are now open for scheduling through the school portal.
              </p>
            </div>
          </div>

          <!-- News Row 3 -->
          <div class="p-6 bg-white rounded-[6px] border border-[#EAE7DE] shadow-xs flex flex-col sm:flex-row gap-5 items-start group hover:border-[#C6892E]/40 transition-all">
            <img class="w-full sm:w-28 h-28 rounded-[4px] object-cover shrink-0" alt="Sports Awards" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCLcChGFtPQ7k6vX4PiQ3lyOp46gIWKN8vqCx0XCruxlxKQvHK-fcPug23NR8E_p9XEKd7I39SufoPdSqZnVgsNNN3SDvIACIDYPaCWdcmhyTR1WFPPPMI_MDPS5YJ9dccLjDl6dXj4OynwEOS4xUH9eRTrsvSR-r7rMxNoOvETnzLIT3QAAm5Hzyc8KFETOET3GA6eeaK2t2t9QskHlR3tOeWi6o4Q-P4gYq8qKzTaU126Xw6tkLdb"/>
            <div class="flex-grow">
              <div class="flex items-center gap-3 text-xs text-[#54524B] mb-1">
                <span>April 12, 2026</span>
                <span>·</span>
                <span class="text-[#C6892E] font-semibold">Sports</span>
              </div>
              <h3 class="font-serif text-lg font-bold text-[#132644] group-hover:text-[#C6892E] transition-colors mb-1">
                Annual Athletics Gala Concludes with Record Turnout
              </h3>
              <p class="text-xs text-[#54524B] leading-relaxed line-clamp-2">
                Jinnah House claims the 2026 Overall Championship Trophy following 3 days of high-spirited track, football, and athletics competitions.
              </p>
            </div>
          </div>

        </div>
      </div>

      <!-- Right Column: Notice Board & Circulars -->
      <div class="w-full lg:w-1/3 space-y-8">
        
        <!-- Official Notice Board -->
        <div class="bg-[#F7F4EE] border border-[#EAE7DE] rounded-[6px] p-6">
          <h3 class="font-serif text-lg font-bold text-[#132644] mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[#C6892E]">campaign</span>
            Official Notice Board
          </h3>
          <div class="space-y-4">
            <div class="p-3 bg-white rounded border border-[#EAE7DE]">
              <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded">URGENT</span>
              <h4 class="font-bold text-xs text-[#132644] mt-1.5 mb-1">Summer Vacation Timings &amp; Dress Code</h4>
              <p class="text-[11px] text-[#54524B]">Morning assembly shifted to 07:45 AM due to seasonal temperatures.</p>
            </div>
            <div class="p-3 bg-white rounded border border-[#EAE7DE]">
              <span class="text-[10px] font-bold text-[#00522d] bg-green-50 px-2 py-0.5 rounded">CIRCULAR</span>
              <h4 class="font-bold text-xs text-[#132644] mt-1.5 mb-1">BISE Matriculation Roll No. Slips</h4>
              <p class="text-[11px] text-[#54524B]">Class 9 and 10 students may collect official exam slips from the admin desk.</p>
            </div>
          </div>
        </div>

        <!-- Quick Links Card -->
        <div class="bg-white border border-[#EAE7DE] rounded-[6px] p-6 shadow-xs">
          <h4 class="font-serif text-base font-bold text-[#132644] mb-3">Academic Quick Links</h4>
          <ul class="space-y-2.5 text-xs text-[#54524B]">
            <li><a href="events.php" class="hover:text-[#C6892E] flex items-center gap-2"><span class="material-symbols-outlined text-sm">calendar_month</span> Academic Calendar 2026–27</a></li>
            <li><a href="admissions.php" class="hover:text-[#C6892E] flex items-center gap-2"><span class="material-symbols-outlined text-sm">assignment</span> Admission Requirements</a></li>
            <li><a href="dashboard/index.php" class="hover:text-[#C6892E] flex items-center gap-2"><span class="material-symbols-outlined text-sm">portal</span> Parent &amp; Student Portal</a></li>
          </ul>
        </div>

      </div>

    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
