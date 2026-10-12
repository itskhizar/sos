<?php
/**
 * SOS Hermann Gmeiner Public School & College Dhodial
 * Contact Us Page — contact.php
 * Aligned with Stitch Master Design & Signature Navy Hero
 */
$pageTitle     = 'Contact Us — Connect with Our Campus';
$pageDesc      = 'Get in touch with SOS Hermann Gmeiner Public School & College Dhodial. Campus visits, admissions desk, email inquiries, and location map.';
$pageKeywords  = 'contact SOS Hermann Gmeiner Public School & College Dhodial, school location Dhodial, admissions office phone, campus map';
$pageCanonical = 'http://localhost/sos/contact.php';

include 'includes/head.php';
include 'includes/header.php';
?>

<main class="pb-16 sm:pb-24 overflow-x-hidden">
  <!-- Page Header (Signature Navy Hero) -->
  <section class="relative min-h-[300px] sm:min-h-[340px] md:h-[360px] flex items-center justify-center bg-[#132644] overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-25 mix-blend-multiply" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDFub_RbohzEi3ejSSClo-G4tStClxXNLW1HqrLumc9drbWz1b2bZ47J5sPDoZZgI5P-hTIq6KrUZZMdM6jguILzSxa1LqIDXZJm65d32-0yEyiKzG8hyBlPPTosyErUXNdWY1cpvfvZNRYEPPYXDR-KV7mR3rj7FfnVZd9D52MP1BGmF6W5xGGcC-Sog33WAmlNr219dXPvkUSoBVzvCsr1gxQMY8m6fMGwvxEpgiVFaBvCfSc29xJ')"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#132644] via-transparent to-[#132644]/70"></div>
    <div class="relative z-10 text-center px-4 sm:px-6 max-w-3xl mx-auto py-12">
      <div class="inline-flex items-center gap-2 border border-[#C6892E]/40 bg-[#C6892E]/15 px-3.5 py-1.5 rounded-full text-[12px] font-semibold text-[#F7F4EE] mb-4 shadow-xs">
        <span class="w-2 h-2 rounded-full bg-[#C6892E]"></span>
        <span>GET IN TOUCH · DHODIAL CAMPUS</span>
      </div>
      <h1 class="font-serif text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-3 tracking-tight">Connect with Our Campus</h1>
      <p class="text-[#E0E6EF] text-[13.5px] sm:text-base max-w-xl mx-auto leading-relaxed">Have questions regarding curriculum, admissions, or campus visits? Leave a note for our administrative desk or visit us in Dhodial.</p>
    </div>
  </section>

  <div class="max-w-[1400px] mx-auto px-4 sm:px-6 md:px-12 py-10 sm:py-16 space-y-12 sm:space-y-16">
    
    <!-- 4 Contact Cards (Responsive Grid) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
      <div class="locked-card text-center flex flex-col items-center p-5 sm:p-6">
        <div class="w-12 h-12 rounded-full bg-[#132644]/10 text-[#132644] flex items-center justify-center mb-3 shrink-0">
          <span class="material-symbols-outlined text-2xl">location_on</span>
        </div>
        <h3 class="font-serif text-base font-bold text-[#132644] mb-1">Campus Address</h3>
        <p class="text-xs text-[#54524B] leading-relaxed">SOS Hermann Gmeiner Public School &amp; College, Dhodial, Mansehra, KP, Pakistan</p>
      </div>

      <div class="locked-card text-center flex flex-col items-center p-5 sm:p-6">
        <div class="w-12 h-12 rounded-full bg-[#C6892E]/15 text-[#C6892E] flex items-center justify-center mb-3 shrink-0">
          <span class="material-symbols-outlined text-2xl">call</span>
        </div>
        <h3 class="font-serif text-base font-bold text-[#132644] mb-1">Phone Numbers</h3>
        <p class="text-xs text-[#54524B] leading-relaxed">+92 (997) 530-221<br/>+92 (234) 567-8900</p>
      </div>

      <div class="locked-card text-center flex flex-col items-center p-5 sm:p-6">
        <div class="w-12 h-12 rounded-full bg-[#002c15]/10 text-[#002c15] flex items-center justify-center mb-3 shrink-0">
          <span class="material-symbols-outlined text-2xl">mail</span>
        </div>
        <h3 class="font-serif text-base font-bold text-[#132644] mb-1">Email Inquiries</h3>
        <p class="text-xs text-[#54524B] leading-relaxed">admissions@soshg.edu<br/>info@soshg.edu</p>
      </div>

      <div class="locked-card text-center flex flex-col items-center p-5 sm:p-6">
        <div class="w-12 h-12 rounded-full bg-[#132644]/10 text-[#132644] flex items-center justify-center mb-3 shrink-0">
          <span class="material-symbols-outlined text-2xl">schedule</span>
        </div>
        <h3 class="font-serif text-base font-bold text-[#132644] mb-1">Office Hours</h3>
        <p class="text-xs text-[#54524B] leading-relaxed">Monday – Friday: 8:00 AM – 3:30 PM<br/>Saturday: 8:00 AM – 1:00 PM</p>
      </div>
    </div>

    <!-- Map & Form Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 bg-white rounded-[6px] border border-[#EAE7DE] p-5 sm:p-8 lg:p-12 shadow-xs">
      
      <!-- Left: Map + Details -->
      <div class="flex flex-col justify-between h-full space-y-6">
        <div>
          <span class="eyebrow-text block mb-2">Location</span>
          <h2 class="font-serif text-xl sm:text-2xl md:text-3xl font-bold text-[#132644] mb-3">Find Us in Dhodial</h2>
          <p class="text-sm text-[#54524B] leading-relaxed mb-6">
            Our expansive campus is conveniently situated on the Karakoram Highway route in Dhodial, Mansehra, easily accessible for local student transport services.
          </p>
        </div>

        <div class="w-full h-64 sm:h-80 bg-[#F7F4EE] rounded-[6px] overflow-hidden relative border border-[#EAE7DE]">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3298.7!2d73.1890!3d34.3560!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDIxJzIxLjYiTiA3M8KwMTEnMjAuNCJF!5e0!3m2!1sen!2spk!4v1698246375000!5m2!1sen!2s" style="border:0; width:100%; height:100%;" allowfullscreen="" loading="lazy" title="Campus Location Map"></iframe>
        </div>
      </div>

      <!-- Right: Form -->
      <div class="bg-[#F7F4EE] p-5 sm:p-8 rounded-[6px] border border-[#EAE7DE]">
        <?php if (isset($_GET['submitted'])): ?>
          <div class="mb-4 p-4 rounded bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            Thank you! Your enquiry has been received. Our administrative desk will contact you shortly.
          </div>
        <?php endif; ?>
        <h3 class="font-serif text-xl sm:text-2xl font-bold text-[#132644] mb-2">Send an Enquiry</h3>
        <p class="text-xs text-[#54524B] mb-6">Fill out the message form below and our administrative desk will respond within 24 hours.</p>

        <form action="process.php" method="post" class="space-y-4" id="contact-page-form">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="c_firstName">First name <span class="text-red-500">*</span></label>
              <input class="w-full rounded-[6px] border-[#EAE7DE] bg-white text-sm p-2.5 sm:p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644]" id="c_firstName" name="first_name" placeholder="Ahmad" type="text" required/>
            </div>
            <div>
              <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="c_lastName">Last name <span class="text-red-500">*</span></label>
              <input class="w-full rounded-[6px] border-[#EAE7DE] bg-white text-sm p-2.5 sm:p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644]" id="c_lastName" name="last_name" placeholder="Khan" type="text" required/>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="c_email">Email address <span class="text-red-500">*</span></label>
            <input class="w-full rounded-[6px] border-[#EAE7DE] bg-white text-sm p-2.5 sm:p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644]" id="c_email" name="email" placeholder="parent@example.com" type="email" required/>
          </div>

          <div>
            <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="c_phone">Phone number</label>
            <input class="w-full rounded-[6px] border-[#EAE7DE] bg-white text-sm p-2.5 sm:p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644]" id="c_phone" name="phone" placeholder="+92 300 1234567" type="tel"/>
          </div>

          <div>
            <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="c_dept">Area of Interest</label>
            <select class="w-full rounded-[6px] border-[#EAE7DE] bg-white text-sm p-2.5 sm:p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644]" id="c_dept" name="interest">
              <option value="admissions">Admissions Inquiry</option>
              <option value="academics">Academic Syllabus &amp; Wings</option>
              <option value="fees">Fee Structures &amp; Scholarships</option>
              <option value="careers">Careers &amp; Faculty Vacancies</option>
              <option value="general">General Tour / Other</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-[#1C1C1A] mb-1.5" for="c_message">Message <span class="text-red-500">*</span></label>
            <textarea class="w-full rounded-[6px] border-[#EAE7DE] bg-white text-sm p-2.5 sm:p-3 focus:border-[#132644] focus:ring-1 focus:ring-[#132644]" id="c_message" name="message" placeholder="How can our administration assist you?" rows="4" required></textarea>
          </div>

          <!-- Respecting hierarchy: Navy for form submission -->
          <button class="w-full bg-[#132644] hover:bg-[#0C1B30] text-white py-2.5 sm:py-3.5 rounded-[6px] font-semibold text-[13.5px] sm:text-sm transition-colors shadow-xs" type="submit">
            Submit Message
          </button>
          <input type="hidden" name="contact_submission" value="1"/>
        </form>
      </div>

    </div>

  </div>
</main>

<?php include 'includes/footer.php'; ?>
