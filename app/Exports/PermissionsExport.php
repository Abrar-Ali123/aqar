<?php
namespace App\Exports;

use App\Models\Permission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PermissionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Permission::select('id', 'name', 'pages')->get();
    }
    public function headings(): array
    {
        return ['ID', 'Name', 'Pages'];
    }
}
