<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Project;
use App\Models\ProjectTranslation;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function getReverseGeocode($latitude, $longitude)
    {
        $client = new \GuzzleHttp\Client;
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $language = app()->getLocale();
        $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&language={$language}&key={$apiKey}");
        $data = json_decode($response->getBody());

        return $data->results[0]->formatted_address ?? 'Address not found';
    }

    public function index(Request $request, $facility)
    {
        $facility = Facility::findOrFail($facility);
        $projects = Project::with(['facility', 'translations'])->where('facility_id', $facility->id)->get();

        return view('projects.index', compact('projects', 'facility'));
    }

    public function create($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);

        return view('projects.create', compact('facility'));
    }

    public function store(Request $request)
    {
        $project = new Project;
        $project->latitude = $request->latitude;
        $project->longitude = $request->longitude;
        $project->google_maps_url = $request->google_maps_url;
        $project->facility_id = auth()->user()->facility_id;
        $project->seller_user_id = auth()->user()->id;
        $project->project_type = $request->project_type;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects/images', 'public');
            $project->image = $imagePath;
        }

        $project->save();

        if ($request->has('translations')) {
            $translations = [];
            foreach ($request->input('translations') as $locale => $translationData) {
                $translations[] = [
                    'project_id' => $project->id,
                    'locale' => $locale,
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? '',
                ];
            }
            ProjectTranslation::insert($translations);
        }

        return redirect()->route('projects.index', ['facility' => $project->facility_id])->with('success', 'تم إضافة المشروع بنجاح.');
    }

    public function show($id)
    {
        $project = Project::with('translations')->findOrFail($id);

        return view('projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::with('translations')->findOrFail($id);
        $facilities = Facility::all();

        return view('projects.edit', compact('project', 'facilities'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->author = $request->author;
        $project->latitude = $request->latitude;
        $project->longitude = $request->longitude;
        $project->google_maps_url = $request->google_maps_url;
        $project->facility_id = $request->facility_id;
        $project->project_type = $request->project_type;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects/images', 'public');
            $project->image = $imagePath;
        }

        $project->save();

        if ($request->has('translations')) {
            ProjectTranslation::where('project_id', $project->id)->delete();
            $translations = [];
            foreach ($request->input('translations') as $locale => $translationData) {
                $translations[] = [
                    'project_id' => $project->id,
                    'locale' => $locale,
                    'name' => $translationData['name'],
                    'description' => $translationData['description'] ?? '',
                ];
            }
            ProjectTranslation::insert($translations);
        }

        return redirect()->route('projects.index')->with('success', 'تم تحديث المشروع بنجاح.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'تم حذف المشروع بنجاح.');
    }
}
