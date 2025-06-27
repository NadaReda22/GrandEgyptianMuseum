<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Artifact;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ArtifactResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ArtifactResource\RelationManagers;

class ArtifactResource extends Resource
{
    protected static ?string $model = Artifact::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('name')->required()->maxLength(255),
            Textarea::make('description')->required(),
FileUpload::make('image')
    ->directory('uploads/artifacts') // don't start with '/'
    ->preserveFilenames()
    ->image()
    ->visibility('public')
,

            Select::make('location')
                ->options([
                    'egypt' => 'Egypt',
                    'outside' => 'Outside',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('name')->searchable(),
        // ImageColumn::make('image')
        //     ->label('Artifact Image')
        //     ->circular()
        //     ->height(60)
        //     ->url(null) // disables clickable link
        //     ->getStateUsing(fn ($record) => asset($record->image)),
        // prevents adding `/storage` prefix

        ImageColumn::make('image')
            ->label('Artifact Image')
            ->getStateUsing(fn ($record) => asset('storage/' . $record->image))
            ->circular()
            ->height(60),

    // ✅ show correct preview
    TextColumn::make('location')->badge()->color(fn (string $state): string => $state === 'egypt' ? 'success' : 'warning'),
    TextColumn::make('created_at')->dateTime('d M Y'),
        ])
        ->filters([
            SelectFilter::make('location')
                ->options([
                    'egypt' => 'Egypt',
                    'outside' => 'Outside',
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArtifacts::route('/'),
            'create' => Pages\CreateArtifact::route('/create'),
            'edit' => Pages\EditArtifact::route('/{record}/edit'),
        ];
    }
    
        public static function getNavigationGroup(): ?string
    {
        return 'Museum Management';
    }
}
