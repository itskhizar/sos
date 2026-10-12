<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Global Footer Partial — includes/footer.php
 */
if (!isset($siteUrl)) {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    if (basename($dir) === 'dashboard') {
        $dir = rtrim(dirname($dir), '/\\');
    }
    $siteUrl = $protocol . '://' . $host . $dir;
}
?>
<!-- Footer Navigation (Navy Background #132644, 96px padding) -->
<footer class="bg-[#132644] text-white py-20 lg:py-24 border-t border-white/10">
  <div class="max-w-[1600px] mx-auto px-6 md:px-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
      
      <!-- Col 1: Brand + Affiliation Lockup -->
      <div class="lg:col-span-1">
        <div class="flex items-center gap-3 mb-4">
          <img alt="SOS Hermann Gmeiner School Logo" class="h-[38px] w-[38px] rounded-lg shrink-0 object-contain shadow-sm bg-white p-1" src="<?= $siteUrl ?>/assets/images/logo.jpg"/>
          <div>
            <h2 class="font-serif text-[18px] font-bold text-white leading-tight">Hermann Gmeiner School</h2>
            <p class="text-[12px] text-[#A6BCD8]">&amp; College · Dhodial</p>
          </div>
        </div>
        <p class="text-[14px] text-[#A6BCD8] leading-relaxed mb-6">
          A project of SOS Children's Villages Pakistan. Empowering students through quality education, character, and lifelong leadership since 1982.
        </p>
        <div class="flex gap-3">
          <a aria-label="Website" class="w-9 h-9 rounded-[4px] bg-white/10 flex items-center justify-center text-white hover:bg-[#C6892E] transition-colors" href="<?= $siteUrl ?>/index.php">
            <span class="material-symbols-outlined text-[18px]">public</span>
          </a>
          <a aria-label="Email" class="w-9 h-9 rounded-[4px] bg-white/10 flex items-center justify-center text-white hover:bg-[#C6892E] transition-colors" href="mailto:admissions@soshg.edu">
            <span class="material-symbols-outlined text-[18px]">mail</span>
          </a>
          <a aria-label="Call" class="w-9 h-9 rounded-[4px] bg-white/10 flex items-center justify-center text-white hover:bg-[#C6892E] transition-colors" href="tel:+922345678900">
            <span class="material-symbols-outlined text-[18px]">call</span>
          </a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div>
        <h4 class="text-[13px] font-bold text-[#C6892E] uppercase tracking-wider mb-5">Quick Links</h4>
        <ul class="space-y-3 text-[14px]">
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/about.php">About the School</a></li>
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/academics.php">Academic Wings</a></li>
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/admissions.php">Admission Guidelines</a></li>
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/campus.php">Campus &amp; Facilities</a></li>
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/careers.php">Careers &amp; Faculty Vacancies</a></li>
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/student-life.php">Student Achievements</a></li>
        </ul>
      </div>

      <!-- Col 3: Resources -->
      <div>
        <h4 class="text-[13px] font-bold text-[#C6892E] uppercase tracking-wider mb-5">Resources</h4>
        <ul class="space-y-3 text-[14px]">
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/dashboard/index.php">Student &amp; Parent Portal</a></li>
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/entry-test.php">Entry Test Information</a></li>
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/admissions-track.php">Track Application Status</a></li>
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/events.php">Academic Calendar &amp; Events</a></li>
          <li><a class="text-[#E0E6EF] hover:text-[#C6892E] transition-colors" href="<?= $siteUrl ?>/gallery.php">Photo &amp; Campus Gallery</a></li>
        </ul>
      </div>

      <!-- Col 4: Contact Us -->
      <div>
        <h4 class="text-[13px] font-bold text-[#C6892E] uppercase tracking-wider mb-5">Contact Us</h4>
        <ul class="space-y-3 text-[14px] text-[#E0E6EF]">
          <li class="flex items-start gap-2.5">
            <span class="material-symbols-outlined text-[18px] text-[#C6892E] shrink-0 mt-0.5">location_on</span>
            <span>Dhodial, Mansehra, Khyber Pakhtunkhwa, Pakistan</span>
          </li>
          <li class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-[18px] text-[#C6892E] shrink-0">phone</span>
            <a href="tel:+922345678900" class="hover:text-[#C6892E] transition-colors">+92 (234) 567-8900</a>
          </li>
          <li class="flex items-center gap-2.5">
            <span class="material-symbols-outlined text-[18px] text-[#C6892E] shrink-0">mail</span>
            <a href="mailto:admissions@soshg.edu" class="hover:text-[#C6892E] transition-colors">admissions@soshg.edu</a>
          </li>
          <li class="flex items-center gap-2.5 pt-2">
            <a href="<?= $siteUrl ?>/contact.php" class="inline-flex items-center gap-1.5 text-[#C6892E] hover:text-white font-semibold text-[13px] transition-colors">
              Campus Visit Schedule <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
          </li>
        </ul>
      </div>

    </div>

    <!-- Footer Bottom Bar -->
    <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-[13px] text-[#A6BCD8]">
      <p class="text-center md:text-left">
        &copy; <?= date('Y') ?> SOS Hermann Gmeiner School &amp; College · Dhodial. A project of SOS Children's Villages Pakistan.
      </p>
      <div class="flex gap-5">
        <a class="hover:text-white transition-colors" href="<?= $siteUrl ?>/privacy-policy.php">Privacy Policy</a>
        <span>·</span>
        <a class="hover:text-white transition-colors" href="<?= $siteUrl ?>/terms.php">Terms of Admission</a>
        <span>·</span>
        <a class="hover:text-white transition-colors" href="<?= $siteUrl ?>/sitemap.xml">Sitemap</a>
      </div>
    </div>
  </div>
</footer>

<!-- Optional Quick Back-To-Top Button -->
<button id="back-to-top" aria-label="Back to top" class="fixed bottom-6 right-6 w-11 h-11 bg-[#132644] text-white border border-white/20 rounded-full shadow-lg items-center justify-center hidden hover:bg-[#C6892E] transition-all z-50">
  <span class="material-symbols-outlined text-[20px]">arrow_upward</span>
</button>

<script>
window.addEventListener('scroll', () => {
  const btn = document.getElementById('back-to-top');
  if (btn) {
    if (window.scrollY > 400) {
      btn.classList.remove('hidden');
      btn.classList.add('flex');
    } else {
      btn.classList.add('hidden');
      btn.classList.remove('flex');
    }
  }
});
document.getElementById('back-to-top')?.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>

</body>
</html>
