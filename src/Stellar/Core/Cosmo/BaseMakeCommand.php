<?php

namespace Stellar\Core\Cosmo;

use Core\Abstractions\Enums\FrameworkPath;
use Core\Abstractions\Enums\ProjectPath;
use Core\Core\Log\Log;
use Core\Exceptions\FailedOnCloseFile;
use Core\Exceptions\FailedOnDeleteDir;
use Core\Exceptions\FailedOnOpenFile;
use Core\Exceptions\FailedOnScanDirectory;
use Core\Exceptions\FailedOnWriteFile;
use Core\Exceptions\PathAlreadyExist;
use Core\Exceptions\PathNotFound;
use Core\Exceptions\TypeNotMatch;
use Core\Helpers\StrTool;
use Core\Structure\File;
use Core\Structure\Path;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;

abstract class BaseMakeCommand extends \Stellar\Core\Cosmo\Console\VortexCommand
{
    protected string $class_filename;
    protected string $class_name;

    abstract protected function getArgumentName(): string;

    abstract protected function finalPath(): ProjectPath;

    abstract protected function getIndex(): string;

    abstract protected function stubFileName(): string;

    protected function getExtensionFile(): string
    {
        return '.php';
    }

    /**
     * @return CommandReturnStatus
     * @throws FailedOnCloseFile
     * @throws FailedOnDeleteDir
     * @throws FailedOnOpenFile
     * @throws FailedOnScanDirectory
     * @throws FailedOnWriteFile
     * @throws PathAlreadyExist
     * @throws PathNotFound
     * @throws TypeNotMatch
     */
    protected function handle(): CommandReturnStatus
    {
        $this->setClassName();
        $this->indexRow($this->getIndex());

        if (!Path::exist($this->finalPath()->additionalPath($this->class_filename))) {
            File::createByTemplate(
                $this->class_filename,
                $this->finalPath()->value,
                FrameworkPath::STUBS->additionalPath($this->stubFileName()),
                $this->trades()
            );

            $this->successRow($this->class_name, 'created');
        } else {
            $_SERVER['COMMAND'] = "php cosmo {$this->getName()} \"$this->class_name\"";
            Log::error(StrTool::singularize($this->getIndex()) . " \"$this->class_name\" already exist");
            $this->failRow($this->class_name, 'already exist');
        }

        $this->breakLine(2);

        return CommandReturnStatus::SUCCESS;
    }

    protected function trades(): array
    {
        return [];
    }

    protected function setClassName(): void
    {
        $class_name = $this->input->getArgument($this->getArgumentName());

        if (!StrTool::startsWith($extension = $this->getExtensionFile(), '.')) {
            $extension = ".$extension";
        }

        if (strpos($class_name, $extension)) {
            $class_name = substr($class_name, 0, strlen($class_name) - strlen($extension));
        }

        $this->class_name = StrTool::pascalCase($class_name);
        $this->class_filename = $this->class_name . $extension;
    }
}
