<?php
// modules/Core/Providers/Filament/AdminPanelProvider.php
declare(strict_types=1);

namespace Modules\Core\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;

final class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('admin')
            ->login()
            ->profile()
            ->passwordReset()
            ->colors([
                'primary' => '#2563eb',
            ])
            ->brandName('Dorak Admin');

        $this->discoverFromModules($panel, 'Admin');

        return $panel;
    }

    private function discoverFromModules(Panel $panel, string $panelName): void
    {
        $modulesDir = base_path('modules');

        foreach (scandir($modulesDir) as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $base = "{$modulesDir}/{$module}/Filament/Panels/{$panelName}";

            $resourcesDir = "{$base}/Resources";
            if (is_dir($resourcesDir)) {
                $panel->discoverResources(
                    in: $resourcesDir,
                    for: "Modules\\{$module}\\Filament\\Panels\\{$panelName}\\Resources",
                );
            }

            $pagesDir = "{$base}/Pages";
            if (is_dir($pagesDir)) {
                $panel->discoverPages(
                    in: $pagesDir,
                    for: "Modules\\{$module}\\Filament\\Panels\\{$panelName}\\Pages",
                );
            }

            $widgetsDir = "{$base}/Widgets";
            if (is_dir($widgetsDir)) {
                $panel->discoverWidgets(
                    in: $widgetsDir,
                    for: "Modules\\{$module}\\Filament\\Panels\\{$panelName}\\Widgets",
                );
            }
        }
    }
}
