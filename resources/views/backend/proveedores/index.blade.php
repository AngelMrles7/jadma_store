@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1 class="text-center">Lista de proveedores</h1>
@stop

@section('content')
    <!-- Inicio Modal Crear productos -->
    <div class="modal fade" id="modalCRUD" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title text-center" id="exampleModalLabel"></h5>
            <button type="button" class="btn-close btn-close-productos" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
            </div>
           
            <div class="modal-body">
                <form action="#" method='POST' id="form_product"  name="fileinfo" class="formulario">
                    @csrf
                    <ul id="error_list">
    
                    </ul> 
                    <div class="mb-3">
                        <!--Nombre-->
                        <label for="nombre" class="form-label">Nombre</label>
                        <input id='nombre' name="name" type="text" class="form-control" placeholder="Nombre del proveedor" required>
                        
                    </div>
                    <div class="mb-3" >
                        <!--Descripción-->
                        <label for="direccion" class="form-label">Dirección</label>
                        <input id='direccion' name="direccion" class="form-control"   required>
                    </div>
        
                    <div class="mb-3">
                        <!--foto-->
                        <label for="email" class="form-label">Email</label>
                        <input id='email' type="email" name="email" class="form-control" placeholder="Correo electronico" required>
                    </div>
                    <div class="form__grupo mb-3">
                        <!--Cantidad-->
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input id='telefono' name="telefono" type="number" min="0" max="99999999" class="form-control" placeholder="Numero teléfonico" required>
                    </div>
                    <div class="mb-3">
                        <!-- existencia -->
                        <label for="razonSocial" class="form-label">Razón Social</label>
                        <input id='razonSocial' name="razonSocial" type="text" min="1" class="form-control" placeholder="Indica la Razón social" required>
                    </div>
                    <div class="mb-3">
                        <!-- serial -->
                        <label for="nit" class="form-label">Nit</label>
                        <input id='nit' name="nit" min="1"  type="number" class="form-control" placeholder="Nit" required>
                    </div>
              

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-bs-dismiss="modal">Cancelar</button>
                    <button id="editar" type="submit"  class="btn btn-warning">Editar</button>
                    <button id="guardar" type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
            
            </div>
         
        </div>
        </div>
    </div>
    <!-- Fin Modal Crear productos -->
    
        <div class="col-lg-12">            
        <button id="btnNuevo" type="button" class="btn btn-primary" data-toggle="modal"><i class="material-icons">library_add</i></button>    
        </div>    
        
    <br>
    <table id="table_product"  class="table table-dark table-striped text-center">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Razón social</th>
                <th>Nit</th>
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
      $('.btn-close').on('click', function() {
        $('#error_list').html('');
        $('#error_list').removeClass("alert alert-danger");
      });
      //Se carga la datatable con los datos 
      $('#table_product').DataTable({
        "ajax":"{{Route('proveedores.allProveedrs')}}",
        "columns":[
          {data:"id"},
          {data:"name"},
          {data:"direccion"},
          {data:"email"},
          {data:"telefono"},
          {data:"razonSocial"},
          {data:"nit"},
          {defaultContent:"<div class='text-center'><div class='btn-group'><button class='btn btn-primary btn-sm btnEditar'><i class='material-icons'>edit</i></button>|<button class='btn btn-danger btn-sm btnBorrar'><i class='material-icons'>delete</i></button></div></div>"}
        ],
        language: {
          url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
        }
      });

        $("#btnNuevo").click(function(){
            $('#guardar').show();
            $('#editar').hide();
            $("#form_product").trigger("reset");
            $(".modal-header").css( "background-color", "#020202");
            $(".modal-header").css( "color", "white" );
            $(".modal-title").text("Agregar un proveedor");
            $('#modalCRUD').modal('show');	    
        });

        var fila; //captura la fila, para editar o eliminar

        //Proceso para el cual guardar los datos
        $(document).on('click', '#guardar', function(e){
            $('#error_list').html('');
            $('#error_list').removeClass("alert alert-danger");
            e.preventDefault();
            var data = new FormData($("#form_product")[0]);//Se utiliza cuando se esta manejando file
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{Route('proveedores.store')}}",
                data: data,
                processData: false,
                contentType: false,
                success: function (res){
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
                    title: '¡Has agregado un Proveedor exitosamente!',
                    showConfirmButton: false,
                    timer: 1500
                    });
                    $('#table_product').DataTable().ajax.reload();
                    $("#form_product")[0].reset();
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
            var id = parseInt(fila.find('td:eq(0)').text()); 
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'get',
                url: "proveedores/"+id+"/edit",
                data: id,
                success: function(res){
                if (res.status == 400) {
                    alert(res.message);
                    $('#modalCRUD').modal('hide');
                }else{
                    $('#nombre').val(res.proveedor.name);
                    $('#direccion').val(res.proveedor.direccion);
                    $('#email').val(res.proveedor.email);
                    $('#telefono').val(res.proveedor.telefono);
                    $('#razonSocial').val(res.proveedor.razonSocial);
                    $('#nit').val(res.proveedor.nit);
                }
                }

            })

            $(".modal-header").css("background-color", "#020202");
            $(".modal-header").css("color", "white" );
            $(".modal-title").text("Editar Proveedor");		
            $('#modalCRUD').modal('show');
        });

        $(document).on('click', '#editar', function(e){
            e.preventDefault();
            const data = new FormData(document.getElementById('form_product'))
            var id = parseInt(fila.find('td:eq(0)').text()); 
            $.ajax({
                type: "POST",
                url: "proveedores/update/"+id,
                data: data,
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
                    title: '¡Has actualizado un proveedor!',
                    showConfirmButton: false,
                    timer: 15000
                    });
                    $('#table_product').DataTable().ajax.reload();
                    $("#form_product")[0].reset();
                    $("#modalCRUD").modal('hide');
                }
                }
            });
        });


        $(document).on("click", ".btnBorrar", function(){
            fila = $(this);           
            id = parseInt($(this).closest('tr').find('td:eq(0)').text()) ;		 //eliminar        
            Swal.fire({
                title: 'Estas seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminalo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "proveedores/"+id,
                        type: "DELETE",
                        datatype:"json",        
                        success: function(res) {
                            if (res.status = 200) {
                                Swal.fire(
                                'Deleted!',
                                'Tu registro ha sido borrado exitosamente',
                                'success'
                                ); 
                                $('#table_product').DataTable().ajax.reload();
                            }         
                        }
                    });	
                }
            });                
        });

    });

</script>
@stop