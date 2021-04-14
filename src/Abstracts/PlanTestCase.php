<?php

declare(strict_types=1);

namespace JPeters\Architect\TestHelpers\Abstracts;

use RuntimeException;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Laravel\Models\User;

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
    public function itReturnsANewPlanFromTheStaticGenerator()
    {
        $this->assertInstanceOf(
            $this->getPlan(),
            $this->getPlan()::generate('Foo')
        );
    }

    /** @test */
    public function itCanBeHiddenFromTheIndex()
    {
        $this->plan->hideOnIndex();

        $this->assertFalse($this->plan->isAvailableOnIndex());
    }

    /** @test */
    public function itCanBeHiddenFromForms()
    {
        $this->plan->hideOnForms();

        $this->assertFalse($this->plan->isAvailableOnForm());
    }

    /** @test */
    public function itCanBeHiddenForMobileViews()
    {
        $this->plan->hideFromIndexOnMobile();

        $this->assertTrue($this->plan->isHiddenOnMobile());
    }

    /** @test */
    public function itSetsTheColumnName()
    {
        $this->assertNotNull($this->plan->getColumn());
        $this->assertEquals($this->getColumnName(), $this->plan->getColumn());
    }

    /** @test */
    public function itSetsTheLabelName()
    {
        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('foo', 'Bar');

        $this->assertNotNull($plan->getLabel());
        $this->assertEquals('Bar', $plan->getLabel());
    }

    /** @test */
    public function itSetsAComplexLabelName()
    {
        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('foo_bar');

        $this->assertEquals('Foo Bar', $plan->getLabel());
    }

    /** @test */
    public function itUpdatesTheModel()
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
    public function itCanHaveADefault()
    {
        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('email');

        $this->assertNull($plan->getDefault());

        $plan->setDefault('foo');

        $this->assertEquals('foo', $plan->getDefault());
    }

    /** @test */
    public function itCanHaveEventListenersSet()
    {
        $this->assertArrayHasKey('listeners', $this->plan->getMetas());

        $this->plan->addListener('name', 'changed', static function () {
            return 'foo';
        });

        $this->assertArrayHasKey('changed', $this->plan->getMetas()['listeners']);
        $this->assertEquals('name', $this->plan->getMetas()['listeners']['changed']);
    }

    /** @test */
    public function itErrorsWhenTryingToAddAnUnknownEvent()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unknown event handler');

        $this->plan->addListener('name', 'foo', static function () {
            return 'foo';
        });
    }

    /** @test */
    public function itExecutesAListener()
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
    public function itErrorsWhenTryingToExecuteAnUnknownListener()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Couldn't find listener");

        $this->plan->executeEvent('foo', 'bar');
    }
}
