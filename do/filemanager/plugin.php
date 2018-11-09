<?php

$this->filemanager = new \LecAdmin\filemanager('/uploads/');
header('Content-Type: application/javascript');
die($this->filemanager->getTinyMCEJS());
