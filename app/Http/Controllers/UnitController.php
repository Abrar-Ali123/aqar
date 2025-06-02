<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitConversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $query = Unit::query()
            ->when($validated['type'] ?? null, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when(isset($validated['is_active']), function ($query) use ($validated) {
                $query->where('is_active', $validated['is_active']);
            });

        $units = $query->paginate($validated['per_page'] ?? 15);

        return response()->json([
            'data' => $units
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
            'code' => 'required|string|unique:units,code',
            'type' => 'required|string',
            'is_base' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'symbol' => 'nullable|array',
            'symbol.*' => 'nullable|string|max:50'
        ]);

        try {
            DB::beginTransaction();

            $unit = Unit::create([
                'name' => $validated['name']['ar'] ?? $validated['name']['en'],
                'code' => $validated['code'],
                'type' => $validated['type'],
                'is_base' => $validated['is_base'] ?? false,
                'is_active' => $validated['is_active'] ?? true
            ]);

            // حفظ الترجمات
            foreach ($validated['name'] as $locale => $name) {
                $unit->translations()->create([
                    'locale' => $locale,
                    'name' => $name,
                    'symbol' => $validated['symbol'][$locale] ?? null
                ]);
            }

            // إذا كانت وحدة أساسية، تحديث باقي الوحدات من نفس النوع
            if ($validated['is_base'] ?? false) {
                Unit::where('type', $validated['type'])
                    ->where('id', '!=', $unit->id)
                    ->update(['is_base' => false]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Unit created successfully',
                'data' => $unit->load('translations')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(Unit $unit)
    {
        return response()->json([
            'data' => $unit->load(['translations', 'fromConversions.toUnit', 'toConversions.fromUnit'])
        ]);
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
            'code' => 'required|string|unique:units,code,' . $unit->id,
            'type' => 'required|string',
            'is_base' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'symbol' => 'nullable|array',
            'symbol.*' => 'nullable|string|max:50'
        ]);

        try {
            DB::beginTransaction();

            $unit->update([
                'name' => $validated['name']['ar'] ?? $validated['name']['en'],
                'code' => $validated['code'],
                'type' => $validated['type'],
                'is_base' => $validated['is_base'] ?? $unit->is_base,
                'is_active' => $validated['is_active'] ?? $unit->is_active
            ]);

            // تحديث الترجمات
            foreach ($validated['name'] as $locale => $name) {
                $unit->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'name' => $name,
                        'symbol' => $validated['symbol'][$locale] ?? null
                    ]
                );
            }

            // إذا كانت وحدة أساسية، تحديث باقي الوحدات من نفس النوع
            if ($validated['is_base'] ?? false) {
                Unit::where('type', $validated['type'])
                    ->where('id', '!=', $unit->id)
                    ->update(['is_base' => false]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Unit updated successfully',
                'data' => $unit->load('translations')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Unit $unit)
    {
        // التحقق من عدم استخدام الوحدة في المخزون
        if ($unit->inventoryValues()->exists()) {
            return response()->json([
                'message' => 'Cannot delete unit that is being used in inventory'
            ], 400);
        }

        $unit->delete();

        return response()->json([
            'message' => 'Unit deleted successfully'
        ]);
    }

    public function storeConversion(Request $request)
    {
        $validated = $request->validate([
            'from_unit_id' => 'required|exists:units,id',
            'to_unit_id' => 'required|exists:units,id|different:from_unit_id',
            'conversion_factor' => 'required|numeric|gt:0',
            'create_reverse' => 'nullable|boolean'
        ]);

        $fromUnit = Unit::findOrFail($validated['from_unit_id']);
        $toUnit = Unit::findOrFail($validated['to_unit_id']);

        // التحقق من أن الوحدتين من نفس النوع
        if ($fromUnit->type !== $toUnit->type) {
            return response()->json([
                'message' => 'Units must be of the same type'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $conversion = UnitConversion::create([
                'from_unit_id' => $validated['from_unit_id'],
                'to_unit_id' => $validated['to_unit_id'],
                'conversion_factor' => $validated['conversion_factor']
            ]);

            // إنشاء تحويل عكسي إذا تم طلبه
            if ($validated['create_reverse'] ?? false) {
                $conversion->createReverse();
            }

            DB::commit();

            return response()->json([
                'message' => 'Conversion created successfully',
                'data' => $conversion
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroyConversion(UnitConversion $conversion)
    {
        $conversion->delete();

        return response()->json([
            'message' => 'Conversion deleted successfully'
        ]);
    }
}
