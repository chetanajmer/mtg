<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class RejectDangerousUploads
{
    /**
     * File extensions the site is allowed to receive (images, documents,
     * videos and the Excel/CSV import files).
     */
    protected $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico',
        'pdf',
        'mp4', 'webm', 'mov', 'm4v',
        'xlsx', 'xls', 'csv',
    ];

    /**
     * Reject the request when any uploaded file is not on the allow list, so
     * nobody can upload a PHP script into a public folder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        foreach ($this->flatten($request->allFiles()) as $file) {
            if (!$this->isAllowed($file)) {
                $message = 'This file type is not allowed: ' . $file->getClientOriginalName();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['message' => $message], 422);
                }

                return redirect()->back()->with('error', $message);
            }
        }

        return $next($request);
    }

    protected function isAllowed(UploadedFile $file)
    {
        if (!$file->isValid()) {
            return true; // Let the controller report the failed upload
        }

        $name = strtolower($file->getClientOriginalName());

        // Block names like "shell.php.jpg" or "shell.phtml"
        if (preg_match('/\.(php\d*|phtml|phar|pht|phps|cgi|pl|py|sh|exe|htaccess|htm|html|js)(\.|$)/', $name)) {
            return false;
        }

        if (!in_array(strtolower($file->getClientOriginalExtension()), $this->allowedExtensions, true)) {
            return false;
        }

        // The file contents must not be PHP, whatever the extension says
        $mime = (string) $file->getMimeType();
        if (stripos($mime, 'php') !== false || stripos($mime, 'x-httpd') !== false) {
            return false;
        }

        return true;
    }

    protected function flatten(array $files)
    {
        $flat = [];

        array_walk_recursive($files, function ($file) use (&$flat) {
            if ($file instanceof UploadedFile) {
                $flat[] = $file;
            }
        });

        return $flat;
    }
}
