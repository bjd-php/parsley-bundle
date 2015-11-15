# ParsleyBundle

[![Build Status](https://travis-ci.org/J-Ben87/ParsleyBundle.svg?branch=master)](https://travis-ci.org/J-Ben87/ParsleyBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/J-Ben87/ParsleyBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/J-Ben87/ParsleyBundle/?branch=master)

Convert Symfony constraints into data-attributes for client-side validation with Parsley.

## Installation

Classic but always useful.

First install the bundle with composer:

```bash
composer require j-ben87/parsley-bundle
```

Don't forget to register the bundle in your `app/AppKernel.php`:

```php
public function registerBundles()
{
    $bundles = [
        // ...
        new JBen87\ParsleyBundle\ParsleyBundle(),
    ];
    
    // ...
}
```

And finally, you'll need to enable the `serializer` component by exposing the following in your `config.yml` (commented by default):

```
framework:
    # ...
    serializer:      { enable_annotations: true }
    # ...
```

## Configuration

The bundle exposes a basic configuration:

```yml
jben87_parsley:
    trigger_event: blur     # the JavaScript event for which the validation is to be triggered relative to the selected input
```

## Basic usage

### Form constraints

Create a `FormType` that extends `Symfony\Component\Form\AbstractType`.

The constraints you have defined for each child of your `FormType` will automatically be turned into Parsley data-attributes.

**Nothing else.** 

> Yes, it's that simple!

```php
<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CustomType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(30),
                ],
            ])
            ->add('content', 'textarea', [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'custom';
    }
}
```

Results in:

```html
{% {{ form_widget(form.title) }} %}
<input type="text" id="custom_title" name="custom[title]" required="required" data-parsley-trigger="blur" data-parsley-required="true" data-parsley-required-message="This value should not be blank." data-parsley-length="[30, 30]" data-parsley-length-message="This value should have exactly 30 characters." data-parsley-id="4">

{% {{ form_widget(form.content }} %}
<textarea id="custom_content" name="custom[content]" required="required" data-parsley-trigger="blur" data-parsley-required="true" data-parsley-required-message="This value should not be blank." data-parsley-id="6"></textarea>
```

### Entity constraints

Create a `FormType` that extends `Symfony\Component\Form\AbstractType` and configure it to use one of your entities.

The constraints you have defined for any properties of the entity will automatically be turned into Parsley data-attributes.

**Nothing else.** 

> Here again, it's incredibly simple!

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
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

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user';
    }
}
```

Results in:

```html
{% {{ form_widget(form.username) }} %}
<input type="text" id="user_username" name="user[username]" required="required" maxlength="255" data-parsley-trigger="blur" data-parsley-required="true" data-parsley-required-message="This value should not be blank." data-parsley-maxlength="255" data-parsley-maxlength-message="This value is too long. It should have 255 characters or less." data-parsley-id="4">

{% {{ form_widget(form.email }} %}
<input type="email" id="user_email" name="user[email]" required="required" maxlength="255" data-parsley-trigger="blur" data-parsley-required="true" data-parsley-required-message="This value should not be blank." data-parsley-maxlength="255" data-parsley-maxlength-message="This value is too long. It should have 255 characters or less." data-parsley-type="email" data-parsley-type-message="This value is not a valid email address." data-parsley-id="8">
```

**Notice:** if you define the same constraint on both `FormType` and entity side, `FormType` constraint will override the entity one.

## Advanced usage

The bundle comes with 2 services to generate the constraints:

### Builder

The `ConstraintBuilder` is a service registered as `jben87_parsley.builder.constraint`.

It can be used to turn Symfony constraints into Parsley constraints.

- First `configure` the `ConstraintBuilder` with an array of Symfony `Constraint`.
- Then `build` an array of Parsley `Constraint`.

```php
<?php

use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

/** @var Constraint[] $constraints */
$constraints = [
    new Assert\NotBlank(),
    new Assert\Email(),
];

/** @var ConstraintBuilder $builder */
$builder = $container->get('jben87_parsley.builder.constraint');
$builder->configure(['constraints' => $constraints]);

/** @var ParsleyConstraint[] $parsleyConstraints */
$parsleyConstraints = $builder->build();
```

### Factory

Internally, the builder uses the `ConstraintFactory` service, registered as `jben87_parsley.validator.constraint_factory`.

It can be used to create the suitable Parsley constraint for a given Symfony constraint.

```php
<?php

use JBen87\ParsleyBundle\Validator\Constraint as ParsleyConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

/** @var Constraint $constraint */
$constraint = new Assert\NotBlank();

/** @var ConstraintFactory $factory */
$factory = $container->get('jben87_parsley.validator.constraint_factory');

/** @var ParsleyConstraint $parsleyConstraint */
$parsleyConstraint = $factory->create($constraint);
```

## Supported constraints

The following `Constraint` are currently supported:

- Symfony\Component\Validator\Constraints\Email
- Symfony\Component\Validator\Constraints\Length
- Symfony\Component\Validator\Constraints\NotBlank
- Symfony\Component\Validator\Constraints\Range

## What's next

- Support more constraints
- Support group validation
