<?php

namespace App\Filament\Pages;

use App\Models\Worker;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use UnitEnum;

class WorkerRating extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static string|UnitEnum|null $navigationGroup = 'Boshqaruv';

    protected static ?string $navigationLabel = 'Reyting';

    protected static ?string $title = 'Ishchilar reytingi';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.worker-rating';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Worker::query()
                    ->withCount(self::getCachedCountQueries())
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->size('lg')
                    ->weight('bold')
                    ->color(fn ($rowLoop) => match (true) {
                        $rowLoop->iteration === 1 => 'warning',
                        $rowLoop->iteration === 2 => 'gray',
                        $rowLoop->iteration === 3 => 'danger',
                        default => 'default',
                    }),

                ImageColumn::make('user.image')
                    ->label('Rasm')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->user?->name) . '&background=random'),

                TextColumn::make('user.name')
                    ->label('Ism')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(20),

                TextColumn::make('title')
                    ->label('Lavozim')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('total_tasks')
                    ->label('Jami')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('completed_tasks')
                    ->label('Bajarilgan')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('pending_tasks')
                    ->label('Bajarilmagan')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('overdue_tasks')
                    ->label('Muddati o\'tgan')
                    ->sortable()
                    ->badge()
                    ->color('danger'),

                TextColumn::make('completion_rate')
                    ->label('Samaradorlik')
                    ->getStateUsing(fn ($record) => $record->total_tasks > 0
                        ? round(($record->completed_tasks / $record->total_tasks) * 100) . '%'
                        : '0%')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        (int) $state >= 80 => 'success',
                        (int) $state >= 50 => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('title')
                    ->label('Lavozim')
                    ->options(fn () => Worker::distinct()->pluck('title', 'title')->toArray())
                    ->native(false),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->filtersFormColumns(1)
            ->defaultSort('completed_tasks', 'desc')
            ->paginated(false);
    }

    protected static function getCachedCountQueries(): array
    {
        return [
            'tasks as total_tasks',
            'tasks as completed_tasks' => fn (Builder $q) => $q->whereNotNull('completed_at'),
            'tasks as pending_tasks' => fn (Builder $q) => $q->whereNull('completed_at'),
            'tasks as overdue_tasks' => fn (Builder $q) => $q->whereNull('completed_at')->where('deadline_at', '<', now()),
        ];
    }

    public static function getCachedRatingData(): array
    {
        return Cache::remember('worker_rating_data', 3600, function () {
            return Worker::query()
                ->with('user')
                ->withCount([
                    'tasks as total_tasks',
                    'tasks as completed_tasks' => fn (Builder $q) => $q->whereNotNull('completed_at'),
                    'tasks as pending_tasks' => fn (Builder $q) => $q->whereNull('completed_at'),
                    'tasks as overdue_tasks' => fn (Builder $q) => $q->whereNull('completed_at')->where('deadline_at', '<', now()),
                ])
                ->orderByDesc('completed_tasks')
                ->get()
                ->toArray();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('worker_rating_data');
        Cache::forget('worker_rating_stats');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WorkerRatingStats::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            WorkerRatingStats::class,
        ];
    }
}