@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="text-center">Banners</h1>
@stop

@section('content')
    <!-- Inicio Modal -->
    <div class="modal fade" id="modalCRUD" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
                </div>
                <form action="#" method='POST' enctype="multipart/form-data" id="form"  name="fileinfo" class="formulario">
                    @csrf
                    <div class="modal-body">
                        
                        <!-- <div class="alert alert-danger" role="alert"> -->
                            <ul id="error_list">
    
                            </ul> 
                        <!--Nombre-->

                        <!--foto-->
                        <div class="mb-3">
                            <label for="foto" class="form-label">Imagen de Banner</label>
                            <input id='foto' type="file" name="imagen" class="form-control">
                        </div>
                        <!--Descripción-->
                        <div class="mb-3" >
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea id='descripcion' name="descripcion" class="form-control" placeholder="Indica la descripción del Banner" cols="30" rows="10" required></textarea>
                        </div>
                    
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close-modal" data-bs-dismiss="modal">Cancelar</button>
                    <button id="modal-btn-edtiar" type="submit" class="btn btn-primary">Editar</button>
                    <button id="modal-btn-guardar" type="submit" class="btn btn-primary">Guardar</button>
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
    <!-- Inicio de la tabla -->
    <table id="table_banner"  class="table table-dark table-striped text-center">
        <thead>
            <tr>
            <th>Id</th>
            <th>Descripción</th>
            <th>Imagen</th>
            <th>Opciones</th>
            </tr>
        </thead>
        <tfoot>
            <tr >
            <th>Id</th>
            <th>Descripción</th>
            <th>Imagen</th>
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

            $('.btn-close-modal').on('click', function() {
                $('#error_list').html('');
                $('#error_list').removeClass("alert alert-danger");
            });
            //Se carga la datatable con los datos 
            $('#table_banner').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
                },
                "drawCallback": function( settings ) {
                    $('ul.pagination').addClass("pagination-sm");
                },
                "ajax":"{{Route('banners.allBanners')}}",
                "columns":[
                    {data:"id"},
                    {data:"descripcion"},
                    {data:"imagen",
                    'sortable': false,
                    'searchable': false,
                    'render': function (foto) {
                    if (!foto) {
                    return 'N/A';
                    }
                    else {
                    return '<img src="' + foto + '" class="img-fluid img-thumbnail" width="100px">';
                    }
                    }},
                    {defaultContent:"<div class='text-center'><div class='btn-group'><button class='btn btn-primary btn-sm btnEditar'><i class='material-icons'>edit</i></button>|<button class='btn btn-danger btn-sm btnBorrar'><i class='material-icons'>delete</i></button></div></div>"}
                ]
            });

            $("#btnNuevo").click(function(){
                $('#modal-btn-guardar').show();
                $('#modal-btn-edtiar').hide();
                $("#form").trigger("reset");
                $(".modal-header").css( "background-color", "#020202");
                $(".modal-header").css( "color", "white" );
                $(".modal-title").text("Crear Banner");
                $('#modalCRUD').modal('show');	    
            });

            //Proceso para guardar los datos
            $(document).on('click', '#modal-btn-guardar', function(e){
                $('#error_list').html('');
                $('#error_list').removeClass("alert alert-danger");
             
                e.preventDefault();
                let data = new FormData($("#form")[0]);//Se utiliza cuando se esta manejando file
                $.ajaxSetup({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: "{{Route('banners.store')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    //dataType: 'json',
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
                        title: '¡Has agregado nuevo Banner exitosamente!',
                        showConfirmButton: false,
                        timer: 1500
                        });
                        $('#table_banner').DataTable().ajax.reload();
                        $("#form")[0].reset();
                        $("#modalCRUD").modal('hide');
                    }
                    },
                });
            });

            $(document).on('click', '.btnEditar', function(e){
                $('#error_list').html('');
                $('#error_list').removeClass("alert alert-danger");
                $('#modal-btn-guardar').hide();
                $('#modal-btn-edtiar').show();
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
                    url: "banners/"+id+"/edit",
                    data: id,
                    success: function(res){
                        if (res.status == 400) {
                            alert(res.message);
                            $('#modalCRUD').modal('hide');
                        }else{
                            $('#descripcion').val(res.banner.descripcion);
                      
                        }
                    }

                })

                $(".modal-header").css("background-color", "#020202");
                $(".modal-header").css("color", "white" );
                $(".modal-title").text("Editar Banner");		
                $('#modalCRUD').modal('show');
            });

            $(document).on('click', '#modal-btn-edtiar', function(e){
                e.preventDefault();
                let formEdit = new FormData($('#form')[0]);
                let id = parseInt(fila.find('td:eq(0)').text()); 
                console.log('1');
                $.ajax({
                    type: "POST",
                    url: "banners/update/"+id,
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
                            title: '¡Has actualizado el Banner!',
                            showConfirmButton: false,
                            timer: 15000
                            });
                            $('#table_banner').DataTable().ajax.reload();
                            $("#form")[0].reset();
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
                            url: "banners/"+id,
                            type: "DELETE",
                            datatype:"json",        
                            success: function(res) {
                                if (res.status = 200) {
                                    Swal.fire(
                                    'Deleted!',
                                    'Tu registro ha sido borrado exitosamente',
                                    'success'
                                    ); 
                                    $('#table_banner').DataTable().ajax.reload();
                                }         
                            }
                        });	
                    }
                });                
            });
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
@stop