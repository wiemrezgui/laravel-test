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
            Forms\Components\Section::make('Informations générales')->schema([
                Forms\Components\TextInput::make('name')->label('Nom')->required()->maxLength(255),
                Forms\Components\Textarea::make('description')->label('Description')->required()->rows(4),
                Forms\Components\TextInput::make('price_per_night')->label('Prix par nuit')->numeric()->prefix('€')->required(),
                Forms\Components\Select::make('status')->label('Statut')->options([
                    'ACTIVE' => 'Actif', 'INACTIVE' => 'Inactif', 'MAINTENANCE' => 'Maintenance',
                ])->required()->default('ACTIVE'),
            ])->columns(2),
            Forms\Components\Section::make('Localisation')->schema([
                Forms\Components\TextInput::make('address')->label('Adresse')->required(),
                Forms\Components\TextInput::make('city')->label('Ville')->required(),
                Forms\Components\TextInput::make('country')->label('Pays')->required(),
            ])->columns(3),
            Forms\Components\Section::make('Détails')->schema([
                Forms\Components\TextInput::make('max_guests')->label('Max invités')->numeric()->required()->default(2),
                Forms\Components\TextInput::make('bedrooms')->label('Chambres')->numeric()->required()->default(1),
                Forms\Components\TextInput::make('bathrooms')->label('SDB')->numeric()->required()->default(1),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Nom')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('city')->label('Ville')->searchable(),
            Tables\Columns\TextColumn::make('price_per_night')->label('Prix/nuit')->money('EUR')->sortable(),
            Tables\Columns\BadgeColumn::make('status')->label('Statut')->colors([
                'success' => 'ACTIVE', 'danger' => 'INACTIVE', 'warning' => 'MAINTENANCE',
            ]),
            Tables\Columns\TextColumn::make('bookings_count')->label('Réservations')->counts('bookings'),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->options([
                'ACTIVE' => 'Actif', 'INACTIVE' => 'Inactif', 'MAINTENANCE' => 'Maintenance'
            ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
