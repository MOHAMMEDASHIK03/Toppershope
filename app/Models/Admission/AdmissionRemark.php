<?php

namespace App\Models\Admission;

use Illuminate\Database\Eloquent\Model;

class AdmissionRemark extends Model
{
    protected $table = 'admission_remarks';

    protected $fillable = ['contact_id', 'admission_user_id', 'note', 'called_at'];

    protected $casts = ['called_at' => 'datetime'];

    public function contact()
    {
        return $this->belongsTo(AdmissionContact::class, 'contact_id');
    }

    public function staff()
    {
        return $this->belongsTo(AdmissionUser::class, 'admission_user_id');
    }
}
