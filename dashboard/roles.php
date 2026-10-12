<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * Dynamic Roles & Permissions Matrix — dashboard/roles.php
 * Fully Database-Driven RBAC with granular module capability toggles
 */
$dashTitle   = 'Roles & Permissions';
$currentDash = 'roles';
include 'includes/header.php';

$allRoles = $database->getRoles();
$selectedRoleId = isset($_GET['role_id']) ? intval($_GET['role_id']) : ($allRoles[0]['role_id'] ?? 1);
$currentRole = $database->getRole($selectedRoleId) ?: ($allRoles[0] ?? null);
$allPermissions = $database->getPermissions();
$rolePerms = $database->getRolePermissions($selectedRoleId);
?>

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Dynamic Role-Based Access Control (RBAC)</h2>
    <p class="text-sm text-[#44474f] mt-1">Configure custom administrative roles and granular per-module action permissions.</p>
  </div>
  <button onclick="document.getElementById('modal-add-role').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">add_moderator</span>
    Create Custom Role
  </button>
</div>

<?php if (isset($_GET['saved'])): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
    <span class="font-medium">Role permissions successfully updated and synchronized across all active sessions!</span>
  </div>
<?php elseif (isset($_GET['added'])): ?>
  <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg flex items-center gap-2 shadow-xs">
    <span class="material-symbols-outlined text-blue-600 text-[20px]">check_circle</span>
    <span class="font-medium">New custom security role created successfully! Select it below to assign capabilities.</span>
  </div>
<?php endif; ?>

<!-- Dynamic Roles Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
  <?php foreach ($allRoles as $r): 
    $isSelected = ($r['role_id'] == $selectedRoleId);
    $cardBorder = $isSelected ? 'border-[#C6892E] ring-2 ring-[#C6892E]/20 bg-[#FDFBF7]' : 'border-[#e3e2df] bg-white hover:border-[#C6892E]/40';
  ?>
  <a href="roles.php?role_id=<?= $r['role_id'] ?>" class="rounded-xl p-5 border transition-all shadow-xs block <?= $cardBorder ?>">
    <div class="flex items-center justify-between mb-3">
      <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $r['is_active'] ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' ?>">
        <?= $r['is_active'] ? 'Active Role' : 'Disabled' ?>
      </span>
      <span class="material-symbols-outlined text-[20px] <?= $isSelected ? 'text-[#C6892E]' : 'text-[#002452]' ?>">
        <?= ($r['role_id'] == 1) ? 'admin_panel_settings' : 'shield_person' ?>
      </span>
    </div>
    <h3 class="font-serif text-lg font-bold text-[#1b1c1a] leading-tight"><?= htmlspecialchars($r['role_name']) ?></h3>
    <p class="text-xs text-[#747780] mt-1 line-clamp-2"><?= htmlspecialchars($r['description'] ?: 'Custom role with defined operational boundaries') ?></p>
    <div class="flex items-center justify-between mt-4 pt-3 border-t border-[#e3e2df]/60">
      <span class="text-xs font-semibold text-[#885200]"><?= intval($r['user_count']) ?> Users Assigned</span>
      <span class="text-xs text-[#002452] font-medium flex items-center gap-0.5">
        <?= $isSelected ? 'Configuring' : 'Configure' ?>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
      </span>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<!-- Permission Matrix Form for Selected Role -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mb-12">
  <div class="px-6 py-5 border-b border-[#e3e2df] flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#fbfaf8]">
    <div>
      <div class="flex items-center gap-2">
        <h3 class="font-serif text-lg font-bold text-[#1b1c1a]">Configuring Permissions: <?= htmlspecialchars($currentRole['role_name']) ?></h3>
        <?php if ($currentRole['role_id'] == 1): ?>
          <span class="text-[11px] font-bold bg-[#C6892E] text-white px-2 py-0.5 rounded-full">Root Omnipotent</span>
        <?php endif; ?>
      </div>
      <p class="text-xs text-[#54524B] mt-0.5"><?= htmlspecialchars($currentRole['description'] ?: 'Check the specific operational capabilities granted to this user group.') ?></p>
    </div>
    <div class="flex items-center gap-2">
      <button type="button" onclick="toggleAllPerms(true)" class="px-3 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">Select All</button>
      <button type="button" onclick="toggleAllPerms(false)" class="px-3 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">Deselect All</button>
    </div>
  </div>

  <form action="process.php" method="post">
    <input type="hidden" name="role_id" value="<?= $currentRole['role_id'] ?>"/>
    
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-[#f4f3f0] text-[11px] uppercase tracking-wider text-[#44474f]">
          <tr>
            <th class="px-6 py-3.5 text-left font-semibold">Module / Resource</th>
            <th class="px-4 py-3.5 text-left font-semibold">Description</th>
            <th class="px-3 py-3.5 text-center font-semibold">View</th>
            <th class="px-3 py-3.5 text-center font-semibold">Create</th>
            <th class="px-3 py-3.5 text-center font-semibold">Edit</th>
            <th class="px-3 py-3.5 text-center font-semibold">Delete</th>
            <th class="px-3 py-3.5 text-center font-semibold">Approve</th>
            <th class="px-3 py-3.5 text-center font-semibold">Export</th>
            <th class="px-3 py-3.5 text-center font-semibold">Full Manage</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#e3e2df]">
          <?php foreach ($allPermissions as $p): 
            $pName = $p['permission_name'];
            $rp = $rolePerms[$pName] ?? [];
            $isRoot = ($currentRole['role_id'] == 1);
          ?>
          <tr class="hover:bg-[#faf9f6] transition-colors">
            <td class="px-6 py-3.5 font-semibold text-[#1b1c1a]">
              <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-[#C6892E]">folder_open</span>
                <span><?= htmlspecialchars($p['module']) ?></span>
              </div>
            </td>
            <td class="px-4 py-3.5 text-xs text-[#54524B]"><?= htmlspecialchars($p['description']) ?></td>
            
            <?php foreach (['can_view', 'can_create', 'can_edit', 'can_delete', 'can_approve', 'can_export', 'can_manage'] as $act): 
              $isChecked = $isRoot || (!empty($rp[$act]) && $rp[$act] == 1);
            ?>
            <td class="px-3 py-3.5 text-center">
              <input type="checkbox" name="perms[<?= $p['permission_id'] ?>][<?= $act ?>]" value="1" 
                class="perm-checkbox rounded text-[#002452] focus:ring-[#C6892E] w-4 h-4 cursor-pointer" 
                <?= $isChecked ? 'checked' : '' ?> 
                <?= $isRoot ? 'disabled title="Super Admin automatically possesses all permissions"' : '' ?>/>
            </td>
            <?php endforeach; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="px-6 py-4 border-t border-[#e3e2df] bg-[#fbfaf8] flex justify-end gap-3">
      <a href="roles.php" class="px-4 py-2 border border-[#e3e2df] text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">Cancel</a>
      <?php if ($currentRole['role_id'] != 1): ?>
        <button type="submit" name="saverolepermissions" class="px-6 py-2 bg-[#002452] hover:bg-[#001a3d] text-white font-semibold text-sm rounded-lg shadow-sm transition-colors flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">save</span>
          <span>Save Permissions Matrix</span>
        </button>
      <?php else: ?>
        <span class="text-xs text-[#747780] self-center italic">Root Super Admin maintains permanent irrevocable full system permissions.</span>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Modal: Create Custom Role -->
<div id="modal-add-role" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#e3e2df] animate-in fade-in duration-200">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Create New Custom Role</h3>
      <button onclick="document.getElementById('modal-add-role').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="flex flex-col gap-4">
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Role Title / Name</label>
        <input type="text" name="role_name" placeholder="e.g. Examination Controller, Headmistress" required 
          class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#002452] outline-none"/>
      </div>
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Functional Description</label>
        <textarea name="description" rows="3" placeholder="Describe the responsibilities and scope of this role..." 
          class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#002452] outline-none"></textarea>
      </div>
      <div class="flex justify-end gap-2 pt-2 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-add-role').classList.add('hidden')" 
          class="px-4 py-2 border border-[#e3e2df] text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" name="addrole" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-sm font-semibold rounded-lg shadow-sm">Create Role</button>
      </div>
    </form>
  </div>
</div>

<script>
function toggleAllPerms(check) {
  document.querySelectorAll('.perm-checkbox:not([disabled])').forEach(cb => {
    cb.checked = check;
  });
}
</script>

<?php include 'includes/footer.php'; ?>
