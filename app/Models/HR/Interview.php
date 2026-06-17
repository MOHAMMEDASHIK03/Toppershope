<?php

namespace App\Models\HR;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'job_application_id',
        'interviewer_id',
        'scheduled_at',
        'location_or_link',
        'feedback',
        'status',
        'rating',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function interviewer()
    {
        return $this->belongsTo(HrUser::class, 'interviewer_id');
    }

    /** @deprecated Use scheduled_at — kept for older blade references */
    protected function interviewDate(): Attribute
    {
        return Attribute::get(fn () => $this->scheduled_at);
    }

    /** @deprecated Use location_or_link */
    protected function meetingLink(): Attribute
    {
        return Attribute::get(fn () => $this->location_or_link);
    }

    /** @deprecated Use feedback */
    protected function notes(): Attribute
    {
        return Attribute::get(fn () => $this->feedback);
    }

    public function displaysTime(): bool
    {
        if (! $this->scheduled_at) {
            return false;
        }

        return $this->scheduled_at->format('H:i') !== '00:00';
    }

    public function formattedSchedule(): string
    {
        if (! $this->scheduled_at) {
            return '—';
        }

        $date = $this->scheduled_at->format('M d, Y');

        return $this->displaysTime()
            ? $date.' at '.$this->scheduled_at->format('g:i A')
            : $date;
    }

    public static function composeScheduledAt(string $date, ?string $time = null): Carbon
    {
        $parsed = Carbon::parse($date);

        if ($time) {
            [$hour, $minute] = array_pad(explode(':', $time), 2, 0);

            return $parsed->setTime((int) $hour, (int) $minute, 0);
        }

        return $parsed->startOfDay();
    }
}
