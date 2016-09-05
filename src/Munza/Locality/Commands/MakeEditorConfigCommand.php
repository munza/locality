<?php

namespace Munza\Locality\Commands;

use Illuminate\Console\Command;

class MakeEditorConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:editorconfig
                            {-- default : use the default configuration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the editorconfig file';

    /**
     * List of choices for the editorconfig file.
     *
     * @var array
     */
    protected $choices = [
        'indent_size' => ['4', '2'],
        'indent_style' => ['space', 'tab'],
        'end_of_line' => ['lf', 'cr'],
        'insert_final_newline' => ['true', 'false'],
        'trim_trailing_whitespace' => ['true', 'false'],
    ];

    /**
     * The list of variables to be writted in the stub file.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->cannotWriteFile()) return;

        if ($this->wantDefaultSettings()) {
            $this->setDefaultVariables();
        } else {
            $this->chooseVariables();
        }

        $fileData = $this->prepareFile();

        $this->writeFile($fileData);

        $this->info('.editorconfig created.');
    }

    /**
     * Check if the user wants to use default settings.
     *
     * @return bool
     */
    private function wantDefaultSettings()
    {
        return $this->option('default') || $this->confirm('Want to use the default settings?');
    }

    /**
     * Check if the file is already exisits and then confirm for overwrite.
     *
     * @return bool
     */
    private function cannotWriteFile()
    {
        if (file_exists(base_path('.editorconfig'))) {
            return !$this->confirm('File already exists! Want to overwrite existing .editorconfig?');
        }

        return false;
    }

    /**
     * Set the chosen variables before replacing in the stub file.
     *
     * @return void
     */
    private function setDefaultVariables()
    {
        foreach ($this->choices as $key => $options) {
            $this->variables[$key] = $options[0];
        }
    }

    /**
     * Set the chosen variables before replacing in the stub file.
     *
     * @return void
     */
    private function chooseVariables()
    {
        foreach ($this->choices as $key => $options) {
            $this->variables[$key] = $this->choice($key.'?', $options, 0);
        }
    }

    /**
     * Replace the variables with choices.
     *
     * @return string
     */
    private function prepareFile()
    {
        $fileData = file_get_contents(__DIR__.'/../resources/stubs/editorconfig.stub');

        foreach ($this->variables as $key => $value) {
            $fileData = str_replace('{{'.$key.'}}', $value, $fileData);
        }

        return $fileData;
    }

    /**
     * Write data in .editorconfig file.
     *
     * @param  string $fileData
     * @return void
     */
    private function writeFile($fileData)
    {
        file_put_contents(base_path('.editorconfig'), $fileData);
    }
}
