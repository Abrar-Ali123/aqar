<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Facility;
use App\Models\Loan;
use App\Models\LoanTranslation;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['bank', 'facility'])->get();

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $banks = Bank::all();

        return view('loans.create', compact('banks'));
    }

    public function store(Request $request)
    {
        // إنشاء قرض جديد وربط المنشأة والمستخدم والبيانات الأساسية
        $loan = new Loan;
        $loan->facility_id = $request->facility_id; // تحديد المنشأة
        $loan->applicant = auth()->id();
        $loan->birth = $request->birth;
        $loan->salary = $request->salary;
        $loan->commitments = $request->commitments;
        $loan->military = $request->military;
        $loan->rank = $request->rank;
        $loan->employment = $request->employment;
        $loan->bank_id = $request->bank_id;
        $loan->updated_by = auth()->id(); // حفظ المستخدم الذي قام بالإنشاء
        $loan->save();

        // حفظ الترجمة لحقل agency حسب اللغة
        if ($request->has('translations')) {
            foreach ($request->input('translations') as $locale => $translationData) {
                $translations[] = [
                    'loan_id' => $loan->id,
                    'locale' => $locale,
                    'agency' => $translationData['agency'],
                ];
            }

            LoanTranslation::insert($translations);
        }

        return redirect()->route('loans.index')->with('success', 'تم تقديم الطلب بنجاح.');
    }

    public function edit($id)
    {
        $loan = Loan::findOrFail($id);

        return response()->json([
            'id' => $loan->id,
            'applicant' => $loan->applicant,
            'birth' => $loan->birth,
            'salary' => $loan->salary,
            'commitments' => $loan->commitments,
            'military' => $loan->military,
            'rank' => $loan->rank,
            'employment' => $loan->employment,
            'bank_id' => $loan->bank_id,
            'facility_id' => $loan->facility_id,
            'agency' => $loan->translate(app()->getLocale())->agency ?? '',
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $loan = Loan::findOrFail($id);

            // تحديث بيانات القرض الأساسية
            $loan->applicant = $request->applicant;
            $loan->birth = $request->birth;
            $loan->salary = $request->salary;
            $loan->commitments = $request->commitments;
            $loan->military = $request->military;
            $loan->rank = $request->rank;
            $loan->employment = $request->employment;
            $loan->bank_id = $request->bank_id;
            $loan->facility_id = $request->facility_id; // تحديد المنشأة
            $loan->updated_by = auth()->id(); // تحديث المستخدم الذي قام بالتعديل
            $loan->save();

            // تحديث الترجمة لحقل agency حسب اللغة الحالية فقط
            $locale = app()->getLocale();
            if ($request->has('agency')) {
                $loan->translateOrNew($locale)->agency = $request->agency;
                $loan->save();
            }

            return response()->json([
                'id' => $loan->id,
                'agency' => $loan->translate($locale)->agency,
                'birth' => $loan->birth,
                'salary' => $loan->salary,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating loan: '.$e->getMessage());

            return response()->json(['error' => 'حدث خطأ أثناء تحديث البيانات.'], 500);
        }
    }

    public function destroy($id)
    {
        Loan::destroy($id);

        return response()->json(['status' => 'success']);
    }

    public function facilityLoans($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        $loans = Loan::where('facility_id', $facility->id)->with('bank')->get();

        return view('loans.facility_loans', compact('loans', 'facility'));
    }
}
