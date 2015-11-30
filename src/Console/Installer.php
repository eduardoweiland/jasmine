<?php

namespace App\Console;

use Composer\Script\Event;
use Composer\IO\IOInterface;
use Exception;

/**
 * Provides installation hooks for when this application is installed via
 * composer.
 */
class Installer
{
    /**
     * Does some routine installation tasks so people don't have to.
     *
     * @param Event $event The composer event object.
     * @throws Exception Exception raised by validator.
     * @return void
     */
    public static function postInstall(Event $event)
    {
        $io = $event->getIO();

        $rootDir = dirname(dirname(__DIR__));

        static::createAppConfig($rootDir, $io);
        static::createWritableDirectories($rootDir, $io);

        if (static::confirmAction($io, 'Set Folder Permissions?')) {
            static::setFolderPermissions($rootDir, $io);
        }

        static::setSecuritySalt($rootDir, $io);
        static::setDebugLevel($rootDir, $event);

        if (class_exists('\Cake\Codeception\Console\Installer')) {
            \Cake\Codeception\Console\Installer::customizeCodeceptionBinary($event);
        }
    }

    /**
     * Asks the user for confirmation on some action to be taken.
     *
     * @param IOInterface $io IO interface to write to console.
     * @param string $question
     * @param boolean $default Default answer
     * @return boolean
     */
    protected static function confirmAction(IOInterface $io, $question, $default = true)
    {
        if (!$io->isInteractive()) {
            return $default;
        }

        $validator = function ($arg) {
            if (in_array($arg, ['Y', 'y', 'N', 'n'])) {
                return $arg;
            }
            throw new Exception('This is not a valid answer. Please choose Y or n.');
        };

        $defaultAnswer = $default ? 'Y' : 'n';

        $msg = '<question>' . $question . '</question> '
                . '<info>(Default to ' . $defaultAnswer . ')</info> '
                . '[<comment>Y,n</comment>]: ';

        $answer = $io->askAndValidate($msg, $validator, 10, $defaultAnswer);

        if (in_array($answer, ['Y', 'y'])) {
            return true;
        }

        return $default;
    }

    /**
     * Create the config/app.php file if it does not exist.
     *
     * @param string $dir The application's root directory.
     * @param IOInterface $io IO interface to write to console.
     * @return void
     */
    private static function createAppConfig($dir, IOInterface $io)
    {
        $appConfig = $dir . '/config/app.php';
        $defaultConfig = $dir . '/config/app.default.php';
        if (!file_exists($appConfig)) {
            copy($defaultConfig, $appConfig);
            $io->write('Created `config/app.php` file');
        }
    }

    /**
     * Create the `logs` and `tmp` directories.
     *
     * @param string $dir The application's root directory.
     * @param IOInterface $io IO interface to write to console.
     * @return void
     */
    private static function createWritableDirectories($dir, IOInterface $io)
    {
        $paths = [
            'logs',
            'tmp',
            'tmp/cache',
            'tmp/cache/models',
            'tmp/cache/persistent',
            'tmp/cache/views',
            'tmp/sessions',
            'tmp/tests'
        ];

        foreach ($paths as $path) {
            $path = $dir . '/' . $path;
            if (!file_exists($path)) {
                mkdir($path);
                $io->write('Created `' . $path . '` directory');
            }
        }
    }

    /**
     * Set globally writable permissions on the "tmp" and "logs" directory.
     *
     * This is not the most secure default, but it gets people up and running quickly.
     *
     * @param string $dir The application's root directory.
     * @param IOInterface $io IO interface to write to console.
     * @return void
     */
    private static function setFolderPermissions($dir, IOInterface $io)
    {
        // Change the permissions on a path and output the results.
        $changePerms = function ($path, $perms, $io) {
            // Get current permissions in decimal format so we can bitmask it.
            $currentPerms = octdec(substr(sprintf('%o', fileperms($path)), -4));
            if (($currentPerms & $perms) == $perms) {
                return;
            }

            $res = chmod($path, $currentPerms | $perms);
            if ($res) {
                $io->write('Permissions set on ' . $path);
            } else {
                $io->write('Failed to set permissions on ' . $path);
            }
        };

        $walker = function ($dir, $perms, IOInterface $io) use (&$walker, $changePerms) {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = $dir . '/' . $file;

                if (!is_dir($path)) {
                    continue;
                }

                $changePerms($path, $perms, $io);
                $walker($path, $perms, $io);
            }
        };

        $worldWritable = bindec('0000000111');
        $walker($dir . '/tmp', $worldWritable, $io);
        $changePerms($dir . '/tmp', $worldWritable, $io);
        $changePerms($dir . '/logs', $worldWritable, $io);
    }

    /**
     * Set the security.salt value in the application's config file.
     *
     * @param string $dir The application's root directory.
     * @param IOInterface $io IO interface to write to console.
     * @return void
     */
    private static function setSecuritySalt($dir, IOInterface $io)
    {
        $config = $dir . '/config/app.php';
        $originalContent = file_get_contents($config);

        $newKey = hash('sha256', $dir . php_uname() . microtime(true));
        $newContent = str_replace('__SALT__', $newKey, $originalContent, $count);

        if ($count == 0) {
            $io->write('No Security.salt placeholder to replace.');
            return;
        }

        $result = file_put_contents($config, $newContent);
        if ($result) {
            $io->write('Updated Security.salt value in config/app.php');
            return;
        }
        $io->write('Unable to update Security.salt value.');
    }

    /**
     * Set the debug flag value in the application's config file.
     *
     * @param string $dir The application's root directory.
     * @param Event $event The composer event object.
     * @return void
     */
    private static function setDebugLevel($dir, Event $event)
    {
        $io = $event->getIO();
        $debug = $event->isDevMode() ? 'true' : 'false';

        $config = $dir . '/config/app.php';
        $originalContent = file_get_contents($config);

        $newContent = str_replace('__DEBUG__', $debug, $originalContent, $count);

        if ($count == 0) {
            $io->write('No debug placeholder to replace.');
            return;
        }

        $result = file_put_contents($config, $newContent);
        if ($result) {
            $io->write('Updated debug value in config/app.php');
            return;
        }
        $io->write('Unable to update debug value.');
    }
}
