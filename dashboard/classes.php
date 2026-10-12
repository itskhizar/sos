<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Classes Management — dashboard/classes.php
 */
include_once dirname(__DIR__) . '/include/classes/session.php';
if ($session->logged_in) {
$dashTitle = 'Classes & Sections';
$currentDash = 'classes';
include 'includes/header.php';
}else{
    header('location: ../login.php');
    exit;
}
$openAdd = (isset($_GET['added']) && $_GET['added'] == 'false');
$openEdit = (isset($_GET['updated']) && $_GET['updated'] == 'false');
?>

<?php if(isset($_GET['added']) && $_GET['added'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Class created successfully.</div>
<?php elseif(isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-800 text-sm font-medium">Class updated successfully.</div>
<?php elseif(isset($_GET['added']) && $_GET['added'] == 'db'): ?>
<div class="mb-4 px-4 py-3 rounded-lg bg-red-50 text-red-800 text-sm font-medium">Could not save class. Please try again.</div>
<?php endif; ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
  <div>
    <div class="flex items-center gap-2 text-xs text-[#54524B] mb-1">
      <span>Academic Setup</span>
      <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      <span class="text-[#002452] font-semibold">Classes</span>
    </div>
    <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#002452]">Classes</h1>
  </div>
  <button type="button" onclick="document.getElementById('modal-add-class').classList.remove('hidden')"
    class="flex items-center gap-2 px-4 py-2 rounded bg-[#002452] text-white font-semibold text-xs md:text-sm hover:bg-[#002452]/90 transition-colors shadow-sm">
    <span class="material-symbols-outlined text-[18px]">add</span>
    Add New Class
  </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
  <?php
  $classes = $database->getAllClasses();
  if (empty($classes)):
  ?>
  <div class="col-span-full bg-white rounded-xl border border-[#e3e2df] p-8 text-center text-gray-500">
    No classes created yet. Click "Add New Class" to begin.
  </div>
  <?php else: foreach($classes as $c): ?>
  <div class="bg-white rounded-xl border border-[#e3e2df] p-5 shadow-xs hover:shadow-md transition-shadow">
    <div class="flex items-start justify-between gap-3 mb-2">
      <div>
        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-[#002452]/5 text-[#002452] uppercase tracking-wide mb-1">
          Order #<?php echo intval($c['class_order']); ?>
        </span>
        <h3 class="font-serif text-lg font-bold text-[#002452]"><?php echo htmlspecialchars($c['class_name']); ?></h3>
      </div>
      <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold <?php echo !empty($c['is_active']) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-600 border border-gray-200'; ?>">
        <?php echo !empty($c['is_active']) ? 'Active' : 'Inactive'; ?>
      </span>
    </div>
    <?php if (!empty($c['description'])): ?>
      <p class="text-xs text-[#54524B] mb-4 line-clamp-2"><?php echo htmlspecialchars($c['description']); ?></p>
    <?php else: ?>
      <p class="text-xs text-gray-400 italic mb-4">No description provided</p>
    <?php endif; ?>
    <div class="pt-3 border-t border-[#e3e2df] flex items-center justify-between">
      <a href="sections.php?class_id=<?php echo $c['class_id']; ?>" class="text-xs font-semibold text-[#002452] hover:text-[#C6892E] flex items-center gap-1">
        <span>View Sections</span>
        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
      </a>
      <button type="button"
        onclick="openEditClass(<?php echo $c['class_id']; ?>, '<?php echo addslashes($c['class_name']); ?>', '<?php echo intval($c['class_order']); ?>', '<?php echo addslashes($c['description'] ?? ''); ?>', <?php echo !empty($c['is_active']) ? 1 : 0; ?>)"
        class="px-2.5 py-1 rounded text-[11px] font-medium border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
        Edit
      </button>
    </div>
  </div>
  <?php endforeach; endif; ?>
</div>

<div id="modal-add-class" class="<?php echo $openAdd ? '' : 'hidden'; ?> fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button type="button" onclick="document.getElementById('modal-add-class').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a] transition-colors">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Add New Class</h2>
    <p class="text-sm text-[#44474f] mb-6">Create a class such as Nursery, Class VI or FSc Pre-Medical.</p>
    <form action="process.php" method="post" class="space-y-4">
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Class Name</label>
        <input type="text" name="class_name" placeholder="e.g. Class 1" value="<?php echo $form->value('class_name'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('class_name'); ?>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Display Order</label>
        <input type="number" name="class_order" placeholder="e.g. 1" value="<?php echo $form->value('class_order'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('class_order'); ?>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Description</label>
        <input type="text" name="description" placeholder="Optional" value="<?php echo $form->value('description'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>
      <label class="flex items-center gap-2 text-sm text-[#1b1c1a]">
        <input type="checkbox" name="is_active" value="1" class="rounded text-[#002452] focus:ring-[#002452]" checked/>
        Active class
      </label>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-add-class').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0] transition-colors">Cancel</button>
        <button type="submit" name="addclass" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d] transition-colors">Save Class</button>
      </div>
    </form>
  </div>
</div>

<div id="modal-edit-class" class="<?php echo $openEdit ? '' : 'hidden'; ?> fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
    <button type="button" onclick="document.getElementById('modal-edit-class').classList.add('hidden')"
      class="absolute top-4 right-4 text-[#747780] hover:text-[#1b1c1a] transition-colors">
      <span class="material-symbols-outlined">close</span>
    </button>
    <h2 class="font-serif text-xl font-bold text-[#1b1c1a] mb-1">Edit Class</h2>
    <form action="process.php" method="post" class="space-y-4 mt-4">
      <input type="hidden" name="class_id" id="edit_class_id" value="<?php echo $form->value('class_id'); ?>"/>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Class Name</label>
        <input type="text" name="class_name" id="edit_class_name" value="<?php echo $form->value('class_name'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('class_name'); ?>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Display Order</label>
        <input type="number" name="class_order" id="edit_class_order" value="<?php echo $form->value('class_order'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
        <?php echo $form->error('class_order'); ?>
      </div>
      <div>
        <label class="block text-xs font-semibold text-[#44474f] mb-1.5 uppercase tracking-wide">Description</label>
        <input type="text" name="description" id="edit_description" value="<?php echo $form->value('description'); ?>"
          class="w-full px-3 py-2.5 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452]"/>
      </div>
      <label class="flex items-center gap-2 text-sm text-[#1b1c1a]">
        <input type="checkbox" name="is_active" id="edit_class_active" value="1" class="rounded text-[#002452] focus:ring-[#002452]"/>
        Active class
      </label>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="document.getElementById('modal-edit-class').classList.add('hidden')"
          class="flex-1 px-4 py-2.5 border border-[#e3e2df] rounded-lg text-sm font-semibold text-[#44474f] hover:bg-[#f4f3f0] transition-colors">Cancel</button>
        <button type="submit" name="editclass" class="flex-1 px-4 py-2.5 bg-[#002452] text-white rounded-lg text-sm font-semibold hover:bg-[#001a3d] transition-colors">Update Class</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditClass(id, name, order, description, isActive) {
  document.getElementById('edit_class_id').value = id;
  document.getElementById('edit_class_name').value = name;
  document.getElementById('edit_class_order').value = order;
  document.getElementById('edit_description').value = description;
  document.getElementById('edit_class_active').checked = (isActive == 1);
  document.getElementById('modal-edit-class').classList.remove('hidden');
}
</script>

<?php include 'includes/footer.php'; ?>
