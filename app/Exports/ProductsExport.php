<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Product::query()
            ->with(['translations', 'category', 'facility'])
            ->when($this->request->category_id, function ($query) {
                return $query->where('category_id', $this->request->category_id);
            })
            ->when($this->request->facility_id, function ($query) {
                return $query->where('facility_id', $this->request->facility_id);
            });
    }

    public function headings(): array
    {
        return [
            'المعرف',
            'SKU',
            'الاسم (عربي)',
            'الاسم (إنجليزي)',
            'الوصف (عربي)',
            'الوصف (إنجليزي)',
            'السعر',
            'النوع',
            'الفئة',
            'المنشأة',
            'الكمية',
            'حد المخزون المنخفض',
            'نشط',
            'تاريخ الإنشاء'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->sku,
            $product->translate('ar')->name ?? '',
            $product->translate('en')->name ?? '',
            $product->translate('ar')->description ?? '',
            $product->translate('en')->description ?? '',
            $product->price,
            $product->type,
            $product->category->name ?? '',
            $product->facility->name ?? '',
            $product->quantity ?? 0,
            $product->low_stock_threshold ?? 0,
            $product->is_active ? 'نعم' : 'لا',
            $product->created_at->format('Y-m-d H:i:s')
        ];
    }
}
