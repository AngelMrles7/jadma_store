@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="text-center">Pqrs</h1>
@stop

@section('content')
    <!-- Inicio Modal -->
    <div class="modal fade" id="modalCRUD" tabindex="-1"  aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close btn-close-user" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
            </div>
        
                <div class="modal-body">   
                    <form action="#" method='POST' id="form_pqrs"  name="fileinfo" class="formulario">
                        <ul id="error_list">

                        </ul> 
                        @csrf  
                        <input type="input" value='{{Auth::id()}}' name="users_id" hidden>
                        <!--  -->
                        <label for="respuesta" class="form-label">Respuesta </label>
                        <input id='respuesta' name="respuesta" type="text" class="form-control" required>
                
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-close-user" data-bs-dismiss="modal">Cancelar</button>
                            <button id="modal-btn-guardar" type="submit" class="btn btn-primary btnGuardar">Guardar</button>
                        </div>
                    </form>
                </div>
            
            </div>
        </div>
    </div>
    <!-- Fin Modal -->
    
    <table id="table_pqrs"  class="table table-dark table-striped text-center">
        <thead>
            <tr>
                <th>Id</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Fecha de evento</th>
                <th>Descripción</th>
                <th>Soporte</th>
                <th>Contacto</th>
                <th>Empleado</th>
                <th>Respuesta</th>
                <th>Opción</th>
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
            
            //Se carga la datatable con los datos 
            $('#table_pqrs').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
                },
                "ajax":"{{Route('pqrs.allPqrs')}}",
                "columns":[
                    {data:"id"},
                    {data:"nameCl"},
                    {data:"nombre"},
                    {data:"fecha"},
                    {data:"fecha_event"},
                    {data:"descripcion"},
                    {data:"imagen"},
                    {data:"contacto"},
                    {data:"name"},
                    {data:"respuesta"},
                    {defaultContent:"<button class='btn btn-primary btn-sm btnEditar'><i class='material-icons'>edit</i></button>"}
                ]
            });

            var fila;

            $(document).on('click', '.btnEditar', function(e){
                $('#error_list').html('');
                $('#error_list').removeClass("alert alert-danger");
                e.preventDefault();
                fila = $(this).closest("tr");	        
                var id = parseInt(fila.find('td:eq(0)').text()); 
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type:'get',
                    url: "pqrs/"+id+"/edit",
                    data: id,
                    success: function(res){
                        if (res.status == 400) {
                            alert(res.message);
                            $('#modalCRUD').modal('hide');
                        }
                    }
                })

                $(".modal-header").css("background-color", "#020202");
                $(".modal-header").css("color", "white" );
                $(".modal-title").text("Respuesta al caso");		
                $('#modalCRUD').modal('show');
            });

            $(document).on('click', '.btnGuardar', function(e){
                e.preventDefault();
                let formEdit = new FormData($('#form_pqrs')[0]);
                var id = parseInt(fila.find('td:eq(0)').text()); 
                $.ajax({
                    type: "POST",
                    url: 'pqrs/update/'+id,
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
                            title: '¡Has actualizado el Resgistro PQRS!',
                            showConfirmButton: false,
                            timer: 15000
                            });
                            $('#table_pqrs').DataTable().ajax.reload();
                            $("#form_pqrs")[0].reset();
                            $("#modalCRUD").modal('hide');
                        }
                    }
                });
            });

        })
   </script>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
   <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
   
   
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
   <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stop