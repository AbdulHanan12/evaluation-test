<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $company->name }} - Company Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('companies.search') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-900">Company Details</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                        </svg>
                        Cart
                        @if(session('cart'))
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Company Information -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $company->name }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Company registration and details
                        </p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $company->country === 'SG' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $company->country }}
                        </span>
                        @if($company->country === 'MX' && isset($company->state_name))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $company->state_name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                                         @if($company->country === 'SG')
                     <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                         <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                         <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                             {{ $company->registration_number ?? 'N/A' }}
                         </dd>
                     </div>
                     @else
                     <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                         <dt class="text-sm font-medium text-gray-500">Brand Name</dt>
                         <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                             {{ $company->brand_name ?? 'N/A' }}
                         </dd>
                     </div>
                     @endif
                                         <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                         <dt class="text-sm font-medium text-gray-500">Slug</dt>
                         <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                             {{ $company->slug ?? 'N/A' }}
                         </dd>
                     </div>
                     @if($company->address)
                     <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                         <dt class="text-sm font-medium text-gray-500">Address</dt>
                         <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                             {{ $company->address }}
                         </dd>
                     </div>
                     @endif
                    @if($company->country === 'MX' && isset($company->state_name))
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">State</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $company->state_name }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Available Reports -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Available Reports</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    @if($company->country === 'SG')
                        All reports are available for Singapore companies
                    @else
                        Reports available based on company's state in Mexico
                    @endif
                </p>
            </div>

            @if($reports->count() > 0)
                <div class="border-t border-gray-200">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-500">
                            <div class="col-span-6">Report Name</div>
                            <div class="col-span-2 text-right">Price</div>
                        </div>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        @foreach($reports as $report)
                            <li class="px-4 py-4">
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $report->name }}</div>
                                                @if($report->description)
                                                    <div class="text-sm text-gray-500">{{ Str::limit($report->description, 100) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-2 text-right">
                                        <div class="text-sm font-medium text-gray-900">
                                            ${{ number_format($report->price ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <form method="POST" action="{{ route('cart.add') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                                        <input type="hidden" name="report_id" value="{{ $report->id }}">
                                        <input type="hidden" name="report_name" value="{{ $report->name }}">
                                        <input type="hidden" name="country" value="{{ $company->country }}">
                                        <input type="hidden" name="company_name" value="{{ $company->name }}">
                                        <input type="hidden" name="price" value="{{ $report->price ?? 0 }}">
                                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m6 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                            </svg>
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="border-t border-gray-200 px-4 py-6">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No reports available</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if($company->country === 'MX')
                                No reports are currently available for companies in this state.
                            @else
                                No reports are currently available for this company.
                            @endif
                        </p>
                    </div>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                {{ session('success') }}
            </div>
        @endif
    </div>
</body>
</html>
