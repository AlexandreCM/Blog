<?php
namespace App;

use App\Security\ForbiddenException;

class Router {

    private string $viewPath;
    private \AltoRouter $router;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new \AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    public function post(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    public function match(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('POST|GET', $url, $view, $name);
        return $this;
    }

    public function url(string $name, array $params = [])
    {
        return $this->router->generate($name, $params);
    }

    public function run(): self
    {
        $match = $this->router->match();
        $view = $match['target'];
        $params = $match['params'];
        $router = $this;
        if (session_status() === PHP_SESSION_NONE) session_start();
        $isAdmin = isset($_SESSION['auth']);
        $layout = $isAdmin ? '/admin/layouts/default' : '/layouts/default';
        try {
            ob_start();
            require $this->viewPath . $view . '.php';
            $content = ob_get_clean();
            require $this->viewPath . $layout . '.php';
        } catch (ForbiddenException $e) {
            header('Location: ' . $this->url('login') . '?forbidden=1');
            exit();
        }


        return $this;
    }
}