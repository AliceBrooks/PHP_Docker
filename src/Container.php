<?php

declare(strict_types=1);

namespace PHP_Docker;

class Container
{
    public array $options;
    public string $name;
    public string $id;

    public function __construct(string $image, string $name = '')
    {
        $this->options['Image'] = $image;
        $this->name = $name;
    }

    public function addMount(string $path, string $mount_path = ''): void
    {
        if ($mount_path) {
            $this->options['HostConfig']['Binds'][] = $path . ':' . $mount_path;
        } else {
            $this->options['HostConfig']['Binds'][] = $path . ':' . $path;
        }
    }

    public function addVolume(string $path): void
    {
        $this->options['Volumes'][$path] = new \stdClass();
    }

    public function setEntryPoint(string $entry_point): void
    {
        $this->options['EntryPoint'] = explode(' ', $entry_point);
    }

    public function setAutoRemove(bool $remove): void
    {
        $this->options['HostConfig']['AutoRemove'] = $remove;
    }

    public function setEnv(string $key, string $value): void
    {
        $this->options['Env'][] = "$key=$value";
    }

    public function clearEnv(): void
    {
        unset($this->options['Env']);
    }

    public function getLogs(): Response
    {
        $request = new Request();
        $response = $request->send("containers/{$this->id}/logs?stdout=true");

        return $response;
    }

    public function setRestartPolicy($policy): void
    {
        $this->options['HostConfig']['RestartPolicy'] = [
            'name' => $policy
        ];
    }

    public function setOptions(array $options): void
    {
        $this->options = array_merge_recursive($this->options, $options);
    }

    public function create(): Response
    {
        $request = new Request();
        if ($this->name) {
            $response = $request->send("containers/create?name={$this->name}", json_encode($this->options));
        } else {
            $response = $request->send('containers/create', json_encode($this->options));
        }
        $this->id = $response->getId();

        return $response;
    }

    public static function getAllContainers(): Response
    {
        $request = new Request();
        $response = $request->send("containers/list");

        return $response;
    }

    public static function pullImage(string $image, array $options = []): Response
    {
        $request = new Request();
        $defaults = [
            'fromImage' => $image,
        ];
        $image = array_merge($defaults, $options);
        $query = http_build_query($image);
        $response = $request->send("images/create?$query", '', Request::METHOD_POST);

        return $response;
    }

    public function start(): Response
    {
        $request = new Request();
        $response = $request->send("containers/{$this->id}/start", '', Request::METHOD_POST);
        echo 'Started ' . $this->id . PHP_EOL;

        return $response;
    }

    public function stop(): Response
    {
        $request = new Request();
        $response = $request->send("containers/{$this->id}/stop", '', Request::METHOD_POST);
        echo 'Stopped ' . $this->id . PHP_EOL;

        return $response;
    }

    public function delete(): Response
    {
        $request = new Request();
        $response = $request->send("containers/{$this->id}", '', Request::METHOD_DELETE);
        echo 'Deleted ' . $this->id . PHP_EOL;

        return $response;
    }

    public function waitForCompletion(): Response
    {
        $request = new Request();
        $response = $request->send("containers/{$this->id}/wait", '', Request::METHOD_POST);

        return $response;
    }
}
