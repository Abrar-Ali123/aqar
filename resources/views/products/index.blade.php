@extends('dashboard.layouts.app1')
    @section('content')
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة مع هيدر وفوتر وشبكة داخلية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;

        }


        .main {
            display: flex;
            flex: 1;
        }
        .sidebar {
            background-color: #555;
            padding: 10px;
            width: 200px;
            height: 100vh;
            overflow-y: auto;
        }
        .content {

            flex: 1;
            padding: 20px;
            background-color: red;
            margin: 8px;

        }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 20px;

        }
        .cart {
            position: relative;
            width: 100%;
        }
        .images {
            position: relative;
            width: 100%;

        }
        .images img {
            width: 100%;
            height: 300px;
            display: block;
        }
        .images h1 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
            z-index: 1;
        }
        .price-tag {
            background-color: #FFD700;
            color: #333;
            font-size: 1.5em;
            padding: 5px 10px;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 10;

            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%); /* ضبط التوسيط الأفقي */
        }
        .inner-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-columns: 30% 40% 30%;
            gap: 5px;

        }

        .grid div, .inner-grid div {
            background-color: #ddd;
            padding: 5px;

        }
        .property-info {
            font-size: 14px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
        }
        .controls {
            display: flex;
            justify-content: Space-around;
            margin-top: 10px;
        }
        .control-btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            color: white;
            border-radius: 5px;
        }
        .edit { background-color: #4CAF50; }
        .delete { background-color: #f44336; }
        .view { background-color: #008CBA; }
        nav ul {
            list-style-type: none;
        }
        nav ul li {
            padding: 8px;
            margin-bottom: 8px;
            background-color: #666;
            color: white;
            text-align: center;
        }
        nav ul li:hover {
            background-color: #777;
        }
        @media (max-width: 600px) {
            .grid {
                grid-template-columns: repeat(1, 1fr);
            }
            .inner-grid {
                grid-template-columns: repeat(4, 1fr); /* هذا يحافظ على عرض المعلومات بمعلومتين في كل سطر حتى على الشاشات الصغيرة */
            }
            .sidebar {
                position: fixed;
                left: -200px;
                top: 0;
                height: 100%;
                z-index: 1000;
                transition: left 0.3s;
            }
            .menu-toggle {
                display: block;
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 1001;
                cursor: pointer;
            }
        }
        /* تنسيق الفورم الخاص بالبحث */
        form {
            margin: 20px;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 10px;
        }

        form input[type="text"],
        form input[type="number"],
        form select,
        form button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #45a049;
        }




    </style>
</head>
<body>



<form action="{{ route('products.index') }}" method="get">

    <!-- حقل البحث العام -->
    <input type="text" name="search_term" placeholder="ابحث عن منتج..." value="{{ request('search_term') }}">

    <!-- التصفية بالمساحة -->
    <input type="number" name="min_space" placeholder="المساحة الدنيا" value="{{ request('min_space') }}">
    <input type="number" name="max_space" placeholder="المساحة القصوى" value="{{ request('max_space') }}">

    <!-- التصفية بالسعر -->
    <input type="number" name="min_price" placeholder="السعر الأدنى" value="{{ request('min_price') }}">
    <input type="number" name="max_price" placeholder="السعر الأقصى" value="{{ request('max_price') }}">

    <!-- البحث بالتصنيف -->
    <select name="category_id">
        <option value="">اختر التصنيف</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
    </select>

    <!-- البحث بالنوع -->
    <input type="text" name="type" placeholder="النوع" value="{{ request('type') }}">


    <!-- البحث بالمنشأة -->
    <select name="facility_id">
        <option value="">اختر المنشأة</option>
        @foreach ($facility as $facilityItem)
            <option value="{{ $facilityItem->id }}" {{ request('facility_id') == $facilityItem->id ? 'selected' : '' }}>{{ $facilityItem->name }}</option>
        @endforeach
    </select>

    <!-- البحث بالموظف -->
    {{--<select name="employee_id">--}}
    {{--    <option value="">اختر الموظف</option>--}}
    {{--    @foreach ($employees as $employee)--}}
    {{--        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>--}}
    {{--    @endforeach--}}
    {{--</select>--}}

    {{--<!-- البحث بالمالك -->--}}
    {{--<select name="owner_id">--}}
    {{--    <option value="">اختر المالك</option>--}}
    {{--    @foreach ($owners as $owner)--}}
    {{--        <option value="{{ $owner->id }}" {{ request('owner_id') == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>--}}
    {{--    @endforeach--}}
    {{--</select>--}}

    <!-- زر البحث -->
    <button type="submit">بحث</button>
</form>




<div class="sdf">

</div>

<div class="grid">
    @foreach($products as $product)

        <div class="cart" data-space="120">
            <div class="images">

                <img src="{{ Storage::url($product->image) }}" alt="صورة">
                <span class="type-badge">{{ $product->product_type }}</span>
                <span class="price-badge">{{ $product->price }} ر.س</span>

                <h1>{{ $product->name }}</h1>
            </div>

            <style>
                .images {
                    position: relative;
                    /* بقية الأنماط */
                }

                .price-badge {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background-color: rgba(255, 255, 255, 0.7);
                    color: #333;
                    padding: 5px;
                    border-radius: 5px;
                    font-size: 0.8em;
                    z-index: 2;
                    /* إضافة ظل للنص لتعزيز القراءة على خلفيات متنوعة */
                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
                }


                .type-badge {
                    position: absolute;
                    top: 10px;
                    left: 10px;
                    background-color: rgba(0, 0, 0, 0.7);
                    color: #fff;
                    padding: 5px;
                    border-radius: 5px;
                    font-size: 0.8em;
                    z-index: 2;
                }


                .inner-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 5px;

                    font-size: 12px;
                    text-align: center;
                }

                .inner-grid div {
                    background-color: #ddd;
                    margin: 0;

                }

                @media (max-width: 600px) {
                    .grid {
                        grid-template-columns: repeat(1, 1fr);
                    }
                    .inner-grid {
                        grid-template-columns: repeat(3, 1fr); /* هذا يحافظ على عرض المعلومات بمعلومتين في كل سطر حتى على الشاشات الصغيرة */
                    }
                }
            </style>

            <div class="inner-grid">
                <div class="igrid"><i class="fas fa-bed"></i>  الغرف :  {{ $product->room }}</div>
                <div class="igrid"><i class="fas fa-toilet"></i> دورات المياة :  {{ $product->bathroom }}</div>
                <div class="igrid"><i class="fas fa-expand"></i> المساحة : {{ $product->Space }}</div>


                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

            </div>

            <div class="property-info">
                <!-- عرض التصنيف -->
                @if($product->category)
                    <div><i class="fas fa-tags"></i> التصنيف: {{ $product->category->name }}</div>
                @endif

                <!-- عرض اسم المالك -->
                @if($product->owner)
                    <div><i class="fas fa-user-tie"></i> المالك: {{ $product->owner->name }}</div>
                @endif

                <!-- عرض النوع -->
            </div>
            <div class="property-info">
                <div><i class="fas fa-building"></i> اسم المنشأة: {{ $product->employee->facility->name }}</div>
                <div><i class="fas fa-user"></i> اسم الموظف: {{ $product->employee->name }}</div>
            </div>
            <div class="controls">
                <div><i class="fas fa-thumbs-up"></i> 100 </div>
                <div><i class="fas fa-comments"></i> 50 </div>

                <button class="control-btn view"><i class="fas fa-eye"></i> عرض</button>
                <button class="control-btn edit">
                    <a href="{{ route('products.edit', ['product' => $product->id]) }}" style="color: white; text-decoration: none;">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                </button>
                <a href="{{ route('products.delete', ['product' => $product->id]) }}" class="control-btn delete">
                    <i class="fas fa-trash"></i> حذف
                </a>
            </div>
        </div>
    @endforeach

    <!-- يمكنك إضافة المزيد من العناصر هنا -->
</div>

</div>

</div>



<footer>فوتر الموقع</footer>


</body>
    @endsection
