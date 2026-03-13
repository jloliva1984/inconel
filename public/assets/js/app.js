/**
 * Inconel Building - Main Application JavaScript
 */

'use strict';

// ============================================================
// DataTables Spanish Language
// ============================================================
const datatableES = {
    sEmptyTable:     'No hay datos disponibles',
    sInfo:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
    sInfoEmpty:      'Mostrando 0 a 0 de 0 registros',
    sInfoFiltered:   '(filtrado de _MAX_ registros totales)',
    sInfoPostFix:    '',
    sInfoThousands:  ',',
    sLengthMenu:     'Mostrar _MENU_ registros',
    sLoadingRecords: '<i class="fas fa-spinner fa-spin mr-2"></i>Cargando...',
    sProcessing:     '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...',
    sSearch:         '<i class="fas fa-search mr-1"></i>',
    sSearchPlaceholder: 'Buscar...',
    sZeroRecords:    'No se encontraron registros coincidentes',
    oPaginate: {
        sFirst:    '<i class="fas fa-step-backward"></i>',
        sLast:     '<i class="fas fa-step-forward"></i>',
        sNext:     '<i class="fas fa-chevron-right"></i>',
        sPrevious: '<i class="fas fa-chevron-left"></i>',
    },
    oAria: {
        sSortAscending:  ': activar para ordenar columna ascendentemente',
        sSortDescending: ': activar para ordenar columna descendentemente',
    },
    buttons: {
        copy:       '<i class="fas fa-copy mr-1"></i> Copiar',
        excel:      '<i class="fas fa-file-excel mr-1"></i> Excel',
        csv:        '<i class="fas fa-file-csv mr-1"></i> CSV',
        pdf:        '<i class="fas fa-file-pdf mr-1"></i> PDF',
        print:      '<i class="fas fa-print mr-1"></i> Imprimir',
        colvis:     '<i class="fas fa-columns mr-1"></i> Columnas',
        copyTitle:  'Copiar al portapapeles',
        copySuccess: { 1: '1 fila copiada', _: '%d filas copiadas' },
    },
};

// ============================================================
// Confirm Delete
// ============================================================
function confirmDelete(url, onSuccess) {
    Swal.fire({
        title:              '¿Eliminar registro?',
        text:               'Esta acción no se puede deshacer.',
        icon:               'warning',
        showCancelButton:   true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor:  '#6c757d',
        confirmButtonText:  '<i class="fas fa-trash mr-1"></i> Sí, eliminar',
        cancelButtonText:   '<i class="fas fa-times mr-1"></i> Cancelar',
        reverseButtons:     true,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url:    url,
                type:   'POST',
                data:   { _method: 'DELETE' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text:  response.message || 'El registro fue eliminado.',
                            icon:  'success',
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        if (typeof onSuccess === 'function') {
                            setTimeout(onSuccess, 1600);
                        }
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo eliminar.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo completar la operación.', 'error');
                }
            });
        }
    });
}

// ============================================================
// Auto-dismiss alerts
// ============================================================
$(function() {
    // Auto-close flash messages
    setTimeout(function() {
        $('.alert.fade').alert('close');
    }, 5000);

    // Tooltip init
    $('[data-bs-toggle="tooltip"], [data-toggle="tooltip"]').tooltip();

    // Mark active menu items
    const path = window.location.pathname;
    $('.nav-sidebar .nav-link').each(function() {
        const href = $(this).attr('href');
        if (href && href !== '#' && path.indexOf(href.replace(/^.*\/\/[^\/]+/, '')) !== -1) {
            $(this).addClass('active');
            $(this).closest('.nav-treeview').prev('.nav-link').addClass('active');
            $(this).closest('.nav-item').closest('.nav-treeview').closest('.nav-item').addClass('menu-open');
        }
    });
});

// ============================================================
// Warranty badge helper (used in inline scripts)
// ============================================================
function warrantyBadge(status) {
    const map = {
        'activa':     '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Activa</span>',
        'vencida':    '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Vencida</span>',
        'por_vencer': '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-circle me-1"></i>Por Vencer</span>',
    };
    return map[status] || '<span class="badge bg-secondary">' + status + '</span>';
}

// ============================================================
// Flash message from URL hash (optional)
// ============================================================
function showNotification(type, message) {
    const icons = { success: 'success', error: 'error', warning: 'warning', info: 'info' };
    Swal.fire({
        toast:             true,
        position:          'top-end',
        icon:              icons[type] || 'info',
        title:             message,
        showConfirmButton: false,
        timer:             3500,
        timerProgressBar:  true,
    });
}
