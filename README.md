## Summary
- [Cosmo](#Cosmo)
  - [Command](#Command)
    - [Permissions](#Permissions)
      - [CanRun](#CanRun)
      - [CanSee](#CanSee)
      - [Can](#Can)
    - [Arguments](#Arguments)
    - [Options](#Options)
    - [Bells](#Bells)
- [Request](#Request)


## Cosmo

### Command
Commands allow developers to automate common tasks such as deployment, installation, and others. In the Vortex
application, there are two methods to register commands: either by creating the command class inside the
``app/commands`` directory or by creating a new ``Adapter`` and specifying the commands to be registered. To generate a 
new command, utilize the following Cosmo command and specify the desired command name.

```shell
php cosmo make:command ${COMMAND_NAME}
```

Command classes require set the ``name()`` and the ``handle()`` methods. The first is the name of our command and need
return a string, and the last is the code that must be executed where the command be called, and need return an object
of type ``CommandReturnStatus``.

```php
<?php

use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Vortex\Cosmo\Command; 
      
class ProjectInstall extends Command  
{  
    protected function name(): string
    {
        return 'project:install';
    }

    protected function handle(): CommandReturnStatus
    {
        // Code to install project
        
        return CommandReturnStatus::SUCCESS;
    }
}
```

#### Permissions

##### CanRun()

To determine whether a command can be executed or not, the static method ``canRun()`` is used, which takes the instance
of
the Application class as a parameter, as in the example below. This method should return a boolean value.

```php
<?php

use Stellar\Vortex\Cosmo\Argument;  
use Stellar\Vortex\Cosmo\Command;  
      
class ProjectInstall extends Command  
{  
    public static function canRun(Application $application): bool 
    {  
        return true;
    }
}
```

##### CanSee()

Similarly, but controlling the visibility of a command, the static method ``canSee()`` should be used. It also takes an
instance of the Application class as a parameter and should return a boolean value. Note that this method will only
block visibility, allowing the command to be executed but not listed.

```php
<?php

use Stellar\Vortex\Cosmo\Argument;  
use Stellar\Vortex\Cosmo\Command;  
      
class ProjectInstall extends Command  
{  
    public static function canSee(Application $application): bool 
    {  
        return true;
    }
}
```

##### Can()

For an upper level control, the static method ``can()`` will both block visibility and execution permission, our
structure and functionality are similar to earlier methods.

```php
<?php

use Stellar\Vortex\Cosmo\Argument;  
use Stellar\Vortex\Cosmo\Command;  
      
class ProjectInstall extends Command  
{  
    public static function can(Application $application): bool 
    {  
        return true;
    }
}
```

#### Arguments

To add arguments for our command, you need set the protected method ``arguments()`` in the command class. This method
need return an array of Arguments objects.

```php
<?php

use Stellar\Vortex\Cosmo\Argument;  
use Stellar\Vortex\Cosmo\Command;  
      
class ProjectInstall extends Command  
{  
    protected function arguments(): array  
    {  
        return [  
            Argument::make('model'),  
        ];  
    }
}
```

#### Options

Options are similar to arguments, but you set the protected method ``options()`` in the command class and return an
array of Options objects.

```php
<?php

use Stellar\Vortex\Cosmo\Option;  
use Stellar\Vortex\Cosmo\Command;  
      
class ProjectInstall extends Command  
{  
    protected function options(): array  
    {  
        return [  
            Option::make('model'),  
        ];  
    }
}
```

``OBS``: The Argument and Option classes can be created with default ``new`` constructor or with the aliases ``make()``
method.

#### Bells
In additional Command class has some bells to customize the experience on run and debug commands. To display the command
runtime in the end of command, just set the method ``withRuntime()`` to return true. By default, this is enabled.

```php
<?php

use Stellar\Vortex\Cosmo\Option;  
use Stellar\Vortex\Cosmo\Command;  
      
class ProjectInstall extends Command  
{  
    protected function withRuntime() : bool
    {
        return true;
    }
}
```

Another feature is the change counter, which will show the total count of changes at the end of command execution. It is 
the developer's responsibility to specify the points at which the count will be incremented using the method 
``addChange()`` than can receive an boolean or Closure by parameter to determine if the change will be counted, as shown 
below:

```php
<?php

use Stellar\Vortex\Cosmo\Command;  
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
      
class ProjectInstall extends Command  
{  
    protected function handle() : CommandReturnStatus
    {
        for ($i = 0; $i < 100; $i++) {
            $this->addChange(fn() => $i > 5);
        }
        
        return CommandReturnStatus::SUCCESS;
    }
}
```

## Request
Classes Request are responsible for grouping and abstracting the data and information necessary in a request, such as 
``HTTP`` method, data from a ``POST`` form, query parameters from a ``GET`` request, among others. Additionally, it also 
allows for validating these data, using ``Validations`` and ``Rules``. It is possible to extend the Request class and 
define a customized class to be used in the application, by placing our string namespace in the setting 
``app/default_request``. This must necessarily be an extension of the Request class.

```php
<?php

// settings/app.php
return [
    'default_request' => \Stellar\Vortex\Request::class,
];
```

### Validations

#### Raw Validations
To add rules and feedback directly during validation, you can perform a raw validation by passing rules and/or feedbacks 
as parameters to the `validate()` method of Request, as shown below. The rules should be an associative array where the 
key is the field to be validated and the value is an instance of Rule or an array of Rules `(Rule|array<Rule>)`. The 
feedbacks should be an array where the key is the field name and the value is the rules applied to it, with its 
sub-rules separated by `"."`.

```php
<?php

use Stellar\Vortex\Request\Validations\Rules\EmailRule;
use Stellar\Vortex\Request\Validations\Rules\RequiredRule;
use Stellar\Vortex\Request\Validations\Rules\StringRule;

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

use Stellar\Vortex\Request\Validations\Rules\EmailRule;
use Stellar\Vortex\Request\Validations\Rules\RequiredRule;
use Stellar\Vortex\Request\Validations\Rules\StringRule;

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

use Stellar\Vortex\Request\Validation;
use Stellar\Vortex\Request\Validations\Rules\StringRule;
use Stellar\Vortex\Request\Validations\Rules\RequiredRule;

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

use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validations\Rule;

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

