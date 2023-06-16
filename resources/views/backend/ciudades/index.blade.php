@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1 class="text-center">Ciudades</h1>
@stop

@section('content')
    <!-- Inicio Modal -->
    <div class="modal fade" id="modalCRUD" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close btn-close-ciudad" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
            </div>
            <form action="#" method='POST' enctype="multipart/-data" id="form_ciudades"  name="fileinfo" class="formulario">
                @csrf
                <div class="modal-body">
                    
                    <!-- <div class="alert alert-danger" role="alert"> -->
                        <ul id="error_list">

                        </ul> 
                     <!--Nombre-->
                    <div class="form__grupo mb-3" id="grupo__nombre">
                   
                        <label for="nombre" class="form-label">Nombre</label>
                        <input id='nombre' name="nombreCiudad" type="text" class="form-control" placeholder="Indica el nombre del producto nuevo">
                    </div>
                   
                    <!-- Paises -->
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">País</label>
                        <select name="paises_id" id="pais" class="form-select"></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-close-ciudad" data-bs-dismiss="modal">Cancelar</button>
                        <button id="btnUpdate" type="submit" class="btn btn-warning">Editar</button>
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
    <table id="table_ciudades"  class="table table-dark table-striped text-center">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>País</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tfoot>
          <tr >
            <th>Id</th>
            <th>Nombre</th>
            <th>País</th>
            <th>Opciones</th>
          </tr>
        </tfoot>
        
      </table>

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
@stop

@section('js')
<script>
    $(document).ready(function(){
        $('.btn-close-ciudad').on('click', function() {
            $('#error_list').html('');
            $('#error_list').removeClass("alert alert-danger");
        });

         //Se carga la datatable con los datos 
        $('#table_ciudades').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
            },
            "ajax":"{{Route('ciudades.allCiudades')}}",
            "columns":[
                {data:"id"},
                {data:"nombreCiudad"},
                {data:"nombrePais"},
                {defaultContent:"<div class='text-center'><button class='btn btn-primary btn-sm btnEditar'><i class='material-icons'>edit</i></button></div>"}
            ]
        });
   
        $("#btnNuevo").click(function(){
            $('#guardar').show();
            $('#btnUpdate').hide();
            $("#form_ciudades").trigger("reset");
            $(".modal-header").css( "background-color", "#020202");
            $(".modal-header").css( "color", "white" );
            $(".modal-title").text("Agregar Ciudadad");
            $('#modalCRUD').modal('show');	    
        });
            
        var fila;
        $.ajax({
            type: "POST",
            url: "{{Route('ciudades.paises')}}",
            data: {
                _token: "{{csrf_token()}}"
            },
            success: function(res){
                $('#pais').append('<option value="">Seleccione</option>');
                $(res).each(function(i,v){
                $('#pais').append('<option value="'+v.id+'">'+v.nombrePais+'</option>');
                });
                
            },
            
        });

        $(document).on('click', '#guardar', function(e){
            $('#error_list').html('');
            $('#error_list').removeClass("alert alert-danger");
            e.preventDefault();
            var data = {
                'nombreCiudad' : $('#nombre').val(),
                'paises_id' :  $('#pais').val(),
            }
            
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "{{Route('ciudades.store')}}",
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
                        title: '¡Se ha agregado una ciudad exitosamente!',
                        showConfirmButton: false,
                        timer: 1500
                        });
                        $('#table_ciudades').DataTable().ajax.reload();
                        $("#form_ciudades")[0].reset();
                        $("#modalCRUD").modal('hide');
                    }
                },
            });
        })

        $(document).on('click', '.btnEditar', function(e){
            $('#error_list').html('');
            $('#error_list').removeClass("alert alert-danger");
            $('#guardar').hide();
            $('#btnUpdate').show();
            e.preventDefault();
            fila = $(this).closest("tr");	        
            var id = parseInt(fila.find('td:eq(0)').text()); 
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'get',
                url: "ciudades/"+id+"/edit",
                data: id,
                success: function(res){
                    if (res.status == 400) {
                        alert(res.message);
                        $('#modalCRUD').modal('hide');
                    }else{
                        $('#nombre').val(res.ciudad.nombreCiudad);
                        $('#pais').val(res.ciudad.paises_id);
                    }
                }
            })

            $(".modal-header").css("background-color", "#020202");
            $(".modal-header").css("color", "white" );
            $(".modal-title").text("Editar la ciudad");		
            $('#modalCRUD').modal('show');
        });

        $(document).on('click', '#btnUpdate', function(e){
            e.preventDefault();
            let formEdit = new FormData($('#form_ciudades')[0]);
            var id = parseInt(fila.find('td:eq(0)').text()); 
            $.ajax({
                type: "POST",
                url: "ciudades/update/"+id,
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
                        title: '¡Has actualizado una ciudad!',
                        showConfirmButton: false,
                        timer: 15000
                        });
                        $('#table_ciudades').DataTable().ajax.reload();
                        $("#form_ciudades")[0].reset();
                        $("#modalCRUD").modal('hide');
                    }
                }
            });
        });

    })

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
@stop