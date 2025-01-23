<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::paginate(10);
        return view('Administrator.pages.list', compact('pages'));
    }

    public function create()
    {
        return view('Administrator.pages.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
        ]);
        
        try {
            // Create a new page
            $page = new Page();
            $page->title = $request->input('title');
            $page->slug = $request->input('slug');
            $page->content = $request->input('content');
            $page->save();
            
            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Page created successfully.',
            ]);
        } catch (\Exception $e) {
            // Return error response in case of exceptions
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating the page.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    public function edit($id)
    {
        // Find the page by its ID
        $page = Page::find($id);
        
        // Check if the page exists
        if (!$page) {
            return redirect()->route('pages.index')->with('error', 'Page not found.');
        }
        
        // Return the edit view with the page details
        return view('Administrator.pages.edit', compact('page'));
    }
    
    
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $id,
            'content' => 'nullable|string',
        ]);
        
        // Find the page by its ID
        $page = Page::find($id);
        
        // Check if the page exists
        if (!$page) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found.',
            ], 404);
        }
        
        try {
            // Update the page attributes
            $page->title = $validatedData['title'];
            $page->slug = $validatedData['slug'];
            $page->content = $validatedData['content'];
            
            // Save the changes to the database
            $page->save();
            
            // Return a success response
            return response()->json([
                'status' => true,
                'message' => 'Page updated successfully.',
                'data' => $page,
            ], 200);
            
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the page.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function changeStatus(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:pages,id',
            'status' => 'required|in:active,inactive',
        ]);
        
        try {
            // Find the page by ID
            $page = Page::findOrFail($validatedData['id']);
            
            // Update the status
            $page->status = $validatedData['status'];
            $page->save();
            
            // Return a success response
            return response()->json([
                'status' => true,
                'message' => 'Page status updated successfully.',
                'new_status' => $page->status,
            ]);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the page status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    

    // In PageController.php

public function destroy($id)
{
    try {
        // Find the page by ID
        $page = Page::findOrFail($id);

        // Delete the page
        $page->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Page deleted successfully!'
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete the page.'
        ], 500);
    }
}

}
