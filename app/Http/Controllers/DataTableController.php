<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\User;
use App\Models\Admin;
use App\Models\Carousel;
use App\Models\ContactMessage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DataTableController extends Controller
{
    // Products DataTable
    public function products(Request $request)
    {
        if ($request->ajax()) {
            $data = Products::query()->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('brand_name', function ($row) {
                    return $row->brand_name;
                })
                ->addColumn('article_name', function ($row) {
                    return $row->article_name;
                })
                ->addColumn('type', function ($row) {
                    return '<span class="badge bg-primary text-uppercase">' . str_replace('_', ' ', $row->type) . '</span>';
                })
                ->addColumn('size', function ($row) {
                    if (!empty($row->size) && is_array($row->size)) {
                        $sizes = array_map(function ($size) {
                            return '<span class="badge bg-secondary me-1">' . strtoupper($size) . '</span>';
                        }, $row->size);
                        return implode('', $sizes);
                    }
                    return '<span class="text-muted">N/A</span>';
                })
                ->addColumn('fabric', function ($row) {
                    return $row->fabric ? '<span class="badge bg-success">' . ucfirst($row->fabric) . '</span>' : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('gender', function ($row) {
                    return $row->gender ? '<span class="badge bg-warning text-dark">' . ucfirst($row->gender) . '</span>' : '<span class="text-muted">N/A</span>';
                })
                ->addColumn('description', function ($row) {
                    return Str::limit($row->description, 50);
                })
                ->addColumn('price', function ($row) {
                    return '$' . number_format($row->price, 2);
                })
                ->addColumn('images', function ($row) {
                    $images = is_array($row->images) ? $row->images : json_decode($row->images, true);
                    if (!empty($images)) {
                        $html = '<div class="d-flex flex-wrap justify-content-center">';
                        foreach ($images as $img) {
                            $html .= '<img src="' . asset($img) . '" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover; margin: 2px; border-radius: 4px;">';
                        }
                        $html .= '</div>';
                        return $html;
                    }
                    return '<span class="text-muted">No images</span>';
                })
                ->addColumn('actions', function ($row) {
                    return '<div class="d-flex flex-column gap-2">
                        <a href="' . route('edit.product', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        <a href="' . route('delete.product', $row->id) . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>
                    </div>';
                })
                ->rawColumns(['type', 'size', 'fabric', 'gender', 'images', 'actions'])
                ->make(true);
        }

        return view('Productable');
    }

    // Orders DataTable
    public function orders(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::query()->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', function ($row) {
                    return $row->customer_name;
                })
                ->addColumn('number', function ($row) {
                    return $row->number;
                })
                ->addColumn('customer_email', function ($row) {
                    return $row->customer_email;
                })
                ->addColumn('address', function ($row) {
                    return $row->address;
                })
                ->addColumn('total_amount', function ($row) {
                    return '$' . number_format($row->total_amount, 2);
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="/admin/orderitemtable/' . $row->id . '" class="btn btn-info btn-sm">View Items</a>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('Ordertable');
    }

    // Order Items DataTable
    public function orderItems(Request $request)
    {
        if ($request->ajax()) {
            $orderId = $request->get('order_id');
            $query = Orderitem::query();

            if ($orderId) {
                $query->where('order_id', $orderId);
            }

            $data = $query->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('order_id', function ($row) {
                    return $row->order_id;
                })
                ->addColumn('brand_name', function ($row) {
                    return $row->brand_name;
                })
                ->addColumn('article_name', function ($row) {
                    return $row->article_name;
                })
                ->addColumn('size', function ($row) {
                    return $row->size ?? 'N/A';
                })
                ->addColumn('gender', function ($row) {
                    return $row->gender ?? 'N/A';
                })
                ->addColumn('price', function ($row) {
                    return '$' . number_format($row->price, 2);
                })
                ->addColumn('quantity', function ($row) {
                    return $row->quantity;
                })
                ->addColumn('images', function ($row) {
                    $images = json_decode($row->images, true);
                    if (is_array($images) && count($images) > 0) {
                        return '<img src="' . asset($images[0]) . '" style="width: 50px; height: 50px; object-fit: cover;" class="img-thumbnail">';
                    }
                    return '<span class="text-muted">No image</span>';
                })
                ->rawColumns(['images'])
                ->make(true);
        }

        return view('Orderitemstable');
    }

    // Users DataTable
    public function users(Request $request)
    {
        if ($request->ajax()) {
            $data = User::query()->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('role', function ($row) {
                    return $row->role ?? 'user';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    return '<div class="d-flex flex-column gap-2">
                        <a href="' . route('edit.user', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>
                        <form action="' . route('delete.user', $row->id) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this user?\')" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('authtable');
    }

    // Admins DataTable
    public function admins(Request $request)
    {
        if ($request->ajax()) {
            $data = Admin::query()->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('role', function ($row) {
                    return $row->role ?? 'admin';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    return '<a href="' . route('edit.user', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('admintable');
    }

    // Contact Messages DataTable
    public function messages(Request $request)
    {
        if ($request->ajax()) {
            $data = ContactMessage::query()->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('subject', function ($row) {
                    return $row->subject;
                })
                ->addColumn('website', function ($row) {
                    return $row->website ? '<a href="' . e($row->website) . '" target="_blank">' . e($row->website) . '</a>' : '—';
                })
                ->addColumn('actions', function ($row) {
                    $viewButton = '<button type="button" class="btn btn-info btn-sm view-message-btn" data-bs-toggle="modal" data-bs-target="#messageModal" data-name="' . e($row->name) . '" data-email="' . e($row->email) . '" data-subject="' . e($row->subject) . '" data-website="' . e($row->website) . '" data-message="' . e($row->message) . '">View</button>';
                    $deleteForm = '<form action="' . route('messages.destroy', $row->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure you want to delete this message?\')">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm">Delete</button></form>';
                    return '<div class="d-flex gap-1">' . $viewButton . $deleteForm . '</div>';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : 'N/A';
                })
                ->rawColumns(['website', 'actions'])
                ->make(true);
        }

        return view('messages');
    }

    // Carousel DataTable
    public function carousel(Request $request)
    {
        if ($request->ajax()) {
            $data = Carousel::query()->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('img', function ($row) {
                    if ($row->img) {
                        return '<img src="' . asset('images/' . $row->img) . '" alt="Banner" style="width: 150px; height: 80px; object-fit: cover; border-radius: 6px;">';
                    }
                    return '<span class="text-muted">No image</span>';
                })
                ->addColumn('para', function ($row) {
                    return $row->para ?? 'N/A';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    return '<form action="' . route('delete.car', $row->id) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this banner?\')" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>';
                })
                ->rawColumns(['img', 'actions'])
                ->make(true);
        }

        return view('carousel.cartable');
    }
}