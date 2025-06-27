<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\BookingResource\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('phone')
                ->tel()
                ->required()
                ->maxLength(255),

            TextInput::make('national_id')
                ->required()
                ->maxLength(255),

            TextInput::make('nationality')
                ->required()
                ->maxLength(255),

            Select::make('ticket_type')
                ->options([
                    'entry' => 'Entry Ticket',
                    'guided' => 'Guided Tour',
                ])
                ->required(),

            TextInput::make('quantity')
                ->numeric()
                ->default(1)
                ->minValue(1)
                ->required(),

            Select::make('payment_method')
                ->options([
                    'pay_now' => 'Pay Now',
                    'pay_museum' => 'Pay at Museum',
                ])
                ->required(),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),

            TextInput::make('amount')
                ->numeric()
                ->step(0.01)
                ->required()
                ->default(200.00),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('phone')->toggleable(),
                TextColumn::make('ticket_type')->badge(),
                TextColumn::make('quantity'),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'paid',
                        'danger' => 'cancelled',
                    ]),
                TextColumn::make('payment_method'),
                TextColumn::make('amount')->money('EGP'),
                TextColumn::make('created_at')->dateTime('d M Y - H:i'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),

                SelectFilter::make('ticket_type')
                    ->options([
                        'entry' => 'Entry Ticket',
                        'guided' => 'Guided Tour',
                    ]),

                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'pay_now' => 'Pay Now',
                        'pay_museum' => 'Pay at Museum',
                    ]),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Museum Management';
    }
}
