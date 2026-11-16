<?php

namespace App\Filament\Resources\Producers\RelationManagers;

use App\Filament\Support\Fields;
use App\Models\Product;
use App\Support\Icons;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $title = 'Produits';

    protected static ?string $modelLabel = 'produit';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nom')->required()->maxLength(120)->columnSpanFull(),
                Toggle::make('is_amount')->label('Montant d\'engagement plutôt qu\'un produit')
                    ->helperText('Par exemple « Engagement 50 € » : le prix est le total pour la saison, quels que soient les produits livrés.')
                    ->live()->columnSpanFull(),
                Fields::money('price_cents', 'Prix')
                    ->label(fn (Get $get) => $get('is_amount') ? 'Montant pour la saison' : 'Prix par livraison'),
                TextInput::make('unit')->label('Unité')->placeholder('panier, pièce, kg, litre…')->required()->default('panier')
                    ->hidden(fn (Get $get) => $get('is_amount'))->dehydrateStateUsing(fn ($state, Get $get) => $get('is_amount') ? 'engagement' : $state),

                Section::make('Icône dans le calendrier')
                    ->description('Chaque produit a sa propre icône. Choisissez une couleur pour distinguer deux producteurs : une chèvre noire, une chèvre blanche…')
                    ->columns(2)->columnSpanFull()
                    ->schema([
                        Select::make('icon_color')->label('Couleur')->options(Icons::colorOptions())->allowHtml()
                            ->default('vert')->required()->live(),
                        Select::make('icon')->label('Icône')->searchable()->allowHtml()->required()->live()
                            ->options(Icons::groupedOptions())
                            ->extraAttributes(fn (Get $get) => ['style' => Icons::cssVariables($get('icon_color'))])
                            ->getSearchResultsUsing(fn (string $search) => collect(Icons::groupedOptions())
                                ->flatMap(fn ($icons) => $icons)
                                ->filter(fn ($html, $key) => str_contains(Str::lower(Str::ascii(Icons::label($key))), Str::lower(Str::ascii($search))))
                                ->all())
                            ->helperText(fn (Get $get, ?Product $record) => self::usedColorsHint($get('icon'), $record))
                            ->rule(fn (Get $get, ?Product $record) => function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                                $taken = self::takenBy($value, $get('icon_color'), $record);
                                if ($taken) {
                                    $fail("Cette icône est déjà utilisée dans cette couleur par {$taken->producer->name} ({$taken->name}). Choisissez une autre couleur.");
                                }
                            }),
                        TextInput::make('category_label')->label('Type de produit (légende du calendrier)')
                            ->placeholder('Fromage de chèvre')->maxLength(60)->columnSpanFull(),
                        Placeholder::make('apercu')->label('Aperçu')
                            ->content(fn (Get $get) => new HtmlString(Icons::badge($get('icon'), $get('icon_color'), '3rem'))),
                    ]),

                FileUpload::make('photo')->label('Photo du produit')
                    ->helperText('Remplace l\'icône sur la fiche et l\'écran de souscription. Le calendrier garde l\'icône.')
                    ->image()->imageEditor()->disk('public')->directory('produits')->maxSize(8192)->columnSpanFull(),
                TextInput::make('sort')->label('Ordre d\'affichage')->numeric()->default(0),
                Textarea::make('description')->label('Description')->rows(2)->columnSpanFull(),
                Toggle::make('is_active')->label('Proposé actuellement')->default(true),
            ]);
    }

    /** Produit d'un autre producteur qui a déjà cette icône dans cette couleur. */
    public static function takenBy(?string $icon, ?string $color, ?Product $record): ?Product
    {
        if (! $icon) {
            return null;
        }

        return Product::with('producer')->where('icon', $icon)->where('icon_color', $color)
            ->when($record?->producer_id, fn ($q, $producerId) => $q->where('producer_id', '!=', $producerId))
            ->first();
    }

    private static function usedColorsHint(?string $icon, ?Product $record): ?string
    {
        if (! $icon) {
            return 'Tapez pour chercher : chèvre, pain, pomme…';
        }
        $used = Product::with('producer')->where('icon', $icon)
            ->when($record?->producer_id, fn ($q, $producerId) => $q->where('producer_id', '!=', $producerId))
            ->get()->map(fn (Product $p) => Icons::colorLabel($p->icon_color).' ('.$p->producer->name.')')->unique();

        return $used->isEmpty() ? 'Icône libre.' : 'Déjà utilisée en : '.$used->join(', ').'.';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                TextColumn::make('icon')->label('')->html()
                    ->formatStateUsing(fn (Product $p) => $p->photo ? '<img src="'.e($p->photoUrl()).'" style="width:2.5rem;height:2.5rem;border-radius:9999px;object-fit:cover" alt="">' : $p->iconBadge('2.5rem')),
                TextColumn::make('name')->label('Produit')->searchable()->description(fn (Product $p) => $p->description),
                TextColumn::make('price_cents')->label('Prix')->money('EUR', divideBy: 100, locale: 'fr')
                    ->description(fn (Product $p) => $p->is_amount ? 'pour la saison' : 'par '.$p->unit),
                IconColumn::make('is_active')->label('Proposé')->boolean(),
            ])
            ->headerActions([
                CreateAction::make()->label('Ajouter un produit'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('dupliquer')->label('Dupliquer')->icon('heroicon-o-document-duplicate')->color('gray')
                    // La copie garde l'icône du même producteur : la règle d'unicité ne s'applique qu'entre producteurs.
                    ->action(fn (Product $record) => $record->replicate()->fill(['name' => $record->name.' (copie)'])->save()),
                DeleteAction::make(),
            ]);
    }
}
