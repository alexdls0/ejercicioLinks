(function() {

    var genericAjax = function(url, data, type, callBack) {
        $.ajax({
                url: url,
                data: data,
                type: type,
                dataType: 'json',
            })
            .done(function(json) {
                console.log('ajax done');
                console.log(json);
                callBack(json);
            })
            .fail(function(xhr, status, errorThrown) {
                console.log('ajax fail');
            })
            .always(function(xhr, status) {
                console.log('ajax always');
            });
    }
    
    $('#registrarUsuario').on('click', function(event) {
        var parametros
        event.preventDefault();
        parametros = {
            nombreRegistrar: $('#nombreRegistrar').val().trim(),
            apellidoRegistrar: $('#apellidoRegistrar').val().trim(),
            aliasRegistrar: $('#aliasRegistrar').val().trim(),
            emailRegistrar: $('#emailRegistrar').val().trim(),
            claveRegistrar: $('#claveRegistrar').val().trim(),
            claveRepRegistrar: $('#claveRepRegistrar').val().trim(),
        };
        if (parametros.nombreRegistrar !== '' &&
            parametros.apellidoRegistrar !== '' &&
            parametros.aliasRegistrar !== '' &&
            parametros.emailRegistrar !== '' &&
            parametros.claveRegistrar !== '' &&
            parametros.claveRepRegistrar !== '') {
            console.log(parametros);
            genericAjax('ajax/registrarUsuario', parametros, 'post', function(json) {
                if (json.registrarUsuario > 0) {
                    window.location.replace('index');
                }else{
                    $('#nombreRegistrar').val('');
                    $('#apellidoRegistrar').val('');
                    $('#aliasRegistrar').val('');
                    $('#emailRegistrar').val('');
                    $('#claveRegistrar').val('');
                    $('#claveRepRegistrar').val('');
                    alert('Datos incorrectos');   
                }
            });
        }else{
            alert('Rellene el formulario de registro');
        }
    });
    
    $('#iniciarSesion').on('click', function(event) {
        var parametros
        event.preventDefault();
        parametros = {
            emaillogin: $('#emaillogin').val().trim(),
            clavelogin: $('#clavelogin').val().trim(),
        };
        if (parametros.emaillogin !== '' &&
            parametros.clavelogin !== '' ) {
            console.log(parametros);
            genericAjax('ajax/iniciarSesion', parametros, 'post', function(json) {
                if (json.iniciarSesion > 0) {
                    window.location.replace('user');
                }else{
                    $('#emaillogin').val('');
                    $('#clavelogin').val('');
                    alert('Datos incorrectos');   
                }
            });
        }else{
            alert('Introduzca todos los datos');
        }
    });
    
    $('#cerrarSesion').on('click', function(event) {
        genericAjax('ajax/cerrarSesion', null, 'get', function(json) {
            window.location.replace('index');
        });
    });
    
    $('#crearCategoria').on('click', function(event) {
        var parametros
        event.preventDefault();
        parametros = {
            categorianombre: $('#categorianombre').val().trim(),
        };
        if (parametros.categorianombre !== '') {
            console.log(parametros);
            genericAjax('ajax/crearCategoria', parametros, 'post', function(json) {
                if (json.crearCategoria > 0) {
                    //window.location.replace('categorias');
                    $('#contenedorCategorias').empty()
                    pintarCategorias();
                    $('#categorianombre').val('');
                }else{
                    $('#categorianombre').val('');
                    alert('Se ha producido un error');   
                }
            });
        }else{
            alert('Introduzca el nombre de la categoría');
        }
    });
    
    $('#contenedorCategorias').on('click', 'a', function() {
        var identificador = $(this).attr('id');
        
        var parametros
        event.preventDefault();
        parametros = {
            idcatborrar: identificador,
        };
        if (parametros.idcatborrar !== '') {
            console.log(parametros);
            genericAjax('ajax/borrarCategoria', parametros, 'post', function(json) {
                if (json.borrarCategoria > 0) {
                    $('#contenedorCategorias').empty()
                    pintarCategorias();
                }else{
                    alert('Error');   
                }
            });
        }else{
            alert('Error en la categoría');
        }
    });
    
    $('#crearLink').on('click', function(event) {
        var parametros
        event.preventDefault();
        parametros = {
            enlacenombre: $('#enlacenombre').val().trim(),
            enlacehref: $('#enlacehref').val().trim(),
            enlacecomentario: $('#enlacecomentario').val().trim(),
            enlacecategoria: $('#enlacecategoria').val(),
        };
        if (parametros.title !== '' &&
            parametros.href !== '' &&
            parametros.comentario !== '') {
            console.log(parametros);
            genericAjax('ajax/crearEnlace', parametros, 'post', function(json) {
                if (json.crearEnlace > 0) {
                    $('#enlacenombre').val('');
                    $('#enlacehref').val('');
                    $('#enlacecomentario').val('');
                    $('#contenedorEnlaces').empty()
                    pintarEnlaces();
                }else{
                    alert('Se ha producido un error');   
                }
            });
        }else{
            alert('Compruebe los campos');
        }
    });
    
    $('#contenedorEnlaces').on('click', 'a.borrar', function() {
        var identificador = $(this).attr('id');
        
        var parametros
        event.preventDefault();
        parametros = {
            idlinkborrar: identificador,
        };
        if (parametros.idlinkborrar !== '') {
            console.log(parametros);
            genericAjax('ajax/borrarEnlace', parametros, 'post', function(json) {
                if (json.borrarEnlace > 0) {
                    $('#contenedorEnlaces').empty()
                    pintarEnlaces();
                }
            });
        }else{
            alert('Error en el enlace');
        }
    });
    
    $(document).ready(function() {
        if($('#contenedorCategorias').length > 0){
            pintarCategorias();
        }
       
        if($('#contenedorEnlaces').length > 0){
            pintarEnlaces();
        }
       
        if($('#contenedorPaginacion').length > 0){
            initialAjax();
        }
    });
    
    function pintarCategorias(){
        if($('#contenedorCategorias').length > 0){
            genericAjax('ajax/listadoCategorias', null, 'get', function(json) {
                var contador = 0;
                $.each(json.listadoCategorias, function(key, value) {
                    $('#contenedorCategorias').append('<p style="width:100%; text-align:center; margin-top: 50px; margin-bottom: 50px;"><span>ID categoría: </span>'+value.id+'/ <span>Nombre de la categoría: </span>'+ value.nombre+'<a style="color:black;" href="" id="'+value.id+'"> &rarr;Borrar</a></p>')
                    contador++;
                });
            });
        }else{
        }
    }
    
    function pintarEnlaces(){
        if($('#contenedorEnlaces').length > 0){
            genericAjax('ajax/listadoEnlaces', null, 'get', function(json) {
                //var contador = 0;
                $.each(json.listadoEnlaces.reverse(), function(key, value) {
                    //if(contador < 5){
                        $('#contenedorEnlaces').append('<tr style="width:100%; height:60px; text-align:center; margin-top: 30px; margin-bottom: 30px;"> <td>'+value.id+'</td><td>'+value.titulo+'</td><td><a href="'+value.enlace+'" style="color:blue;">Enlace</a></td><td>'+value.comentario+'</td><td>'+value.idcategoria+'</td><td> <a style="color:black;" class="borrar" href="" id="'+value.id+'"> &larr;Borrar</a></td></tr>')
                        //contador++;
                    //}
                });
            });
        }else{
        }
    }
    
    var getEnlaces = function (pagina) {
        genericAjax('paginacion', {'pagina': pagina}, 'get', function(json) {
            procesarEnlaces(json.enlaces);
            procesarPaginas(json.paginas);
        });
    }

    var getTrEnlaces = function (value) {
        return `<tr>
                    <td>${value.id}</td>
                    <td>${value.idcategoria}</td>
                    <td>${value.title}</td>
                    <td>${value.href}</td>
                    <td>${value.comentario}</td>
                </tr>`;
    };

    function initialAjax() {
        genericAjax('paginacion', null, 'get', function(json) {
            procesarEnlaces(json.enlaces);
            procesarPaginas(json.paginas);
        });
    }

    var procesarEnlaces = function (ciudades) {
        var listaitems = '';
        $.each(enlaces, function(key, value) {
            listaitems += getTrEnlaces(value);
        });
        $('#cuerpoTablaEnlaces').empty();
        $('#cuerpoTablaEnlaces').append(listaitems);
    }
    
    var procesarPaginas = function (paginas) {
        var stringFirst = '<a href = "#" class = "btn btn-primary">' + paginas.primero + '</a>';
        var stringPrev  = '<a href = "#" class = "btn btn-primary">' + paginas.anterior + '</a>';
        var stringRange = '';
        $.each(paginas.rango, function(key, value) {
            if(paginas.pagina == value) {
                stringRange += '<a href = "#" class = "btnNoPagina btn btn-info">' + value + '</a> ';
            } else {
                stringRange += '<a href = "#" class = "btnPagina btn btn-primary" data-pagina="' + value + '">' + value + '</a> ';
            }
        });
        var stringNext = '<a href = "#" class = "btnPagina btn btn-primary">' + paginas.siguiente + '</a>';
        var stringLast = '<a href = "#" class = "btnPagina btn btn-primary">' + paginas.ultimo + '</a>';
        var finalString = stringFirst + stringPrev + stringRange + stringNext + stringLast;
        $('#pintarPaginas').empty();
        $('#pintarPaginas').append(stringRange);
        $('.btnPagina').on('click', function(e) {
            e.preventDefault();
            var p = e.target.getAttribute('data-pagina');
            getEnlaces(p); 
        });
        $('.btnNoPagina').on('click', function(e) {
            e.preventDefault();
        });
    }

})();