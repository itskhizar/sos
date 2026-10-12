<?php
/**
 * SOS Hermann Gmeiner School & College · Database Access Layer
 * Comprehensive OOP MySQL Database Service for all 10 Modules, RBAC, and Audit Logging
 */

include_once("constants.php");

class MySQLDB
{
   var $connection;
   var $num_active_users;
   var $num_active_guests;
   var $num_members;

   function __construct()
   {
      $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (!$this->connection) {
         die('Database Connection Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
      }
      mysqli_set_charset($this->connection, "utf8mb4");

      $this->num_members = -1;
      if (defined('TRACK_VISITORS') && TRACK_VISITORS) {
         $this->calcNumActiveUsers();
         $this->calcNumActiveGuests();
      }
   }

   function esc($val)
   {
      if ($val === null) return '';
      return mysqli_real_escape_string($this->connection, trim($val));
   }

   function query($q)
   {
      return mysqli_query($this->connection, $q);
   }

   function resolveUserId($usernameOrId)
   {
      if (empty($usernameOrId)) {
         $r = $this->query("SELECT userid FROM " . TBL_USERS . " LIMIT 1");
         return ($r && mysqli_num_rows($r) > 0) ? mysqli_fetch_assoc($r)['userid'] : null;
      }
      $val = $this->esc(trim($usernameOrId));
      $q1 = "SELECT userid FROM " . TBL_USERS . " WHERE userid = '$val' LIMIT 1";
      $r1 = $this->query($q1);
      if ($r1 && mysqli_num_rows($r1) > 0) {
         return mysqli_fetch_assoc($r1)['userid'];
      }
      $q2 = "SELECT userid FROM " . TBL_USERS . " WHERE username = '$val' LIMIT 1";
      $r2 = $this->query($q2);
      if ($r2 && mysqli_num_rows($r2) > 0) {
         return mysqli_fetch_assoc($r2)['userid'];
      }
      $q3 = "SELECT userid FROM " . TBL_USERS . " LIMIT 1";
      $r3 = $this->query($q3);
      return ($r3 && mysqli_num_rows($r3) > 0) ? mysqli_fetch_assoc($r3)['userid'] : null;
   }

   function resolveUserIdSql($usernameOrId)
   {
      $uid = $this->resolveUserId($usernameOrId);
      return $uid ? "'$uid'" : "NULL";
   }

   // -------------------------------------------------------------
   // USER AUTHENTICATION & CORE USER MANAGEMENT
   // -------------------------------------------------------------

   function confirmUserPass($username, $password)
   {
      $username = $this->esc($username);
      $q = "SELECT password FROM " . TBL_USERS . " WHERE username = '$username' AND status = 'Active'";
      $result = $this->query($q);
      if (!$result || (mysqli_num_rows($result) < 1)) {
         return 1; // User not found or inactive
      }
      $dbarray = mysqli_fetch_assoc($result);
      $dbpass = $dbarray['password'];

      // Support password_hash() as well as md5 fallback
      if (password_verify($password, $dbpass) || md5($password) === $dbpass || $password === $dbpass) {
         return 0; // Success
      } else {
         return 2; // Invalid password
      }
   }

   function confirmUserID($username, $userid)
   {
      $username = $this->esc($username);
      $userid = $this->esc($userid);
      $q = "SELECT userid FROM " . TBL_USERS . " WHERE username = '$username'";
      $result = $this->query($q);
      if (!$result || (mysqli_num_rows($result) < 1)) return 1;
      $dbarray = mysqli_fetch_assoc($result);
      if ($userid === $dbarray['userid']) return 0;
      return 2;
   }

   function usernameTaken($username)
   {
      $username = $this->esc($username);
      $q = "SELECT username FROM " . TBL_USERS . " WHERE username = '$username'";
      $result = $this->query($q);
      return ($result && mysqli_num_rows($result) > 0);
   }

   function emailTaken($email)
   {
      $email = $this->esc($email);
      $q = "SELECT email FROM " . TBL_USERS . " WHERE email = '$email'";
      $result = $this->query($q);
      return ($result && mysqli_num_rows($result) > 0);
   }

   function getUserInfo($username)
   {
      $username = $this->esc($username);
      $q = "SELECT u.*, r.role_name, r.description as role_desc 
            FROM " . TBL_USERS . " u 
            LEFT JOIN " . TBL_ROLES . " r ON u.role_id = r.role_id 
            WHERE u.username = '$username'";
      $result = $this->query($q);
      if (!$result || (mysqli_num_rows($result) < 1)) return null;
      return mysqli_fetch_assoc($result);
   }

   function clientdata($username)
   {
      return $this->getUserInfo($username);
   }

   function loginsession($username)
   {
      return $this->getUserInfo($username);
   }

   function updateUserField($username, $field, $value)
   {
      $username = $this->esc($username);
      $field = $this->esc($field);
      $value = $this->esc($value);
      $q = "UPDATE " . TBL_USERS . " SET $field = '$value', updated_at = NOW() WHERE username = '$username'";
      return $this->query($q);
   }

   function addNewUser($username, $password, $email, $role_id = ROLE_ADMISSIONS_ID, $full_name = '', $phone = '')
   {
      $username = $this->esc($username);
      $hashedPass = password_hash($password, PASSWORD_DEFAULT);
      $userid = md5(uniqid(rand(), true));
      $email = $this->esc($email);
      $role_id = intval($role_id);
      $full_name = $this->esc($full_name ?: $username);
      $phone = $this->esc($phone);

      $q = "INSERT INTO " . TBL_USERS . " 
            (username, password, userid, userlevel, email, role_id, full_name, phone, status, created_at)
            VALUES 
            ('$username', '$hashedPass', '$userid', 1, '$email', $role_id, '$full_name', '$phone', 'Active', NOW())";
      if ($this->query($q)) {
         $this->logAudit($username, 'CREATE_USER', 'users', 'users', $username, '', "Created user $username with role $role_id");
         return true;
      }
      return false;
   }

   function getAllUsers()
   {
      $q = "SELECT u.*, r.role_name 
            FROM " . TBL_USERS . " u 
            LEFT JOIN " . TBL_ROLES . " r ON u.role_id = r.role_id 
            ORDER BY u.created_at DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function updateUserStatus($username, $status, $role_id = null)
   {
      $username = $this->esc($username);
      $status = in_array($status, ['Active', 'Inactive', 'Banned']) ? $status : 'Active';
      $roleSql = $role_id ? ", role_id = " . intval($role_id) : "";
      $q = "UPDATE " . TBL_USERS . " SET status = '$status' $roleSql, updated_at = NOW() WHERE username = '$username'";
      return $this->query($q);
   }

   function uploadimage($username, $image)
   {
      $username = $this->esc($username);
      $image = $this->esc($image);
      $q = "UPDATE " . TBL_USERS . " SET parent_directory = '$image', updated_at = NOW() WHERE username = '$username'";
      return $this->query($q);
   }

   function change_accountdetails($username, $displayname, $email, $phone)
   {
      $username = $this->esc($username);
      $displayname = $this->esc($displayname);
      $email = $this->esc($email);
      $phone = $this->esc($phone);
      $q = "UPDATE " . TBL_USERS . " SET email = '$email', full_name = '$displayname', phone = '$phone', updated_at = NOW() WHERE username = '$username'";
      return $this->query($q);
   }

   function changepassword($username, $curpass, $newpass)
   {
      $username = $this->esc($username);
      $hashed = password_hash($newpass, PASSWORD_DEFAULT);
      $q = "UPDATE " . TBL_USERS . " SET password = '$hashed', updated_at = NOW() WHERE username = '$username'";
      return $this->query($q);
   }

   // -------------------------------------------------------------
   // CENTRALIZED FLEXIBLE DATA ROUTER: groupdata()
   // Single unified interface for fetching lists, datasets, and HTML dropdown options
   // -------------------------------------------------------------
   function groupdata($type, $param = null)
   {
      $type = trim($type);

      switch ($type) {
         // Dropdown helpers
         case 'sessions_dropdown':
            $sessions = $this->getAllSessions();
            $html = '';
            foreach ($sessions as $s) {
               $sel = ($param !== null && $param == $s['session_id']) || (!empty($s['is_active']) && $param === null) ? 'selected' : '';
               $html .= '<option value="' . $s['session_id'] . '" ' . $sel . '>' . htmlspecialchars($s['session_name']) . ($s['is_active'] ? ' (Active)' : '') . '</option>';
            }
            echo $html;
            return $html;

         case 'classes_dropdown':
            $classes = $this->getAllClasses();
            $html = '';
            foreach ($classes as $c) {
               $sel = ($param !== null && $param == $c['class_id']) ? 'selected' : '';
               $html .= '<option value="' . $c['class_id'] . '" ' . $sel . '>' . htmlspecialchars($c['class_name']) . '</option>';
            }
            echo $html;
            return $html;

         case 'sections_dropdown':
            $class_id = intval($param);
            $sections = $class_id > 0 ? $this->getSectionsByClass($class_id) : $this->getAllSections();
            $html = '';
            foreach ($sections as $sec) {
               $html .= '<option value="' . $sec['section_id'] . '">' . htmlspecialchars($sec['section_name']) . ' (' . htmlspecialchars($sec['class_name'] ?? 'Section') . ')</option>';
            }
            echo $html;
            return $html;

         case 'fee_types_dropdown':
            $types = $this->getFeeTypes();
            $html = '';
            foreach ($types as $ft) {
               $sel = ($param !== null && $param == $ft['fee_type_id']) ? 'selected' : '';
               $html .= '<option value="' . $ft['fee_type_id'] . '" ' . $sel . '>' . htmlspecialchars($ft['fee_type_name']) . '</option>';
            }
            echo $html;
            return $html;

         case 'concession_types_dropdown':
            $types = $this->getConcessionTypes();
            $html = '';
            foreach ($types as $ct) {
               $sel = ($param !== null && $param == $ct['concession_type_id']) ? 'selected' : '';
               $html .= '<option value="' . $ct['concession_type_id'] . '" ' . $sel . '>' . htmlspecialchars($ct['type_name']) . ' (' . htmlspecialchars($ct['value_type']) . ')</option>';
            }
            echo $html;
            return $html;

         case 'roles_dropdown':
            $roles = $this->getRoles();
            $html = '';
            foreach ($roles as $r) {
               $sel = ($param !== null && $param == $r['role_id']) ? 'selected' : '';
               $html .= '<option value="' . $r['role_id'] . '" ' . $sel . '>' . htmlspecialchars($r['role_name']) . '</option>';
            }
            echo $html;
            return $html;

         case 'students_dropdown':
            $students = $this->getAllStudents('Active');
            $html = '';
            foreach ($students as $st) {
               $sel = ($param !== null && $param == $st['student_id']) ? 'selected' : '';
               $html .= '<option value="' . $st['student_id'] . '" ' . $sel . '>' . htmlspecialchars($st['first_name'] . ' ' . $st['last_name']) . ' (' . htmlspecialchars($st['registration_no']) . ')</option>';
            }
            echo $html;
            return $html;

         // Direct dataset fetching
         case 'sessions':
            return $this->getAllSessions();

         case 'active_session':
            return $this->getActiveSession();

         case 'classes':
            return $this->getAllClasses();

         case 'sections':
            return is_numeric($param) && intval($param) > 0 ? $this->getSectionsByClass(intval($param)) : $this->getAllSections();

         case 'enquiries':
            return $this->getAllEnquiries(is_array($param) ? $param : []);

         case 'applications':
            $status = is_string($param) ? $param : ($param['status'] ?? '');
            $class_id = is_array($param) ? intval($param['class_id'] ?? 0) : 0;
            $session_id = is_array($param) ? intval($param['session_id'] ?? 0) : 0;
            return $this->getAllApplications($status, $class_id, $session_id);

         case 'entry_tests':
            $session_id = is_array($param) ? intval($param['session_id'] ?? 0) : 0;
            $class_id = is_array($param) ? intval($param['class_id'] ?? 0) : 0;
            return $this->getAllEntryTests($session_id, $class_id);

         case 'students':
            $status = is_string($param) ? $param : ($param['status'] ?? '');
            $class_id = is_array($param) ? intval($param['class_id'] ?? 0) : 0;
            $session_id = is_array($param) ? intval($param['session_id'] ?? 0) : 0;
            $search = is_array($param) ? ($param['search'] ?? '') : '';
            return $this->getAllStudents($status, $class_id, $session_id, $search);

         case 'enrollments':
            $session_id = is_array($param) ? intval($param['session_id'] ?? 0) : 0;
            $class_id = is_array($param) ? intval($param['class_id'] ?? 0) : 0;
            $status = is_array($param) ? ($param['status'] ?? '') : (is_string($param) ? $param : '');
            return $this->getAllEnrollments($session_id, $class_id, $status);

         case 'fee_types':
            return $this->getFeeTypes();

         case 'fee_structures':
            $session_id = is_array($param) ? intval($param['session_id'] ?? 0) : 0;
            $class_id = is_array($param) ? intval($param['class_id'] ?? 0) : 0;
            return $this->getFeeStructures($session_id, $class_id);

         case 'fee_charges':
            return $this->getAllFeeCharges(is_array($param) ? $param : []);

         case 'concessions':
            return $this->getAllConcessions();

         case 'concession_types':
            return $this->getConcessionTypes();

         case 'fines':
            return $this->getAllFines(is_string($param) ? $param : '');

         case 'challans':
            $status = is_array($param) ? ($param['status'] ?? '') : (is_string($param) ? $param : '');
            $search = is_array($param) ? ($param['search'] ?? '') : '';
            return $this->getAllChallans($status, $search);

         case 'payments':
            return $this->getAllPayments(is_string($param) ? $param : '');

         case 'receipts':
            return is_numeric($param) ? $this->getReceipt(intval($param)) : $this->getAllPayments('Verified');

         case 'roles':
            return $this->getRoles();

         case 'users':
            return $this->getAllUsers(is_array($param) ? $param : []);

         case 'audit_logs':
            $limit = is_numeric($param) ? intval($param) : (is_array($param) ? intval($param['limit'] ?? 100) : 100);
            $module = is_array($param) ? ($param['module'] ?? '') : '';
            return $this->getAuditLogs($limit, $module);

         case 'dashboard_kpis':
            return $this->getDashboardKPIs();

         case 'admission_cycles':
            return $this->getAllAdmissionCycles();

         case 'active_admission_cycle':
            return $this->getActiveAdmissionCycle();

         case 'job_openings':
            return $this->getAllJobs();

         case 'open_jobs':
            return $this->getOpenJobs();

         case 'job_applications':
            return $this->getJobApplications(is_numeric($param) ? intval($param) : 0);

         default:
            return [];
      }
   }

   // -------------------------------------------------------------
   // DYNAMIC ROLES & PERMISSIONS (RBAC)
   // -------------------------------------------------------------

   function getRoles()
   {
      $q = "SELECT r.*, COUNT(u.username) as user_count 
            FROM " . TBL_ROLES . " r 
            LEFT JOIN " . TBL_USERS . " u ON r.role_id = u.role_id 
            GROUP BY r.role_id 
            ORDER BY r.role_id ASC";
      $res = $this->query($q);
      $roles = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $roles[] = $r;
      }
      return $roles;
   }

   function getRole($role_id)
   {
      $role_id = intval($role_id);
      $q = "SELECT * FROM " . TBL_ROLES . " WHERE role_id = $role_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function addRole($role_name, $description, $is_active = 1)
   {
      $role_name = $this->esc($role_name);
      $description = $this->esc($description);
      $is_active = $is_active ? 1 : 0;
      $q = "INSERT INTO " . TBL_ROLES . " (role_name, description, is_active, created_at) 
            VALUES ('$role_name', '$description', $is_active, NOW())";
      if ($this->query($q)) {
         return mysqli_insert_id($this->connection);
      }
      return false;
   }

   function updateRole($role_id, $role_name, $description, $is_active = 1)
   {
      $role_id = intval($role_id);
      $role_name = $this->esc($role_name);
      $description = $this->esc($description);
      $is_active = $is_active ? 1 : 0;
      $q = "UPDATE " . TBL_ROLES . " 
            SET role_name = '$role_name', description = '$description', is_active = $is_active, updated_at = NOW() 
            WHERE role_id = $role_id";
      return $this->query($q);
   }

   function getPermissions()
   {
      $q = "SELECT * FROM " . TBL_PERMISSIONS . " ORDER BY permission_id ASC";
      $res = $this->query($q);
      $perms = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $perms[] = $r;
      }
      return $perms;
   }

   function getRolePermissions($role_id)
   {
      $role_id = intval($role_id);
      $q = "SELECT rp.*, p.permission_name, p.module, p.description 
            FROM " . TBL_ROLE_PERMISSIONS . " rp 
            JOIN " . TBL_PERMISSIONS . " p ON rp.permission_id = p.permission_id 
            WHERE rp.role_id = $role_id";
      $res = $this->query($q);
      $perms = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) {
            $perms[$r['permission_name']] = $r;
         }
      }
      return $perms;
   }

   function checkRolePermission($role_id, $module, $action = 'view')
   {
      $role_id = intval($role_id);
      // Role 1 (Super Admin) has omnipotent access
      if ($role_id === ROLE_SUPER_ADMIN_ID) return true;

      $module = $this->esc($module);
      $actionCol = 'can_' . $this->esc($action);
      $allowedCols = ['can_view', 'can_create', 'can_edit', 'can_delete', 'can_approve', 'can_export', 'can_manage'];
      if (!in_array($actionCol, $allowedCols)) $actionCol = 'can_view';

      $q = "SELECT rp.$actionCol, rp.can_manage 
            FROM " . TBL_ROLE_PERMISSIONS . " rp 
            JOIN " . TBL_PERMISSIONS . " p ON rp.permission_id = p.permission_id 
            WHERE rp.role_id = $role_id AND p.permission_name = '$module'";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         $r = mysqli_fetch_assoc($res);
         return ($r[$actionCol] == 1 || $r['can_manage'] == 1);
      }
      return false;
   }

   function saveRolePermissions($role_id, $permData)
   {
      $role_id = intval($role_id);
      foreach ($permData as $perm_id => $actions) {
         $perm_id = intval($perm_id);
         $v = !empty($actions['can_view']) ? 1 : 0;
         $c = !empty($actions['can_create']) ? 1 : 0;
         $e = !empty($actions['can_edit']) ? 1 : 0;
         $d = !empty($actions['can_delete']) ? 1 : 0;
         $a = !empty($actions['can_approve']) ? 1 : 0;
         $x = !empty($actions['can_export']) ? 1 : 0;
         $m = !empty($actions['can_manage']) ? 1 : 0;

         $q = "INSERT INTO " . TBL_ROLE_PERMISSIONS . " 
               (role_id, permission_id, can_view, can_create, can_edit, can_delete, can_approve, can_export, can_manage)
               VALUES ($role_id, $perm_id, $v, $c, $e, $d, $a, $x, $m)
               ON DUPLICATE KEY UPDATE 
               can_view = $v, can_create = $c, can_edit = $e, can_delete = $d, 
               can_approve = $a, can_export = $x, can_manage = $m";
         $this->query($q);
      }
      return true;
   }

   // -------------------------------------------------------------
   // MODULE 01: ACADEMIC SETUP (SESSIONS, CLASSES, SECTIONS)
   // -------------------------------------------------------------

   function getActiveSession()
   {
      $q = "SELECT * FROM " . TBL_ACADEMIC_SESSIONS . " WHERE is_active = 1 LIMIT 1";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         return mysqli_fetch_assoc($res);
      }
      // Fallback to most recent session
      $q2 = "SELECT * FROM " . TBL_ACADEMIC_SESSIONS . " ORDER BY session_id DESC LIMIT 1";
      $res2 = $this->query($q2);
      return ($res2 && mysqli_num_rows($res2) > 0) ? mysqli_fetch_assoc($res2) : null;
   }

   function getAllSessions()
   {
      $q = "SELECT * FROM " . TBL_ACADEMIC_SESSIONS . " ORDER BY session_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getSession($session_id)
   {
      $session_id = intval($session_id);
      $q = "SELECT * FROM " . TBL_ACADEMIC_SESSIONS . " WHERE session_id = $session_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function sessionNameTaken($name, $exclude_id = 0)
   {
      $name = $this->esc($name);
      $exclude_id = intval($exclude_id);
      $q = "SELECT session_id FROM " . TBL_ACADEMIC_SESSIONS . " WHERE session_name = '$name' AND session_id != $exclude_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0);
   }

   function addNewSession($name, $start_date, $end_date, $status = 'Planned', $is_active = 0, $user = '')
   {
      $name = $this->esc($name);
      $start_date = $this->esc($start_date);
      $end_date = $this->esc($end_date);
      $status = in_array($status, ['Planned', 'Open', 'Closed', 'Archived']) ? $status : 'Planned';
      $is_active = $is_active ? 1 : 0;
      $user = $this->esc($user);

      if ($is_active) {
         $this->query("UPDATE " . TBL_ACADEMIC_SESSIONS . " SET is_active = 0");
      }

      $uSql = $this->resolveUserIdSql($user);
      $q = "INSERT INTO " . TBL_ACADEMIC_SESSIONS . " 
            (session_name, start_date, end_date, status, is_active, created_by, created_at)
            VALUES ('$name', '$start_date', '$end_date', '$status', $is_active, $uSql, NOW())";
      if ($this->query($q)) {
         $sessionId = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'CREATE_SESSION', 'academic_setup', 'academic_sessions', $sessionId, '', "Created session $name");
         return 0;
      }
      return 2;
   }

   function updateSession($session_id, $name, $start_date, $end_date, $status = 'Planned', $is_active = 0, $user = '')
   {
      $session_id = intval($session_id);
      $name = $this->esc($name);
      $start_date = $this->esc($start_date);
      $end_date = $this->esc($end_date);
      $status = in_array($status, ['Planned', 'Open', 'Closed', 'Archived']) ? $status : 'Planned';
      $is_active = $is_active ? 1 : 0;
      $user = $this->esc($user);

      if ($is_active) {
         $this->query("UPDATE " . TBL_ACADEMIC_SESSIONS . " SET is_active = 0 WHERE session_id != $session_id");
      }

      $q = "UPDATE " . TBL_ACADEMIC_SESSIONS . " 
            SET session_name = '$name', start_date = '$start_date', end_date = '$end_date', 
                status = '$status', is_active = $is_active, updated_at = NOW() 
            WHERE session_id = $session_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'UPDATE_SESSION', 'academic_setup', 'academic_sessions', $session_id, '', "Updated session $name");
         return 0;
      }
      return 2;
   }

   function setActiveSession($session_id, $user = '')
   {
      $session_id = intval($session_id);
      $this->query("UPDATE " . TBL_ACADEMIC_SESSIONS . " SET is_active = 0");
      $q = "UPDATE " . TBL_ACADEMIC_SESSIONS . " SET is_active = 1, status = 'Open' WHERE session_id = $session_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'SET_ACTIVE_SESSION', 'academic_setup', 'academic_sessions', $session_id, '', "Set active session #$session_id");
         return 0;
      }
      return 2;
   }

   function getAllClasses()
   {
      $q = "SELECT c.*, 
            (SELECT COUNT(*) FROM " . TBL_SECTIONS . " s WHERE s.class_id = c.class_id AND s.is_active = 1) as section_count 
            FROM " . TBL_CLASSES . " c 
            ORDER BY c.class_order ASC, c.class_id ASC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getClass($class_id)
   {
      $class_id = intval($class_id);
      $q = "SELECT * FROM " . TBL_CLASSES . " WHERE class_id = $class_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function classNameTaken($name, $exclude_id = 0)
   {
      $name = $this->esc($name);
      $exclude_id = intval($exclude_id);
      $q = "SELECT class_id FROM " . TBL_CLASSES . " WHERE class_name = '$name' AND class_id != $exclude_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0);
   }

   function addNewClass($name, $order = 0, $description = '', $is_active = 1, $user = '')
   {
      $name = $this->esc($name);
      $order = intval($order);
      $description = $this->esc($description);
      $is_active = $is_active ? 1 : 0;
      $user = $this->esc($user);

      $q = "INSERT INTO " . TBL_CLASSES . " (class_name, class_order, description, is_active, created_at)
            VALUES ('$name', $order, '$description', $is_active, NOW())";
      if ($this->query($q)) {
         $classId = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'CREATE_CLASS', 'academic_setup', 'classes', $classId, '', "Created class $name");
         return 0;
      }
      return 2;
   }

   function updateClass($class_id, $name, $order = 0, $description = '', $is_active = 1, $user = '')
   {
      $class_id = intval($class_id);
      $name = $this->esc($name);
      $order = intval($order);
      $description = $this->esc($description);
      $is_active = $is_active ? 1 : 0;
      $user = $this->esc($user);

      $q = "UPDATE " . TBL_CLASSES . " 
            SET class_name = '$name', class_order = $order, description = '$description', 
                is_active = $is_active, updated_at = NOW() 
            WHERE class_id = $class_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'UPDATE_CLASS', 'academic_setup', 'classes', $class_id, '', "Updated class $name");
         return 0;
      }
      return 2;
   }

   function getOrCreateClassSession($session_id, $class_id)
   {
      $session_id = intval($session_id);
      $class_id = intval($class_id);
      $q = "SELECT class_session_id FROM " . TBL_CLASS_SESSIONS . " WHERE session_id = $session_id AND class_id = $class_id";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         $r = mysqli_fetch_assoc($res);
         return $r['class_session_id'];
      }
      $ins = "INSERT INTO " . TBL_CLASS_SESSIONS . " (session_id, class_id, is_active, created_at) VALUES ($session_id, $class_id, 1, NOW())";
      $this->query($ins);
      return mysqli_insert_id($this->connection);
   }

   function getAllSections()
   {
      $q = "SELECT s.*, c.class_name 
            FROM " . TBL_SECTIONS . " s 
            LEFT JOIN " . TBL_CLASSES . " c ON s.class_id = c.class_id 
            ORDER BY c.class_order ASC, s.section_name ASC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getSectionsByClass($class_id)
   {
      $class_id = intval($class_id);
      $q = "SELECT * FROM " . TBL_SECTIONS . " WHERE class_id = $class_id AND is_active = 1 ORDER BY section_name ASC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function addNewSection($session_id, $class_id, $name, $capacity = 35, $is_active = 1, $user = '')
   {
      $class_id = intval($class_id);
      $session_id = intval($session_id);
      $name = $this->esc($name);
      $capacity = intval($capacity);
      $is_active = $is_active ? 1 : 0;
      $user = $this->esc($user);

      // Check if section already exists for this class
      $chk = "SELECT section_id FROM " . TBL_SECTIONS . " WHERE class_id = $class_id AND section_name = '$name' LIMIT 1";
      $chkRes = $this->query($chk);
      if ($chkRes && mysqli_num_rows($chkRes) > 0) {
         $section_id = mysqli_fetch_assoc($chkRes)['section_id'];
         if ($session_id > 0) {
            $class_session_id = $this->getOrCreateClassSession($session_id, $class_id);
            $this->getOrCreateSectionSession($section_id, $class_session_id, $name, $capacity);
         }
         return 0;
      }

      $q = "INSERT INTO " . TBL_SECTIONS . " (class_id, section_name, capacity, is_active, created_at)
            VALUES ($class_id, '$name', $capacity, $is_active, NOW())";
      if ($this->query($q)) {
         $section_id = mysqli_insert_id($this->connection);
         // Also maintain section_sessions
         if ($session_id > 0) {
            $class_session_id = $this->getOrCreateClassSession($session_id, $class_id);
            $q2 = "INSERT INTO " . TBL_SECTION_SESSIONS . " (class_session_id, section_id, section_name, capacity, is_active, created_at)
                   VALUES ($class_session_id, $section_id, '$name', $capacity, $is_active, NOW())";
            $this->query($q2);
         }
         $this->logAudit($user, 'CREATE_SECTION', 'academic_setup', 'sections', $section_id, '', "Created section $name for class $class_id");
         return 0;
      }
      return 2;
   }

   function updateSection($section_id, $class_id, $name, $capacity = 35, $is_active = 1, $user = '')
   {
      $section_id = intval($section_id);
      $class_id = intval($class_id);
      $name = $this->esc($name);
      $capacity = intval($capacity);
      $is_active = $is_active ? 1 : 0;
      $user = $this->esc($user);

      $q = "UPDATE " . TBL_SECTIONS . " 
            SET class_id = $class_id, section_name = '$name', capacity = $capacity, is_active = $is_active, updated_at = NOW() 
            WHERE section_id = $section_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'UPDATE_SECTION', 'academic_setup', 'sections', $section_id, '', "Updated section $name");
         return 0;
      }
      return 2;
   }

   function getOrCreateSectionSession($section_id, $class_session_id, $name = 'A', $capacity = 35)
   {
      $section_id = intval($section_id);
      $class_session_id = intval($class_session_id);
      $name = $this->esc($name);
      $capacity = intval($capacity);

      $q = "SELECT section_session_id FROM " . TBL_SECTION_SESSIONS . " 
            WHERE class_session_id = $class_session_id AND section_id = $section_id LIMIT 1";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         $r = mysqli_fetch_assoc($res);
         return $r['section_session_id'];
      }
      $ins = "INSERT INTO " . TBL_SECTION_SESSIONS . " 
              (class_session_id, section_id, section_name, capacity, is_active, created_at)
              VALUES ($class_session_id, $section_id, '$name', $capacity, 1, NOW())";
      $this->query($ins);
      return mysqli_insert_id($this->connection);
   }

   // -------------------------------------------------------------
   // MODULE 02: INQUIRY MANAGEMENT & FOLLOW-UPS
   // -------------------------------------------------------------

   function generateEnquiryNo()
   {
      $prefix = 'ENQ-' . date('Y') . '-';
      $q = "SELECT enquiry_no FROM " . TBL_ENQUIRIES . " WHERE enquiry_no LIKE '$prefix%' ORDER BY enquiry_id DESC LIMIT 1";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         $lastNo = mysqli_fetch_assoc($res)['enquiry_no'];
         $num = intval(substr($lastNo, strlen($prefix))) + 1;
      } else {
         $num = 1001;
      }
      return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
   }

   function getAllEnquiries($status = '', $class_id = 0, $session_id = 0)
   {
      $where = "WHERE 1=1";
      if ($status) $where .= " AND e.status = '" . $this->esc($status) . "'";
      if ($class_id) $where .= " AND e.requested_class_id = " . intval($class_id);
      if ($session_id) $where .= " AND e.requested_session_id = " . intval($session_id);

      $q = "SELECT e.*, c.class_name, s.session_name,
            (SELECT COUNT(*) FROM " . TBL_ENQUIRY_FOLLOWUPS . " f WHERE f.enquiry_id = e.enquiry_id) as followup_count,
            (SELECT MAX(followup_date) FROM " . TBL_ENQUIRY_FOLLOWUPS . " f WHERE f.enquiry_id = e.enquiry_id) as last_followup_date
            FROM " . TBL_ENQUIRIES . " e 
            LEFT JOIN " . TBL_CLASSES . " c ON e.requested_class_id = c.class_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON e.requested_session_id = s.session_id 
            $where 
            ORDER BY e.enquiry_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getEnquiry($enquiry_id)
   {
      $enquiry_id = intval($enquiry_id);
      $q = "SELECT e.*, c.class_name, s.session_name 
            FROM " . TBL_ENQUIRIES . " e 
            LEFT JOIN " . TBL_CLASSES . " c ON e.requested_class_id = c.class_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON e.requested_session_id = s.session_id 
            WHERE e.enquiry_id = $enquiry_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function addNewEnquiry($data, $user = '')
   {
      $enquiry_no = $this->generateEnquiryNo();
      $source = $this->esc($data['source'] ?? 'Website');
      $type = $this->esc($data['enquiry_type'] ?? 'Admission');
      $parent = $this->esc($data['parent_name'] ?? '');
      $student = $this->esc($data['student_name'] ?? '');
      $phone = $this->esc($data['phone'] ?? '');
      $email = $this->esc($data['email'] ?? '');
      $address = $this->esc($data['address'] ?? '');
      $message = $this->esc($data['message'] ?? '');
      $session_id = intval($data['requested_session_id'] ?? 0);
      $sessionSql = $session_id > 0 ? $session_id : "NULL";
      $class_id = intval($data['requested_class_id'] ?? 0);
      $classSql = $class_id > 0 ? $class_id : "NULL";
      $status = $this->esc($data['status'] ?? 'New');
      $assignedSql = $this->resolveUserIdSql($data['assigned_to'] ?? '');
      $follow_date = !empty($data['follow_up_date']) ? "'" . $this->esc($data['follow_up_date']) . "'" : "NULL";
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_ENQUIRIES . " 
            (enquiry_no, source, enquiry_type, parent_name, student_name, phone, email, address, message,
             requested_session_id, requested_class_id, status, assigned_to, follow_up_date, created_by, created_at)
            VALUES 
            ('$enquiry_no', '$source', '$type', '$parent', '$student', '$phone', '$email', '$address', '$message',
             $sessionSql, $classSql, '$status', $assignedSql, $follow_date, $userSql, NOW())";
      if ($this->query($q)) {
         $id = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'CREATE_ENQUIRY', 'enquiries', 'enquiries', $id, '', "Logged enquiry $enquiry_no for $student");
         return $id;
      }
      return false;
   }

   function updateEnquiry($enquiry_id, $data, $user = '')
   {
      $enquiry_id = intval($enquiry_id);
      $parent = $this->esc($data['parent_name'] ?? '');
      $student = $this->esc($data['student_name'] ?? '');
      $phone = $this->esc($data['phone'] ?? '');
      $email = $this->esc($data['email'] ?? '');
      $address = $this->esc($data['address'] ?? '');
      $message = $this->esc($data['message'] ?? '');
      $status = $this->esc($data['status'] ?? 'New');
      $assignedSql = $this->resolveUserIdSql($data['assigned_to'] ?? '');
      $follow_date = !empty($data['follow_up_date']) ? "'" . $this->esc($data['follow_up_date']) . "'" : "NULL";

      $q = "UPDATE " . TBL_ENQUIRIES . " 
            SET parent_name = '$parent', student_name = '$student', phone = '$phone', email = '$email',
                address = '$address', message = '$message', status = '$status', assigned_to = $assignedSql,
                follow_up_date = $follow_date, updated_at = NOW() 
            WHERE enquiry_id = $enquiry_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'UPDATE_ENQUIRY', 'enquiries', 'enquiries', $enquiry_id, '', "Updated enquiry #$enquiry_id status to $status");
         return true;
      }
      return false;
   }

   function addEnquiryFollowup($enquiry_id, $method, $notes, $next_date = null, $user = '')
   {
      $enquiry_id = intval($enquiry_id);
      $method = $this->esc($method);
      $notes = $this->esc($notes);
      $nextSql = $next_date ? "'" . $this->esc($next_date) . "'" : "NULL";
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_ENQUIRY_FOLLOWUPS . " 
            (enquiry_id, followup_date, method, notes, next_followup_date, status, created_by, created_at)
            VALUES ($enquiry_id, NOW(), '$method', '$notes', $nextSql, 'Completed', $userSql, NOW())";
      if ($this->query($q)) {
         // Update parent enquiry's follow up date and status if needed
         $upQ = "UPDATE " . TBL_ENQUIRIES . " 
                 SET status = 'FollowUp', follow_up_date = $nextSql, updated_at = NOW() 
                 WHERE enquiry_id = $enquiry_id";
         $this->query($upQ);
         $this->logAudit($user, 'ADD_FOLLOWUP', 'enquiries', 'enquiry_followups', $enquiry_id, '', "Added $method follow-up to enquiry #$enquiry_id");
         return true;
      }
      return false;
   }

   function getEnquiryFollowups($enquiry_id)
   {
      $enquiry_id = intval($enquiry_id);
      $q = "SELECT * FROM " . TBL_ENQUIRY_FOLLOWUPS . " WHERE enquiry_id = $enquiry_id ORDER BY followup_date DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function convertEnquiryToApplication($enquiry_id, $user = '')
   {
      $enquiry = $this->getEnquiry($enquiry_id);
      if (!$enquiry) return false;

      // Check if already converted
      if (!empty($enquiry['converted_application_id'])) {
         return $enquiry['converted_application_id'];
      }

      $appData = [
         'enquiry_id' => $enquiry_id,
         'source' => $enquiry['source'] === 'Website' ? 'Website' : 'HR',
         'student_name' => $enquiry['student_name'] ?: 'Applicant',
         'phone' => $enquiry['phone'],
         'email' => $enquiry['email'],
         'address' => $enquiry['address'],
         'parent_name' => $enquiry['parent_name'],
         'parent_phone' => $enquiry['phone'],
         'requested_session_id' => $enquiry['requested_session_id'],
         'applied_class_id' => $enquiry['requested_class_id'],
         'remarks' => "Converted from enquiry {$enquiry['enquiry_no']}. " . $enquiry['message']
      ];

      $app_id = $this->addNewApplication($appData, $user);
      if ($app_id) {
         $q = "UPDATE " . TBL_ENQUIRIES . " 
               SET status = 'Converted', converted_application_id = $app_id, updated_at = NOW() 
               WHERE enquiry_id = $enquiry_id";
         $this->query($q);
         $this->logAudit($user, 'CONVERT_ENQUIRY', 'admissions', 'admission_applications', $app_id, '', "Converted enquiry #$enquiry_id to application #$app_id");
         return $app_id;
      }
      return false;
   }

   // -------------------------------------------------------------
   // MODULE 03: ADMISSION APPLICATIONS & REQUIRED DOCUMENTS
   // -------------------------------------------------------------

   function generateApplicationNo()
   {
      $prefix = 'APP-' . date('Y') . '-';
      $q = "SELECT application_no FROM " . TBL_APPLICATIONS . " WHERE application_no LIKE '$prefix%' ORDER BY application_id DESC LIMIT 1";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         $lastNo = mysqli_fetch_assoc($res)['application_no'];
         $num = intval(substr($lastNo, strlen($prefix))) + 1;
      } else {
         $num = 1001;
      }
      return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
   }

   function getAllApplications($status = '', $class_id = 0, $session_id = 0)
   {
      $where = "WHERE 1=1";
      if ($status) $where .= " AND a.status = '" . $this->esc($status) . "'";
      if ($class_id) $where .= " AND a.applied_class_id = " . intval($class_id);
      if ($session_id) $where .= " AND a.requested_session_id = " . intval($session_id);

      $q = "SELECT a.*, c.class_name, s.session_name,
            (SELECT COUNT(*) FROM " . TBL_APPLICATION_DOCS . " d WHERE d.application_id = a.application_id) as doc_count,
            (SELECT COUNT(*) FROM " . TBL_APPLICATION_DOCS . " d WHERE d.application_id = a.application_id AND d.is_verified = 1) as verified_doc_count
            FROM " . TBL_APPLICATIONS . " a 
            LEFT JOIN " . TBL_CLASSES . " c ON a.applied_class_id = c.class_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON a.requested_session_id = s.session_id 
            $where 
            ORDER BY a.application_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getApplication($application_id)
   {
      $application_id = intval($application_id);
      $q = "SELECT a.*, c.class_name, s.session_name, e.enquiry_no 
            FROM " . TBL_APPLICATIONS . " a 
            LEFT JOIN " . TBL_CLASSES . " c ON a.applied_class_id = c.class_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON a.requested_session_id = s.session_id 
            LEFT JOIN " . TBL_ENQUIRIES . " e ON a.enquiry_id = e.enquiry_id 
            WHERE a.application_id = $application_id";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         $row = mysqli_fetch_assoc($res);
         $row['documents'] = $this->getApplicationDocuments($application_id);
         return $row;
      }
      return null;
   }

   function bulkConvertApplicationsToStudents($application_ids = [], $user = '')
   {
      $created = [];
      foreach ($application_ids as $app_id) {
         $app_id = intval($app_id);
         if ($app_id > 0) {
            $sid = $this->createStudentFromApplication($app_id, $user);
            if ($sid) {
               $created[] = [
                  'application_id' => $app_id,
                  'student_id' => $sid
               ];
            }
         }
      }
      return $created;
   }

   function getApplicationByNo($app_no)
   {
      $app_no = $this->esc($app_no);
      $q = "SELECT a.*, c.class_name, s.session_name 
            FROM " . TBL_APPLICATIONS . " a 
            LEFT JOIN " . TBL_CLASSES . " c ON a.applied_class_id = c.class_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON a.requested_session_id = s.session_id 
            WHERE a.application_no = '$app_no'";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function addNewApplication($data, $user = '')
   {
      $app_no = $this->generateApplicationNo();
      $enquiry_id = !empty($data['enquiry_id']) ? intval($data['enquiry_id']) : "NULL";
      $source = $this->esc($data['source'] ?? 'Website');
      $student = $this->esc($data['student_name'] ?? '');
      $dob = !empty($data['date_of_birth']) ? "'" . $this->esc($data['date_of_birth']) . "'" : "NULL";
      $gender = $this->esc($data['gender'] ?? 'Male');
      $blood = $this->esc($data['blood_group'] ?? '');
      $religion = $this->esc($data['religion'] ?? 'Islam');
      $nationality = $this->esc($data['nationality'] ?? 'Pakistani');
      $address = $this->esc($data['address'] ?? '');
      $phone = $this->esc($data['phone'] ?? '');
      $email = $this->esc($data['email'] ?? '');
      $parent = $this->esc($data['parent_name'] ?? '');
      $parent_phone = $this->esc($data['parent_phone'] ?? $phone);
      $parent_email = $this->esc($data['parent_email'] ?? $email);
      $session_id = intval($data['requested_session_id'] ?? 0);
      $sessionSql = $session_id > 0 ? $session_id : "NULL";
      $class_id = intval($data['applied_class_id'] ?? 0);
      $classSql = $class_id > 0 ? $class_id : "NULL";
      $status = $this->esc($data['status'] ?? 'Submitted');
      $remarks = $this->esc($data['remarks'] ?? '');
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_APPLICATIONS . " 
            (application_no, enquiry_id, source, student_name, date_of_birth, gender, blood_group, religion, nationality,
             address, phone, email, parent_name, parent_phone, parent_email, requested_session_id, applied_class_id,
             status, remarks, applied_on, created_by, created_at)
            VALUES 
            ('$app_no', $enquiry_id, '$source', '$student', $dob, '$gender', '$blood', '$religion', '$nationality',
             '$address', '$phone', '$email', '$parent', '$parent_phone', '$parent_email', $sessionSql, $classSql,
             '$status', '$remarks', NOW(), $userSql, NOW())";
      if ($this->query($q)) {
         $id = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'CREATE_APPLICATION', 'admissions', 'admission_applications', $id, '', "Created application $app_no for $student");
         return $id;
      }
      return false;
   }

   function updateApplicationStatus($application_id, $status, $remarks = '', $user = '')
   {
      $application_id = intval($application_id);
      $status = in_array($status, ['Submitted', 'UnderReview', 'DocumentsRequired', 'Interview', 'Accepted', 'Waitlisted', 'Rejected', 'Cancelled', 'Converted']) ? $status : 'UnderReview';
      $remarks = $this->esc($remarks);
      $userSql = $this->resolveUserIdSql($user);

      $q = "UPDATE " . TBL_APPLICATIONS . " 
            SET status = '$status', remarks = CONCAT(IFNULL(remarks,''), '\n', '$remarks'), 
                reviewed_by = $userSql, reviewed_at = NOW(), updated_at = NOW() 
            WHERE application_id = $application_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'UPDATE_APPLICATION_STATUS', 'admissions', 'admission_applications', $application_id, '', "Updated application status to $status");
         return true;
      }
      return false;
   }

   function addApplicationDocument($application_id, $doc_type, $file_path, $uploaded_by = '')
   {
      $application_id = intval($application_id);
      $doc_type = $this->esc($doc_type);
      $file_path = $this->esc($file_path);
      $uploadedSql = $this->resolveUserIdSql($uploaded_by);

      $q = "INSERT INTO " . TBL_APPLICATION_DOCS . " 
            (application_id, document_type, file_path, is_verified, uploaded_by, uploaded_on)
            VALUES ($application_id, '$doc_type', '$file_path', 0, $uploadedSql, NOW())";
      return $this->query($q);
   }

   function getApplicationDocuments($application_id)
   {
      $application_id = intval($application_id);
      $q = "SELECT * FROM " . TBL_APPLICATION_DOCS . " WHERE application_id = $application_id ORDER BY admission_doc_id ASC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function verifyApplicationDocument($admission_doc_id, $is_verified = 1, $verified_by = '', $remarks = '')
   {
      $admission_doc_id = intval($admission_doc_id);
      $is_verified = $is_verified ? 1 : 0;
      $user = (func_num_args() >= 4) ? func_get_arg(3) : $verified_by;
      $userSql = $this->resolveUserIdSql($user);
      $q = "UPDATE " . TBL_APPLICATION_DOCS . " 
            SET is_verified = $is_verified, verified_by = $userSql, verified_on = NOW() 
            WHERE admission_doc_id = $admission_doc_id";
      return $this->query($q);
   }

   function getAllVerificationDocs($is_verified = null)
   {
      $where = "WHERE 1=1";
      if ($is_verified !== null) {
         $where .= " AND d.is_verified = " . intval($is_verified);
      }
      $q = "SELECT d.*, a.application_no, a.student_name, c.class_name 
            FROM " . TBL_APPLICATION_DOCS . " d
            JOIN " . TBL_APPLICATIONS . " a ON d.application_id = a.application_id
            LEFT JOIN " . TBL_CLASSES . " c ON a.applied_class_id = c.class_id
            $where 
            ORDER BY d.admission_doc_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   // -------------------------------------------------------------
   // MODULE 04: ENTRY TESTS, SCHEDULE & RESULTS
   // -------------------------------------------------------------

   function getAllEntryTests($session_id = 0, $class_id = 0)
   {
      $where = "WHERE 1=1";
      if ($session_id) $where .= " AND t.session_id = " . intval($session_id);
      if ($class_id) $where .= " AND t.class_id = " . intval($class_id);

      $q = "SELECT t.*, c.class_name, s.session_name,
            (SELECT COUNT(*) FROM " . TBL_ENTRY_TEST_CANDIDATES . " tc WHERE tc.test_id = t.test_id) as total_candidates,
            (SELECT COUNT(*) FROM " . TBL_ENTRY_TEST_CANDIDATES . " tc WHERE tc.test_id = t.test_id AND tc.result_status = 'Pass') as passed_count,
            (SELECT COUNT(*) FROM " . TBL_ENTRY_TEST_CANDIDATES . " tc WHERE tc.test_id = t.test_id AND tc.result_status = 'Fail') as failed_count
            FROM " . TBL_ENTRY_TESTS . " t 
            LEFT JOIN " . TBL_CLASSES . " c ON t.class_id = c.class_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON t.session_id = s.session_id 
            $where 
            ORDER BY t.test_date DESC, t.start_time ASC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getEntryTest($test_id)
   {
      $test_id = intval($test_id);
      $q = "SELECT t.*, c.class_name, s.session_name 
            FROM " . TBL_ENTRY_TESTS . " t 
            LEFT JOIN " . TBL_CLASSES . " c ON t.class_id = c.class_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON t.session_id = s.session_id 
            WHERE t.test_id = $test_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function scheduleEntryTest($data, $user = '')
   {
      $name = $this->esc($data['test_name'] ?? 'Entry Assessment');
      $session_id = intval($data['session_id'] ?? 0);
      $class_id = intval($data['class_id'] ?? 0);
      $subject = $this->esc($data['subject'] ?? 'English & Mathematics');
      $date = $this->esc($data['test_date'] ?? date('Y-m-d'));
      $start = $this->esc($data['start_time'] ?? '09:00:00');
      $end = $this->esc($data['end_time'] ?? '11:00:00');
      $venue = $this->esc($data['venue'] ?? 'Main Examination Hall');
      $total = floatval($data['total_marks'] ?? 100);
      $pass = floatval($data['passing_marks'] ?? 50);
      $instructions = $this->esc($data['instructions'] ?? '');
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_ENTRY_TESTS . " 
            (test_name, session_id, class_id, subject, test_date, start_time, end_time, venue, total_marks, passing_marks, instructions, status, created_by, created_at)
            VALUES 
            ('$name', $session_id, $class_id, '$subject', '$date', '$start', '$end', '$venue', $total, $pass, '$instructions', 'Scheduled', $userSql, NOW())";
      if ($this->query($q)) {
         $test_id = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'SCHEDULE_TEST', 'entry_tests', 'entry_tests', $test_id, '', "Scheduled entry test '$name' for Class #$class_id");
         return $test_id;
      }
      return false;
   }

   function getEligibleApplicantsForTest($session_id, $class_id)
   {
      $session_id = intval($session_id);
      $class_id = intval($class_id);

      $q = "SELECT a.*, c.class_name 
            FROM " . TBL_APPLICATIONS . " a 
            LEFT JOIN " . TBL_CLASSES . " c ON a.applied_class_id = c.class_id 
            WHERE a.requested_session_id = $session_id 
              AND a.applied_class_id = $class_id 
              AND a.status IN ('Submitted', 'UnderReview', 'Interview', 'Accepted')
            ORDER BY a.application_id ASC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function assignCandidateToTest($test_id, $application_id, $roll_no = '')
   {
      $test_id = intval($test_id);
      $application_id = intval($application_id);
      $roll_no = $this->esc($roll_no ?: ('ROLL-' . $test_id . '-' . $application_id));

      $q = "INSERT INTO " . TBL_ENTRY_TEST_CANDIDATES . " 
            (test_id, application_id, roll_number, result_status, created_at)
            VALUES ($test_id, $application_id, '$roll_no', 'Pending', NOW())
            ON DUPLICATE KEY UPDATE roll_number = '$roll_no'";
      if ($this->query($q)) {
         $candId = mysqli_insert_id($this->connection);
         if (!$candId) {
            $checkQ = "SELECT candidate_id FROM " . TBL_ENTRY_TEST_CANDIDATES . " WHERE test_id = $test_id AND application_id = $application_id";
            $res = $this->query($checkQ);
            if ($res && $r = mysqli_fetch_assoc($res)) {
               $candId = $r['candidate_id'];
            }
         }
         return $candId;
      }
      return false;
   }

   function assignApplicantToTest($test_id, $application_id, $user = '', $roll_no = '')
   {
      return $this->assignCandidateToTest($test_id, $application_id, $roll_no);
   }

   function getTestCandidates($test_id)
   {
      $test_id = intval($test_id);
      $q = "SELECT tc.*, a.application_no, a.student_name, a.parent_name, a.phone, a.email, a.status as app_status,
            c.class_name, t.total_marks, t.passing_marks 
            FROM " . TBL_ENTRY_TEST_CANDIDATES . " tc 
            JOIN " . TBL_APPLICATIONS . " a ON tc.application_id = a.application_id 
            JOIN " . TBL_ENTRY_TESTS . " t ON tc.test_id = t.test_id 
            LEFT JOIN " . TBL_CLASSES . " c ON a.applied_class_id = c.class_id 
            WHERE tc.test_id = $test_id 
            ORDER BY tc.candidate_id ASC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function recordTestResult($candidate_id, $marks, $arg3 = '', $arg4 = '', $arg5 = '')
   {
      $candidate_id = intval($candidate_id);
      $marks = floatval($marks);

      // Support both: (candidate_id, marks, remarks, evaluator) and (candidate_id, marks, status, remarks, evaluator)
      if (in_array(strtolower($arg3), ['pass', 'fail', 'absent', 'pending']) && $arg4 !== '') {
         $explicit_status = ucfirst(strtolower($arg3));
         $remarks = $this->esc($arg4);
         $evaluator = $this->esc($arg5 ?: 'admin');
      } else {
         $explicit_status = null;
         $remarks = $this->esc($arg3);
         $evaluator = $this->esc($arg4 ?: 'admin');
      }

      // Get test total & passing marks
      $q = "SELECT t.total_marks, t.passing_marks, tc.application_id 
            FROM " . TBL_ENTRY_TEST_CANDIDATES . " tc 
            JOIN " . TBL_ENTRY_TESTS . " t ON tc.test_id = t.test_id 
            WHERE tc.candidate_id = $candidate_id";
      $res = $this->query($q);
      if (!$res || mysqli_num_rows($res) === 0) return false;
      $row = mysqli_fetch_assoc($res);

      $total = $row['total_marks'] ?: 100;
      $passing = $row['passing_marks'] ?: 50;
      $pct = round(($marks / $total) * 100, 2);
      $status = $explicit_status ?: (($marks >= $passing) ? 'Pass' : 'Fail');

      $up = "UPDATE " . TBL_ENTRY_TEST_CANDIDATES . " 
             SET marks_obtained = $marks, percentage = $pct, result_status = '$status',
                 remarks = '$remarks', evaluated_by = '$evaluator', evaluated_at = NOW(), updated_at = NOW() 
             WHERE candidate_id = $candidate_id";
      if ($this->query($up)) {
         // Auto advance application status if passed
         $newAppStatus = ($status === 'Pass') ? 'Accepted' : 'Waitlisted';
         $this->updateApplicationStatus($row['application_id'], $newAppStatus, "Entry test result: $status ($marks/$total)", $evaluator);
         $this->logAudit($evaluator, 'RECORD_TEST_RESULT', 'entry_tests', 'entry_test_candidates', $candidate_id, '', "Recorded result: $status ($marks/$total)");
         return true;
      }
      return false;
   }

   // -------------------------------------------------------------
   // MODULE 05: STUDENT MANAGEMENT & 360-DEGREE PROFILE
   // -------------------------------------------------------------

   function generateRegistrationNo()
   {
      $year = date('Y');
      $prefix = "SOS-$year-";
      $q = "SELECT registration_no FROM " . TBL_STUDENTS . " WHERE registration_no LIKE '$prefix%' ORDER BY student_id DESC LIMIT 1";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         $last = mysqli_fetch_assoc($res)['registration_no'];
         $num = intval(substr($last, strlen($prefix))) + 1;
      } else {
         $num = 1;
      }
      return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
   }

   function createStudentFromApplication($application_id, $user = '')
   {
      $application_id = intval($application_id);
      $app = $this->getApplication($application_id);
      if (!$app) return false;

      // Check if student already registered for this application
      $check = "SELECT student_id FROM " . TBL_STUDENTS . " WHERE admission_application_id = $application_id LIMIT 1";
      $cRes = $this->query($check);
      if ($cRes && mysqli_num_rows($cRes) > 0) {
         return mysqli_fetch_assoc($cRes)['student_id'];
      }

      $reg_no = $this->generateRegistrationNo();
      $nameParts = explode(' ', trim($app['student_name']), 2);
      $first_name = $this->esc($nameParts[0]);
      $last_name = $this->esc($nameParts[1] ?? '');
      $dobSql = !empty($app['date_of_birth']) ? "'" . $this->esc($app['date_of_birth']) . "'" : "NULL";
      $gender = $this->esc($app['gender'] ?: 'Male');
      $blood = $this->esc($app['blood_group'] ?: '');
      $religion = $this->esc($app['religion'] ?: 'Islam');
      $nationality = $this->esc($app['nationality'] ?: 'Pakistani');
      $address = $this->esc($app['address'] ?: '');
      $phone = $this->esc($app['phone'] ?: '');
      $email = $this->esc($app['email'] ?: '');
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_STUDENTS . " 
            (registration_no, admission_application_id, first_name, last_name, date_of_birth, gender, blood_group,
             religion, nationality, address, phone, email, status, created_by, created_at)
            VALUES 
            ('$reg_no', $application_id, '$first_name', '$last_name', $dobSql, '$gender', '$blood',
             '$religion', '$nationality', '$address', '$phone', '$email', 'Active', $userSql, NOW())";
      if ($this->query($q)) {
         $student_id = mysqli_insert_id($this->connection);
         // Mark application as Converted
         $this->query("UPDATE " . TBL_APPLICATIONS . " SET status = 'Converted', updated_at = NOW() WHERE application_id = $application_id");

         // Also register guardian record if guardian name exists
         if (!empty($app['parent_name'])) {
            $gName = $this->esc($app['parent_name']);
            $gPhone = $this->esc($app['parent_phone'] ?: $phone);
            $gEmail = $this->esc($app['parent_email'] ?: $email);
            $gQ = "INSERT INTO " . TBL_USERS . " (username, password, userid, userlevel, email, role_id, full_name, phone, status, created_at)
                   VALUES ('parent_$student_id', '" . password_hash('sos@123', PASSWORD_DEFAULT) . "', '" . md5(uniqid()) . "', 1, '$gEmail', 3, '$gName', '$gPhone', 'Active', NOW())
                   ON DUPLICATE KEY UPDATE full_name = '$gName'";
            $this->query($gQ);
         }

         $this->logAudit($user, 'CREATE_STUDENT', 'students', 'students', $student_id, '', "Registered student $first_name $last_name as $reg_no");
         return $student_id;
      }
      return false;
   }

   function getAllStudents($status = '', $class_id = 0, $session_id = 0, $search = '')
   {
      $where = "WHERE 1=1";
      if ($status) $where .= " AND st.status = '" . $this->esc($status) . "'";
      if ($search) {
         $search = $this->esc($search);
         $where .= " AND (st.registration_no LIKE '%$search%' OR st.first_name LIKE '%$search%' OR st.last_name LIKE '%$search%' OR st.phone LIKE '%$search%')";
      }

      $q = "SELECT st.*, 
            e.enrollment_id, e.enrollment_no, e.status as enrollment_status,
            c.class_name, sec.section_name, s.session_name,
            app.application_no, app.parent_name
            FROM " . TBL_STUDENTS . " st 
            LEFT JOIN " . TBL_APPLICATIONS . " app ON st.admission_application_id = app.application_id 
            LEFT JOIN (
               SELECT * FROM " . TBL_ENROLLMENTS . " WHERE status = 'Active' ORDER BY enrollment_id DESC
            ) e ON e.student_id = st.student_id 
            LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
            LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
            LEFT JOIN " . TBL_SECTION_SESSIONS . " ss ON e.section_session_id = ss.section_session_id 
            LEFT JOIN " . TBL_SECTIONS . " sec ON ss.section_id = sec.section_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON cs.session_id = s.session_id 
            $where 
            ORDER BY st.student_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getStudent($student_id)
   {
      $student_id = intval($student_id);
      $q = "SELECT st.*, app.application_no, app.parent_name, app.parent_phone, app.parent_email, app.parent_cnic, app.previous_school, app.previous_class, app.previous_marks_percentage 
            FROM " . TBL_STUDENTS . " st 
            LEFT JOIN " . TBL_APPLICATIONS . " app ON st.admission_application_id = app.application_id 
            WHERE st.student_id = $student_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function getStudentByRegNo($reg_no)
   {
      $reg_no = $this->esc($reg_no);
      $q = "SELECT st.*, app.parent_name, app.parent_phone 
            FROM " . TBL_STUDENTS . " st 
            LEFT JOIN " . TBL_APPLICATIONS . " app ON st.admission_application_id = app.application_id 
            WHERE st.registration_no = '$reg_no'";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function getStudentProfileFull($student_id)
   {
      $student = $this->getStudent($student_id);
      if (!$student) return null;

      // 1. Current & Historical Enrollments
      $enrollmentsQ = "SELECT e.*, c.class_name, sec.section_name, s.session_name 
                       FROM " . TBL_ENROLLMENTS . " e 
                       LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
                       LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
                       LEFT JOIN " . TBL_SECTION_SESSIONS . " ss ON e.section_session_id = ss.section_session_id 
                       LEFT JOIN " . TBL_SECTIONS . " sec ON ss.section_id = sec.section_id 
                       LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON cs.session_id = s.session_id 
                       WHERE e.student_id = $student_id 
                       ORDER BY e.enrollment_id DESC";
      $eRes = $this->query($enrollmentsQ);
      $enrollments = [];
      if ($eRes) {
         while ($er = mysqli_fetch_assoc($eRes)) $enrollments[] = $er;
      }
      $student['enrollments'] = $enrollments;
      $student['current_enrollment'] = $enrollments[0] ?? null;

      // 2. Fee Charges & Outstanding
      $chargesQ = "SELECT fc.*, ft.fee_type_name 
                   FROM " . TBL_FEE_CHARGES . " fc 
                   JOIN " . TBL_FEE_TYPES . " ft ON fc.fee_type_id = ft.fee_type_id 
                   JOIN " . TBL_ENROLLMENTS . " e ON fc.enrollment_id = e.enrollment_id 
                   WHERE e.student_id = $student_id 
                   ORDER BY fc.billing_month DESC";
      $cRes = $this->query($chargesQ);
      $charges = [];
      $totalOutstanding = 0;
      if ($cRes) {
         while ($cr = mysqli_fetch_assoc($cRes)) {
            $charges[] = $cr;
            $totalOutstanding += floatval($cr['outstanding_amount']);
         }
      }
      $student['charges'] = $charges;
      $student['total_outstanding'] = $totalOutstanding;

      // 3. Challans
      $challansQ = "SELECT ch.*, s.session_name, c.class_name, sec.section_name,
                           st.first_name, st.last_name, st.registration_no 
                    FROM " . TBL_CHALLANS . " ch 
                    LEFT JOIN " . TBL_STUDENTS . " st ON ch.student_id = st.student_id 
                    LEFT JOIN " . TBL_ENROLLMENTS . " e ON ch.enrollment_id = e.enrollment_id 
                    LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
                    LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
                    LEFT JOIN " . TBL_SECTION_SESSIONS . " ss ON e.section_session_id = ss.section_session_id 
                    LEFT JOIN " . TBL_SECTIONS . " sec ON ss.section_id = sec.section_id 
                    LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON cs.session_id = s.session_id 
                    WHERE ch.student_id = $student_id 
                    ORDER BY ch.challan_id DESC";
      $chRes = $this->query($challansQ);
      $challans = [];
      if ($chRes) {
         while ($chr = mysqli_fetch_assoc($chRes)) $challans[] = $chr;
      }
      $student['challans'] = $challans;

      // 4. Payments
      $paymentsQ = "SELECT p.*, ch.challan_no 
                    FROM " . TBL_PAYMENTS . " p 
                    LEFT JOIN " . TBL_CHALLANS . " ch ON p.challan_id = ch.challan_id 
                    WHERE p.student_id = $student_id 
                    ORDER BY p.payment_date DESC";
      $pRes = $this->query($paymentsQ);
      $payments = [];
      if ($pRes) {
         while ($pr = mysqli_fetch_assoc($pRes)) $payments[] = $pr;
      }
      $student['payments'] = $payments;

      // 5. Concessions
      $concessionsQ = "SELECT sc.*, ct.type_name as concession_type_name, ft.fee_type_name 
                       FROM " . TBL_CONCESSIONS . " sc 
                       LEFT JOIN " . TBL_CONCESSION_TYPES . " ct ON sc.concession_type_id = ct.concession_type_id 
                       LEFT JOIN " . TBL_FEE_TYPES . " ft ON sc.fee_type_id = ft.fee_type_id 
                       JOIN " . TBL_ENROLLMENTS . " e ON sc.enrollment_id = e.enrollment_id 
                       WHERE e.student_id = $student_id";
      $cnRes = $this->query($concessionsQ);
      $concessions = [];
      if ($cnRes) {
         while ($cnr = mysqli_fetch_assoc($cnRes)) $concessions[] = $cnr;
      }
      $student['concessions'] = $concessions;

      // 6. Fines
      $finesQ = "SELECT f.* 
                 FROM " . TBL_FINES . " f 
                 JOIN " . TBL_ENROLLMENTS . " e ON f.enrollment_id = e.enrollment_id 
                 WHERE e.student_id = $student_id";
      $fRes = $this->query($finesQ);
      $fines = [];
      if ($fRes) {
         while ($fr = mysqli_fetch_assoc($fRes)) $fines[] = $fr;
      }
      $student['fines'] = $fines;

      // 7. Student Documents (from student_documents & linked admission_documents)
      $docs = [];
      $sdRes = $this->query("SELECT * FROM " . TBL_STUDENT_DOCS . " WHERE student_id = $student_id ORDER BY student_doc_id ASC");
      if ($sdRes) {
         while ($sdr = mysqli_fetch_assoc($sdRes)) {
            $docs[] = [
               'doc_id' => $sdr['student_doc_id'],
               'source' => 'student',
               'document_type' => $sdr['document_type'],
               'file_path' => $sdr['file_path'],
               'is_verified' => 1,
               'uploaded_on' => $sdr['uploaded_on']
            ];
         }
      }
      if (!empty($student['admission_application_id'])) {
         $appId = intval($student['admission_application_id']);
         $adRes = $this->query("SELECT * FROM " . TBL_APPLICATION_DOCS . " WHERE application_id = $appId ORDER BY admission_doc_id ASC");
         if ($adRes) {
            while ($adr = mysqli_fetch_assoc($adRes)) {
               $docs[] = [
                  'doc_id' => $adr['admission_doc_id'],
                  'source' => 'admission',
                  'document_type' => $adr['document_type'],
                  'file_path' => $adr['file_path'],
                  'is_verified' => intval($adr['is_verified']),
                  'uploaded_on' => $adr['uploaded_on']
               ];
            }
         }
      }
      $student['documents'] = $docs;

      return $student;
   }

   function addStudentDocument($student_id, $doc_type, $file_path, $uploaded_by = '')
   {
      $student_id = intval($student_id);
      $doc_type = $this->esc($doc_type);
      $file_path = $this->esc($file_path);
      $userSql = $this->resolveUserIdSql($uploaded_by);
      $q = "INSERT INTO " . TBL_STUDENT_DOCS . " (student_id, document_type, file_path, uploaded_by, uploaded_on)
            VALUES ($student_id, '$doc_type', '$file_path', $userSql, NOW())";
      return $this->query($q);
   }

   // -------------------------------------------------------------
   // MODULE 06: ENROLLMENT & PROMOTION
   // -------------------------------------------------------------

   function generateEnrollmentNo()
   {
      $prefix = 'ENR-' . date('Y') . '-';
      $q = "SELECT enrollment_no FROM " . TBL_ENROLLMENTS . " WHERE enrollment_no LIKE '$prefix%' ORDER BY enrollment_id DESC LIMIT 1";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         $lastNo = mysqli_fetch_assoc($res)['enrollment_no'];
         $num = intval(substr($lastNo, strlen($prefix))) + 1;
      } else {
         $num = 1001;
      }
      return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
   }

   function enrollStudent($student_id, $session_id, $class_id, $section_id = 0, $roll_no = '', $user = '')
   {
      $student_id = intval($student_id);
      $session_id = intval($session_id);
      $class_id = intval($class_id);
      $section_id = intval($section_id);
      $user = $this->esc($user);

      // Check existing active enrollment for this session
      $class_session_id = $this->getOrCreateClassSession($session_id, $class_id);

      // Resolve section_session_id safely
      $sections = $this->getSectionsByClass($class_id);
      $validSectionIds = array_column($sections, 'section_id');
      if ($section_id <= 0 || !in_array($section_id, $validSectionIds)) {
         $section_id = !empty($validSectionIds) ? intval($validSectionIds[0]) : 0;
      }
      if ($section_id <= 0) {
         $this->addNewSection($session_id, $class_id, 'A', 35, 1, $user);
         $sections = $this->getSectionsByClass($class_id);
         $section_id = !empty($sections) ? intval($sections[0]['section_id']) : 0;
      }
      $section_session_id = $this->getOrCreateSectionSession($section_id, $class_session_id);

      // Check if student already enrolled in this session
      $chk = "SELECT enrollment_id FROM " . TBL_ENROLLMENTS . " 
              WHERE student_id = $student_id AND class_session_id = $class_session_id LIMIT 1";
      $cRes = $this->query($chk);
      if ($cRes && mysqli_num_rows($cRes) > 0) {
         return mysqli_fetch_assoc($cRes)['enrollment_id'];
      }

      $enr_no = $this->generateEnrollmentNo();
      $userSql = $this->resolveUserIdSql($user);
      $q = "INSERT INTO " . TBL_ENROLLMENTS . " 
            (student_id, class_session_id, section_session_id, enrollment_no, enrollment_date, status, created_by, created_at)
            VALUES 
            ($student_id, $class_session_id, $section_session_id, '$enr_no', CURDATE(), 'Active', $userSql, NOW())";
      if ($this->query($q)) {
         $enr_id = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'ENROLL_STUDENT', 'enrollment', 'enrollments', $enr_id, '', "Enrolled student #$student_id into Class #$class_id (Session #$session_id)");
         return $enr_id;
      }
      return false;
   }

   function promoteStudent($student_id, $target_session_id, $target_class_id, $target_section_id = 0, $promoted_by = '')
   {
      $student_id = intval($student_id);
      $target_session_id = intval($target_session_id);
      $target_class_id = intval($target_class_id);

      // Mark old enrollment as Promoted
      $upOld = "UPDATE " . TBL_ENROLLMENTS . " SET status = 'Promoted', updated_at = NOW() 
                WHERE student_id = $student_id AND status = 'Active'";
      $this->query($upOld);

      // Create new enrollment record (preserves historical record!)
      return $this->enrollStudent($student_id, $target_session_id, $target_class_id, $target_section_id, '', $promoted_by);
   }

   function getAllEnrollments($session_id = 0, $class_id = 0, $status = '')
   {
      $where = "WHERE 1=1";
      if ($session_id) $where .= " AND cs.session_id = " . intval($session_id);
      if ($class_id) $where .= " AND cs.class_id = " . intval($class_id);
      if ($status) $where .= " AND e.status = '" . $this->esc($status) . "'";

      $q = "SELECT e.*, st.registration_no, st.first_name, st.last_name, st.phone,
            c.class_name, sec.section_name, s.session_name 
            FROM " . TBL_ENROLLMENTS . " e 
            JOIN " . TBL_STUDENTS . " st ON e.student_id = st.student_id 
            LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
            LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
            LEFT JOIN " . TBL_SECTION_SESSIONS . " ss ON e.section_session_id = ss.section_session_id 
            LEFT JOIN " . TBL_SECTIONS . " sec ON ss.section_id = sec.section_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON cs.session_id = s.session_id 
            $where 
            ORDER BY e.enrollment_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   // -------------------------------------------------------------
   // MODULE 07: FEE MANAGEMENT, STRUCTURES & BILLING GENERATION
   // -------------------------------------------------------------

   function getFeeTypes()
   {
      $q = "SELECT * FROM " . TBL_FEE_TYPES . " WHERE is_active = 1 ORDER BY fee_type_id ASC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function addFeeType($name, $description = '', $is_recurring = 1)
   {
      $name = $this->esc($name);
      $description = $this->esc($description);
      $is_recurring = $is_recurring ? 1 : 0;
      $q = "INSERT INTO " . TBL_FEE_TYPES . " (fee_type_name, description, is_recurring, is_active)
            VALUES ('$name', '$description', $is_recurring, 1)
            ON DUPLICATE KEY UPDATE description = '$description', is_recurring = $is_recurring";
      return $this->query($q);
   }

   function getFeeStructures($session_id = 0, $class_id = 0)
   {
      $where = "WHERE 1=1";
      if ($session_id) $where .= " AND fs.session_id = " . intval($session_id);
      if ($class_id) $where .= " AND fs.class_id = " . intval($class_id);

      $q = "SELECT fs.*, c.class_name, s.session_name,
            (SELECT COUNT(*) FROM " . TBL_FEE_STRUCTURE_ITEMS . " fsi WHERE fsi.fee_structure_id = fs.fee_structure_id) as item_count,
            (SELECT SUM(amount) FROM " . TBL_FEE_STRUCTURE_ITEMS . " fsi WHERE fsi.fee_structure_id = fs.fee_structure_id) as total_amount
            FROM " . TBL_FEE_STRUCTURES . " fs 
            LEFT JOIN " . TBL_CLASSES . " c ON fs.class_id = c.class_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON fs.session_id = s.session_id 
            $where 
            ORDER BY fs.fee_structure_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getFeeStructureItems($fee_structure_id)
   {
      $fee_structure_id = intval($fee_structure_id);
      $q = "SELECT fsi.*, ft.fee_type_name, ft.is_recurring 
            FROM " . TBL_FEE_STRUCTURE_ITEMS . " fsi 
            JOIN " . TBL_FEE_TYPES . " ft ON fsi.fee_type_id = ft.fee_type_id 
            WHERE fsi.fee_structure_id = $fee_structure_id";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function addFeeStructure($session_id, $class_id, $name, $effective_from, $items = [], $user = '')
   {
      $session_id = intval($session_id);
      $class_id = intval($class_id);
      $name = $this->esc($name);
      $from = $this->esc($effective_from ?: date('Y-m-01'));
      $userSql = $this->resolveUserIdSql($user);

      $checkQ = "SELECT fee_structure_id FROM " . TBL_FEE_STRUCTURES . " 
                 WHERE session_id = $session_id AND class_id = $class_id AND structure_name = '$name'";
      $chkRes = $this->query($checkQ);
      if ($chkRes && mysqli_num_rows($chkRes) > 0) {
         return intval(mysqli_fetch_assoc($chkRes)['fee_structure_id']);
      }

      $q = "INSERT INTO " . TBL_FEE_STRUCTURES . " 
            (session_id, class_id, structure_name, effective_from, is_active, created_by, created_at)
            VALUES ($session_id, $class_id, '$name', '$from', 1, $userSql, NOW())";
      if ($this->query($q)) {
         $fsId = mysqli_insert_id($this->connection);
         foreach ($items as $item) {
            $ftId = intval($item['fee_type_id']);
            $amt = floatval($item['amount']);
            if ($ftId > 0 && $amt > 0) {
               $iq = "INSERT INTO " . TBL_FEE_STRUCTURE_ITEMS . " (fee_structure_id, fee_type_id, amount) 
                      VALUES ($fsId, $ftId, $amt)";
               $this->query($iq);
            }
         }
         $this->logAudit($user, 'CREATE_FEE_STRUCTURE', 'fee_structure', 'fee_structures', $fsId, '', "Created fee structure '$name'");
         return $fsId;
      }
      return false;
   }

   function generateMonthlyFees($session_id, $class_id, $billing_month, $user = '')
   {
      $session_id = intval($session_id);
      $class_id = intval($class_id);
      $monthDate = date('Y-m-01', strtotime($billing_month));
      $user = $this->esc($user);

      // Find applicable fee structure
      $fsQ = "SELECT * FROM " . TBL_FEE_STRUCTURES . " 
              WHERE session_id = $session_id AND class_id = $class_id AND is_active = 1 
              ORDER BY fee_structure_id DESC LIMIT 1";
      $fsRes = $this->query($fsQ);
      if (!$fsRes || mysqli_num_rows($fsRes) === 0) {
         return ['success' => false, 'message' => 'No active fee structure found for this class and session'];
      }
      $feeStruct = mysqli_fetch_assoc($fsRes);
      $items = $this->getFeeStructureItems($feeStruct['fee_structure_id']);
      if (empty($items)) {
         return ['success' => false, 'message' => 'Fee structure has no item charges configured'];
      }

      // Find active enrollments
      $class_session_id = $this->getOrCreateClassSession($session_id, $class_id);
      $enrQ = "SELECT enrollment_id, student_id FROM " . TBL_ENROLLMENTS . " 
               WHERE class_session_id = $class_session_id AND status = 'Active'";
      $enrRes = $this->query($enrQ);
      $enrolledCount = 0;
      $generatedCount = 0;

      while ($enr = mysqli_fetch_assoc($enrRes)) {
         $enrolledCount++;
         $enrId = $enr['enrollment_id'];

         // Check applicable concession for this enrollment
         $concQ = "SELECT value FROM " . TBL_CONCESSIONS . " 
                   WHERE enrollment_id = $enrId AND is_active = 1 
                     AND CURDATE() BETWEEN effective_from AND IFNULL(effective_to, '2099-12-31') LIMIT 1";
         $concRes = $this->query($concQ);
         $concessionDiscount = ($concRes && mysqli_num_rows($concRes) > 0) ? floatval(mysqli_fetch_assoc($concRes)['value']) : 0;

         foreach ($items as $item) {
            $ftId = $item['fee_type_id'];
            $fsiId = $item['fee_structure_item_id'];
            $origAmt = floatval($item['amount']);

            // Avoid duplicate charges for the same billing month & fee item
            $dupQ = "SELECT charge_id FROM " . TBL_FEE_CHARGES . " 
                     WHERE enrollment_id = $enrId AND fee_type_id = $ftId AND billing_month = '$monthDate' LIMIT 1";
            $dRes = $this->query($dupQ);
            if ($dRes && mysqli_num_rows($dRes) > 0) continue;

            $netAmt = max(0, $origAmt - $concessionDiscount);
            $concessionApplied = min($concessionDiscount, $origAmt);

            $userSql = $this->resolveUserIdSql($user);
            $ins = "INSERT INTO " . TBL_FEE_CHARGES . " 
                    (enrollment_id, fee_structure_item_id, fee_type_id, billing_month, original_amount, concession_amount, 
                     net_amount, paid_amount, outstanding_amount, charge_type, status, generated_on, created_by)
                    VALUES 
                    ($enrId, $fsiId, $ftId, '$monthDate', $origAmt, $concessionApplied, 
                     $netAmt, 0, $netAmt, 'Recurring', 'Generated', NOW(), $userSql)";
            if ($this->query($ins)) $generatedCount++;
         }
      }

      $this->logAudit($user, 'GENERATE_FEES', 'fee_charges', 'fee_charges', 0, '', "Generated $generatedCount fee items across $enrolledCount students for $monthDate");
      return ['success' => true, 'enrolled' => $enrolledCount, 'generated' => $generatedCount];
   }

   function getAllFeeCharges($filters = [])
   {
      $where = "WHERE 1=1";
      if (!empty($filters['status'])) $where .= " AND fc.status = '" . $this->esc($filters['status']) . "'";
      if (!empty($filters['month'])) $where .= " AND fc.billing_month = '" . $this->esc($filters['month']) . "'";
      if (!empty($filters['class_id'])) $where .= " AND cs.class_id = " . intval($filters['class_id']);

      $q = "SELECT fc.*, ft.fee_type_name, st.registration_no, st.first_name, st.last_name, c.class_name 
            FROM " . TBL_FEE_CHARGES . " fc 
            JOIN " . TBL_FEE_TYPES . " ft ON fc.fee_type_id = ft.fee_type_id 
            JOIN " . TBL_ENROLLMENTS . " e ON fc.enrollment_id = e.enrollment_id 
            JOIN " . TBL_STUDENTS . " st ON e.student_id = st.student_id 
            LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
            LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
            $where 
            ORDER BY fc.billing_month DESC, fc.charge_id DESC LIMIT 200";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   // -------------------------------------------------------------
   // MODULE 08: CONCESSIONS & SCHOLARSHIPS
   // -------------------------------------------------------------

   function getConcessionTypes()
   {
      $q = "SELECT * FROM " . TBL_CONCESSION_TYPES . " ORDER BY concession_type_id ASC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function addStudentConcession($enrollment_id, $concession_type_id, $value, $from, $to = null, $reason = '', $user = '')
   {
      $enrollment_id = intval($enrollment_id);
      $concession_type_id = intval($concession_type_id);
      $value = floatval($value);
      $from = $this->esc($from ?: date('Y-m-01'));
      $toSql = $to ? "'" . $this->esc($to) . "'" : "NULL";
      $reason = $this->esc($reason);
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_CONCESSIONS . " 
            (enrollment_id, concession_type_id, value, effective_from, effective_to, reason, is_active, created_by, created_on)
            VALUES 
            ($enrollment_id, $concession_type_id, $value, '$from', $toSql, '$reason', 1, $userSql, NOW())";
      if ($this->query($q)) {
         $id = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'ADD_CONCESSION', 'concessions', 'student_concessions', $id, '', "Granted concession Rs. $value to enrollment #$enrollment_id");
         return $id;
      }
      return false;
   }

   function getAllConcessions()
   {
      $q = "SELECT sc.*, ct.type_name as concession_type_name, st.registration_no, st.first_name, st.last_name, c.class_name 
            FROM " . TBL_CONCESSIONS . " sc 
            LEFT JOIN " . TBL_CONCESSION_TYPES . " ct ON sc.concession_type_id = ct.concession_type_id 
            JOIN " . TBL_ENROLLMENTS . " e ON sc.enrollment_id = e.enrollment_id 
            JOIN " . TBL_STUDENTS . " st ON e.student_id = st.student_id 
            LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
            LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
            ORDER BY sc.concession_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   // -------------------------------------------------------------
   // MODULE 09: CHALLANS & PAYMENTS / TRANSACTIONS
   // -------------------------------------------------------------

   function generateChallanNo()
   {
      $prefix = 'CHL-' . date('ym') . '-';
      $q = "SELECT challan_no FROM " . TBL_CHALLANS . " WHERE challan_no LIKE '$prefix%' ORDER BY challan_id DESC LIMIT 1";
      $res = $this->query($q);
      if ($res && mysqli_num_rows($res) > 0) {
         $lastNo = mysqli_fetch_assoc($res)['challan_no'];
         $num = intval(substr($lastNo, strlen($prefix))) + 1;
      } else {
         $num = 1001;
      }
      return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
   }

   function generateStudentChallan($student_id, $enrollment_id = 0, $due_date = null, $user = '')
   {
      $student_id = intval($student_id);
      $user = $this->esc($user);
      $dueDate = $due_date ? $this->esc($due_date) : date('Y-m-d', strtotime('+15 days'));

      // If enrollment not specified, get latest
      if ($enrollment_id <= 0) {
         $enrQ = "SELECT enrollment_id FROM " . TBL_ENROLLMENTS . " WHERE student_id = $student_id AND status = 'Active' ORDER BY enrollment_id DESC LIMIT 1";
         $eRes = $this->query($enrQ);
         $enrollment_id = ($eRes && mysqli_num_rows($eRes) > 0) ? mysqli_fetch_assoc($eRes)['enrollment_id'] : 0;
      }

      // Compute total unpaid charges for this enrollment
      $chargesQ = "SELECT charge_id, outstanding_amount, net_amount 
                   FROM " . TBL_FEE_CHARGES . " 
                   WHERE enrollment_id = $enrollment_id AND status != 'Paid'";
      $cRes = $this->query($chargesQ);
      $totalAmt = 0;
      $chargeIds = [];
      if ($cRes) {
         while ($r = mysqli_fetch_assoc($cRes)) {
            $totalAmt += floatval($r['outstanding_amount']);
            $chargeIds[] = $r['charge_id'];
         }
      }

      // Add unpaid fines
      $fineQ = "SELECT fine_id, amount FROM " . TBL_FINES . " WHERE enrollment_id = $enrollment_id AND status = 'Pending'";
      $fRes = $this->query($fineQ);
      $fineAmt = 0;
      if ($fRes) {
         while ($fr = mysqli_fetch_assoc($fRes)) {
            $fineAmt += floatval($fr['amount']);
         }
      }
      $totalAmt += $fineAmt;

      if ($totalAmt <= 0) $totalAmt = 1500; // Minimum default invoice if no charges pending

      $challan_no = $this->generateChallanNo();
      $bankName = 'Habib Bank Limited (HBL)';
      $acctTitle = 'SOS Hermann Gmeiner School Dhodial';
      $acctNo = 'PK36HABB0001234567890101';

      $userSql = $this->resolveUserIdSql($user);
      $q = "INSERT INTO " . TBL_CHALLANS . " 
            (challan_no, student_id, enrollment_id, issue_date, due_date, requested_amount, status, 
             bank_name, bank_account_title, bank_account_no, generated_by, generated_at)
            VALUES 
            ('$challan_no', $student_id, $enrollment_id, CURDATE(), '$dueDate', $totalAmt, 'Generated',
             '$bankName', '$acctTitle', '$acctNo', $userSql, NOW())";
      if ($this->query($q)) {
         $challan_id = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'GENERATE_CHALLAN', 'challans', 'challans', $challan_id, '', "Generated Challan $challan_no for Rs. $totalAmt");
         return $challan_id;
      }
      return false;
   }

   function getAllChallans($status = '', $search = '')
   {
      $where = "WHERE 1=1";
      if ($status) $where .= " AND ch.status = '" . $this->esc($status) . "'";
      if ($search) {
         $search = $this->esc($search);
         $where .= " AND (ch.challan_no LIKE '%$search%' OR st.registration_no LIKE '%$search%' OR st.first_name LIKE '%$search%')";
      }

      $q = "SELECT ch.*, st.registration_no, st.first_name, st.last_name, c.class_name, sec.section_name 
            FROM " . TBL_CHALLANS . " ch 
            JOIN " . TBL_STUDENTS . " st ON ch.student_id = st.student_id 
            LEFT JOIN " . TBL_ENROLLMENTS . " e ON ch.enrollment_id = e.enrollment_id 
            LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
            LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
            LEFT JOIN " . TBL_SECTION_SESSIONS . " ss ON e.section_session_id = ss.section_session_id 
            LEFT JOIN " . TBL_SECTIONS . " sec ON ss.section_id = sec.section_id 
            $where 
            ORDER BY ch.challan_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getChallan($challan_id)
   {
      $challan_id = intval($challan_id);
      $q = "SELECT ch.*, st.registration_no, st.first_name, st.last_name, st.phone,
            app.parent_name, c.class_name, sec.section_name, s.session_name 
            FROM " . TBL_CHALLANS . " ch 
            JOIN " . TBL_STUDENTS . " st ON ch.student_id = st.student_id 
            LEFT JOIN " . TBL_APPLICATIONS . " app ON st.admission_application_id = app.application_id 
            LEFT JOIN " . TBL_ENROLLMENTS . " e ON ch.enrollment_id = e.enrollment_id 
            LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
            LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
            LEFT JOIN " . TBL_SECTION_SESSIONS . " ss ON e.section_session_id = ss.section_session_id 
            LEFT JOIN " . TBL_SECTIONS . " sec ON ss.section_id = sec.section_id 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON cs.session_id = s.session_id 
            WHERE ch.challan_id = $challan_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function recordPayment($challan_id, $amount, $payment_date, $method = 'Cash', $ref_no = '', $user = '', $remarks = '')
   {
      $challan_id = intval($challan_id);
      $challan = $this->getChallan($challan_id);
      if (!$challan) return false;

      $amount = floatval($amount);
      $student_id = $challan['student_id'];
      $payment_date = $this->esc($payment_date ?: date('Y-m-d'));
      $bank_name = $this->esc($method === 'Cash' ? 'Cash Counter' : 'Habib Bank Limited');
      $ref_no = $this->esc($ref_no ?: ('TXN-' . date('Ymd') . '-' . rand(100, 999)));
      $user = $this->esc($user);
      $remarks = $this->esc($remarks);
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_PAYMENTS . " 
            (challan_id, student_id, payment_date, bank_name, bank_reference_no, amount, 
             submitted_on, submitted_by, verification_status, verified_by, verified_on, verification_remarks, created_at)
            VALUES 
            ($challan_id, $student_id, '$payment_date', '$bank_name', '$ref_no', $amount,
             NOW(), $userSql, 'Verified', $userSql, NOW(), '$remarks', NOW())";
      if ($this->query($q)) {
         $payment_id = mysqli_insert_id($this->connection);

         // Update challan status
         $newStatus = ($amount >= $challan['requested_amount']) ? 'Paid' : 'PartiallyPaid';
         $this->query("UPDATE " . TBL_CHALLANS . " SET status = '$newStatus' WHERE challan_id = $challan_id");

         // Generate receipt
         $receiptNo = 'REC-' . date('Y') . '-' . str_pad($payment_id, 4, '0', STR_PAD_LEFT);
         $this->query("INSERT INTO " . TBL_RECEIPTS . " (payment_id, receipt_no, receipt_type, issued_on, issued_by) 
                       VALUES ($payment_id, '$receiptNo', 'Official', NOW(), $userSql)");

         // Mark outstanding fee charges as paid
         if ($challan['enrollment_id']) {
            $this->query("UPDATE " . TBL_FEE_CHARGES . " SET status = 'Paid', paid_amount = net_amount, outstanding_amount = 0 
                          WHERE enrollment_id = {$challan['enrollment_id']} AND status != 'Paid'");
            $this->query("UPDATE " . TBL_FINES . " SET status = 'Paid' 
                          WHERE enrollment_id = {$challan['enrollment_id']} AND status = 'Pending'");
         }

         $this->logAudit($user, 'RECORD_PAYMENT', 'payments', 'payments', $payment_id, '', "Recorded payment Rs. $amount for Challan #$challan_id (Receipt $receiptNo)");
         return $payment_id;
      }
      return false;
   }

   function getAllPayments($status = '')
   {
      $where = "WHERE 1=1";
      if ($status) $where .= " AND p.verification_status = '" . $this->esc($status) . "'";

      $q = "SELECT p.*, ch.challan_no, st.registration_no, st.first_name, st.last_name, r.receipt_no 
            FROM " . TBL_PAYMENTS . " p 
            JOIN " . TBL_CHALLANS . " ch ON p.challan_id = ch.challan_id 
            JOIN " . TBL_STUDENTS . " st ON p.student_id = st.student_id 
            LEFT JOIN " . TBL_RECEIPTS . " r ON p.payment_id = r.payment_id 
            $where 
            ORDER BY p.payment_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getReceipt($payment_id)
   {
      $payment_id = intval($payment_id);
      $q = "SELECT r.*, p.*, ch.challan_no, ch.bank_account_title, ch.bank_account_no, 
            st.registration_no, st.first_name, st.last_name, c.class_name 
            FROM " . TBL_RECEIPTS . " r 
            JOIN " . TBL_PAYMENTS . " p ON r.payment_id = p.payment_id 
            JOIN " . TBL_CHALLANS . " ch ON p.challan_id = ch.challan_id 
            JOIN " . TBL_STUDENTS . " st ON p.student_id = st.student_id 
            LEFT JOIN " . TBL_ENROLLMENTS . " e ON ch.enrollment_id = e.enrollment_id 
            LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
            LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
            WHERE r.payment_id = $payment_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   // -------------------------------------------------------------
   // MODULE 10: FINE MANAGEMENT
   // -------------------------------------------------------------

   function getAllFines($status = '')
   {
      $where = "WHERE 1=1";
      if ($status) $where .= " AND f.status = '" . $this->esc($status) . "'";

      $q = "SELECT f.*, st.registration_no, st.first_name, st.last_name, c.class_name 
            FROM " . TBL_FINES . " f 
            JOIN " . TBL_ENROLLMENTS . " e ON f.enrollment_id = e.enrollment_id 
            JOIN " . TBL_STUDENTS . " st ON e.student_id = st.student_id 
            LEFT JOIN " . TBL_CLASS_SESSIONS . " cs ON e.class_session_id = cs.class_session_id 
            LEFT JOIN " . TBL_CLASSES . " c ON cs.class_id = c.class_id 
            $where 
            ORDER BY f.fine_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function addFine($enrollment_id, $amount, $reason, $user = '')
   {
      $enrollment_id = intval($enrollment_id);
      $amount = floatval($amount);
      $reason = $this->esc($reason);
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_FINES . " (enrollment_id, fine_date, amount, reason, status, created_by, created_at)
            VALUES ($enrollment_id, CURDATE(), $amount, '$reason', 'Pending', $userSql, NOW())";
      if ($this->query($q)) {
         $id = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'ADD_FINE', 'fines', 'fines', $id, '', "Assigned fine Rs. $amount to enrollment #$enrollment_id ($reason)");
         return $id;
      }
      return false;
   }

   function waiveFine($fine_id, $user = '')
   {
      $fine_id = intval($fine_id);
      $user = $this->esc($user);
      $q = "UPDATE " . TBL_FINES . " SET status = 'Waived' WHERE fine_id = $fine_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'WAIVE_FINE', 'fines', 'fines', $fine_id, '', "Waived fine #$fine_id");
         return true;
      }
      return false;
   }

   // -------------------------------------------------------------
   // AUDIT LOGGING & SYSTEM SETTINGS
   // -------------------------------------------------------------

   function logAudit($user_id, $action, $module, $record_type, $record_id, $old_values = null, $new_values = null)
   {
      $userSql = $this->resolveUserIdSql($user_id);
      $action = $this->esc($action);
      $module = $this->esc($module);
      $record_type = $this->esc($record_type);
      $record_id = $this->esc(strval($record_id));

      // Handle old_values JSON
      if ($old_values === null || $old_values === '') {
         $oldSql = "NULL";
      } elseif (is_array($old_values)) {
         $oldSql = "'" . $this->esc(json_encode($old_values)) . "'";
      } else {
         $decoded = json_decode($old_values);
         if ($decoded !== null || $old_values === 'null') {
            $oldSql = "'" . $this->esc($old_values) . "'";
         } else {
            $oldSql = "'" . $this->esc(json_encode(['value' => $old_values])) . "'";
         }
      }

      // Handle new_values JSON
      if ($new_values === null || $new_values === '') {
         $newSql = "NULL";
      } elseif (is_array($new_values)) {
         $newSql = "'" . $this->esc(json_encode($new_values)) . "'";
      } else {
         $decoded = json_decode($new_values);
         if ($decoded !== null || $new_values === 'null') {
            $newSql = "'" . $this->esc($new_values) . "'";
         } else {
            $newSql = "'" . $this->esc(json_encode(['value' => $new_values])) . "'";
         }
      }

      $ip = $this->esc($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
      $userAgent = $this->esc($_SERVER['HTTP_USER_AGENT'] ?? 'CLI/Browser');

      $q = "INSERT INTO " . TBL_AUDIT_LOGS . " 
            (user_id, action, module, table_name, record_id, old_values, new_values, ip_address, user_agent, created_at)
            VALUES 
            ($userSql, '$action', '$module', '$record_type', '$record_id', $oldSql, $newSql, '$ip', '$userAgent', NOW())";
      return $this->query($q);
   }

   function getAuditLogs($limit = 100, $module = '')
   {
      $limit = intval($limit);
      $where = $module ? "WHERE module = '" . $this->esc($module) . "'" : "";
      $q = "SELECT log_id, log_id AS audit_id, user_id, module, action, table_name, table_name AS record_type, record_id, old_values, new_values, ip_address, created_at, created_at AS timestamp 
            FROM " . TBL_AUDIT_LOGS . " $where ORDER BY log_id DESC LIMIT $limit";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getSystemSettings()
   {
      $q = "SELECT * FROM " . TBL_SETTINGS;
      $res = $this->query($q);
      $settings = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) {
            $settings[$r['setting_key']] = $r['setting_value'];
         }
      }
      return $settings;
   }

   function updateSystemSetting($key, $value)
   {
      $key = $this->esc($key);
      $value = $this->esc($value);
      $q = "INSERT INTO " . TBL_SETTINGS . " (setting_key, setting_value, updated_at) 
            VALUES ('$key', '$value', NOW()) 
            ON DUPLICATE KEY UPDATE setting_value = '$value', updated_at = NOW()";
      return $this->query($q);
   }

   // -------------------------------------------------------------
   // DASHBOARD KPIS & STATS
   // -------------------------------------------------------------

   function getDashboardKPIs()
   {
      $kpi = [];

      // Total Students
      $q = "SELECT COUNT(*) as cnt FROM " . TBL_STUDENTS;
      $r = $this->query($q);
      $kpi['total_students'] = ($r) ? mysqli_fetch_assoc($r)['cnt'] : 0;

      // Active Students
      $q = "SELECT COUNT(*) as cnt FROM " . TBL_STUDENTS . " WHERE status = 'Active'";
      $r = $this->query($q);
      $kpi['active_students'] = ($r) ? mysqli_fetch_assoc($r)['cnt'] : 0;

      // Total Enquiries
      $q = "SELECT COUNT(*) as cnt FROM " . TBL_ENQUIRIES;
      $r = $this->query($q);
      $kpi['total_enquiries'] = ($r) ? mysqli_fetch_assoc($r)['cnt'] : 0;

      // New / Pending Enquiries
      $q = "SELECT COUNT(*) as cnt FROM " . TBL_ENQUIRIES . " WHERE status IN ('New', 'Contacted', 'FollowUp')";
      $r = $this->query($q);
      $kpi['pending_enquiries'] = ($r) ? mysqli_fetch_assoc($r)['cnt'] : 0;

      // Total Applications
      $q = "SELECT COUNT(*) as cnt FROM " . TBL_APPLICATIONS;
      $r = $this->query($q);
      $kpi['total_applications'] = ($r) ? mysqli_fetch_assoc($r)['cnt'] : 0;

      // Pending Applications
      $q = "SELECT COUNT(*) as cnt FROM " . TBL_APPLICATIONS . " WHERE status IN ('Submitted', 'UnderReview', 'Interview')";
      $r = $this->query($q);
      $kpi['pending_admissions'] = ($r) ? mysqli_fetch_assoc($r)['cnt'] : 0;

      // Scheduled Entry Tests
      $q = "SELECT COUNT(*) as cnt FROM " . TBL_ENTRY_TESTS . " WHERE status = 'Scheduled'";
      $r = $this->query($q);
      $kpi['upcoming_tests'] = ($r) ? mysqli_fetch_assoc($r)['cnt'] : 0;

      // Total Verified Payments Collected
      $q = "SELECT SUM(amount) as total FROM " . TBL_PAYMENTS . " WHERE verification_status = 'Verified'";
      $r = $this->query($q);
      $kpi['total_collected'] = ($r) ? (mysqli_fetch_assoc($r)['total'] ?: 0) : 0;

      // Outstanding Dues
      $q = "SELECT SUM(outstanding_amount) as total FROM " . TBL_FEE_CHARGES . " WHERE status != 'Paid'";
      $r = $this->query($q);
      $kpi['total_outstanding'] = ($r) ? (mysqli_fetch_assoc($r)['total'] ?: 0) : 0;

      return $kpi;
   }

   // -------------------------------------------------------------
   // ADMISSION CYCLES & QUOTAS MANAGEMENT
   // -------------------------------------------------------------

   function getActiveAdmissionCycle()
   {
      $q = "SELECT ac.*, s.session_name 
            FROM " . TBL_ADMISSION_CYCLES . " ac 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON ac.session_id = s.session_id 
            WHERE ac.status = 'Open' 
              AND ac.is_active = 1 
              AND CURDATE() <= ac.end_date 
            ORDER BY ac.admission_cycle_id DESC 
            LIMIT 1";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function getAllAdmissionCycles()
   {
      $q = "SELECT ac.*, s.session_name,
            (SELECT COUNT(*) FROM " . TBL_APPLICATIONS . " a WHERE a.requested_session_id = ac.session_id) as total_applications
            FROM " . TBL_ADMISSION_CYCLES . " ac 
            LEFT JOIN " . TBL_ACADEMIC_SESSIONS . " s ON ac.session_id = s.session_id 
            ORDER BY ac.admission_cycle_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function addAdmissionCycle($data, $user = '')
   {
      $session_id = intval($data['session_id'] ?? 0);
      $name = $this->esc($data['cycle_name'] ?? 'Annual Admission Cycle');
      $start = $this->esc($data['start_date'] ?? date('Y-m-d'));
      $end = $this->esc($data['end_date'] ?? date('Y-m-d', strtotime('+30 days')));
      $status = in_array($data['status'] ?? '', ['Upcoming', 'Open', 'Closed', 'Cancelled']) ? $data['status'] : 'Open';
      $banner = $this->esc($data['banner_image'] ?? '');
      $desc = $this->esc($data['description'] ?? '');
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_ADMISSION_CYCLES . " 
            (session_id, cycle_name, start_date, end_date, status, banner_image, description, is_active, created_by, created_at)
            VALUES 
            ($session_id, '$name', '$start', '$end', '$status', '$banner', '$desc', 1, $userSql, NOW())";
      if ($this->query($q)) {
         $id = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'CREATE_ADMISSION_CYCLE', 'admissions', 'admission_cycles', $id, '', "Created admission cycle '$name'");
         return $id;
      }
      return false;
   }

   function updateAdmissionCycle($cycle_id, $data, $user = '')
   {
      $cycle_id = intval($cycle_id);
      $session_id = intval($data['session_id'] ?? 0);
      $name = $this->esc($data['cycle_name'] ?? '');
      $start = $this->esc($data['start_date'] ?? '');
      $end = $this->esc($data['end_date'] ?? '');
      $status = in_array($data['status'] ?? '', ['Upcoming', 'Open', 'Closed', 'Cancelled']) ? $data['status'] : 'Open';
      $desc = $this->esc($data['description'] ?? '');
      $bannerSql = !empty($data['banner_image']) ? ", banner_image = '" . $this->esc($data['banner_image']) . "'" : "";

      $q = "UPDATE " . TBL_ADMISSION_CYCLES . " 
            SET session_id = $session_id, cycle_name = '$name', start_date = '$start', end_date = '$end', 
                status = '$status', description = '$desc' $bannerSql 
            WHERE admission_cycle_id = $cycle_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'UPDATE_ADMISSION_CYCLE', 'admissions', 'admission_cycles', $cycle_id, '', "Updated cycle #$cycle_id ($name)");
         return true;
      }
      return false;
   }

   // -------------------------------------------------------------
   // CAREERS & JOB VACANCIES MODULE
   // -------------------------------------------------------------

   function getOpenJobs()
   {
      $q = "SELECT * FROM " . TBL_JOB_OPENINGS . " 
            WHERE status = 'Open' AND CURDATE() <= last_date_to_apply 
            ORDER BY job_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getAllJobs()
   {
      $q = "SELECT j.*, 
            (SELECT COUNT(*) FROM " . TBL_JOB_APPLICATIONS . " ja WHERE ja.job_id = j.job_id) as applicant_count 
            FROM " . TBL_JOB_OPENINGS . " j 
            ORDER BY j.job_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function getJob($job_id)
   {
      $job_id = intval($job_id);
      $q = "SELECT * FROM " . TBL_JOB_OPENINGS . " WHERE job_id = $job_id";
      $res = $this->query($q);
      return ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
   }

   function addJobOpening($data, $user = '')
   {
      $title = $this->esc($data['job_title'] ?? '');
      $dept = $this->esc($data['department'] ?? 'Academic Faculty');
      $type = $this->esc($data['employment_type'] ?? 'Full-time');
      $exp = $this->esc($data['experience_required'] ?? '');
      $qual = $this->esc($data['qualification'] ?? '');
      $vac = intval($data['vacancies'] ?? 1);
      $last_date = $this->esc($data['last_date_to_apply'] ?? date('Y-m-d', strtotime('+30 days')));
      $desc = $this->esc($data['description'] ?? '');
      $req = $this->esc($data['requirements'] ?? '');
      $status = in_array($data['status'] ?? '', ['Open', 'Closed']) ? $data['status'] : 'Open';
      $userSql = $this->resolveUserIdSql($user);

      $q = "INSERT INTO " . TBL_JOB_OPENINGS . " 
            (job_title, department, employment_type, experience_required, qualification, vacancies, last_date_to_apply, description, requirements, status, created_by, created_at)
            VALUES 
            ('$title', '$dept', '$type', '$exp', '$qual', $vac, '$last_date', '$desc', '$req', '$status', $userSql, NOW())";
      if ($this->query($q)) {
         $id = mysqli_insert_id($this->connection);
         $this->logAudit($user, 'CREATE_JOB', 'careers', 'job_openings', $id, '', "Posted job vacancy '$title'");
         return $id;
      }
      return false;
   }

   function updateJobOpening($job_id, $data, $user = '')
   {
      $job_id = intval($job_id);
      $title = $this->esc($data['job_title'] ?? '');
      $dept = $this->esc($data['department'] ?? '');
      $type = $this->esc($data['employment_type'] ?? 'Full-time');
      $exp = $this->esc($data['experience_required'] ?? '');
      $qual = $this->esc($data['qualification'] ?? '');
      $vac = intval($data['vacancies'] ?? 1);
      $last_date = $this->esc($data['last_date_to_apply'] ?? date('Y-m-d', strtotime('+30 days')));
      $desc = $this->esc($data['description'] ?? '');
      $req = $this->esc($data['requirements'] ?? '');
      $status = in_array($data['status'] ?? '', ['Open', 'Closed']) ? $data['status'] : 'Open';

      $q = "UPDATE " . TBL_JOB_OPENINGS . " 
            SET job_title = '$title', department = '$dept', employment_type = '$type', 
                experience_required = '$exp', qualification = '$qual', vacancies = $vac, 
                last_date_to_apply = '$last_date', description = '$desc', requirements = '$req', status = '$status'
            WHERE job_id = $job_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'UPDATE_JOB', 'careers', 'job_openings', $job_id, '', "Updated job vacancy #$job_id ($title)");
         return true;
      }
      return false;
   }

   function deleteJobOpening($job_id, $user = '')
   {
      $job_id = intval($job_id);
      $q = "DELETE FROM " . TBL_JOB_OPENINGS . " WHERE job_id = $job_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'DELETE_JOB', 'careers', 'job_openings', $job_id, '', "Deleted job opening #$job_id");
         return true;
      }
      return false;
   }

   function addJobApplication($job_id, $data, $resume_path = '')
   {
      $job_id = intval($job_id);
      $name = $this->esc($data['applicant_name'] ?? '');
      $email = $this->esc($data['email'] ?? '');
      $phone = $this->esc($data['phone'] ?? '');
      $qual = $this->esc($data['qualification'] ?? '');
      $exp = $this->esc($data['experience'] ?? '');
      $cover = $this->esc($data['cover_letter'] ?? '');
      $resume = $this->esc($resume_path);

      $q = "INSERT INTO " . TBL_JOB_APPLICATIONS . " 
            (job_id, applicant_name, email, phone, qualification, experience, resume_path, cover_letter, status, applied_at)
            VALUES 
            ($job_id, '$name', '$email', '$phone', '$qual', '$exp', '$resume', '$cover', 'Received', NOW())";
      return $this->query($q);
   }

   function getJobApplications($job_id = 0)
   {
      $where = $job_id ? "WHERE ja.job_id = " . intval($job_id) : "";
      $q = "SELECT ja.*, j.job_title, j.department 
            FROM " . TBL_JOB_APPLICATIONS . " ja 
            JOIN " . TBL_JOB_OPENINGS . " j ON ja.job_id = j.job_id 
            $where 
            ORDER BY ja.application_id DESC";
      $res = $this->query($q);
      $rows = [];
      if ($res) {
         while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      }
      return $rows;
   }

   function updateJobApplicationStatus($application_id, $status, $user = '')
   {
      $application_id = intval($application_id);
      $status = in_array($status, ['Received', 'Shortlisted', 'Interviewed', 'Rejected', 'Selected']) ? $status : 'Received';
      $q = "UPDATE " . TBL_JOB_APPLICATIONS . " SET status = '$status' WHERE application_id = $application_id";
      if ($this->query($q)) {
         $this->logAudit($user, 'UPDATE_JOB_APP', 'careers', 'job_applications', $application_id, '', "Updated application status to $status");
         return true;
      }
      return false;
   }

   // -------------------------------------------------------------
   // VISITOR & ACTIVE USER TRACKING
   // -------------------------------------------------------------

   function addActiveUser($username, $time)
   {
      $username = $this->esc($username);
      $time = intval($time);
      $q = "REPLACE INTO " . TBL_ACTIVE_USERS . " VALUES ('$username', $time)";
      $this->query($q);
      $this->calcNumActiveUsers();
   }

   function addActiveGuest($ip, $time)
   {
      $ip = $this->esc($ip);
      $time = intval($time);
      $q = "REPLACE INTO " . TBL_ACTIVE_GUESTS . " VALUES ('$ip', $time)";
      $this->query($q);
      $this->calcNumActiveGuests();
   }

   function removeActiveUser($username)
   {
      $username = $this->esc($username);
      $q = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE username = '$username'";
      $this->query($q);
      $this->calcNumActiveUsers();
   }

   function removeActiveGuest($ip)
   {
      $ip = $this->esc($ip);
      $q = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE ip = '$ip'";
      $this->query($q);
      $this->calcNumActiveGuests();
   }

   function removeInactiveUsers()
   {
      $timeout = time() - (USER_TIMEOUT * 60);
      $q = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE timestamp < $timeout";
      $this->query($q);
      $this->calcNumActiveUsers();
   }

   function removeInactiveGuests()
   {
      $timeout = time() - (GUEST_TIMEOUT * 60);
      $q = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE timestamp < $timeout";
      $this->query($q);
      $this->calcNumActiveGuests();
   }

   function calcNumActiveUsers()
   {
      $q = "SELECT COUNT(*) FROM " . TBL_ACTIVE_USERS;
      $result = $this->query($q);
      $this->num_active_users = ($result) ? mysqli_fetch_row($result)[0] : 0;
   }

   function calcNumActiveGuests()
   {
      $q = "SELECT COUNT(*) FROM " . TBL_ACTIVE_GUESTS;
      $result = $this->query($q);
      $this->num_active_guests = ($result) ? mysqli_fetch_row($result)[0] : 0;
   }
}

$database = new MySQLDB;
?>