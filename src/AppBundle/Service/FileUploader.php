<?php
/**
 * Created by PhpStorm.
 * User: pivchenberg
 * DateTime: 20.12.2016 17:55
 */

namespace AppBundle\Services;


use StarterPackBundle\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileUploader
{

	static $filePrefix = '.ht';
	private $targetDir;
	private $fullTargetDir;
	private $user;

	public function __construct(ContainerInterface $container, $targetDir = "/web/uploads/files")
	{
		$this->container = $container;
		$this->targetDir = $targetDir;
		$this->fullTargetDir = $this->container->getParameter('kernel.root_dir') . '/..' . $targetDir;
		$this->user = $this->container->get('security.token_storage')->getToken()->getUser();
	}

	public function upload(File $file)
	{
		$uploadedFile = $file->getReadyToUploadFile();
		//Оригинальное имя при загрузке
		$file->setUploadName($uploadedFile->getClientOriginalName());
		//Имя файла на сервере
		$serverName = sha1( uniqid() . $uploadedFile->getRealPath() . $uploadedFile->getMTime() . microtime() );
		$file->setServerName($serverName);
		//Url по которому доступен файл
		$file->setUrlName( sha1($serverName . uniqid()) );
		//Папка в которой находится файл
		$file->setFilePath($this->targetDir);
		//Префикс файла
		$file->setPrefix(self::$filePrefix);
		//Расширение файла
		$file->setExtension($uploadedFile->guessClientExtension());
		//Размер файла в байтах
		$file->setBytesSize($uploadedFile->getSize());
		//Размер файла для отображения на морде
		$file->setDisplaySize($this->humanFileSize($uploadedFile->getSize()));
		//Пользователь
		$file->setUser($this->user);

		$uploadedFile->move($this->fullTargetDir, $file->getServerFileName());

		return $file;
	}

	public function uploadImage(File $file)
	{
		$uploadedFile = $file->getReadyToUploadFile();
		//Оригинальное имя при загрузке
		$file->setUploadName($uploadedFile->getClientOriginalName());
		//Имя файла на сервере
		$serverName = sha1( uniqid() . $uploadedFile->getRealPath() . $uploadedFile->getMTime() . microtime() ) . '.' . $uploadedFile->guessClientExtension();
		$file->setServerName($serverName);
		//Папка в которой находится файл
		$file->setFilePath($this->targetDir);
		//Префикс пустой
		$file->setPrefix(null);
		//Расширение файла
		$file->setExtension($uploadedFile->guessClientExtension());
		//Размер файла в байтах
		$file->setBytesSize($uploadedFile->getSize());
		//Размер файла для отображения на морде
		$file->setDisplaySize($this->humanFileSize($uploadedFile->getSize()));
		//Пользователь
		$file->setUser($this->user);

		$uploadedFile->move($this->fullTargetDir, $file->getServerFileName());

		return $file;
	}

	public static function humanFileSize($size, $unit = "") {
		if( (!$unit && $size >= 1<<30) || $unit == " гб")
			return number_format($size/(1<<30), 2)." гб";
		if( (!$unit && $size >= 1<<20) || $unit == " мб")
			return number_format($size/(1<<20), 2)." мб";
		if( (!$unit && $size >= 1<<10) || $unit == " кб")
			return number_format($size/(1<<10), 2)." кб";
		return number_format($size)." байт";
	}

	/**
	 * @param mixed $targetDir
	 * @return $this
	 */
	public function setTargetDir($targetDir)
	{
		$this->targetDir = $targetDir;
		$this->fullTargetDir = $this->container->getParameter('kernel.root_dir') . '/..' . $targetDir;
		
		return $this;
	}
}