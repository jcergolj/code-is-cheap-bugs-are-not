<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;


class MakeTalkPageCommand extends Command
{
    protected $signature = 'make:talk-page';

    protected $description = 'Create a new talk page (Blade view + hardcoded route).';

    private const BEGINNING = '(beginning)';

    private const END = '(end)';

    private string $routeFile;

    private string $lastPagePath;

    public function __construct()
    {
        parent::__construct();
        $this->routeFile = base_path('routes/talk.php');
        $this->lastPagePath = storage_path('app/last-page.txt');
    }

    public function handle(Filesystem $files): int
    {
        $pages = $this->getExistingPages();
        $existingSlugs = array_keys($pages);

        $title = text(
            label: 'Title',
            required: true,
            transform: fn ($v) => trim($v),
        );

        $slug = text(
            label: 'Slug',
            default: Str::slug($title),
            required: true,
            validate: function ($value) use ($existingSlugs) {
                if (! preg_match('/^[a-z0-9-]+$/', $value)) {
                    return 'Slug may contain only lowercase letters, digits, and hyphens.';
                }
                if (in_array($value, $existingSlugs, true)) {
                    return "Page '{$value}' already exists.";
                }

                return null;
            },
        );

        $section = select(
            label: 'Section',
            options: ['content-center', 'content'],
            default: 'content-center',
        );

        $lastAdded = $files->exists($this->lastPagePath) ? trim($files->get($this->lastPagePath)) : '';
        $positionOptions = array_merge([self::BEGINNING], $existingSlugs, [self::END]);
        $positionDefault = ($lastAdded !== '' && in_array($lastAdded, $existingSlugs, true))
            ? $lastAdded
            : self::END;

        $position = select(
            label: 'Insert position (page will be inserted after the selected one)',
            options: $positionOptions,
            default: $positionDefault,
        );

        $this->newLine();
        $this->line("Title:    {$title}");
        $this->line("Slug:     {$slug}");
        $this->line("Section:  {$section}");
        $this->line('Position: '.($position === self::BEGINNING
            ? 'at the beginning'
            : ($position === self::END ? 'at the end' : "after '{$position}'")));
        $this->newLine();

        if (! confirm(label: 'Create page?', default: true)) {
            $this->warn('Aborted.');

            return self::SUCCESS;
        }

        $newPages = $this->insert($pages, $slug, $title, $position);
        $this->writeRoutes($files, $newPages);
        $files->put(resource_path("views/talk/{$slug}.blade.php"), $this->renderView($section));
        $files->put($this->lastPagePath, $slug);

        $this->info("Page '{$slug}' created.");

        return self::SUCCESS;
    }

    private function getExistingPages(): array
    {
        if (! file_exists($this->routeFile)) {
            return [];
        }

        $content = file_get_contents($this->routeFile);
        $pages = [];

        // Match Route::get('/path', ...)->name('talk.slug');
        preg_match_all(
            "/Route::get\('([^']+)'.*?->name\('talk\.([a-z0-9-]+)'\);/s",
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $path = $match[1];
            $slug = $match[2];
            // Extract title from view name or use slug as fallback
            $title = $this->extractTitleFromRoute($content, $slug) ?? Str::title(str_replace('-', ' ', $slug));
            $pages[$slug] = [
                'path' => $path,
                'title' => $title,
            ];
        }

        return $pages;
    }

    private function extractTitleFromRoute(string $content, string $slug): ?string
    {
        // Try to find the view file and extract title from <x-title> tag
        $viewPath = resource_path("views/talk/{$slug}.blade.php");
        if (! file_exists($viewPath)) {
            return null;
        }

        $viewContent = file_get_contents($viewPath);
        if (preg_match('/<x-title>(.*?)<\/x-title>/s', $viewContent, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function insert(array $pages, string $slug, string $title, string $position): array
    {
        $newEntry = [$slug => ['path' => "/{$slug}", 'title' => $title]];

        if ($position === self::BEGINNING) {
            return $newEntry + $pages;
        }

        if ($position === self::END) {
            return $pages + $newEntry;
        }

        $keys = array_keys($pages);
        $i = array_search($position, $keys, true);
        $before = array_slice($pages, 0, $i + 1, true);
        $after = array_slice($pages, $i + 1, null, true);

        return $before + $newEntry + $after;
    }

    private function writeRoutes(Filesystem $files, array $pages): void
    {
        $lines = ["<?php\n"];
        $slugs = array_keys($pages);

        foreach ($pages as $slug => $page) {
            $path = $page['path'];
            $i = array_search($slug, $slugs, true);
            $prevSlug = $i > 0 ? $slugs[$i - 1] : null;
            $nextSlug = $i < count($slugs) - 1 ? $slugs[$i + 1] : null;

            $params = [];
            if ($nextSlug !== null) {
                $params[] = "'next' => route('talk.{$nextSlug}')";
            }
            if ($prevSlug !== null) {
                $params[] = "'previous' => route('talk.{$prevSlug}')";
            }

            $paramsStr = empty($params) ? '' : ', ['.implode(', ', $params).']';

            $lines[] = "Route::get('{$path}', function () {";
            $lines[] = "    return view('talk/{$slug}'{$paramsStr});";
            $lines[] = "})->name('talk.{$slug}');";
            $lines[] = '';
        }

        $files->put($this->routeFile, implode("\n", $lines));
    }

    private function renderView(string $section): string
    {
        return <<<BLADE
@extends('layouts.talk-app')

@section('{$section}')
    <x-title>title goes here</x-title>
@endsection

BLADE;
    }
}
