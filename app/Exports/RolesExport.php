<?php
namespace App\Exports;

use App\Models\Role;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RolesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Role::select('id', 'name', 'description', 'is_active', 'level')->get();
    }
    public function headings(): array
    {
        return ['ID', 'Name', 'Description', 'Active', 'Level'];
    }
}
