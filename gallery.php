<?php
/**
 * SOS Hermann Gmeiner School & College · Dhodial
 * Campus Photo Gallery — gallery.php
 * Aligned with Stitch Master Design
 */
$pageTitle     = 'Photo & Campus Gallery — Life at Dhodial';
$pageDesc      = 'Explore our rich photo gallery capturing moments across campus architecture, scientific laboratories, sports grounds, arts exhibitions, and annual events.';
$pageKeywords  = 'gallery SOS Hermann Gmeiner, school photos Dhodial, campus pictures, student moments';
$pageCanonical = 'http://localhost/sos/gallery.php';

include 'includes/head.php';
include 'includes/header.php';

$galleryItems = [
  ['Campus & Architecture', 'Central Academic Wing & Lawns', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDkSZsS-Iqlvm4WA2I4tdngLjBR3KhU44dpiOTGAN-v4HyPc_9if4moawq8yehh0gSIm4cLrha7Tl1_6lnP7teFWAXwPxBITOhjADP70dbJORhfivcTygwyAK0G_1E-TQif_g5DTghKktZXDJhP_M01nfCzfLOMmB7WPTc3gqtN3iNl95p2_SSgGD84xHPKOpTAQcukpqJVHF-2XpKkCYb61rKOpIk59vrBSGAwF03ixJsJVia5Muwp'],
  ['Academic Life', 'Interactive Smart Classroom Session', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDvH6FAXRtj-jqndp7F4q6vDF5CnGdsbuLKgcLDWBBHPOt8H3e5Lcgq9GzJ4_M0ggaA4x7T-_MS8tQZ8or99OB7IjJNHHnAmJJHon7juvTX3pS1Ck09-Jn1S7qJCsdA9EBdCbjoWUsG9qXv-tDdFACTURt8hmC2qU_8V8FzZSViNfpplMujeMdqGleFYrBOOh0YPG9nuWFGev6f9At_TKeJh9oXTmt_fkcvTBfUOEOHNlhlAgu2_fUC'],
  ['Academic Life', 'Science Laboratory Practical Experiments', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB0DiwvgmQHvMj75wdnMrWCaj_bI74241QX4OMBG7DvEGwU11c3J_L-48mf8VhiKOgL9H9UYqCDk7_i_gMQl0fVMjGxHXcZB_8kExDHwyi9sp6ZcAaR3O0avfp3Lv2QToC9JA10N4nex9_IAtb6kDmgMuJgBRVXObAupwB_UeDWYNmkMgB-AMpZQFahOj8ZgFRtIkLndWh1pdr-AtgQvdg0p-AtIGHf8-doIl0u6DA560JMdZp_0dp4'],
  ['Campus & Architecture', 'Central Library Reading Hall', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCkzfEv2iWYJ6SGdT8ADPk7JUHhokozJZD0lqKHBmS5YSCBPHzMWQPLbBZ8bBQTStqu2e1zbbpXdKfX_nnAWfNnpjbqpH6w_wd2DaIL7hTelUo11J0PJ7gQbMszYG5D0_h5SCODKqwoYgtqu6nGWbfdHGUU1kxEgwX1hbGzliKwWjqZ-qqSHJsKCvABNBIEVq2_wVg7YHwfJ56LOazz6-9A-NWJDT-jmDGwSCUVkbo9EIhx2zYvy5Lj'],
  ['Sports & Athletics', 'Inter-House Athletics Track Championship', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCLcChGFtPQ7k6vX4PiQ3lyOp46gIWKN8vqCx0XCruxlxKQvHK-fcPug23NR8E_p9XEKd7I39SufoPdSqZnVgsNNN3SDvIACIDYPaCWdcmhyTR1WFPPPMI_MDPS5YJ9dccLjDl6dXj4OynwEOS4xUH9eRTrsvSR-r7rMxNoOvETnzLIT3QAAm5Hzyc8KFETOET3GA6eeaK2t2t9QskHlR3tOeWi6o4Q-P4gYq8qKzTaU126Xw6tkLdb'],
  ['Events & Ceremonies', 'National Parliamentary Debate Finals', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDM0iNib9aDqsUcM6H919UjVYjAFHADuhGlUVI3v5x59pjSSuiZgSEyJLn1TjjmJ7fuMEwk1dNkhUsA52xe9Ota9rO_goL5QXQPegUlinGHMqMSxVXGkjVDjwyO6hH1C69Eg8JhKxb-CkqJqbQlrkP11pHifPtZWxc3deeU8OxHMFcC_WjST-uKPBFdBLJUk5dtUGyeV_mzbgom2fjMUWutZiP_j99SXOnWZPErs2Rv2kC_U7id13tf'],
  ['Events & Ceremonies', 'Regional Science Fair Presentation', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDEkznc5d_1aHrcmSTX1HYdcWsAqAR4MtRJZbTOIEJn5zu8YIN0cyXW6wUB9NLxNgrtZJAP3T93qiRKemJKANwALTQXJHdtM1jFaw-KoGxawiVxMPB5Y27TCvjaOzo2y887jK_Ni-i5rlcd4zGDT0kmtsGriPzUO7htu8BDD3RfQ9-CyeBxByrrbCEXLFppxgXqF0gTvbvewRyZS9Bc8bBH7CiXpWVGiHUzflCihTGyEyLejI_O9iwL'],
  ['Campus & Architecture', 'Auditorium Assembly Hall', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBM3_ZHcaMCRtYEUZzEa_oxZhPeWIWkbwLkJW6f50R1PpZOKWPLayiQUclPQYCVJCr5yeORzMhf3-nK5CWC6yL4JCdUm82rWwuhmuqGzzuT4MoMXwcwexhAEaD3Lsl3xuZTMIIXO0fm7m5wWhZNuu1Ev7QdT_e_v9HkZlS3s34Dzjt4_UAyxbdCbTnJ-8Olkd23GSxl_Z8MRp_aPfEFPNbZ8K4I7_PZ7IVpnOuTLJO6TS1x63OKm6vG'],
  ['Sports & Athletics', 'Football &amp; Sports Complex', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAY7yktvMU3DPtizQZxAVDd9_43NUxnaGTQpAbSkoKdk0ixIbnKmdyZs2PQ4OhuLOrC7T7wEW8p9WURl6jCwopruCSQ9QP5yHKvRqmQxNtBUMADNiPJK2WcDZfs6xehEEFptsidNfesQgbP25DG7oL7UvdWPXMuNir31NIdfkJAcaHfZMn2BR7NHxDLzRvK3qU0nEKqHj3YGSFC62py_BOseCU1K1Wqu9bvIOODOmCMp5Dn6AwfK8dc'],
];
?>

<main class="pb-24 overflow-x-hidden">
  <!-- Page Header (Signature Navy Hero) -->
  <section class="relative min-h-[300px] sm:min-h-[340px] md:h-[360px] flex items-center justify-center bg-[#132644] overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-25 mix-blend-multiply" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDkSZsS-Iqlvm4WA2I4tdngLjBR3KhU44dpiOTGAN-v4HyPc_9if4moawq8yehh0gSIm4cLrha7Tl1_6lnP7teFWAXwPxBITOhjADP70dbJORhfivcTygwyAK0G_1E-TQif_g5DTghKktZXDJhP_M01nfCzfLOMmB7WPTc3gqtN3iNl95p2_SSgGD84xHPKOpTAQcukpqJVHF-2XpKkCYb61rKOpIk59vrBSGAwF03ixJsJVia5Muwp')"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#132644] via-transparent to-[#132644]/70"></div>
    <div class="relative z-10 text-center px-4 sm:px-6 max-w-3xl mx-auto py-12">
      <div class="inline-flex items-center gap-2 border border-[#C6892E]/40 bg-[#C6892E]/15 px-3.5 py-1.5 rounded-full text-[12px] font-semibold text-[#F7F4EE] mb-4 shadow-xs">
        <span class="w-2 h-2 rounded-full bg-[#C6892E]"></span>
        <span>CAMPUS MOMENTS · VISUAL ARCHIVE</span>
      </div>
      <h1 class="font-serif text-2xl xs:text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-3 tracking-tight">Photo &amp; Campus Gallery</h1>
      <p class="text-[#E0E6EF] text-[13.5px] sm:text-base max-w-xl mx-auto leading-relaxed">Explore snapshots of academic inquiry, scientific discovery, athletics, and cultural vitality at our Dhodial campus.</p>
    </div>
  </section>

  <!-- Gallery Grid -->
  <div class="max-w-[1400px] mx-auto px-6 md:px-12 py-16">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($galleryItems as [$cat, $caption, $img]): ?>
      <div class="group relative rounded-[6px] overflow-hidden border border-[#EAE7DE] shadow-xs aspect-[4/3] bg-[#0C1B30] cursor-pointer">
        <img src="<?= $img ?>" alt="<?= htmlspecialchars($caption) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
        <div class="absolute inset-0 bg-gradient-to-t from-[#0C1B30]/90 via-transparent to-transparent opacity-80 group-hover:opacity-95 transition-opacity"></div>
        <div class="absolute bottom-4 left-4 right-4">
          <span class="text-[10px] uppercase tracking-wider font-bold text-[#C6892E] bg-white/90 px-2 py-0.5 rounded inline-block mb-1">
            <?= $cat ?>
          </span>
          <h3 class="font-serif text-sm md:text-base font-bold text-white leading-snug">
            <?= $caption ?>
          </h3>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</main>

<?php include 'includes/footer.php'; ?>
