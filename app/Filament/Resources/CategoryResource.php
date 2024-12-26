<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static bool $hasTitleCaseModelLabel = false;
    protected static ?string $slug = 'gestao-categorias';
    protected static ?string $navigationGroup = 'Gestão';
    protected static ?string $navigationLabel = 'Categorias';
    protected static ?string $pluralModelLabel = 'Categorias cadastradas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel()
                    ->tooltip('Visualizar')
                    ->modalHeading('Visualizar Categoria')
                    ->authorize(true),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->tooltip('Editar')
                    ->modalHeading('Editar Categoria')
                    ->authorize(true),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->tooltip('Excluir')
                    ->modalHeading('Excluir'),
                // ->visible(function (User $record): bool {
                //     // Só permite exclusão se o usuário atual for Super Admin ou se o usuário sendo excluído não for Super Admin
                //     return self::isSuperAdmin() || !self::hasSuperAdminRole($record);
                // }),
                Tables\Actions\RestoreAction::make()
                    ->hiddenLabel()
                    ->tooltip('Restaurar')
                    ->modalHeading('Restaurar'),
                // ->visible(function (User $record): bool {
                //     return self::isSuperAdmin() || !self::hasSuperAdminRole($record);
                // }),
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
            'index' => Pages\ListCategories::route('/'),
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
