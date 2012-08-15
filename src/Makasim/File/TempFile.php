<?php
namespace Makasim\File;
/**
 * @author Kotlyar Maksim <kotlyar.maksim@gmail.com>
 * @since 8/15/12
 */
class TempFile extends \SplFileInfo 
{
    /**
     * @var array
     */
    protected static $tempFilePool = array();

    /**
     * {@inheritdoc}
     */
    public function __construct($file_name)
    {
        self::$tempFilePool[] = $this;
        
        parent::__construct($file_name);
    }
    
    public function __destruct()
    {
        if ($this->isFile()) {
            var_dump('OK');
            @unlink($this->getRealPath());
        }
    }
    
    public static function generate($prefix = 'php-tmp-file')
    {
        $fileClass = get_called_class();
        
        return new $fileClass(tempnam(sys_get_temp_dir(), $prefix));
    }
}