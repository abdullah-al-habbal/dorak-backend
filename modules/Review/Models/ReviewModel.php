<?php
// modules/Review/Models/ReviewModel.php
declare(strict_types=1);

namespace Modules\Review\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Booking\Models\BookingModel;

class ReviewModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'reviews';

    protected $fillable = [
        'booking_id', 'author_id', 'author_type', 'subject_id', 'subject_type', 'rating', 'comment',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(BookingModel::class);
    }

    public function author(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
