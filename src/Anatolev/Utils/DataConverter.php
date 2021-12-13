<?php
namespace Anatolev\Utils;

use Anatolev\Exception\SourceFileException;

class DataConverter
{
    private $input_file;
    private $output_file;
    private $import_columns;
    private $import_data;

    public function __construct(
        private string $file_path,
        private string $output_dir = 'sql',
        private bool $array_mode = true
    ) {}

    public function convert()
    {
        if (!file_exists($this->file_path)) {
            throw new SourceFileException('Файл не существует');
        }

        try {
            $this->input_file = new \SplFileObject($this->file_path);
        } catch (RuntimeException $ex) {
            throw new SourceFileException('Не удалось открыть файл на чтение');
        }

        $this->import();

        if ($this->array_mode) {
            return $this->import_data;
        }

        $this->createSQLFile();
    }

    private function import(): void
    {
        $this->import_columns = $this->getHeaderData();

        foreach ($this->getNextLine() as $line) {
            if (!isset($line[0])) {
                continue;
            }

            if ($this->array_mode) {
                $values = [];
                foreach ($this->import_columns as $i => $column) {
                    $values[$column] = $line[$i];
                }
            }

            $this->import_data[] = $values ?? $line;
        }
    }

    private function createSQLFile(): void
    {
        if (!file_exists($this->output_dir)) {
            mkdir($this->output_dir);
        }

        $table = basename($this->file_path, '.csv');
        $import_columns = implode(', ', $this->import_columns);
        $data = "INSERT INTO {$table} ({$import_columns}) VALUES\n";

        $output_path = "{$this->output_dir}/{$table}.sql";

        $this->output_file = new \SplFileObject($output_path, 'w');
        $this->output_file->fwrite($data);

        $last_index = count($this->import_data) - 1;
        foreach ($this->import_data as $index => $row) {
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

            $this->output_file->fwrite($data);
        }
    }

    private function getHeaderData(): ?array
    {
        $this->input_file->rewind();
        $data = $this->input_file->fgetcsv();

        return $data;
    }

    private function getNextLine(): ?iterable
    {
        $result = null;

        while (!$this->input_file->eof()) {
            yield $this->input_file->fgetcsv();
        }

        return null;
    }
}
