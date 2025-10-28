<?php

namespace EHXDonate\Core;

/**
 * Plugin installer
 */
class Installer
{
    /**
     * Post-install hook for composer
     */
    public static function postInstall(): void
    {
        // Create necessary directories
        $directories = [
            EHXDonate_PATH . 'assets/js',
            EHXDonate_PATH . 'assets/css',
            EHXDonate_PATH . 'languages'
        ];
        
        foreach ($directories as $directory) {
            if (!file_exists($directory)) {
                wp_mkdir_p($directory);
            }
        }
        
        // Create .htaccess for public directory
        $htaccessContent = "Options -Indexes\n";
        file_put_contents(EHXDonate_PATH . 'public/.htaccess', $htaccessContent);
    }
}
