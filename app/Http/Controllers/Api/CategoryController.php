<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\Helper;
use File;

use App\Models\Category;
use Storage;

class CategoryController extends Controller
{
    /**
 * Creates a new category based on the provided request data.
 *
 * @param CategoryCreateRequest $request The request object containing the category data.
 * @return \Illuminate\Http\JsonResponse A JSON response with the created category data.
 */
public function create_category(CategoryCreateRequest $request)
{
    // Instantiate a new Category model.
    $category = new Category();
    // Set the name of the category from the request.
    $category->name = $request->name;

    // Check if an image file is uploaded with the request.
    if ($request->hasFile('image')) {
        // Generate a unique file name for the uploaded image.
        $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
        // Store the uploaded image in the 'categories' directory within the 'public' disk.
        $filePath = $request->file('image')->storeAs('categories', $fileName, 'public');
        // Get the URL of the stored image.
        $fileUrl = Storage::url($filePath);
        // Set the image path and URL for the category.
        $category->image = $filePath;
        $category->image_URL = $fileUrl;
    } else {
        // If no image is uploaded, set default values for image path and URL.
        $category->image = 'not uploaded';
        $category->image_URL = 'not uploaded';
    }

    // Generate a unique category ID using a helper function.
    $category->category_Id = Helper::Generator(new Category, 'category_Id', 4, 'Category');

    // Save the category data to the database.
    $category->save();

    // Return a JSON response with the created category data.
    return response()->json([
        'massage' => 'Category created successfully',
        'data' => $category
    ],201);
}

   /**
 * Updates an existing category with new data.
 *
 * @param CategoryCreateRequest $request The request object containing the new data for the category.
 * @return \Illuminate\Http\JsonResponse A JSON response with a success message and updated category data or an error message.
 */
public function update_category(CategoryCreateRequest $request)
{
    try {
        // Find the category by ID or throw a ModelNotFoundException if not found.
        $find_category = Category::findOrFail($request->id);

        // Update the category name if provided in the request.
        $find_category->name = $request->name ?? $find_category->name;

        // Check if a new image file is uploaded with the request.
        if ($request->hasFile('image')) {
            // If the category already has an image, delete the existing image from storage.
            if ($find_category->image) {
                Storage::disk('public')->delete($find_category->image);
            }

            // Generate a new file name for the uploaded image.
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            // Store the new image in the 'categories' directory within the 'public' disk.
            $filePath = $request->file('image')->storeAs('categories', $fileName, 'public');
            // Get the URL of the stored image.
            $fileUrl = Storage::url($filePath);
            // Update the category's image path and URL.
            $find_category->image = $filePath;
            $find_category->image_URL = $fileUrl;
        }

        // Save the updated category data to the database.
        $find_category->save();

        // Return a JSON response with a success message and the updated category data.
        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $find_category
        ], 201);
    } catch (ModelNotFoundException $exception) {
        // Return a JSON response with an error message if the category is not found.
        return response()->json([
            'error' => 'Category not found',
        ], 404);
    }
}

}
