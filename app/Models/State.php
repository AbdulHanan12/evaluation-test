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
        $mxConnection = env('DB_MX_CONNECTION_NAME', 'companies_house_mx');
        return Report::on($mxConnection)
            ->join('report_state', 'reports.id', '=', 'report_state.report_id')
            ->where('report_state.state_id', $this->id)
            ->select('reports.*', 'report_state.amount as price')
            ->get();
    }
}
