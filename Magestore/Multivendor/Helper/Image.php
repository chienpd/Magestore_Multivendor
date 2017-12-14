<?php

namespace Magestore\Multivendor\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Image
 * @package Magestore\Multivendor\Helper
 */
class Image extends AbstractHelper
{
    /**
     * default small image size.
     */
    const SMALL_IMAGE_SIZE_WIDTH = 300;
    /**
     *
     */
    const SMALL_IMAGE_SIZE_HEIGHT = 300;
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    protected $_mediaDirectory;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var
     */
    protected $_imageUploaderFactory;

    /**
     * Block constructor.
     *
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem
    )
    {
        parent::__construct($context);
        $this->_mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;

    }

    /**
     * get media url of image.
     *
     * @param string $imagePath
     *
     * @return string
     */
    public function getMediaUrlImage($imagePath = '')
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $imagePath;
    }

    /**
     * @param AbstractModel $model
     * @param $fileId
     * @param $relativePath
     *
     * @param bool $makeResize
     * @throws LocalizedException
     */
    public function mediaUploadImage(
        AbstractModel $model,
        $fileId,
        $relativePath,
        $makeResize = false
    )
    {
        $imageRequest = $this->_getRequest()->getFiles($fileId);
        if ($imageRequest) {
            if (isset($imageRequest['name'])) {
                $fileName = $imageRequest['name'];
            } else {
                $fileName = '';
            }
        } else {
            $fileName = '';
        }
        if ($imageRequest && strlen($fileName)) {
            try {
                /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
                $uploader = $this->_objectManager->create(
                    'Magento\MediaStorage\Model\File\Uploader',
                    ['fileId' => $fileId]
                );
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
                $uploader->addValidateCallback('multivendor', $imageAdapter, 'validateUploadFile');
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);

                $mediaAbsolutePath = $this->_mediaDirectory->getAbsolutePath($relativePath);
                $uploader->save($mediaAbsolutePath);
                /*
                 * resize to small image
                 */
                if ($makeResize) {
                    $this->_resizeImage(
                        $mediaAbsolutePath . $uploader->getUploadedFileName(),
                        self::SMALL_IMAGE_SIZE_WIDTH
                    );
                    $imagePath = $this->_getResizeImageFileName($relativePath . $uploader->getUploadedFileName());
                } else {
                    $imagePath = $relativePath . $uploader->getUploadedFileName();
                }
                $model->setData($fileId, $imagePath);
            } catch (\Exception $e) {
                throw new LocalizedException(
                    __($e->getMessage())
                );
            }
        } else {
            if ($model->getData($fileId) && empty($model->getData($fileId . '/delete'))) {
                $model->setData($fileId, $model->getData($fileId . '/value'));
            } else {
                $model->setData($fileId, '');
            }
        }
    }

    /**
     * resize image.
     *
     * @param $fileName
     * @param $width
     * @param null $height
     */
    protected function _resizeImage($fileName, $width, $height = null)
    {
        /** @var \Magento\Framework\Image $image */
        $image = $this->_objectManager->create('Magento\Framework\Image', ['fileName' => $fileName]);
        $image->constrainOnly(true);
        $image->keepAspectRatio(true);
        $image->keepFrame(false);
        $image->resize($width, $height);
        $image->save($this->_getResizeImageFileName($fileName));
    }

    /**
     * @param $fileName
     *
     * @return string
     */
    protected function _getResizeImageFileName($fileName)
    {
        return dirname($fileName) . '/resize/' . basename($fileName);
    }
}