<?php

namespace App\Filament\Resources\Lowongans\Tables;

use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LowongansTable
{
    public static function configure(Table $table): Table
    {
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';
        $isMahasiswa = Auth::check() && Auth::user()->role === 'mahasiswa';

        return $table
            ->columns([
                TextColumn::make('nama_posisi')
                    ->searchable(),
                TextColumn::make('nama_perusahaan')
                    ->label('Perusahaan'),
                TextColumn::make('divisi')
                    ->searchable(),
                TextColumn::make('kuota')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('lokasi')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('apply')
                    ->label('Ajukan Lamaran')
                    ->visible($isMahasiswa)
                    ->requiresConfirmation()
                    ->form([
                        TextInput::make('nim')
                            ->label('NIM')
                            ->required(),
                        TextInput::make('jurusan')
                            ->label('Jurusan')
                            ->required(),
                        TextInput::make('semester')
                            ->label('Semester')
                            ->numeric()
                            ->required(),
                        TextInput::make('no_hp')
                            ->label('No HP')
                            ->required(),
                        TextInput::make('cv')
                            ->label('CV')
                            ->helperText('Opsional, isi nama file atau link CV.'),
                    ])
                    ->action(function (Lowongan $record, array $data): void {
                        if ($record->status !== 'dibuka') {
                            Notification::make()
                                ->danger()
                                ->title('Lowongan sedang ditutup')
                                ->send();

                            return;
                        }

                        $pendaftar = Pendaftar::updateOrCreate(
                            ['user_id' => Auth::id()],
                            [
                                'nim' => $data['nim'],
                                'jurusan' => $data['jurusan'],
                                'semester' => $data['semester'],
                                'no_hp' => $data['no_hp'],
                                'cv' => $data['cv'] ?? null,
                            ],
                        );

                        $alreadyApplied = Lamaran::query()
                            ->where('pendaftar_id', $pendaftar->id)
                            ->where('lowongan_id', $record->id)
                            ->exists();

                        if ($alreadyApplied) {
                            Notification::make()
                                ->warning()
                                ->title('Kamu sudah melamar lowongan ini')
                                ->send();

                            return;
                        }

                        Lamaran::create([
                            'pendaftar_id' => $pendaftar->id,
                            'lowongan_id' => $record->id,
                            'tanggal_lamaran' => now()->toDateString(),
                            'status' => 'pending',
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Lamaran berhasil dikirim')
                            ->body('Menunggu review dari admin.')
                            ->send();
                    }),
                EditAction::make()
                    ->visible($isAdmin),
            ])
            ->toolbarActions($isAdmin ? [
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ] : []);
    }
}
