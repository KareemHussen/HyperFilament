<?php

namespace App\Filament\Resources;

use App\Enums\PackageTypeEnum;
use App\Enums\TripStatus;
use App\Filament\Resources\TripResource\Pages;
use App\Models\Area;
use App\Models\City;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Component as Livewire;
use function PHPUnit\Framework\isNull;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Select::make('company_id')
                    ->label('Company')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->options(Company::pluck('name', 'id'))
                    ->optionsLimit(7)
                    ->afterStateUpdated(function (Forms\Set $set){
                        $set('vehicle_id', null);
                        $set('driver_id', null);
                    }),


                Forms\Components\ToggleButtons::make('status')
                    ->inline()
                    ->options(TripStatus::class)
                    ->required()
                    ->reactive()
                    ->default(0),
                
                Select::make('vehicle_id')
                    ->label('Vehicle')
                    ->required()
                    ->searchable()
                    ->live()
                    ->placeholder('Select Vehicle')
                    ->options(function (Forms\Get $get) {
                        return Vehicle::where('company_id', $get('company_id'))->pluck('name', 'id');
                    })
                    ->disabled(fn (Forms\Get $get) => ! $get('company_id')) 
                    ->optionsLimit(7),



                Select::make('driver_id')
                    ->label('Driver')
                    ->required()
                    ->searchable()
                    ->placeholder('Select Driver')
                    ->options(function (Forms\Get $get) {
                        return Driver::where('company_id', $get('company_id'))->pluck('name', 'id');
                    })
                    ->disabled(fn (Forms\Get $get) => ! $get('company_id'))
                    ->optionsLimit(7),


                Select::make('from_city')
                    ->label('From City')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->dehydrated(false)
                    ->options(City::pluck('name', 'id'))
                    ->optionsLimit(7)
                    ->afterStateUpdated(function (Forms\Set $set){
                        $set('from_area', null);
                    })
                    ->afterStateHydrated(function (Forms\Set $set, Model $record = null) {
                        if ($record && $record->from_area) {
                            $set('from_city', $record->fromArea->city_id);
                        }
                    }),

                Select::make('to_city')
                    ->label('To City')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->dehydrated(false)
                    ->options(City::pluck('name', 'id'))
                    ->optionsLimit(7)
                    ->afterStateUpdated(function (Forms\Set $set){
                        $set('to_area', null);
                    })
                    ->afterStateHydrated(function (Forms\Set $set, Model $record = null) {
                        if ($record && $record->to_area) {
                            $set('to_city', $record->toArea->city_id);
                        }
                    }),

                Select::make('from_area')
                    ->label('From Area')
                    ->required()
                    ->relationship('fromArea' , 'name' , modifyQueryUsing: fn (Builder $query, Forms\Get $get) =>
                            $get('to_city')
                                ? $query->where('city_id', $get('to_city'))
                                : $query
                    )
                    ->searchable()
                    ->placeholder('Select Area')
                    ->disabled(fn (Forms\Get $get) => ! $get('from_city'))
                    ->options(function (Forms\Get $get) {
                        return Area::where('city_id', $get('from_city'))->pluck('name', 'id');
                    })
                    ->optionsLimit(7),
                    
                Select::make('to_area')
                    ->label('To Area')
                    ->required()
                    ->relationship('fromArea' , 'name' , modifyQueryUsing: fn (Builder $query, Forms\Get $get) =>
                            $get('from_city')
                                ? $query->where('city_id', $get('from_city'))
                                : $query
                    )
                    ->searchable()
                    ->placeholder('Select Area')
                    ->disabled(fn (Forms\Get $get) => ! $get('to_city'))
                    ->options(function (Forms\Get $get) {
                        return Area::where('city_id', $get('to_city'))->pluck('name', 'id');
                    })
                    ->optionsLimit(7),


                DateTimePicker::make('start_date')
                    ->label('Start Date & Time')
                    ->required()
                    ->before('end_date'),

                DateTimePicker::make('end_date')
                    ->label('End Date & Time')
                    ->required()
                    ->after('start_date'),


                Forms\Components\Card::make('Packages Information')
                    ->schema([

                        Forms\Components\Grid::make(1)->schema([

                            Forms\Components\Repeater::make('packages')
                                ->relationship('packages')
                                ->defaultItems(1)
                                ->columns(2)
                                ->minItems(1)
                                ->addActionLabel('Add New Package')
                                ->schema([

                                    Forms\Components\Select::make('type')
                                        ->label('Package Type')
                                        ->reactive()
                                        ->options( PackageTypeEnum::class)
                                        ->required(),

                                    Forms\Components\TextInput::make('description')
                                        ->label('Description')
                                        ->reactive()
                                        ->required(fn ($get) => $get('type') == 'Other'),

                                    Forms\Components\TextInput::make('weight')
                                        ->label('Weight')
                                        ->reactive()
                                        ->required()
                                        ->numeric()
                                        ->afterStateUpdated(fn ($state, $set, $get) => $set('package_weight', ($get('weight') ?: 0) * ($get('quantity') ?: 0)))
                                        ->rules([
                                            fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {

                                                $vehicleWeight = Vehicle::find($get('../../vehicle_id'))->weight ?? 0;
                                                $totalPackageWeight = collect($get('../../packages') ?: [])->sum(fn ($package) => ($package['weight'] ?: 0) * ($package['quantity'] ?: 0));
                                                $availableWeight = $vehicleWeight - $totalPackageWeight;

                                                if ($availableWeight < 0) {
                                                    $fail('Vehicle Weight is not enough');
                                                }
                                            },
                                        ]),

                                    Forms\Components\TextInput::make('length')
                                        ->label('Length')
                                        ->numeric()
                                        ->required(),

                                    Forms\Components\TextInput::make('width')
                                        ->label('Width')
                                        ->numeric()
                                        ->required(),

                                    Forms\Components\TextInput::make('height')
                                        ->label('Height')
                                        ->numeric()
                                        ->required(),

                                    Forms\Components\TextInput::make('quantity')
                                        ->label('Quantity')
                                        ->reactive()
                                        ->required()
                                        ->integer()
                                        ->afterStateUpdated(fn ($state, $set, $get) => $set('package_weight', ($get('weight') ?: 0) * ($get('quantity') ?: 0))),

                                    Forms\Components\TextInput::make('note')
                                        ->label('Note'),

                                    Forms\Components\Placeholder::make('package_weight')
                                        ->label('Package Weight')
                                        ->content(fn ($get) => new HtmlString('<h1>'.(($get('weight') ?: 0) * ($get('quantity') ?: 0)).'</h1>')),

                                ])->afterStateUpdated(function ($state, $set, $get) {
                                    $vehicleWeight = Vehicle::find($get('vehicle_id'))->weight ?? 0;
                                    $totalPackageWeight = collect($get('packages') ?: [])->sum(fn ($package) => ($package['weight'] ?: 0) * ($package['quantity'] ?: 0));
                                    $availableWeight = $vehicleWeight - $totalPackageWeight;
                                    $set('available_weight', $availableWeight);
                                }),

                            Forms\Components\Placeholder::make('total_weight')
                                ->label('Total Weight of All Packages')
                                ->content(function ($get) {
                                    $totalWeight = collect($get('packages') ?: [])->sum(fn ($package) => ($package['weight'] ?: 0) * ($package['quantity'] ?: 0));

                                    return new HtmlString('<h1>'.$totalWeight.'</h1>');
                                }),

                            Forms\Components\Placeholder::make('available_weight')
                                ->label('Available Weight in Selected Vehicle')
                                ->content(function ($get) {
                                    $vehicleWeight = Vehicle::find($get('vehicle_id'))->weight ?? 0;
                                    $totalPackageWeight = collect($get('packages') ?: [])->sum(fn ($package) => ($package['weight'] ?: 0) * ($package['quantity'] ?: 0));
                                    $availableWeight = $vehicleWeight - $totalPackageWeight;

                                    return new HtmlString('<h1>'.$availableWeight.'</h1>');
                                })
                                ,

                        ]),

                    ]),
                
                ]);
        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')
                    ->options(TripStatus::class)
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }
}
