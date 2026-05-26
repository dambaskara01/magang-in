<?php

namespace App\Filament\Resources\Lowongans\Pages;

use App\Filament\Resources\Lowongans\LowonganResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditLowongan extends EditRecord
{
    protected static string $resource = LowonganResource::class;

    protected function getHeaderActions(): array
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return [
                DeleteAction::make(),
            ];
        }

        return [];
    }
}
