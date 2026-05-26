<?php

namespace App\Filament\Resources\Lowongans;

use App\Filament\Resources\Lowongans\Pages\CreateLowongan;
use App\Filament\Resources\Lowongans\Pages\EditLowongan;
use App\Filament\Resources\Lowongans\Pages\ListLowongans;
use App\Filament\Resources\Lowongans\Schemas\LowonganForm;
use App\Filament\Resources\Lowongans\Tables\LowongansTable;
use App\Models\Lowongan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LowonganResource extends Resource
{
    protected static ?string $navigationLabel = 'Lowongan Magang';

    protected static ?string $model = Lowongan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function canViewAny(): bool
    {
        return Auth::check(); // semua user boleh lihat
    }

    public static function canView($record): bool
    {
        return Auth::check();
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function canCreateAnother(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return LowonganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LowongansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLowongans::route('/'),
            'create' => CreateLowongan::route('/create'),
            'edit' => EditLowongan::route('/{record}/edit'),
        ];
    }
}
