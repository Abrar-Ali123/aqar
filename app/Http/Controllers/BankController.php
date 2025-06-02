<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankController extends TranslatableController
{
    protected $translatableFields = [
        'name' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
    ];

    public function index()
    {
        $banks = Bank::latest()->paginate(10);
        return view('banks.index', compact('banks'));
    }

    public function create()
    {
        $languages = $this->getLanguages();
        return view('banks.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'swift_code' => 'required|string|max:11|unique:banks',
            'is_active' => 'boolean',
        ]);

        try {
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('banks/logos', 'public');
            }

            $bank = Bank::create([
                'logo' => $logoPath,
                'swift_code' => $request->swift_code,
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->handleTranslations($bank, $request, array_keys($this->translatableFields));

            return redirect()->route('banks.index')
                ->with('success', __('messages.bank_created_successfully'));
        } catch (\Exception $e) {
            if (isset($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            return redirect()->back()
                ->with('error', __('messages.bank_create_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Bank $bank)
    {
        $languages = $this->getLanguages();
        $translations = $this->prepareTranslations($bank, array_keys($this->translatableFields));
        return view('banks.edit', compact('bank', 'languages', 'translations'));
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'swift_code' => 'required|string|max:11|unique:banks,swift_code,' . $bank->id,
            'is_active' => 'boolean',
        ]);

        try {
            if ($request->hasFile('logo')) {
                if ($bank->logo) {
                    Storage::disk('public')->delete($bank->logo);
                }
                $bank->logo = $request->file('logo')->store('banks/logos', 'public');
            }

            $bank->update([
                'swift_code' => $request->swift_code,
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->handleTranslations($bank, $request, array_keys($this->translatableFields));

            return redirect()->route('banks.index')
                ->with('success', __('messages.bank_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.bank_update_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Bank $bank)
    {
        try {
            if ($bank->loans()->exists()) {
                return redirect()->back()
                    ->with('error', __('messages.bank_delete_error_has_loans'));
            }

            if ($bank->logo) {
                Storage::disk('public')->delete($bank->logo);
            }

            $bank->delete();

            return redirect()->route('banks.index')
                ->with('success', __('messages.bank_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.bank_delete_error') . ': ' . $e->getMessage());
        }
    }
}
