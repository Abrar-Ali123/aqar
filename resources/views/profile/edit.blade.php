@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">الملف الشخصي</h4>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label">رقم الهاتف</label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>

                    <hr class="my-5">

                    <div class="delete-account">
                        <h5 class="text-danger mb-4">حذف الحساب</h5>
                        <p class="text-muted mb-4">بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته نهائياً. قبل حذف حسابك، يرجى تحميل أي بيانات أو معلومات ترغب في الاحتفاظ بها.</p>

                        <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('هل أنت متأكد من حذف حسابك؟');">
                            @csrf
                            @method('delete')

                            <div class="mb-3">
                                <label for="password" class="form-label">كلمة المرور الحالية</label>
                                <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt me-1"></i>
                                    حذف الحساب
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.delete-account {
    background-color: #fff5f5;
    padding: 2rem;
    border-radius: 0.5rem;
}
</style>
@endsection
