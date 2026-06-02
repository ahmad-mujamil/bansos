<?php

namespace App\Livewire;

use Carbon\CarbonInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithPagination;
use UnitEnum;

/**
 * Generic, reusable list component.
 *
 * Column config shape (per item in $columns):
 *   key            string  field name or dot-relation, e.g. 'jenisBantuan.nama'
 *   label          string  header label
 *   format         string  text|currency|date|datetime|enum|badge_enum|yesno|raw
 *   sortable       bool    enable click-to-sort (only for non-relation keys)
 *   searchable     bool    include in keyword search
 *   sub_key        string  optional secondary key shown below main value
 *   sub_format     string  format for sub_key
 *   badge_color    string  for format=badge static color
 */
class DataList extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $model = '';
    public array $columns = [];
    public array $detailFields = [];
    public array $with = [];
    public array $where = [];

    public string $defaultSortColumn = 'id';
    public string $defaultSortDirection = 'desc';
    public int $perPage = 10;
    public string $title = '';
    public string $searchPlaceholder = 'Cari…';
    public bool $enableDetail = true;
    public bool $showSearch = true;
    public string $detailRoute = '';

    public string $search = '';
    public string $sortColumn = '';
    public string $sortDirection = '';
    public int|string|null $selectedId = null;

    public function mount(): void
    {
        if ($this->detailFields === []) {
            $this->detailFields = $this->columns;
        }
        if ($this->sortColumn === '') {
            $this->sortColumn = $this->defaultSortColumn;
        }
        if ($this->sortDirection === '') {
            $this->sortDirection = $this->defaultSortDirection;
        }
    }

    /**
     * Helper for subclasses to set up the component imperatively.
     */
    protected function configure(array $config): void
    {
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
        if ($this->detailFields === []) {
            $this->detailFields = $this->columns;
        }
        $this->sortColumn = $this->defaultSortColumn;
        $this->sortDirection = $this->defaultSortDirection;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if (! $this->isColumnSortable($column)) {
            return;
        }
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function showDetail(int|string $id): void
    {
        if (! $this->enableDetail || $this->detailRoute !== '') {
            return;
        }
        $this->selectedId = $id;
        $this->dispatch('data-list-detail:show');
    }

    public function closeDetail(): void
    {
        $this->selectedId = null;
        $this->dispatch('data-list-detail:hide');
    }

    protected function isColumnSortable(string $column): bool
    {
        foreach ($this->columns as $col) {
            if (($col['key'] ?? null) === $column) {
                return (bool) ($col['sortable'] ?? false);
            }
        }
        return false;
    }

    protected function buildQuery(): Builder
    {
        /** @var class-string<Model> $model */
        $model = $this->model;
        /** @var Builder $query */
        $query = $model::query();

        if ($this->with !== []) {
            $query->with($this->with);
        }
        foreach ($this->where as $col => $val) {
            if (is_array($val)) {
                $query->whereIn($col, $val);
            } else {
                $query->where($col, $val);
            }
        }
        $this->applySearch($query);
        $this->applyExtraQuery($query);
        $this->applySort($query);

        return $query;
    }

    protected function applySearch(Builder $query): void
    {
        $keyword = trim($this->search);
        if ($keyword === '' || ! $this->showSearch) {
            return;
        }
        $searchables = array_filter($this->columns, fn ($c) => ($c['searchable'] ?? false));
        if ($searchables === []) {
            return;
        }
        $query->where(function (Builder $q) use ($keyword, $searchables) {
            foreach ($searchables as $col) {
                $key = $col['key'];
                if (str_contains($key, '.')) {
                    [$rel, $field] = explode('.', $key, 2);
                    $q->orWhereHas($rel, fn (Builder $sub) => $sub->where($field, 'like', "%{$keyword}%"));
                } else {
                    $q->orWhere($key, 'like', "%{$keyword}%");
                }
            }
        });
    }

    protected function applySort(Builder $query): void
    {
        $column = $this->sortColumn !== '' ? $this->sortColumn : $this->defaultSortColumn;
        $direction = $this->sortDirection !== '' ? $this->sortDirection : $this->defaultSortDirection;

        if ($column === '' || str_contains($column, '.')) {
            return;
        }
        $tableName = (new ($this->model))->getTable();
        $query->orderBy($tableName.'.'.$column, $direction);
    }

    protected function applyExtraQuery(Builder $query): void
    {
        // override in subclass for custom filters
    }

    public function getValue(Model $row, string $key): mixed
    {
        return data_get($row, $key);
    }

    public function formatValue(Model $row, array $column): string
    {
        $main = $this->renderSingle($row, $column['key'] ?? '', $column['format'] ?? 'text', $column);
        if (! empty($column['sub_key'])) {
            $sub = $this->renderSingle($row, $column['sub_key'], $column['sub_format'] ?? 'text', $column);
            if ($sub !== '' && $sub !== '<span class="text-muted">—</span>') {
                return '<div class="fw-semibold">'.$main.'</div><div class="text-muted small mt-1">'.$sub.'</div>';
            }
        }
        return $main;
    }

    protected function renderSingle(Model $row, string $key, string $format, array $column): string
    {
        $value = $this->getValue($row, $key);
        if ($value === null || $value === '') {
            return '<span class="text-muted">—</span>';
        }
        return match ($format) {
            'currency' => 'Rp '.number_format((float) $value, 0, ',', '.'),
            'date' => $value instanceof CarbonInterface
                ? e($value->translatedFormat('d M Y'))
                : e((string) $value),
            'datetime' => $value instanceof CarbonInterface
                ? e($value->translatedFormat('d M Y H:i'))
                : e((string) $value),
            'enum' => $this->renderEnum($value),
            'badge_enum' => $this->renderBadgeEnum($value),
            'badge' => '<span class="badge bg-'.e($column['badge_color'] ?? 'primary').'">'.e((string) $value).'</span>',
            'yesno' => $value
                ? '<span class="text-success">Ya</span>'
                : '<span class="text-danger">Tidak</span>',
            'raw' => (string) $value,
            default => e((string) $value),
        };
    }

    protected function renderEnum(mixed $value): string
    {
        if ($value instanceof UnitEnum && method_exists($value, 'getDescription')) {
            return e($value->getDescription());
        }
        if ($value instanceof UnitEnum) {
            return e($value->name);
        }
        return e((string) $value);
    }

    protected function renderBadgeEnum(mixed $value): string
    {
        if (! ($value instanceof UnitEnum)) {
            return e((string) $value);
        }
        $label = method_exists($value, 'getDescription') ? $value->getDescription() : $value->name;
        $color = method_exists($value, 'badgeColor') ? $value->badgeColor() : 'secondary';
        return '<span class="badge bg-'.e($color).'">'.e($label).'</span>';
    }

    public function render(): View
    {
        $items = $this->buildQuery()->paginate($this->perPage);
        $selectedRow = $this->selectedId !== null && $this->model !== ''
            ? $this->model::with($this->with)->find($this->selectedId)
            : null;

        return view('livewire.data-list', [
            'items' => $items,
            'selectedRow' => $selectedRow,
        ]);
    }
}
