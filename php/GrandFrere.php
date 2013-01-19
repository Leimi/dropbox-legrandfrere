<?php
class GrandFrere {
	protected $Dropbox;
	protected $maxedOut = false;
	protected $conflictedFiles = array();
	protected $deletedFiles = array();
	protected $languages = array(
		'en' => "'s conflicted copy",
		'ja' => " 上の問題のあるコピー",
		'fr' => "en conflit",
		'es' => "Copia conflictiva de",
		'de' => "In Konflikt stehende Kopie von"
	);

	public function __construct(Dropbox_API $dropbox) {
		$this->Dropbox = $dropbox;
	}

	public function getFiles() {
		return $this->conflictedFiles;
	}

	public function setFiles($files) {
		$this->conflictedFiles = $files;
	}

	public function getDeletedFiles() {
		return $this->deletedFiles;
	}

	public function isMaxedOut() {
		return $this->maxedOut;
	}

	public function inspectTheHouse() {
		$conflicted = array();
		$limitReached = $this->maxedOut = false;
		foreach ($this->languages as $key => $value) {
			$conflicted[$key] = $this->Dropbox->search($value, 'dropbox');
			$this->conflictedFiles += $conflicted[$key];
			if (!$limitReached && count($conflicted[$key]) > 999) $limitReached = true;
		}
		if ($limitReached) $this->maxedOut = true;
		return $this->conflictedFiles;
	}

	public function clearTheHouse($files = array()) {
		if (!empty($files))
			$this->setFiles($files);
		$this->deletedFiles = array();
		foreach ($this->conflictedFiles as $key => $value) {
			$path = is_string($value) ? $value : $value['path'];
			if (!empty($path)) {
				$deleted = $this->Dropbox->delete($path, 'dropbox');
				if ($deleted) {
					unset($this->conflictedFiles[$key]);
					$this->deletedFiles[] = $deleted;
				}				
			}
		}
		return $this->deletedFiles;
	}

	public function manMode() {
		$this->inspectTheHouse();
		$this->clearTheHouse();
		$deleted = $this->getDeletedFiles();
		while ($this->maxedOut) {
			$this->inspectTheHouse();
			$this->clearTheHouse();
			$deleted += $this->getDeletedFiles();
		}
		return array($this->conflictedFiles, $deleted);
	}
}