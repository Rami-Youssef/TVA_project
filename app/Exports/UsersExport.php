<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    protected $search;
    protected $roleFilter;

    public function __construct($search = null, $roleFilter = null)
    {
        $this->search = $search;
        $this->roleFilter = $roleFilter;
    }

    public function query()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->roleFilter && $this->roleFilter !== 'all') {
            $query->where('role', $this->roleFilter);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Role',
            'Creation Date',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
            $user->role,
            $user->created_at->format('d/m/Y H:i'),
        ];
    }
}
