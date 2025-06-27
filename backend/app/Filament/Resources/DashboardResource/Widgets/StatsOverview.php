<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Booking;
use App\Models\Artifact;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),

            Stat::make('Total Artifacts', Artifact::count()),

            Stat::make('Pending Tickets', Booking::where('status', 'pending')->count()),

            Stat::make('Paid Tickets', Booking::where('status', 'paid')->count()),
        ];
    }
}
