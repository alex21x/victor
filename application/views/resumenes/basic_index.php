<h2 align="center"><strong>Lista de Resumenes : Boletas Anuladas</strong></h2>
<br>
<div class="container">
    <div class="row">        
        <div class="col-md-4 col-md-offset-4">
            <div class="form">
                <div class="input-group">
                  <input type="text" class="form-control" id="numero" placeholder="Buscar por numero">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="btn_buscar_categoria"><span class="glyphicon glyphicon-search"></span></button>
                  </span>
                </div>            
            </div>            
        </div>        
        

    </div>
    <br>
    
</div>
<div id="grid" style="width: 90%;margin: 0 auto;"></div>
<script>

    /* function send_sunat(opc,resumen_id){
            toast("info",10000, 'Enviando . . .');
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosSunat",{opc,comprobante_id:resumen_id})
             .done(function(json){
                console.log(json);
                if(json.config.go == 1){
                    var datosJSON = JSON.stringify(json);
                    $.post("<?php echo RUTA_API?>/SITIFACSUNAT/index.php/Sunat/send_sunat",{datosJSON})
                     .done(function(res){
          
                        var response;
                        try{
                             response = JSON.parse(res);
                             if(response.res == 1){
                                toast("success", 4000, response.msg);
                                     $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoResumenCPE",{estado:response.estado,comprobante:response.comprobante,ticket:response.ticket,numero:response.numero})
                                     .done(function(res){
                                          dataSource.read();
                                     })
                             }

                        }catch(e){
                            console.log(res);
                            toast("error",5000, "Error en el XML generado");
                          
                        }

                           
                     })
                }else{
                     toast("success", 4000,'Enviado a resumenes de boletas por anular');
                     $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoCPE",{estado:5,comprobante:json.config.id,ticket:'none',numero:'none'})
                     .done(function(res){
                        dataSource.read();
                     })
                }
                
             });
     
        
    }*/

    function send_anulacion(opc,resumen_id){
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosAnulacion",{opc,comprobante_id:resumen_id})
             .done(function(json){
                    toast("info",10000, 'Enviando RESUMEN DE ANULACIÓN . . .');
                    var datosJSON = JSON.stringify(json);
                    $.post("<?php echo RUTA_API?>index.php/Sunat/send_anulacion",{datosJSON})
                     .done(function(res){
                        var response;
                        try{
                                 response = JSON.parse(res);
                                 if(response.res == 1){
                                    
                                         $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoResumenCPE",{comprobante:resumen_id,estado:response.estado,ticket:response.ticket,numero:response.numero})
                                         .done(function(res){
                                              toast("success", 4000, response.msg); 
                                              dataSource.read();
                                         })
                                 }

                        }catch(e){
                                console.log(res);
                                toast("error",6000,res);
                              
                        }

                               
                    })    

            });
        }

    var dataSource = new kendo.data.DataSource({
             transport: {
                read: {
                    url:"<?php echo base_url()?>index.php/resumenes/getMainList/",
                    dataType:"json",
                    method:'post',
                    data:function(){
                        return {
                            numero:function(){
                                return $("#numero").val();
                            }
                        }
                    }
                }
            },
            schema:{
                data:'data',
                total:'rows'
            },
            pageSize: 20,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true
                             
    });
    $("#grid").kendoGrid({
        dataSource: dataSource,
        height: 600,
        sortable: true,
        pageable: true,
        columns: [
        			{field:'indice',title:'N°',width:'20px'},
                    {field:'numero',title:'NUMERO',width:'100px'},
                    {field:'fecha',title:'FECHA',width:'100px'},
                    //{field:'estado',title:'ESTADO',width:'50px'},
                    {field:'enviar', title:'ESTADO',width:'60px',template:"#= enviar #"},
        ],
        detailTemplate: '<div class="lista_resumenes"></div>',
        detailInit: detailInit,
        dataBound:function(e){
            //modificar producto
            $(".btn_modificar_categoria").click(function(e){
               var idCategoria = $(this).data('id');               
                $("#myModal").load('<?php echo base_url()?>index.php/categoria/editar/'+idCategoria,{});
            });
            //enviar al facturador
            var PrintWindow;
            function openWindow(id) {
              PrintWindow = window.open('<?PHP echo base_url() ?>index.php/resumenes/txtboleta/'+id);
              setTimeout(function() {closeOpenedWindow();}, 400);
            }
            function closeOpenedWindow() {
              PrintWindow.close();
            }
            
            $('#btn_enviar_facturador').click(function(e) {
                e.preventDefault();
                var _id = $(this).attr('data_id');
                window.location.href = '<?PHP echo base_url() ?>index.php/resumenes/txtboleta/'+_id;
            });
            
                                    
        }
    });   


    //buscar seccion por campo texto
    $("#numero").keyup(function(e){
        e.preventDefault();
        var enter = 13;
        if(e.which == enter)
        {
            dataSource.read();
        };
    })
     function detailInit(e) {
        var detailRow = e.detailRow;

        detailRow.find(".lista_resumenes").kendoGrid({
            dataSource: {
                type: "json",
                transport: {
                    read:{
                        url:"<?php echo base_url()?>index.php/resumenes/getMainListDetail/",
                        dataType:"json",
                        method:'post',
                        data:function(){
                            return {
                                resumen_id:e.data.id
                            }
                        }
                    }
                },
                schema:{
                    data:'data',
                    total:'rows'
                },                
                serverPaging: true,
                serverSorting: true,
                serverFiltering: true,
                pageSize: 7,
            },
            scrollable: false,
            sortable: true,
            pageable: true,
            columns: [
                { field: "indiced", title:"N°", width: "40px" },
                { field: "numdoc", title:"DOCUMENTO", width: "40PX" },                
                { field: "f_emision", title:"F.EMISION", width: "80px" },
                { field: "f_vencimiento", title:"F.VENCIMIENTO", width: "80px" },
                { field: "cliente", title:"CLIENTE", width: "120px" },
                { field: "importe", title:"IMPORTE", width: "60px" },
                { field: "igv", title:"IGV", width: "60px" },
                { field: "total", title:"TOTAL", width: "60px" },
                { field: "estadot", title:"Estado", width: "60px" },
                
               // { field: "ingd_eliminar", title:"&nbsp;",width:"70px",template:"#= ingd_eliminar #"}
            ],
            dataBound:function(e){
                

            }
        });
    }

</script>