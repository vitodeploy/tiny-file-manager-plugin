<?php

namespace App\Vito\Plugins\Vitodeploy\TinyFileManager;

use App\SiteTypes\PHPBlank;
use Illuminate\Validation\Rule;

class TinyFileManager extends PHPBlank
{
    public static function id(): string
    {
        return 'tiny-file-manager';
    }

    public function createRules(array $input): array
    {
        return [
            'php_version' => [
                'required',
                Rule::in($this->site->server->installedPHPVersions()),
            ],
            'password' => [
                'required',
            ],
            'root_path' => [
                'nullable',
            ],
        ];
    }

    public function createFields(array $input): array
    {
        return [
            'web_directory' => '',
            'php_version' => $input['php_version'] ?? '',
        ];
    }

    public function data(array $input): array
    {
        return [
            'password' => password_hash($input['password'], PASSWORD_DEFAULT) ?? '',
            'root_path' => $input['root_path'] ?? '/home/'.$this->site->user,
        ];
    }

    /**
     * @throws SSHError
     */
    public function install(): void
    {
        parent::install();

        $download = view('vitodeploy-tiny-file-manager::download', [
            'path' => $this->site->getWebDirectoryPath(),
        ]);
        $this->site->server->ssh($this->site->user)->exec(
            $download,
            'download-tinyfilemanager',
            $this->site->id
        );

        $config = '<?php'."\n".view('vitodeploy-tiny-file-manager::config', [
            'password' => $this->site->type_data['password'],
            'rootPath' => $this->site->type_data['root_path'],
        ]);
        $this->site->server->ssh($this->site->user)->write(
            $this->site->getWebDirectoryPath().'/config.php',
            $config,
            $this->site->user
        );
    }
}
