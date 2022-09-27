<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuotesController extends Controller
{
    public $searchKey;
    public $status;
    public $quoteStatus;
    public $quote;
    public $quoteProduct;
    public $user;
    public $product;

    /**
     * QuotesController constructor.
     * @param Quote $quote
     * @param User $user
     * @param Product $product
     */
    public function __construct(Quote $quote, User $user, Product $product, QuoteProduct $quoteProduct)
    {
        $this->quote = $quote;
        $this->user = $user;
        $this->product = $product;
        $this->quoteProduct = $quoteProduct;
    }

    public function quoteList()
    {

        $returnViewArray = array();

        $this->searchKey = "";
        $this->status = "";
        $this->quoteStatus = 1;

        $orderByQry = 'id';
        $orderByQryTyp = 'desc';

        $sortID = '';
        if (isset($_GET['sortID'])) {
            $sortID = trim($_GET['sortID']);
            $orderByQry = 'id';
            $orderByQryTyp = $sortID;
        }
        $orderCustomer = '';
        if (isset($_GET['orderCustomer'])) {
            $orderCustomer = trim($_GET['orderCustomer']);
            $orderByQry = 'customer_name';
            $orderByQryTyp = $orderCustomer;
        }

        $orderProductCount = '';
        if (isset($_GET['orderProductCount'])) {
            $orderProductCount = trim($_GET['orderProductCount']);
            $orderByQry = 'products_count';
            $orderByQryTyp = $orderProductCount;
        }
        $orderTotalPrice = '';
        if (isset($_GET['orderTotalPrice'])) {
            $orderTotalPrice = trim($_GET['orderTotalPrice']);
            $orderByQry = 'total_price';
            $orderByQryTyp = $orderTotalPrice;
        }
        $orderCreatedDate = '';
        if (isset($_GET['orderCreatedDate'])) {
            $orderCreatedDate = trim($_GET['orderCreatedDate']);
            $orderByQry = 'created_at';
            $orderByQryTyp = $orderCreatedDate;
        }
        $orderStatus = '';
        if (isset($_GET['orderStatus'])) {
            $orderStatus = trim($_GET['orderStatus']);
            $orderByQry = 'status';
            $orderByQryTyp = $orderStatus;
        }

        if (isset($_GET['s'])) {
            $this->searchKey = trim($_GET['s']);
        }
        if (isset($_GET['status'])) {
            $this->status = trim($_GET['status']);
        }

        $returnViewArray['searchKey'] = $this->searchKey;
        $returnViewArray['status'] = $this->status;

        $returnViewArray['sortID'] = $sortID;
        $returnViewArray['orderCustomer'] = $orderCustomer;
        $returnViewArray['orderProductCount'] = $orderProductCount;
        $returnViewArray['orderTotalPrice'] = $orderTotalPrice;
        $returnViewArray['orderCreatedDate'] = $orderCreatedDate;
        $returnViewArray['orderStatus'] = $orderStatus;

        $quotes = $this->quote->select(
            DB::raw(
                '
                     quotes.*
                     ,(select CONCAT(first_name,\' \',last_name) as customer_name from user_details where user_id = quotes.customer_id) as customer_name
                     ,(select count(id) as products_count from quote_products where quote_id=quotes.id) as products_count
                '
            )
        )->where(function ($query) {
            if (trim($this->searchKey) != '') {
                $query->where('id', trim($this->searchKey))
                    ->orWhereHas('customer', function ($query) {
                        $query->whereHas('detail',function ($query){
                            $query->where('first_name', 'like', '%' . trim($this->searchKey) . '%')
                                ->orWhere('last_name', 'like', '%' . trim($this->searchKey) . '%')
                                ->orWhere(DB::raw('CONCAT(first_name,\' \',last_name)'), 'like', '%' . trim($this->searchKey) . '%');
                        });
                    });
            }
        })
            ->where(
                function ($query) {
                    if (trim($this->status) != '') {
                        $query->where('status', trim($this->status));
                    }
                }
            )
            ->orderBy($orderByQry, $orderByQryTyp);


        $returnViewArray['quotes'] = $quotes->paginate(25);


        return view('quotes.list', $returnViewArray);
    }

    public function quoteAdd()
    {
        $returnViewArray = array();
        $customers = $this->user->where('type', CUSTOMER)->where('active', 1)->get();
        $returnViewArray['customers'] = $customers;
        return view('quotes.addEdit', $returnViewArray);
    }

    public function quoteEdit($id)
    {
        $returnViewArray = array();
        $quote = $this->quote->where('id', $id)->first();

        if (!$quote) {
            return view('messages')
                ->with('type', 'danger')
                ->with('message', 'Quote does not exist.');
        }

        $customers = $this->user->where('type', CUSTOMER)->where('active', 1)->get();
        $returnViewArray['customers'] = $customers;
        $returnViewArray['id'] = $id;
        $returnViewArray['quote'] = $quote;


        return view('quotes.addEdit', $returnViewArray);
    }

    public function quoteSave(Request $request)
    {
        $id = (int)$request->get('id', 0);
        if ($id != 0) {
            $quote = $this->quote->where('id', $id)->first();
            if (!$quote) {
                return view('messages')
                    ->with('type', 'danger')
                    ->with('message', 'Quote doesn\'s exist.');
            }
            $returnMessage = 'Successfully updated';
            if ($quote->status == DECLINED) {
                return view('messages')
                    ->with('type', 'danger')
                    ->with('message', 'Quote is declined.');
            }
        }

        $rules = array(
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'country' => 'required',

        );
        $messages = array(
            'address.required' => 'Street address is required.',
            'city.required' => 'City is required.',
            'state.required' => 'State is required.',
            'zip.required' => 'Postal Code is required.',
            'country.required' => 'Country is required.',
        );

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $sub_total = 0;
        $total_price = 0;

        $shipping_cost = (float)$request->get('shipping_cost', 0);
        $taxes_cost = (float)$request->get('taxes_cost', 0);
        $total_discount = 0;

        $data = [
            'created_by' => Auth::id(),
            'customer_id' => (int)trim($request->get('customer_id', 0)),
            'sub_total' => (float)$sub_total,
            'shipping' => $shipping_cost,
            'tax' => $taxes_cost,
            'discount' => $total_discount,
            'total_price' => $total_price,
            'note' => trim($request->get('note', '')),
            'status' => trim($request->get('status', 'New')),
            'address' => trim($request->get('address', '')),
            'address2' => trim($request->get('address2', '')),
            'city' => trim($request->get('city', '')),
            'state' => trim($request->get('state', '')),
            'zip' => trim($request->get('zip', '')),
            'country' => trim($request->get('country', '')),
            'phone' => trim($request->get('phone', '')),
        ];

        if ($id == 0) {
            $returnMessage = 'Successfully saved';
            $quote = $this->quote->create($data);
        } else {
            unset($data['created_by']);
            $quote->update($data);
        }


        $this->quoteProduct->where('quote_id', $quote->id)->delete();

        $cart_product_id = $request->get('cart_product_id', array());
        $cart_product_retail = $request->get('cart_product_retail', array());
        $cart_product_qty = $request->get('cart_product_qty', array());
        $cart_product_discount = $request->get('cart_product_discount', array());

        if (count($cart_product_id)) {
            for ($i = 0; $i < count($cart_product_id); $i++) {
                if (array_key_exists($i, $cart_product_id)
                    && array_key_exists($i, $cart_product_retail)
                    && array_key_exists($i, $cart_product_qty)
                    && array_key_exists($i, $cart_product_discount)
                ) {

                    $_pid = $cart_product_id[$i];
                    $_retail = (float)$cart_product_retail[$i];
                    $_qty = (int)$cart_product_qty[$i];
                    $_discount = (float)$cart_product_discount[$i];

                    if ($_discount == '' || $_discount == 0) {
                        $st = ($_retail * $_qty);
                    } else {
                        $t_st = ($_retail * $_qty);
                        $t_d = ($t_st * $_discount) / 100;
                        $st = $t_st - $t_d;
                        $total_discount = $total_discount + $t_d;
                    }
                    $sub_total = $sub_total + $st;

                    $data = [
                        'quote_id' => $quote->id,
                        'product_id' => $_pid,
                        'quantity' => $_qty,
                        'price' => $_retail,
                        'discount' => $_discount,

                    ];

                    $this->quoteProduct->create($data);
                }
            }
        }


        $total_price = $sub_total + $shipping_cost + $taxes_cost;

        $data = [
            'sub_total' => (float)$sub_total,
            'total_price' => $total_price,
            'discount' => $total_discount,
        ];

        $quote->update($data);


        return redirect()->route('quoteView', $quote->id)->with('message', $returnMessage);
    }

    public function quoteView($id)
    {
        $returnViewArray = array();
        $id = (int)$id;

        $quote = $this->quote->find($id);

        if (!$quote) {
            return view('messages')
                ->with('type', 'danger')
                ->with('message', 'Quote does not exist.');
        }

        $returnViewArray['id'] = $id;
        $returnViewArray['quote'] = $quote;
        $returnViewArray['products'] = $quote->products;

        return view('quotes.view', $returnViewArray);
    }

    public function quoteDelete($id){
        $id = (int)$id;

        $quote = $this->quote->find($id);

        if (!$quote) {
            return view('messages')
                ->with('type', 'danger')
                ->with('message', 'Quote does not exist.');
        }

        $quote->delete();

        return redirect()->route('quoteList')->with('message', 'Successfully removed');
    }

    public function getBaseValue(Request $request)
    {
        $product = $this->product->where('id', $request->id)->first();
        $quantity = $request->quantity;
        if ($quantity <= 0 || empty($quantity) || $quantity < $product->min_quantity) {
            $quantity = $product->min_quantity;
        }
        $basePrice = $product->price;
        $map = $product->map;
        $retail = $request->retail;
        $isChanged = $request->isChanged;
        if ($quantity > 0 && is_numeric($quantity)) {
            if ($isChanged == 1 && $retail >= $map && is_numeric($retail)) {
                $basePrice = $retail;
            } elseif ($isChanged == 1 && $retail < $map && is_numeric($retail)) {
                $basePrice = $map;
            }
            $discount = $request->discount;
            $maxAllowedDiscount = (($basePrice - $map) * 100) / $basePrice;
            if ($discount > $maxAllowedDiscount) {
                $discount = number_format((float)$maxAllowedDiscount, 2, '.', '');
            }

            return response()->json([
                'data' => $basePrice,
                'status' => 200,
                'discount' => $discount,
                'quantity' => $quantity
            ]);
        }

        return response()->json([
            'data' => $basePrice,
            'status' => 400,
            'quantity' => $quantity
        ]);
    }
}
