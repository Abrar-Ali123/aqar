<?php
namespace App\Plugins;

class SamplePlugin implements PluginInterface
{
    public function install() { /* تنفيذ التثبيت */ }
    public function uninstall() { /* تنفيذ الإزالة */ }
    public function info() { return ['name' => 'إضافة تجريبية', 'version' => '1.0']; }
}
