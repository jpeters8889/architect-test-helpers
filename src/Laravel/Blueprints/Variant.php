<?php

declare(strict_types=1);

namespace JPeters\Architect\TestHelpers\Laravel\Blueprints;

use JPeters\Architect\Plans\BulkBlueprintVariants;
use JPeters\Architect\Plans\Lookup;
use JPeters\Architect\Plans\Textarea;
use JPeters\Architect\Plans\Textfield;
use Illuminate\Database\Eloquent\Builder;
use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\TestHelpers\Laravel\Models\BlogType;

class Variant extends Blueprint
{
    public function model(): string
    {
        return \JPeters\Architect\TestHelpers\Laravel\Models\Variant::class;
    }

    public function plans(): array
    {
        return [
            new Textfield('title'),

            new Textarea('description'),

            (new BulkBlueprintVariants('options'))
            ->plans([
                new Textfield('option'),

                new Textfield('price'),
            ]),
        ];
    }

    public function searchable(): bool
    {
        return false;
    }

    public function filters(): array
    {
        return [
            'type_id' => [
                'name' => 'Type',
                'options' => BlogType::query()->get()
                    ->mapWithKeys(fn (BlogType $type) => [$type->id => $type->type]),
                'filter' => fn (Builder $builder, $value) => $builder->where('type_id', $value),
            ],
        ];
    }
}
