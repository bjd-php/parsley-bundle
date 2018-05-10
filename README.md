# ParsleyBundle

[![Build Status](https://travis-ci.com/bjd-php/parsley-bundle.svg?branch=master)](https://travis-ci.com/bjd-php/parsley-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bjd-php/parsley-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bjd-php/parsley-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/bjd-php/parsley-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bjd-php/parsley-bundle/?branch=master)

Convert Symfony constraints into data-attributes for client-side validation with Parsley.

## Installation

Install the bundle with composer:

```bash
composer require j-ben87/parsley-bundle
```

Enable the `serializer` component in your `config/packages/framework.yaml`:

```
framework:
    serializer: { enable_annotations: true }
```

## Configuration

The bundle exposes a basic configuration:

```yml
jben87_parsley:
    enabled: true           # enable/disable Parsley validation globally (can be enabled on FormType or Constraint level)
    trigger_event: 'blur'   # the JavaScript event for which the validation is to be triggered (relative to the selected input)
```

## Usage

### Form constraints

Create a `FormType`.

Any supported constraints you have defined on your form will automatically be turned into Parsley data-attributes.

Yes, it's that simple!

```php
<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CustomType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(30),
                ],
            ])
            ->add('content', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
        ;
    }
}
```

Results in:

```html
<!-- {{ form_widget(form.title) }} -->
<input type="text" id="title" name="title" required="required" data-parsley-trigger="blur" data-parsley-required="true" data-parsley-required-message="This value should not be blank." data-parsley-length="[30, 30]" data-parsley-length-message="This value should have exactly 30 characters.">

<!-- {{ form_widget(form.content }} -->
<textarea id="content" name="content" required="required" data-parsley-trigger="blur" data-parsley-required="true" data-parsley-required-message="This value should not be blank."></textarea>
```

### `data-class` constraints

Create a `FormType` and configure its `data-class` option.

Any supported constraint you have defined on your class will automatically be turned into Parsley data-attributes.

Here again, it's incredibly simple!

```php
<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $username;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     * @Assert\Email
     */
    private $email;
}
```

And

```php
<?php

namespace App\Form\Type;

use App\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('email')
        ;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
```

Results in:

```html
{% {{ form_widget(form.username) }} %}
<input type="text" id="username" name="username" required="required" maxlength="255" data-parsley-trigger="blur" data-parsley-required="true" data-parsley-required-message="This value should not be blank." data-parsley-maxlength="255" data-parsley-maxlength-message="This value is too long. It should have 255 characters or less.">

{% {{ form_widget(form.email }} %}
<input type="email" id="email" name="email" required="required" maxlength="255" data-parsley-trigger="blur" data-parsley-required="true" data-parsley-required-message="This value should not be blank." data-parsley-maxlength="255" data-parsley-maxlength-message="This value is too long. It should have 255 characters or less." data-parsley-type="email" data-parsley-type-message="This value is not a valid email address.">
```

**Notice:** if you define the same constraint on both the `FormType` and the configured `data-class`, the `FormType` constraint will override the one configured on the `data-class`.

## Internals

The `ParsleyTypeExtension` is where all the magic happens.

It gathers all Symfony constraints thanks to registered readers and turn them into Parsley constraints through factories.

It uses the special `ChainFactory` to automatically find the first factory that supports the given Symfony constraint.

Finally it normalizes the Parsley constraint into data-attributes and merge them with the `FormView` attributes.

## Extending the bundle

You can easily add more constraints by:

- creating a constraint that extends the abstract class `JBen87\ParsleyBundle\Constraint\Constraint`
- creating a factory that implements the interface `JBen87\ParsleyBundle\Constraint\Factory\FactoryInterface`

Your factory will be automatically registered to be used by the `ChainFactory` service.

```php
<?php

namespace App\Constraint\Constraints;

use JBen87\ParsleyBundle\Constraint\Constraint;

class Valid extends Constraint
{
    /**
     * @inheritdoc
     */
    protected function getAttribute(): string
    {
        return 'data-parsley-valid';
    }

    /**
     * @inheritdoc
     */
    protected function getValue(): string
    {
        return 'true';
    }
}
```

```php
<?php

namespace App\Constraint\Factory;

use App\Constraint\Constraints as ParsleyAssert;
use JBen87\ParsleyBundle\Constraint\Constraint;
use JBen87\ParsleyBundle\Constraint\Factory\FactoryTrait;
use JBen87\ParsleyBundle\Constraint\Factory\TranslatableFactoryInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class ValidFactory implements TranslatableFactoryInterface
{
    use FactoryTrait;

    /**
     * @inheritdoc
     */
    public function create(SymfonyConstraint $constraint): Constraint
    {
        /** @var Assert\Valid $constraint */

        return new ParsleyAssert\Valid([
            'message' => $this->trans($constraint->message),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function supports(SymfonyConstraint $constraint): bool
    {
        return $constraint instanceof Assert\Valid;
    }
}
```

## Supported constraints

The following Symfony constraints are currently supported:

- Symfony\Component\Validator\Constraints\Date
- Symfony\Component\Validator\Constraints\DateTime
- Symfony\Component\Validator\Constraints\Email
- Symfony\Component\Validator\Constraints\GreaterThan
- Symfony\Component\Validator\Constraints\Length
- Symfony\Component\Validator\Constraints\LessThan
- Symfony\Component\Validator\Constraints\NotBlank
- Symfony\Component\Validator\Constraints\Range
- Symfony\Component\Validator\Constraints\Time

## What's next

- Support more constraints
- Support group validation
