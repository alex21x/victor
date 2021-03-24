<?php
require __DIR__ . '/../ticket/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class Ticket_model extends CI_Model
{
    public function imprimirBoleta($data)
    {
        $impresora = "POS";
        //echo $impresora;exit;

        //echo $data['comprobante']['empresa'];exit;
        //var_dump($data);exit;
        $connector = new WindowsPrintConnector($impresora);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("\n" . $data['comprobante']['empresa'] . "\n");
        $printer->text("\n" . "RUC " . $data['comprobante']['empresa_ruc'] . "\n");
        $printer->text("\n" . $data['comprobante']['domicilio_fiscal'] . "\n");
        date_default_timezone_set("America/Lima");
        $printer->text(date("Y-m-d H:i:s") . "\n");
        $printer->text("------------------------------------------------" . "\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("CANT    DESCRIPCION          P/U       SUBTOTAL.\n");
        $printer->text("------------------------------------------------" . "\n");

        /*
            A partir de aca se imprimen los productos
        */
        /*Alinear a la izquierda para la cantidad y el nombre*/

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        foreach ($data['items'] as $row) {
            $printer->text($row['cantidad'] . "       " . $row['descripcion']);

            for($ii = strlen($row['descripcion']); $ii < 20; $ii++ ){
                $printer->text(" ");
            }
            $printer->text(number_format($row['importe'],1));

            for($i = strlen(number_format($row['subtotal'],1)); $i < 10; $i++ ){
                $printer->text( "  ");
            }
            $printer->text( number_format($row['subtotal'],1));
            $printer->text(  "\n");
        }
        $printer->text("------------------------------------------------" . "\n");
        $printer->text("" . "\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("SUBTOTAL:" . $data['comprobante']['total_gravada'] . "\n");
        $printer->text("IGV:" . $data['comprobante']['total_igv'] . "\n");
        $printer->text("TOTAL:" . $data['comprobante']['total_a_pagar'] . "\n");


        $printer->text("----------------------" . "\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Muchas gracias por su compra  \n");
        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();


    }
}