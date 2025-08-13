<?php
namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Propriétés';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Section pour les infos générales
            Forms\Components\Section::make('Informations générales')->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\Textarea::make('description')->rows(4),
                Forms\Components\TextInput::make('price_per_night')
                    ->numeric()
                    ->prefix('€'),
                Forms\Components\Select::make('status')
                    ->options([
                        'ACTIVE' => 'Actif', 
                        'INACTIVE' => 'Inactif', 
                        'MAINTENANCE' => 'Maintenance',
                    ])
                    ->default('ACTIVE'),
            ])->columns(2),
            
            // Section pour la localisation
            Forms\Components\Section::make('Localisation')->schema([
                Forms\Components\TextInput::make('address'),
                Forms\Components\TextInput::make('city'),
                Forms\Components\TextInput::make('country'),
            ])->columns(3),
            
            // Section pour les détails
            Forms\Components\Section::make('Détails')->schema([
                Forms\Components\TextInput::make('max_guests')
                    ->numeric()
                    ->default(2),
                Forms\Components\TextInput::make('bedrooms')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('bathrooms')
                    ->numeric()
                    ->default(1),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('city')
                ->searchable(),
            Tables\Columns\TextColumn::make('price_per_night')
                ->money('EUR'),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'success' => 'ACTIVE', 
                    'danger' => 'INACTIVE', 
                    'warning' => 'MAINTENANCE',
                ]),
            Tables\Columns\TextColumn::make('bookings_count')
                ->counts('bookings'), // Compte les réservations
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'ACTIVE' => 'Actif', 
                    'INACTIVE' => 'Inactif', 
                    'MAINTENANCE' => 'Maintenance'
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(), // Action supplémentaire de suppression
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}