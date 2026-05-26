<?php

namespace App\Filament\Resources\Lamarans;

use App\Filament\Resources\Lamarans\Pages\EditLamaran;
use App\Filament\Resources\Lamarans\Pages\ListLamarans;
use App\Filament\Resources\Lamarans\Schemas\LamaranForm;
use App\Filament\Resources\Lamarans\Tables\LamaransTable;
use App\Models\Lamaran;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LamaranResource extends Resource
{


    protected static ?string $navigationLabel = 'Lamaran Magang';

    protected static ?string $modelLabel = 'Lamaran';

    protected static ?string $pluralModelLabel = 'Kelola Lamaran';

    protected static ?string $model = Lamaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check();
    }

    public static function canAccess(): bool
    {
        return Auth::check();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return LamaranForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LamaransTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLamarans::route('/'),
            'edit' => EditLamaran::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->role === 'mahasiswa') {

            $pendaftar = \App\Models\Pendaftar::where('user_id', Auth::id())->first();

            if ($pendaftar) {
                $query->where('pendaftar_id', $pendaftar->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        return $query;
    }
}
