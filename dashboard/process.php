<?php
/**
 * SOS Hermann Gmeiner School & College · Admin Portal Action Processor
 * Master Controller for all 10 Modules, Dynamic Roles, and Audit Trail
 */

include_once dirname(__DIR__) . '/include/classes/session.php';

class DashboardProcess
{
   function __construct()
   {
      global $session;

      // 1. Auth Actions
      if (isset($_POST['sublogin'])) {
         $this->procLogin();
      } else if (isset($_GET['action']) && $_GET['action'] === 'logout') {
         $this->procLogout();
      }

      // 2. Academic Setup Actions
      else if (isset($_POST['addsession'])) {
         $this->procAddSession();
      } else if (isset($_POST['editsession'])) {
         $this->procEditSession();
      } else if (isset($_POST['setactivesession'])) {
         $this->procSetActiveSession();
      } else if (isset($_POST['addclass'])) {
         $this->procAddClass();
      } else if (isset($_POST['editclass'])) {
         $this->procEditClass();
      } else if (isset($_POST['addsection'])) {
         $this->procAddSection();
      } else if (isset($_POST['editsection'])) {
         $this->procEditSection();
      }

      // 3. Enquiry Actions
      else if (isset($_POST['addenquiry'])) {
         $this->procAddEnquiry();
      } else if (isset($_POST['updateenquiry'])) {
         $this->procUpdateEnquiry();
      } else if (isset($_POST['addfollowup'])) {
         $this->procAddFollowup();
      } else if (isset($_POST['convertenquiry'])) {
         $this->procConvertEnquiry();
      }

      // 4. Admission Application Actions
      else if (isset($_POST['addapplication'])) {
         $this->procAddApplication();
      } else if (isset($_POST['updateappstatus'])) {
         $this->procUpdateAppStatus();
      } else if (isset($_POST['verifydoc'])) {
         $this->procVerifyDoc();
      }

      // 5. Entry Test Actions
      else if (isset($_POST['scheduletest'])) {
         $this->procScheduleTest();
      } else if (isset($_POST['assigntestcandidate'])) {
         $this->procAssignTestCandidate();
      } else if (isset($_POST['recordtestresult'])) {
         $this->procRecordTestResult();
      } else if (isset($_POST['converttostudent'])) {
         $this->procConvertToStudent();
      } else if (isset($_POST['bulkconverttostudent'])) {
         $this->procBulkConvertToStudent();
      } else if (isset($_POST['addadmissioncycle'])) {
         $this->procAddAdmissionCycle();
      } else if (isset($_POST['updateadmissioncycle'])) {
         $this->procUpdateAdmissionCycle();
      } else if (isset($_POST['addjob'])) {
         $this->procAddJob();
      } else if (isset($_POST['updatejob'])) {
         $this->procUpdateJob();
      } else if (isset($_POST['deletejob'])) {
         $this->procDeleteJob();
      } else if (isset($_POST['updatejobappstatus'])) {
         $this->procUpdateJobAppStatus();
      }

      // 6. Student Actions
      else if (isset($_POST['createstudent'])) {
         $this->procCreateStudent();
      } else if (isset($_POST['editstudent'])) {
         $this->procEditStudent();
      } else if (isset($_POST['uploadstudentdoc'])) {
         $this->procUploadStudentDocument();
      }

      // 7. Enrollment & Promotion Actions
      else if (isset($_POST['enrollstudent'])) {
         $this->procEnrollStudent();
      } else if (isset($_POST['promotestudent'])) {
         $this->procPromoteStudent();
      }

      // 8. Fee Management Actions
      else if (isset($_POST['addfeetype'])) {
         $this->procAddFeeType();
      } else if (isset($_POST['addfeestructure'])) {
         $this->procAddFeeStructure();
      } else if (isset($_POST['generatefees'])) {
         $this->procGenerateFees();
      }

      // 9. Concessions & Fines
      else if (isset($_POST['addconcession'])) {
         $this->procAddConcession();
      } else if (isset($_POST['addfine'])) {
         $this->procAddFine();
      } else if (isset($_POST['waivefine'])) {
         $this->procWaiveFine();
      }

      // 10. Challans & Payments
      else if (isset($_POST['generatechallan'])) {
         $this->procGenerateChallan();
      } else if (isset($_POST['recordpayment'])) {
         $this->procRecordPayment();
      }

      // 11. RBAC Roles & Permissions
      else if (isset($_POST['addrole'])) {
         $this->procAddRole();
      } else if (isset($_POST['updaterole'])) {
         $this->procUpdateRole();
      } else if (isset($_POST['saverolepermissions'])) {
         $this->procSaveRolePermissions();
      }

      // 12. Users & Settings
      else if (isset($_POST['adduser'])) {
         $this->procAddUser();
      } else if (isset($_POST['updateuserstatus'])) {
         $this->procUpdateUserStatus();
      } else if (isset($_POST['updatesettings'])) {
         $this->procUpdateSettings();
      } else if (isset($_POST['changepassword'])) {
         $this->procChangePassword();
      } else if (isset($_POST['change_accountdetails'])) {
         $this->procChangeAccountDetails();
      } else if (isset($_POST['uploadimage'])) {
         $this->procUploadImage();
      }

      // Default
      else if ($session->logged_in) {
         $this->procLogout();
      } else {
         header("Location: index.php");
         exit;
      }
   }

   function requireLogin()
   {
      global $session;
      if (!$session->logged_in) {
         header("Location: ../login.php");
         exit;
      }
   }

   function requirePerm($module, $action = 'view', $redirect = 'index.php')
   {
      global $session;
      $this->requireLogin();
      if (!$session->hasPermission($module, $action)) {
         header("Location: $redirect?error=unauthorized");
         exit;
      }
   }

   // -------------------------------------------------------------
   // AUTH
   // -------------------------------------------------------------

   function procLogin()
   {
      global $session, $form;
      $username = $_POST['username'] ?? ($_POST['user'] ?? '');
      $password = $_POST['pass'] ?? '';
      $remember = isset($_POST['rememberMe']);

      $retval = $session->login($username, $password, $remember);
      if ($retval) {
         header("Location: index.php");
         exit;
      } else {
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ../login.php?error=invalid");
         exit;
      }
   }

   function procLogout()
   {
      global $session;
      $session->logout();
      header("Location: ../login.php?logout=true");
      exit;
   }

   // -------------------------------------------------------------
   // MODULE 01: ACADEMIC SETUP
   // -------------------------------------------------------------

   function procAddSession()
   {
      global $database, $session;
      $this->requirePerm('academic_setup', 'create', 'sessions.php');
      $is_active = isset($_POST['is_active']) ? 1 : 0;
      $res = $database->addNewSession(
         $_POST['session_name'] ?? '',
         $_POST['start_date'] ?? '',
         $_POST['end_date'] ?? '',
         $_POST['status'] ?? 'Planned',
         $is_active,
         $session->username
      );
      header("Location: sessions.php?" . ($res === 0 ? "added=true" : "added=error"));
      exit;
   }

   function procEditSession()
   {
      global $database, $session;
      $this->requirePerm('academic_setup', 'edit', 'sessions.php');
      $is_active = isset($_POST['is_active']) ? 1 : 0;
      $res = $database->updateSession(
         intval($_POST['session_id']),
         $_POST['session_name'] ?? '',
         $_POST['start_date'] ?? '',
         $_POST['end_date'] ?? '',
         $_POST['status'] ?? 'Planned',
         $is_active,
         $session->username
      );
      header("Location: sessions.php?" . ($res === 0 ? "updated=true" : "updated=error"));
      exit;
   }

   function procSetActiveSession()
   {
      global $database, $session;
      $this->requirePerm('academic_setup', 'edit', 'sessions.php');
      $res = $database->setActiveSession(intval($_POST['session_id']), $session->username);
      header("Location: sessions.php?" . ($res === 0 ? "active=true" : "active=error"));
      exit;
   }

   function procAddClass()
   {
      global $database, $session;
      $this->requirePerm('academic_setup', 'create', 'classes.php');
      $is_active = isset($_POST['is_active']) ? 1 : 0;
      $res = $database->addNewClass(
         $_POST['class_name'] ?? '',
         intval($_POST['class_order'] ?? 0),
         $_POST['description'] ?? '',
         $is_active,
         $session->username
      );
      header("Location: classes.php?" . ($res === 0 ? "added=true" : "added=error"));
      exit;
   }

   function procEditClass()
   {
      global $database, $session;
      $this->requirePerm('academic_setup', 'edit', 'classes.php');
      $is_active = isset($_POST['is_active']) ? 1 : 0;
      $res = $database->updateClass(
         intval($_POST['class_id']),
         $_POST['class_name'] ?? '',
         intval($_POST['class_order'] ?? 0),
         $_POST['description'] ?? '',
         $is_active,
         $session->username
      );
      header("Location: classes.php?" . ($res === 0 ? "updated=true" : "updated=error"));
      exit;
   }

   function procAddSection()
   {
      global $database, $session;
      $this->requirePerm('academic_setup', 'create', 'sections.php');
      $is_active = isset($_POST['is_active']) ? 1 : 0;
      $res = $database->addNewSection(
         intval($_POST['session_id'] ?? 0),
         intval($_POST['class_id'] ?? 0),
         $_POST['section_name'] ?? 'A',
         intval($_POST['capacity'] ?? 35),
         $is_active,
         $session->username
      );
      header("Location: sections.php?" . ($res === 0 ? "added=true" : "added=error"));
      exit;
   }

   function procEditSection()
   {
      global $database, $session;
      $this->requirePerm('academic_setup', 'edit', 'sections.php');
      $is_active = isset($_POST['is_active']) ? 1 : 0;
      $res = $database->updateSection(
         intval($_POST['section_id']),
         intval($_POST['class_id'] ?? 0),
         $_POST['section_name'] ?? 'A',
         intval($_POST['capacity'] ?? 35),
         $is_active,
         $session->username
      );
      header("Location: sections.php?" . ($res === 0 ? "updated=true" : "updated=error"));
      exit;
   }

   // -------------------------------------------------------------
   // MODULE 02: INQUIRY MANAGEMENT
   // -------------------------------------------------------------

   function procAddEnquiry()
   {
      global $database, $session;
      $this->requirePerm('enquiries', 'create', 'enquiries.php');
      $data = [
         'source' => $_POST['source'] ?? 'WalkIn',
         'enquiry_type' => $_POST['enquiry_type'] ?? 'Admission',
         'parent_name' => $_POST['parent_name'] ?? '',
         'student_name' => $_POST['student_name'] ?? '',
         'phone' => $_POST['phone'] ?? '',
         'email' => $_POST['email'] ?? '',
         'address' => $_POST['address'] ?? '',
         'message' => $_POST['message'] ?? '',
         'requested_session_id' => intval($_POST['requested_session_id'] ?? 0),
         'requested_class_id' => intval($_POST['requested_class_id'] ?? 0),
         'status' => $_POST['status'] ?? 'New',
         'assigned_to' => $_POST['assigned_to'] ?? $session->username,
         'follow_up_date' => $_POST['follow_up_date'] ?? null
      ];
      $id = $database->addNewEnquiry($data, $session->username);
      header("Location: enquiries.php?" . ($id ? "added=true" : "added=error"));
      exit;
   }

   function procUpdateEnquiry()
   {
      global $database, $session;
      $this->requirePerm('enquiries', 'edit', 'enquiries.php');
      $id = intval($_POST['enquiry_id']);
      $data = [
         'parent_name' => $_POST['parent_name'] ?? '',
         'student_name' => $_POST['student_name'] ?? '',
         'phone' => $_POST['phone'] ?? '',
         'email' => $_POST['email'] ?? '',
         'address' => $_POST['address'] ?? '',
         'message' => $_POST['message'] ?? '',
         'status' => $_POST['status'] ?? 'Contacted',
         'assigned_to' => $_POST['assigned_to'] ?? '',
         'follow_up_date' => $_POST['follow_up_date'] ?? null
      ];
      $ok = $database->updateEnquiry($id, $data, $session->username);
      header("Location: enquiries.php?" . ($ok ? "updated=true" : "updated=error"));
      exit;
   }

   function procAddFollowup()
   {
      global $database, $session;
      $this->requirePerm('enquiries', 'edit', 'enquiries.php');
      $id = intval($_POST['enquiry_id']);
      $ok = $database->addEnquiryFollowup(
         $id,
         $_POST['method'] ?? 'Call',
         $_POST['notes'] ?? '',
         $_POST['next_followup_date'] ?? null,
         $session->username
      );
      header("Location: enquiries.php?enquiry_id=$id&" . ($ok ? "followup=true" : "followup=error"));
      exit;
   }

   function procConvertEnquiry()
   {
      global $database, $session;
      $this->requirePerm('admissions', 'create', 'enquiries.php');
      $id = intval($_POST['enquiry_id']);
      $app_id = $database->convertEnquiryToApplication($id, $session->username);
      if ($app_id) {
         header("Location: admissions.php?app_id=$app_id&converted=true");
      } else {
         header("Location: enquiries.php?converted=error");
      }
      exit;
   }

   // -------------------------------------------------------------
   // MODULE 03: ADMISSION APPLICATIONS & DOCUMENTS
   // -------------------------------------------------------------

   function procAddApplication()
   {
      global $database, $session;
      $this->requirePerm('admissions', 'create', 'admissions.php');
      $data = [
         'source' => $_POST['source'] ?? 'HR',
         'student_name' => $_POST['student_name'] ?? '',
         'date_of_birth' => $_POST['date_of_birth'] ?? null,
         'gender' => $_POST['gender'] ?? 'Male',
         'blood_group' => $_POST['blood_group'] ?? '',
         'religion' => $_POST['religion'] ?? 'Islam',
         'nationality' => $_POST['nationality'] ?? 'Pakistani',
         'address' => $_POST['address'] ?? '',
         'phone' => $_POST['phone'] ?? '',
         'email' => $_POST['email'] ?? '',
         'parent_name' => $_POST['parent_name'] ?? '',
         'parent_phone' => $_POST['parent_phone'] ?? '',
         'parent_email' => $_POST['parent_email'] ?? '',
         'requested_session_id' => intval($_POST['requested_session_id'] ?? 0),
         'applied_class_id' => intval($_POST['applied_class_id'] ?? 0),
         'status' => $_POST['status'] ?? 'Submitted',
         'remarks' => $_POST['remarks'] ?? ''
      ];
      $id = $database->addNewApplication($data, $session->username);
      header("Location: admissions.php?" . ($id ? "added=true" : "added=error"));
      exit;
   }

   function procUpdateAppStatus()
   {
      global $database, $session;
      $this->requirePerm('admissions', 'approve', 'admissions.php');
      $id = intval($_POST['application_id']);
      $status = $_POST['status'] ?? 'UnderReview';
      $remarks = $_POST['remarks'] ?? '';
      $ok = $database->updateApplicationStatus($id, $status, $remarks, $session->username);
      header("Location: admissions.php?" . ($ok ? "status_updated=true" : "status_updated=error"));
      exit;
   }

   function procVerifyDoc()
   {
      global $database, $session;
      $this->requirePerm('admissions', 'approve', 'verifications.php');
      $doc_id = intval($_POST['admission_doc_id']);
      $is_verified = intval($_POST['is_verified'] ?? 1);
      $database->verifyApplicationDocument($doc_id, $is_verified, $session->username);
      header("Location: verifications.php?verified=true");
      exit;
   }

   // -------------------------------------------------------------
   // MODULE 04: ENTRY TESTS & RESULT RECORDING
   // -------------------------------------------------------------

   function procScheduleTest()
   {
      global $database, $session;
      $this->requirePerm('entry_tests', 'create', 'entry-tests.php');
      $data = [
         'test_name' => $_POST['test_name'] ?? 'Entry Test',
         'session_id' => intval($_POST['session_id'] ?? 0),
         'class_id' => intval($_POST['class_id'] ?? 0),
         'subject' => $_POST['subject'] ?? 'General Assessment',
         'test_date' => $_POST['test_date'] ?? date('Y-m-d'),
         'start_time' => $_POST['start_time'] ?? '09:00:00',
         'end_time' => $_POST['end_time'] ?? '11:00:00',
         'venue' => $_POST['venue'] ?? 'Main Examination Hall',
         'total_marks' => floatval($_POST['total_marks'] ?? 100),
         'passing_marks' => floatval($_POST['passing_marks'] ?? 50),
         'instructions' => $_POST['instructions'] ?? ''
      ];
      $id = $database->scheduleEntryTest($data, $session->username);
      header("Location: entry-tests.php?" . ($id ? "scheduled=true" : "scheduled=error"));
      exit;
   }

   function procAssignTestCandidate()
   {
      global $database, $session;
      $this->requirePerm('entry_tests', 'create', 'entry-tests.php');
      $test_id = intval($_POST['test_id']);
      $application_id = intval($_POST['application_id']);
      $roll_no = $_POST['roll_number'] ?? '';
      $database->assignCandidateToTest($test_id, $application_id, $roll_no);
      header("Location: entry-tests.php?test_id=$test_id&candidate_assigned=true");
      exit;
   }

   function procRecordTestResult()
   {
      global $database, $session;
      $this->requirePerm('entry_tests', 'edit', 'entry-tests.php');
      $candidate_id = intval($_POST['candidate_id']);
      $marks = floatval($_POST['marks_obtained']);
      $remarks = $_POST['remarks'] ?? '';
      $test_id = intval($_POST['test_id'] ?? 0);

      $ok = $database->recordTestResult($candidate_id, $marks, $remarks, $session->username);
      header("Location: entry-tests.php?" . ($test_id ? "test_id=$test_id&" : "") . ($ok ? "result_saved=true" : "result_saved=error"));
      exit;
   }

   function procConvertToStudent()
   {
      global $database, $session;
      $this->requirePerm('students', 'create', 'students.php');
      $app_id = intval($_POST['application_id']);
      $student_id = $database->createStudentFromApplication($app_id, $session->username);
      $returnTo = $_POST['return_to'] ?? '';
      if ($student_id) {
         if ($returnTo === 'admissions.php') {
            header("Location: admissions.php?converted=true&student_id=$student_id");
         } else {
            header("Location: students.php?student_id=$student_id&created=true");
         }
      } else {
         header("Location: admissions.php?error=conversion_failed");
      }
      exit;
   }

   function procBulkConvertToStudent()
   {
      global $database, $session;
      $this->requirePerm('students', 'create', 'admissions.php');
      $app_ids = $_POST['selected_apps'] ?? [];
      if (!is_array($app_ids) || empty($app_ids)) {
         header("Location: admissions.php?error=no_selection");
         exit;
      }
      $converted = 0;
      foreach ($app_ids as $aid) {
         $aid = intval($aid);
         if ($aid > 0) {
            $sid = $database->createStudentFromApplication($aid, $session->username);
            if ($sid) $converted++;
         }
      }
      header("Location: admissions.php?bulk_converted=" . $converted);
      exit;
   }

   function procAddAdmissionCycle()
   {
      global $database, $session;
      $this->requirePerm('admissions', 'create', 'admission-cycles.php');
      $id = $database->addAdmissionCycle($_POST, $session->username);
      header("Location: admission-cycles.php?" . ($id ? "added=true" : "added=error"));
      exit;
   }

   function procUpdateAdmissionCycle()
   {
      global $database, $session;
      $this->requirePerm('admissions', 'edit', 'admission-cycles.php');
      $id = intval($_POST['admission_cycle_id'] ?? 0);
      $ok = $database->updateAdmissionCycle($id, $_POST, $session->username);
      header("Location: admission-cycles.php?" . ($ok ? "updated=true" : "updated=error"));
      exit;
   }

   function procAddJob()
   {
      global $database, $session;
      $this->requirePerm('settings', 'create', 'careers.php');
      $id = $database->addJobOpening($_POST, $session->username);
      header("Location: careers.php?" . ($id ? "added=true" : "added=error"));
      exit;
   }

   function procUpdateJob()
   {
      global $database, $session;
      $this->requirePerm('settings', 'edit', 'careers.php');
      $id = intval($_POST['job_id'] ?? 0);
      $ok = $database->updateJobOpening($id, $_POST, $session->username);
      header("Location: careers.php?" . ($ok ? "updated=true" : "updated=error"));
      exit;
   }

   function procDeleteJob()
   {
      global $database, $session;
      $this->requirePerm('settings', 'delete', 'careers.php');
      $id = intval($_POST['job_id'] ?? 0);
      $ok = $database->deleteJobOpening($id, $session->username);
      header("Location: careers.php?" . ($ok ? "deleted=true" : "deleted=error"));
      exit;
   }

   function procUpdateJobAppStatus()
   {
      global $database, $session;
      $this->requirePerm('settings', 'edit', 'careers.php');
      $app_id = intval($_POST['application_id'] ?? 0);
      $status = $_POST['status'] ?? 'Received';
      $ok = $database->updateJobApplicationStatus($app_id, $status, $session->username);
      header("Location: careers.php?" . ($ok ? "app_status_updated=true" : "app_status_updated=error"));
      exit;
   }

   // -------------------------------------------------------------
   // MODULE 05 & 06: STUDENTS & ENROLLMENT
   // -------------------------------------------------------------

   function procCreateStudent()
   {
      global $database, $session;
      $this->requirePerm('students', 'create', 'students.php');
      $app_id = intval($_POST['application_id'] ?? 0);
      $student_id = $database->createStudentFromApplication($app_id, $session->username);
      header("Location: students.php?" . ($student_id ? "added=true" : "added=error"));
      exit;
   }

   function procEditStudent()
   {
      global $database, $session;
      $this->requirePerm('students', 'edit', 'students.php');
      $student_id = intval($_POST['student_id']);
      $fname = $database->esc($_POST['first_name'] ?? '');
      $lname = $database->esc($_POST['last_name'] ?? '');
      $phone = $database->esc($_POST['phone'] ?? '');
      $address = $database->esc($_POST['address'] ?? '');
      $status = $database->esc($_POST['status'] ?? 'Active');

      $q = "UPDATE " . TBL_STUDENTS . " 
            SET first_name = '$fname', last_name = '$lname', phone = '$phone', 
                address = '$address', status = '$status', updated_at = NOW() 
            WHERE student_id = $student_id";
      $database->query($q);
      header("Location: students.php?student_id=$student_id&updated=true");
      exit;
   }

   function procEnrollStudent()
   {
      global $database, $session;
      $this->requirePerm('enrollment', 'create', 'enrollment.php');
      $student_id = intval($_POST['student_id']);
      $session_id = intval($_POST['session_id']);
      $class_id = intval($_POST['class_id']);
      $section_id = intval($_POST['section_id'] ?? 0);
      $roll_no = $_POST['roll_number'] ?? '';

      $enr_id = $database->enrollStudent($student_id, $session_id, $class_id, $section_id, $roll_no, $session->username);
      header("Location: enrollment.php?" . ($enr_id ? "enrolled=true" : "enrolled=error"));
      exit;
   }

   function procPromoteStudent()
   {
      global $database, $session;
      $this->requirePerm('promotion', 'create', 'promotions.php');
      $student_id = intval($_POST['student_id']);
      $session_id = intval($_POST['target_session_id']);
      $class_id = intval($_POST['target_class_id']);
      $section_id = intval($_POST['target_section_id'] ?? 0);

      $new_enr = $database->promoteStudent($student_id, $session_id, $class_id, $section_id, '', $session->username);
      header("Location: promotions.php?" . ($new_enr ? "promoted=true" : "promoted=error"));
      exit;
   }

   // -------------------------------------------------------------
   // MODULE 07: FEE MANAGEMENT
   // -------------------------------------------------------------

   function procAddFeeType()
   {
      global $database, $session;
      $this->requirePerm('fee_structure', 'create', 'fee-types.php');
      $name = $_POST['fee_type_name'] ?? '';
      $desc = $_POST['description'] ?? '';
      $rec = isset($_POST['is_recurring']) ? 1 : 0;
      $database->addFeeType($name, $desc, $rec);
      header("Location: fee-types.php?added=true");
      exit;
   }

   function procAddFeeStructure()
   {
      global $database, $session;
      $this->requirePerm('fee_structure', 'create', 'fee-structures.php');
      $session_id = intval($_POST['session_id']);
      $class_id = intval($_POST['class_id']);
      $name = $_POST['structure_name'] ?? 'Class Fee Structure';
      $from = $_POST['effective_from'] ?? date('Y-m-01');

      $items = [];
      if (!empty($_POST['fee_type_id'])) {
         foreach ($_POST['fee_type_id'] as $idx => $ftId) {
            $amt = floatval($_POST['amount'][$idx] ?? 0);
            if ($ftId && $amt > 0) {
               $items[] = ['fee_type_id' => $ftId, 'amount' => $amt];
            }
         }
      }

      $fsId = $database->addFeeStructure($session_id, $class_id, $name, $from, $items, $session->username);
      header("Location: fee-structures.php?" . ($fsId ? "added=true" : "added=error"));
      exit;
   }

   function procGenerateFees()
   {
      global $database, $session;
      $this->requirePerm('fee_charges', 'create', 'fees.php');
      $session_id = intval($_POST['session_id']);
      $class_id = intval($_POST['class_id']);
      $month = $_POST['billing_month'] ?? date('Y-m');

      $res = $database->generateMonthlyFees($session_id, $class_id, $month, $session->username);
      if ($res['success']) {
         header("Location: fees.php?generated=true&count=" . intval($res['generated']));
      } else {
         header("Location: fees.php?generated=error&msg=" . urlencode($res['message']));
      }
      exit;
   }

   // -------------------------------------------------------------
   // MODULE 08 & 10: CONCESSIONS & FINES
   // -------------------------------------------------------------

   function procAddConcession()
   {
      global $database, $session;
      $this->requirePerm('concessions', 'create', 'concessions.php');
      $enrollment_id = intval($_POST['enrollment_id']);
      $type_id = intval($_POST['concession_type_id']);
      $value = floatval($_POST['value']);
      $from = $_POST['effective_from'] ?? date('Y-m-01');
      $to = $_POST['effective_to'] ?? null;
      $reason = $_POST['reason'] ?? '';

      $id = $database->addStudentConcession($enrollment_id, $type_id, $value, $from, $to, $reason, $session->username);
      header("Location: concessions.php?" . ($id ? "added=true" : "added=error"));
      exit;
   }

   function procAddFine()
   {
      global $database, $session;
      $this->requirePerm('fines', 'create', 'fines.php');
      $enrollment_id = intval($_POST['enrollment_id']);
      $amount = floatval($_POST['amount']);
      $reason = $_POST['reason'] ?? 'Late Payment Fine';

      $id = $database->addFine($enrollment_id, $amount, $reason, $session->username);
      header("Location: fines.php?" . ($id ? "added=true" : "added=error"));
      exit;
   }

   function procWaiveFine()
   {
      global $database, $session;
      $this->requirePerm('fines', 'edit', 'fines.php');
      $fine_id = intval($_POST['fine_id']);
      $database->waiveFine($fine_id, $session->username);
      header("Location: fines.php?waived=true");
      exit;
   }

   // -------------------------------------------------------------
   // MODULE 09: CHALLANS & PAYMENTS
   // -------------------------------------------------------------

   function procGenerateChallan()
   {
      global $database, $session;
      $this->requirePerm('challans', 'create', 'challans.php');
      $student_id = intval($_POST['student_id']);
      $enrollment_id = intval($_POST['enrollment_id'] ?? 0);
      $due_date = $_POST['due_date'] ?? date('Y-m-d', strtotime('+15 days'));
      $returnTo = $_POST['return_to'] ?? '';

      $chId = $database->generateStudentChallan($student_id, $enrollment_id, $due_date, $session->username);
      if ($returnTo === 'students.php') {
         header("Location: students.php?student_id=$student_id" . ($chId ? "&challan_id=$chId&generated=true" : "&generated=error"));
      } else {
         header("Location: challans.php?" . ($chId ? "challan_id=$chId&generated=true" : "generated=error"));
      }
      exit;
   }

   function procUploadStudentDocument()
   {
      global $database, $session;
      $this->requirePerm('students', 'edit', 'students.php');
      $student_id = intval($_POST['student_id'] ?? 0);
      $doc_type = trim($_POST['document_type'] ?? 'General Document');
      $return_to = $_POST['return_to'] ?? ("students.php?student_id=" . $student_id);

      if ($student_id <= 0 || empty($_FILES['document_file']['name'])) {
         header("Location: $return_to&error=missing_file");
         exit;
      }

      $uploadDir = __DIR__ . '/../uploads/documents/';
      if (!is_dir($uploadDir)) {
         mkdir($uploadDir, 0777, true);
      }
      $ext = pathinfo($_FILES['document_file']['name'], PATHINFO_EXTENSION);
      $filename = 'std_' . $student_id . '_' . time() . '_' . rand(100, 999) . '.' . $ext;
      $target = $uploadDir . $filename;

      if (move_uploaded_file($_FILES['document_file']['tmp_name'], $target)) {
         $filePath = 'uploads/documents/' . $filename;
         $database->addStudentDocument($student_id, $doc_type, $filePath, $session->username);
         header("Location: $return_to&doc_uploaded=true");
      } else {
         header("Location: $return_to&error=upload_failed");
      }
      exit;
   }

   function procRecordPayment()
   {
      global $database, $session;
      $this->requirePerm('payments', 'create', 'payments.php');
      $challan_id = intval($_POST['challan_id']);
      $amount = floatval($_POST['amount']);
      $payment_date = $_POST['payment_date'] ?? date('Y-m-d');
      $method = $_POST['payment_method'] ?? 'Cash';
      $ref_no = $_POST['bank_reference_no'] ?? '';
      $remarks = $_POST['remarks'] ?? '';

      $pId = $database->recordPayment($challan_id, $amount, $payment_date, $method, $ref_no, $session->username, $remarks);
      header("Location: receipts.php?" . ($pId ? "payment_id=$pId&recorded=true" : "recorded=error"));
      exit;
   }

   // -------------------------------------------------------------
   // RBAC ROLES & PERMISSIONS
   // -------------------------------------------------------------

   function procAddRole()
   {
      global $database, $session;
      $this->requirePerm('roles', 'create', 'roles.php');
      $name = $_POST['role_name'] ?? '';
      $desc = $_POST['description'] ?? '';
      $id = $database->addRole($name, $desc, 1);
      header("Location: roles.php?" . ($id ? "role_id=$id&added=true" : "added=error"));
      exit;
   }

   function procUpdateRole()
   {
      global $database, $session;
      $this->requirePerm('roles', 'edit', 'roles.php');
      $id = intval($_POST['role_id']);
      $name = $_POST['role_name'] ?? '';
      $desc = $_POST['description'] ?? '';
      $is_active = isset($_POST['is_active']) ? 1 : 0;
      $database->updateRole($id, $name, $desc, $is_active);
      header("Location: roles.php?role_id=$id&updated=true");
      exit;
   }

   function procSaveRolePermissions()
   {
      global $database, $session;
      $this->requirePerm('roles', 'edit', 'roles.php');
      $role_id = intval($_POST['role_id']);
      $perms = $_POST['perms'] ?? [];
      $database->saveRolePermissions($role_id, $perms);
      header("Location: roles.php?role_id=$role_id&saved=true");
      exit;
   }

   function procAddUser()
   {
      global $database, $session;
      $this->requirePerm('users', 'create', 'users.php');
      $user = $_POST['username'] ?? '';
      $pass = $_POST['password'] ?? '';
      $email = $_POST['email'] ?? '';
      $role_id = intval($_POST['role_id'] ?? ROLE_ADMISSIONS_ID);
      $fullname = $_POST['full_name'] ?? '';
      $phone = $_POST['phone'] ?? '';

      $ok = $database->addNewUser($user, $pass, $email, $role_id, $fullname, $phone);
      header("Location: users.php?" . ($ok ? "added=true" : "added=error"));
      exit;
   }

   function procUpdateUserStatus()
   {
      global $database, $session;
      $this->requirePerm('users', 'edit', 'users.php');
      $user = $_POST['username'] ?? '';
      $status = $_POST['status'] ?? 'Active';
      $role_id = intval($_POST['role_id'] ?? 0);
      $database->updateUserStatus($user, $status, $role_id);
      header("Location: users.php?updated=true");
      exit;
   }

   function procUpdateSettings()
   {
      global $database, $session;
      $this->requirePerm('settings', 'edit', 'settings.php');
      if (!empty($_POST['settings'])) {
         foreach ($_POST['settings'] as $k => $v) {
            $database->updateSystemSetting($k, $v);
         }
      }
      header("Location: settings.php?saved=true");
      exit;
   }

   function procChangePassword()
   {
      global $session;
      $username = $_POST['username'] ?? $session->username;
      $curpass = $_POST['curpass'] ?? '';
      $newpass = $_POST['newpass'] ?? '';
      $retval = $session->changepassword($username, $curpass, $newpass);
      header("Location: settings.php?msg=" . ($retval === 0 ? "pass_success" : "pass_error"));
      exit;
   }

   function procChangeAccountDetails()
   {
      global $session;
      $username = $_POST['username'] ?? $session->username;
      $displayname = $_POST['displayname'] ?? '';
      $email = $_POST['email'] ?? '';
      $phone = $_POST['phone'] ?? '';
      $retval = $session->change_accountdetails($username, $displayname, $email, $phone);
      header("Location: settings.php?msg=" . ($retval === 0 ? "account_success" : "account_error"));
      exit;
   }

   function procUploadImage()
   {
      global $session;
      if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
         header("Location: settings.php?msg=upload_error");
         exit;
      }
      $image = basename($_FILES['image']['name']);
      $fileTmpPath = $_FILES['image']['tmp_name'];
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
      $fileExt = strtolower(pathinfo($image, PATHINFO_EXTENSION));
      if (!in_array($fileExt, $allowedExtensions)) {
         header("Location: settings.php?msg=invalid_image_type");
         exit;
      }
      $dest = "../assets/uploads/" . $image;
      if (!is_dir("../assets/uploads")) @mkdir("../assets/uploads", 0777, true);
      move_uploaded_file($fileTmpPath, $dest);
      $session->uploadimage($_POST['username'] ?? $session->username, $image);
      header("Location: settings.php?msg=upload_success");
      exit;
   }
}

new DashboardProcess;
?>
