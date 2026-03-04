<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        // Check if PageComment model exists
        if (!class_exists('\App\Models\PageComment')) {
            return response()->json([
                'success' => false,
                'message' => 'Comments system is not available.'
            ], 500);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'page_identifier' => 'required|string|max:100',
            'component_id' => 'required|string|max:50',
            'website_id' => 'required|string',
            'author_name' => 'required|string|max:100',
            'author_email' => 'nullable|email|max:150',
            'comment' => 'required|string|max:5000',
            'is_anonymous' => 'boolean',
            'parent_id' => [
                'nullable',
                'exists:page_comments,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value) {
                        $parentComment = \App\Models\PageComment::find($value);
                        if ($parentComment && $parentComment->website_id !== $request->website_id) {
                            $fail('Invalid parent comment for this website.');
                        }
                    }
                }
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create the comment
            $comment = \App\Models\PageComment::create([
                'page_identifier' => $request->page_identifier,
                'component_id' => $request->component_id,
                'website_id' => $request->website_id,
                'author_name' => $request->author_name,
                'author_email' => $request->author_email,
                'comment' => $request->comment,
                'is_anonymous' => $request->boolean('is_anonymous', false),
                'is_approved' => true, // Auto-approve for now, can be changed based on settings
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'parent_id' => $request->parent_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment posted successfully!',
                'comment' => [
                    'id' => $comment->id,
                    'author_name' => $comment->is_anonymous ? 'Anonymous' : $comment->author_name,
                    'comment' => $comment->comment,
                    'time_ago' => $comment->created_at->diffForHumans(),
                    'created_at' => $comment->created_at
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to post comment. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_identifier' => 'required|string',
            'component_id' => 'required|string',
            'website_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $comments = PageComment::where('page_identifier', $request->page_identifier)
            ->where('component_id', $request->component_id)
            ->where('website_id', $request->website_id)
            ->approved()
            ->topLevel()
            ->with(['replies' => function($query) use ($request) {
                $query->where('website_id', $request->website_id)
                      ->where('is_approved', true);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'author_name' => $comment->author_display_name,
                    'comment' => $comment->comment,
                    'time_ago' => $comment->time_ago,
                    'created_at' => $comment->created_at,
                    'replies' => $comment->replies->map(function ($reply) {
                        return [
                            'id' => $reply->id,
                            'author_name' => $reply->author_display_name,
                            'comment' => $reply->comment,
                            'time_ago' => $reply->time_ago,
                            'created_at' => $reply->created_at,
                            'is_admin_reply' => $reply->is_admin_reply ?? false
                        ];
                    })
                ];
            })
        ]);
    }
}
