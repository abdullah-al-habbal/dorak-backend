<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\JobPostingResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\JobPosting\Models\JobPostingModel;

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPostingModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $recordTitleAttribute = 'title.en';

    protected static ?string $slug = 'job-postings';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function form(Form $form): Form
    {
        return JobPostingFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return JobPostingsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return JobPostingInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobPostingsPage::route('/'),
            'create' => Pages\CreateJobPostingPage::route('/create'),
            'edit' => Pages\EditJobPostingPage::route('/{record}/edit'),
            'view' => Pages\ViewJobPostingPage::route('/{record}'),
        ];
    }
}
