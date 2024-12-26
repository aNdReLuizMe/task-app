<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static bool $hasTitleCaseModelLabel = false;
    protected static ?string $slug = 'gestao-usuarios';
    protected static ?string $navigationGroup = 'Gestão';
    protected static ?string $navigationLabel = 'Usuários';
    protected static ?string $pluralModelLabel = 'Usuários cadastrados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::inputForm());
    }

    public static function inputForm(): array
    {
        return [
            Section::make()->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(table: 'users', column: 'email', ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->confirmed()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->maxLength(255)
                    ->revealable()
                    ->visible(fn($operation) => $operation !== 'view'),
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirme a senha')
                    ->password()
                    ->requiredWith('password')
                    ->dehydrated(false)
                    ->revealable()
                    ->visible(fn($operation) => $operation !== 'view'),
                Forms\Components\Toggle::make('active')
                    ->label(fn(Get $get) => $get('active') ? 'Usuário ativo' : 'Usuário inativo')
                    ->live()
                    ->default(true)
                    ->required(),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Nível de acesso')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel()
                    ->tooltip('Visualizar')
                    ->modalHeading('Visualizar Usuário')
                    ->authorize(true),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->tooltip('Editar')
                    ->modalHeading('Editar Usuário')
                    ->authorize(true),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
