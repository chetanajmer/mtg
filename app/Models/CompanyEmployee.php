<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A company's gift recipient (an employee who does not log in). */
class CompanyEmployee extends Model
{
    protected $guarded = ['id', 'account_id'];

    protected $casts = ['birthday' => 'date', 'joined_on' => 'date'];

    /** Birthdays and work anniversaries within $days of $from, each as [type, date, years], soonest first. */
    public function occasions(\Carbon\Carbon $from, $days)
    {
        $found = [];
        foreach (['birthday' => 'Birthday', 'joined_on' => 'Work anniversary'] as $field => $label) {
            if (!$this->$field) {
                continue;
            }
            $date = $this->$field->copy()->year($from->year);
            if ($date->lt($from->copy()->startOfDay())) {
                $date->addYear();
            }
            if ($date->diffInDays($from->copy()->startOfDay()) <= $days) {
                $found[] = ['type' => $label, 'date' => $date, 'years' => $field === 'joined_on' ? $date->year - $this->$field->year : null];
            }
        }
        usort($found, function ($a, $b) { return $a['date'] <=> $b['date']; });
        return $found;
    }
}
