<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function __construct(protected ?string $status = null, protected ?string $name = null)
    {
    }

    public function collection()
    {
        $query = User::query()->orderByDesc('created_at');

        if ($this->name) {
            $query->where('name', 'like', '%'.$this->name.'%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get(['id', 'name', 'email', 'phone_number', 'status', 'created_at'])
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'status' => $user->status,
                'created_at' => $user->created_at?->format('Y-m-d'),
            ]);
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Email', 'Phone Number', 'Status', 'Created At'];
    }
}
