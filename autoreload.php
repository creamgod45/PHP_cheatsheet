<?php
function PHP24023e844594d457c6d055c026b970f6($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                $obj=explode(".",$object);
                $obj=array_reverse($obj);
                if($obj[0]==="php" OR $obj[0]==="lock") unlink($dir . "/" . $object);
            }
        }
        reset($objects);
    }
}
PHP24023e844594d457c6d055c026b970f6("temp");
?>