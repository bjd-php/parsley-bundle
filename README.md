# ParsleyBundle


[![Build Status](https://travis-ci.org/J-Ben87/ParsleyBundle.svg?branch=master)](https://travis-ci.org/J-Ben87/ParsleyBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/J-Ben87/ParsleyBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/J-Ben87/ParsleyBundle/?branch=master)


Convert Symfony constraints into data-attributes for client-side validation with Parsley.


## Basic usage


The `AbstractType` provides a helper method that generate the Parsley constraints for each field you have defined Symfony constraints for.

The `ParsleyConstraintsTypeExtension` allows you to use the `'parsley_constraints'` option in your form type. That option is required as it holds the Parsley constraints that will be used to generate the data attributes Parsley uses to process the client-side validation.


```php
$symfonyConstraints = [
    'login' => [
        new NotBlank()
    ],
    'email' => [
        new NotBlank(),
        new Email()
    ]
];

$parsleyConstraints = $this->generateConstraints($symfonyConstraints);

$builder
    ->add('login', 'text', [
        'constraints'           => $symfonyConstraints['login'],
        'parsley_constraints'   => $parsleyConstraints['login']
    ])
    ->add('email', 'email' [
        'constraints'           => $symfonyConstraints['email'],
        'parsley_constraints'   => $parsleyConstraints['email']
    ]);
```


## Advanced usage


The `ConstraintsAdapter` is a service that generates Parsley constraints from an array of Symfony constraints.


```php
$symfonyConstraints = [
    new NotBlank(),
    new Email()
];

$parsleyConstraints = $constraintsAdapter->generateConstraints($symfonyConstraints);
```


## What's next


Handling entities based validation (annotations, validation.yml file...).
