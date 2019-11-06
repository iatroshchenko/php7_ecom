<?php


namespace Core;


class Cache
{
    public function set(string $key, $data, int $time = 3600): bool
    {
        if ($time) {
            $content['data'] = $data;
            $content['end_time'] = time() + $time;
            if (
                file_put_contents(
                    $this->getFilePath($key),
                    serialize($content)
                )
            ) {
                return true;
            };
        }
        return false;
    }

    public function get(string $key)
    {
        $file = $this->getFilePath($key);
        if (file_exists($file)) {
            $content = unserialize(file_get_contents($file));
            if (time() <= $content['end_time']) return $content['data'];
            unlink($file);
        }
        return false;
    }

    public function delete(string $key): void
    {
        $file = $this->getFilePath($key);
        if (file_exists($file)) unlink($file);
    }

    private function getFilePath($key)
    {
        return CACHE . '/' . md5($key) . 'txt';
    }
}