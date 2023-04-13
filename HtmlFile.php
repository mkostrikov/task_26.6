<?php

require __DIR__ . '/HtmlIterator.php';

class HtmlFile
{
    /** @var string */
    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Удаляет одиночные html тэги с заданным аттрибутом name, записывает результат в новый файл
     */
    public function removeTagByAttributeName(string $tag, array $nameValues, string $newFileName = 'tags_removed'): void
    {
        $pathInfo = pathinfo($this->path);
        $dirName = $pathInfo['dirname'];
        $fileName = $pathInfo['filename'];

        $namePatterns = [];
        foreach ($nameValues as $value) {
            $namePatterns[] = '|name\h*=\h*[\'"]{1}' . $value . '[\'"]{1}';
        }
        $firstNamePattern = str_replace('|', '', array_shift($namePatterns));

        $pattern = '/<' . $tag . '.*' . $firstNamePattern . '.*>/';

        if (!empty($namePatterns)) {
            $pattern = '/<' . $tag . '.*(' . $firstNamePattern . implode('', $namePatterns) . ').*>/';
        }

        if ($newFileName === '') {
            $newFileName = 'tags_removed';
        }
        $newFilePath = $dirName . '/' . $newFileName . '.html';

        $iterator = new HtmlIterator($this->path);
        foreach ($iterator as $row) {
            $result = preg_replace($pattern, '', $row);

            try {
                $newFile = fopen($newFilePath, 'ab');
                fwrite($newFile, $result);
                fclose($newFile);
            } catch (\Exception $e) {
                throw new \Exception('The file cannot be written.');
            }
        }
    }
}