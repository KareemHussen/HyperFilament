<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Rules\EgyptionPhoneNumberRule;
use App\Rules\LicenseNumberRule;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class DriversRelationManager extends RelationManager
{
    protected static string $relationship = 'drivers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->unique(ignoreRecord: true)->required(),
                TextInput::make('phone')->unique(ignoreRecord: true)->rule(new EgyptionPhoneNumberRule)->required(),
                TextInput::make('license_number')->unique(ignoreRecord: true)->rule(new LicenseNumberRule)->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('license_number')->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
