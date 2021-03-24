    
   (function ($) {
        $.widget("ui.combobox", {
            _create: function () {
                var input,
                  that = this,
                  wasOpen = false,
                  select = this.element.hide(),
                  selected = select.children(":selected"),
                  defaultValue = selected.text() || "",
                  wrapper = this.wrapper = $("<span>")
                    .addClass("ui-combobox")
                    .insertAfter(select);

                function removeIfInvalid(element) {
                    var value = $(element).val(),
                      matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(value) + "$", "i"),
                      valid = false;
                    select.children("option").each(function () {
                        if ($(this).text().match(matcher)) {
                            this.selected = valid = true;
                            return false;
                        }
                    });

                    if (!valid) {
                        // remove invalid value, as it didn't match anything
                        $(element).val(defaultValue);
                        select.val(defaultValue);
                        input.data("ui-autocomplete").term = "";
                    }
                }

                input = $("<input>")
                  .appendTo(wrapper)
                  .val(defaultValue)
                  .attr("title", "")
                  .addClass("ui-state-default ui-combobox-input")
                  .width(select.width())
                  .autocomplete({
                      delay: 0,
                      minLength: 0,
                      autoFocus: true,
                      source: function (request, response) {
                          var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
                          response(select.children("option").map(function () {
                              var text = $(this).text();
                              if (this.value && (!request.term || matcher.test(text)))
                                  return {
                                      label: text.replace(
                                        new RegExp(
                                          "(?![^&;]+;)(?!<[^<>]*)(" +
                                          $.ui.autocomplete.escapeRegex(request.term) +
                                          ")(?![^<>]*>)(?![^&;]+;)", "gi"
                                        ), "<strong>$1</strong>"),
                                      value: text,
                                      option: this
                                  };
                          }));
                      },
                      select: function (event, ui) {
                          ui.item.option.selected = true;
                          that._trigger("selected", event, {
                              item: ui.item.option
                          });
                      },
                      change: function (event, ui) {
                          if (!ui.item) {
                              removeIfInvalid(this);
                          }
                      }
                  })
                  .addClass("ui-widget ui-widget-content ui-corner-left");

                input.data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li>")
                      .append("<a>" + item.label + "</a>")
                      .appendTo(ul);
                };

                $("<a>")
                  .attr("tabIndex", -1)
                  .appendTo(wrapper)
                  .button({
                      icons: {
                          primary: "ui-icon-triangle-1-s"
                      },
                      text: false
                  })
                  .removeClass("ui-corner-all")
                  .addClass("ui-corner-right ui-combobox-toggle")
                  .mousedown(function () {
                      wasOpen = input.autocomplete("widget").is(":visible");
                  })
                  .click(function () {
                      input.focus();

                      // close if already visible
                      if (wasOpen) {
                          return;
                      }

                      // pass empty string as value to search for, displaying all results
                      input.autocomplete("search", "");
                  });
            },

            _destroy: function () {
                this.wrapper.remove();
                this.element.show();
            }
        });                 
    })(jQuery);
        
        
    
    function validar_item(){
        var campos = $('#valida input[type=text],textarea').serializeArray();                        
        var mensaje  = "";
        var contador = 0;
        var cmp = '';        
        
        // Campos Vacios                        
        $.each(campos,function(){
            if(this.value === ''){
                mensaje +=  "\n- " + this.name;
                contador++;
                cmp += this.name + '-';
            }                                                
        });  
        cmp = cmp.split('-');        
        $('#'+cmp[0]).focus();
        
        //console.log(contador);
        if (contador > 0){                                                
            alert('Campos Requeridos :'+ mensaje);            
            return contador;
        }                
    }
                
    
    function checkDecimals(fieldName, fieldValue) {
        decallowed = 2; // how many decimals are allowed?

        if (isNaN(fieldValue) || fieldValue == "") {            
            alert("El número no es válido. Prueba de nuevo.");        
            fieldName.select();
            fieldName.focus();        
            return 5;
        }
        else {
        if (fieldValue.indexOf('.') === -1) fieldValue += ".";
            dectext = fieldValue.substring(fieldValue.indexOf('.')+1, fieldValue.length);

        if (dectext.length > decallowed)
        {           
            alert ("Por favor, entra un número con " + decallowed + " números decimales.");        
            fieldName.select();
            fieldName.focus();        
            return 5;        
              }
           }
    }

    function validDecimals(e,str){
        var decallowed = 2; // how many decimals are allowed?
        var valorNum = str.value;

        //alert(str.value);                          
        if (e.ctrlKey || e.altKey)           
                e.preventDefault();           
          //alert(event.keyCode);                              
          
           if (e.keyCode === 8 || e.keyCode === 9 || e.keyCode === 16 || e.keyCode === 46 || e.keyCode === 110 ||(e.keyCode > 34 && e.keyCode < 40)) {    
               
               if (e.keyCode === 110 && isNaN(valorNum+'.')) {            
                    e.preventDefault();
                }                                                
           }
           else {
                if (e.keyCode < 95) {
                  if (e.keyCode < 48 || e.keyCode > 57) {
                        e.preventDefault();
                  }
                  else {
                        if (valorNum.indexOf('.') === -1) valorNum += ".";
                            dectext = valorNum.substring(valorNum.indexOf('.')+1, valorNum.length);                            
                                if (dectext.length >= decallowed){                                                               
                                    e.preventDefault();
                                }                                                            
                  }
                } 
                else {
                      if (e.keyCode < 96 || e.keyCode > 105) {
                          e.preventDefault();
                      }                      
                      else{
                          if (valorNum.indexOf('.') === -1) valorNum += ".";
                            dectext = valorNum.substring(valorNum.indexOf('.')+1, valorNum.length);                            
                                if (dectext.length >= decallowed){                                                               
                                    e.preventDefault();
                                }                                                        
                      }
                }
              }                                                                                
    }    
    
    
    function validNumericos(e){      
        //alert(e.keyCode);
        
        if (e.ctrlKey || e.altKey)           
                e.preventDefault();                   
          
           if (e.keyCode === 8 || e.keyCode === 9 || e.keyCode === 16 || e.keyCode === 46 || (e.keyCode > 34 && e.keyCode < 40)){}
           else {
                if (e.keyCode < 95) {
                  if (e.keyCode < 48 || e.keyCode > 57) {
                        e.preventDefault();
                  }             
                }
                else {
                      if (e.keyCode < 96 || e.keyCode > 105) {
                          e.preventDefault();
                      }
                }
              }                                                                        
    }
    
    function validAlfaNumerico(e){
        console.log(e.keyCode);
        if (e.ctrlKey || e.altKey)
                e.preventDefault();
            
            if (e.keyCode === 8 || e.keyCode === 9 || e.keyCode === 16 || e.keyCode === 46 || (e.keyCode > 34 && e.keyCode < 40)){}
            else {
                if(e.keyCode < 95 ){
                    if(e.keyCode < 48 || e.keyCode > 90){e.preventDefault();}
                    else if(e.keyCode > 65 || e.keyCode > 90){}
                }
                else {
                      if (e.keyCode < 96 || e.keyCode > 105) {
                          e.preventDefault();
                      }                                            
                }
            }                                        
    }    