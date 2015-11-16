<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator;

use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Benoit Jouhaud <bjouhaud@gmail.com>
 */
abstract class Constraint extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectProphecy|NormalizerInterface
     */
    protected $normalizer;

    /**
     * Test creating the constraint without options.
     */
    abstract public function emptyConfiguration();

    /**
     * Test creating the constraint with invalid options.
     */
    abstract public function invalidConfiguration();

    /**
     * Test creating the constraint with valid options.
     */
    abstract public function validConfiguration();

    /**
     * Test normalizing the constraint.
     */
    abstract public function normalization();

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->normalizer = $this->prophesize('Symfony\Component\Serializer\Normalizer\NormalizerInterface');
    }
}
