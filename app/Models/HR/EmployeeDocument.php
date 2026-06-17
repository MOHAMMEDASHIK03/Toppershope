<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $table = 'employee_documents';

    protected $fillable = [
        'employee_id',
        'document_name',
        'document_type',
        'file_path',
        'expiry_date',
        'is_verified',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_verified' => 'boolean',
    ];

    public static function categoryLabels(): array
    {
        return [
            'identity' => 'Identity proof',
            'contract' => 'Employment contract',
            'education' => 'Educational certificates',
            'experience' => 'Experience letters',
            'financial' => 'Financial / bank',
            'other' => 'Other',
        ];
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categoryLabels()[$this->document_type] ?? ucfirst(str_replace('_', ' ', $this->document_type ?? 'other'));
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
