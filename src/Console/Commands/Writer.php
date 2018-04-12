<?php namespace Ill\Docs\Console\Commands;

use Illuminate\Config\Repository;

final class Writer
{

    private $format;
    private $folder;

    public function __construct(Repository $config)
    {
        $this->format = 'json';
        // Should always be this folder.
        $this->folder = 'docs/generated';
    }

    public function write(array $data = [])
    {
        $file = storage_path("{$this->folder}/api.{$this->format}");
        $this->putContents($file, json_encode($data, JSON_UNESCAPED_SLASHES));
    }

    public function writeStripped(string $data, string $collection, string $name)
    {
        $file = storage_path("{$this->folder}/resources/{$collection}/{$name}.{$this->format}");
        $this->putContents($file, $data);
    }

    public function saveEmpty(string $dir, string $filename)
    {
        $directory = storage_path("{$this->folder}/{$dir}");
        $file = storage_path("{$this->folder}/{$dir}/{$filename}");

        if (!is_dir($directory)) {
            if ( ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
            }
        }
        $this->putContents($file, json_encode(['empty'=>'but full potential'], JSON_UNESCAPED_SLASHES));
    }

    private function putContents($file, $data)
    {
        file_put_contents($file, $data);
    }
}