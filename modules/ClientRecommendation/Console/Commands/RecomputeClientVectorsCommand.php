<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Ai\Embeddings;
use Modules\Client\Models\ClientModel;
use Modules\ClientInteraction\Models\ClientFavoriteModel;
use Modules\ClientRecommendation\Enums\EdgeTypeEnum;
use Modules\ClientRecommendation\Models\ClientPreferenceVectorModel;
use Modules\ClientRecommendation\Models\RecommendationEdgeModel;
use Modules\ClientRecommendation\ValuesObjects\RecommendationFactorWeightsValueObject;

final class RecomputeClientVectorsCommand extends Command
{
    protected $signature = 'recommend:recompute-vectors
        {--client-id= : Recompute a single client by UUID}
        {--force : Skip freshness check}';

    protected $description = 'Recompute client preference vectors from interaction signals';

    public function handle(): int
    {
        $clientId = $this->option('client-id');
        $clientId !== null
            ? $this->info("Recomputing vector for client: {$clientId}")
            : $this->info('Recomputing vectors for all clients with signals...');

        ClientModel::query()
            ->when($clientId, fn ($q, $id) => $q->where('id', $id))
            ->chunk(100, function ($clients): void {
                foreach ($clients as $client) {
                    $this->processClient($client);
                }
            });

        $this->info('Done.');

        return self::SUCCESS;
    }

    private function processClient(ClientModel $client): void
    {
        $signals = $this->gatherSignals($client);

        if (empty($signals)) {
            return;
        }

        $text = implode("\n", $signals);

        try {
            $response = Embeddings::for([$text])
                ->dimensions(1536)
                ->generate();

            $embedding = $response->embeddings[0] ?? null;

            if ($embedding === null) {
                $this->warn("No embedding returned for client: {$client->id}");
                return;
            }

            ClientPreferenceVectorModel::updateOrCreate(
                ['client_id' => $client->id],
                [
                    'embedding' => $embedding,
                    'metadata' => [
                        'signal_count' => count($signals),
                        'weights' => RecommendationFactorWeightsValueObject::defaults()->toArray(),
                    ],
                    'computed_at' => now(),
                ]
            );

            $this->rebuildEdges($client);

            $this->info("Vector computed for client: {$client->id}");
        } catch (\Throwable $e) {
            $this->error("Failed for client {$client->id}: {$e->getMessage()}");
        }
    }

    private function gatherSignals(ClientModel $client): array
    {
        $signals = [];

        $favorites = ClientFavoriteModel::where('client_id', $client->id)->get();
        foreach ($favorites as $favorite) {
            $signals[] = "favorite:{$favorite->favorable_type}:{$favorite->favorable_id}";
        }

        $bookings = $client->bookings()
            ->where('status', 'completed')
            ->with('barber', 'services.catalogItem')
            ->get();

        foreach ($bookings as $booking) {
            $barberName = $booking->barber?->name ?? 'unknown';
            $serviceNames = $booking->services->map(
                fn ($s) => $s->catalogItem?->name ?? $s->name ?? 'unknown service'
            )->implode(', ');

            $signals[] = "visited barber:{$barberName}, services:{$serviceNames}";
        }

        return $signals;
    }

    private function rebuildEdges(ClientModel $client): void
    {
        $favorites = ClientFavoriteModel::where('client_id', $client->id)->get();
        foreach ($favorites as $favorite) {
            RecommendationEdgeModel::updateOrCreate(
                [
                    'source_type' => 'client',
                    'source_id' => $client->id,
                    'target_type' => $favorite->favorable_type,
                    'target_id' => $favorite->favorable_id,
                    'edge_type' => EdgeTypeEnum::Favorite->value,
                ],
                ['weight' => 1.0]
            );
        }
    }
}
