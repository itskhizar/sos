<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Campus & Facilities Page — campus.php
 * Pixel-Perfect implementation from Stitch Master
 */
$pageTitle     = 'Discover Our World-Class Campus & Facilities';
$pageDesc      = 'Our purpose-built campus in Dhodial provides a safe, nurturing, and technologically advanced environment designed to foster academic excellence and holistic development.';
$pageKeywords  = 'SOS Hermann Gmeiner campus, smart classrooms, science labs, IT center, central library, sports complex Dhodial';
$pageCanonical = 'http://localhost/sos/campus.php';
$bodyClass     = 'bg-surface text-on-surface';

include 'includes/head.php';
include 'includes/header.php';
?>

<main>
  <!-- ================================================================
       HERO SECTION (Curved Wave Bottom)
       ================================================================ -->
  <section class="relative w-full min-h-[614px] flex flex-col justify-center bg-[#132644] overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
      <div class="absolute inset-0 bg-[#132644]/70 z-10 mix-blend-multiply"></div>
      <img alt="Campus Building" class="w-full h-full object-cover object-center" src="https://lh3.googleusercontent.com/aida-public/AB6AXuADVDucvIlbViYkYuVMSE3rY9a3ac2UbhPgJjeYVjgYX6UO73-VsSdRPSjjAi2NLMxUZMKRJ1XuOMZvLiMzJPrviHGGC9csPUNAjs3oUt0V4i6wh_scNc5KQg1-A_uKcHo7UYFydhOg3f92D6fK76pu82G3jvv3Bz2KSBG7vlQubCyt5ucRMtGq9aOpInUTNIZ65xOt59HhVjCDwB4ClSdJCFPTfdKf5RJAIVOAF8Ham9FPQQDHgqI2"/>
    </div>
    
    <div class="relative z-20 max-w-[1600px] mx-auto px-6 md:px-12 w-full py-20 text-center md:text-left">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full mb-6 text-white w-fit">
        <span class="material-symbols-outlined text-[16px]">apartment</span>
        <span class="text-[12px] uppercase tracking-wider font-semibold">CAMPUS</span>
      </div>
      <h1 class="font-serif text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-6 max-w-3xl leading-tight">
        Discover Our World-Class Campus &amp; Facilities
      </h1>
      <p class="text-base md:text-lg text-[#E0E6EF] max-w-2xl mb-8 leading-relaxed">
        Our purpose-built campus provides a safe, nurturing, and technologically advanced environment designed to foster academic excellence and holistic development.
      </p>
      <div class="flex flex-wrap gap-4 justify-center md:justify-start">
        <a href="contact.php" class="bg-white text-[#132644] hover:bg-[#F7F4EE] px-6 py-3.5 rounded-[6px] font-semibold text-[14px] shadow-sm transition-colors inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">calendar_month</span> Schedule a Campus Visit
        </a>
        <a href="admissions-apply.php" class="bg-[#C6892E] hover:bg-[#b27824] text-white px-6 py-3.5 rounded-[6px] font-semibold text-[14px] shadow-sm transition-colors inline-flex items-center gap-2">
          Apply for Admission
        </a>
      </div>
    </div>

    <!-- Bottom decorative wave/shape -->
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-20">
      <svg class="relative block w-full h-[60px] md:h-[100px]" preserveAspectRatio="none" viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg">
        <path class="fill-white" d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,119.33,195.42,110.15,237.64,104.14,281.3,85.2,321.39,56.44Z"></path>
      </svg>
    </div>
  </section>

  <!-- ================================================================
       FACILITIES GRID SECTION
       ================================================================ -->
  <section class="py-24 bg-white">
    <div class="max-w-[1600px] mx-auto px-6 md:px-12">
      <div class="text-center mb-16">
        <span class="eyebrow-text block mb-2">Our infrastructure</span>
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-4">Exceptional Spaces for Every Learner</h2>
        <p class="text-[16px] text-[#54524B] max-w-2xl mx-auto">
          Explore our comprehensive facilities designed to support a well-rounded educational journey, from state-of-the-art laboratories to expansive sports grounds.
        </p>
      </div>

      <!-- 8 Facilities Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Card 1: Classrooms -->
        <div class="group bg-white rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-xs hover:border-[#C6892E]/40 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="Modern Classroom" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuApNHZh175SccPloXWQjDHl92OdGKgYWruRINkTqaXPfJbKp32JREOqiV9oukfj7QFKKVhD8asufqers58ib8El7BqJRCUwY96eIdkGYk1dqiQ00yBTLBCPkZWQF58L026rGb1rW7MkD1l_ghUF9yFLWLtHkTrWtDpwDXxz138tZz7WhhmG0q7FSZf0b_c9rgGmy2XJZ4rTbLimgqVs81Qm4-42Xb22GfkUT9cKoB0s9ZuuWsOdkUQS"/>
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-2 rounded-[4px] shadow-sm">
              <span class="material-symbols-outlined text-[#132644] text-[20px]">menu_book</span>
            </div>
          </div>
          <div class="p-6 flex-grow flex flex-col">
            <h3 class="font-serif text-[18px] font-bold text-[#132644] mb-2">Smart Classrooms</h3>
            <p class="text-[14px] text-[#54524B] leading-relaxed">Equipped with interactive panels and ergonomic furniture to facilitate engaging, modern learning experiences.</p>
          </div>
        </div>

        <!-- Card 2: Science Labs -->
        <div class="group bg-white rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-xs hover:border-[#C6892E]/40 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="Science Laboratory" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpTpLWS4PYDZc9FZdSCap-gITKkw0pmYLxAvuY6h8sw2BmOY11pYfnrfFvwZC-L5kISLwodpe16Y8OcciOCtG9CATdN-5Y-tHTIzDNZfLEYcH9vWkeqcisECkCfODI9A0992MFlw2QpZ2GZRF-jrxVT5Mj2sWsLxXMiFPrx70IUBcajIg6KIic91xRW7v5AfyK5qQClbm-B-nEtbGG7hpuFt6zWexRGA9Xjnqxxda43-RXnU1kuIyI"/>
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-2 rounded-[4px] shadow-sm">
              <span class="material-symbols-outlined text-[#132644] text-[20px]">science</span>
            </div>
          </div>
          <div class="p-6 flex-grow flex flex-col">
            <h3 class="font-serif text-[18px] font-bold text-[#132644] mb-2">Science Labs</h3>
            <p class="text-[14px] text-[#54524B] leading-relaxed">Fully equipped Physics, Chemistry, and Biology labs fostering hands-on experimentation and discovery.</p>
          </div>
        </div>

        <!-- Card 3: IT Center -->
        <div class="group bg-white rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-xs hover:border-[#C6892E]/40 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="IT Center" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBLERhIyXWpkBf-qSV8DQxjD5jZ4A0RzaCT39hCJ3VnrRDi3BSj6gqFWNzmegopIUCwCdQDTlrkfaSarCG6hY06Y42mjSacXnStnyVpHVz6rBtGi96y6b7mN2RCfP32DApCbokc2bYKbfE5XSKUan9n9CvjGSihOZWBhbLqOMsv8bc8clVyUItucGnD8Upg1D-R8psGt1rT76YFZhjiQAMBH40AAsdxUqBaoKYIILPGDPBSOg4nbqI2"/>
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-2 rounded-[4px] shadow-sm">
              <span class="material-symbols-outlined text-[#132644] text-[20px]">computer</span>
            </div>
          </div>
          <div class="p-6 flex-grow flex flex-col">
            <h3 class="font-serif text-[18px] font-bold text-[#132644] mb-2">IT Center</h3>
            <p class="text-[14px] text-[#54524B] leading-relaxed">Advanced computing facilities with high-speed internet, dedicated to developing crucial digital literacy skills.</p>
          </div>
        </div>

        <!-- Card 4: Library -->
        <div class="group bg-white rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-xs hover:border-[#C6892E]/40 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="School Library" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDmthaPdsYZCWTtetuaWGirL_MW8ktJpWUxyNpFwwZ9wuj5NBTrh6sH1lsHgkHsEzR7fjnrkqeyWxnL3d9KF0ypZKd5vifEigZh6Dx3XLIISJ-X7UPjjZ0EaQH-AX4yotQMxWM6IjCGjfBWG8d135u1uyudb4Dm5eddC5FlVq0vy7KaPMutEyImIANwjQV4G9TOsNxWYoT4ESnzEOe5ow4SjWdNzEryafwXcR0KQL8RnP_-SuXbuxsD"/>
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-2 rounded-[4px] shadow-sm">
              <span class="material-symbols-outlined text-[#132644] text-[20px]">local_library</span>
            </div>
          </div>
          <div class="p-6 flex-grow flex flex-col">
            <h3 class="font-serif text-[18px] font-bold text-[#132644] mb-2">Central Library</h3>
            <p class="text-[14px] text-[#54524B] leading-relaxed">A vast collection of academic resources, literature, and digital archives in a serene study environment.</p>
          </div>
        </div>

        <!-- Card 5: Sports Complex -->
        <div class="group bg-white rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-xs hover:border-[#C6892E]/40 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="Sports Complex" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC6FchP3Ne2zE53aWIhXqtLBr8yw34vIIikMYDXR4_S6_ErJ-cazJkjIXtYXneWHwMGVN13FJhcK5fMwZ4NqRNyTShwMfaKygDdEAtwPTpxYl_hktsX5oOqyuS3eiIT-JngA9MzhqEKssbB2I2nc_zwsx6Pf15rrcupGJ7GxLFpS0wyIe3TP-Ai293FVpF9_B68DnByznHirD4sH4XJgmrueZE-4_56Y5BZoOInuP_wymKkzTh_jywJ"/>
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-2 rounded-[4px] shadow-sm">
              <span class="material-symbols-outlined text-[#132644] text-[20px]">sports_soccer</span>
            </div>
          </div>
          <div class="p-6 flex-grow flex flex-col">
            <h3 class="font-serif text-[18px] font-bold text-[#132644] mb-2">Sports Complex</h3>
            <p class="text-[14px] text-[#54524B] leading-relaxed">Expansive grounds and courts for football, basketball, and athletics to promote physical well-being.</p>
          </div>
        </div>

        <!-- Card 6: Auditorium -->
        <div class="group bg-white rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-xs hover:border-[#C6892E]/40 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="School Auditorium" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuATnJw7aiYLeCHFof4RPZmLKrD575iWw1U-Pm4fDwr24kjSjdRKxusS8z7DtC94TBt-89u9sCndesmRQco6BX4iPYwkwuxgYM1MpnDlzYGdR2XvLlyDxATSbdhv2fmAiPpGl1afUbr_T_ZgaJS_IPbxXXQN7TLii5RESdN4ocW-skiv3SafWQZGX7gdOlL_OysGnCKU8r3MGLWN2XVcAad_WetxmNgvxBxQmy3UBw7XFc8fkeyvZ0Cw"/>
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-2 rounded-[4px] shadow-sm">
              <span class="material-symbols-outlined text-[#132644] text-[20px]">theater_comedy</span>
            </div>
          </div>
          <div class="p-6 flex-grow flex flex-col">
            <h3 class="font-serif text-[18px] font-bold text-[#132644] mb-2">Auditorium</h3>
            <p class="text-[14px] text-[#54524B] leading-relaxed">A multi-purpose hall with professional acoustics for assemblies, performances, and community events.</p>
          </div>
        </div>

        <!-- Card 7: Medical Room -->
        <div class="group bg-white rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-xs hover:border-[#C6892E]/40 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="Medical Room" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAaIsDxseF9C2vNXGNhCYN8nAXwdpTEkI-7Be2mwx6rvVyXpdrpacLHHlOUDBk54zl3I3In1eoJA54yFfs-IVx4LEIKBplZGFVNFqEozX_t1ObCovmIRl0zoKD49AwoeFohfq9lapfosyEw5wvY-TcTyyJ9sitIKq0kxrbLKhouWGlIwSRi_lPjs1A6qhzcAjBdI0YVscIRKsMFRRef1YyxLc8WBwzRLX7SYSTOXc4S3hI2gFaOitt3"/>
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-2 rounded-[4px] shadow-sm">
              <span class="material-symbols-outlined text-[#132644] text-[20px]">medical_services</span>
            </div>
          </div>
          <div class="p-6 flex-grow flex flex-col">
            <h3 class="font-serif text-[18px] font-bold text-[#132644] mb-2">Medical Room</h3>
            <p class="text-[14px] text-[#54524B] leading-relaxed">A dedicated health center staffed by qualified professionals to ensure immediate care and student safety.</p>
          </div>
        </div>

        <!-- Card 8: Cafeteria -->
        <div class="group bg-white rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-xs hover:border-[#C6892E]/40 hover:shadow-lg transition-all duration-300 flex flex-col h-full">
          <div class="aspect-[4/3] w-full relative overflow-hidden">
            <img alt="School Cafeteria" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB216346L4hN7v-rC_pB31rB6ZJtXn49QfPzU6LwWbYj9e3Fv89TqN95O804A99Pq1ZfB32_03fQ593-3M6Y8vT59Qn8zFwQn290mP4G9v_Q8_15FzZ490nB8_GvQnB8"/>
            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm p-2 rounded-[4px] shadow-sm">
              <span class="material-symbols-outlined text-[#132644] text-[20px]">restaurant</span>
            </div>
          </div>
          <div class="p-6 flex-grow flex flex-col">
            <h3 class="font-serif text-[18px] font-bold text-[#132644] mb-2">Cafeteria</h3>
            <p class="text-[14px] text-[#54524B] leading-relaxed">A clean, spacious dining area providing nutritious, balanced meals in a hygienic environment.</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ================================================================
       SAFE & NURTURING HAVEN FOR GROWTH
       ================================================================ -->
  <section class="py-24 bg-[#F7F4EE] border-t border-[#EAE7DE]">
    <div class="max-w-[1600px] mx-auto px-6 md:px-12">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        <!-- Left text content -->
        <div>
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-[#132644]/10 rounded-full mb-4 text-[#132644] w-fit">
            <span class="material-symbols-outlined text-[16px]">verified</span>
            <span class="text-[12px] uppercase tracking-wider font-semibold">ENVIRONMENT</span>
          </div>
          <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-6 leading-tight">
            A Safe &amp; Nurturing Haven for Growth
          </h2>
          <p class="text-[15px] text-[#54524B] leading-relaxed mb-4">
            Beyond bricks and mortar, our campus is designed to be a secure, inspiring community. We maintain stringent security protocols, comprehensive CCTV coverage, and secure entry points to ensure peace of mind for parents.
          </p>
          <p class="text-[15px] text-[#54524B] leading-relaxed mb-8">
            Our landscaped gardens and open courtyards provide students with tranquil spaces to relax, socialize, and connect with nature during their breaks, fostering mental well-being alongside academic rigor.
          </p>
          
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <span class="material-symbols-outlined text-[#C6892E] text-[22px]">check_circle</span>
              <span class="text-[15px] font-medium text-[#132644]">24/7 Monitored Campus Security</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="material-symbols-outlined text-[#C6892E] text-[22px]">check_circle</span>
              <span class="text-[15px] font-medium text-[#132644]">Eco-friendly Green Spaces</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="material-symbols-outlined text-[#C6892E] text-[22px]">check_circle</span>
              <span class="text-[15px] font-medium text-[#132644]">Strict Visitor Management Protocols</span>
            </div>
          </div>
        </div>

        <!-- Right image with Holistic Well-being badge -->
        <div class="relative">
          <div class="rounded-[8px] overflow-hidden border border-[#EAE7DE] shadow-lg">
            <img alt="Students in Campus Courtyard" class="w-full h-[440px] object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkSZsS-Iqlvm4WA2I4tdngLjBR3KhU44dpiOTGAN-v4HyPc_9if4moawq8yehh0gSIm4cLrha7Tl1_6lnP7teFWAXwPxBITOhjADP70dbJORhfivcTygwyAK0G_1E-TQif_g5DTghKktZXDJhP_M01nfCzfLOMmB7WPTc3gqtN3iNl95p2_SSgGD84xHPKOpTAQcukpqJVHF-2XpKkCYb61rKOpIk59vrBSGAwF03ixJsJVia5Muwp"/>
          </div>
          <!-- Floating badge -->
          <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-md p-4 rounded-[6px] border border-[#EAE7DE] shadow-md flex items-center gap-3 max-w-xs">
            <div class="w-10 h-10 rounded-full bg-[#C6892E]/20 flex items-center justify-center text-[#C6892E] shrink-0">
              <span class="material-symbols-outlined text-[20px]">local_florist</span>
            </div>
            <div>
              <h4 class="text-[14px] font-bold text-[#132644]">Holistic Well-being</h4>
              <p class="text-[12px] text-[#54524B]">Spaces designed to inspire.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================================================================
       EXPERIENCE OUR CAMPUS FIRSTHAND (CTA)
       ================================================================ -->
  <section class="py-20 bg-white border-t border-[#EAE7DE] text-center">
    <div class="max-w-[1600px] mx-auto px-6 md:px-12">
      <h2 class="font-serif text-3xl md:text-4xl font-bold text-[#132644] mb-4">
        Experience Our Campus Firsthand
      </h2>
      <p class="text-[16px] text-[#54524B] max-w-xl mx-auto mb-8">
        We invite prospective families to tour our facilities, meet our dedicated staff, and discover what makes our learning environment truly exceptional.
      </p>
      <div class="flex flex-wrap gap-4 justify-center">
        <a href="contact.php" class="border border-[#132644] text-[#132644] hover:bg-[#132644]/5 px-6 py-3.5 rounded-[6px] font-semibold text-[14px] transition-colors inline-flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">calendar_today</span> Schedule a Campus Visit
        </a>
        <a href="admissions-apply.php" class="bg-[#C6892E] hover:bg-[#b27824] text-white px-6 py-3.5 rounded-[6px] font-semibold text-[14px] shadow-sm transition-colors inline-flex items-center gap-2">
          Apply for Admission
        </a>
      </div>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
