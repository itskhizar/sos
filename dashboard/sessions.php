<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Academic Sessions Management — dashboard/sessions.php
 */
include_once dirname(__DIR__) . '/include/classes/session.php';
if ($session->logged_in) {
$dashTitle = 'Academic Sessions';
$currentDash = 'sessions';
include 'includes/header.php';
}else{
    header('location: ../login.php');
    exit;
}

$openAdd = (isset($_GET['added']) && $_GET['added'] == 'false');
$openEdit = (isset($_GET['updated']) && $_GET['updated'] == 'false');
?>

<?php if(isset($_GET['added']) && $_GET['added'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Academic session created successfully.</div>
<?php elseif(isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Academic session updated successfully.</div>
<?php elseif(isset($_GET['active']) && $_GET['active'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Active session updated.</div>
<?php elseif(isset($_GET['added']) && $_GET['added'] == 'db'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm font-medium">Could not save session. Please try again.</div>
<?php endif; ?>

<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
  <div>
    <div class="flex items-center gap-2 text-xs text-[#54524B] mb-1">
      <span>Academic Setup</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#002452] font-semibold">Academic Sessions</span>
    </div>
    <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Academic Sessions &amp; Terms</h1>
  </div>
  <button type="button" onclick="document.getElementById('modal-add-session').classList.remove('hidden')"
    class="flex items-center gap-2 px-4 py-2 rounded bg-[#002452] text-white font-semibold text-xs md:text-sm hover:bg-[#002452]/90 transition-colors shadow-sm">
    <span class="material-symbols-outlined text-[18px]">add</span>
    Create New Session
  </button>
</div>

<!-- Sessions Table Card -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-gray-50 text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
        <tr>
          <th class="py-3.5 px-6">Session Title</th>
          <th class="py-3.5 px-6">Commencement Date</th>
          <th class="py-3.5 px-6">Conclusion Date</th>
          <th class="py-3.5 px-6">Status</th>
          <th class="py-3.5 px-6 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
        <?php
        $sessions = $database->getAllSessions();
        if (empty($sessions)):
        ?>
        <tr>
          <td colspan="5" class="py-8 text-center text-gray-500">No academic sessions found. Create your first session.</td>
        </tr>
        <?php else: foreach($sessions as $sess): ?>
        <tr class="hover:bg-gray-50/60 transition-colors">
          <td class="py-4 px-6 font-semibold text-[#002452]">
            <div class="flex items-center gap-2">
              <span><?php echo htmlspecialchars($sess['session_name']); ?></span>
              <?php if (!empty($sess['is_active'])): ?>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wide">Current Active</span>
              <?php endif; ?>
            </div>
          </td>
          <td class="py-4 px-6 text-[#44474f]"><?php echo $sess['start_date'] ? date('M d, Y', strtotime($sess['start_date'])) : '—'; ?></td>
          <td class="py-4 px-6 text-[#44474f]"><?php echo $sess['end_date'] ? date('M d, Y', strtotime($sess['end_date'])) : '—'; ?></td>
          <td class="py-4 px-6">
            <?php
              $badge = match($sess['status']) {
                'Open', 'Active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'Planned', 'Upcoming' => 'bg-amber-50 text-amber-700 border-amber-200',
                'Closed', 'Completed' => 'bg-gray-100 text-gray-700 border-gray-200',
                default => 'bg-slate-100 text-slate-600 border-slate-200'
              };
            ?>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border <?php echo $badge; ?>">
              <?php echo htmlspecialchars($sess['status']); ?>
            </span>
          </td>
          <td class="py-4 px-6 text-right">
            <div class="flex items-center justify-end gap-2">
              <?php if (empty($sess['is_active'])): ?>
              <form action="process.php" method="post" class="inline">
                <input type="hidden" name="session_id" value="<?php echo $sess['session_id']; ?>">
                <button type="submit" name="setactivesession" title="Set as Current Active Session"
                  class="px-2.5 py-1 rounded text-[11px] font-medium bg-[#002452]/5 text-[#002452] hover:bg-[#002452]/10 transition-colors">
                  Set Active
                </button>
              </form>
              <?php endif; ?>
              <button type="button"
                onclick="openEditSession(<?php echo $sess['session_id']; ?>, '<?php echo addslashes($sess['session_name']); ?>', '<?php echo $sess['start_date']; ?>', '<?php echo $sess['end_date']; ?>', '<?php echo $sess['status']; ?>', <?php echo $sess['is_active'] ? 1 : 0; ?>)"
                class="px-2.5 py-1 rounded text-[11px] font-medium border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
                Edit
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ADD SESSION MODAL -->
<div id="modal-add-session" class="<?php echo $openAdd ? '' : 'hidden'; ?> fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button type="button" onclick="document.getElementById('modal-add-session').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a] transition-colors">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Create Academic Session</h2>
    <p class="text-sm text-[#44474f] mb-6">Add a new session for admissions, enrollment and fee billing.</p>
    <form action="process.php" method="post" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Session Title</label>
        <input type="text" name="session_name" placeholder="e.g. 2026–2027" value="<?php echo $form->value('session_name'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('session_name'); ?>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Commencement</label>
          <input type="date" name="start_date" value="<?php echo $form->value('start_date'); ?>"
            class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          <?php echo $form->error('start_date'); ?>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Conclusion</label>
          <input type="date" name="end_date" value="<?php echo $form->value('end_date'); ?>"
            class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          <?php echo $form->error('end_date'); ?>
        </div>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Status</label>
        <select name="status" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <?php
            $st = $form->value('status');
            $opts = array('Planned','Open','Closed','Archived');
            foreach($opts as $o){
              $sel = ($st == $o) ? 'selected' : '';
              echo "<option value='$o' $sel>$o</option>";
            }
          ?>
        </select>
        <?php echo $form->error('status'); ?>
      </div>
      <label class="flex items-center gap-2 text-sm text-[#1b1c1a]">
        <input type="checkbox" name="is_active" value="1" class="rounded text-[#002452] focus:ring-[#002452]" <?php echo $form->value('is_active') ? 'checked' : ''; ?>/>
        Set as current active session
      </label>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-add-session').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0] transition-colors">
          Cancel
        </button>
        <button type="submit" name="addsession" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d] transition-colors">
          Save Session
        </button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT SESSION MODAL -->
<div id="modal-edit-session" class="<?php echo $openEdit ? '' : 'hidden'; ?> fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button type="button" onclick="document.getElementById('modal-edit-session').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a] transition-colors">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Edit Academic Session</h2>
    <p class="text-sm text-[#44474f] mb-6">Update session dates, status or current flag.</p>
    <form action="process.php" method="post" class="space-y-4">
      <input type="hidden" name="session_id" id="edit_session_id" value="<?php echo $form->value('session_id'); ?>"/>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Session Title</label>
        <input type="text" name="session_name" id="edit_session_name" value="<?php echo $form->value('session_name'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('session_name'); ?>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Commencement</label>
          <input type="date" name="start_date" id="edit_start_date" value="<?php echo $form->value('start_date'); ?>"
            class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          <?php echo $form->error('start_date'); ?>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Conclusion</label>
          <input type="date" name="end_date" id="edit_end_date" value="<?php echo $form->value('end_date'); ?>"
            class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
          <?php echo $form->error('end_date'); ?>
        </div>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Status</label>
        <select name="status" id="edit_status" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option value="Planned">Planned</option>
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
          <option value="Archived">Archived</option>
        </select>
        <?php echo $form->error('status'); ?>
      </div>
      <label class="flex items-center gap-2 text-sm text-[#1b1c1a]">
        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded text-[#002452] focus:ring-[#002452]"/>
        Set as current active session
      </label>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-edit-session').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0] transition-colors">
          Cancel
        </button>
        <button type="submit" name="editsession" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d] transition-colors">
          Update Session
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditSession(id, name, start, end, status, isActive) {
  document.getElementById('edit_session_id').value = id;
  document.getElementById('edit_session_name').value = name;
  document.getElementById('edit_start_date').value = start;
  document.getElementById('edit_end_date').value = end;
  document.getElementById('edit_status').value = status;
  document.getElementById('edit_is_active').checked = (isActive == 1);
  document.getElementById('modal-edit-session').classList.remove('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
