<?php
// app/Models/AdmissionQuery.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionQuery extends Model
{
    use HasFactory;

    protected $table = 'admission_queries';

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Automatic balance calculation
            if ($model->isDirty(['total_fee', 'paid_amount'])) {
                $model->balance_amount = ($model->total_fee ?? 0) - ($model->paid_amount ?? 0);
            }

            foreach ($model->getAttributes() as $key => $value) {
                // Only process strings
                if (is_string($value)) {
                    // Skip specific fields that should maintain casing
                    $excludedFields = [
                        'email',
                        'form_type',
                        'status',
                        'payment_status',
                        'submitted_ip',
                        'submitted_user_agent',
                        'document_enclosure',
                        'selected_subjects',
                        'two_factor_backup_codes',
                    ];

                    // Don't uppercase paths or password
                    if (
                        !in_array($key, $excludedFields) &&
                        !str_ends_with($key, '_path') &&
                        !str_ends_with($key, '_at') &&
                        !($key === 'password')
                    ) {
                        $model->attributes[$key] = mb_strtoupper($value);
                    }
                }
            }
        });
    }

    protected $fillable = [
        // Form Type
        'form_type',

        // Application Info
        'application_number',
        'academic_year',

        // Student Information (Common)
        'surname',
        'first_name',
        'student_name',
        'gender',
        'dob',
        'date_of_birth',
        'date_of_birth_words',
        'place_of_birth',
        'nationality',
        'religion',
        'caste',
        'sub_caste',
        'mother_tongue',
        'blood_group',

        // Parent Information
        'father_name',
        'father_full_name',
        'father_occupation',
        'father_designation',
        'father_qualification',
        'mother_name',
        'mother_full_name',
        'mother_occupation',
        'mother_designation',
        'mother_qualification',
        'parents_guardian_full_name',

        // Contact Information
        'email',
        'mobile_1',
        'mobile_2',
        'phone_no1',
        'phone_no2',

        // Address Information
        'address',
        'local_address',
        'present_address',
        'permanent_address',
        'pin_code',

        // Educational Information
        'admission_sought_for_class',
        'admission_class',
        'previous_school_name',
        'previous_class',
        'last_school_attended',
        'last_school_address',
        'studying_in_std',
        'medium_of_instruction',

        // ID Details (Form 2 & 3)
        'aadhar_no',
        'apaar_id',
        'pen_no',
        'adharshila_number',
        'id_type',
        'id_number',

        // Form 3 Specific
        'stream',
        'subject_group',
        'selected_subjects',

        // File Paths
        'passport_photo_path',
        'student_photo_path',
        'father_photo_path',
        'mother_photo_path',
        'birth_certificate_path',
        'caste_certificate_path',
        'leaving_certificate_path',
        'marksheet_path',

        // Declarations
        'is_physically_unfit',
        'agreed_to_rules',
        'agree_declaration',

        // Additional Information
        'additional_info',

        // Status tracking
        'status',
        'payment_status',
        'remarks',
        'processed_at',
        'admin_notes',
        'submitted_ip',
        'submitted_user_agent',
        'status_updated_at',
        'document_enclosure',
        'downloaded_at',
        'total_fee',
        'paid_amount',
        'balance_amount',
    ];

    protected $casts = [
        'dob' => 'date',
        'date_of_birth' => 'date',
        'is_physically_unfit' => 'boolean',
        'agreed_to_rules' => 'boolean',
        'agree_declaration' => 'boolean',
        'selected_subjects' => 'array',
        'document_enclosure' => 'array',
        'processed_at' => 'datetime',
        'status_updated_at' => 'datetime',
        'downloaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_fee' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => 'pending',
        'is_physically_unfit' => false,
        'agreed_to_rules' => false,
        'agree_declaration' => false,
        'form_type' => 'form1',
    ];

    // Helper method to get table columns
    public static function getTableColumns()
    {
        return \DB::getSchemaBuilder()->getColumnListing((new static)->getTable());
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        if (!empty($this->first_name) || !empty($this->surname)) {
            return trim($this->first_name . ' ' . $this->surname);
        }
        return $this->student_name;
    }

    public function getFormTypeNameAttribute()
    {
        $formNames = [
            'form1' => 'Pre-primary to Class 1',
            'form2' => 'Class 3 to 10',
            'form3' => 'Higher Secondary (11th-12th)'
        ];

        return $formNames[$this->form_type] ?? 'Unknown';
    }

    public function getContactNumberAttribute()
    {
        return $this->phone_no1 ?? $this->mobile_1;
    }

    public function getAddressAttribute()
    {
        if ($this->form_type === 'form1') {
            return $this->local_address ?? $this->attributes['address'] ?? '';
        } else {
            return $this->present_address ?? $this->permanent_address ?? '';
        }
    }

    public function getDesiredClassAttribute()
    {
        return $this->admission_sought_for_class ?? $this->admission_class ?? 'N/A';
    }

    public function getSubmittedAtAttribute()
    {
        return $this->created_at;
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return \Carbon\Carbon::parse($this->date_of_birth)->age;
        }
        return 'N/A';
    }

    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => 'Pending',
            'reviewed' => 'Application Accepted',
            'accepted' => 'Admitted',
            'rejected' => 'Rejected'
        ];

        return $statusLabels[$this->status] ?? ucfirst($this->status);
    }

    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'subject')->latest();
    }
}
