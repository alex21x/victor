<style type="text/css">
  
   .lbl_nombre{
        width: 100%;
   } 

   .k-grid-header .k-header {
        height: 20px;
        padding: 0;
      }

   .k-grid tbody tr {
        line-height: 14px;
        height: 35px;
        text-align: center;
      }
   .k-textbox{
        width: 100%;
   }   

   .k-grid tbody td {
        padding: 0;
      }

     .k-filter-menu .k-datepicker, .k-filter-menu .k-datetimepicker, .k-filter-menu .k-dropdown, .k-filter-menu .k-numerictextbox, .k-filter-menu .k-textbox, .k-filter-menu .k-timepicker {
        width: 100%;
      }
      .k-filter-menu span.k-filter-and {
        width: 100%;
      }      
 </style>
<div class="modal-dialog modal-lg modal-admin" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalNuevoCliente"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">BUSCAR PRODUCTO</h4>
      </div>
      <div class="modal-body" style="height:600px;">  
      <input type="hidden" name="tipo_comprobante" id="tipo_comprobante" value="<?= $tipo_comprobante ?>">      
        <div class="container-fluid">
            <div class="col-xs-12 col-md-12 col-lg-12">
              <input type="hidden" id="search" />                
    <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12">
            <div id="grid">
            </div>                
        </div>    
    </div></div>
      </div>  
      <div class="modal-footer">
        <div class="text-left">
        <input type="hidden" class="form-control" id="almacen" value="1">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
      </div>            
    </div>  
</div>
<div id="example">    
    <script>
        $('.modal-admin').css('width', '1800px');
        $('.modal-admin').css('margin', '20px auto 20px auto'); 
        //$('.modal-admin').modal('show');
        $(document).ready(function() {
            var dataSource = new kendo.data.DataSource({                            
                    type: "json",
                    transport: {
                        read: {
                            url:"<?php echo base_url()?>index.php/productos/getMainList/",                            
                            dataType:"json",                             
                            method: "post"                                                
                    }},
                    schema: {
                        data:'data',
                        total:'rows',   
                        model: {
                            fields: {
                                prod_codigo: { type: "number" },                                
                                prod_nombre: { type: "string" }                                
                            }
                        }                 
                    },
                    pageSize: 20,
                    serverPaging: true,
                    serverFiltering: true                
                });


    $("#grid").kendoGrid({
                dataSource: dataSource,                
                height: 520,                
                filterable: {
                    mode: "row"
                },          
                pageable: true,
                columns: 
               [{
                    field: "prod_agregarItem",
                    width: 50,
                    title: "&nbsp",
                    filterable: false,
                    template:"#= prod_agregarItem #"
                },
                {
                    field: "prod_codigo",
                    width: 60,
                    title: "CODIGO",
                    filterable: false
                },
                {
                    field: "prod_nombre",
                    width: 360,
                    title: "NOMBRE",
                    filterable: {
                        cell: {
                            showOperators: false,
                            enabled: true,
                            delay: 50000000,
                            inputWidth: 340
                        }
                    },
                    extra: false
                },     

                <?PHP if($tipo_comprobante != 2){?>//COMPRAS                           
                {
                    field: "prod_precio_publico",
                    width: 60,
                    title: "P1",
                    filterable: false
                },

                <?PHP } else {?>
                {   field: "prod_precio_compra",
                    width: 160,
                    title: "PRECIO COMPRA",
                    filterable: false
                },    
                <?PHP }?>  
                
                {
                    field: "prod_stock",
                    width: 80,
                    title: "STOCK",
                    filterable: false
                },
                {
                    field: "cat_nombre",
                    width: 150,
                    title: "CATEGORIA",
                    filterable: {
                        cell: {
                            showOperators: false,
                            inputWidth: 120
                        }
                    }   
                },                
                {
                    field: "alm_nombre",
                    width: 170,
                    title: "ALMACEN",
                    filterable: {
                        cell: {
                            showOperators: false,
                            inputWidth: 130
                        }
                    }                  
                }
                ],
                dataBound:function(e){
                    //Agregar Item
                    $(".btn_agregarItem").click(function(e){
                       e.preventDefault();
                       var prod_id = $(this).data('id');
                       var prod_nombre  = $(this).data('nombre');
                       var prod_precio  = ($("#tipo_comprobante").val() != 2 ) ? $(this).data('precio') : $(this).data('precio_compra');

                       agregarFila(prod_id,prod_nombre,prod_precio);
                       $("#myModalProducto").modal('hide');
                    });

                }
            });   


        //FUNCION AGREGAR FILA
        function agregarFila(prod_id,prod_nombre,prod_precio){
            var fila = '<tr class="cont-item" >';                               
                fila += '<td class="col-sm-4" style="border:0;">'+                        
                        '<input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required="" value="'+prod_nombre+'">'+                        
                        '<div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="'+ prod_id+'"></div></td>';
                
                fila += '<td style="border:0;">'+
                        '<input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1" ></td>';
                fila += '<td style="border:0;">'+
                        '<input type="text" id="dosificacion" name="dosificacion[]"  class="form-control dosificacion" ></td>';
                fila += '<td class="eliminarFila" style="border:0;">'+
                        '<span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
                fila += '</tr>';
            $("#tableProducto").css("display","block");
               $("#tableProducto tbody").append(fila);
        }
    });        
    </script>
</div>