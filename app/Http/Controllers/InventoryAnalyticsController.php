<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Location;
use App\Models\Attribute;
use App\Models\InventoryAlert;
use App\Services\InventoryAnalytics;
use Illuminate\Http\Request;

class InventoryAnalyticsController extends Controller
{
    protected $analytics;

    public function __construct(InventoryAnalytics $analytics)
    {
        $this->analytics = $analytics;
    }

    public function getConsumptionAnalysis(Request $request, Product $product)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'required|string',
            'days' => 'nullable|integer|min:1|max:365'
        ]);

        $location = Location::findOrFail($validated['location_id']);
        $attribute = Attribute::findOrFail($validated['attribute_id']);

        $analysis = $this->analytics->analyzeConsumptionRate(
            $product,
            $attribute,
            $validated['attribute_value'],
            $location,
            $validated['days'] ?? 30
        );

        return response()->json([
            'data' => $analysis
        ]);
    }

    public function getReorderPointAnalysis(Request $request, Product $product)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'required|string',
            'lead_time_days' => 'required|integer|min:1',
            'safety_stock_factor' => 'nullable|numeric|min:1|max:3'
        ]);

        $location = Location::findOrFail($validated['location_id']);
        $attribute = Attribute::findOrFail($validated['attribute_id']);

        $analysis = $this->analytics->predictReorderPoint(
            $product,
            $attribute,
            $validated['attribute_value'],
            $location,
            $validated['lead_time_days'],
            $validated['safety_stock_factor'] ?? 1.5
        );

        return response()->json([
            'data' => $analysis
        ]);
    }

    public function getSeasonalityAnalysis(Request $request, Product $product)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'required|string',
            'months' => 'nullable|integer|min:1|max:60'
        ]);

        $location = Location::findOrFail($validated['location_id']);
        $attribute = Attribute::findOrFail($validated['attribute_id']);

        $analysis = $this->analytics->analyzeSeasonality(
            $product,
            $attribute,
            $validated['attribute_value'],
            $location,
            $validated['months'] ?? 12
        );

        return response()->json([
            'data' => $analysis
        ]);
    }

    public function getCostAnalysis(Request $request, Product $product)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'required|string',
            'holding_cost_percentage' => 'nullable|numeric|min:0|max:1',
            'ordering_cost' => 'nullable|numeric|min:0'
        ]);

        $location = Location::findOrFail($validated['location_id']);
        $attribute = Attribute::findOrFail($validated['attribute_id']);

        $analysis = $this->analytics->analyzeInventoryCost(
            $product,
            $attribute,
            $validated['attribute_value'],
            $location,
            $validated['holding_cost_percentage'] ?? 0.2,
            $validated['ordering_cost'] ?? 100
        );

        return response()->json([
            'data' => $analysis
        ]);
    }

    public function getOptimizationRecommendations(Request $request, Product $product)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'required|string'
        ]);

        $location = Location::findOrFail($validated['location_id']);
        $attribute = Attribute::findOrFail($validated['attribute_id']);

        $recommendations = $this->analytics->getInventoryOptimizationRecommendations(
            $product,
            $attribute,
            $validated['attribute_value'],
            $location
        );

        return response()->json([
            'data' => $recommendations
        ]);
    }

    // إدارة التنبيهات
    public function createAlert(Request $request, Product $product)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'attribute_id' => 'required|exists:attributes,id',
            'attribute_value' => 'required|string',
            'alert_type' => 'required|in:low_stock,expiry,overstock',
            'threshold_value' => 'required|numeric|min:0',
            'threshold_unit_id' => 'required|exists:units,id',
            'notification_channels' => 'required|array',
            'notification_channels.*' => 'required|in:email,sms,push,database',
            'notification_roles' => 'required|array',
            'notification_roles.*' => 'required|exists:roles,id',
            'custom_message' => 'nullable|string',
            'frequency' => 'required|in:immediate,daily,weekly'
        ]);

        $alert = InventoryAlert::create(array_merge(
            $validated,
            ['is_active' => true]
        ));

        return response()->json([
            'message' => 'Alert created successfully',
            'data' => $alert
        ], 201);
    }

    public function updateAlert(Request $request, InventoryAlert $alert)
    {
        $validated = $request->validate([
            'threshold_value' => 'nullable|numeric|min:0',
            'threshold_unit_id' => 'nullable|exists:units,id',
            'notification_channels' => 'nullable|array',
            'notification_channels.*' => 'in:email,sms,push,database',
            'notification_roles' => 'nullable|array',
            'notification_roles.*' => 'exists:roles,id',
            'custom_message' => 'nullable|string',
            'frequency' => 'nullable|in:immediate,daily,weekly',
            'is_active' => 'nullable|boolean'
        ]);

        $alert->update($validated);

        return response()->json([
            'message' => 'Alert updated successfully',
            'data' => $alert
        ]);
    }

    public function deleteAlert(InventoryAlert $alert)
    {
        $alert->delete();

        return response()->json([
            'message' => 'Alert deleted successfully'
        ]);
    }

    public function getAlerts(Request $request, Product $product)
    {
        $validated = $request->validate([
            'location_id' => 'nullable|exists:locations,id',
            'attribute_id' => 'nullable|exists:attributes,id',
            'alert_type' => 'nullable|in:low_stock,expiry,overstock',
            'is_active' => 'nullable|boolean'
        ]);

        $query = InventoryAlert::where('product_id', $product->id)
            ->when($validated['location_id'] ?? null, function ($query, $locationId) {
                $query->where('location_id', $locationId);
            })
            ->when($validated['attribute_id'] ?? null, function ($query, $attributeId) {
                $query->where('attribute_id', $attributeId);
            })
            ->when($validated['alert_type'] ?? null, function ($query, $type) {
                $query->where('alert_type', $type);
            })
            ->when(isset($validated['is_active']), function ($query) use ($validated) {
                $query->where('is_active', $validated['is_active']);
            });

        return response()->json([
            'data' => $query->paginate()
        ]);
    }
}
