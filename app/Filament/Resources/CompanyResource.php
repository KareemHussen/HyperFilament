<?php

namespace App\Filament\Resources;

use App\Enums\IndustryEnum;
use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use App\Rules\EgyptionPhoneNumberRule;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->minLength(3)->maxLength(255)->required(),
                Select::make('industry')
                    ->options(IndustryEnum::class)
                    ->required()
                    ->reactive()
                    ->default(0),
                    
                TextInput::make('address')->minLength(3)->maxLength(255)->required(),
                TextInput::make('phone')->rule(new EgyptionPhoneNumberRule),
                TextInput::make('email')->email()->minLength(3)->maxLength(255)->required(),
                TextInput::make('website')->url(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('industry')->getStateUsing(function ($record) {
                    return $record->industry ?? 'N/A';
                })->sortable(),
                TextColumn::make('phone')->searchable()->getStateUsing(function ($record) {
                    return $record->phone ?? 'N/A';
                })->toggleable(),
                TextColumn::make('email')->searchable()->toggleable(),
                TextColumn::make('address')->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('industry')->label('Industry')
                    ->options(IndustryEnum::class)
                    ->multiple(),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\DriversRelationManager::class,
            RelationManagers\VehiclesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
