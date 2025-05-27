<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserCurrentPageExport implements FromQuery, WithHeadings, WithMapping
{
    protected $userIds;

    public function __construct(array $userIds)
    {
        $this->userIds = $userIds;
    }

    public function query()
    {
        // Query that explicitly filters by the user IDs from the current page
        return User::query()
            ->whereIn('id', $this->userIds)
            ->orderBy('created_at', 'desc');
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
