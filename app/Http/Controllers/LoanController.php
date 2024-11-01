<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Loan;
use App\Models\LoanTranslation;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with('bank')->get();

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $banks = Bank::all();

        return view('loans.create', compact('banks'));
    }

    public function store(Request $request)
    {
        $loan = new Loan;
        $loan->applicant = auth()->id();
        $loan->birth = $request->birth;
        $loan->salary = $request->salary;
        $loan->commitments = $request->commitments;
        $loan->military = $request->military;
        $loan->rank = $request->rank;
        $loan->employment = $request->employment;
        $loan->agency = $request->agency;
        $loan->bank_id = $request->bank_id;

        $loan->save();

        $translations = [];

        if ($request->has('translations')) {
            foreach ($request->input('translations') as $locale => $translationData) {
                $translations[] = [
                    'bank_id' => $bank->id,
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
        $banks = Bank::all();

        return view('loans.edit', compact('loan', 'banks'));
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->birth = $request->birth;
        $loan->salary = $request->salary;
        $loan->commitments = $request->commitments;
        $loan->military = $request->military;
        $loan->rank = $request->rank;
        $loan->employment = $request->employment;
        $loan->agency = $request->agency;
        $loan->bank_id = $request->bank_id;

        $loan->save();

        LoanTranslation::where('loan_id', $loan->id)->delete();

        foreach ($request->translations as $translation) {
            LoanTranslation::create([
                'loan_id' => $loan->id,
                'locale' => $translation['locale'],
                'translated_agency' => $translation['agency'] ?? null,
            ]);
        }

        return redirect()->route('loans.index')->with('success', 'تم تحديث الطلب بنجاح.');
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->delete();
        LoanTranslation::where('loan_id', $id)->delete();

        return redirect()->route('loans.index')->with('success', 'تم حذف الطلب بنجاح.');
    }
}
