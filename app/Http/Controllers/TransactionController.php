<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $tag = $request->tag;
        
        $grouped = Transaction::select('source', 'amount', 'tag', 'date_transacted', 'date_booked')->get()->groupBy('tag');
        
        if (isset($tag)) {
            $transactions = $grouped[$tag]->toArray();
        } else {
            $transactions = $grouped[""]->unique(function ($item) {
                return $item['source'].$item['amount'];
            })->toArray();
        }
        
        $selection = $request->tag ? $request->tag : 'all';
        $firstDate = $grouped[$tag]->first()->date_transacted;
        $lastDate = $grouped[$tag]->last()->date_transacted;
        
        $filename = "{$selection}_{$lastDate}_to_{$firstDate}";
        
        $fp = fopen("{$filename}.csv", 'w');

        foreach ($transactions as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
        
        dd($transactions);
    }
}
