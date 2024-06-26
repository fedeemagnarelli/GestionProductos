$(document).ready(function() {
    /* alert("CARGUE");
    return; */
    //Cargar barra busqueda con tabla
    function cargarTabla(page = 1, search = ''){
        $.ajax({
            type: 'GET',
            url: 'index.php',
            data: {page: page, search: search},
            success: function(r){
                $('body').html(r);
            }
        });
    }


    $('#searchForm').on('submit', function(e){
        e.preventDefault();
        cargarTabla(1, $('#search').val());
    });

    //Paginacion
    $('.paginacion').on('click', 'a', function(e){
        e.preventDefault();
        var pagina = $(this).attr('href').split('pagina=')[1];
        cargarTabla(pagina);
    });

    //Funciones CRUD
    $('#crearProd').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../Controlador/ProductoControlador.php',
            data: $('#crearForm').serialize(),
            success: function(r){
                alert("Producto creado");
                window.location.href = 'index.php';
            },
            error: function(){
                alert("Hubo un error al crear el producto");
            }
        });
    });

    $('#editProd').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../Controlador/ProductoControlador.php',
            data: $('#editForm').serialize(),
            success: function(r){
                alert("Producto editado");
                window.location.href = 'index.php';
            },
            error: function(){
                alert("Hubo un error al editar el producto");
            }
        });
    });

    $('.deleteProd').on('click', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../Controlador/ProductoControlador.php',
            data: $('#deleteForm').serialize(),
            success: function(r){
                alert("Producto eliminado");
                window.location.href = 'index.php';
            },
            error: function(){
                alert("Hubo un error al eliminar el producto");
            }
        });
    });
});