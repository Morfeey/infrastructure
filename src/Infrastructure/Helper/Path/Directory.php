<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\Path;

class Directory
{
    use HelperPath;

    protected string $directory;

    /**
     * Directory constructor.
     *
     * @param $Directories | default self::getDocumentRoot
     */
    public function __construct($Directories = null)
    {
        $PathHandler = new PathHandler();
        $params = func_get_args();
        if (count($params) !== 0) {
            foreach ($params as $ItemDirectory) {
                $PathHandler->add($ItemDirectory);
            }
            $this->directory = $PathHandler->getResult();
        } else {
            $this->directory = self::getDocumentRoot();
        }
    }

    /**
     * Search Directories  (opening {a,b,c}, for search on 'a'|'b'|'c')
     *
     * @param string|* $Pattern
     * @param PathOptionInterface|null $searchOption |default SearchOption::searchOnlyHere()
     * @return array
     */
    public function getDirectories(string $Pattern = "*", PathOptionInterface $searchOption = null): array
    {
        $searchOption = $searchOption ?? SearchType::searchOnlyHere();
        $getDirectories = function (string $Directory) use (&$getDirectories, $Pattern, $searchOption) {
            $result = [];
            $Directory = $this->getLastSlashCustom($Directory);
            $List = glob($Directory . $Pattern, GLOB_BRACE | GLOB_MARK | GLOB_ONLYDIR);
            foreach ($List as $Item) {
                $Item = $this->deleteLastSlashCustom($Item);
                $result[] = $Item;
                $Temp = $getDirectories($Item);
                if ($searchOption->isRecurse() && count($Temp) != 0) {
                    foreach ($Temp as $item) {
                        $result[] = $item;
                    }
                }
            }
            return $result;
        };

        return $getDirectories($this->directory);
    }

    /**
     * Search Files  (opening {a,b,c}, for search on 'a'|'b'|'c')
     *
     * @param string|* $Pattern
     * @param PathOptionInterface|null $searchOption
     * @return array
     */
    public function getFiles(string $Pattern = "*", PathOptionInterface $searchOption = null): array
    {
        $searchOption = $searchOption ?? SearchType::searchOnlyHere();
        $GetFiles = function (string $Directory) use (&$GetFiles, $Pattern, $searchOption) {
            $result = [];
            $Dir = $this->getLastSlashCustom($Directory);
            $Directories = glob("$Dir*", GLOB_ONLYDIR | GLOB_MARK);
            $Files = function () use ($Dir, $Pattern) {
                $result = [];
                $files = glob($Dir . $Pattern, GLOB_BRACE);
                foreach ($files as $Item) {
                    if (is_file($Item)) {
                        $result[] = $Item;
                    }
                }
                return $result;
            };
            $result = array_merge($result, $Files());
            if ($searchOption->isRecurse()) {
                foreach ($Directories as $Item) {
                    $result = array_merge($result, $GetFiles($Item));
                }
            }
            return $result;
        };

        return $GetFiles($this->directory);
    }

    public function getLastDir(): self
    {
        $exp = explode("/", $this->directory);
        $result = $exp[count($exp) - 1];
        $this->directory = $result;
        return $this;
    }

    /** exit to the parent directory
     *
     * @return Directory
     */
    public function parent(): self
    {
        $ListLevels = explode("/", $this->directory);
        unset($ListLevels[count($ListLevels) - 1]);
        $result = "";
        foreach ($ListLevels as $level) {
            $result .= $level . "/";
        }
        $this->directory = $this->deleteLastSlashCustom($result);
        return $this;
    }

    /** merging of paths
     *
     * @param string $MergedDirectory
     * @param string|null $Directory
     * @return Directory
     */
    public function merge(string $MergedDirectory, string $Directory = null): self
    {
        $Directory = (is_null($Directory)) ? $this->directory : $Directory;
        $this->directory = $this->getLastSlashCustom($Directory) . $MergedDirectory;
        return $this;
    }

    public function deleteLastSlash(): self
    {
        $this->directory = self::deleteLastSlashCustom($this->directory);
        return $this;
    }

    public function getResult() :string
    {
        return $this->directory;
    }

    public function setLastSlash(): self
    {
        $this->directory = self::getLastSlashCustom($this->directory);
        return $this;
    }
}
