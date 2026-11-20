<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartorder extends Model
{
    /** SKU of a custom order line: a Request a Quote with no catalogue product. */
    const CUSTOM_MODEL = 'CUSTOM';

    use HasFactory;

    public function workflow()
    {
        return $this->hasOne(\App\Models\OrderProcessingWorkflow::class, 'cartorder_id');
    }

    public function workflowSteps()
    {
        return $this->hasMany(\App\Models\OrderWorkflowStep::class, 'cartorder_id');
    }

    public function getWorkflowStep($stepNumber)
    {
        return $this->workflowSteps()->where('step_number', $stepNumber)->first();
    }

    /**
     * URL of the ordered product's main image, or null when there is no
     * image file. The product is matched by slug or model number (productid
     * is not reliable on old orders); the image saved with the order is the
     * fallback.
     */
    public function productImageUrl()
    {
        $candidates = [];

        $product = null;
        if (!empty($this->slug)) {
            $product = Product::where('slug', $this->slug)->first();
        }
        if (!$product && !empty($this->modelno)) {
            $product = Product::where('modelno', $this->modelno)->first();
        }
        if ($product) {
            $candidates[] = $product->image1;
            $candidates[] = $product->thumbnail;
        }
        $candidates[] = $this->thumbnail;

        foreach (array_filter($candidates) as $file) {
            if (preg_match('#^https?://#i', $file)) {
                return $file; // MidOcean products store full image URLs
            }
            foreach (['upload/product/', 'upload/product/thumbnail/'] as $folder) {
                if (is_file(public_path($folder . $file))) {
                    return asset($folder . rawurlencode($file));
                }
            }
        }

        return null;
    }
}
