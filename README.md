## Summary
- [Gateway](#Gateway)
- [Provider](#Provider)
- [Helpers](#Helpers)
  - [Navigation](#Navigation)
    - [File](#File)
    - [Directory](#Directory)
    - [Symlink](#Symlink)
- [Request](#Request)

## Gateway
If you need to add a method to Adapters, you just need to create and set `new Gateway` classes, and then either set them 
in the `app.php` configuration file or in the `gateways()` method inside `Providers`. After set our custom methods from
our custom `Gateway`, you can access the same in the `Adapter` classes.

```php
<?php
// Settings/app.php

return [
    'gateways' => [
        CustomGateway::class,
    ],
];
```

**Warning**: If you configure multiple Gateways methods for the same Adapter with same name , one error will be triggered.

### Gateway class
Gateways are straightforward classes that establish mappings between base interfaces and custom classes requiring 
customized functionalities. To create a `Gateway`, simply define two static methods: `baseInterface()`, which specifies 
the base interface for our custom class, and `customClass()`, which refers to our customized class.

```php
<?php

namespace Stellar\Storage;

use Stellar\Gateway;use Stellar\Gateway\Method;use Stellar\Storage\Adapters\Storage;

class StorageGateway extends Gateway
{
    public static function adapterClass(): string
    {
        return Storage::class;
    }

    public static function methods(): array
    {
        return [
            Method::make('test', function (Storage $adapter, string $drive) {
                return $adapter::drive($drive);
            }),
        ];
    }
}
``` 

**Warning**: Look who the first parameter from Gateway callable must be the Adapter object or Adapter::class, and is 
required and must be the first parameter.

## Provider

## Helpers

### Navigation
#### File

#### Directory
#### Symlink

## Request
Classes Request are responsible for grouping and abstracting the data and information necessary in a request, such as 
``HTTP`` method, data from a ``POST`` form, query parameters from a ``GET`` request, among others. Additionally, it also 
allows for validating these data, using ``Validations`` and ``Rules``. It is possible to extend the Request class and 
define a customized class to be used in the application, by adding in the `app.gateways` setting, for more about this 
look to [Gateway](#gateway) section.

### Validations

#### Raw Validations
To add rules and feedback directly during validation, you can perform a raw validation by passing rules and/or feedbacks 
as parameters to the `validate()` method of Request, as shown below. The rules should be an associative array where the 
key is the field to be validated and the value is an instance of Rule or an array of Rules `(Rule|array<Rule>)`. The 
feedbacks should be an array where the key is the field name and the value is the rules applied to it, with its 
sub-rules separated by `"."`.

```php
<?php

use Stellar\Request\Validations\Rules\EmailRule;use Stellar\Request\Validations\Rules\RequiredRule;use Stellar\Request\Validations\Rules\StringRule;

$rules = [
    'name' => StringRule::make()->max(125)->min(10),
    'email' => [
        RequiredRule::make(),
        EmailRule::make(),
    ],
];

$feedbacks = [
    'name' => [
        'string.max' => 'Custom string error message for field $field.'
    ]
];

$request->validate($rules, $feedbacks);
```

#### Custom Validation
To use a custom Validation in Vortex, there are two ways: creating a new Request class and specifying which Validation 
class will be used, or before performing the validation, from the Request instance, call the `setValidation()` method 
and pass the Validation class that should be used.

```php
<?php

use Stellar\Request\Validations\Rules\EmailRule;use Stellar\Request\Validations\Rules\RequiredRule;use Stellar\Request\Validations\Rules\StringRule;

$rules = [
    'name' => StringRule::make()->max(125)->min(10),
    'email' => [
        RequiredRule::make(),
        EmailRule::make(),
    ],
];

$feedbacks = [
    'name' => [
        'string.max' => 'Custom string error message for field $field.',
        'email' => 'The field $field must be a valid email.'
    ]
];

$request->setValidation(new CustomValidation);
$request->validate($rules, $feedbacks);
```

#### Validation
Validation classes allow you to define which rules will be applied to each field, as well as customize their respective 
feedback. To define a custom validation, there is the `customValidation()` method that takes an instance of the Request 
class as a parameter and must return a boolean.

```php
<?php

use Stellar\Request\Validation;use Stellar\Request\Validations\Rules\RequiredRule;use Stellar\Request\Validations\Rules\StringRule;

class CustomValidation extends Validation
{
    public function getRules(): array
    {
        return [
            'name' => [
                StringRule::make(),
                RequiredRule::make(),
            ],
        ];
    }

    public function getFeedbacks(): array
    {
        return [
            'name' => [
                'string' => 'Custom string rule feedback',
                'required' => 'Field is required',
            ],
        ];   
    }
    
    public function customValidation(Request $request): bool|string
    {
        return true;
    }
}
```
#### Rules
For create custom field validations you can make new Rule classes. The required methods are `applyRule()` and 
`getRuleKey()`. The `getRuleKey()` is the method that define main rule identifier, and the `applyRule()` is the method 
that will validate the field. To apply the rule you use the method `applyRule()` and to mark an error call method 
`fireError()` with the error key identifier and optional feedback message. You can define an array of custom attributes
that can be used to complete feedback messages with method `customAttributes()`.

```php
<?php

namespace Stellar\Vortex\Request\Validations\Rules;

use Stellar\Request;use Stellar\Request\Validations\Rule;

class EmailRule extends Rule
{
    protected array $feedback_messages = [
        'email' => 'The $field field must be a valid Email.',
    ];

    public function applyRule(Request $request): void
    {
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $this->fireError('email');
        }
    }

    public function getRuleKey(): string
    {
        return 'email';
    }
    
    public function customAttributes(Request $request): array
    {
        return [
            '$field' => $this->field,
            '$value' => $this->value ?? 'null',
        ];
    }
}
```

