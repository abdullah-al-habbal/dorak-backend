<?php

declare(strict_types=1);

namespace Modules\Onboarding\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Onboarding\Http\Requests\Shared\GetOnboardingConfigRequest;
use Modules\Onboarding\Models\OnboardingConfigurationModel;

final class GetOnboardingConfigAction extends BaseApiAction
{
    public function __invoke(GetOnboardingConfigRequest $request): JsonResponse
    {
        $locale = $request->getLocale();
        $season = $request->getSeason();

        $config = OnboardingConfigurationModel::query()
            ->where('locale', $locale)
            ->where('is_active', true)
            ->orderByRaw('CASE WHEN season = ? THEN 0 ELSE 1 END', [$season])
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($config === null) {
            $config = OnboardingConfigurationModel::query()
                ->where('is_active', true)
                ->orderBy('sort_order', 'desc')
                ->first();
        }

        if ($config === null) {
            return $this->notFound();
        }

        return $this->ok(data: [
            'hero_image_url' => Storage::disk('public')->url($config->hero_image_path),
            'season' => $config->season,
            'locale' => $config->locale,
        ]);
    }
}
