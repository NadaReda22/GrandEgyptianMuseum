<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Booking;

class PaidTicketsChart extends ChartWidget
{
    protected static ?string $heading = 'Paid Tickets Per Event';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Fetch paid ticket counts grouped by event title
        $data = Booking::where('status', 'paid')
            ->whereNotNull('event_id') // optional safety check
            ->with('event')
            ->get()
            ->groupBy(fn ($booking) => $booking->event->title ?? 'Unknown')
            ->map(fn ($group) => $group->count());

        return [
            'datasets' => [
                [
                    'label' => 'Paid Tickets',
                    'data' => $data->values(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
