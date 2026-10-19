<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\AdmissionQuery;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdmissionController extends Controller
{
    // Home Page with School Details
    public function home()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $defaultYear = "{$currentYear}-{$nextYear}";
        $academicYear = SiteSetting::get('academic_year', $defaultYear) ?: $defaultYear;

        $form1Enabled = SiteSetting::isFormEnabled('form1');
        $form2Enabled = SiteSetting::isFormEnabled('form2');
        $form3Enabled = SiteSetting::isFormEnabled('form3');

        return view('home', [
            'academicYear' => $academicYear,
            'form1Enabled' => $form1Enabled,
            'form2Enabled' => $form2Enabled,
            'form3Enabled' => $form3Enabled,
            'anyFormEnabled' => ($form1Enabled || $form2Enabled || $form3Enabled),
        ]);
    }

    // Form Selection Page
    public function showFormSelection()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $defaultYear = "{$currentYear}-{$nextYear}";
        $academicYear = SiteSetting::get('academic_year', $defaultYear) ?: $defaultYear;

        $form1Enabled = SiteSetting::isFormEnabled('form1');
        $form2Enabled = SiteSetting::isFormEnabled('form2');
        $form3Enabled = SiteSetting::isFormEnabled('form3');

        return view('admission.select-form', [
            'academicYear' => $academicYear,
            'form1Enabled' => $form1Enabled,
            'form2Enabled' => $form2Enabled,
            'form3Enabled' => $form3Enabled,
            'anyFormEnabled' => ($form1Enabled || $form2Enabled || $form3Enabled),
        ]);
    }

    // Process Form Selection
    public function processFormSelection(Request $request)
    {
        $request->validate([
            'selected_form' => 'required|in:form1,form2,form3'
        ]);

        if (!SiteSetting::isFormEnabled($request->selected_form)) {
            return redirect()->route('admission.select')->with('error', 'The selected admission form is currently closed.');
        }

        $routeMap = [
            'form1' => 'admission.form1',
            'form2' => 'admission.form2',
            'form3' => 'admission.form3'
        ];

        return redirect()->route($routeMap[$request->selected_form]);
    }

    // Show Form 1 (Pre-primary to Class 2)
    public function showForm1()
    {
        if (!SiteSetting::isFormEnabled('form1')) {
            return redirect()->route('admission.select')->with('error', 'Primary Admission Form is currently closed.');
        }

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $defaultYear = "{$currentYear}-{$nextYear}";
        $academicYear = SiteSetting::get('academic_year', $defaultYear) ?: $defaultYear;

        return view('admission.form1', [
            'academicYear' => $academicYear,
            'classes' => SiteSetting::getFormClasses('form1')
        ]);
    }

    // Show Form 2 (Class 2 to 10)
    public function showForm2()
    {
        if (!SiteSetting::isFormEnabled('form2')) {
            return redirect()->route('admission.select')->with('error', 'Secondary Admission Form is currently closed.');
        }

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $defaultYear = "{$currentYear}-{$nextYear}";
        $academicYear = SiteSetting::get('academic_year', $defaultYear) ?: $defaultYear;

        return view('admission.form2', [
            'academicYear' => $academicYear,
            'classes' => SiteSetting::getFormClasses('form2'),
            'idTypes' => ['Aadhar', 'Birth Certificate', 'Passport', 'PAN Card', 'Voter ID', 'Other']
        ]);
    }

    // Show Form 3 (Higher Secondary)
    public function showForm3()
    {
        if (!SiteSetting::isFormEnabled('form3')) {
            return redirect()->route('admission.select')->with('error', 'HSC Admission Form is currently closed.');
        }

        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $defaultYear = "{$currentYear}-{$nextYear}";
        $academicYear = SiteSetting::get('academic_year', $defaultYear) ?: $defaultYear;

        return view('admission.form3', [
            'academicYear' => $academicYear,
            'classes' => SiteSetting::getFormClasses('form3'),
            'subjectGroups' => SiteSetting::getForm3SubjectGroups(),
        ]);
    }

    // Submit Form 1 (Pre-primary to Class 2)
    public function submitForm1(Request $request)
    {
        \Log::info('Form 1 submission started', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => session()->getId()
        ]);

        if (!SiteSetting::isFormEnabled('form1')) {
            return redirect()->route('admission.select')->with('error', 'The Pre-primary admission form is currently closed.');
        }


        // Validate captcha first
        if (!$this->validateCaptcha($request->input('g-recaptcha-response'))) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Please complete the security verification.'
            ])->withInput();
        }

        $validated = $request->validate([
            // Basic Information
            'surname' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile_1' => 'required|string|max:15',
            'admission_sought_for_class' => 'required|string|max:50',

            // Personal Information
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date',
            'date_of_birth_words' => 'required|string|max:255',
            'place_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:100',
            'religion' => 'required|string|max:100',
            'caste' => 'required|string|max:100',
            'sub_caste' => 'nullable|string|max:100',
            'mother_tongue' => 'required|string|max:100',
            'blood_group' => 'nullable|string|max:10',
            'aadhar_no' => 'required|digits:12',

            // Address
            'present_address' => 'required|string',
            'permanent_address' => 'required|string',

            // Parent Information
            'father_full_name' => 'required|string|max:255',
            'mother_full_name' => 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_designation' => 'nullable|string|max:255',
            'father_qualification' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_designation' => 'nullable|string|max:255',
            'mother_qualification' => 'nullable|string|max:255',
            'parents_guardian_full_name' => 'nullable|string|max:255',

            // Previous School (if any)
            'last_school_attended' => 'nullable|string|max:255',
            'studying_in_std' => 'nullable|string|max:50',
            'last_school_address' => 'nullable|string',
            'medium_of_instruction' => 'nullable|string|max:100',

            // Academic Year
            'academic_year' => 'required|string|max:20',

            // Files
            'passport_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'father_photo' => 'required_without:mother_photo|image|mimes:jpeg,png,jpg|max:2048',
            'mother_photo' => 'required_without:father_photo|image|mimes:jpeg,png,jpg|max:2048',

            // Declarations
            'is_physically_unfit' => 'nullable|boolean',
            'agreed_to_rules' => 'required|accepted',

            // Captcha
            // Captcha (Validated manually above)
        ]);

        try {
            // Handle file uploads
            $filePaths = [];
            $fileFields = [
                'passport_photo' => 'passport_photo_path',
                'father_photo' => 'father_photo_path',
                'mother_photo' => 'mother_photo_path'
            ];

            foreach ($fileFields as $field => $dbField) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $extension = $file->extension() ?: 'jpg';
                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    $path = $file->storeAs('admission-documents/' . date('Y/m'), $fileName, 'public');
                    $filePaths[$dbField] = $path;
                }
            }

            // Generate application number
            // Generate application number safely based on max ID to prevent duplicates
            $nextId = (AdmissionQuery::max('id') ?? 0) + 1;
            $applicationNumber = 'ADM' . date('Y') . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            // Prepare data
            $admissionData = [
                'form_type' => 'form1',
                'application_number' => $applicationNumber,
                'academic_year' => $validated['academic_year'],

                // Student Info
                'surname' => $validated['surname'],
                'first_name' => $validated['first_name'],
                'student_name' => trim($validated['first_name'] . ' ' . ($validated['surname'] ?? '')),
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'date_of_birth_words' => $validated['date_of_birth_words'],
                'place_of_birth' => $validated['place_of_birth'],
                'nationality' => $validated['nationality'],
                'religion' => $validated['religion'],
                'caste' => $validated['caste'],
                'sub_caste' => $validated['sub_caste'] ?? null,
                'mother_tongue' => $validated['mother_tongue'],
                'blood_group' => $validated['blood_group'] ?? null,
                'aadhar_no' => $validated['aadhar_no'],

                // Contact
                'email' => $validated['email'],
                'mobile_1' => $validated['mobile_1'],
                'mobile_2' => $request->input('mobile_2'),

                // Address
                'present_address' => $validated['present_address'],
                'permanent_address' => $validated['permanent_address'],
                'local_address' => $validated['present_address'],

                // Parent Info
                'father_full_name' => $validated['father_full_name'],
                'mother_full_name' => $validated['mother_full_name'],
                'father_occupation' => $validated['father_occupation'] ?? null,
                'father_designation' => $validated['father_designation'] ?? null,
                'father_qualification' => $validated['father_qualification'] ?? null,
                'mother_occupation' => $validated['mother_occupation'] ?? null,
                'mother_designation' => $validated['mother_designation'] ?? null,
                'mother_qualification' => $validated['mother_qualification'] ?? null,
                'parents_guardian_full_name' => $validated['parents_guardian_full_name'],

                // Education
                'admission_sought_for_class' => $validated['admission_sought_for_class'],
                'last_school_attended' => $validated['last_school_attended'] ?? null,
                'studying_in_std' => $validated['studying_in_std'] ?? null,
                'last_school_address' => $validated['last_school_address'] ?? null,
                'medium_of_instruction' => $validated['medium_of_instruction'] ?? null,

                // Declarations
                'is_physically_unfit' => $request->boolean('is_physically_unfit'),
                'agreed_to_rules' => true,

                // Status
                'status' => 'pending',
                'submitted_ip' => $request->ip(),
                'submitted_user_agent' => $request->userAgent(),
            ];

            // Merge file paths
            $admissionData = array_merge($admissionData, $filePaths);

            // Create the admission query
            $admission = AdmissionQuery::create($admissionData);

            // Send confirmation email
            $this->sendConfirmationEmail($admission);

            \Log::info('Form 1 application submitted successfully', [
                'application_id' => $admission->id,
                'application_number' => $admission->application_number,
            ]);

            // Store authorized submission ID in session
            $submitted = $request->session()->get('submitted_admission_ids', []);
            $submitted[] = $admission->id;
            $request->session()->put('submitted_admission_ids', $submitted);

            // Regenerate CSRF token after successful submission
            $request->session()->regenerateToken();

            return redirect()->route('admission.success', $admission->id)
                ->with('success', 'Application submitted successfully! Your application number is ' . $admission->application_number);

        } catch (\Exception $e) {
            \Log::error('Form 1 submission error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Failed to submit application. An unexpected error occurred. Please try again or contact the school office.'])->withInput();
        }
    }

    // Submit Form 2 (Class 3 to 10)
    public function submitForm2(Request $request)
    {
        if (!SiteSetting::isFormEnabled('form2')) {
            return redirect()->route('admission.select')->with('error', 'The Secondary admission form is currently closed.');
        }
        \Log::info('Form 2 submission started', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => session()->getId()
        ]);


        // Validate captcha first
        if (!$this->validateCaptcha($request->input('g-recaptcha-response'))) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Please complete the security verification.'
            ])->withInput();
        }

        $validated = $request->validate([
            // Student Information
            'surname' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'father_designation' => 'nullable|string|max:255',
            'mother_designation' => 'nullable|string|max:255',
            'father_qualification' => 'nullable|string|max:255',
            'mother_qualification' => 'nullable|string|max:255',

            // Date of Birth
            'date_of_birth' => 'required|date',
            'date_of_birth_words' => 'required|string|max:255',

            // Personal Details
            'gender' => 'required|in:Male,Female,Other',
            'nationality' => 'required|string|max:100',
            'religion' => 'required|string|max:100',
            'caste' => 'required|string|max:100',
            'sub_caste' => 'nullable|string|max:100',
            'mother_tongue' => 'required|string|max:100',
            'place_of_birth' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:10',

            // Previous School
            'last_school_attended' => 'required|string|max:255',

            // Address
            'present_address' => 'required|string',
            'permanent_address' => 'required|string',

            // Contact
            'email' => 'nullable|email|max:255',
            'phone_no1' => 'required|digits:10',
            'phone_no2' => 'nullable|digits:10',

            // ID Details
            'aadhar_no' => 'required|digits:12',
            'apaar_id' => 'nullable|string|max:50',
            'pen_no' => 'nullable|string|max:50',
            'adharshila_number' => 'nullable|string|max:50',

            // Admission Details
            'admission_class' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',

            // Photos
            'student_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'father_photo' => 'required_without:mother_photo|image|mimes:jpeg,png,jpg|max:2048',
            'mother_photo' => 'required_without:father_photo|image|mimes:jpeg,png,jpg|max:2048',

            // Declaration
            'agree_declaration' => 'required|accepted',
            'agreed_to_rules' => 'required|accepted',

            // Captcha
            // Captcha (Validated manually above)
        ]);

        try {
            // Handle file uploads
            $filePaths = [];
            $fileFields = [
                'student_photo' => 'student_photo_path',
                'father_photo' => 'father_photo_path',
                'mother_photo' => 'mother_photo_path'
            ];

            foreach ($fileFields as $field => $dbField) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $extension = $file->extension() ?: 'jpg';
                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    $path = $file->storeAs('admission-documents/' . date('Y/m'), $fileName, 'public');
                    $filePaths[$dbField] = $path;
                }
            }

            // Generate application number
            // Generate application number safely based on max ID to prevent duplicates
            $nextId = (AdmissionQuery::max('id') ?? 0) + 1;
            $applicationNumber = 'ADM' . date('Y') . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            // Prepare data
            $admissionData = [
                'form_type' => 'form2',
                'application_number' => $applicationNumber,
                'academic_year' => $validated['academic_year'],

                // Student Info
                'surname' => $validated['surname'],
                'first_name' => $validated['first_name'],
                'student_name' => trim($validated['first_name'] . ' ' . ($validated['surname'] ?? '')),
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'date_of_birth_words' => $validated['date_of_birth_words'],
                'nationality' => $validated['nationality'],
                'religion' => $validated['religion'],
                'caste' => $validated['caste'],
                'sub_caste' => $validated['sub_caste'] ?? null,
                'mother_tongue' => $validated['mother_tongue'],
                'place_of_birth' => $validated['place_of_birth'],
                'blood_group' => $validated['blood_group'] ?? null,
                'mobile_1' => $validated['phone_no1'],
                'mobile_2' => $validated['phone_no2'] ?? null,

                // Parent Info
                'father_name' => $validated['father_name'],
                'mother_name' => $validated['mother_name'],
                'father_occupation' => $validated['father_occupation'],
                'mother_occupation' => $validated['mother_occupation'],
                'father_designation' => $validated['father_designation'] ?? null,
                'mother_designation' => $validated['mother_designation'] ?? null,
                'father_qualification' => $validated['father_qualification'] ?? null,
                'mother_qualification' => $validated['mother_qualification'] ?? null,

                // Address
                'present_address' => $validated['present_address'],
                'local_address' => $validated['present_address'], // Map to local_address to satisfy DB constraint
                'permanent_address' => $validated['permanent_address'],

                // Contact
                'email' => $validated['email'],
                'phone_no1' => $validated['phone_no1'],
                'phone_no2' => $validated['phone_no2'] ?? null,

                // Education
                'admission_class' => $validated['admission_class'],
                'last_school_attended' => $validated['last_school_attended'],

                // ID Details
                'aadhar_no' => $validated['aadhar_no'],
                'apaar_id' => $validated['apaar_id'] ?? null,
                'pen_no' => $validated['pen_no'] ?? null,
                'adharshila_number' => $validated['adharshila_number'] ?? null,

                // Declarations
                'agree_declaration' => true,
                'agreed_to_rules' => true,

                // Status
                'status' => 'pending',
                'submitted_ip' => $request->ip(),
                'submitted_user_agent' => $request->userAgent(),
            ];

            $admissionData['parents_guardian_full_name'] = $request->input('parents_guardian_full_name');
            $admissionData['father_full_name'] = $validated['father_name'];
            $admissionData['mother_full_name'] = $validated['mother_name'];

            // Default values for fields not in Form 2
            $admissionData['last_school_address'] = 'N/A';
            $admissionData['studying_in_std'] = 'N/A';
            $admissionData['medium_of_instruction'] = 'N/A';

            // Normalize Class (Form 2 sends admission_class, map to admission_sought_for_class too)
            $admissionData['admission_sought_for_class'] = $validated['admission_class'];

            // Merge file paths
            $admissionData = array_merge($admissionData, $filePaths);

            // Create the admission query
            $admission = AdmissionQuery::create($admissionData);

            // Send confirmation email
            $this->sendConfirmationEmail($admission);

            \Log::info('Form 2 application submitted successfully', [
                'application_id' => $admission->id,
                'application_number' => $admission->application_number,
            ]);

            // Store authorized submission ID in session
            $submitted = $request->session()->get('submitted_admission_ids', []);
            $submitted[] = $admission->id;
            $request->session()->put('submitted_admission_ids', $submitted);

            // Regenerate CSRF token after successful submission
            $request->session()->regenerateToken();

            return redirect()->route('admission.success', $admission->id)
                ->with('success', 'Application submitted successfully! Your application number is ' . $admission->application_number);

        } catch (\Exception $e) {
            \Log::error('Form 2 submission error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Failed to submit application. An unexpected error occurred. Please try again or contact the school office.'])->withInput();
        }
    }






    // Submit Form 3 (Higher Secondary)
    public function submitForm3(Request $request)
    {
        if (!SiteSetting::isFormEnabled('form3')) {
            return redirect()->route('admission.select')->with('error', 'The Junior College admission form is currently closed.');
        }
        \Log::info('Form 3 submission started', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => session()->getId()
        ]);


        // Validate captcha first
        if (!$this->validateCaptcha($request->input('g-recaptcha-response'))) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Please complete the security verification.'
            ])->withInput();
        }

        // UPDATED VALIDATION RULES - Documents are now optional
        $validated = $request->validate([
            // Basic Information (similar to Form 2)
            'surname' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_qualification' => 'nullable|string|max:255',
            'father_designation' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_qualification' => 'nullable|string|max:255',
            'mother_designation' => 'nullable|string|max:255',

            // Date of Birth
            'date_of_birth' => 'required|date',
            'date_of_birth_words' => 'required|string|max:255',

            // Personal Details
            'gender' => 'required|in:Male,Female,Other',
            'nationality' => 'required|string|max:100',
            'religion' => 'required|string|max:100',
            'caste' => 'required|string|max:100',
            'sub_caste' => 'nullable|string|max:100',
            'mother_tongue' => 'required|string|max:100',
            'blood_group' => 'nullable|string|max:10',

            // Previous School
            'last_school_attended' => 'required|string|max:255',

            // Address
            'present_address' => 'required|string',
            'place_of_birth' => 'required|string|max:100',
            'permanent_address' => 'required|string',

            // Contact
            'email' => 'nullable|email|max:255',
            'phone_no1' => 'required|digits:10',
            'phone_no2' => 'nullable|digits:10',

            // ID Details
            'aadhar_no' => 'required|digits:12',
            'apaar_id' => 'nullable|string|max:50',
            'pen_no' => 'nullable|string|max:50',
            'adharshila_number' => 'nullable|string|max:50',

            // Admission Details
            'admission_class' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',

            // Subject Group Selection
            'subject_group' => 'required|string|max:100',

            'parents_guardian_full_name' => 'nullable|string|max:255',

            // Photos
            'student_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'father_photo' => 'required_without:mother_photo|image|mimes:jpeg,png,jpg|max:2048',
            'mother_photo' => 'required_without:father_photo|image|mimes:jpeg,png,jpg|max:2048',

            // Declaration
            'agree_declaration' => 'required|accepted',
            'agreed_to_rules' => 'required|accepted',
        ]);
        \Log::info('Form 3 validation passed', ['validated_data' => array_keys($validated)]);

        try {
            // Handle file uploads
            $filePaths = [];
            $fileFields = [
                'student_photo' => 'student_photo_path',
                'father_photo' => 'father_photo_path',
                'mother_photo' => 'mother_photo_path'
            ];

            foreach ($fileFields as $field => $dbField) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $extension = $file->extension() ?: 'jpg';
                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    $path = $file->storeAs('admission-documents/' . date('Y/m'), $fileName, 'public');
                    $filePaths[$dbField] = $path;
                } else {
                    // Set to null if file not uploaded (optional)
                    $filePaths[$dbField] = null;
                }
            }

            // Generate application number
            // Generate application number safely based on max ID to prevent duplicates
            $nextId = (AdmissionQuery::max('id') ?? 0) + 1;
            $applicationNumber = 'ADM' . date('Y') . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            // Prepare data for database - Match all fields with your AdmissionQuery model
            $admissionData = [
                'form_type' => 'form3',
                'application_number' => $applicationNumber,
                'academic_year' => $validated['academic_year'],

                // Student Info
                'surname' => $validated['surname'],
                'first_name' => $validated['first_name'],
                'student_name' => trim($validated['first_name'] . ' ' . ($validated['surname'] ?? '')),
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'date_of_birth_words' => $validated['date_of_birth_words'],
                'place_of_birth' => $validated['place_of_birth'],
                'nationality' => $validated['nationality'],
                'religion' => $validated['religion'],
                'caste' => $validated['caste'],
                'sub_caste' => $validated['sub_caste'] ?? null,
                'blood_group' => $validated['blood_group'] ?? null,
                'mother_tongue' => $validated['mother_tongue'],

                // Parent Info
                'father_name' => $validated['father_name'],
                'father_full_name' => $validated['father_name'],
                'mother_name' => $validated['mother_name'],
                'mother_full_name' => $validated['mother_name'],
                'father_occupation' => $validated['father_occupation'] ?? 'Guardian / Service',
                'father_designation' => $validated['father_designation'] ?? null,
                'father_qualification' => $validated['father_qualification'] ?? null,
                'mother_occupation' => $validated['mother_occupation'] ?? 'Homemaker / Service',
                'mother_designation' => $validated['mother_designation'] ?? null,
                'mother_qualification' => $validated['mother_qualification'] ?? null,
                'parents_guardian_full_name' => $validated['parents_guardian_full_name'] ?? null,

                // Address
                'present_address' => $validated['present_address'],
                'local_address' => $validated['present_address'],
                'permanent_address' => $validated['permanent_address'],

                // Contact
                'email' => $validated['email'],
                'phone_no1' => $validated['phone_no1'],
                'phone_no2' => $validated['phone_no2'] ?? null,
                'mobile_1' => $validated['phone_no1'],
                'mobile_2' => $validated['phone_no2'] ?? null,

                // Education
                'admission_class' => $validated['admission_class'],
                'admission_sought_for_class' => $validated['admission_class'],
                'last_school_attended' => $validated['last_school_attended'],

                // ID Details
                'aadhar_no' => $validated['aadhar_no'],
                'apaar_id' => $validated['apaar_id'] ?? null,
                'pen_no' => $validated['pen_no'] ?? null,
                'adharshila_number' => $validated['adharshila_number'] ?? null,

                // Subject Group
                'subject_group' => $validated['subject_group'],

                // Declarations
                'agree_declaration' => true,
                'agreed_to_rules' => true,
                'is_physically_unfit' => false,

                // Status
                'status' => 'pending',
                'submitted_ip' => $request->ip(),
                'submitted_user_agent' => $request->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Parents info and Guardian already set in $admissionData

            // Default values for fields not in Form 3
            // place_of_birth is now handled above
            $admissionData['last_school_address'] = 'N/A';
            $admissionData['studying_in_std'] = 'N/A';
            $admissionData['medium_of_instruction'] = 'N/A';

            // Normalize Stream and Subjects dynamically from Subject Group
            if (isset($validated['subject_group'])) {
                $group = $validated['subject_group'];
                $admissionData['stream'] = SiteSetting::getStreamForSubjectGroup($group);
                $subjects = SiteSetting::getSubjectsForGroup($group);
                if (!empty($subjects)) {
                    $admissionData['selected_subjects'] = $subjects;
                }
            }

            // Merge file paths
            $admissionData = array_merge($admissionData, $filePaths);

            // Log the data for debugging
            \Log::info('Admission data prepared:', array_keys($admissionData));

            // Create the admission query
            $admission = AdmissionQuery::create($admissionData);

            // Send confirmation email
            $this->sendConfirmationEmail($admission);

            \Log::info('Form 3 application submitted successfully', [
                'application_id' => $admission->id,
                'application_number' => $admission->application_number,
            ]);

            // Store authorized submission ID in session
            $submitted = $request->session()->get('submitted_admission_ids', []);
            $submitted[] = $admission->id;
            $request->session()->put('submitted_admission_ids', $submitted);

            // Regenerate CSRF token after successful submission
            $request->session()->regenerateToken();

            return redirect()->route('admission.success', $admission->id)
                ->with('success', 'Application submitted successfully! Your application number is ' . $admission->application_number);

        } catch (\Exception $e) {
            \Log::error('Form 3 submission error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $validated ?? [],
                'file_errors' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'Failed to submit application. An unexpected error occurred. Please try again or contact the school office.'])->withInput();
        }
    }






    // Show Success Page
    public function showSuccess($id)
    {
        try {
            // IDOR Protection: Allow only if user submitted in current session, has valid signed URL, or is admin
            $allowed = auth()->check()
                || request()->hasValidSignature()
                || in_array((int) $id, array_map('intval', session()->get('submitted_admission_ids', [])));

            if (!$allowed) {
                return redirect()->route('home')->withErrors(['error' => 'Unauthorized or expired session for this application.']);
            }

            $admission = AdmissionQuery::findOrFail($id);

            if ($admission->date_of_birth) {
                $admission->age = Carbon::parse($admission->date_of_birth)->age;
            }

            return view('admission.success', compact('admission'));

        } catch (\Exception $e) {
            \Log::error('Error showing success page: ' . $e->getMessage());
            return redirect()->route('home')->withErrors(['error' => 'Application not found.']);
        }
    }

    // Download PDF
    public function downloadPDF($id)
    {
        try {
            // IDOR Protection: Allow only if user submitted in current session, has valid signed URL, or is admin
            $allowed = auth()->check()
                || request()->hasValidSignature()
                || in_array((int) $id, array_map('intval', session()->get('submitted_admission_ids', [])));

            if (!$allowed) {
                abort(403, 'Unauthorized access to download this application.');
            }

            $admission = AdmissionQuery::findOrFail($id);

            // Check if already downloaded (Skip for admins)
            if ($admission->downloaded_at && !auth()->check()) {
                return redirect()->route('admission.success', $id)
                    ->with('error', 'The application form can only be downloaded once. If you need it again, please contact the school administration.');
            }

            // Mark as downloaded (Only for non-admins)
            if (!auth()->check()) {
                $admission->update(['downloaded_at' => now()]);
            }

            $studentPhotoBase64 = null;

            // Get student photo (check both possible field names)
            $photoPath = $admission->student_photo_path ?? $admission->passport_photo_path;

            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                $photoData = Storage::disk('public')->get($photoPath);
                $studentPhotoBase64 = base64_encode($photoData);
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $photoMime = $finfo->buffer($photoData);
            }

            $fatherPhotoBase64 = null;
            $fatherPhotoMime = null;
            if ($admission->father_photo_path && Storage::disk('public')->exists($admission->father_photo_path)) {
                $photoData = Storage::disk('public')->get($admission->father_photo_path);
                $fatherPhotoBase64 = base64_encode($photoData);
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $fatherPhotoMime = $finfo->buffer($photoData);
            }

            $motherPhotoBase64 = null;
            $motherPhotoMime = null;
            if ($admission->mother_photo_path && Storage::disk('public')->exists($admission->mother_photo_path)) {
                $photoData = Storage::disk('public')->get($admission->mother_photo_path);
                $motherPhotoBase64 = base64_encode($photoData);
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $motherPhotoMime = $finfo->buffer($photoData);
            }

            // Get school logo - try multiple fallback paths
            $logoBase64 = null;
            $logoPath = null;

            // Priority 1: storage/public/logo.png (The one verified via curl in production)
            if (Storage::disk('public')->exists('logo.png')) {
                $logoData = Storage::disk('public')->get('logo.png');
                $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
            }
            // Priority 2: public/images/logo.png (Standard location)
            elseif (file_exists(public_path('images/logo.png'))) {
                $logoPath = public_path('images/logo.png');
            }
            // Priority 3: public/images/logo1.png (Previously used fallback)
            elseif (file_exists(public_path('images/logo1.png'))) {
                $logoPath = public_path('images/logo1.png');
            }
            // Priority 4: public/images/logow.png (Used in frontend)
            elseif (file_exists(public_path('images/logow.png'))) {
                $logoPath = public_path('images/logow.png');
            }

            if (!$logoBase64 && $logoPath) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }

            $data = [
                'admission' => $admission,
                'logo_base64' => $logoBase64,
                'student_photo_base64' => $studentPhotoBase64 ? "data:{$photoMime};base64,{$studentPhotoBase64}" : null,
                'father_photo_base64' => $fatherPhotoBase64 ? "data:{$fatherPhotoMime};base64,{$fatherPhotoBase64}" : null,
                'mother_photo_base64' => $motherPhotoBase64 ? "data:{$motherPhotoMime};base64,{$motherPhotoBase64}" : null,
                'current_date' => now()->format('d/m/Y'),
            ];

            // Set basic options but avoid overriding public_path unless necessary context dictates
            $pdf = Pdf::loadView('admission.pdf', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('defaultFont', 'DejaVu Sans');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', false);

            $filename = 'Admission_Application_' . ($admission->application_number ?? $admission->id) . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('PDF generation error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->withErrors(['error' => 'Failed to generate PDF. Please contact the administrator.']);
        }
    }

    // Send Confirmation Email
    private function sendConfirmationEmail($admission)
    {
        try {
            if (config('mail.default') !== 'log') {
                $studentPhotoBase64 = null;
                $photoPath = $admission->student_photo_path ?? $admission->passport_photo_path;

                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    $photoFullPath = Storage::disk('public')->path($photoPath);
                    $studentPhotoBase64 = base64_encode(file_get_contents($photoFullPath));
                    $photoMime = mime_content_type($photoFullPath);
                }

                $fatherPhotoBase64 = null;
                $fatherPhotoMime = null;
                if ($admission->father_photo_path && Storage::disk('public')->exists($admission->father_photo_path)) {
                    $photoFullPath = Storage::disk('public')->path($admission->father_photo_path);
                    $fatherPhotoBase64 = base64_encode(file_get_contents($photoFullPath));
                    $fatherPhotoMime = mime_content_type($photoFullPath);
                }

                $motherPhotoBase64 = null;
                $motherPhotoMime = null;
                if ($admission->mother_photo_path && Storage::disk('public')->exists($admission->mother_photo_path)) {
                    $photoFullPath = Storage::disk('public')->path($admission->mother_photo_path);
                    $motherPhotoBase64 = base64_encode(file_get_contents($photoFullPath));
                    $motherPhotoMime = mime_content_type($photoFullPath);
                }

                // Logo handling (Same as downloadPDF)
                $logoPath = public_path('images/logo1.png');
                $logoBase64 = null;
                if (file_exists($logoPath)) {
                    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                }

                $data = [
                    'admission' => $admission,
                    'logo_base64' => $logoBase64,
                    'student_photo_base64' => $studentPhotoBase64 ? "data:{$photoMime};base64,{$studentPhotoBase64}" : null,
                    'father_photo_base64' => $fatherPhotoBase64 ? "data:{$fatherPhotoMime};base64,{$fatherPhotoBase64}" : null,
                    'mother_photo_base64' => $motherPhotoBase64 ? "data:{$motherPhotoMime};base64,{$motherPhotoBase64}" : null,
                    'current_date' => now()->format('d/m/Y'),
                ];

                // Generate PDF (configure same options as download)
                $pdf = PDF::loadView('admission.pdf', $data);
                $pdf->setOption('isHtml5ParserEnabled', true);
                $pdf->setOption('isRemoteEnabled', false);

                if ($admission->email) {
                    Mail::send('emails.admission-confirmation', ['admission' => $admission], function ($message) use ($admission, $pdf) {
                        $message->to($admission->email)
                            ->subject('Admission Application Received - ' . ($admission->application_number ?? ''))
                            ->attachData($pdf->output(), 'Application-' . $admission->id . '.pdf', [
                                'mime' => 'application/pdf',
                            ]);
                    });

                    \Log::info('Confirmation email sent to: ' . $admission->email);
                } else {
                    \Log::info('Confirmation email skipped: Email not provided for application ' . $admission->application_number);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }
    }

    // Legacy method for compatibility (optional)
    public function showForm()
    {
        return $this->showFormSelection();
    }

    // CSRF Token validation helper
    private function validateCsrfToken(Request $request)
    {
        $csrfToken = $request->input('_token');
        $sessionToken = session()->token();

        return hash_equals($sessionToken, $csrfToken);
    }

    // Handle CSRF error
    private function handleCsrfError(Request $request)
    {
        \Log::warning('CSRF token mismatch detected', [
            'session_token' => session()->token(),
            'submitted_token' => $request->input('_token'),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Regenerate session token
        $request->session()->regenerateToken();

        return back()
            ->withInput($request->except('_token', 'password', 'password_confirmation'))
            ->withErrors([
                'csrf_error' => 'Your session has expired. Please refresh the page and try again.'
            ]);
    }

    // Captcha validation helper
    private function validateCaptcha($response)
    {
        if (empty($response)) {
            return false;
        }

        $secret = config('captcha.secret_key');

        // For local development/testing, you might want to bypass captcha
        if (app()->environment('local') && $response === 'test') {
            return true;
        }

        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

        $data = [
            'secret' => $secret,
            'response' => $response,
            'remoteip' => request()->ip()
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($verifyUrl, false, $context);
        $resultJson = json_decode($result);

        return $resultJson->success ?? false;
    }
}
