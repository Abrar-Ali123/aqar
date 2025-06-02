<?php

namespace App\Http\Controllers;

use App\Models\SavedSearch;
use Illuminate\Http\Request;

class SavedSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'notify' => 'boolean',
            'frequency' => 'nullable|in:daily,weekly,monthly',
        ]);

        $filters = array_filter($request->except(['name', 'notify', 'frequency', '_token']));

        $savedSearch = $request->user()->savedSearches()->create([
            'name' => $validated['name'],
            'filters' => $filters,
            'notify' => $validated['notify'] ?? false,
            'frequency' => $validated['frequency'],
        ]);

        return response()->json([
            'message' => __('messages.search_saved'),
            'saved_search' => $savedSearch
        ]);
    }

    public function index()
    {
        $savedSearches = auth()->user()->savedSearches()->latest()->get();
        
        return view('saved-searches.index', compact('savedSearches'));
    }

    public function destroy(SavedSearch $savedSearch)
    {
        $this->authorize('delete', $savedSearch);
        
        $savedSearch->delete();
        
        return response()->json(['message' => __('messages.search_deleted')]);
    }

    public function update(Request $request, SavedSearch $savedSearch)
    {
        $this->authorize('update', $savedSearch);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'notify' => 'boolean',
            'frequency' => 'nullable|in:daily,weekly,monthly',
        ]);

        $savedSearch->update($validated);
        
        return response()->json([
            'message' => __('messages.search_updated'),
            'saved_search' => $savedSearch
        ]);
    }
}
