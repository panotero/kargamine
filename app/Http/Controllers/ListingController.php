<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Listing;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\ListingPhoto;
use Illuminate\Support\Facades\Log;
use Exception;
use League\Config\Exception\ValidationException;

class ListingController extends Controller
{

    public function index()
    {
        return response()->json(Listing::latest()->get());
    }

    public function show($id)
    {

        $listing = Listing::findOrFail($id);
        return response()->json($listing);
    }


    public function update(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);

        $imagetodelete = $request->deleted_images;

        $currentImages = is_array($listing->images)
            ? $listing->images
            : (json_decode($listing->images, true) ?? []);

        $images = array_diff($currentImages, $imagetodelete);
        $listing->update([
            'property_name' => $request->property_name,
            'address'       => $request->address,
            'description'   => $request->description,
            'price'         => $request->price,
            'link'          => $request->link,
            'status'        => $request->status,
            'images'        => json_encode(array_values($images)),
        ]);

        return response()->json(['success' => true, 'message' => 'Listing updated successfully']);
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'propertyName' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'description' => 'nullable|string',
                'price' => 'nullable|string|max:100',
                'link' => 'nullable|url|max:1000',
                'status' => 'required|in:Active,Pending,Sold',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            $storedFiles = [];
            $image_list = [];

            if ($request->hasFile('images')) {
                $listingsPath = base_path('../public/listings');
                if (!file_exists($listingsPath)) {
                    mkdir($listingsPath, 0777, true);
                }

                foreach ($request->file('images') as $image) {
                    if ($image->isValid()) {
                        $filename = Str::random(12) . '_' . time() . '.' . $image->getClientOriginalExtension();
                        $image->move($listingsPath, $filename);
                        $storedFiles[] = '/listings/' . $filename;
                    }
                }
            }


            $listing = Listing::create([
                'property_name' => $request->propertyName,
                'address' => $request->address,
                'description' => $request->description,
                'price' => $request->price,
                'link' => $request->link,
                'status' => $request->status,
                'images' => $storedFiles,
            ]);

            Log::info('Listing created successfully:', ['listing' => $listing]);


            return response()->json([
                'message' => 'Listing saved successfully.',
                'inputs' => $request->all(),
                'files' => $request->file('images'),
            ]);
        } catch (ValidationException $e) {
            Log::warning('Validation failed:', ['errors' => $e->errors()]);

            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            Log::error('Error saving listing: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Failed to save listing.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy(Listing $listing)
    {
        if ($listing->images) {
            foreach ($listing->images as $img) {
                $filePath = str_replace('/storage/', 'public/', $img);
                Storage::delete($filePath);
            }
        }
        $listing->delete();

        return response()->json(['message' => 'Listing deleted successfully']);
    }
}
