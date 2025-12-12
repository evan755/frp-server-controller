<?php declare(strict_types=1);

namespace App\Models;
class Container
{

    protected string $name;

    public function __construct(string $name)
    {
        $this->name = strtolower($name);
        print_r($this->container());
    }

    protected function container(): string
    {
        return $this->containers() . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR . 'docker-compose.yml';
    }

    protected function containers(): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'containers';
    }

    public function create(string $image, string $token, int $main_port, int $web_port, string $web_username, string $web_password): bool
    {
        is_dir(dirname($this->container())) || mkdir(dirname($this->container()), 0755, true);
        return (bool)yaml_emit_file($this->container(), [
            'services' => [
                'frp-server-instance' => [
                    'container_name' => $this->name,
                    'image' => $image,
                    'restart' => 'always',
                    'environment' => [
                        'FRP_MAIN_TOKEN' => $token,
                        'FRP_MAIN_PORT' => $main_port,
                        'FRP_WEB_PORT' => $web_port,
                        'FRP_WEB_USERNAME' => $web_username,
                        'FRP_WEB_PASSWORD' => $web_password,
                    ],
                    'ports' => [
                        '0.0.0.0:' . $main_port . '-' . $web_port . ':' . $main_port . '-' . $web_port
                    ],
                    'networks' => [
                        'frp-network'
                    ],
                ]
            ],
            'networks' => [
                'frp-network' => [
                    'name' => 'frp-network',
                    'driver' => 'bridge'
                ]
            ]
        ]);
    }

    public function delete(): bool
    {
        return (bool)unlink($this->container()) && rmdir(dirname($this->container()));
    }

    public function start(string $container_name): bool
    {
        $command = sprintf('docker-compose -f %s up -d', $this->container());
        $output = [];
        exec($command, $output, $result);
        return $result === 0;
    }

    public function stop(): bool
    {
        $command = sprintf('docker-compose -f %s down -v', $this->container());
        $output = [];
        exec($command, $output, $result);
        return $result === 0;
    }

    public function restart(): bool
    {
        $command = sprintf('docker-compose -f %s restart', $this->container());
        $output = [];
        exec($command, $output, $result);
        return $result === 0;
    }

}