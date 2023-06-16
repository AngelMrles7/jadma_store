@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1 class="text-center">Usuarios</h1>
@stop

@section('content')
  <!-- Inicio Modal -->
  <div class="modal fade" id="modalCRUD" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
            <button type="button" class="btn-close btn-close-user" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
        </div>
       
            <div class="modal-body">
                    <!--Nombre-->
                    <div class="mb-3" >
                        <label for="nombre" class="form-label">Nombre de Usuario</label>
                        <input id='nombre' name="name" type="text" class="form-control" disabled>
                    </div>
                <form action="#" method='POST' id="form_ciudades"  name="fileinfo" class="formulario">
                    @csrf  
                    <h5>Listado de Roles</h5>
                     <!-- Roles -->
                     <div class="mb-3">   
                        <label for="exampleInputEmail1" class="form-label">Roles</label>
                        <select name="roles" id="rol" class="form-select"></select>
                    </div> 
               
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-close-user" data-bs-dismiss="modal">Cancelar</button>
                        <button id="modal-btn-edtiar" type="submit" class="btn btn-warning">Editar</button>
                        <button id="modal-btn-guardar" type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                    </form>
            </div>
        
        </div>
    </div>
</div>
<!-- Fin Modal -->
<div class="col-lg-12">            
    <a  href ="{{Route('users.create')}}">Crear usuraio</a>
</div> 

    <table id="table_ciudades"  class="table table-dark table-striped text-center">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Opción</th>
          </tr>
        </thead>
        <tfoot>
          <tr >
            <th>Id</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Opción</th>
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
        $('.btn-close-user').on('click', function() {
            $('#error_list').html('');
            $('#error_list').removeClass("alert alert-danger");
        });

         //Se carga la datatable con los datos 
        $('#table_ciudades').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
            },
            "ajax":"{{Route('users.allUsers')}}",
            "columns":[
                {data:"id"},
                {data:"name"},
                {data:"email"},
                {data:"rol"},
                {defaultContent:"<button class='btn btn-primary btn-sm btnEditar'><i class='material-icons'>edit</i></button>"}
            ]
        });

    
        var fila;
        $.ajax({
            type: "POST",
            url: "{{Route('users.roles')}}",
            data: {
                _token: "{{csrf_token()}}"
            },
            success: function(res){
                $('#rol').append('<option value="3">Seleccione un Rol</option>');
                $(res).each(function(i,v){
                    $('#rol').append('<option value="'+v.id+'">'+v.name+'</option>');
                });
            },
            
        });
      
        $(document).on('click', '.btnEditar', function(e){
            $('#error_list').html('');
            $('#error_list').removeClass("alert alert-danger");
            $('#modal-btn-guardar').hide();
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
                url: "users/"+id+"/edit",
                data: id,
                success: function(res){
                    if (res.status == 400) {
                        alert(res.message);
                        $('#modalCRUD').modal('hide');
                    }else{
                        $('#nombre').val(res.user.name);
                    }
                }
            })

            $(".modal-header").css("background-color", "#020202");
            $(".modal-header").css("color", "white" );
            $(".modal-title").text("Editar usuario");		
            $('#modalCRUD').modal('show');
        });

        $(document).on('click', '#modal-btn-edtiar', function(e){
            e.preventDefault();
            let formEdit = new FormData($('#form_ciudades')[0]);
            var id = parseInt(fila.find('td:eq(0)').text()); 
            $.ajax({
                type: "post",
                url: "/users/update/"+id+rol,
                data:  formEdit,  
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
                        title: '¡Has agregado un rol!',
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