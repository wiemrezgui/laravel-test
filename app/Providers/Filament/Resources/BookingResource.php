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
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Réservations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informations de réservation')->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Utilisateur')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('property_id')
                    ->label('Propriété')
                    ->relationship('property', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Date d\'arrivée')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Date de départ')
                    ->required(),
            ])->columns(2),
            Forms\Components\Section::make('Détails financiers')->schema([
                Forms\Components\TextInput::make('total_price')
                    ->label('Prix total')
                    ->numeric()
                    ->prefix('€')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        'PENDING' => 'En attente',
                        'CONFIRMED' => 'Confirmée',
                        'CANCELLED' => 'Annulée',
                    ])
                    ->required()
                    ->default('CONFIRMED'),
            ])->columns(2),
            Forms\Components\Section::make('Demandes spéciales')->schema([
                Forms\Components\Textarea::make('special_requests')
                    ->label('Demandes spéciales')
                    ->rows(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('user.name')
                ->label('Client')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('property.name')
                ->label('Propriété')
                ->searchable()
                ->limit(30),
            Tables\Columns\TextColumn::make('start_date')
                ->label('Arrivée')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('end_date')
                ->label('Départ')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('total_price')
                ->label('Prix total')
                ->money('EUR')
                ->sortable(),
            Tables\Columns\BadgeColumn::make('status')
                ->label('Statut')
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
            Tables\Columns\TextColumn::make('created_at')
                ->label('Créé le')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')
                ->label('Statut')
                ->options([
                    'PENDING' => 'En attente',
                    'CONFIRMED' => 'Confirmée',
                    'CANCELLED' => 'Annulée',
                ]),
            Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('created_from')->label('Créé depuis'),
                    Forms\Components\DatePicker::make('created_until')->label('Créé jusqu\'à'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['created_from'], fn ($query) => $query->whereDate('created_at', '>=', $data['created_from']))
                        ->when($data['created_until'], fn ($query) => $query->whereDate('created_at', '<=', $data['created_until']));
                }),
        ])->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

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
