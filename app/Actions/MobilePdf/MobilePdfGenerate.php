<?php
namespace App\Actions\MobilePdf;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\New_Customer;
use Illuminate\Support\Facades\Storage;
 class MobilePdfGenerate{

    protected $order;
    protected $refid;
    public function __construct(Order $order,$refid)
    {
        $this->order=$order;
        $this->refid=$refid;
    }
    public function createPdf()
    {
        // $order = $this->order;
        // $customer=New_Customer::findOrFail($this->order->user_id);
        // $refId = $this->order->ref_id;
        // $data = $this->order->orderAssets;
        // // dd(public_path());
        // // public_path('/backend/dist/img/logo.png')
        // $path = 'https://mystore.com.np/public/backend/dist/img/logo.png';
        // $type = pathinfo($path, PATHINFO_EXTENSION);
        // $img = file_get_contents($path);
        // $tick = 'data:image/' . $type . ';base64,' . base64_encode($img);
        
        // $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('frontend.orderpdf', compact('order','data', 'refId','customer','tick'));
        // $pdf->save('https://mystore.com.np/public/mobilepdf' . $this->order->id.'.pdf');
        $order = $this->order;
        $customer = New_Customer::findOrFail($this->order->user_id);
        $refId = $this->order->ref_id;
        $data = $this->order->orderAssets;
        $path = 'https://mystore.com.np/public/backend/dist/img/logo.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $tick = 'data:image/' . $type . ';base64,' . base64_encode($img);

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('frontend.orderpdf', compact('order', 'data', 'refId', 'customer', 'tick'));

        $localPath = storage_path('app/public/mobilepdf' . $this->order->id . '.pdf');
      
        $pdf->save($localPath);

        $remotePath = 'mobilepdf' . $this->order->id . '.pdf';
        Storage::disk('pdf_path')->put($remotePath, file_get_contents($localPath));
    }
 }