<?php

require __DIR__ . '/../ticket/autoload.php';
//require __DIR__ . '/../autoload.php';
//require __DIR__ . '/ticket/src/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class Ticket_model  extends CI_Controller
{
    public function imprimirBoleta($data)
    {
        $impresora = "POS";

        var_dump(unserialize(urldecode($this->uri->segment(3))));exit;

        var_dump($this->uri->segment(3));exit;
        var_dump($data);exit;

        $connector = new WindowsPrintConnector($impresora);
        $printer = new Printer($connector);

        echo 1;


        $printer ->setJustification(Printer::JUSTIFY_CENTER);

        try{
            $logo = EscposImage::load("geek.png", false);
            $printer->bitImage($logo);
        }catch (Exception $ex){/*No hacemos nada si hay un error*/};



        $printer->text("\n"."Nombre de la Empresa" . "\n");
        $printer->text("\n"."Dirección: Santa Clara 457" . "\n");
        $printer->text("\n"."Telefono: 977589542" . "\n");
        date_default_timezone_set("America/Lima");
        $printer->text(date("Y-m-d H:i:s") . "\n");
        $printer->text("------------------------------" . "\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("CANT  DESCRIPCION  P:U  IMP.\n");
        $printer->text("------------------------------" . "\n");

        /*
            A partir de aca se imprimen los productos
        */
        /*Alinear a la izquierda para la cantidad y el nombre*/

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Producto Galletas \n");
        $printer->text("2 pieza 10.00 30.00 \n");
        $printer->text("Doritos \n");
        $printer->text("5 pieza 10.00 50.00 \n");

        /*Terminamos los productos ahora va el total*/

        $printer->text("-----------------------------------"."\n");

        $printer->setJustification(Printer::JUSTIFY_RIGHT);

        $printer->text("SUBTOTAL: $100.00 \n");
        $printer->text("IVA: $16.00 \n");
        $printer->text("TOTAL: $116.00 \n");


        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Muchas gracias por su compra  \n");

        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }
}