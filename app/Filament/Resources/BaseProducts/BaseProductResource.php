<?php

namespace App\Filament\Resources\BaseProducts;

use App\Filament\Resources\BaseProducts\Pages\CreateBaseProduct;
use App\Filament\Resources\BaseProducts\Pages\EditBaseProduct;
use App\Filament\Resources\BaseProducts\Pages\ListBaseProducts;
use App\Filament\Resources\BaseProducts\Pages\ViewBaseProduct;
use App\Filament\Resources\BaseProducts\Schemas\BaseProductForm;
use App\Filament\Resources\BaseProducts\Schemas\BaseProductInfolist;
use App\Filament\Resources\BaseProducts\Tables\BaseProductsTable;
use App\Models\BaseProduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BaseProductResource extends Resource
{
    protected static ?string $model = BaseProduct::class;

    protected static ?string $modelLabel = 'محصول';
    protected static ?string $pluralModelLabel = 'محصولات';
    protected static ?int $navigationSort = 5;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return BaseProductForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BaseProductInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BaseProductsTable::configure($table);
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
            'index' => ListBaseProducts::route('/'),
            'create' => CreateBaseProduct::route('/create'),
            'view' => ViewBaseProduct::route('/{record}'),
            'edit' => EditBaseProduct::route('/{record}/edit'),
        ];
    }
}
