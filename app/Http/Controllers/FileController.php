<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($_FILES['file']['tmp_name']);
        
        $pages = $pdf->getPages();
        
        $transactions = [];
        
        foreach ($pages as $page) {
            $data = $page->getDataTm();
            
            foreach ($data as $key => $subArr) {
                unset($subArr['0']);
                $data[$key] = $subArr;  
            }
            
            array_push($transactions, data_get($data, '*.1'));
        }
            
        $collapsed = Arr::collapse($transactions);
        
        $transactionsArray = [];
        
        function isValid($date, $format = 'd-m-Y'){
            try {
                $dt = Carbon::createFromFormat($format, $date);
                return $dt && $dt->format($format) === $date;
            } catch (Exception $e) {
                return false;
            }
        }
        
        foreach($collapsed as $key => $value) {
            if (isValid($value)) {
                $start = $key;
            } elseif (Str::contains($value, 'Valutadatum')) {
                $end = $key;
            }
            
            if (isset($start) && isset($end)) {
                $range = $end + 1 - $start;
                array_push($transactionsArray, array_slice($collapsed, $start, $range));
                
                $start = null;
                $end = null;
            }
        }
        
        $overview = [];
        
        foreach ($transactionsArray as $item) {
            
            $transaction = Transaction::create([
                'source' => $item[1],
                'amount' => str_replace(' ', '', $item[3]),
                'tag' => $request->tag ? $request->tag : null,
                'date_transacted' => str_replace('Valutadatum: ', '', end($item)),
                'date_booked' => $item[0],
            ]);
            
            array_push($overview, $transaction);
        }
        
        return redirect()->route('home');
    }
}
