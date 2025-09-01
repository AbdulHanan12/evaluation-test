<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Report extends Model
{
    protected $fillable = [
        'id',
        'name',
        'description',
        'price', // For SG companies
        'category',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the price for a specific company (handles country-specific logic)
     */
    public function getPriceForCompany($company)
    {
        if ($company->country === 'SG') {
            return $this->price;
        } elseif ($company->country === 'MX') {
            // For MX, price comes from report_state table
            $mxConnection = env('DB_MX_CONNECTION_NAME', 'companies_house_mx');
            $reportState = \DB::connection($mxConnection)
                ->table('report_state')
                ->where('state_id', $company->state_id)
                ->where('report_id', $this->id)
                ->first();
            
            return $reportState ? $reportState->amount : 0;
        }

        return 0;
    }
}
