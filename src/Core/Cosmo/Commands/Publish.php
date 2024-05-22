<?php

namespace Stellar\Core\Cosmo\Commands;

use Core\Abstractions\Enums\FrameworkPath;
use Core\Abstractions\Enums\ProjectPath;
use Core\Exceptions\PathAlreadyExist;
use Core\Structure\File;
use Stellar\Core\Cosmo\Console\Enums\CommandReturnStatus;
use Stellar\Core\Cosmo\Console\VortexCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;

#[AsCommand(
    name: 'publish',
)]
class Publish extends VortexCommand
{
    private const SESSION_MIGRATIONS = [
        'PersonalTokensTable.php' => '2022_11_29_01_19_38_create_personal_tokens_table.php',
        'SessionTokens.php' => '2022_12_30_02_18_55_create_sessions_table.php'
    ];

    protected function configure()
    {
        $this->addOption(
            'force_mode',
            'f',
            InputOption::VALUE_NONE,
            'Enable overwriting of existing files.'
        );
    }

    protected function handle(): CommandReturnStatus
    {
        $this->write('Select the publish option to continue', break_line: true);
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            '',
            ['Session default migrations', 'Users create table migration']
        );

        $question->setErrorMessage('%s in not valid option for publish');
        $response = $helper->ask($this->input, $this->output, $question);

        switch ($response) {
            case 'Session default migrations':
                $this->publishSessionMigrations();
                break;
            case 'Users create table migration':
                $this->publishUsersTableMigration();
                break;
            default:
                return CommandReturnStatus::FAILED;
        }

        return CommandReturnStatus::SUCCESS;
    }

    private function publishSessionMigrations(): void
    {
        foreach (self::SESSION_MIGRATIONS as $migration_class => $file_name) {
            try {
                !File::copy(
                    FrameworkPath::MIGRATIONS->additionalPath($migration_class),
                    ProjectPath::MIGRATIONS->value,
                    $file_name,
                    force_mode: $this->input->getOption('force_mode') ?? false
                );
            } catch (PathAlreadyExist) {
                $this->failBlock("Fail to publish migrations, migrations already exist in your project, you can set force mode like \"-f\"");
                $this->breakLine();
                return;
            }
        }

        $this->successBlock('Success on publish migrations!');
        $this->breakLine();
    }

    private function publishUsersTableMigration(): void
    {
        try {
            !File::copy(
                FrameworkPath::MIGRATIONS->additionalPath('UsersTable.php'),
                ProjectPath::MIGRATIONS->value,
                '2022_10_17_02_08_26_create_users_table.php',
                force_mode: $this->input->getOption('force_mode') ?? false
            );
        } catch (PathAlreadyExist) {
            $this->failBlock("Fail to publish migration, migrations already exist in your project, you can set force mode like \"-f\"");
            $this->breakLine();
            return;
        }

        $this->successBlock('Success on publish migrations!');
        $this->breakLine();
    }
}

