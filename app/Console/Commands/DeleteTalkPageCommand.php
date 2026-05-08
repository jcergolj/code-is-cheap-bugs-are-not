<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

/**
 * Delete a talk page.
 *
 * Usage:
 *   php artisan delete-talk-page
 *
 * Interactive — shows dropdown of existing pages, deletes route and view.
 * Updates adjacent routes' next/previous links.
 */
class DeleteTalkPageCommand extends Command
{
    protected $signature = 'delete-talk-page';

    protected $description = 'Delete a talk page (removes route and view).';

    private string $routeFile;

    public function __construct()
    {
        parent::__construct();
        $this->routeFile = base_path('routes/talk.php');
    }

    public function handle(Filesystem $files): int
    {
        $pages = $this->getExistingPages();

        if (empty($pages)) {
            $this->error('No talk pages found.');

            return self::FAILURE;
        }

        if (count($pages) === 1) {
            $this->error('Cannot delete the only talk page. Create another one first.');

            return self::FAILURE;
        }

        $slug = select(
            label: 'Which page do you want to delete?',
            options: array_keys($pages),
        );

        $this->newLine();
        $this->line("Page: {$slug}");
        $this->newLine();

        if (! confirm(label: 'Delete this page?', default: false)) {
            $this->warn('Aborted.');

            return self::SUCCESS;
        }

        // Remove from array
        unset($pages[$slug]);

        // Rewrite routes
        $this->writeRoutes($files, $pages);

        // Delete view
        $viewPath = resource_path("views/talk/{$slug}.blade.php");
        if ($files->exists($viewPath)) {
            $files->delete($viewPath);
        }

        $this->info("Page '{$slug}' deleted.");

        return self::SUCCESS;
    }

    private function getExistingPages(): array
    {
        if (! file_exists($this->routeFile)) {
            return [];
        }

        $content = file_get_contents($this->routeFile);
        $pages = [];

        preg_match_all(
            "/Route::get\('([^']+)'.*?->name\('talk\.([a-z0-9-]+)'\);/s",
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $slug = $match[2];
            $pages[$slug] = [
                'path' => $match[1],
                'title' => Str::title(str_replace('-', ' ', $slug)),
            ];
        }

        return $pages;
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
}
