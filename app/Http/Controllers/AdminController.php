<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdmissionQuery;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\StudentApplicationStatusMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total' => AdmissionQuery::count(),
            'pending' => AdmissionQuery::where('status', 'pending')->count(),
            'reviewed' => AdmissionQuery::where('status', 'reviewed')->count(),
            'accepted' => AdmissionQuery::where('status', 'accepted')->count(),
            'rejected' => AdmissionQuery::where('status', 'rejected')->count(),
            'today' => AdmissionQuery::whereDate('created_at', today())->count(),
            'paid' => AdmissionQuery::where('payment_status', 'paid')->count(),
            'unpaid' => AdmissionQuery::where('payment_status', 'unpaid')->count(),
            'partial' => AdmissionQuery::where('payment_status', 'partial')->count(),
            'total_collected' => AdmissionQuery::sum('paid_amount'),
        ];

        $formTypeStats = AdmissionQuery::select('form_type', DB::raw('count(*) as count'))
            ->groupBy('form_type')
            ->get();

        $topClasses = AdmissionQuery::select(
            DB::raw('CASE
                    WHEN form_type = "form1" THEN admission_sought_for_class
                    ELSE admission_class
                END as class_name'),
            'form_type',
            DB::raw('count(*) as total')
        )
            ->groupBy('class_name', 'form_type')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // FIXED: Removed the non-existent 'user' relationship
        $recentApplications = AdmissionQuery::latest()
            ->limit(10)
            ->get();

        $monthlyStats = AdmissionQuery::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $dailyStats = AdmissionQuery::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get recent activity logs
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(15)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'formTypeStats',
            'topClasses',
            'recentApplications',
            'monthlyStats',
            'dailyStats',
            'recentActivities'
        ));
    }

    /**
     * Show all applications with filtering and search
     */
    public function applications(Request $request)
    {
        try {
            $query = AdmissionQuery::query();

            // Search
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('id', $search)
                        ->orWhere('application_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('surname', 'like', "%{$search}%")
                        ->orWhere('student_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile_1', 'like', "%{$search}%")
                        ->orWhere('mobile_2', 'like', "%{$search}%")
                        ->orWhere('phone_no1', 'like', "%{$search}%")
                        ->orWhere('phone_no2', 'like', "%{$search}%")
                        ->orWhere('aadhar_no', 'like', "%{$search}%")
                        ->orWhere('apaar_id', 'like', "%{$search}%")
                        ->orWhere('pen_no', 'like', "%{$search}%");
                });
            }

            // Filter by form type
            if ($request->filled('form_type')) {
                $query->where('form_type', $request->form_type);
            }

            // Filter by status - using lowercase status values
            if ($request->filled('status') && in_array($request->status, ['pending', 'reviewed', 'accepted', 'rejected'])) {
                $query->where('status', $request->status);
            }

            // Filter by payment status
            if ($request->filled('payment_status') && in_array($request->payment_status, ['paid', 'unpaid', 'partial', 'fee'])) {
                $query->where('payment_status', $request->payment_status);
            }

            // Filter by document status
            if ($request->filled('doc_status')) {
                if ($request->doc_status === 'attached') {
                    $query->where(function ($q) {
                        $q->whereNotNull('student_photo_path')
                            ->orWhereNotNull('passport_photo_path')
                            ->orWhereNotNull('birth_certificate_path')
                            ->orWhereNotNull('caste_certificate_path');
                    });
                } elseif ($request->doc_status === 'missing') {
                    $query->whereNull('student_photo_path')->whereNull('passport_photo_path');
                }
            }

            // Filter by class
            if ($request->filled('class')) {
                $class = $request->class;
                $query->where(function ($q) use ($class) {
                    $q->where('admission_sought_for_class', 'like', "%{$class}%")
                        ->orWhere('admission_class', 'like', "%{$class}%");
                });
            }

            // Filter by application date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Filter by payment date range
            if ($request->filled('payment_date_from')) {
                $query->where(function ($q) use ($request) {
                    $q->whereDate('status_updated_at', '>=', $request->payment_date_from)
                        ->orWhere(function ($sub) use ($request) {
                            $sub->whereNull('status_updated_at')
                                ->whereDate('updated_at', '>=', $request->payment_date_from);
                        });
                });
            }

            if ($request->filled('payment_date_to')) {
                $query->where(function ($q) use ($request) {
                    $q->whereDate('status_updated_at', '<=', $request->payment_date_to)
                        ->orWhere(function ($sub) use ($request) {
                            $sub->whereNull('status_updated_at')
                                ->whereDate('updated_at', '<=', $request->payment_date_to);
                        });
                });
            }

            // Export functionality
            if ($request->has('export') && $request->export == 'true') {
                $filename = 'applications_export_' . date('Y-m-d_H-i-s') . '.csv';
                $headers = [
                    "Content-type" => "text/csv; charset=utf-8",
                    "Content-Disposition" => "attachment; filename=$filename",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                ];

                $columns = [
                    'ID',
                    'App No',
                    'Form',
                    'Year',
                    'Name',
                    'Gender',
                    'DOB',
                    'Birth Place',
                    'Religion',
                    'Caste',
                    'Sub Caste',
                    'Tongue',
                    'Blood',
                    'Father Name',
                    'Father Occ',
                    'Father Qual',
                    'Father Inc',
                    'Mother Name',
                    'Mother Occ',
                    'Mother Qual',
                    'Mother Inc',
                    'Mobile 1',
                    'Mobile 2',
                    'Email',
                    'Adhaar',
                    'APAAR',
                    'PEN',
                    'Adharshila No',
                    'Present Addr',
                    'Perm Addr',
                    'Class Sought',
                    'Prev School',
                    'Prev Class',
                    'Medium',
                    'Stream',
                    'Subjects',
                    'Fee Status',
                    'Total Fee',
                    'Paid',
                    'Balance',
                    'Status',
                    'Public Remarks',
                    'Admin Notes',
                    'Date'
                ];

                $callback = function () use ($query, $columns) {
                    $file = fopen('php://output', 'w');
                    // Add UTF-8 BOM for Excel
                    fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
                    fputcsv($file, $columns);

                    $sanitizeCsv = function ($val) {
                        if (is_string($val) && strlen($val) > 0 && in_array($val[0], ['=', '+', '-', '@', "\t", "\r"])) {
                            return "'" . $val;
                        }
                        return $val;
                    };

                    $query->chunk(100, function ($rows) use ($file, $sanitizeCsv) {
                        foreach ($rows as $row) {
                            $data = [
                                $row->id,
                                $row->application_number,
                                $row->form_type,
                                $row->academic_year,
                                $row->full_name,
                                $row->gender,
                                $row->date_of_birth ? $row->date_of_birth->format('d/m/Y') : '',
                                $row->place_of_birth,
                                $row->religion,
                                $row->caste,
                                $row->sub_caste,
                                $row->mother_tongue,
                                $row->blood_group,
                                $row->father_full_name ?? $row->father_name,
                                $row->father_occupation,
                                $row->father_qualification,
                                $row->father_designation,
                                $row->mother_full_name ?? $row->mother_name,
                                $row->mother_occupation,
                                $row->mother_qualification,
                                $row->mother_designation,
                                $row->mobile_1,
                                $row->mobile_2,
                                $row->email,
                                $row->aadhar_no,
                                $row->apaar_id,
                                $row->pen_no,
                                $row->adharshila_number,
                                $row->present_address ?? $row->local_address,
                                $row->permanent_address ?? $row->address,
                                $row->admission_sought_for_class ?? $row->admission_class,
                                $row->previous_school_name ?? $row->last_school_attended,
                                $row->previous_class ?? $row->studying_in_std,
                                $row->medium_of_instruction,
                                $row->stream,
                                is_array($row->selected_subjects) ? implode(', ', $row->selected_subjects) : $row->selected_subjects,
                                strtoupper($row->payment_status ?? 'unpaid'),
                                $row->total_fee,
                                $row->paid_amount,
                                $row->balance_amount,
                                $row->status_label,
                                $row->remarks,
                                $row->admin_notes,
                                $row->created_at->format('d/m/Y H:i')
                            ];
                            fputcsv($file, array_map($sanitizeCsv, $data));
                        }
                    });
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            // Get applications with pagination
            $applications = $query->latest()->paginate(20);

            // Get status counts for this view
            $statusCounts = [
                'total' => AdmissionQuery::count(),
                'pending' => AdmissionQuery::where('status', 'pending')->count(),
                'accepted' => AdmissionQuery::where('status', 'accepted')->count(),
                'rejected' => AdmissionQuery::where('status', 'rejected')->count(),
                'paid' => AdmissionQuery::where('payment_status', 'paid')->count(),
                'unpaid' => AdmissionQuery::where('payment_status', 'unpaid')->count(),
                'partial' => AdmissionQuery::where('payment_status', 'partial')->count(),
                'fee' => AdmissionQuery::where('payment_status', 'fee')->count(),
            ];

            // Get unique classes for filter dropdown
            $classList = AdmissionQuery::select('admission_sought_for_class')
                ->whereNotNull('admission_sought_for_class')
                ->distinct()
                ->pluck('admission_sought_for_class')
                ->merge(
                    AdmissionQuery::select('admission_class')
                        ->whereNotNull('admission_class')
                        ->distinct()
                        ->pluck('admission_class')
                )
                ->unique()
                ->sort()
                ->values();

            return view('admin.applications', compact('applications', 'classList', 'statusCounts'));

        } catch (\Exception $e) {
            Log::error('Applications list error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load applications. Please try again.');
        }
    }

    /**
     * View single application
     */
    public function viewApplication($id)
    {
        try {
            $application = AdmissionQuery::findOrFail($id);
            return view('admin.applications.view', compact('application'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Application not found - ID: ' . $id);
            return redirect()->route('admin.applications')
                ->with('error', 'Application not found. It may have been deleted or the ID is incorrect.');
        } catch (\Exception $e) {
            Log::error('View application error: ' . $e->getMessage());
            return back()->with('error', 'Error loading application details.');
        }
    }

    /**
     * Show send email modal
     */
    public function showSendEmail($id)
    {
        try {
            $application = AdmissionQuery::findOrFail($id);
            return view('admin.modals.send-email', compact('application'));
        } catch (\Exception $e) {
            Log::error('Show send email error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load email modal. Please try again.'
            ], 500);
        }
    }

    /**
     * Send application email to student
     */
    public function sendApplicationEmail(Request $request, $id)
    {
        try {
            $application = AdmissionQuery::findOrFail($id);

            // Validate request - Accept string values for checkboxes
            $validated = $request->validate([
                'subject' => 'nullable|string|max:255',
                'message' => 'nullable|string',
                'send_copy_to_admin' => 'nullable|string|in:1,0,true,false,on,off,yes,no',
                'include_remarks' => 'nullable|string|in:1,0,true,false,on,off,yes,no',
            ]);

            // Convert string values to proper booleans
            $sendCopyToAdmin = filter_var($request->input('send_copy_to_admin', false), FILTER_VALIDATE_BOOLEAN);
            $includeRemarks = filter_var($request->input('include_remarks', false), FILTER_VALIDATE_BOOLEAN);

            // Prepare PDF Data (Matches AdmissionController@downloadPDF for consistency)
            $logoPaths = [public_path('images/logo1.png'), public_path('images/logow.png')];
            $logoPath = null;
            foreach ($logoPaths as $p) {
                if (file_exists($p)) {
                    $logoPath = $p;
                    break;
                }
            }
            $logoBase64 = null;
            if ($logoPath) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }

            $currentPhotoPath = $application->student_photo_path ?? $application->passport_photo_path;
            $studentPhotoBase64 = null;
            if ($currentPhotoPath && Storage::disk('public')->exists($currentPhotoPath)) {
                $photoData = Storage::disk('public')->get($currentPhotoPath);
                $studentPhotoBase64 = 'data:image/jpeg;base64,' . base64_encode($photoData);

                // Better mime detection
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->buffer($photoData);
                $studentPhotoBase64 = 'data:' . $mime . ';base64,' . base64_encode($photoData);
            }

            $pdfData = [
                'admission' => $application,
                'logo_base64' => $logoBase64,
                'student_photo_base64' => $studentPhotoBase64,
                'current_date' => now()->format('d/m/Y'),
            ];

            // Generate PDF
            $pdf = PDF::loadView('admission.pdf', $pdfData);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', false);

            $pdfPath = 'applications/application-' . $application->id . '-' . time() . '.pdf';

            // Ensure directory exists
            if (!Storage::exists('applications')) {
                Storage::makeDirectory('applications');
            }

            Storage::put($pdfPath, $pdf->output());

            // Get current admin user
            $adminUser = auth()->user();

            // Prepare email data
            $emailData = [
                'application' => $application,
                'additional_message' => $validated['message'] ?? null,
                'subject' => $validated['subject'] ?? null,
                'admin_name' => $adminUser->name,
                'admin_email' => $adminUser->email,
                'include_remarks' => $includeRemarks,
            ];

            // Send email to student
            Mail::to($application->email)
                ->send(new StudentApplicationStatusMail($application, $pdfPath, $emailData));

            // Send copy to admin if requested
            if ($sendCopyToAdmin) {
                Mail::to($adminUser->email)
                    ->send(new StudentApplicationStatusMail($application, $pdfPath, $emailData));
            }

            // Clean up temporary PDF
            Storage::delete($pdfPath);

            // Log activity
            ActivityLog::log('Email Sent', 'Sent application email to ' . $application->email, $application);
            Log::info('Application email sent', [
                'application_id' => $id,
                'student_email' => $application->email,
                'sent_by' => $adminUser->id,
                'admin_copy' => $sendCopyToAdmin,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully to ' . $application->email,
            ]);

        } catch (\Exception $e) {
            Log::error('Send application email error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again.'
            ], 500);
        }
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            // Use lowercase status values: pending, reviewed, accepted, rejected
            $validated = $request->validate([
                'status' => 'required|in:pending,reviewed,accepted,rejected',
                'payment_status' => 'nullable|in:unpaid,paid,partial,fee',
                'paid_amount' => 'nullable|numeric|min:0',
                'total_fee' => 'nullable|numeric|min:0',
                'remarks' => 'nullable|string|max:500',
                'document_enclosure' => 'nullable|array',
            ]);

            $application = AdmissionQuery::findOrFail($id);

            // Capture old state for comparison
            $oldEnclosures = $application->document_enclosure ?? [];
            $newEnclosures = $validated['document_enclosure'] ?? [];
            $oldPaymentStatus = $application->payment_status;
            $oldPaidAmount = $application->paid_amount;

            // Update status and remarks
            $statusChanged = $application->status !== $validated['status'];
            $application->status = $validated['status'];

            if (isset($validated['payment_status'])) {
                $application->payment_status = $validated['payment_status'];
                if ($validated['payment_status'] == 'paid') {
                    $application->paid_amount = $application->total_fee;
                    $application->balance_amount = 0;
                } elseif ($validated['payment_status'] == 'unpaid') {
                    $application->paid_amount = 0;
                    $application->balance_amount = $application->total_fee;
                }
            }

            if (isset($validated['paid_amount'])) {
                $application->paid_amount = $validated['paid_amount'];
            }
            if (isset($validated['total_fee'])) {
                $application->total_fee = $validated['total_fee'];
            }

            // Calculate balance if possible
            if ($application->total_fee !== null && $application->paid_amount !== null) {
                $application->balance_amount = $application->total_fee - $application->paid_amount;
            }

            $application->remarks = $validated['remarks'] ?? null;
            $application->document_enclosure = $newEnclosures;
            $application->status_updated_at = now();
            $application->save();

            // Log activity for status change
            if ($statusChanged) {
                ActivityLog::log('Status Updated', 'Updated application status to ' . $validated['status'], $application);
            }

            // Log activity for payment change
            if (isset($validated['payment_status']) && $oldPaymentStatus != $validated['payment_status']) {
                $desc = "Payment status updated to " . strtoupper($validated['payment_status']);
                if (isset($validated['paid_amount'])) {
                    $desc .= " (Amount: ₹" . number_format($validated['paid_amount']) . ")";
                }
                ActivityLog::log('Payment Updated', $desc, $application);
            } elseif (isset($validated['paid_amount']) && $oldPaidAmount != $validated['paid_amount']) {
                ActivityLog::log('Payment Updated', "Paid amount updated to ₹" . number_format($validated['paid_amount']), $application);
            }

            // Log activity for document enclosure change
            if ($oldEnclosures != $newEnclosures) {
                $changes = [];

                // Check for updates or additions in new enclosures
                foreach ($newEnclosures as $key => $data) {
                    $oldData = $oldEnclosures[$key] ?? [];
                    if ($data != $oldData) {
                        $readableKey = ucwords(str_replace('_', ' ', $key));
                        $status = isset($data['checked']) ? 'Checked' : 'Unchecked';
                        $remark = !empty($data['remark']) ? " (Remark: {$data['remark']})" : "";
                        $changes[] = "{$readableKey}: {$status}{$remark}";
                    }
                }

                // Check for removals (keys in old but not in new, meaning unchecked completely if utilizing standard form behavior)
                // However, since we structure data as [key => [checked => 1]], absence usually means unchecked.
                // The previous loop handles it IF the key exists in newEnclosures but without 'checked'.
                // If the key is entirely missing from newEnclosures, it means it was unchecked.

                foreach ($oldEnclosures as $key => $data) {
                    if (!isset($newEnclosures[$key])) {
                        // Item was completely removed/unchecked
                        $readableKey = ucwords(str_replace('_', ' ', $key));
                        $changes[] = "{$readableKey}: Unchecked";
                    }
                }

                $description = 'Document enclosures updated.';
                if (!empty($changes)) {
                    // Filter duplicates just in case
                    $changes = array_unique($changes);
                    $description .= ' Changes: ' . implode(', ', $changes);
                }

                ActivityLog::log('Documents Updated', $description, $application);
            }


            Log::info('Application updated', [
                'application_id' => $id,
                'new_status' => $validated['status'],
                'updated_by' => auth()->id(),
            ]);

            // Send WhatsApp Notification
            try {
                $whatsapp = new \App\Services\WhatsAppService();
                $whatsapp->sendStatusUpdate(
                    $application->contact_number,
                    $validated['status'],
                    $application->full_name
                );
            } catch (\Exception $e) {
                Log::error('WhatsApp Notification Failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'status' => $validated['status'],
                'payment_status' => $application->payment_status,
                'status_text' => $application->status_label,
                'status_class' => $validated['status'] == 'accepted' ? 'success' :
                    ($validated['status'] == 'reviewed' ? 'info' :
                        ($validated['status'] == 'rejected' ? 'danger' : 'warning')),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status. Please try again.'
            ], 500);
        }
    }

    /**
     * Download application PDF
     */
    public function downloadApplication($id)
    {
        try {
            $application = AdmissionQuery::findOrFail($id);

            // If PDF exists in storage, download it
            if ($application->pdf_path && \Storage::exists($application->pdf_path)) {
                return \Storage::download($application->pdf_path);
            }

            // Otherwise, redirect to public download route
            return redirect()->route('admission.download', $id);

        } catch (\Exception $e) {
            Log::error('Download application error: ' . $e->getMessage());
            return back()->with('error', 'Failed to download application.');
        }
    }

    /**
     * Bulk update applications status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:admission_queries,id',
                'status' => 'nullable|in:pending,reviewed,accepted,rejected',
                'payment_status' => 'nullable|in:unpaid,paid,partial,fee',
            ]);

            $updateData = [];
            if ($request->filled('status')) {
                $updateData['status'] = $validated['status'];
            }
            if ($request->filled('payment_status')) {
                $updateData['payment_status'] = $validated['payment_status'];
            }

            if (empty($updateData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No update parameters provided.'
                ], 422);
            }

            $updated = AdmissionQuery::whereIn('id', $validated['ids'])
                ->update($updateData);

            Log::info('Bulk update', [
                'count' => $updated,
                'data' => $updateData,
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => $updated . ' application(s) updated successfully!',
                'count' => $updated,
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update applications. Please try again.'
            ], 500);
        }
    }

    /**
     * Show advanced statistics and reports page
     */
    public function statistics()
    {
        $stats = [
            'total' => AdmissionQuery::count(),
            'pending' => AdmissionQuery::where('status', 'pending')->count(),
            'accepted' => AdmissionQuery::where('status', 'accepted')->count(),
            'rejected' => AdmissionQuery::where('status', 'rejected')->count(),
        ];

        $genderStats = AdmissionQuery::select('gender', DB::raw('count(*) as count'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get();

        $formTypeStats = AdmissionQuery::select('form_type', DB::raw('count(*) as count'))
            ->groupBy('form_type')
            ->get();

        $streamStats = AdmissionQuery::where('form_type', 'form3')
            ->select('stream', DB::raw('count(*) as count'))
            ->whereNotNull('stream')
            ->groupBy('stream')
            ->get();

        $religionStats = AdmissionQuery::select('religion', DB::raw('count(*) as count'))
            ->whereNotNull('religion')
            ->groupBy('religion')
            ->get();

        $paymentStats = AdmissionQuery::select('payment_status', DB::raw('count(*) as count'))
            ->groupBy('payment_status')
            ->get()
            ->map(function ($item) {
                if (!$item->payment_status)
                    $item->payment_status = 'unpaid';
                return $item;
            });

        // 1. Class-wise distribution
        $classStats = AdmissionQuery::select(
            DB::raw('COALESCE(NULLIF(CASE WHEN form_type = "form1" THEN admission_sought_for_class ELSE admission_class END, ""), "Unspecified") as class_name'),
            DB::raw('count(*) as count')
        )
            ->groupBy('class_name')
            ->orderBy('count', 'desc')
            ->get();

        // 2. Class-wise gender distribution
        $classGenderRaw = AdmissionQuery::select(
            DB::raw('COALESCE(NULLIF(CASE WHEN form_type = "form1" THEN admission_sought_for_class ELSE admission_class END, ""), "Unspecified") as class_name'),
            DB::raw('COALESCE(NULLIF(gender, ""), "Other") as gender'),
            DB::raw('count(*) as count')
        )
            ->groupBy('class_name', 'gender')
            ->orderBy('class_name')
            ->get();

        $classGenderData = [];
        foreach ($classStats as $clsItem) {
            $cls = $clsItem->class_name;
            $male = $classGenderRaw->where('class_name', $cls)->filter(fn($item) => strtoupper(trim($item->gender)) === 'MALE')->sum('count');
            $female = $classGenderRaw->where('class_name', $cls)->filter(fn($item) => strtoupper(trim($item->gender)) === 'FEMALE')->sum('count');
            $other = $classGenderRaw->where('class_name', $cls)->reject(fn($item) => in_array(strtoupper(trim($item->gender)), ['MALE', 'FEMALE']))->sum('count');
            $total = $male + $female + $other;
            $classGenderData[] = [
                'class_name' => $cls,
                'male' => $male,
                'female' => $female,
                'other' => $other,
                'total' => $total,
            ];
        }

        // 3. Form Type Gender distribution
        $formTypeGenderRaw = AdmissionQuery::select(
            'form_type',
            DB::raw('COALESCE(NULLIF(gender, ""), "Other") as gender'),
            DB::raw('count(*) as count')
        )
            ->groupBy('form_type', 'gender')
            ->orderBy('form_type')
            ->get();

        $formTypes = [
            'form1' => 'Primary (Form 1)',
            'form2' => 'Secondary (Form 2)',
            'form3' => 'HSC (Form 3)'
        ];

        $formTypeGenderData = [];
        foreach ($formTypes as $fKey => $fLabel) {
            $male = $formTypeGenderRaw->where('form_type', $fKey)->filter(fn($item) => strtoupper(trim($item->gender)) === 'MALE')->sum('count');
            $female = $formTypeGenderRaw->where('form_type', $fKey)->filter(fn($item) => strtoupper(trim($item->gender)) === 'FEMALE')->sum('count');
            $other = $formTypeGenderRaw->where('form_type', $fKey)->reject(fn($item) => in_array(strtoupper(trim($item->gender)), ['MALE', 'FEMALE']))->sum('count');
            $total = $male + $female + $other;
            $formTypeGenderData[$fKey] = [
                'key' => $fKey,
                'label' => $fLabel,
                'male' => $male,
                'female' => $female,
                'other' => $other,
                'total' => $total,
            ];
        }

        return view('admin.statistics', compact(
            'stats',
            'genderStats',
            'formTypeStats',
            'streamStats',
            'religionStats',
            'paymentStats',
            'classStats',
            'classGenderData',
            'formTypeGenderData'
        ));
    }

    /**
     * Get application statistics for charts (API)
     */
    public function getStatistics(Request $request)
    {
        try {
            $range = $request->input('range', 'monthly');

            switch ($range) {
                case 'weekly':
                    $data = AdmissionQuery::select(
                        DB::raw('WEEK(created_at) as period'),
                        DB::raw('COUNT(*) as count')
                    )
                        ->whereYear('created_at', date('Y'))
                        ->groupBy('period')
                        ->orderBy('period')
                        ->get();
                    break;

                case 'daily':
                    $data = AdmissionQuery::select(
                        DB::raw('DATE(created_at) as period'),
                        DB::raw('COUNT(*) as count')
                    )
                        ->whereDate('created_at', '>=', now()->subDays(30))
                        ->groupBy('period')
                        ->orderBy('period')
                        ->get();
                    break;

                default: // monthly
                    $data = AdmissionQuery::select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'),
                        DB::raw('COUNT(*) as count')
                    )
                        ->whereYear('created_at', '>=', date('Y') - 1)
                        ->groupBy('period')
                        ->orderBy('period')
                        ->get();
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'range' => $range,
            ]);

        } catch (\Exception $e) {
            Log::error('Get statistics error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics. Please try again.'
            ], 500);
        }
    }

    /**
     * Update application details and documents
     */
    public function updateApplication(Request $request, $id)
    {
        try {
            $application = AdmissionQuery::findOrFail($id);

            // Validation
            $rules = [
                'first_name' => 'nullable|string|max:100',
                'surname' => 'nullable|string|max:100',
                'student_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:150',
                'mobile_1' => 'nullable|string|max:20',
                'mobile_2' => 'nullable|string|max:20',
                'phone_no1' => 'nullable|string|max:20',
                'phone_no2' => 'nullable|string|max:20',
                'admission_sought_for_class' => 'nullable|string|max:50',
                'admission_class' => 'nullable|string|max:50',
                'date_of_birth' => 'nullable|date',
                'date_of_birth_words' => 'nullable|string|max:255',
                'gender' => 'nullable|string|max:20',
                'nationality' => 'nullable|string|max:100',
                'religion' => 'nullable|string|max:100',
                'caste' => 'nullable|string|max:100',
                'sub_caste' => 'nullable|string|max:100',
                'mother_tongue' => 'nullable|string|max:100',
                'blood_group' => 'nullable|string|max:10',
                'place_of_birth' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'local_address' => 'nullable|string',
                'present_address' => 'nullable|string',
                'permanent_address' => 'nullable|string',
                'pin_code' => 'nullable|string|max:10',
                'father_name' => 'nullable|string|max:255',
                'father_full_name' => 'nullable|string|max:255',
                'father_qualification' => 'nullable|string|max:255',
                'father_occupation' => 'nullable|string|max:255',
                'father_designation' => 'nullable|string|max:255',
                'mother_name' => 'nullable|string|max:255',
                'mother_full_name' => 'nullable|string|max:255',
                'mother_qualification' => 'nullable|string|max:255',
                'mother_occupation' => 'nullable|string|max:255',
                'mother_designation' => 'nullable|string|max:255',
                'parents_guardian_full_name' => 'nullable|string|max:255',
                'aadhar_no' => 'nullable|string|max:20',
                'apaar_id' => 'nullable|string|max:50',
                'pen_no' => 'nullable|string|max:50',
                'adharshila_number' => 'nullable|string|max:50',
                'id_type' => 'nullable|string|max:50',
                'id_number' => 'nullable|string|max:100',
                'last_school_attended' => 'nullable|string|max:255',
                'last_school_address' => 'nullable|string',
                'previous_school_name' => 'nullable|string|max:255',
                'previous_class' => 'nullable|string|max:50',
                'studying_in_std' => 'nullable|string|max:50',
                'medium_of_instruction' => 'nullable|string|max:100',
                'stream' => 'nullable|string|max:50',
                'subject_group' => 'nullable|string|max:100',
                'is_physically_unfit' => 'nullable|boolean',
                'academic_year' => 'nullable|string|max:50',
                'additional_info' => 'nullable|string',
                'admin_notes' => 'nullable|string',
                'remarks' => 'nullable|string',
                'application_number' => 'nullable|string|max:50',
                'status' => 'nullable|in:pending,reviewed,accepted,rejected',
                'payment_status' => 'nullable|in:unpaid,paid,partial,fee',
                'total_fee' => 'nullable|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0'
            ];

            // Add dynamic validation for files
            $photoFields = [
                'passport_photo',
                'student_photo',
                'father_photo',
                'mother_photo',
            ];

            $docFields = [
                'birth_certificate',
                'caste_certificate',
                'leaving_certificate',
                'marksheet',
            ];

            $fileFields = array_merge($photoFields, $docFields);

            foreach ($photoFields as $field) {
                $rules[$field] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';
            }

            foreach ($docFields as $field) {
                $rules[$field] = 'nullable|file|mimes:jpeg,png,jpg,pdf|max:4096';
            }

            $validated = $request->validate($rules);

            $oldPaymentStatus = $application->payment_status;
            $oldPaidAmount = $application->paid_amount;
            $oldStatus = $application->status;

            // Handle text fields - Merge all allowed fields
            $updateData = $request->only(array_keys(array_filter($rules, fn($k) => !in_array($k, $fileFields), ARRAY_FILTER_USE_KEY)));

            // Ensure email is properly set to null if empty
            if (array_key_exists('email', $updateData)) {
                $emailVal = trim((string) $request->input('email', ''));
                $updateData['email'] = $emailVal !== '' ? $emailVal : null;
            }

            // Handle boolean
            $updateData['is_physically_unfit'] = $request->has('is_physically_unfit');

            // Sync dual name fields for consistency
            if ($request->filled('first_name')) {
                $surname = $request->input('surname', $application->surname ?? '');
                $updateData['student_name'] = trim($request->first_name . ' ' . $surname);
            } elseif ($request->filled('student_name')) {
                $parts = explode(' ', trim($request->student_name), 2);
                $updateData['first_name'] = $parts[0];
                if (isset($parts[1]) && !$request->filled('surname')) {
                    $updateData['surname'] = $parts[1];
                }
            }

            // Sync dual mobile fields for consistency
            if ($request->filled('mobile_1')) {
                $updateData['phone_no1'] = $request->mobile_1;
            } elseif ($request->filled('phone_no1')) {
                $updateData['mobile_1'] = $request->phone_no1;
            }
            if ($request->filled('mobile_2')) {
                $updateData['phone_no2'] = $request->mobile_2;
            } elseif ($request->filled('phone_no2')) {
                $updateData['mobile_2'] = $request->phone_no2;
            }

            if ($request->filled('father_full_name')) {
                $updateData['father_name'] = $request->father_full_name;
            } elseif ($request->filled('father_name')) {
                $updateData['father_full_name'] = $request->father_name;
            }
            if ($request->filled('mother_full_name')) {
                $updateData['mother_name'] = $request->mother_full_name;
            } elseif ($request->filled('mother_name')) {
                $updateData['mother_full_name'] = $request->mother_name;
            }
            if ($request->filled('admission_sought_for_class')) {
                $updateData['admission_class'] = $request->admission_sought_for_class;
            } elseif ($request->filled('admission_class')) {
                $updateData['admission_sought_for_class'] = $request->admission_class;
            }

            // Handle files
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $pathField = $field . '_path';

                    // Store new file with MIME-verified extension
                    $extension = $file->extension() ?: 'jpg';
                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    $path = $file->storeAs('admission-documents/' . date('Y/m'), $fileName, 'public');

                    // Delete old file if exists
                    if ($application->$pathField && \Storage::disk('public')->exists($application->$pathField)) {
                        \Storage::disk('public')->delete($application->$pathField);
                    }

                    $updateData[$pathField] = $path;
                }
            }

            $application->update($updateData);

            // Log key changes
            if ($oldStatus != ($updateData['status'] ?? $oldStatus)) {
                ActivityLog::log('Status Updated', 'Bulk update changed status to ' . $updateData['status'], $application);
            }

            if (($updateData['payment_status'] ?? $oldPaymentStatus) != $oldPaymentStatus) {
                ActivityLog::log('Payment Updated', 'Bulk update changed payment status to ' . strtoupper($updateData['payment_status']), $application);
            } elseif (($updateData['paid_amount'] ?? $oldPaidAmount) != $oldPaidAmount) {
                ActivityLog::log('Payment Updated', 'Bulk update updated paid amount to ₹' . number_format($updateData['paid_amount']), $application);
            }

            return back()->with('success', 'Application updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = implode(', ', $e->validator->errors()->all());
            Log::warning('Application update validation failed: ' . $errorMsg);
            return back()->withErrors($e->validator)->withInput()->with('error', 'Validation failed: ' . $errorMsg);
        } catch (\Exception $e) {
            Log::error('Update application error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update application. Please try again.');
        }
    }

    /**
     * Delete application
     */
    public function deleteApplication($id)
    {
        try {
            $application = AdmissionQuery::findOrFail($id);
            $application->delete();

            Log::info('Application deleted', [
                'application_id' => $id,
                'deleted_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application deleted successfully!',
            ]);

        } catch (\Exception $e) {
            Log::error('Delete application error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete application. Please try again.'
            ], 500);
        }
    }

    /**
     * Serve uploaded files securely
     */
    public function serveFile($path)
    {
        try {
            // Decode the path
            $filePath = base64_decode($path);

            // Path traversal defense
            if (!$filePath || str_contains($filePath, '..') || str_contains($filePath, '\\') || str_contains($filePath, "\0")) {
                abort(403, 'Invalid file path');
            }

            // Use the public disk since files are stored there
            $disk = Storage::disk('public');
            $fullPath = $disk->path($filePath);
            $basePath = realpath($disk->path(''));
            $realFullPath = realpath($fullPath);

            // Verify the resolved file is strictly inside the public storage root
            if (!$realFullPath || !$basePath || !str_starts_with($realFullPath, $basePath . DIRECTORY_SEPARATOR) || !file_exists($realFullPath) || is_dir($realFullPath)) {
                Log::warning('File not found or outside storage root: ' . $filePath);
                abort(404, 'File not found');
            }

            // Get file content
            $file = file_get_contents($realFullPath);
            $fileName = basename($realFullPath);

            // Detect MIME type safely
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($file) ?: 'application/octet-stream';

            // Return file response with security headers
            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"')
                ->header('Cache-Control', 'private, no-cache, no-store, must-revalidate')
                ->header('X-Content-Type-Options', 'nosniff');

        } catch (\Exception $e) {
            Log::error('Serve file error: ' . $e->getMessage());
            abort(404, 'File not found');
        }
    }

    /**
     * Export Activity Logs
     */
    public function exportActivityLog()
    {
        try {
            $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $columns = ['ID', 'Date', 'User', 'Activity', 'Description', 'IP Address', 'User Agent'];

            $callback = function () {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Date', 'User', 'Activity', 'Description', 'IP Address', 'User Agent']);

                $sanitizeCsv = function ($val) {
                    if (is_string($val) && strlen($val) > 0 && in_array($val[0], ['=', '+', '-', '@', "\t", "\r"])) {
                        return "'" . $val;
                    }
                    return $val;
                };

                ActivityLog::with('user')->chunk(100, function ($logs) use ($file, $sanitizeCsv) {
                    foreach ($logs as $log) {
                        $logRow = [
                            $log->id,
                            $log->created_at->format('Y-m-d H:i:s'),
                            $log->user ? $log->user->name : 'System',
                            $log->activity,
                            $log->description,
                            $log->ip_address,
                            $log->user_agent
                        ];
                        fputcsv($file, array_map($sanitizeCsv, $logRow));
                    }
                });
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Activity log export error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export activity logs.');
        }
    }

    /**
     * User Management Methods
     */

    public function indexUsers()
    {
        $users = User::paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,user', // 'user' is Office Staff
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        ActivityLog::log('Created User', "Created new user {$user->name} with role {$user->role}", $user);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Prevent demoting the last active administrator
        if ($user->isAdmin() && $validated['role'] !== 'admin') {
            $otherAdmins = User::where('role', 'admin')->where('id', '!=', $user->id)->whereNull('banned_at')->count();
            if ($otherAdmins === 0) {
                return back()->with('error', 'Cannot demote the last active administrator.');
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->phone = $validated['phone'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        ActivityLog::log('Updated User', "Updated user {$user->name}", $user);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting the last active administrator
        if ($user->isAdmin()) {
            $otherAdmins = User::where('role', 'admin')->where('id', '!=', $user->id)->whereNull('banned_at')->count();
            if ($otherAdmins === 0) {
                return back()->with('error', 'Cannot delete the last active administrator.');
            }
        }

        $user->delete();
        ActivityLog::log('Deleted User', "Deleted user {$user->name} ({$user->email})", null);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show activity logs page
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by activity type
        if ($request->filled('activity')) {
            $query->where('activity', 'like', '%' . $request->activity . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->paginate(25);
        $users = User::orderBy('name')->get();

        return view('admin.activity-logs.index', compact('activities', 'users'));
    }

    /**
     * Ban a user
     */
    public function banUser(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        if ($user->isAdmin()) {
            if (!auth()->user()->isAdmin()) {
                return back()->with('error', 'You cannot ban an administrator.');
            }
            $otherAdmins = User::where('role', 'admin')->where('id', '!=', $user->id)->whereNull('banned_at')->count();
            if ($otherAdmins === 0) {
                return back()->with('error', 'Cannot ban the last active administrator.');
            }
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $user->banned_at = now();
        $user->banned_reason = $validated['reason'];
        $user->save();

        ActivityLog::log('Banned User', "Banned user {$user->name}. Reason: {$validated['reason']}", $user);

        return back()->with('success', "User {$user->name} has been banned successfully.");
    }

    /**
     * Unban a user
     */
    public function unbanUser(User $user)
    {
        $user->banned_at = null;
        $user->banned_reason = null;
        $user->save();

        ActivityLog::log('Unbanned User', "Unbanned user {$user->name}", $user);

        return back()->with('success', "User {$user->name} has been unbanned successfully.");
    }

    /**
     * Reset application download status
     */
    public function resetDownload($id)
    {
        try {
            $application = AdmissionQuery::findOrFail($id);
            $application->update(['downloaded_at' => null]);

            ActivityLog::log('Download Reset', 'Reset download status for application #' . $id, $application);

            return response()->json([
                'success' => true,
                'message' => 'Download status reset successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Reset download error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset download status.'
            ], 500);
        }
    }

    /**
     * Dedicated applications export endpoint
     */
    public function exportApplications(Request $request)
    {
        $request->merge(['export' => 'true']);
        return $this->applications($request);
    }
}
