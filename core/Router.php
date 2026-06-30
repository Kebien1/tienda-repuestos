<?php

class Router {
    private $routes = [];

    // Registrar ruta GET
    public function get($route, $action) {
        $this->routes['GET'][$route] = $action;
    }

    // Registrar ruta POST
    public function post($route, $action) {
        $this->routes['POST'][$route] = $action;
    }

    // Despachar la ruta actual
    public function dispatch($url, $method) {
        // Limpiar URL y remover slash final
        $url = trim($url, '/');
        if (empty($url)) {
            $url = '';
        }

        // Buscar ruta exacta
        if (isset($this->routes[$method][$url])) {
            $action = $this->routes[$method][$url];
            $this->executeAction($action);
            return;
        }

        // Buscar rutas dinámicas con parámetros numéricos (ej. proveedores/editar/5)
        foreach ($this->routes[$method] as $route => $action) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
            $pattern = '@^' . trim($pattern, '/') . '$@';

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches); // Quitar la coincidencia completa
                $this->executeAction($action, $matches);
                return;
            }
        }

        // Ruta no encontrada
        http_response_code(404);
        echo "404 - Página no encontrada";
    }

    private function executeAction($action, $params = []) {
        list($controllerName, $methodName) = explode('@', $action);
        
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $methodName)) {
                    call_user_func_array([$controller, $methodName], $params);
                    return;
                }
            }
        }
        
        echo "Error: No se pudo resolver el controlador o acción $action.";
    }
}
