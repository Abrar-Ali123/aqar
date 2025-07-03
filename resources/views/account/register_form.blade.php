<form id="register-user-form">
    @csrf
    <input type="hidden" id="reg_firebase_uid" name="firebase_uid" value="">
    <input type="hidden" id="reg_phone_number" name="phone_number" value="">

    <div class="mb-3">
        <label for="reg_name" class="form-label">الاسم الكامل</label>
        <input type="text" class="form-control" id="reg_name" name="name" required>
    </div>

    <div class="mb-3">
        <label for="reg_type" class="form-label">نوع المستخدم</label>
        <select class="form-control" id="reg_type" name="type" required>
            <option value="individual">فرد</option>
            <option value="company">شركة</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">إكمال التسجيل</button>

    <div id="register-form-errors" class="text-danger mt-2"></div>
</form>
