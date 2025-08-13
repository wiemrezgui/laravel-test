<?php
namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    // Configuration de base de la ressource
    protected static ?string $model = Booking::class; // Modèle associé
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days'; // Icône dans la navigation
    protected static ?string $navigationLabel = 'Réservations'; // Libellé

    // Configuration du formulaire de création
    public static function form(Form $form): Form
    {
        return $form->schema([
            // Section principale pour les infos de réservation
            Forms\Components\Section::make('Informations de réservation')->schema([
                // Sélection de l'utilisateur avec recherche
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name') // Relation vers le modèle User
                    ->searchable()
                    ->required(),
                
                // Sélection de la propriété avec recherche
                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'name')
                    ->searchable()
                    ->required(),
                
                // Sélecteurs de dates
                Forms\Components\DatePicker::make('start_date')->required(),
                Forms\Components\DatePicker::make('end_date')->required(),
            ])->columns(2),
            
            // Section pour les détails financiers
            Forms\Components\Section::make('Détails financiers')->schema([
                // Champ prix total avec préfixe €
                Forms\Components\TextInput::make('total_price')
                    ->numeric()
                    ->prefix('€')
                    ->required(),
                
                // Sélecteur de statut avec options prédéfinies
                Forms\Components\Select::make('status')
                    ->options([
                        'PENDING' => 'En attente',
                        'CONFIRMED' => 'Confirmée',
                        'CANCELLED' => 'Annulée',
                    ])
                    ->required()
                    ->default('CONFIRMED'),
            ])->columns(2),
            
            // Section pour les commentaires
            Forms\Components\Section::make('Demandes spéciales')->schema([
                Forms\Components\Textarea::make('special_requests')
                    ->rows(3), // Zone de texte sur 3 lignes
            ]),
        ]);
    }

    // Configuration de la liste
    public static function table(Table $table): Table
    {
        return $table->columns([
            // Colonnes avec différentes fonctionnalités:
            Tables\Columns\TextColumn::make('user.name') // Relation user
                ->searchable()
                ->sortable(),
            
            Tables\Columns\TextColumn::make('property.name')
                ->limit(30), // Limite de caractères
            
            // Colonnes de dates
            Tables\Columns\TextColumn::make('start_date')->date(),
            Tables\Columns\TextColumn::make('end_date')->date(),
            
            // Colonne monétaire
            Tables\Columns\TextColumn::make('total_price')
                ->money('EUR'), // Formatage euro
            
            // Colonne de statut
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'warning' => 'PENDING',
                    'success' => 'CONFIRMED',
                    'danger' => 'CANCELLED',
                ])
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'PENDING' => 'En attente',
                    'CONFIRMED' => 'Confirmée',
                    'CANCELLED' => 'Annulée',
                }),
            
            // Colonne masquée par défaut
            Tables\Columns\TextColumn::make('created_at')
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            // Filtre par statut
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'PENDING' => 'En attente',
                    'CONFIRMED' => 'Confirmée',
                    'CANCELLED' => 'Annulée',
                ]),
            
            // Filtre par date de création
            Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('created_from'),
                    Forms\Components\DatePicker::make('created_until'),
                ])
                ->query(function ($query, array $data) {
                    // Logique de filtrage
                }),
        ])
        ->actions([
            // Actions disponibles sur chaque ligne
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            // Actions groupées
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    // Définition des pages associées
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}