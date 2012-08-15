<?php
namespace Makasim\File;
/**
 * @author Kotlyar Maksim <kotlyar.maksim@gmail.com>
 * @since 8/15/12
 */
class TempFile extends \SplFileInfo 
{
    public function __destruct()
    {
        if ($this->isFile()) {
            @unlink($this->getRealPath());
        }
    }

    /**
     * @param string $prefix
     * 
     * @return TempFile
     */
    public static function generate($prefix = 'php-tmp-file')
    {
        $fileClass = get_called_class();
        
        return new $fileClass(tempnam(sys_get_temp_dir(), $prefix));
    }

    /**
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
}