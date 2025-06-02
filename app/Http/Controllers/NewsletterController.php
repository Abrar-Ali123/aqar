<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter_subscriptions,email',
            'facility_id' => 'nullable|exists:facilities,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $subscription = NewsletterSubscription::create([
            'email' => $request->email,
            'facility_id' => $request->facility_id,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => __('تم الاشتراك في النشرة البريدية بنجاح'),
            'data' => $subscription
        ]);
    }

    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:newsletter_subscriptions,email',
            'facility_id' => 'nullable|exists:facilities,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $query = NewsletterSubscription::where('email', $request->email);
        
        if ($request->facility_id) {
            $query->where('facility_id', $request->facility_id);
        }

        $query->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => __('تم إلغاء الاشتراك من النشرة البريدية بنجاح')
        ]);
    }
}
