<?php

namespace App\Filament\Resources\Lowongans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LowonganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_posisi')
                    ->required(),
                TextInput::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->required(),
                TextInput::make('divisi')
                    ->required(),
                Textarea::make('deskripsi')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('kuota')
                    ->required()
                    ->numeric(),
                TextInput::make('lokasi')
                    ->required(),
                Select::make('status')
                    ->options(['dibuka' => 'Dibuka', 'ditutup' => 'Ditutup'])
                    ->default('dibuka')
                    ->required(),
            ]);
    }
}
