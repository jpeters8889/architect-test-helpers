<?php

declare(strict_types=1);

namespace JPeters\Architect\TestHelpers\Traits;

use JPeters\Architect\Plans\Plan;
use JPeters\Architect\TestHelpers\Laravel\Models\Blog;
use JPeters\Architect\TestHelpers\Laravel\Models\BlogType;

trait TestsPlanRelationships
{
    /** @test */
    public function it_can_be_marked_as_being_from_a_relationship()
    {
        BlogType::query()->create(['type' => 'News']);
        BlogType::query()->create(['type' => 'Personal']);

        $blog = factory(Blog::class)->create(['type_id' => 2]);

        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('type_id');

        $this->assertEquals(2, $plan->getCurrentValue($blog));

        $plan->isInRelationship('type');

        $this->assertEquals('Personal', $plan->getCurrentValue($blog));
    }

    /** @test */
    public function it_updates_a_plan_when_marked_as_in_a_relationship()
    {
        BlogType::query()->create(['type' => 'News']);
        BlogType::query()->create(['type' => 'Personal']);

        $blog = factory(Blog::class)->create(['type_id' => 2]);

        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('type_id');

        $plan->handleUpdate($blog, 'type_id', 1);

        $this->assertEquals(1, $plan->getCurrentValue($blog));

        $plan->isInRelationship('type');

        $plan->handleUpdate($blog = $blog->fresh(), 'type_id', 'Personal');

        $this->assertEquals(2, $blog->type_id);

        $plan->handleUpdate($blog = $blog->fresh(), 'type_id', 'Work');

        $this->assertDatabaseHas('blog_types', ['type' => 'Work']);
        $this->assertEquals(3, $blog->type_id);
    }
}
