<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'registration_number',
        'address',
        'brand_name',
        'former_names',
        'state_id', // Only for MX companies
        'country',
    ];

    protected $casts = [
        'state_id' => 'integer',
    ];

    /**
     * Get the state that the company belongs to (MX only)
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get all available reports for the company based on country
     */
    public function getAvailableReports()
    {
        if ($this->country === 'SG') {
            // SG: All reports are available (from any database, but typically from SG)
            // Since SG companies have all reports available, we'll get them from the SG database
            $sgConnection = env('DB_SG_CONNECTION_NAME', 'companies_house_sg');
            return \DB::connection($sgConnection)
                ->table('reports')
                ->get()
                ->map(function ($report) use ($sgConnection) {
                    $reportObj = new Report();
                    $reportObj->fill((array) $report);
                    $reportObj->id = $report->id; // Explicitly set the ID
                    $reportObj->setConnection($sgConnection);
                    return $reportObj;
                });
        } elseif ($this->country === 'MX') {
            // MX: Reports based on company's state from report_state table
            $mxConnection = env('DB_MX_CONNECTION_NAME', 'companies_house_mx');
            return \DB::connection($mxConnection)
                ->table('reports')
                ->join('report_state', 'reports.id', '=', 'report_state.report_id')
                ->where('report_state.state_id', $this->state_id)
                ->select('reports.*', 'report_state.amount as price')
                ->get()
                ->map(function ($report) use ($mxConnection) {
                    $reportObj = new Report();
                    $reportObj->fill((array) $report);
                    $reportObj->id = $report->id; // Explicitly set the ID
                    $reportObj->setConnection($mxConnection);
                    return $reportObj;
                });
        }

        return collect();
    }

    /**
     * Get the price for a specific report
     */
    public function getReportPrice($reportId)
    {
        if ($this->country === 'SG') {
            // SG: Price from reports table
            $sgConnection = env('DB_SG_CONNECTION_NAME', 'companies_house_sg');
            $report = \DB::connection($sgConnection)
                ->table('reports')
                ->where('id', $reportId)
                ->first();
            return $report ? $report->price : 0;
        } elseif ($this->country === 'MX') {
            // MX: Price from report_state table
            $mxConnection = env('DB_MX_CONNECTION_NAME', 'companies_house_mx');
            $reportState = \DB::connection($mxConnection)
                ->table('report_state')
                ->where('state_id', $this->state_id)
                ->where('report_id', $reportId)
                ->first();

            return $reportState ? $reportState->amount : 0;
        }

        return 0;
    }

    /**
     * Scope to search companies by name across databases
     */
    public function scopeSearchByName($query, $searchTerm)
    {
        return $query->where('name', 'like', "%{$searchTerm}%");
    }
}
