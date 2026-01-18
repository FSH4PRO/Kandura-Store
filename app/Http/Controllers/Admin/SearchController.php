<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Search\SearchService;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SearchRequest;
use App\Http\Requests\Admin\SearchSuggestionsRequest;

class SearchController extends Controller
{
    public function __construct(
        protected SearchService $searchService
    ) {}

    /**
     * Global search results page
     */
    public function index(SearchRequest $request)
    {
        $query = $request->get('q', '');
        $types = $request->get('types', []);

        $results = $this->searchService->search($query, $types);

        $totalResults = array_sum(array_map('count', $results));

        return view('content.search.index', [
            'query' => $query,
            'results' => $results,
            'totalResults' => $totalResults,
            'selectedTypes' => $types,
        ]);
    }

    /**
     * AJAX endpoint for autocomplete suggestions
     */
    public function suggestions(SearchSuggestionsRequest $request)
    {
        $query = $request->get('q', '');

        $suggestions = $this->searchService->getSuggestions($query);

        return response()->json($suggestions);
    }
}
