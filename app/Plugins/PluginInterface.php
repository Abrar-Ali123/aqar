<?php
namespace App\Plugins;

interface PluginInterface
{
    public function install();
    public function uninstall();
    public function info();
}
