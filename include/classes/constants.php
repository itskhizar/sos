<?php
/**
 * SOS Hermann Gmeiner School & College · System Constants
 * Single Source of Truth for Database and Application Constants
 */

define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "sos");

/**
 * Database Table Constants
 */
define("TBL_USERS", "users");
define("TBL_ACTIVE_USERS", "active_users");
define("TBL_ACTIVE_GUESTS", "active_guests");
define("TBL_BANNED_USERS", "banned_users");
define("TBL_ROLES", "roles");
define("TBL_PERMISSIONS", "permissions");
define("TBL_ROLE_PERMISSIONS", "role_permissions");
define("TBL_ACADEMIC_SESSIONS", "academic_sessions");
define("TBL_CLASSES", "classes");
define("TBL_CLASS_SESSIONS", "class_sessions");
define("TBL_SECTIONS", "sections");
define("TBL_SECTION_SESSIONS", "section_sessions");
define("TBL_ENQUIRIES", "enquiries");
define("TBL_ENQUIRY_FOLLOWUPS", "enquiry_followups");
define("TBL_APPLICATIONS", "admission_applications");
define("TBL_APPLICATION_DOCS", "admission_documents");
define("TBL_ADMISSION_DOCS", "admission_documents");
define("TBL_ENTRY_TESTS", "entry_tests");
define("TBL_ENTRY_TEST_CANDIDATES", "entry_test_candidates");
define("TBL_STUDENTS", "students");
define("TBL_STUDENT_DOCS", "student_documents");
define("TBL_ENROLLMENTS", "enrollments");
define("TBL_PROMOTIONS", "promotions");
define("TBL_FEE_TYPES", "fee_types");
define("TBL_FEE_STRUCTURES", "fee_structures");
define("TBL_FEE_STRUCTURE_ITEMS", "fee_structure_items");
define("TBL_FEE_CHARGES", "fee_charges");
define("TBL_CONCESSIONS", "student_concessions");
define("TBL_CONCESSION_TYPES", "concession_types");
define("TBL_FINES", "fines");
define("TBL_CHALLANS", "challans");
define("TBL_CHALLAN_ITEMS", "challan_items");
define("TBL_PAYMENTS", "payments");
define("TBL_RECEIPTS", "receipts");
define("TBL_AUDIT_LOGS", "audit_logs");
define("TBL_SETTINGS", "system_settings");
define("TBL_ADMISSION_CYCLES", "admission_cycles");
define("TBL_JOB_OPENINGS", "job_openings");
define("TBL_JOB_APPLICATIONS", "job_applications");

/**
 * User & Role Constants
 */
define("ADMIN_NAME", "admin");
define("GUEST_NAME", "Guest");
define("GUEST_LEVEL", 0);

define("ROLE_SUPER_ADMIN_ID", 1);
define("ROLE_PRINCIPAL_ID", 2);
define("ROLE_ADMISSIONS_ID", 3);
define("ROLE_FINANCE_ID", 4);

/**
 * System Settings
 */
define("TRACK_VISITORS", true);
define("TRACK_Visitors", true);
define("USER_TIMEOUT", 30);
define("GUEST_TIMEOUT", 10);
define("COOKIE_EXPIRE", 60 * 60 * 24 * 7); // 7 days
define("COOKIE_PATH", "/");

define("SCHOOL_NAME", "SOS Hermann Gmeiner Public School & College");
define("SCHOOL_CAMPUS", "Dhodial, Mansehra");
define("EMAIL_FROM_NAME", "SOS Hermann Gmeiner Admissions");
define("EMAIL_FROM_ADDR", "admissions@soshg.edu");
define("ALL_LOWERCASE", false);
?>