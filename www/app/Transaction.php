<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'title',
        'rate',
        'qty',       
        'type',
        'author',
        'attachment',
        'description',
    ];

    /**
     * trasaction index with search and pagination
     */
    
    public function getTransactions($request)
    {
        $transactions = $this->select(['*']);
        if (!empty($request->search['field'])) {
            $searchField = $request->search['field'];
            $searchValue = $request->search['value'];
            $transactions->where($searchField, 'like', '%' . $searchValue . '%');
        }
        $transactions->orderBy('transactions.id', 'desc');       
        return $transactions->paginate($request->limit);
    }

}