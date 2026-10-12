<?php
/**
 * Guardians — dashboard/guardians.php
 */
$dashTitle   = 'Guardians';
$currentDash = 'guardians';
include 'includes/header.php';
?>

<!-- Page Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <p class="text-sm text-[#44474f]">Manage parent and guardian information for all enrolled students.</p>
  <button onclick="document.getElementById('modal-add-guardian').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">add</span>
    Add Guardian
  </button>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-xl border border-[#e3e2df] p-4 shadow-sm">
  <div class="flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" placeholder="Search by name, phone or relation…" class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </div>
    <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-sm bg-white focus:outline-none focus:border-[#002452]">
      <option>All Relations</option>
      <option>Father</option>
      <option>Mother</option>
      <option>Guardian</option>
    </select>
  </div>
</div>

<!-- Guardians Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-[#e3e2df]">
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">All Guardians</h3>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-6 py-3 text-left font-semibold">Name</th>
          <th class="px-4 py-3 text-left font-semibold">Relation</th>
          <th class="px-4 py-3 text-left font-semibold">Phone</th>
          <th class="px-4 py-3 text-left font-semibold">Occupation</th>
          <th class="px-4 py-3 text-left font-semibold">Email</th>
          <th class="px-4 py-3 text-right font-semibold">Students</th>
          <th class="px-4 py-3 text-center font-semibold">Primary</th>
          <th class="px-4 py-3 text-center font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php
        $guardians = [
          ['Muhammad Ali Khan',    'Father',  '+92-300-1234567', 'Engineer',    'ali.khan@email.com',   3, true],
          ['Fatima Begum',         'Mother',  '+92-321-7654321', 'Housewife',   '—',                   2, false],
          ['Abdul Rehman',         'Father',  '+92-333-2345678', 'Teacher',     'a.rehman@email.com',  1, true],
          ['Rukhsana Parveen',     'Mother',  '+92-344-3456789', 'Doctor',      'rukhsana@email.com',  2, true],
          ['Tariq Mahmood',        'Guardian','+92-355-4567890', 'Businessman', 'tariq@biz.com',       1, true],
          ['Zulfiqar Ahmed',       'Father',  '+92-366-5678901', 'Govt. Officer','zulfiqar@gov.pk',    2, true],
          ['Nasreen Akhtar',       'Mother',  '+92-377-6789012', 'Nurse',       '—',                   1, false],
          ['Imran Ullah',          'Father',  '+92-388-7890123', 'Farmer',      '—',                   3, true],
        ];
        foreach ($guardians as [$name,$rel,$phone,$occ,$email,$students,$primary]):
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full bg-[#002452]/10 flex items-center justify-center text-[#002452] font-bold text-sm shrink-0">
                <?= strtoupper(substr($name, 0, 1)) ?>
              </div>
              <span class="font-semibold text-[#1b1c1a]"><?= $name ?></span>
            </div>
          </td>
          <td class="px-4 py-4 text-[#44474f]"><?= $rel ?></td>
          <td class="px-4 py-4 text-[#44474f] font-mono text-xs"><?= $phone ?></td>
          <td class="px-4 py-4 text-[#44474f]"><?= $occ ?></td>
          <td class="px-4 py-4 text-[#44474f] text-xs"><?= $email ?></td>
          <td class="px-4 py-4 text-right font-semibold text-[#1b1c1a]"><?= $students ?></td>
          <td class="px-4 py-4 text-center">
            <?php if ($primary): ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">
                <span class="material-symbols-outlined text-[12px]">check_circle</span> Yes
              </span>
            <?php else: ?>
              <span class="text-[11px] text-[#747780]">No</span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-4">
            <div class="flex items-center justify-center gap-1">
              <button class="p-1.5 text-[#44474f] hover:text-[#002452] hover:bg-[#002452]/8 rounded-md transition-all" title="View Students">
                <span class="material-symbols-outlined text-[16px]">group</span>
              </button>
              <button class="p-1.5 text-[#44474f] hover:text-[#885200] hover:bg-[#885200]/8 rounded-md transition-all" title="Edit">
                <span class="material-symbols-outlined text-[16px]">edit</span>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="px-6 py-3 border-t border-[#e3e2df] flex items-center justify-between text-xs text-[#44474f]">
    <span>Showing 8 of 412 guardians</span>
    <div class="flex items-center gap-1">
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0] disabled:opacity-50" disabled>Prev</button>
      <button class="px-2.5 py-1 rounded bg-[#002452] text-white">1</button>
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0]">2</button>
      <button class="px-2.5 py-1 rounded border border-[#e3e2df] hover:bg-[#f4f3f0]">Next</button>
    </div>
  </div>
</div>

<!-- ADD GUARDIAN MODAL -->
<div id="modal-add-guardian" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative max-h-[90vh] overflow-y-auto">
    <button onclick="document.getElementById('modal-add-guardian').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold mb-1">Add Guardian</h2>
    <p class="text-sm text-[#44474f] mb-6">Enter guardian/parent information.</p>
    <form class="space-y-4">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">First Name</label>
          <input type="text" placeholder="First name" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Last Name</label>
          <input type="text" placeholder="Last name" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        </div>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Relation</label>
        <select class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option>Father</option><option>Mother</option><option>Guardian</option><option>Sibling</option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Phone</label>
        <input type="tel" placeholder="+92-300-0000000" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Email (optional)</label>
        <input type="email" placeholder="email@example.com" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Occupation</label>
        <input type="text" placeholder="e.g. Teacher, Businessman…" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Address</label>
        <textarea rows="2" placeholder="Full address…" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] resize-none"></textarea>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-add-guardian').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Save Guardian</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
