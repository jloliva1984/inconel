<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth routes
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');

// Dashboard
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Users (Admin only)
$routes->group('usuarios', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('listado', 'Users::listado');
    $routes->get('crear', 'Users::crear');
    $routes->post('guardar', 'Users::guardar');
    $routes->get('editar/(:num)', 'Users::editar/$1');
    $routes->post('actualizar/(:num)', 'Users::actualizar/$1');
    $routes->delete('eliminar/(:num)', 'Users::eliminar/$1');
    $routes->post('eliminar/(:num)', 'Users::eliminar/$1');
    $routes->get('datatable', 'Users::datatable');
});

// Viviendas (Admin and Tecnico)
$routes->group('viviendas', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Viviendas::index');
    $routes->get('listado', 'Viviendas::listado');
    $routes->get('crear', 'Viviendas::crear');
    $routes->post('guardar', 'Viviendas::guardar');
    $routes->get('editar/(:num)', 'Viviendas::editar/$1');
    $routes->post('actualizar/(:num)', 'Viviendas::actualizar/$1');
    $routes->delete('eliminar/(:num)', 'Viviendas::eliminar/$1');
    $routes->post('eliminar/(:num)', 'Viviendas::eliminar/$1');
    $routes->get('datatable', 'Viviendas::datatable');
    $routes->get('ver/(:num)', 'Viviendas::ver/$1');
    $routes->get('exportar-excel', 'Viviendas::exportarExcel');
    $routes->get('exportar-pdf', 'Viviendas::exportarPdf');
    $routes->get('imprimir', 'Viviendas::imprimir');
});

// Reportes
$routes->group('reportes', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Reportes::index');
    $routes->get('garantias', 'Reportes::garantias');
    $routes->get('garantias-datatable', 'Reportes::garantiasDatatable');
    $routes->get('garantias-excel', 'Reportes::garantiasExcel');
    $routes->get('garantias-pdf', 'Reportes::garantiasPdf');
    $routes->get('garantias-imprimir', 'Reportes::garantiasImprimir');
    $routes->get('por-tecnico', 'Reportes::porTecnico');
    $routes->get('por-tecnico-datatable', 'Reportes::porTecnicoDatatable');
    $routes->get('por-tecnico-excel', 'Reportes::porTecnicoExcel');
    $routes->get('por-tecnico-pdf', 'Reportes::porTecnicoPdf');
    $routes->get('vencimientos', 'Reportes::vencimientos');
    $routes->get('vencimientos-datatable', 'Reportes::vencimientosDatatable');
    $routes->get('vencimientos-excel', 'Reportes::vencimientosExcel');
    $routes->get('vencimientos-pdf', 'Reportes::vencimientosPdf');
    $routes->get('resumen', 'Reportes::resumen');
    $routes->get('resumen-excel', 'Reportes::resumenExcel');
    $routes->get('resumen-pdf', 'Reportes::resumenPdf');
});
