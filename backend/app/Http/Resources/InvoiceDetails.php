<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $type = $this->type;
        $invoice = [];
        $name = '';

        if ($this->type == "order") {
            $type_id = 'order_id';
            $details = $this->order->orderDetails;
            $name = $this->order->customer->name;
            $due_date = $this->order->customer->due_date;
            $number = $this->order->customer->number;
            $email = $this->order->customer->email;
            $order_details = OrderDetailResource::collection($details);

            foreach ($order_details as $key => $value) {
                $order_detail['product'] = $value->pku . ' '. $value->product . ' '. $value->size . ' '. $value->brand;
                $order_detail['quantity'] = $value->quantity;
                $order_detail['price'] = $value->price;
                $order_detail['discount'] = $value->discount;
                $order_detail['amount'] = $value->amount;

                $order_detail_object = (object) ($order_detail);
                array_push($invoice, $order_detail_object);
            }

        } else {
            $type_id = 'purchase_id';
            $details = $this->purchase->purchaseDetails;
            $name = $this->purchase->supplier->name;
            $email = $this->purchase->supplier->email;
            $due_date = $this->purchase->supplier->due_date;
            $number = $this->purchase->supplier->number;
            $purchase_details = PurchaseDetailResource::collection($details);

            foreach ($purchase_details as $key => $value) {
                $purchase_detail['product'] = $value->pku . ' ' . $value->product .' '. $value->size .' '. $value->brand;
                $purchase_detail['quantity'] = $value->quantity;
                $purchase_detail['price'] = $value->price;
                $purchase_detail['amount'] = $value->amount;

                $purchase_detail_object = (object) ($purchase_detail);
                array_push($invoice, $purchase_detail_object);

            }

        }
        return [
            'id' => $this->id,
            'type' => $type,
            'tku' => $type . ' ' . $this->updated_at->format('Y-m-d H:i:s'),
            $type_id => $this->$type_id,
            'user_id' => $this->user_id,
            'name' => $name,
            'payment' => empty($this->transaction) ? 0 : $this->transaction->payment,
            'email' => $email,
            'due_date' => $due_date,
            'number' => $number,
            'user' => $this->user->first_name . ' ' . $this->user->last_name,
            'transaction_id' => empty($this->transaction) ? null : $this->transaction->id,
            'details' => $invoice,
            'total' => $this->$type->amount,
            'balance' => $this->balance,
            'status' => $this->status,
            'payment_mode' => $this->paymentmode,
            'transaction_updated' => empty($this->transaction) ? null : $this->transaction->updated_at->format('d-m-Y H:i'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'date' => Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans(),

        ];
    }
}
