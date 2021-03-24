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
        $('.modal-admin').css('margin', '100px auto 20px auto'); 
        $('.modal-admin').modal('show');


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
                    field: "prod_codigo",
                    width: 60,
                    title: "CODIGO",
                    filterable: false
                },
                {
                    field: "prod_nombre",
                    width: 400,
                    title: "NOMBRE",
                    filterable: {
                        cell: {
                            operator: "contains",
                            suggestionOperator: "contains"
                        }
                    }
                },                
                {
                    field: "prod_precio_publico",
                    width: 60,
                    title: "P1",
                    filterable: false
                },  
                {
                    field: "prod_precio_2",
                    width: 60,
                    title: "P2",
                    filterable: false
                },  
                {
                    field: "prod_precio_3",
                    width: 60,
                    title: "P3",
                    filterable: false
                },  
                {
                    field: "prod_precio_4",
                    width: 60,
                    title: "P4",
                    filterable: false
                },  
                {
                    field: "prod_precio_5",
                    width: 60,
                    title: "P5",
                    filterable: false
                },
                {
                    field: "lin_nombre",
                    width: 150,
                    title: "LINEA"                    
                },
                {
                    field: "lin_marca",
                    width: 150,
                    title: "MARCA"                    
                },
                {
                    field: "prod_ubicacion",
                    width: 150,
                    title: "UBICACION"                    
                },
                {
                    field: "alm_nombre",
                    width: 150,
                    title: "ALMACEN"                   
                },
                {
                    field: "prod_agregarItem",
                    width: 150,
                    title: "&nbsp;",
                    template:"#= prod_agregarItem #"
                }]
            });        
    });        
    </script>
</div>