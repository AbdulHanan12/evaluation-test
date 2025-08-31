<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use Illuminate\Pipeline\Pipeline;

class CompanySearchController extends Controller
{
    /**
     * Show the search page
     */
    public function index()
    {
        return view('companies.search');
    }

    /**
     * Search companies across all databases
     */
    public function search(Request $request)
    {
        $searchTerm = trim($request->get('search', ''));

        if ($searchTerm === '') {
            return view('companies.search', ['companies' => collect()]);
        }

        $results = app(Pipeline::class)
            ->send(collect())
            ->through([
                function ($companies) use ($searchTerm) {
                    // Singapore DB
                    $sgCompanies = DB::connection('companies_house_sg')
                        ->table('companies')
                        ->where('name', 'like', "%{$searchTerm}%")
                        ->select('id', 'name', 'registration_number', 'address', 'slug')
                        ->addSelect(DB::raw("'SG' as country"))
                        ->addSelect(DB::raw("'companies_house_sg' as db_name"))
                        ->limit(50)
                        ->get()
                        ->map(function ($c) {
                            return [
                                'id' => $c->id,
                                'name' => $c->name,
                                'registration_number' => $c->registration_number,
                                'address' => $c->address,
                                'slug' => $c->slug,
                                'country' => $c->country,
                                'database' => $c->db_name,
                            ];
                        });

                    return $companies->merge($sgCompanies);
                },

                function ($companies) use ($searchTerm) {
                    // Mexico DB
                    $mxCompanies = DB::connection('companies_house_mx')
                        ->table('companies')
                        ->join('states', 'companies.state_id', '=', 'states.id')
                        ->where('companies.name', 'like', "%{$searchTerm}%")
                        ->select(
                            'companies.id',
                            'companies.name',
                            'companies.slug',
                            'companies.address',
                            'companies.brand_name',
                            'states.name as state_name'
                        )
                        ->addSelect(DB::raw("'MX' as country"))
                        ->addSelect(DB::raw("'companies_house_mx' as db_name"))
                        ->limit(50)
                        ->get()
                        ->map(function ($c) {
                            return [
                                'id' => $c->id,
                                'name' => $c->name,
                                'slug' => $c->slug,
                                'address' => $c->address,
                                'brand_name' => $c->brand_name,
                                'country' => $c->country,
                                'database' => $c->db_name,
                            ];
                        });

                    return $companies->merge($mxCompanies);
                },
            ])
            ->thenReturn();

        $companies = $results->sortBy('name')->values();

        return view('companies.search', [
            'companies'   => $companies,
            'searchTerm'  => $searchTerm,
        ]);
    }

    /**
     * Get company details for a specific company
     */
    public function show($country, $id)
    {
        $database = $this->getDatabaseFromCountry($country);
        $company = $this->getCompanyFromDatabase($database, $id);

        if (!$company) {
            abort(404, 'Company not found');
        }
        // dd($company);
        $reports = $company->getAvailableReports();

        return view('companies.show', [
            'company' => $company,
            'reports' => $reports
        ]);
    }

    /**
     * Get company from specific database
     */
    private function getCompanyFromDatabase($database, $id)
    {
        if ($database === 'companies_house_sg') {
            $companyData = DB::connection('companies_house_sg')
                ->table('companies')
                ->where('id', $id)
                ->first();

            if ($companyData) {
                $company = new Company();
                $company->fill((array) $companyData);
                $company->country = 'SG';
                $company->setConnection('companies_house_sg');
                return $company;
            }
        } elseif ($database === 'companies_house_mx') {
            $companyData = DB::connection('companies_house_mx')
                ->table('companies')
                ->join('states', 'companies.state_id', '=', 'states.id')
                ->where('companies.id', $id)
                ->select('companies.*', 'states.name as state_name')
                ->first();

            if ($companyData) {
                $company = new Company();
                $company->fill((array) $companyData);
                $company->country = 'MX';
                $company->state_id = $companyData->state_id;
                $company->setConnection('companies_house_mx');
                return $company;
            }
        }

        return null;
    }

    /**
     * Convert country code to database name
     */
    private function getDatabaseFromCountry($country)
    {
        switch (strtoupper($country)) {
            case 'SG':
                return 'companies_house_sg';
            case 'MX':
                return 'companies_house_mx';
            default:
                abort(404, 'Invalid country code');
        }
    }
}
