<?php

/*
 * ----------------------------------------------------------------------------
 * PHP ROUTER
 * https://github.com/phprouter/main
 *
 * Ce fichier est basé sur le travail original de PHP ROUTER, disponible sous
 * la licence MIT.
 * ----------------------------------------------------------------------------
 * Copyright (c) 2021 <info@phprouter.com>
 * Copyright (c) 2024 Aymeric-210297
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * ----------------------------------------------------------------------------
 * Modifications apportées par Aymeric-210297, 2024 :
 * - Ajout de la gestion de la méthode HEAD.
 * - Ajout de la possibilité de mettre le token CSRF dans les query params.
 * - Suppression de la possibilité de mettre un fichier à inclure dans les
 *   routes pour privilégier l'utilisation par fonction callback.
 * - Application de la pull request :
 *   https://github.com/phprouter/main/pull/52 pour corriger le problème avec
 *   les query params.
 * - Divers ajustements mineurs pour adapter le code aux besoins spécifiques de notre projet.
 * ----------------------------------------------------------------------------
 */

function get($route, $callback)
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'HEAD') {
        route($route, $callback);
    }
}

function post($route, $callback)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        route($route, $callback);
    }
}

function put($route, $callback)
{
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        route($route, $callback);
    }
}

function patch($route, $callback)
{
    if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
        route($route, $callback);
    }
}

function delete($route, $callback)
{
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        route($route, $callback);
    }
}

function any($route, $callback)
{
    route($route, $callback);
}

function route($route, $callback)
{
    if (!is_callable($callback)) {
        throw new Exception("Callback is not callable");
    }

    if ($route == "/404") {
        call_user_func_array($callback, []);
        exit();
    }

    $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
    $request_url = strtok($request_url, '?');
    $request_url = rtrim($request_url, '/');
    $route_parts = explode('/', $route);
    $request_url_parts = explode('/', $request_url);
    array_shift($route_parts);
    array_shift($request_url_parts);

    if ($route_parts[0] == '' && count($request_url_parts) == 0) {
        call_user_func_array($callback, []);
        exit();
    }

    if (count($route_parts) != count($request_url_parts)) {
        return;
    }

    $parameters = [];
    for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
        $route_part = $route_parts[$__i__];
        if (preg_match("/^[$]/", $route_part)) {
            $route_part = ltrim($route_part, '$');
            array_push($parameters, $request_url_parts[$__i__]);
            $$route_part = $request_url_parts[$__i__];
        } elseif ($route_parts[$__i__] != $request_url_parts[$__i__]) {
            return;
        }
    }

    call_user_func_array($callback, $parameters);
    exit();
}

// Utility functions used to prevent XSS and CSRF attacks

function out($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function get_csrf()
{
    if (!isset($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(50));
    }

    return $_SESSION['csrf'];
}

function set_csrf()
{
    return '<input type="hidden" name="csrf" value="' . get_csrf() . '">';
}

function is_csrf_valid()
{
    if (!isset($_SESSION['csrf'])) {
        return false;
    }

    if (isset($_POST['csrf'])) {
        return $_SESSION['csrf'] === $_POST['csrf'];
    }

    if (isset($_GET['csrf'])) {
        return $_SESSION['csrf'] === $_GET['csrf'];
    }

    return false;
}
