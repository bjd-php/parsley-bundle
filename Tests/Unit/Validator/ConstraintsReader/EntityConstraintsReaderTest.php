<?php

namespace JBen87\ParsleyBundle\Tests\Unit\Validator\ConstraintsReader;

use JBen87\ParsleyBundle\Validator\ConstraintsReader\EntityConstraintsReader;
use PHPUnit\Framework\MockObject\Matcher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Exception\NoSuchMetadataException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\GenericMetadata;
use Symfony\Component\Validator\Mapping\PropertyMetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityConstraintsReaderTest extends TestCase
{
    /**
     * @var MockObject|ValidatorInterface
     */
    private $validator;

    public function testReadNoDataClass(): void
    {
        $form = $this->createMock(FormInterface::class);
        $this->setUpForm($form, $this->once(), null);

        $this->assertEmpty($this->createReader()->read($form));
    }

    /**
     * @param callable $setUpValidator
     *
     * @dataProvider provideReadNoMetadata
     */
    public function testReadNoMetadata(callable $setUpValidator): void
    {
        $form = $this->createMock(FormInterface::class);
        $this->setUpForm($form);

        $setUpValidator($this->validator);

        $this->assertEmpty($this->createReader()->read($form));
    }

    /**
     * @return array
     */
    public function provideReadNoMetadata(): array
    {
        return [
            [
                function (MockObject $validator): void {
                    $validator
                        ->expects($this->once())
                        ->method('getMetadataFor')
                        ->with($this->isType('string'))
                        ->willThrowException(new NoSuchMetadataException())
                    ;
                },
            ],
            [
                function (MockObject $validator): void {
                    $validator
                        ->expects($this->once())
                        ->method('getMetadataFor')
                        ->with($this->isType('string'))
                        ->willReturn(new GenericMetadata())
                    ;
                },
            ],
        ];
    }

    public function testReadNoPropertyMetadata(): void
    {
        $form = $this->createMock(FormInterface::class);
        $this->setUpForm($form);

        $this->setUpValidator($this->validator, $this->never(), []);

        $this->assertEmpty($this->createReader()->read($form));
    }

    public function testReadNoConstraints(): void
    {
        $form = $this->createMock(FormInterface::class);
        $this->setUpForm($form);

        $propertyMetadatum = $this->createMock(PropertyMetadataInterface::class);
        $propertyMetadatum
            ->expects($this->once())
            ->method('findConstraints')
            ->with('Default')
            ->willReturn([])
        ;

        $this->setUpValidator($this->validator, $this->once(), [$propertyMetadatum]);

        $this->assertEmpty($this->createReader()->read($form));
    }

    public function testReadWithConstraints(): void
    {
        $form = $this->createMock(FormInterface::class);
        $this->setUpForm($form);

        $propertyMetadatum1 = $this->createMock(PropertyMetadataInterface::class);
        $propertyMetadatum1
            ->expects($this->once())
            ->method('findConstraints')
            ->with('Default')
            ->willReturn([new NotBlank()])
        ;

        $propertyMetadatum2 = $this->createMock(PropertyMetadataInterface::class);
        $propertyMetadatum2
            ->expects($this->once())
            ->method('findConstraints')
            ->with('Default')
            ->willReturn([new NotNull()])
        ;

        $this->setUpValidator($this->validator, $this->exactly(2), [$propertyMetadatum1, $propertyMetadatum2]);

        $this->assertEquals([new NotBlank(), new NotNull()], $this->createReader()->read($form));
    }

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    /**
     * @param MockObject $form
     * @param Matcher\InvokedCount|null $configMatcher
     * @param null|string $dataClass
     */
    private function setUpForm(
        MockObject $form,
        Matcher\InvokedCount $configMatcher = null,
        ?string $dataClass = '\\stdClass'
    ): void {
        if (null === $configMatcher) {
            $configMatcher = $this->exactly(2);
        }

        $config = $this->createMock(FormConfigInterface::class);
        $config
            ->expects($configMatcher)
            ->method('getDataClass')
            ->willReturn($dataClass)
        ;

        $rootForm = $this->createMock(FormInterface::class);
        $rootForm
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn($config)
        ;

        $form
            ->expects($this->once())
            ->method('getRoot')
            ->willReturn($rootForm)
        ;
    }

    /**
     * @param MockObject $validator
     * @param Matcher\InvokedCount $groupMatcher
     * @param PropertyMetadataInterface[] $propertyMetadata
     */
    private function setUpValidator(
        MockObject $validator,
        Matcher\InvokedCount $groupMatcher,
        array $propertyMetadata
    ): void {
        if (null === $groupMatcher) {
            $groupMatcher = $this->never();
        }

        $metadata = $this->createMock(ClassMetadata::class);
        $metadata
            ->expects($this->once())
            ->method('getPropertyMetadata')
            ->with(null)
            ->willReturn($propertyMetadata)
        ;
        $metadata
            ->expects($groupMatcher)
            ->method('getDefaultGroup')
            ->willReturn('Default')
        ;

        $validator
            ->expects($this->once())
            ->method('getMetadataFor')
            ->with($this->isType('string'))
            ->willReturn($metadata)
        ;
    }

    /**
     * @return EntityConstraintsReader
     */
    private function createReader(): EntityConstraintsReader
    {
        return new EntityConstraintsReader($this->validator);
    }
}
