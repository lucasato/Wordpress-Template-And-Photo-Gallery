<?php

class DirectoryClass
{
    public function newFolder($path = false)
    {
        if (!$path) {
            return $path;
        } else {
            if ($this->folderExists($path)) {
                return true;
            } else {
                if ($this->createFolder($path)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
    private function folderExists($path = false)
    {
        // Get canonicalized absolute pathname
        $path = realpath($path);
        // If it exist, check if it's a directory
        return ($path !== false && is_dir($path)) ? true : false;
    }
    private function createFolder($path = false)
    {
        return mkdir($path, 0777);
    }
    //Remove file from full path
    public function removeFile($path = false)
    {
        if (!$path) {
            return $path;
        }
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return false;
        }
    }
}
