<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $fillable = [
        'name',
        'code',
        'country',
    ];

    /**
     * Get companies in this state
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    /**
     * Get reports available in this state with pricing
     */
    public function getAvailableReports()
    {
        return Report::on('companies_house_mx')
            ->join('report_state', 'reports.id', '=', 'report_state.report_id')
            ->where('report_state.state_id', $this->id)
            ->select('reports.*', 'report_state.amount as price')
            ->get();
    }
}
