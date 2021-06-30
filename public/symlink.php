<?php

$target = '../storage/app/public';

$shortcut = '/storage';

symlink($target, $shortcut);

?>