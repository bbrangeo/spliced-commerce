<?php
/*
 * This file is part of the SplicedCommerceBundle package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Spliced\Component\Commerce\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * ProductImage
 *
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class ProductImage
{
    
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /**
     * @MongoDB\Boolean
     */
    protected $isMain;
    
    /**
     * @MongoDB\String
     */
    protected $fileName;
    
    /**
     * @MongoDB\String
     */
    protected $filePath;
    
    /**
     * @MongoDB\String
     */
    protected $fileMd5;
    
    /**
     * @MongoDB\String
     */
    protected $fileType;
    
    /** @var File $uploadedImage */
    protected $uploadedImage;

    /**
     * getId
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * setIsMain
     *
     * @param boolean $isMain
     */
    public function setIsMain($isMain)
    {
        $this->isMain = $isMain;
        return $this;
    }
    
    /**
     * Get isMain
     *
     * @return boolean
     */
    public function getIsMain()
    {
        return $this->isMain;
    }
    
    
    /**
     * setFileName
     *
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
    
    /**
     * getFileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }
    
    /**
     * setFilePath
     *
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }
    
    /**
     * getFilePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
    /**
     * setFileMd5
     *
     * @param string $fileMd5
     */
    public function setFileMd5($fileMd5)
    {
        $this->fileMd5 = $fileMd5;
        return $this;
    }
    
    /**
     * getFileMd5
     *
     * @return string
     */
    public function getFileMd5()
    {
        return $this->fileMd5;
    }
    
    /**
     * setFileType
     *
     * @param string $fileType
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
        return $this;
    }
    
    /**
     * getFileType
     *
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }
    
    /**
     * getFullPath
     * 
     * @return string
     */
    public function getFullPath()
    {
        return $this->getFilePath().$this->getFileName();
    }
    
    /**
     * 
     */
    public function getUploadedImage()
    {
        return $this->uploadedImage;
    }
    
    public function setUploadedImage($uploadedImage)
    {
        $this->uploadedImage = $uploadedImage;
        return $this;
    }
}