## Summary
- [Gateway](#Gateway)
- [Provider](#Provider)
- [Helpers](#Helpers)
  - [Navigation](#Navigation)
    - [File](#File)
    - [Directory](#Directory)
    - [Symlink](#Symlink)
- [Request](#Request)
- [Storage](#Storage)

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
            Method::make('test', function (Storage $adapter, string $drive): string {
                return $drive;
            }),
        ];
    }
}
``` 

> :warning: Remember set the `callable` return type like the example to help `ide:gateways` command.

> :warning: Look who the first parameter from `Gateway` callable must be the Adapter object or `Adapter::class`, and is 
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

## Storage
Storage is the simplified way Vortex handles files inserted into the application, abstracting their manipulation and 
access. Within Storage, it is possible to create drives, and each drive has two partitions: one public and one private. 
The public partition can be made available for public access, while the private partition cannot.

### Drive
To configure a `drive`, simply register it in the storage settings file. In this example we register the drive "drive_1".

```php
<?php
// Settings/storage.php

return [
    'drives' => [
        'drive_1' => [
        
        ],
    ],
];
```

To set a default drive for Storage, add the `default` key within the storage settings with the name of the configured 
drive key.

```php
<?php
// Settings/storage.php

return [
    'default' => 'drive_1',
    'drives' => [
        'drive_1' => [],
    ],
];
```

To specify that errors when interacting with the drive should not trigger exceptions, set the `exception_mode` key to 
false.

```php
<?php
// Settings/storage.php

return [
    'drives' => [
        'drive_1' => [
            'exception_mode' => false,        
        ],
    ],
];
```

### Partitions
Each drive has a `public` partition and a `private` partition. By default, the public partition is enabled and the private 
partition is disabled. However, these settings can be changed according to the example below. Within the partitions key 
of your drive, there are keys for each partition that should be associated with a boolean value indicating whether they 
are enabled or not.

```php
<?php
// Settings/storage.php

return [
    'drives' => [
        'drive_1' => [
            'partitions' => [
                'public' => true,
                'private' => false,
            ],
        ],
    ],
];
```

### Adapter
#### Presets
Storage has various definitions to look for default values, either in the settings or Vortex presets. Therefore, if no 
preset is specified, all values will be taken by default, including which drive will be accessed, which can be 
configured as demonstrated earlier with the default key. To specify how the drive be accessed, the following methods 
should be used:

```php
<?php
use Stellar\Storage\Adapters\Storage;

Storage::drive('drive_1') // Specify witch drive be access.
    ->publicPartition() // Specify witch partition be access.
    ->exceptionMode(false); // Define the exception_mode
```

> :warning:
> Note that the presets defined during interaction with Storage will take precedence over the settings.

#### Get
To get Drive file content, use the method `get()`, you need pass the partition relative path to the method like bellow:

```php
<?php
use Stellar\Storage\Adapters\Storage;

Storage::drive('drive_1')->get('documents/contract.pdf');
```

> :nazar_amulet:
> Note that the presets defined during interaction with Storage will take precedence over the settings.

#### Url
The public access for files are enable only for `public` partition and `url()` method not accept try call him from 
`private` partition and will throw an Exception. If try access valid file the public URL will be returned.

```php
<?php
use Stellar\Storage\Adapters\Storage;

Storage::drive('drive_1')->url('documents/contract.pdf');
```

#### Exists
The `exists()` method will return boolean value, true if the file exists in the drive or false if not.

```php
<?php
use Stellar\Storage\Adapters\Storage;

Storage::drive('drive_1')->exists('documents/contract.pdf');
```

#### Mime Type
To return the file type use the method `mimeType()` to get that.

```php
<?php
use Stellar\Storage\Adapters\Storage;

Storage::drive('drive_1')->mimeType('documents/contract.pdf');
```

#### Path
Use the method `path()` to get the full path for the file.

```php
<?php
use Stellar\Storage\Adapters\Storage;

Storage::drive('drive_1')->path('documents/contract.pdf');
```

#### Change Partition
To change file partition use the methods `turnPrivate()` or `turnPublic()`, this just can change partition inside same 
drive. If try turn file to the same partition, this method will throw an Exception or return false.

```php
<?php
use Stellar\Storage\Adapters\Storage;

Storage::drive('drive_1')->turnPrivate('documents/contract.pdf');
Storage::drive('drive_1')->turnPublic('documents/contract.pdf');
```

#### Put
Tu add new files to drive you can use string content or one object `Stream`.

```php
<?php

use Stellar\Navigation\Stream\Enums\OpenMode;
use Stellar\Storage\Adapters\Storage;
use Stellar\Navigation\Stream;

// Using string content.
Storage::drive('drive_1')->put('test.txt', 'Test text example.');

// Using Stream.
$stream = Stream::make('file_path', OpenMode::X_PLUS_MODE)->write('Test text example.');
Storage::drive('drive_1')->put('test.txt', $stream);
```