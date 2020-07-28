<?php

namespace JPeters\Architect\TestHelpers\Abstracts;

use JPeters\Architect\Plans\Plan;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Laravel\Models\Blog;
use JPeters\Architect\TestHelpers\Laravel\Models\BlogType;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use RuntimeException;

abstract class PlanTestCase extends ArchitectTestCase
{
    /** @var Plan */
    protected $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $class = $this->getPlan();

        $this->plan = new $class($this->getColumnName());
    }

    abstract public function getPlan();

    abstract public function getColumnName();

    /** @test */
    public function it_returns_a_new_plan_from_the_static_generator()
    {
        $this->assertInstanceOf(
            $this->getPlan(),
            $this->getPlan()::generate('Foo')
        );
    }

    /** @test */
    public function it_can_be_hidden_from_the_index()
    {
        $this->plan->hideOnIndex();

        $this->assertFalse($this->plan->isAvailableOnIndex());
    }

    /** @test */
    public function it_can_be_hidden_from_forms()
    {
        $this->plan->hideOnForms();

        $this->assertFalse($this->plan->isAvailableOnForm());
    }

    /** @test */
    public function it_can_be_hidden_for_mobile_views()
    {
        $this->plan->hideFromIndexOnMobile();

        $this->assertTrue($this->plan->isHiddenOnMobile());
    }

    /** @test */
    public function it_sets_the_column_name()
    {
        $this->assertNotNull($this->plan->getColumn());
        $this->assertEquals($this->getColumnName(), $this->plan->getColumn());
    }

    /** @test */
    public function it_sets_the_label_name()
    {
        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('foo', 'Bar');

        $this->assertNotNull($plan->getLabel());
        $this->assertEquals('Bar', $plan->getLabel());
    }

    /** @test */
    public function it_sets_a_complex_label_name()
    {
        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('foo_bar');

        $this->assertEquals('Foo Bar', $plan->getLabel());
    }

    /** @test */
    public function it_updates_the_model()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('email');

        $plan->handleUpdate($user, 'email', 'foo');

        $this->assertEquals('foo', $user->email);
    }

    /** @test */
    public function it_can_have_a_default()
    {
        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('email');

        $this->assertNull($plan->getDefault());

        $plan->setDefault('foo');

        $this->assertEquals('foo', $plan->getDefault());
    }

    /** @test */
    public function it_can_have_event_listeners_set()
    {
        $this->assertArrayHasKey('listeners', $this->plan->getMetas());

        $this->plan->addListener('name', 'changed', static function () {
            return 'foo';
        });

        $this->assertArrayHasKey('changed', $this->plan->getMetas()['listeners']);
        $this->assertEquals('name', $this->plan->getMetas()['listeners']['changed']);
    }

    /** @test */
    public function it_errors_when_trying_to_add_an_unknown_event()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unknown event handler');

        $this->plan->addListener('name', 'foo', static function () {
            return 'foo';
        });
    }

    /** @test */
    public function it_executes_a_listener()
    {
        $this->assertArrayHasKey('listeners', $this->plan->getMetas());

        $this->plan->addListener('name', 'changed', static function () {
            return 'foo';
        });

        $this->assertEquals(
            'foo',
            $this->plan->executeEvent('name-changed', 'bar')
        );
    }

    /** @test */
    public function it_errors_when_trying_to_execute_an_unknown_listener()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Couldn't find listener");

        $this->plan->executeEvent('foo', 'bar');
    }
}
