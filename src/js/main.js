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
            url: '/Controlador/ProductoControlador.php',
            data: $('#crearProd').serialize(),
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
            url: '/Controlador/ProductoControlador.php',
            data: $('#editProd').serialize(),
            success: function(r){
                console.log(r);
                return;
                alert("Producto editado");
                window.location.href = 'index.php';
            },
            error: function(){
                alert("Hubo un error al editar el producto");
            }
        });
    });

    $('.deleteProd').on('click', function(e){
        var id = $('.deleteProd').val();
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'index.php',
            data: {
                action: 'eliminar',
                id:id
            },
            success: function(r){
                console.log(r);
                return;
                alert("Producto eliminado");
                window.location.href = 'index.php';
            },
            /* error: function(){
                alert("Hubo un error al eliminar el producto");
            } */
        });
    });
});

/* function crearProducto(producto) {
    if( (validaVacio(producto))){
        alert("Hay un error al, falta llenar algun campo");
        return false;
    }

    $.ajax({
        type: 'POST',
        url: 'src/Controlador/ProductoControlador.php?action=crear',
        async: false,
        data: producto,
        success: function(r){
            console.log(r);
            return;
            alert("Producto actualizado");
            window.location.href = 'index.php';
        }
    });
}


function validaVacio(valor) {
	valor = valor.replace("&nbsp;", "");
	valor = valor == undefined ? "" : valor;
	if (!valor || 0 === valor.trim().length) {
		return true;
	} else {
		return false;
	}
} */