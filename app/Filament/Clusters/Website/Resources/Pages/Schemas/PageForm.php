<?php

namespace Modules\Website\Filament\Clusters\Website\Resources\Pages\Schemas;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Modules\Website\Classes\Support\ThemeManager;
use Modules\Website\Enums\PageType;
use Modules\Website\Enums\SectionType;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('Page'))
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug((string) $state))),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                    Select::make('type')
                        ->options(collect(PageType::cases())->mapWithKeys(
                            fn (PageType $type) => [$type->value => str($type->name)->headline()]
                        ))
                        ->required(),
                    Select::make('layout')
                        ->label(__('Theme layout'))
                        ->helperText(__('Visual shell from the active theme. Leave empty to use the theme default (e.g. ClinicalMaster Home).'))
                        ->options(fn (): array => app(ThemeManager::class)->layouts())
                        ->searchable()
                        ->nullable()
                        ->visible(fn (): bool => app(ThemeManager::class)->layouts() !== []),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Toggle::make('is_published')->default(false),
                    TextInput::make('meta_title')->maxLength(255),
                    Textarea::make('meta_description')->rows(2)->columnSpanFull(),
                    TextInput::make('og_image')->maxLength(255)->columnSpanFull(),
                ]),
            Section::make(__('Sections'))
                ->schema([
                    Builder::make('sections')
                        ->reorderable('sort_order')
                        ->collapsible()
                        ->blocks([
                            Block::make(SectionType::Hero->value)
                                ->label('Hero')
                                ->schema([
                                    Group::make()
                                        ->statePath('payload')
                                        ->schema([
                                            TextInput::make('heading')->maxLength(255),
                                            TextInput::make('subheading')->maxLength(255),
                                            TextInput::make('image')->maxLength(255),
                                            TextInput::make('cta_label')->maxLength(255),
                                            TextInput::make('cta_url')->maxLength(255),
                                        ]),
                                    Toggle::make('is_visible')->default(true),
                                ]),
                            Block::make(SectionType::RichText->value)
                                ->label('Rich text')
                                ->schema([
                                    Group::make()
                                        ->statePath('payload')
                                        ->schema([
                                            TextInput::make('heading')->maxLength(255),
                                            Textarea::make('body')->rows(5)->columnSpanFull(),
                                        ]),
                                    Toggle::make('is_visible')->default(true),
                                ]),
                            Block::make(SectionType::Stats->value)
                                ->label('Stats')
                                ->schema(self::simplePayloadBlock()),
                            Block::make(SectionType::ServicesGrid->value)
                                ->label('Services')
                                ->schema(self::simplePayloadBlock()),
                            Block::make(SectionType::TeamGrid->value)
                                ->label('Team')
                                ->schema(self::simplePayloadBlock()),
                            Block::make(SectionType::PartnersLogo->value)
                                ->label('Partners')
                                ->schema(self::simplePayloadBlock()),
                            Block::make(SectionType::Gallery->value)
                                ->label('Gallery')
                                ->schema(self::simplePayloadBlock()),
                            Block::make(SectionType::Faq->value)
                                ->label('FAQ')
                                ->schema(self::simplePayloadBlock()),
                            Block::make(SectionType::Cta->value)
                                ->label('CTA')
                                ->schema([
                                    Group::make()
                                        ->statePath('payload')
                                        ->schema([
                                            TextInput::make('heading')->maxLength(255),
                                            Textarea::make('body')->rows(3),
                                            TextInput::make('cta_label')->maxLength(255),
                                            TextInput::make('cta_url')->maxLength(255),
                                        ]),
                                    Toggle::make('is_visible')->default(true),
                                ]),
                            Block::make(SectionType::ContactForm->value)
                                ->label('Contact form')
                                ->schema(self::simplePayloadBlock()),
                            Block::make(SectionType::NewsTeaser->value)
                                ->label('News teaser')
                                ->schema(self::simplePayloadBlock()),
                        ])
                        ->blockNumbers(false),
                ]),
        ]);
    }

    /**
     * @return list<Component|Field>
     */
    public static function simplePayloadBlock(): array
    {
        return [
            Group::make()
                ->statePath('payload')
                ->schema([
                    TextInput::make('heading')->maxLength(255),
                    Textarea::make('body')->rows(4)->columnSpanFull(),
                ]),
            Toggle::make('is_visible')->default(true),
        ];
    }
}
