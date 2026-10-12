<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Admission Cycles Management — dashboard/admission-cycles.php
 * Module: Manage Open Admissions Cycles, Public Alert Banner, Flyer & Deadlines
 */
$dashTitle   = 'Admission Cycles';
$currentDash = 'admission-cycles';
include 'includes/header.php';

$cycles = $database->getAllAdmissionCycles();
$sessions = $database->getAllSessions();
$activeSession = $database->getActiveSession();

$totalCycles = count($cycles);
$openCycles = count(array_filter($cycles, fn($c) => $c['status'] === 'Open' && !empty($c['is_active']) && strtotime($c['end_date']) >= strtotime('today')));
?>

<!-- Page Header & Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Admission Cycles &amp; Deadlines</h1>
    <p class="text-xs md:text-sm text-[#54524B] mt-0.5">Control open admissions on the public website, flyer alerts, application windows, and last registration dates.</p>
  </div>
  <div class="flex gap-2.5">
    <button onclick="document.getElementById('modal-add-cycle').classList.remove('hidden')"
      class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-[#002452] text-white font-semibold text-xs md:text-sm rounded-lg hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
      <span class="material-symbols-outlined text-[18px]">add</span>
      <span>Configure Admission Cycle</span>
    </button>
  </div>
</div>

<!-- Alerts / Notifications -->
<?php if (isset($_GET['added']) && $_GET['added'] === 'true'): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
    <span class="font-semibold">Admission cycle configured successfully! It will automatically display as an active alert on the website while within date limits.</span>
  </div>
<?php elseif (isset($_GET['updated']) && $_GET['updated'] === 'true'): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
    <span class="font-semibold">Admission cycle and website banner updated successfully.</span>
  </div>
<?php endif; ?>

<!-- Stats Overview Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-[#002452]/10 text-[#002452]">
      <span class="material-symbols-outlined text-[24px]">calendar_month</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $totalCycles ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Total Cycles Recorded</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-emerald-50 text-[#00522d]">
      <span class="material-symbols-outlined text-[24px]">campaign</span>
    </div>
    <div>
      <p class="font-serif text-2xl font-bold text-[#1b1c1a]"><?= $openCycles ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Active on Public Web</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-amber-50 text-[#885200]">
      <span class="material-symbols-outlined text-[24px]">school</span>
    </div>
    <div>
      <p class="font-serif text-lg font-bold text-[#1b1c1a] truncate max-w-[150px]"><?= htmlspecialchars($activeSession['session_name'] ?? '2026-2027') ?></p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Active Academic Session</p>
    </div>
  </div>

  <div class="bg-white rounded-xl p-5 border border-[#e3e2df] shadow-xs flex items-center gap-4">
    <div class="p-3 rounded-xl shrink-0 bg-blue-50 text-blue-800">
      <span class="material-symbols-outlined text-[24px]">timer</span>
    </div>
    <div>
      <p class="font-serif text-sm font-bold text-[#1b1c1a]">Auto-Expiration</p>
      <p class="text-xs text-[#44474f] font-medium mt-0.5">Auto-hides when expired</p>
    </div>
  </div>
</div>

<!-- Admission Cycles Table Card -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden mb-12">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex items-center justify-between bg-[#fbfaf8]">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-[#002452] text-[20px]">assignment_turned_in</span>
      <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Configured Admission Windows</h3>
    </div>
    <span class="text-xs text-gray-500 font-semibold"><?= count($cycles) ?> Cycles</span>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-gray-50 text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
        <tr>
          <th class="py-3.5 px-6">Cycle Name &amp; Flyer</th>
          <th class="py-3.5 px-4">Target Session</th>
          <th class="py-3.5 px-4">Start Date</th>
          <th class="py-3.5 px-4">Last Date to Apply</th>
          <th class="py-3.5 px-4 text-center">Website Banner</th>
          <th class="py-3.5 px-4 text-center">Status</th>
          <th class="py-3.5 px-6 text-center">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
        <?php if (empty($cycles)): ?>
          <tr><td colspan="7" class="py-8 text-center text-[#747780]">No admission cycles configured. Click "Configure Admission Cycle" to open admissions.</td></tr>
        <?php else: foreach ($cycles as $c): 
          $isPast = strtotime($c['end_date']) < strtotime('today');
          $isActiveOnWeb = ($c['status'] === 'Open' && !empty($c['is_active']) && !$isPast);
        ?>
        <tr class="hover:bg-gray-50/80 transition-colors <?= $isActiveOnWeb ? 'bg-emerald-50/30' : '' ?>">
          <td class="py-4 px-6 font-semibold">
            <div class="flex items-center gap-3">
              <?php if (!empty($c['banner_image'])): ?>
                <img src="../<?= htmlspecialchars($c['banner_image']) ?>" alt="Flyer" class="w-10 h-10 rounded-lg object-cover border border-gray-200 shrink-0"/>
              <?php else: ?>
                <div class="w-10 h-10 rounded-lg bg-[#002452]/10 text-[#002452] flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-[20px]">photo</span>
                </div>
              <?php endif; ?>
              <div>
                <span class="text-sm font-bold text-[#002452] block"><?= htmlspecialchars($c['cycle_name']) ?></span>
                <span class="text-[11px] text-[#747780] truncate max-w-[240px] block"><?= htmlspecialchars($c['description'] ?: 'Open admissions window') ?></span>
              </div>
            </div>
          </td>
          <td class="py-4 px-4 font-semibold text-gray-800">
            <?= htmlspecialchars($c['session_name'] ?? 'Current Session') ?>
          </td>
          <td class="py-4 px-4 text-gray-600 font-mono">
            <?= date('d M Y', strtotime($c['start_date'])) ?>
          </td>
          <td class="py-4 px-4 font-mono">
            <span class="<?= $isPast ? 'text-red-700 font-bold' : 'text-[#002452] font-bold' ?>">
              <?= date('d M Y', strtotime($c['end_date'])) ?>
            </span>
            <?php if ($isPast): ?>
              <span class="text-[10px] text-red-600 block">(Expired)</span>
            <?php else: 
              $days = ceil((strtotime($c['end_date']) - time()) / 86400);
            ?>
              <span class="text-[10px] text-emerald-700 block"><?= $days ?> days remaining</span>
            <?php endif; ?>
          </td>
          <td class="py-4 px-4 text-center">
            <?php if ($isActiveOnWeb): ?>
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span> Displaying Live
              </span>
            <?php else: ?>
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-medium bg-gray-100 text-gray-600">
                Hidden / Inactive
              </span>
            <?php endif; ?>
          </td>
          <td class="py-4 px-4 text-center">
            <span class="inline-flex px-2 py-0.5 rounded-full text-[10.5px] font-bold <?= ($c['status'] === 'Open') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' ?>">
              <?= htmlspecialchars($c['status']) ?>
            </span>
          </td>
          <td class="py-4 px-6 text-center">
            <button onclick="editCycle(<?= htmlspecialchars(json_encode($c)) ?>)" 
              class="px-3 py-1.5 bg-[#002452] hover:bg-[#001a3d] text-white rounded-lg text-xs font-semibold inline-flex items-center gap-1 transition-colors">
              <span class="material-symbols-outlined text-[15px]">edit</span>
              <span>Edit Cycle</span>
            </button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL: CONFIGURE NEW ADMISSION CYCLE -->
<div id="modal-add-cycle" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4 overflow-y-auto">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Configure Admission Cycle</h3>
      <button onclick="document.getElementById('modal-add-cycle').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="space-y-4">
      <input type="hidden" name="addadmissioncycle" value="1"/>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Cycle Title <span class="text-red-500">*</span></label>
        <input type="text" name="cycle_name" placeholder="e.g. Admissions Open 2026–2027 (Playgroup to FSc)" required 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Academic Session <span class="text-red-500">*</span></label>
          <select name="session_id" required class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 bg-white outline-none">
            <?php foreach ($sessions as $s): ?>
              <option value="<?= $s['session_id'] ?>" <?= !empty($s['is_active']) ? 'selected' : '' ?>><?= htmlspecialchars($s['session_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Cycle Status <span class="text-red-500">*</span></label>
          <select name="status" required class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 bg-white outline-none">
            <option value="Open">Open</option>
            <option value="Upcoming">Upcoming</option>
            <option value="Closed">Closed</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Opening Date <span class="text-red-500">*</span></label>
          <input type="date" name="start_date" value="<?= date('Y-m-d') ?>" required 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Last Date to Apply <span class="text-red-500">*</span></label>
          <input type="date" name="end_date" value="<?= date('Y-m-d', strtotime('+45 days')) ?>" required 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Flyer Image Path (Optional)</label>
        <input type="text" name="banner_image" placeholder="e.g. assets/images/admissions_banner.jpg" 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Description / Alert Announcement</label>
        <textarea name="description" rows="2" placeholder="Applications are cordially invited for Nursery, Primary, Matric and FSc Pre-Medical / Engineering..." 
          class="w-full text-xs border border-gray-300 rounded-lg p-2.5 outline-none focus:border-[#002452]"></textarea>
      </div>

      <div class="flex items-center gap-2 p-3 bg-emerald-50 rounded-lg border border-emerald-200">
        <input type="checkbox" name="is_active" id="chk-is-active" value="1" checked class="w-4 h-4 text-[#002452] rounded"/>
        <label for="chk-is-active" class="text-xs font-bold text-emerald-900 cursor-pointer">
          Display dynamic admission banner alert on public website
        </label>
      </div>

      <div class="flex justify-end gap-2 pt-3 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-add-cycle').classList.add('hidden')" 
          class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-xs font-bold rounded-lg shadow-sm">Save Admission Cycle</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: EDIT ADMISSION CYCLE -->
<div id="modal-edit-cycle" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4 overflow-y-auto">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Edit Admission Cycle &amp; Alert</h3>
      <button onclick="document.getElementById('modal-edit-cycle').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="space-y-4">
      <input type="hidden" name="updateadmissioncycle" value="1"/>
      <input type="hidden" name="admission_cycle_id" id="edit-cycle-id" value=""/>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Cycle Title <span class="text-red-500">*</span></label>
        <input type="text" name="cycle_name" id="edit-cycle-name" required 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Target Session</label>
          <select name="session_id" id="edit-cycle-session" required class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 bg-white outline-none">
            <?php foreach ($sessions as $s): ?>
              <option value="<?= $s['session_id'] ?>"><?= htmlspecialchars($s['session_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Status</label>
          <select name="status" id="edit-cycle-status" required class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 bg-white outline-none">
            <option value="Open">Open</option>
            <option value="Upcoming">Upcoming</option>
            <option value="Closed">Closed</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Start Date</label>
          <input type="date" name="start_date" id="edit-cycle-start" required 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Last Date to Apply (Deadline)</label>
          <input type="date" name="end_date" id="edit-cycle-end" required 
            class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Flyer Image Path</label>
        <input type="text" name="banner_image" id="edit-cycle-banner" 
          class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 outline-none focus:border-[#002452]"/>
      </div>

      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Description / Alert Announcement</label>
        <textarea name="description" id="edit-cycle-desc" rows="2" 
          class="w-full text-xs border border-gray-300 rounded-lg p-2.5 outline-none focus:border-[#002452]"></textarea>
      </div>

      <div class="flex items-center gap-2 p-3 bg-emerald-50 rounded-lg border border-emerald-200">
        <input type="checkbox" name="is_active" id="edit-cycle-active" value="1" class="w-4 h-4 text-[#002452] rounded"/>
        <label for="edit-cycle-active" class="text-xs font-bold text-emerald-900 cursor-pointer">
          Display dynamic admission banner alert on public website
        </label>
      </div>

      <div class="flex justify-end gap-2 pt-3 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-edit-cycle').classList.add('hidden')" 
          class="px-4 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-xs font-bold rounded-lg shadow-sm">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
function editCycle(cycle) {
  document.getElementById('edit-cycle-id').value = cycle.admission_cycle_id;
  document.getElementById('edit-cycle-name').value = cycle.cycle_name;
  document.getElementById('edit-cycle-session').value = cycle.session_id;
  document.getElementById('edit-cycle-status').value = cycle.status;
  document.getElementById('edit-cycle-start').value = cycle.start_date;
  document.getElementById('edit-cycle-end').value = cycle.end_date;
  document.getElementById('edit-cycle-banner').value = cycle.banner_image || '';
  document.getElementById('edit-cycle-desc').value = cycle.description || '';
  document.getElementById('edit-cycle-active').checked = (cycle.is_active == 1);

  document.getElementById('modal-edit-cycle').classList.remove('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
