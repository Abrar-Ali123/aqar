<?php

namespace App\Http\Middleware;

use App\Models\Facility;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول للوصول إلى هذه الصفحة');
        }

        $user = Auth::user();
        $facilityId = $request->route('facility');

        $facility = Facility::find($facilityId);
        if (! $facility) {
            return redirect()->route('login')->with('error', 'المنشأة غير موجودة.');
        }

        $permissions = $user->roles()
            ->whereHas('userFacilityRoles', function ($query) use ($facilityId) {
                $query->where('facility_id', $facilityId);
            })
            ->with('permissions')
            ->get()
            ->pluck('permissions.*.pages')
            ->flatten()
            ->unique()
            ->toArray();

        // return array of 1 item ,  item is json string
        // array[0] // json_decode json string to array

        $permissions = json_decode($permissions[0], true);

        $requiredPermission = $request->route()->getName();

        if (! in_array($requiredPermission, $permissions)) {
            print_r($requiredPermission);

            //  print_r($permissions);
            //return redirect()->route('login')->with('error', 'ليس لديك الصلاحية للوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
