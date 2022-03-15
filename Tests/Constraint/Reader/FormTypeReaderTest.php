<?php

namespace JBen87\ParsleyBundle\Tests\Constraint\Reader;

use JBen87\ParsleyBundle\Constraint\Reader\FormTypeReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class FormTypeReaderTest extends TestCase
{
    public function testReadNoConstraints(): void
    {
        $config = $this->createMock(FormConfigInterface::class);
        $config
            ->expects($this->once())
            ->method('hasOption')
            ->with('constraints')
            ->willReturn(false)
        ;

        $form = $this->createMock(FormInterface::class);
        $this->setUpForm($form, $config);

        $this->assertEmpty($this->createReader()->read($form));
    }

    public function testReadWithConstraints(): void
    {
        $config = $this->createMock(FormConfigInterface::class);
        $config
            ->expects($this->once())
            ->method('hasOption')
            ->with('constraints')
            ->willReturn(true)
        ;
        $config
            ->expects($this->once())
            ->method('getOption')
            ->with('constraints')
            ->willReturn([new NotBlank()])
        ;

        $form = $this->createMock(FormInterface::class);
        $this->setUpForm($form, $config);

        $this->assertEquals([new NotBlank()], $this->createReader()->read($form));
    }

    /**
     * @param MockObject|FormInterface $form
     * @param MockObject|FormConfigInterface $config
     */
    private function setUpForm(MockObject $form, MockObject $config): void
    {
        $form
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn($config)
        ;
    }

    private function createReader(): FormTypeReader
    {
        return new FormTypeReader();
    }
}
