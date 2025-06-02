<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends TranslatableController
{
    protected $translatableFields = [
        'purpose' => ['required', 'string'],
        'notes' => ['nullable', 'string'],
        'rejection_reason' => ['nullable', 'string'],
    ];

    public function index()
    {
        if (!Auth::user()->can('view loans')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $query = Loan::with(['user', 'status.translations']);

        // Filter by user if not admin
        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        $loans = $query->latest()->paginate(15);
        return view('admin.loans.index', compact('loans'));
    }

    public function create()
    {
        if (!Auth::user()->can('create loans')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $languages = $this->getLanguages();
        return view('admin.loans.create', compact('languages'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create loans')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $request->validate([
            'amount' => 'required|numeric|min:1000|max:1000000',
            'duration_months' => 'required|integer|min:6|max:240',
            'monthly_income' => 'required|numeric|min:0',
            'employment_type' => 'required|string|in:full-time,part-time,self-employed,retired',
            'employer_name' => 'required|string|max:255',
            'employment_duration_years' => 'required|numeric|min:0',
            'has_existing_loans' => 'required|boolean',
            'existing_loans_monthly_payment' => 'required_if:has_existing_loans,true|nullable|numeric|min:0',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            $loan = Loan::create([
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'duration_months' => $request->duration_months,
                'monthly_income' => $request->monthly_income,
                'employment_type' => $request->employment_type,
                'employer_name' => $request->employer_name,
                'employment_duration_years' => $request->employment_duration_years,
                'has_existing_loans' => $request->boolean('has_existing_loans'),
                'existing_loans_monthly_payment' => $request->existing_loans_monthly_payment,
                'status_id' => Status::where('type', 'loan')
                    ->where('is_default', true)
                    ->first()
                    ->id,
            ]);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('loans/attachments', 'public');
                    $loan->attachments()->create([
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            $this->handleTranslations($loan, $request, array_keys($this->translatableFields));

            return redirect()->route('admin.loans.index')
                ->with('success', __('messages.loan_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.loan_create_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Loan $loan)
    {
        if (!Auth::user()->can('view loans') || 
            (!Auth::user()->hasRole('admin') && $loan->user_id !== Auth::id())) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $loan->load(['user', 'status.translations', 'attachments']);
        $translations = $this->prepareTranslations($loan, array_keys($this->translatableFields));
        return view('admin.loans.show', compact('loan', 'translations'));
    }

    public function edit(Loan $loan)
    {
        if (!Auth::user()->can('edit loans') || 
            (!Auth::user()->hasRole('admin') && $loan->user_id !== Auth::id())) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $statuses = Status::with('translations')
            ->where('type', 'loan')
            ->where('is_active', true)
            ->get();
        $languages = $this->getLanguages();
        $translations = $this->prepareTranslations($loan, array_keys($this->translatableFields));
        
        return view('admin.loans.edit', compact('loan', 'statuses', 'languages', 'translations'));
    }

    public function update(Request $request, Loan $loan)
    {
        if (!Auth::user()->can('edit loans') || 
            (!Auth::user()->hasRole('admin') && $loan->user_id !== Auth::id())) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $request->validate([
            'amount' => 'required|numeric|min:1000|max:1000000',
            'duration_months' => 'required|integer|min:6|max:240',
            'monthly_income' => 'required|numeric|min:0',
            'employment_type' => 'required|string|in:full-time,part-time,self-employed,retired',
            'employer_name' => 'required|string|max:255',
            'employment_duration_years' => 'required|numeric|min:0',
            'has_existing_loans' => 'required|boolean',
            'existing_loans_monthly_payment' => 'required_if:has_existing_loans,true|nullable|numeric|min:0',
            'status_id' => 'required|exists:statuses,id',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            $loan->update([
                'amount' => $request->amount,
                'duration_months' => $request->duration_months,
                'monthly_income' => $request->monthly_income,
                'employment_type' => $request->employment_type,
                'employer_name' => $request->employer_name,
                'employment_duration_years' => $request->employment_duration_years,
                'has_existing_loans' => $request->boolean('has_existing_loans'),
                'existing_loans_monthly_payment' => $request->existing_loans_monthly_payment,
                'status_id' => $request->status_id,
            ]);

            // Handle attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('loans/attachments', 'public');
                    $loan->attachments()->create([
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            $this->handleTranslations($loan, $request, array_keys($this->translatableFields));

            return redirect()->route('admin.loans.index')
                ->with('success', __('messages.loan_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.loan_update_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Loan $loan)
    {
        if (!Auth::user()->can('delete loans') || 
            (!Auth::user()->hasRole('admin') && $loan->user_id !== Auth::id())) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        try {
            // Delete attachments
            foreach ($loan->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
                $attachment->delete();
            }

            $loan->delete();

            return redirect()->route('admin.loans.index')
                ->with('success', __('messages.loan_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.loan_delete_error') . ': ' . $e->getMessage());
        }
    }

    public function approve(Loan $loan)
    {
        if (!Auth::user()->can('edit loans') || !Auth::user()->hasRole('admin')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        try {
            $approvedStatus = Status::where('type', 'loan')
                ->where('name->en', 'Approved')
                ->firstOrFail();

            $loan->update([
                'status_id' => $approvedStatus->id,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

            return redirect()->route('admin.loans.index')
                ->with('success', __('messages.loan_approved_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.loan_approve_error') . ': ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Loan $loan)
    {
        if (!Auth::user()->can('edit loans') || !Auth::user()->hasRole('admin')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        try {
            $rejectedStatus = Status::where('type', 'loan')
                ->where('name->en', 'Rejected')
                ->firstOrFail();

            $loan->update([
                'status_id' => $rejectedStatus->id,
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
            ]);

            $this->handleTranslations($loan, $request, ['rejection_reason']);

            return redirect()->route('admin.loans.index')
                ->with('success', __('messages.loan_rejected_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.loan_reject_error') . ': ' . $e->getMessage());
        }
    }
}
