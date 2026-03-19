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

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="product-import-template.csv"',
        ];

        $columns = ['name', 'description', 'category', 'image_name'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns, ';');
            // Example rows
            fputcsv($file, ['Voorbeeld Product', 'Dit is een beschrijving van het product.', 'Categorie A', 'product-foto.jpg'], ';');
            fputcsv($file, ['Tweede Product', 'Nog een beschrijving.', 'Categorie B', 'andere-foto.png'], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function bulkImport()
    {
        return view('client.products.bulk-import');
    }

    public function bulkImportPreview(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $rows = [];
        $errors = [];

        if (($handle = fopen($path, 'r')) !== false) {
            // Detect delimiter from first line
            $firstLine = fgets($handle);
            rewind($handle);
            $delimiter = (substr_count($firstLine, ';') >= substr_count($firstLine, ',')) ? ';' : ',';

            $header = fgetcsv($handle, 0, $delimiter);
            // Strip BOM from first column
            if ($header) {
                $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);
                $header = array_map('trim', array_map('strtolower', $header));
            }

            $required = ['name'];
            foreach ($required as $col) {
                if (!in_array($col, $header)) {
                    $errors[] = "Kolom '{$col}' ontbreekt in het CSV bestand.";
                }
            }

            if (empty($errors)) {
                $lineNum = 1;
                while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                    $lineNum++;
                    if (count($data) < count($header)) {
                        $data = array_pad($data, count($header), '');
                    }
                    $row = array_combine($header, array_slice($data, 0, count($header)));

                    $name = trim($row['name'] ?? '');
                    if ($name === '') {
                        $errors[] = "Regel {$lineNum}: Product naam is leeg.";
                        continue;
                    }

                    $rows[] = [
                        'name' => $name,
                        'description' => trim($row['description'] ?? ''),
                        'category' => trim($row['category'] ?? ''),
                        'image_name' => trim($row['image_name'] ?? ''),
                    ];
                }
            }
            fclose($handle);
        }

        if (!empty($errors)) {
            return redirect()->route('client.products.bulk-import')
                ->with('error', implode(' | ', $errors));
        }

        if (empty($rows)) {
            return redirect()->route('client.products.bulk-import')
                ->with('error', 'Het CSV bestand bevat geen producten.');
        }

        // Store in session for the confirm step
        session(['bulk_import_rows' => $rows]);

        return view('client.products.bulk-import-preview', compact('rows'));
    }

    public function bulkImportStore(Request $request)
    {
        $rows = session('bulk_import_rows', []);
        if (empty($rows)) {
            return redirect()->route('client.products.bulk-import')
                ->with('error', 'Geen importdata gevonden. Upload het CSV bestand opnieuw.');
        }

        $clientId = session('client_id');
        $currentMax = Product::where('client_id', $clientId)->max('sort_order') ?? 0;
        $imported = 0;

        // Pre-load media for auto-matching by filename
        $mediaMap = Media::where('client_id', $clientId)
            ->where('is_active', true)
            ->where('media_type', 'image')
            ->get()
            ->keyBy(function ($m) {
                return strtolower($m->file_name);
            });

        foreach ($rows as $row) {
            $currentMax++;
            $imageId = null;

            // Auto-match image by filename
            $imageName = strtolower($row['image_name'] ?? '');
            if ($imageName !== '' && $mediaMap->has($imageName)) {
                $imageId = $mediaMap->get($imageName)->id;
            }

            Product::create([
                'client_id' => $clientId,
                'title' => $row['name'],
                'slug' => Str::slug($row['name']),
                'description' => $row['description'] ?: null,
                'price' => 0,
                'currency' => 'EUR',
                'category' => $row['category'] ?: null,
                'image_id' => $imageId,
                'image_name' => $row['image_name'] ?: null,
                'is_available' => true,
                'is_visible' => true,
                'sort_order' => $currentMax,
            ]);
            $imported++;
        }

        // Clean up session
        session()->forget('bulk_import_rows');

        $msg = "{$imported} producten geimporteerd.";
        $matched = collect($rows)->filter(fn($r) => $r['image_name'] !== '')->count();
        $linked = collect($rows)->filter(function ($r) use ($mediaMap) {
            return $mediaMap->has(strtolower($r['image_name'] ?? ''));
        })->count();
        if ($matched > 0 && $linked < $matched) {
            $unlinked = $matched - $linked;
            $msg .= " {$linked}/{$matched} afbeeldingen gekoppeld. {$unlinked} afbeeldingen nog niet gevonden in je media bibliotheek.";
        } elseif ($linked > 0) {
            $msg .= " Alle {$linked} afbeeldingen automatisch gekoppeld!";
        }

        return redirect()->route('client.products.index')->with('success', $msg);
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
