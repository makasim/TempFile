<?php
namespace Makasim\File;

/**
 * @author Kotlyar Maksim <kotlyar.maksim@gmail.com>
 * @since 8/15/12
 */
class TempFile extends \SplFileInfo
{
    /**
     * @var array of string paths array(path => path)
     */
    protected static $tempFiles = array();

    /**
     * {@inheritdoc}
     */
    public function __construct($fileName)
    {
        parent::__construct($fileName);

        self::registerRemoveTempFilesHandler();
        self::$tempFiles[$fileName] = $fileName;
    }

    /**
     * Persist file so that it would not be removed at the end of the script execution.
     *
     * @return \SplFileInfo
     */
    public function persist()
    {
        unset(self::$tempFiles[(string) $this]);

        return $this->getFileInfo();
    }

    /**
     * Creates a temp file with unique filename.
     *
     * @param string $prefix
     *
     * @return TempFile
     */
    public static function generate($prefix = 'php-tmp-file', $suffix = '')
    {
        $filename = tempnam(sys_get_temp_dir(), $prefix);

        if ($suffix) {
            $i = 0;
            do {
                $newFilename = $filename . $i++ . $suffix;
            } while (file_exists($newFilename));

            rename($filename, $newFilename);
            $filename = $newFilename;
        }

        return new static($filename);
    }

    /**
     * Creates a temp file from an exist file keeping it safe.
     *
     * @param mixed $file
     * @param string $prefix
     *
     * @return TempFile
     */
    public static function from($file, $prefix = 'php-tmp-file')
    {
        $tmpFile = static::generate($prefix);

        copy($file, $tmpFile);

        return $tmpFile;
    }

    private static function registerRemoveTempFilesHandler()
    {
        static $registered = false;
        if ($registered) {
            return;
        }

        $tempFiles = &self::$tempFiles;
        
        register_shutdown_function(function() use (&$tempFiles) {
            foreach ($tempFiles as $tempFile) {
                if (file_exists($tempFile)) {
                    @unlink($tempFile);
                }
            }
        });

        $registered = true;
    }
}