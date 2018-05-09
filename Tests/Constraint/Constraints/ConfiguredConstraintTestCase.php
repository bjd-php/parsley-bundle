<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Constraints;

abstract class ConfiguredConstraintTestCase extends ConstraintTestCase
{
    abstract public function testEmptyConfiguration(): void;

    abstract public function testInvalidConfiguration(): void;
}
