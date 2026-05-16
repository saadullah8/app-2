<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
class PrinterController extends Controller
{
    public function startPrint(){

        try{
            $profile = CapabilityProfile::load("SP2000");
            $connector = new WindowsPrintConnector("smb://computer/printer");
            $printer = new Printer($connector, $profile);

            $printer -> text("Hello world\n");
            $printer -> cut();
        }
        catch (\Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }

        //return view('index.print_page');
    }

    public function PrintFile(){
        try{
            $connector = new FilePrintConnector("php://stdout");
            $printer = new Printer($connector);
            $printer -> text("Hello world\n");
            $printer -> cut();
        }
        catch (\Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }
    public function newPrinterFile(){
        try {
            $connector = new WindowsPrintConnector("LPT1");

            // A FilePrintConnector will also work, but on non-Windows systems, writes
            // to an actual file called 'LPT1' rather than giving a useful error.
            // $connector = new FilePrintConnector("LPT1");
            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);
            $printer ->text("Hello World!\n");
            $printer ->cut();
            /* Close printer */
            $printer ->close();
        } catch (\Exception $e) {
            echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }
    }

    public function PrintRep(){

        $connector = new WindowsPrintConnector("printer name here");
        $printer = new Printer($connector);
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> setEmphasis(true);
        $printer -> text("Hummas Mediterranean Grill\n");
        $printer -> setEmphasis(false);

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("11205 Jhon F");
        $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        $date = date('M d Y',time());
        $printer -> text($date . "\n");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("Kennedy Dr Unit");
        $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        $date = date('h:i a',time());
        $printer -> text($date . "\n");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("108A\n");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("HAGERS TOWN, MD 21742\n");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("(240) 513-6020\n");
        $printer -> feed();
        $printer->text("----------------------------------------");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("Ticket: Sam\n");
        $printer -> feed();
        $printer->text("----------------------------------------");

        /*
         * Display product and price
         */
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(true);
        $printer -> text("Chicken Rice Bowl");
        $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        $printer -> text("$7");
        $printer -> setEmphasis(false);
        $printer -> text("Greens Bowl, Rice Bowl, Chicken Kabob, Couscous, Tabboulch, Ezme, Crumbled Feta, Diced Tomatoes, Garlic Sauce, Schug Sauce, Mediterranean Dressing, Poppy Seed Dressing\n");
        $printer -> feed();
        $printer->text("----------------------------------------");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("Sub Total");
        $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        $printer -> text("$7.99\n");
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("Sales Tax");
        $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        $printer -> text("$0.48\n");
        $printer -> feed();
        $printer->text("----------------------------------------");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("Total");
        $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        $printer -> text("$8.47\n");

        $printer -> feed();
        $printer -> cut();
        $printer -> close();
    }

    public function formPrinter($id)
    {

        $id = decrypt($id);
        //echo $id;
        Order::where('id',$id)->update(array('order_status'=>3));
        $order = Order::where('id',$id)->first();

        $tax= $this->AddTax($order->total_amount);
        return view('orders.file',compact('order','tax'));
    }
    public function formPendingPrint($id)
    {
        $id = decrypt($id);
        $order = Order::where('id',$id)->first();
        return view('orders.pending_print_file',compact('order'));
    }

}
