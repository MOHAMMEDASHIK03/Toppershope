<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'admin_audit_logs';

    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
