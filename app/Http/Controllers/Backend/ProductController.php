<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|string|max:50',
            'book_type' => 'required|string|max:100',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'language' => 'required|string|max:255',
            'format' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480'
        ]);

        $data = $request->except('image');
        $data['slug'] = Str::slug($request->title) . '-' . uniqid(); // Unique slug
        $data['stock'] = 1000000; // Unlimited stock
        
        do {
            $sku = 'B-' . date('Ym') . '-' . strtoupper(Str::random(4));
        } while (Product::where('sku', $sku)->exists());
        $data['sku'] = $sku;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid('book_') . '.webp';
            
            // Ensure directory exists
            $dirPath = storage_path('app/public/products');
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }
            
            $path = $dirPath . '/' . $filename;
            
            if (extension_loaded('gd')) {
                // Temporarily increase memory limit for large images
                ini_set('memory_limit', '-1');
                
                // Compress and convert to webp using native GD
                $info = getimagesize($file->getRealPath());
                $mime = $info['mime'];
                
                switch ($mime) {
                    case 'image/jpeg': $source = \imagecreatefromjpeg($file->getRealPath()); break;
                    case 'image/png': $source = \imagecreatefrompng($file->getRealPath()); break;
                    case 'image/webp': $source = \imagecreatefromwebp($file->getRealPath()); break;
                    default: $source = \imagecreatefromstring(file_get_contents($file->getRealPath())); break;
                }
                
                if ($source) {
                    $width = \imagesx($source);
                    $height = \imagesy($source);
                    
                    // Resize if wider than 800px
                    $newWidth = $width > 800 ? 800 : $width;
                    $newHeight = floor($height * ($newWidth / $width));
                    
                    $target = \imagecreatetruecolor($newWidth, $newHeight);
                    
                    // Handle transparency for PNGs
                    if ($mime == 'image/png' || $mime == 'image/webp') {
                        \imagealphablending($target, false);
                        \imagesavealpha($target, true);
                        $transparent = \imagecolorallocatealpha($target, 255, 255, 255, 127);
                        \imagefilledrectangle($target, 0, 0, $newWidth, $newHeight, $transparent);
                    }
                    
                    \imagecopyresampled($target, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    \imagewebp($target, $path, 80); // 80 is quality
                    
                    \imagedestroy($target);
                    \imagedestroy($source);
                }
                $data['image'] = 'products/' . $filename;
            } else {
                $data['image'] = $file->store('products', 'public');
            }
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', __('تم إضافة المنتج بنجاح.'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|string|max:50',
            'book_type' => 'required|string|max:100',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'language' => 'required|string|max:255',
            'format' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480'
        ]);

        $data = $request->except('image');
        $data['stock'] = 1000000; // Unlimited stock

        // Only update slug if title changed
        if ($product->title !== $request->title) {
            $data['slug'] = Str::slug($request->title) . '-' . uniqid();
        }

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $file = $request->file('image');
            $filename = uniqid('book_') . '.webp';
            
            // Ensure directory exists
            $dirPath = storage_path('app/public/products');
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }
            
            $path = $dirPath . '/' . $filename;
            
            if (extension_loaded('gd')) {
                // Temporarily increase memory limit for large images
                ini_set('memory_limit', '-1');
                
                // Compress and convert to webp using native GD
                $info = getimagesize($file->getRealPath());
                $mime = $info['mime'];
                
                switch ($mime) {
                    case 'image/jpeg': $source = \imagecreatefromjpeg($file->getRealPath()); break;
                    case 'image/png': $source = \imagecreatefrompng($file->getRealPath()); break;
                    case 'image/webp': $source = \imagecreatefromwebp($file->getRealPath()); break;
                    default: $source = \imagecreatefromstring(file_get_contents($file->getRealPath())); break;
                }
                
                if ($source) {
                    $width = \imagesx($source);
                    $height = \imagesy($source);
                    
                    // Resize if wider than 800px
                    $newWidth = $width > 800 ? 800 : $width;
                    $newHeight = floor($height * ($newWidth / $width));
                    
                    $target = \imagecreatetruecolor($newWidth, $newHeight);
                    
                    // Handle transparency for PNGs
                    if ($mime == 'image/png' || $mime == 'image/webp') {
                        \imagealphablending($target, false);
                        \imagesavealpha($target, true);
                        $transparent = \imagecolorallocatealpha($target, 255, 255, 255, 127);
                        \imagefilledrectangle($target, 0, 0, $newWidth, $newHeight, $transparent);
                    }
                    
                    \imagecopyresampled($target, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    \imagewebp($target, $path, 80); // 80 is quality
                    
                    \imagedestroy($target);
                    \imagedestroy($source);
                }
                $data['image'] = 'products/' . $filename;
            } else {
                $data['image'] = $file->store('products', 'public');
            }
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', __('تم تحديث المنتج بنجاح.'));
    }

    public function destroy(Product $product)
    {
        // order_items cascade on product delete -> would erase order history
        if (\App\Models\OrderItem::where('product_id', $product->id)->exists()) {
            return back()->with('error', __('لا يمكن حذف منتج مرتبط بطلبات سابقة.'));
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', __('تم حذف المنتج بنجاح.'));
    }
}
