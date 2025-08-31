<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Company Search - Centralized Company Database</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Company Search</h1>
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
        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow px-5 py-6 sm:px-6 mb-8">
            <form method="GET" action="{{ route('companies.search.results') }}" class="space-y-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        Search Companies
                    </label>
                    <div class="flex rounded-md shadow-sm">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ $searchTerm ?? '' }}"
                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter company name to search across Singapore and Mexico databases..."
                            required
                        >
                        <button
                            type="submit"
                            class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Search Results -->
        @if(isset($companies) && $companies->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($companies as $company)
                        <li>
                                                         <a href="{{ route('companies.show', ['country' => $company['country'], 'id' => $company['id']]) }}"
                               class="block hover:bg-gray-50 transition duration-150 ease-in-out">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-indigo-600 font-medium text-sm">
                                                        {{ substr($company['name'], 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="flex items-center">
                                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                                        {{ $company['name'] }}
                                                    </p>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $company['country'] === 'SG' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ $company['country'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                                                                                 <div class="mt-1 flex items-center text-sm text-gray-500">
                                                     <p class="truncate">
                                                         Slug: {{ $company['slug'] ?? 'N/A' }}
                                                     </p>
                                                     <span class="mx-2">•</span>
                                                     @if($company['country'] === 'SG')
                                                         <p class="truncate">
                                                             Reg: {{ $company['registration_number'] ?? 'N/A' }}
                                                         </p>
                                                     @else
                                                         <p class="truncate">
                                                             Brand: {{ $company['brand_name'] ?? 'N/A' }}
                                                         </p>
                                                     @endif
                                                 </div>
                                                @if($company['address'])
                                                    <div class="mt-1 text-sm text-gray-500">
                                                        <p class="truncate">{{ $company['address'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @elseif(isset($searchTerm) && $searchTerm)
            <div class="bg-white rounded-lg shadow px-5 py-6 sm:px-6">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No companies found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search terms or browse our database.</p>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow px-5 py-6 sm:px-6">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Search Companies</h3>
                    <p class="mt-1 text-sm text-gray-500">Enter a company name above to search across our Singapore and Mexico databases.</p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
