<?php
/**
 * Class File - zapouzdreni prace se souborem
 */
class File implements FileInterface {

    /** @var resource */
    private $fileHandler = false;
    /** @var string */
    private $name;
    /** @var string */
    private $path;
    /** @var string */
    private $filePath;    

    //=====================================================
    //== Konstruktory =====================================
    //=====================================================

    /**
     * Konstruktor tridy.
     *
     * @param string $filePath
     */
    public function __construct($filePath) {

        preg_match('#^(.*/)([^/]*)$#', $filePath, $match);
        $this->path = $match[1];
        $this->name = $match[2];
        $this->filePath = $filePath;
    }

    /**
     * Destruktor tridy.
     */
    public function __destruct() {
        $this->close();
    }


    //=====================================================
    //== Verejne metody ===================================
    //=====================================================


    /**
     * Zjisti, zda soubor existuje.
     *
     * @return bool
     */
    public function exists() {
        return is_file($this->filePath);
    }

    /**
     * Metoda vraci nazev souboru.
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Metoda vraci adresar souboru.
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Metoda vraci uplnou cestu k souboru.
     *
     * @return string
     */
    public function getFilePath() {
        return $this->filePath;
    }

    /**
     * Nastavi prava.
     *
     * @param int       $rights     Octalove zapsane prava (pr. 0755)
     */
    public function setRights($rights) {
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        @chmod($this->filePath, $rights);
    }

    /**
     * Fuknce presune soubor na cilove misto.
     *
     * @param string    $destination
     * @return bool
     */
    public function rename($destination) {
        $result = rename($this->filePath, $destination);
        preg_match('#^(.*/)([^/]*)$#', $destination, $match);
        $this->path = $match[1];
        $this->name = $match[2];
        $this->filePath = $destination;
        return $result;
    }

    /**
     * Ziska velikost souboru v Bytech.
     *
     * @return int
     */
    public function getSize() {
        return filesize($this->filePath);
    }

    /**
     * Vraci obsah souboru.
     *
     * @return string
     */
    public function getContents() {
        return file_get_contents($this->filePath);
    }

    /**
     * Vraci timestamp posledniho pristupu k souboru.
     *
     * @return int
     */
    public function getAccessTime() {
        $status = $this->getStat();
        return $status[8];
    }

    /**
     * Vraci cas posledni modifikace.
     *
     * @return int
     */
    public function getModificationTime() {
        $status = $this->getStat();
        return $status[9];
    }

    /**
     * Vraci cas vytvoreni souboru.
     *
     * @return int
     */
    public function getCreateTime() {
        return $this->getModificationTime();
    }

    /**
     * Vraci statistiku souboru.
     *
     * Numeric Associative (since PHP 4.0.6) Description
     * 0       dev                           device number
     * 1       ino                           inode number
     * 2       mode                          inode protection mode
     * 3       nlink                         number of links
     * 4       uid                           userid of owner
     * 5       gid                           groupid of owner
     * 6       rdev                          device type, if inode device
     * 7       size                          size in bytes
     * 8       atime                         time of last access (Unix timestamp)
     * 9       mtime                         time of last modification (Unix timestamp)
     * 10      ctime                         time of last inode change (Unix timestamp)
     * 11      blksize                       blocksize of filesystem IO
     * 12      blocks                        number of 512-byte blocks allocated
     *
     * @return array
     */
    public function getStat() {
        return stat($this->getFilePath());
    }

    /**
     * @return string
     */
    public function getMime() {
        return FileUtil::getMime($this->filePath);
    }

    /**
     * @return string
     */
    public function getExtension() {
        return strtolower(preg_replace('/^.*\.(.*?)$/', '$1', $this->name));
    }

    /**
     * @param string    $extension
     */
    public function changeExtension($extension) {
        $this->name = preg_replace('/\..+$/', '.' . $extension, $this->name);
        $this->rename($this->path . '/' . $this->name);
    }

    public function delete() {
        if(file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

    /**
     * @param   string  $dest
     * @return  bool
     */
    public function copy($dest) {
        $result = copy($this->filePath, $dest);
        return $result;
    }

    //=====================================================
    //== Verejne metody pro praci pes file handler ========
    //=====================================================

    /**
     * Metoda otevre soubor.
     *
     * @param string    $mode
     * @return bool
     */
    public function open($mode = 'r') {
        $this->fileHandler = fopen($this->filePath, $mode);
        return $this->fileHandler !== false;
    }

    /**
     * Metoda uzavre soubor.
     */
    public function close() {
        if($this->fileHandler !== false) {
            /** @noinspection PhpUsageOfSilenceOperatorInspection */
            @fclose($this->fileHandler);
            $this->fileHandler = false;
        }
    }

    /**
     * Vrati nacteny radek z ukazatele souboru.
     *
     * @param int       $lenght     Omezeni poctu nactenych znaku.
     * @return string|bool
     */
    public function getLine($lenght = null) {
        $return = false;
        if($this->fileHandler !== false) {
            if($lenght > 0) {
                $lenght++;
                $return = fgets($this->fileHandler, $lenght);
            } else {
                $return = fgets($this->fileHandler);
            }
        }
        return $return;
    }

    /**
     * Metoda zapise predana data do souboru.
     *
     * @param string $buffer
     * @return bool|int
     */
    public function writeBuffer($buffer) {
        $result = false;
        if($this->fileHandler !== false) {
            $result = fwrite($this->fileHandler, $buffer);
        }
        return $result;
    }

    /**
     * Posune offset na zacatek souboru.
     *
     * @return bool
     */
    public function seekToBegin() {
        $result = false;
        if($this->fileHandler !== false) {
            $result = (fseek($this->fileHandler, 0) == 0);
        }
        return $result;
    }
}

/**
 * Class FileException
 */
class FileException extends Exception {}
