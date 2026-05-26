<?php

namespace App\Filament\Resources\Lowongans\Pages;

use App\Filament\Resources\Lowongans\LowonganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListLowongans extends ListRecords
{
    protected static string $resource = LowonganResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return [
                CreateAction::make(),
            ];
        }

        return [];
    }
}
