@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="text-center">Categorias de Clientes</h1>
@stop
@section('content')
        <!-- Inicio Modal Categoria clientes -->
    <div class="modal fade" id="modalCRUD" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close btn-close-productos" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
            </div>
            <form action="#" method='POST' enctype="multipart/form-data" id="form"  name="fileinfo" class="formulario">
                @csrf
                <div class="modal-body">
                    
                    <!-- <div class="alert alert-danger" role="alert"> -->
                        <ul id="error_list">

                        </ul> 
                    <!--Nombre-->
                    <div class="form__grupo mb-3" id="grupo__nombre">
                        <label for="nombre" class="form-label">Nombre de categoria</label>
                        <input id='nombre' name="nom_categoria" type="text" class="form-control" placeholder="Indica el nombre del producto nuevo">
                    </div>
                    <!--Valor de compras-->
                    <div class="mb-3" id="grupo__nombre">
                        <label for="precio" class="form-label">Valor que debe acumular por las compras realizadas</label>
                        <input id='precio' name="valor_compras" type="number" min="10000" max="9999999999" class="form-control" placeholder="Indica el precio del producto nuevo" required>
                    </div>
                    <!-- Tiempo -->
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Periodo de tiempo</label>
                        <input id='fecha' name="tiempo" type="date" class="form-control" min="2022-05-22" required>
                    </div>
                    <div class="mb-3" id="grupo__nombre">
                        <label for="puntos" class="form-label">Puntos de premio</label>
                        <input id='puntos' name="puntos_premio" type="number" min="0" max="99999999" class="form-control" placeholder="Puntos a dar" required>
                    </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close-productos" data-bs-dismiss="modal">Cancelar</button>
                <button id="editar" type="submit" class="btn btn-primary">Editar</button>
                <button id="guardar" type="submit" class="btn btn-primary">Guardar</button>
                </div>
                </div>
            </form>
        </div>
    </div>
    </div>
    <!-- Fin Modal -->

    <div class="col-lg-12">            
        <button id="btnNuevo" type="button" class="btn btn-primary" data-toggle="modal"><i class="material-icons">library_add</i></button>    
    </div> 
    <br>   
    <table id="table_categoria"  class="table table-dark table-striped text-center">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Valor compra</th>
            <th>Periodo de tiempo</th>
            <th>Puntos de premio</th>
            <th>Opciones</th>
          </tr>
        </thead>

        
      </table>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
@stop
@section('js')

<script>
    $(document).ready(function(){
        $('.btn-close-productos').on('click', function() {
        $('#error_list').html('');
        $('#error_list').removeClass("alert alert-danger");
        });
        //Se carga la datatable con los datos 
        $('#table_categoria').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
        },
        "ajax":"{{Route('categoriaClientes.allCategori')}}",
        "columns":[
            {data:"id"},
            {data:"nom_categoria"},
            {data:"valor_compras"},
            {data:"tiempo"},
            {data:"puntos_premio"},
            {defaultContent:"<div class='text-center'><button class='btn btn-primary btn-sm btnEditar'><i class='material-icons'>edit</i></button></div>"}
        ]
        });
    

        $("#btnNuevo").click(function(){
        $('#guardar').show();
        $('#editar').hide();
        $("#form").trigger("reset");
        $(".modal-header").css( "background-color", "#020202");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Agregar Categorias para clientes");
        $('#modalCRUD').modal('show');	    
        });
        
        var fila; //captura la fila, para editar o eliminar

        //Proceso para el cual guardar los datos
        $(document).on('click', '#guardar', function(e){
            $('#error_list').html('');
            $('#error_list').removeClass("alert alert-danger");
            e.preventDefault();
        
            let data = {
                'nom_categoria' : $('#nombre').val(),
                'valor_compras' :  $('#precio').val(),
                'tiempo' :  $('#fecha').val(),
                'puntos_premio': $('#puntos').val(),
            }
            
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{Route('categoriaClientes.store')}}",
                data: data,
                dataType: 'json',
                success: function (res){
                    if(res.status == 400){
                        $('#error_list').html("");
                        $('#error_list').addClass("alert alert-danger");
                        $.each(res.errors, function(key, value){
                            $('#error_list').append('<li>'+value+'</li>');
                        })
                    }
                    else{
                        Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: '¡Se ha agregado una categoria exitosamente!',
                        showConfirmButton: false,
                        timer: 1500
                        });
                        $('#table_categoria').DataTable().ajax.reload();
                        $("#form")[0].reset();
                        $("#modalCRUD").modal('hide');
                    }
                },
            });
        });

        $(document).on('click', '.btnEditar', function(e){
            $('#error_list').html('');
            $('#error_list').removeClass("alert alert-danger");
            $('#guardar').hide();
            $('#editar').show();
            e.preventDefault();
            fila = $(this).closest("tr");	        
            let id = parseInt(fila.find('td:eq(0)').text()); 
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'get',
                url: "categoriaClientes/"+id+"/edit",
                data: id,
                success: function(res){
                    if (res.status == 400) {
                        alert(res.message);
                        $('#modalCRUD').modal('hide');
                    }else{
                        $('#nombre').val(res.categoriaClientes.nom_categoria);
                        $('#precio').val(res.categoriaClientes.valor_compras);
                        $('#fecha').val(res.categoriaClientes.tiempo);
                        $('#puntos').val(res.categoriaClientes.puntos_premio);
                    }
                }

            })

            $(".modal-header").css("background-color", "#020202");
            $(".modal-header").css("color", "white" );
            $(".modal-title").text("Editar Categoria de clientes");		
            $('#modalCRUD').modal('show');

            $('#modalCRUD').on('click', '#editar', function(e){
                e.preventDefault();
                let formEdit = new FormData($('#form')[0]);
               
                $.ajax({
                    type: "POST",
                    url: "categoriaClientes/update/"+id,
                    data: formEdit,
                    contentType: false,
                    processData: false,
                    success: function(res){
                        if(res.status == 400){
                            $('#error_list').html("");
                            $('#error_list').addClass("alert alert-danger");
                            $.each(res.errors, function(key, value){
                                $('#error_list').append('<li>'+value+'</li>');
                            })
                        }else{
                            Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: '¡Has actualizado una categoria!',
                            showConfirmButton: false,
                            timer: 15000
                            });
                            $('#table_categoria').DataTable().ajax.reload();
                            $("#form")[0].reset();
                            $("#modalCRUD").modal('hide');
                        }
                    }
                });
            });
        });
    });
</script>
    <!-- incluir CDN  en el documento-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    {{-- <script src="{{ asset('js/formProducts.js') }}"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stop