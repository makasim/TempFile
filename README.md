TempFile
========

TempFile extends SplFileInfo class. It represents temporary file which will be removed at script shutdown.

Examples:

```php
<?php

//generate
$file = \Makasim\File\TempFile::generate();

//or create a temporary copy of persisted file
$file = \Makasim\File\TempFile::from('path_to_persisted_file');

//prevent file being removed
$file = \Makasim\File\TempFile::generate();

$file->persist();
```
