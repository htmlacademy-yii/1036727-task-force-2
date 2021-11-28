<?php
namespace Anatolev\Utils;

use Anatolev\Exception\SourceFileException;

class DataConverter
{
    private $files;
    private $file_object;
    private $file_name;
    private $file_columns;
    private $file_data;

    public function __construct(
        private string $input_dir,
        private string $output_dir = 'sql'
    ) {
        if (!file_exists($this->input_dir)) {
            throw new SourceFileException('Каталог не существует');
        }
    }

    public function convert(): void
    {
        $this->files = array_diff(scandir($this->input_dir), ['.', '..']);

        foreach ($this->files as $filename) {
            $file_path = "{$this->input_dir}/{$filename}";
            $file_type = mime_content_type($file_path);
            $this->file_name = str_replace('.csv', '', $filename);

            if (!in_array($file_type, ['application/csv', 'text/csv'])) {
                continue;
            }

            try {
                $this->file_object = new \SplFileObject($file_path);
            } catch (RuntimeException $ex) {
                throw new SourceFileException('Не удалось открыть файл на чтение');
            }

            $this->file_columns = $this->getHeaderData();
            $this->import();
            $this->createSQLFile();
        }
    }

    private function import(): void
    {
        $this->file_data = [];
        foreach ($this->getNextLine() as $line) {
            if (isset($line[0])) {
                $this->file_data[] = $line;
            }
        }
    }

    private function createSQLFile(): void
    {
        if (!file_exists($this->output_dir)) {
            mkdir($this->output_dir);
        }

        $file_path = "{$this->output_dir}/{$this->file_name}.sql";
        $columns = implode(', ', $this->file_columns);
        $data = "INSERT INTO {$this->file_name} ({$columns}) VALUES\n";

        $this->file_object = new \SplFileObject($file_path, 'w');
        $this->file_object->fwrite($data);

        $last_index = count($this->file_data) - 1;
        foreach ($this->file_data as $index => $row) {
            $values = [];

            foreach ($row as $value) {
                if (is_numeric($value)) {
                    $values[] = $value;
                } else {
                    $values[] = "'{$value}'";
                }
            }

            $data = '(' . implode(', ', $values) . ')';
            $data .= $index === $last_index ? ";\n" : ",\n";

            $this->file_object->fwrite($data);
        }
    }

    private function getHeaderData(): ?array
    {
        $this->file_object->rewind();
        $data = $this->file_object->fgetcsv();

        return $data;
    }

    private function getNextLine(): ?iterable
    {
        $result = null;

        while (!$this->file_object->eof()) {
            yield $this->file_object->fgetcsv();
        }

        return null;
    }
}
