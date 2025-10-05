<?php

namespace App\Vito\Plugins\Vitodeploy\TinyFileManager;

use App\DTOs\DynamicField;
use App\DTOs\DynamicForm;
use App\Plugins\AbstractPlugin;
use App\Plugins\RegisterSiteType;
use App\Plugins\RegisterViews;

class Plugin extends AbstractPlugin
{
    protected string $name = 'Tiny File Manager Plugin';

    protected string $description = 'Tiny File Manager plugin for VitoDeploy';

    public function boot(): void
    {
        RegisterViews::make('vitodeploy-tiny-file-manager')
            ->path(__DIR__.'/views')
            ->register();

        RegisterSiteType::make(TinyFileManager::id())
            ->label('Tiny File Manager')
            ->handler(TinyFileManager::class)
            ->form(DynamicForm::make([
                DynamicField::make('php_version')
                    ->component()
                    ->label('PHP Version'),
                DynamicField::make('password')
                    ->text()
                    ->label('Admin Password'),
                DynamicField::make('root_path')
                    ->text()
                    ->label('Wich path you want the file manager to have access to?')
                    ->default('/home/vito'),
            ]))
            ->register();
    }
}
