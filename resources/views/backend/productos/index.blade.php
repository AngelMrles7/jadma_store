@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1 class="text-center">Productos</h1>
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
      <form action="#" method='POST' enctype="multipart/form-data" id="form_product"  name="fileinfo" class="formulario">
        @csrf
        <div class="modal-body">
            
              <!-- <div class="alert alert-danger" role="alert"> -->
                <ul id="error_list">

                </ul> 
            <div class="mb-3">
              <!--Nombre-->
              <label for="nombre" class="form-label">Nombre</label>
              <input id='nombre' name="nombre" type="text" class="form-control" placeholder="Indica el nombre del producto nuevo">
              
            </div>
            <div class="mb-3" >
              <!--Descripción-->
              <label for="descripcion" class="form-label">Descripción</label>
              <textarea id='descripcion' name="descripcion" class="form-control" placeholder="Indica la descripción del producto nuevo" cols="30" rows="10" required></textarea>
            </div>

            <div class="mb-3">
              <!--foto-->
              <label for="foto" class="form-label">Foto</label>
              <input id='foto' type="file" name="foto" class="form-control">
            </div>
            <div class="form__grupo mb-3">
              <!--Cantidad-->
              <label for="precio" class="form-label">Precio</label>
              <input id='precio' name="precio" type="number" min="0" max="9999999999" class="form-control" placeholder="Indica el precio del producto nuevo" required>
            </div>
            <div class="mb-3">
              <!-- existencia -->
              <label for="existencia" class="form-label">Existencia</label>
              <input id='existencia' name="existencia" type="number" min="1" class="form-control" placeholder="Indica Existencia del producto" required>
            </div>
            <div class="mb-3">
              <!-- serial -->
              <label for="serial" class="form-label">Serial</label>
              <input id='serial' name="serial" min="1"  type="number" class="form-control" placeholder="Indica serial del producto" required>
            </div>
            
            
            <div class="mb-3">
              <!-- garantia -->
              <label for="garantia" class="form-label">Garantia</label>
              <select name="garantias_id" id="garantia"  class="form-select form-select-sm" aria-label=".form-select-sm "></select>
            </div>
            <div class="mb-3">
              <!-- marcas -->
              <label for="marca" class="form-label">Marcas</label>
              <select name="marcas_id" id="marca"  class="form-select form-select-sm" aria-label=".form-select-sm "></select>
            </div>

            <div class="mb-3">
              <!-- Categoria -->
              <label for="exampleInputEmail1" class="form-label">Categoria</label>
              <select name="categorias_id" id="categoria" class="form-select"></select>
            </div>
            
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-close-productos" data-bs-dismiss="modal">Cancelar</button>
          <button id="editar" type="submit"  class="btn btn-warning">Editar</button>
          <button id="guardar" type="submit" class="btn btn-primary">Guardar</button>
        </div>
        </div>
      </form>
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
      <th>Descripción</th>
      <th>Imagen</th>
      <th>Precio</th>
      <th>Cantidad</th>
      <th>Serial</th>
      <th>Garantia</th>
      <th>Marca</th>
      <th>Categoria</th>
      <th>Opcion</th>
    </tr>
  </thead>

</table>

@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
@stop

@section('js')
<!-- Incio del script de Ajax -->

<script>
  
  $(document).ready(function(){
    $('.btn-close-productos').on('click', function() {
      $('#error_list').html('');
      $('#error_list').removeClass("alert alert-danger");
    });
    //Se carga la datatable con los datos 
    $('#table_product').DataTable({
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
      },
      "ajax":"{{Route('productos.allProduct')}}",
      "columns":[
        {data:"id"},
        {data:"nombre"},
        {data:"descripcion"},
        {data:"foto",
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
        {data:"precio"},
        {data:"existencia"},
        {data:"serial"},
        {data:"tipo"},
        {data:"marca"},
        {data:"categoria"},
        {defaultContent:"<div class='text-center'><button class='btn btn-primary btn-sm btnEditar'><i class='material-icons'>edit</i></button></div>"}
      ]
    });
   

    $("#btnNuevo").click(function(){
      $('#guardar').show();
      $('#editar').hide();
      $("#form_product").trigger("reset");
      $(".modal-header").css( "background-color", "#020202");
      $(".modal-header").css( "color", "white" );
      $(".modal-title").text("Crear productos");
      $('#modalCRUD').modal('show');	    
    });
    
    var fila; //captura la fila, para editar o eliminar
    //trae la información de las relaciones de las tablas
    $.ajax({
      type: "POST",
      url: "{{Route('productos.garantias')}}",
      data: {
        _token: "{{csrf_token()}}"
      },
      success: function(res){
        $('#garantia').append('<option value="">Seleccione la Garantia</option>');
        $(res).each(function(i,v){
          $('#garantia').append('<option value="'+v.id+'">'+v.tipo+'</option>');
        });
      },
    
    });
    $.ajax({
      type: "POST",
      url: "{{Route('productos.marcas')}}",
      data: {
        _token: "{{csrf_token()}}"
      },
      success: function(res){
        $('#marca').append('<option value="">Seleccione una Marca</option>');
        $(res).each(function(i,v){
          $('#marca').append('<option value="'+v.id+'">'+v.nombre+'</option>');
        });
      },
      
    });
    
    $.ajax({
      type: "POST",
      url: "{{Route('productos.categorias')}}",
      data: {
        _token: "{{csrf_token()}}"
      },
      success: function(res){
        $('#categoria').append('<option value="">Selecione una categoria</option>');
        $(res).each(function(i,v){
          $('#categoria').append('<option value="'+v.id+'">'+v.nombre+'</option>');
        });
      },
      
    });

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
        url: "{{Route('productos.store')}}",
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
              title: '¡Has agregado un Producto exitosamente!',
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
      let product_id = parseInt(fila.find('td:eq(0)').text()); 
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: 'get',
        url: "productos/"+product_id+"/edit",
        data: product_id,
        success: function(res){
          if (res.status == 400) {
            alert(res.message);
            $('#modalCRUD').modal('hide');
          }else{
            $('#nombre').val(res.producto.nombre);
            $('#descripcion').val(res.producto.descripcion);
            $('#precio').val(res.producto.precio);
            $('#existencia').val(res.producto.existencia);
            $('#serial').val(res.producto.serial);
            $('#iva').val(res.producto.ivaProduct);
            $('#marca').val(res.producto.marcas_id);
            $('#garantia').val(res.producto.garantias_id);
            $('#categoria').val(res.producto.categorias_id);
          }
        }

      })

      $(".modal-header").css("background-color", "#020202");
      $(".modal-header").css("color", "white" );
      $(".modal-title").text("Editar Producto");		
      $('#modalCRUD').modal('show');
    });

    $(document).on('click', '#editar', function(e){
      e.preventDefault();
      let formEdit = new FormData($('#form_product')[0]);
      let product_id = parseInt(fila.find('td:eq(0)').text()); 
     
      $.ajax({
        type: "POST",
        url: "productos/update/"+product_id,
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
              title: '¡Has actualizado un producto!',
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
});

</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stop