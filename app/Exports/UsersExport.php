<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    protected $search;
    protected $roleFilter;
    protected $page;
    protected $perPage;
    protected $currentPageOnly;

    public function __construct($search = null, $roleFilter = null, $page = null, $perPage = null)
    {
        $this->search = $search;
        $this->roleFilter = $roleFilter;
        $this->page = $page;
        $this->perPage = $perPage;
        $this->currentPageOnly = $page !== null && $perPage !== null;
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

        $query = $query->orderBy('created_at', 'desc');
        
        // If we're only exporting current page data, limit the query accordingly
        if ($this->currentPageOnly) {
            // Cast to integers to ensure correct calculations
            $page = (int)$this->page;
            $perPage = (int)$this->perPage;
            
            // Log the pagination parameters for debugging
            \Log::info('UsersExport pagination', [
                'page' => $page,
                'perPage' => $perPage,
                'offset' => ($page - 1) * $perPage
            ]);
            
            // Apply pagination directly to the query
            $offset = ($page - 1) * $perPage;
            $query->skip($offset)->take($perPage);
        }

        return $query;
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
