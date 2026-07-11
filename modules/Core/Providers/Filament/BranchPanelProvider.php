<?php

// modules/Core/Providers/Filament/BranchPanelProvider.php
declare(strict_types=1);

namespace Modules\Core\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Modules\Core\Http\Middleware\ScopePanelToCurrentUser;

final class BranchPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->id('branch')
            ->path('branch')
            ->authGuard('branch')
            ->login()
            ->passwordReset()
            ->colors([
                'primary' => '#7c3aed',
            ])
            ->brandName('Dorak Branch')
            ->middleware([
                ScopePanelToCurrentUser::class,
            ]);

        $this->discoverFromModules($panel, 'Branch');

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
