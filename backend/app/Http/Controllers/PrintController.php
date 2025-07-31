<?php

namespace App\Http\Controllers;

use App\Helper;
//use Illuminate\Http\Request;
require base_path('vendor') . '/autoload.php';
use App\Http\Resources\InvoiceDetails;
use App\Http\Resources\User as UserResource;
use App\Invoice;
use App\User;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrintController extends Controller
{
    // /use Printer, FilePrintConnector;
    public function addSpaces($string = '', $valid_string_length = 0)
    {
        if (strlen($string) < $valid_string_length) {
            $spaces = $valid_string_length - strlen($string);
            for ($index1 = 1; $index1 <= $spaces; $index1++) {
                $string = $string . ' ';
            }
        }

        return $string;
    }

    public function prints(Invoice $invoice, User $user)
    {
        $InvoiceDetails = new InvoiceDetails($invoice);
        $invoiceJson = json_encode($InvoiceDetails, true);
        $invoice = json_decode($invoiceJson);
        $userResource = new UserResource($user);
        $UserJson = json_encode($userResource, true);
        $user = json_decode($UserJson);
        try {
            $profile = CapabilityProfile::load("simple");
            $connector = new WindowsPrintConnector("smb://" . config('app.printer_path'));

            $printer = new Printer($connector, $profile);
            $print_type = $invoice->status == "not-paid" ? "SALES INVOICE" : "ORIGINAL RECEIPT";
            if ( $invoice->status == "not-paid") {
                $this->printJob($user, $invoice, $printer, $print_type, Printer::CUT_FULL);
            }
            else{
                $this->printJob($user, $invoice, $printer, $print_type, Printer::CUT_PARTIAL);
                $this->printJob($user, $invoice, $printer, $print_type, Printer::CUT_FULL);

            }
            $printer->pulse();
            $printer->close();

            return Helper::validRequest($user->names, 'invoice printed successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    public function columnate($qtyCol, $itemCol, $priceCol, $amountCol, $qtyWidth, $itemWidth, $priceWidth, $amountWidth, $space = 4)
    {

        $qtyWrapped = wordwrap($qtyCol, $qtyWidth, "\n", true);
        $itemWrapped = wordwrap($itemCol, $itemWidth, "\n", true);
        $priceWrapped = wordwrap($priceCol, $priceWidth, "\n", true);
        $amountWrapped = wordwrap($amountCol, $amountWidth, "\n", true);

        $qtyLines = explode("\n", $qtyWrapped);
        $itemLines = explode("\n", $itemWrapped);
        $priceLines = explode("\n", $priceWrapped);
        $amountLines = explode("\n", $amountWrapped);

        $allLines = array();

        for ($i = 0; $i < max(count($qtyLines), count($itemLines), count($priceLines), count($amountLines)); $i++) {
            $qtyPart = str_pad(isset($qtyLines[$i]) ? $qtyLines[$i] : "", $qtyWidth, " ");
            $itemPart = str_pad(isset($itemLines[$i]) ? $itemLines[$i] : "", $itemWidth, " ");
            $pricePart = str_pad(isset($priceLines[$i]) ? $priceLines[$i] : "", $priceWidth, " ");
            $amountPart = str_pad(isset($amountLines[$i]) ? $amountLines[$i] : "", $amountWidth, " ");
            $allLines[] = $qtyPart . str_repeat(" ", $space) . $itemPart . str_repeat(" ", $space) . $pricePart . str_repeat(" ", $space) . $amountPart;
        }
        return implode($allLines, "\n") . "\n";

    }

    public function printJob($user, $invoice, $printer, $print_type, $printmode){
            

            $total = new item('Total', number_format($invoice->total, 2), true);
            $payment = new item('Amount Recd', number_format($invoice->payment, 2), true);
            $balance = new item('Change', number_format($invoice->balance, 2), true);
/* Date is kept the same for testing */
            $date = date('l jS \of F Y h:i:s A');
/* Start the printer */
            /* Name of shop */
            // $printer->setPrintLeftMargin(0);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer->text("Big Star IND CO Ltd.\n");
            $printer->selectPrintMode();
            $printer->text(" Zone 15 No 76, New Motorcycle Spare Parts Nnewi\n");
            $printer->text("08039303292\n");
            $printer->feed();
/* Title of receipt */
            $printer->setEmphasis(true);
            $printer->text($print_type . "\n");
$printer->feed();
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(true);
            $printer->text("payment status: ");
            $printer->setEmphasis(false);
            $printer->text($invoice->status . "\n");
            $printer->setEmphasis(true);
            if ($invoice->status != "not-paid") {
                $printer->text("paid by: ");
                $printer->text($invoice->payment_mode . "\n");
            }
            $printer->text("Invoice no: ");
            $printer->setEmphasis(false);
            $printer->text($invoice->id . "\n");
            if ($invoice->status != "not-paid") {
                $printer->setEmphasis(true);
                $printer->text("Payment date: ");
                $printer->setEmphasis(false);
                $printer->text($invoice->transaction_updated . "\n");
            }
            if (!empty($invoice->due_date )) {
                $printer->setEmphasis(true);
                $printer->text("Payment due date: ");
                $printer->setEmphasis(false);
                $printer->text($invoice->due_date . "\n");
            }
            $printer->feed();

            /* customer and invoice details */
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setEmphasis(true);
            if ($invoice->type == "purchase") {
                $printer->text("Supplier: ");
            }
            else $printer->text("Customer: ");
            $printer->setEmphasis(false);
            $printer->text($invoice->name . "\n");
            $printer->setEmphasis(true);
            $printer->text("Telephone: ");
            $printer->setEmphasis(false);
            $printer->text($invoice->number . "\n");

            $printer->setEmphasis(true);
            $printer->text("Cashier : ");
            $printer->setEmphasis(false);
            $printer->text($user->names . "\n");
        // $printer->setEmphasis(true);
        //     $printer->text("Telephone: ");
        //     $printer->setEmphasis(false);
        //     $printer->text($user->number . "\n");
            $printer->feed();

            /* Items */

            $printer->setEmphasis(true);
            $printer->text($this->columnate('Qty', 'Item', 'Price   (=N=)', 'Amount        (=N=)', 4, 18, 8, 14, 4));
            $data = $this->columnate('Qty', 'Item', 'Price   (=N=)', 'Amount        (=N=)', 4, 18, 8, 14, 4);
            // dd($data);

            $printer->setEmphasis(false);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            foreach ($invoice->details as $item) {
                $data = $this->columnate($item->quantity, $item->product, number_format($item->price), number_format($item->amount), 4, 18, 6, 17, 3);

                $printer->text($data);
            }
/* Tax and total */
    $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setEmphasis(true);
            $printer->text($total);
            $printer->text($payment);
            $printer->text($balance);
            $printer->setEmphasis(false);
            $printer->selectPrintMode();

            /* Footer */
            $printer->feed(2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
if ($invoice->status != "not-paid") {
               $printer->text("Thank you for shopping at Big Star\n");
            $printer->text("No refund or return of goods after payment\n");
            }
            
            $printer->feed(2);
            $printer->text($date . "\n");
            /* Cut the receipt and open the cash drawer */
            $printer->cut($printmode);
            
    }

}

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;
    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this->name = $name;
        $this->price = $price;
        $this->dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this->dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this->name, $leftCols);

        $sign = ($this->dollarSign ? 'N' : '');
        $right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
    public function columnify($leftCol, $rightCol, $leftWidth, $rightWidth, $space = 4)
    {
        $leftWrapped = wordwrap($leftCol, $leftWidth, "\n", true);
        $rightWrapped = wordwrap($rightCol, $rightWidth, "\n", true);

        $leftLines = explode("\n", $leftWrapped);
        $rightLines = explode("\n", $rightWrapped);
        $allLines = array();
        for ($i = 0; $i < max(count($leftLines), count($rightLines)); $i++) {
            $leftPart = str_pad(isset($leftLines[$i]) ? $leftLines[$i] : "", $leftWidth, " ");
            $rightPart = str_pad(isset($rightLines[$i]) ? $rightLines[$i] : "", $rightWidth, " ");
            $allLines[] = $leftPart . str_repeat(" ", $space) . $rightPart;
        }
        return implode($allLines, "\n") . "\n";
    }
    public function columnate($qtyCol, $itemCol, $priceCol, $amountCol, $qtyWidth, $itemWidth, $priceWidth, $amountWidth, $space = 4)
    {

        $qtyWrapped = wordwrap($qtyCol, $qtyWidth, "\n", true);
        $itemWrapped = wordwrap($itemCol, $itemWidth, "\n", true);
        $priceWrapped = wordwrap($priceCol, $priceWidth, "\n", true);
        $amountWrapped = wordwrap($amountCol, $amountWidth, "\n", true);

        $qtyLines = explode("\n", $qtyWrapped);
        $itemLines = explode("\n", $itemWrapped);
        $priceLines = explode("\n", $priceWrapped);
        $amountLines = explode("\n", $amountWrapped);

        $allLines = array();

        for ($i = 0; $i < max(count($qtyLines), count($itemLines), count($priceLines), count($amountLines)); $i++) {
            $qtyPart = str_pad(isset($qtyLines[$i]) ? $qtyLines[$i] : "", $qtyWidth, " ");
            $itemPart = str_pad(isset($itemLines[$i]) ? $itemLines[$i] : "", $itemWidth, " ");
            $pricePart = str_pad(isset($priceLines[$i]) ? $priceLines[$i] : "", $priceWidth, " ");
            $amountPart = str_pad(isset($amountLines[$i]) ? $amountLines[$i] : "", $amountWidth, " ");
            $allLines[] = $qtyPart . str_repeat(" ", $space) . $itemPart . str_repeat(" ", $space) . $pricePart . str_repeat(" ", $space) . $amountPart . str_repeat(" ", $space);
        }

    }
}
class Details
{
    private $quantity;
    private $name;
    private $price;
    private $dollarSign;
    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this->name = $name;
        $this->price = $price;
        $this->dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;
        if ($this->dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this->name, $leftCols);

        $sign = ($this->dollarSign ? '$ ' : '');
        $right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}
