<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal
 * User Management — dashboard/users.php
 * Module 1: Authentication & User Accounts (Connected to Database & Dynamic Roles)
 */
$dashTitle   = 'User Management';
$currentDash = 'users';
include 'includes/header.php';

$users = $database->getAllUsers();
$roles = $database->getRoles();
?>

<!-- Header & Actions -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
  <div>
    <h2 class="font-serif text-2xl font-bold text-[#002452]">Staff & System Users</h2>
    <p class="text-sm text-[#44474f] mt-1">Manage portal credentials, RBAC role designations, and security statuses.</p>
  </div>
  <button onclick="document.getElementById('modal-add-user').classList.remove('hidden')"
    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#002452] text-white rounded-lg font-semibold text-sm hover:bg-[#001a3d] transition-colors shadow-sm whitespace-nowrap">
    <span class="material-symbols-outlined text-[18px]">person_add</span>
    Create New User
  </button>
</div>

<?php if (isset($_GET['added'])): ?>
  <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center gap-2">
    <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
    <span>New user account created successfully with assigned role privileges!</span>
  </div>
<?php elseif (isset($_GET['updated'])): ?>
  <div class="mb-5 p-3.5 bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg flex items-center gap-2">
    <span class="material-symbols-outlined text-blue-600 text-[20px]">check_circle</span>
    <span>User account updated successfully!</span>
  </div>
<?php endif; ?>

<!-- Users Table -->
<div class="bg-white rounded-xl border border-[#e3e2df] shadow-sm overflow-hidden mb-12">
  <div class="px-6 py-4 border-b border-[#e3e2df] flex flex-col md:flex-row md:items-center justify-between gap-3 bg-[#fbfaf8]">
    <div class="relative flex-1 max-w-md">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#c4c6d0] text-[18px]">search</span>
      <input type="text" id="userSearchInput" onkeyup="filterUsers()" placeholder="Search staff by name, email or username..." class="w-full pl-9 pr-3 py-2 border border-[#e3e2df] rounded-lg text-sm focus:outline-none focus:border-[#002452] bg-white"/>
    </div>
    <span class="text-xs text-[#54524B] font-medium"><?= count($users) ?> Total System Accounts</span>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm" id="usersTable">
      <thead class="bg-[#f4f3f0] text-[11px] uppercase tracking-wider text-[#44474f]">
        <tr>
          <th class="px-6 py-3.5 text-left font-semibold">Staff Member</th>
          <th class="px-4 py-3.5 text-left font-semibold">Username</th>
          <th class="px-4 py-3.5 text-left font-semibold">Designated Role</th>
          <th class="px-4 py-3.5 text-left font-semibold">Email</th>
          <th class="px-4 py-3.5 text-left font-semibold">Phone</th>
          <th class="px-4 py-3.5 text-center font-semibold">Account Status</th>
          <th class="px-4 py-3.5 text-center font-semibold">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#e3e2df]">
        <?php foreach ($users as $u): 
          $initial = strtoupper(substr($u['full_name'] ?: $u['username'], 0, 1));
          $statusCls = ($u['status'] === 'Active') 
            ? 'bg-emerald-100 text-emerald-800' 
            : (($u['status'] === 'Banned') ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700');
        ?>
        <tr class="hover:bg-[#faf9f6] transition-colors">
          <td class="px-6 py-3.5 font-semibold text-[#1b1c1a]">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-full bg-[#002452]/10 text-[#002452] flex items-center justify-center font-bold text-xs shrink-0">
                <?= $initial ?>
              </div>
              <span class="staff-name"><?= htmlspecialchars($u['full_name'] ?: $u['username']) ?></span>
            </div>
          </td>
          <td class="px-4 py-3.5 font-mono text-xs text-[#002452] font-semibold staff-username"><?= htmlspecialchars($u['username']) ?></td>
          <td class="px-4 py-3.5 text-xs">
            <span class="inline-flex px-2 py-0.5 rounded-md bg-amber-50 text-[#885200] font-bold border border-amber-200/50">
              <?= htmlspecialchars($u['role_name'] ?: 'Custom Role') ?>
            </span>
          </td>
          <td class="px-4 py-3.5 text-xs text-[#747780] staff-email"><?= htmlspecialchars($u['email']) ?></td>
          <td class="px-4 py-3.5 text-xs text-[#747780]"><?= htmlspecialchars($u['phone'] ?: '—') ?></td>
          <td class="px-4 py-3.5 text-center">
            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full <?= $statusCls ?>">
              <?= htmlspecialchars($u['status']) ?>
            </span>
          </td>
          <td class="px-4 py-3.5 text-center">
            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($u)) ?>)" 
              class="p-1.5 text-[#44474f] hover:text-[#002452] hover:bg-[#002452]/8 rounded-md transition-colors" title="Edit Role / Status">
              <span class="material-symbols-outlined text-[17px]">edit</span>
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal: Create New User -->
<div id="modal-add-user" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-[#e3e2df]">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Create New Staff User</h3>
      <button onclick="document.getElementById('modal-add-user').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="flex flex-col gap-4">
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Full Legal Name</label>
        <input type="text" name="full_name" required placeholder="e.g. Asad Ullah Khan" 
          class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#002452] outline-none"/>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Username</label>
          <input type="text" name="username" required placeholder="asad.khan" 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#002452] outline-none"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Account Password</label>
          <input type="password" name="password" required placeholder="••••••••" 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#002452] outline-none"/>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Official Email Address</label>
          <input type="email" name="email" required placeholder="asad@soshg.edu" 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#002452] outline-none"/>
        </div>
        <div>
          <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Contact Phone</label>
          <input type="text" name="phone" placeholder="+92 300 1234567" 
            class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#002452] outline-none"/>
        </div>
      </div>
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Assigned Security Role</label>
        <select name="role_id" required class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#002452] outline-none bg-white">
          <?php foreach ($roles as $r): ?>
            <option value="<?= $r['role_id'] ?>" <?= ($r['role_id'] == 3) ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['role_name']) ?> (<?= htmlspecialchars($r['description'] ?: 'Active') ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex justify-end gap-2 pt-3 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-add-user').classList.add('hidden')" 
          class="px-4 py-2 border border-[#e3e2df] text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" name="adduser" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-sm font-semibold rounded-lg shadow-sm">Save Account</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Edit User Role & Status -->
<div id="modal-edit-user" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-[#e3e2df]">
    <div class="flex items-center justify-between pb-3 border-b border-[#e3e2df] mb-4">
      <h3 class="font-serif text-lg font-bold text-[#002452]">Edit Account Role &amp; Status</h3>
      <button onclick="document.getElementById('modal-edit-user').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <span class="material-symbols-outlined text-[20px]">close</span>
      </button>
    </div>
    <form action="process.php" method="post" class="flex flex-col gap-4">
      <input type="hidden" name="username" id="edit_username"/>
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">User</label>
        <input type="text" id="edit_display_name" readonly class="w-full text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-gray-600"/>
      </div>
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Designated Role</label>
        <select name="role_id" id="edit_role_id" required class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 bg-white">
          <?php foreach ($roles as $r): ?>
            <option value="<?= $r['role_id'] ?>"><?= htmlspecialchars($r['role_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-xs font-bold text-[#1b1c1a] mb-1">Account Status</label>
        <select name="status" id="edit_status" required class="w-full text-sm border border-[#c4c6d0] rounded-lg px-3 py-2 bg-white">
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
          <option value="Banned">Banned / Suspended</option>
        </select>
      </div>
      <div class="flex justify-end gap-2 pt-3 border-t border-[#e3e2df]">
        <button type="button" onclick="document.getElementById('modal-edit-user').classList.add('hidden')" 
          class="px-4 py-2 border border-[#e3e2df] text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50">Cancel</button>
        <button type="submit" name="updateuserstatus" 
          class="px-5 py-2 bg-[#002452] hover:bg-[#001a3d] text-white text-sm font-semibold rounded-lg shadow-sm">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditModal(user) {
  document.getElementById('edit_username').value = user.username;
  document.getElementById('edit_display_name').value = (user.full_name || user.username) + ' (@' + user.username + ')';
  document.getElementById('edit_role_id').value = user.role_id;
  document.getElementById('edit_status').value = user.status;
  document.getElementById('modal-edit-user').classList.remove('hidden');
}

function filterUsers() {
  const query = document.getElementById('userSearchInput').value.toLowerCase();
  const rows = document.querySelectorAll('#usersTable tbody tr');
  rows.forEach(r => {
    const text = r.textContent.toLowerCase();
    r.style.display = text.includes(query) ? '' : 'none';
  });
}
</script>

<?php include 'includes/footer.php'; ?>
