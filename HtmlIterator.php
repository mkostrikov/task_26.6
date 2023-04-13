<?php

/**
 * Итератор файлов
 */

class HtmlIterator implements Iterator
{
    /**
     * Указатель на файл.
     *
     * @var resource
     */
    protected $filePointer = null;

    /**
     * Счётчик строк.
     *
     * @var int
     */
    public $rowCounter = null;

    /**
     * Текущий элемент, который возвращается на каждой итерации.
     *
     * @var string
     */
    public $currentElement = null;

    /**
     * Конструктор пытается открыть файл. Выдаёт исключение при ошибке.
     *
     * @param string $file файл.
     *
     * @throws \Exception
     */
    public function __construct($file)
    {
        try {
            $this->filePointer = fopen($file, 'rb');
        } catch (\Exception $e) {
            throw new \Exception('The file "' . $file . '" cannot be read.');
        }
    }

    /**
     * Сбрасывает указатель файла.
     */
    public function rewind(): void
    {
        $this->rowCounter = 0;
        rewind($this->filePointer);
    }

    /**
     * Проверяет, достигнут ли конец файла.
     *
     * @return bool Возвращает true при достижении EOF, в ином случае false.
     */
    public function next(): bool
    {
        if (is_resource($this->filePointer)) {
            return !feof($this->filePointer);
        }

        return false;
    }

    /**
     * Проверяет, является ли следующая строка допустимой.
     *
     * @return bool Если следующая строка является допустимой.
     */
    public function valid(): bool
    {
        if (!$this->next()) {
            if (is_resource($this->filePointer)) {
                fclose($this->filePointer);
            }

            return false;
        }

        return true;
    }

    /**
     * Возвращает текущую строку.
     *
     * @return ?string Текущая строка.
     */
    public function current(): ?string
    {
        $this->currentElement = stream_get_line($this->filePointer, 0, '>');
        $this->rowCounter++;

        if ($this->currentElement === false) {
            return null;
        }
        return $this->currentElement . '>';
    }

    /**
     * Возвращает номер текущей строки.
     *
     * @return int Номер текущей строки.
     */
    public function key(): int
    {
        return $this->rowCounter;
    }
}