<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $errors = [];
        
        // Validate Product Name
        if ($request->has('productName')) {
            $productName = htmlspecialchars(trim($request->input('productName')));
            if (!$productName) {
                $errors['productName'] = "Product Name not specified!";
            } elseif (strlen($productName) < 3) {
                $errors['productName'] = "Product Name must be at least 3 characters.";
            }
        } else {
            $errors['productName'] = "Product Name field is missing.";
        }
        
        // Validate Quantity
        if ($request->has('quantity')) {
            $quantity = htmlspecialchars(trim($request->input('quantity')));
            if ($quantity === '') {
                $errors['quantity'] = "Quantity not specified!";
            } elseif (!is_numeric($quantity) || intval($quantity) < 0) {
                $errors['quantity'] = "Quantity must be a non-negative number.";
            }
        } else {
            $errors['quantity'] = "Quantity field is missing.";
        }
        
        // Validate Price
        if ($request->has('price')) {
            $price = htmlspecialchars(trim($request->input('price')));
            if ($price === '') {
                $errors['price'] = "Price not specified!";
            } elseif (!is_numeric($price) || floatval($price) < 0) {
                $errors['price'] = "Price must be a non-negative number.";
            }
        } else {
            $errors['price'] = "Price field is missing.";
        }
        
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }
        
        // Prepare product data
        $productData = [
            'productName' => $productName,
            'quantity' => intval($quantity),
            'price' => number_format(floatval($price), 2),
            'submitted_at' => Carbon::now()->toDateTimeString(),
        ];
        
        // Path to JSON file
        $filePath = storage_path('app/products.json');
        
        // Load existing data
        if (file_exists($filePath)) {
            $existingData = json_decode(file_get_contents($filePath), true);
            if (!is_array($existingData)) {
                $existingData = [];
            }
        } else {
            $existingData = [];
        }
        
        // Append new product data
        $existingData[] = $productData;
        
        // Save back to file
        file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
        
        return response()->json([
            'message' => 'Product submitted and saved successfully!',
            'data' => $productData
        ]);
    }
    
    public function showForm()
    {
        // Load products from JSON file
        $filePath = storage_path('app/products.json');
        $products = [];
        
        if (file_exists($filePath)) {
            $products = json_decode(file_get_contents($filePath), true);
            if (!is_array($products)) {
                $products = [];
            } else {
                // Sort products by submitted_at descending (newest first)
                usort($products, function($a, $b) {
                    return strtotime($b['submitted_at']) <=> strtotime($a['submitted_at']);
                });
            }
        }
        
        // Pass to the view
        return view('products', ['products' => $products]);
    }
    
    
}



