<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        $statuses = $facility->statuses()->with('translations')->get();

        return view('statuses.index', compact('facility', 'statuses'));
    }

    public function create($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);

        return view('statuses.create', compact('facility'));
    }

    public function store(Request $request, $facilityId)
    {
        $facility = Facility::findOrFail($facilityId);

        $status = $facility->statuses()->create([
            'color' => $request->color,
            'icon' => $request->file('icon') ? $request->file('icon')->store('icons', 'public') : null,
            'table_name' => $request->table_name,
            'automated' => $request->automated,
            'action' => $request->action,
        ]);

        foreach (config('app.locales') as $locale) {
            $status->translations()->create([
                'locale' => $locale,
                'name' => $request->input("translations.{$locale}.name"),
            ]);
        }

        return redirect()->route('facilities.statuses.index', $facility->id)->with('success', 'تمت إضافة الحالة بنجاح.');
    }

    public function show($facilityId, $id)
    {
        $facility = Facility::findOrFail($facilityId);
        $status = $facility->statuses()->with('translations')->findOrFail($id);

        return view('statuses.show', compact('facility', 'status'));
    }

    public function edit($facilityId, $id)
    {
        $facility = Facility::findOrFail($facilityId);
        $status = $facility->statuses()->with('translations')->findOrFail($id);

        return view('statuses.edit', compact('facility', 'status'));
    }

    public function update(Request $request, $facilityId, $id)
    {
        $facility = Facility::findOrFail($facilityId);
        $status = $facility->statuses()->findOrFail($id);

        $status->update([
            'color' => $request->color,
            'icon' => $request->file('icon') ? $request->file('icon')->store('icons', 'public') : $status->icon,
            'table_name' => $request->table_name,
            'automated' => $request->automated,
            'action' => $request->action,
        ]);

        foreach (config('app.locales') as $locale) {
            $status->translations()->updateOrCreate(
                ['locale' => $locale],
                ['name' => $request->input("translations.{$locale}.name")]
            );
        }

        return redirect()->route('facilities.statuses.index', $facility->id)->with('success', 'تم تحديث الحالة بنجاح.');
    }

    public function destroy($facilityId, $id)
    {
        $facility = Facility::findOrFail($facilityId);
        $status = $facility->statuses()->findOrFail($id);
        $status->delete();

        return redirect()->route('facilities.statuses.index', $facility->id)->with('success', 'تم حذف الحالة بنجاح.');
    }
}
