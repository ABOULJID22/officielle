<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'xl' => 2,
            ])
            ->components([
                Section::make(__('postForm.section.content.title'))
                    ->icon('heroicon-o-language')
                    ->description(__('postForm.section.content.description'))
                    ->columnSpan(['xl' => 2])
                    ->schema([
                        Repeater::make('translations')
                            ->relationship('translations')
                            ->label(__('postForm.repeater.label'))
                            ->createItemButtonLabel(__('postForm.repeater.create_item'))
                            ->minItems(1)
                            ->maxItems(2)
                            ->reorderable(false)
                            ->defaultItems(1)
                            // Force la création d’une entrée française lorsque l’on ouvre le formulaire.
                            ->afterStateHydrated(function (Repeater $component, ?array $state): void {
                                if (empty($state)) {
                                    $component->state([
                                        ['locale' => 'fr'],
                                    ]);
                                }
                            })
                            ->columns(['default' => 1, 'lg' => 2])
                            ->schema([
                                Select::make('locale')
                                    ->label(__('postForm.locale.label'))
                                    ->options([
                                        'fr' => __('translations.lang.fr'),
                                        'en' => __('translations.lang.en'),
                                    ])
                                    ->native(false)
                                    ->required()
                                    ->helperText(__('postForm.locale.helper'))
                                    ->disableOptionWhen(function ($value, callable $get) {
                                        $items = collect($get('../../translations') ?? [])
                                            ->pluck('locale')
                                            ->filter();
                                        $current = $get('locale');

                                        return $items->contains($value) && $current !== $value;
                                    })
                                    ->columnSpan(['lg' => 1]),

                                TextInput::make('title')
                                    ->label(__('postForm.title.label'))
                                    ->placeholder(__('postForm.title.placeholder'))
                                    ->helperText(__('postForm.title.helper'))
                                    ->required()
                                    ->prefixIcon('heroicon-m-document-text')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', Str::slug($state));
                                    })
                                    ->columnSpan(['lg' => 1]),

                                TextInput::make('slug')
                                    ->label(__('postForm.slug.label'))
                                    ->placeholder(__('postForm.slug.placeholder'))
                                    ->helperText(__('postForm.slug.helper'))
                                    ->prefixIcon('heroicon-m-link')
                                    ->rules(['alpha_dash'])
                                    ->required()
                                    ->columnSpan(['lg' => 1]),

                                RichEditor::make('content')
                                    ->label(__('postForm.content.label'))
                                    ->placeholder(__('postForm.content.placeholder'))
                                    ->helperText(__('postForm.content.helper'))
                                    ->toolbarButtons([
                                        ['bold', 'italic', 'underline', 'strike', 'highlight', 'clearFormatting'],
                                        ['h1', 'h2', 'h3', 'lead', 'small'],
                                        ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
                                        ['blockquote', 'code', 'codeBlock', 'bulletList', 'orderedList', 'horizontalRule'],
                                        ['link', 'undo', 'redo'],
                                    ])
                                    ->floatingToolbars([
                                        'paragraph' => ['bold', 'italic', 'underline', 'strike'],
                                        'heading' => ['h1', 'h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                                    ])
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('posts/content')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Visuel')
                    ->icon('heroicon-o-photo')
                    ->description('Optimisez la vignette pour les partages et la liste d’articles.')
                    ->columns(['default' => 1, 'lg' => 2])
                    ->collapsible()
                    ->collapsed()
                    ->columnSpan(['xl' => 1])
                    ->schema([
                                FileUpload::make('cover_image')
                            ->label(__('postForm.cover_image.label'))
                            ->helperText(__('postForm.cover_image.helper'))
                            ->default(null)
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '21:9',
                                '16:9',
                                '4:3',
                            ])
                            ->visibility('public')
                            ->disk('public')
                            ->directory('cover_image')
                            ->columnSpan(['lg' => 2]),
                    ]),

                Section::make('Publication')
                    ->icon('heroicon-o-calendar-days')
                    ->description('Paramétrez la diffusion de l’article depuis un même endroit.')
                    ->columns(['default' => 1, 'lg' => 2])
                    ->columnSpan(['xl' => 1])
                    ->schema([
                        Select::make('category_id')
                            ->label(__('postForm.category.label'))
                            // Load options manually so we can show the translated name
                            ->options(fn () => \App\Models\Category::query()
                                ->with('translations')
                                ->get()
                                ->mapWithKeys(fn ($c) => [
                                    $c->id => ($c->translation(app()->getLocale())?->name) ?: $c->name,
                                ])
                            )
                            ->searchable()
                            ->preload()
                            ->placeholder(__('postForm.category.placeholder'))
                            ->helperText(__('postForm.category.helper'))
                            ->columnSpan(['lg' => 1]),

                        Select::make('status')
                            ->label(__('postForm.status.label'))
                            ->options([
                                'draft' => __('posts.status.draft'),
                                'scheduled' => __('posts.status.scheduled'),
                                'published' => __('posts.status.published'),
                            ])
                            ->default('published')
                            ->required()
                            ->native(false)
                            ->columnSpan(['lg' => 1]),

                        DateTimePicker::make('published_at')
                            ->label('Date de publication')
                            ->seconds(false)
                            ->native(false)
                            ->placeholder('Définir une date de mise en ligne')
                            ->helperText('Laisser vide pour publier immédiatement.')
                            ->required()
                            ->columnSpan(['lg' => 1]),

                        TextInput::make('reading_time')
                            ->label('Temps de lecture (min)')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->suffix('min')
                            ->placeholder('Calcul automatique si non renseigné')
                            ->helperText('Indiquez une valeur uniquement si vous souhaitez remplacer le calcul automatique.')
                            ->default(null)
                            ->columnSpan(['lg' => 1]),
                    ]),
            ]);
    }
}
