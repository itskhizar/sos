<?php
/**
 * Academic Sections — dashboard/sections.php
 */
include_once dirname(__DIR__) . '/include/classes/session.php';
if ($session->logged_in) {
$dashTitle   = 'Sections';
$currentDash = 'sections';
include 'includes/header.php';
}else{
    header('location: ../login.php');
    exit;
}
$openAdd = (isset($_GET['added']) && $_GET['added'] == 'false');
$openEdit = (isset($_GET['updated']) && $_GET['updated'] == 'false');
?>

<?php if(isset($_GET['added']) && $_GET['added'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Section created successfully.</div>
<?php elseif(isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Section updated successfully.</div>
<?php elseif(isset($_GET['added']) && $_GET['added'] == 'db'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm font-medium">Could not save section. Create a session and class first.</div>
<?php endif; ?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <p class="text-sm text-[#44474f] mt-1">Manage class sections and their capacity settings.</p>
  </div>
  <button type="button" onclick="document.getElementById('modal-add-section').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm">
    <span class="material-symbols-outlined text-[18px]">add</span>
    Add Section
  </button>
</div>

<div class="bg-white rounded-xl border border-[#e3e2df] shadow-xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-gray-50 text-[#747780] uppercase tracking-wider font-semibold border-b border-[#e3e2df]">
        <tr>
          <th class="py-3.5 px-6">Section</th>
          <th class="py-3.5 px-6">Assigned Class</th>
          <th class="py-3.5 px-6">Capacity</th>
          <th class="py-3.5 px-6">Status</th>
          <th class="py-3.5 px-6 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df] text-[#1b1c1a]">
        <?php
        $sections = $database->getAllSections();
        if (empty($sections)):
        ?>
        <tr>
          <td colspan="5" class="py-8 text-center text-gray-500">No sections found. Click "Add Section" to create one.</td>
        </tr>
        <?php else: foreach($sections as $sec): ?>
        <tr class="hover:bg-gray-50/60 transition-colors">
          <td class="py-4 px-6 font-semibold text-[#002452]">
            Section <?php echo htmlspecialchars($sec['section_name']); ?>
          </td>
          <td class="py-4 px-6 text-[#44474f] font-medium">
            <?php echo htmlspecialchars($sec['class_name'] ?? 'Class #' . $sec['class_id']); ?>
          </td>
          <td class="py-4 px-6 text-[#44474f]">
            <?php echo intval($sec['capacity'] ?? 35); ?> Scholars
          </td>
          <td class="py-4 px-6">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold <?php echo !empty($sec['is_active']) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200'; ?>">
              <?php echo !empty($sec['is_active']) ? 'Active' : 'Inactive'; ?>
            </span>
          </td>
          <td class="py-4 px-6 text-right">
            <button type="button"
              onclick="openEditSection(<?php echo $sec['section_id']; ?>, <?php echo $sec['class_id']; ?>, '<?php echo addslashes($sec['section_name']); ?>', <?php echo intval($sec['capacity'] ?? 35); ?>, <?php echo !empty($sec['is_active']) ? 1 : 0; ?>)"
              class="px-2.5 py-1 rounded text-[11px] font-medium border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
              Edit
            </button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div id="modal-add-section" class="<?php echo $openAdd ? '' : 'hidden'; ?> fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button type="button" onclick="document.getElementById('modal-add-section').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a] transition-colors">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Add New Section</h2>
    <p class="text-sm text-[#44474f] mb-6">Create a class section for an academic session.</p>
    <form action="process.php" method="post" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Academic Session</label>
        <select name="session_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option value="">Select session…</option>
          <?php
          $allSess = $database->getAllSessions();
          $actSess = $database->getActiveSession();
          foreach($allSess as $s):
            $sel = ($actSess && $actSess['session_id'] == $s['session_id']) ? 'selected' : '';
          ?>
            <option value="<?php echo $s['session_id']; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($s['session_name']); ?><?php echo !empty($s['is_active']) ? ' (Active)' : ''; ?></option>
          <?php endforeach; ?>
        </select>
        <?php echo $form->error('session_id'); ?>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Class</label>
        <select name="class_id" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option value="">Select class…</option>
          <?php
          $allCls = $database->getAllClasses();
          foreach($allCls as $c):
          ?>
            <option value="<?php echo $c['class_id']; ?>"><?php echo htmlspecialchars($c['class_name']); ?></option>
          <?php endforeach; ?>
        </select>
        <?php echo $form->error('class_id'); ?>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Section Name</label>
        <input type="text" name="section_name" placeholder="e.g. A, B, Blue…" value="<?php echo $form->value('section_name'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('section_name'); ?>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Capacity</label>
        <input type="number" name="capacity" placeholder="e.g. 35" value="<?php echo $form->value('capacity') ?: '35'; ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('capacity'); ?>
      </div>
      <label class="flex items-center gap-2 text-sm text-[#1b1c1a]">
        <input type="checkbox" name="is_active" value="1" class="rounded text-[#002452] focus:ring-[#002452]" checked/>
        Active section
      </label>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-add-section').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0] transition-colors">Cancel</button>
        <button type="submit" name="addsection" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d] transition-colors">Save Section</button>
      </div>
    </form>
  </div>
</div>

<div id="modal-edit-section" class="<?php echo $openEdit ? '' : 'hidden'; ?> fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button type="button" onclick="document.getElementById('modal-edit-section').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a] transition-colors">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Edit Section</h2>
    <form action="process.php" method="post" class="space-y-4 mt-4">
      <input type="hidden" name="section_id" id="edit_section_id" value="<?php echo $form->value('section_id'); ?>"/>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Class</label>
        <select name="class_id" id="edit_section_class" class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white">
          <option value="">Select class…</option>
          <?php foreach($allCls as $c): ?>
            <option value="<?php echo $c['class_id']; ?>"><?php echo htmlspecialchars($c['class_name']); ?></option>
          <?php endforeach; ?>
        </select>
        <?php echo $form->error('class_id'); ?>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Section Name</label>
        <input type="text" name="section_name" id="edit_section_name" value="<?php echo $form->value('section_name'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('section_name'); ?>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Capacity</label>
        <input type="number" name="capacity" id="edit_section_capacity" value="<?php echo $form->value('capacity'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('capacity'); ?>
      </div>
      <label class="flex items-center gap-2 text-sm text-[#1b1c1a]">
        <input type="checkbox" name="is_active" id="edit_section_active" value="1" class="rounded text-[#002452] focus:ring-[#002452]"/>
        Active section
      </label>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-edit-section').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0] transition-colors">Cancel</button>
        <button type="submit" name="editsection" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d] transition-colors">Update Section</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditSection(id, classId, name, capacity, isActive) {
  document.getElementById('edit_section_id').value = id;
  document.getElementById('edit_section_class').value = classId;
  document.getElementById('edit_section_name').value = name;
  document.getElementById('edit_section_capacity').value = capacity;
  document.getElementById('edit_section_active').checked = (isActive == 1);
  document.getElementById('modal-edit-section').classList.remove('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
