<?php
class FileUpload
{
    private $upload_dir = null;
    private $upload_url = null;
    private $wp_file_url= '';
    private $file       = null;
    private $fileName   = null;
    private $extension  = null;
    private $file_data  = array();

    public function __construct()
    {
        //Post file
        if (isset($_FILES['file'])) {
            $this->upload_dir = wp_upload_dir();
            $this->upload_url = $this->upload_dir['basedir'] .'/'. date('Y').'/'.date('m'); //Upload Path

            if (!$this->folderExists()) {
                if (!$this->createFolder()) {
                    echo 'PERMISSION DENIED';
                    exit;
                }
            }
            $this->file       = $_FILES['file'];
            $this->file_data  = pathinfo(preg_replace("/[^A-Z a-z 0-9\.]/", "", $this->file['name']));
            $this->extension  = $this->file_data['extension'];
            $this->fileName   = self::prettyFileName($this->file_data['filename']).'_'.time().'.'.$this->extension; //php 5.2.0
            $this->wp_file_url  = $this->upload_dir['baseurl'].'/'.date('Y').'/'.date('m').'/'.$this->fileName; //File name full http url
        }
    }

    private function prettyFileName($name)
    {
        $result = strtolower($name);

        $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
        $result = trim(preg_replace("/[\s-]+/", " ", $result));
        $result = preg_replace("/\s/", "-", $result);

        return $result;
    }

    public function upload()
    {
        //Start copying file.
        if ($this->upload_url !==  null && $this->file  !== null && copy($this->file['tmp_name'], $this->upload_url.'/'.$this->fileName)) {
            return true;
        } else {
            return false;
        }
    }
    private function folderExists()
    {
        // Get canonicalized absolute pathname
        $path = realpath($this->upload_url.'/');
        // If it exist, check if it's a directory
        return ($path !== false && is_dir($this->upload_url)) ? true : false;
    }

    private function createFolder()
    {
        return mkdir($this->upload_url.'/', 0777, true);
    }

    //Return extension without dot
    public function getExtension()
    {
        return strtolower($this->extension);
    }

    //Return file Size
    public function getSize()
    {
        return number_format(((filesize($this->upload_url.'/'.$this->fileName)/1024)/1024), 2);
    }

    //New Filename
    public function getName()
    {
        return $this->fileName;
    }

    //System Path Folder
    public function getPath()
    {
        return $this->upload_url.'/';
    }

    //Full System Directory + File Name
    public function getFullPath()
    {
        return $this->upload_url.'/'.$this->fileName;
    }

    //HTTP Url File
    public function getFileUrl()
    {
        return $this->wp_file_url;
    }
}
