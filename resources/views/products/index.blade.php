@extends('dashboard.layouts.app1')
    @section('content')







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




    @endsection
