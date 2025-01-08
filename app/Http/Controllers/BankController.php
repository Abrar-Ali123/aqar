<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankTranslation;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::with('translations')->get();

        return view('banks.index', compact('banks'));
    }

    public function create()
    {
        return view('banks.create');
    }

    public function store(Request $request)
    {
        $bank = new Bank;

        if ($request->hasFile('logo')) {
            $bank->logo = $request->file('logo')->store('banks/logos', 'public');
        }

        $bank->save();

        $translations = [];

        if ($request->has('translations')) {
            foreach ($request->input('translations') as $locale => $translationData) {
                $translations[] = [
                    'bank_id' => $bank->id,
                    'locale' => $locale,
                    'name' => $translationData['name'],
                ];
            }

            BankTranslation::insert($translations);
        }

        return redirect()->route('banks.index')->with('success', 'تم إنشاء البنك بنجاح.');
    }

    public function edit($id)
    {
        $bank = Bank::with('translations')->findOrFail($id);

        return view('banks.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);
        $bank->name = $request->name;

        if ($request->hasFile('logo')) {
            $bank->logo = $request->file('logo')->store('banks/logos', 'public');
        }

        $bank->save();

        BankTranslation::where('bank_id', $bank->id)->delete();

        foreach ($request->input('translations') as $translation) {
            BankTranslation::create([
                'bank_id' => $bank->id,
                'locale' => $translation['locale'],
                'name' => $translation['name'],
            ]);
        }

        return redirect()->route('banks.index')->with('success', 'تم تحديث البنك بنجاح.');
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();
        BankTranslation::where('bank_id', $id)->delete();

        return redirect()->route('banks.index')->with('success', 'تم حذف البنك بنجاح.');
    }
}
