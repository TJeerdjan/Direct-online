<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('image')
            ->where('client_id', session('client_id'))
            ->orderBy('sort_order')
            ->get();
        return view('client.products.index', compact('products'));
    }

    public function create()
    {
        $media = Media::where('client_id', session('client_id'))
            ->where('is_active', true)
            ->where('media_type', 'image')
            ->orderByDesc('uploaded_at')
            ->get();
        return view('client.products.create', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'sku' => 'nullable|string|max:100',
            'stock_quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'image_id' => 'nullable|integer|exists:media_DO,id',
            'new_image' => 'nullable|image|max:10240',
        ]);

        $data = $request->only(['title', 'description', 'price', 'currency', 'category', 'brand', 'sku', 'stock_quantity', 'weight', 'image_id']);
        $data['client_id'] = session('client_id');
        $data['slug'] = Str::slug($request->title);
        $data['is_available'] = true;
        $data['is_visible'] = true;
        $data['sort_order'] = Product::where('client_id', session('client_id'))->max('sort_order') + 1;

        // Handle new image upload
        if ($request->hasFile('new_image')) {
            $media = $this->uploadImage($request->file('new_image'));
            $data['image_id'] = $media->id;
        }

        Product::create($data);
        return redirect()->route('client.products.index')->with('success', 'Product toegevoegd');
    }

    public function edit(Product $product)
    {
        if ($product->client_id != session('client_id')) {
            abort(403);
        }
        $product->load('image');
        $media = Media::where('client_id', session('client_id'))
            ->where('is_active', true)
            ->where('media_type', 'image')
            ->orderByDesc('uploaded_at')
            ->get();
        return view('client.products.edit', compact('product', 'media'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->client_id != session('client_id')) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'category' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'sku' => 'nullable|string|max:100',
            'stock_quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'image_id' => 'nullable|integer',
            'is_available' => 'nullable|boolean',
            'is_visible' => 'nullable|boolean',
            'new_image' => 'nullable|image|max:10240',
        ]);

        $data = $request->only(['title', 'description', 'price', 'currency', 'category', 'brand', 'sku', 'stock_quantity', 'weight']);
        $data['slug'] = Str::slug($request->title);
        $data['is_available'] = $request->boolean('is_available');
        $data['is_visible'] = $request->boolean('is_visible');

        if ($request->hasFile('new_image')) {
            $media = $this->uploadImage($request->file('new_image'));
            $data['image_id'] = $media->id;
        } elseif ($request->filled('image_id')) {
            $data['image_id'] = $request->image_id;
        }

        $product->update($data);
        return redirect()->route('client.products.index')->with('success', 'Product bijgewerkt');
    }

    public function destroy(Product $product)
    {
        if ($product->client_id != session('client_id')) {
            abort(403);
        }
        $product->delete();
        return redirect()->route('client.products.index')->with('success', 'Product verwijderd');
    }

    private function uploadImage($file): Media
    {
        $clientId = session('client_id');
        $dir = 'uploads/' . $clientId;
        $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '.' . $file->getClientOriginalExtension();

        // Store metadata before moving the file (move invalidates the UploadedFile object)
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();

        $file->move(public_path($dir), $fileName);

        return Media::create([
            'client_id' => $clientId,
            'file_name' => $originalName,
            'file_path' => $clientId . '/' . $fileName,
            'media_type' => 'image',
            'mime_type' => $mimeType,
            'file_size' => filesize(public_path($dir . '/' . $fileName)),
            'is_active' => true,
        ]);
    }
}
