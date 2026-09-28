<?php

namespace App\Filament\Resources\DistributiveResource\Pages;

use App\Filament\Resources\DistributiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDistributive extends EditRecord
{
    protected static string $resource = DistributiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
