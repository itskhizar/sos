<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Other Charges — dashboard/other-charges.php
 * Module 11: Other Charges (Board registrations, educational trips, uniform kits)
 */
$dashTitle   = 'Other Charges';
$currentDash = 'other-charges';
include 'includes/header.php';
?>

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Special & Ancillary Charges</h2>
    <p class="text-sm text-[#44474f] mt-1">Manage BISE Board registration fees, educational excursion costs, and identity card re-issuance.</p>
  </div>
  <button onclick="document.getElementById('modal-add-charge').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">add_card</span>
    Add Charge
  </button>
</div>

<!-- Charges Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row md:items-center justify-between gap-3">
    <div class="relative flex-1 max-w-md">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" placeholder="Search by student, charge type or receipt ID..." class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
    </div>
    <select class="px-3 py-2 border border-[#e3e2df] rounded-lg text-xs bg-white text-[#44474f]">
      <option>All Charge Types</option>
      <option>BISE Abbottabad Board Registration</option>
      <option>Annual Study Excursion Tour</option>
      <option>Sports Uniform Kit</option>
      <option>Science Exhibition Project Kit</option>
      <option>Duplicate ID Card</option>
    </select>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#f4f3f0] text-[10.5px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-4 py-3 text-left font-semibold">Ref Code</th>
          <th class="px-4 py-3 text-left font-semibold">Student Name</th>
          <th class="px-4 py-3 text-left font-semibold">Class / Section</th>
          <th class="px-4 py-3 text-left font-semibold">Charge Item</th>
          <th class="px-4 py-3 text-right font-semibold">Amount</th>
          <th class="px-4 py-3 text-left font-semibold">Billing Date</th>
          <th class="px-4 py-3 text-center font-semibold">Status</th>
          <th class="px-4 py-3 text-center font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php
        $charges = [
          ['OC-2027-101', 'Muhammad Umar', 'Class X - A', 'BISE Board Matric Exam Fee', 3500, '2027-01-10', 'Paid'],
          ['OC-2027-102', 'Fatima Noor', 'Class IX - A', 'BISE Registration Fee', 2800, '2027-01-10', 'Paid'],
          ['OC-2027-103', 'Hamza Tariq', 'Class IV - A', 'Murree Science Excursion Tour', 2500, '2027-01-08', 'Pending'],
          ['OC-2027-104', 'Bilal Ahmad', 'Class VI - A', 'Scouts & Camping Kit', 1800, '2027-01-05', 'Waived'],
          ['OC-2027-105', 'Sara Malik', 'Class I - B', 'Duplicate ID Card Re-issue', 300, '2027-01-03', 'Paid'],
        ];
        foreach ($charges as [$cRef, $sName, $sClass, $cItem, $cAmt, $cDate, $cStatus]):
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-4 py-3.5 font-mono text-xs font-semibold text-[#002452]"><?= $cRef ?></td>
          <td class="px-4 py-3.5 font-semibold text-[#1b1c1a]"><?= $sName ?></td>
          <td class="px-4 py-3.5 text-xs text-[#44474f]"><?= $sClass ?></td>
          <td class="px-4 py-3.5 text-xs font-medium text-[#1b1c1a]"><?= $cItem ?></td>
          <td class="px-4 py-3.5 text-right font-bold text-[#002452]">Rs. <?= number_format($cAmt) ?></td>
          <td class="px-4 py-3.5 text-xs text-[#747780]"><?= $cDate ?></td>
          <td class="px-4 py-3.5 text-center">
            <?php if ($cStatus === 'Paid'): ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#9af6b8]/40 text-[#00522d]">Paid</span>
            <?php elseif ($cStatus === 'Waived'): ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#ffdcbb] text-[#673d00]">Waived</span>
            <?php else: ?>
              <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-[#ffdad6] text-[#93000a]">Pending</span>
            <?php endif; ?>
          </td>
          <td class="px-4 py-3.5 text-center">
            <button class="p-1.5 text-[#44474f] hover:text-[#002452] rounded" title="View Detail">
              <span class="material-symbols-outlined text-[17px]">visibility</span>
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Add Charge -->
<div id="modal-add-charge" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button onclick="document.getElementById('modal-add-charge').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a]">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h3 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Add Ancillary Charge</h3>
    <p class="text-xs text-[#44474f] mb-5">Assign a one-time special charge to a student account.</p>

    <form class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Student Enrollment</label>
        <input type="text" placeholder="Search student name or roll #..." class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Charge Description</label>
        <select class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option>BISE Board Matric Exam Fee</option>
          <option>BISE Board Ninth Registration</option>
          <option>Annual Study Excursion Tour</option>
          <option>Scouts / Girl Guides Kit</option>
          <option>Duplicate ID Card / Badge</option>
          <option>Special Certificate Fee</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Amount (PKR)</label>
        <input type="number" placeholder="e.g. 2500" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Billing Month</label>
        <input type="month" value="2027-02" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white"/>
      </div>

      <div class="flex gap-3 pt-3">
        <button type="button" onclick="document.getElementById('modal-add-charge').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0]">Cancel</button>
        <button type="submit" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d]">Post Charge</button>
      </div>
    </form>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
