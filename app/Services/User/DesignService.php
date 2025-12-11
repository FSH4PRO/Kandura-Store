<?php

namespace App\Services\User;

use App\Models\Design;
use App\Models\Customer;
use App\Models\DesignOptionSelection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DesignService
{

    public function listForUser(Customer $customer, array $filters = []): LengthAwarePaginator
    {
        $query = Design::query()
            ->with(['sizes', 'options', 'customer.user']);

        $mode = $filters['mode'] ?? 'my';

        if ($mode === 'my') {
            $query->ownedByCustomer($customer);
        } elseif ($mode === 'browse') {
            $query->where('customer_id', '!=', $customer->id);
        }

        $query
            ->search($filters['search'] ?? null)
            ->filterSize($filters['size_id'] ?? null)
            ->filterPrice($filters['price_min'] ?? null, $filters['price_max'] ?? null)
            ->filterOption($filters['option_id'] ?? null);

        if (! empty($filters['creator_id'])) {
            $query->where('customer_id', $filters['creator_id']);
        }

        $sortBy  = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        $query->orderBy($sortBy, $sortDir);

        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        return $query->paginate($perPage)->withQueryString();
    }


    public function create(array $data, Customer $customer): Design
    {
        return DB::transaction(function () use ($data, $customer) {

            $design = Design::create([
                'customer_id' => $customer->id,
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'price'       => $data['price'],
            ]);


            $design->sizes()->sync($data['size_ids']);


            if (! empty($data['design_options'])) {
                $this->syncDesignOptions($design, $data['design_options']);
            }


            if (! empty($data['images'])) {
                foreach ($data['images'] as $file) {
                    $design->addMedia($file)->toMediaCollection('images');
                }
            }

            return $design->load(['sizes', 'options', 'customer.user']);
        });
    }


    public function update(Design $design, array $data): Design
    {
        return DB::transaction(function () use ($design, $data) {

            if (isset($data['name'])) {
                $design->name = $data['name'];
            }

            if (isset($data['description'])) {
                $design->description = $data['description'];
            }

            if (isset($data['price'])) {
                $design->price = $data['price'];
            }

            $design->save();

            if (isset($data['size_ids'])) {
                $design->sizes()->sync($data['size_ids']);
            }


            if (array_key_exists('design_options', $data)) {
                $this->syncDesignOptions($design, $data['design_options'] ?? []);
            }

            if (! empty($data['images']) && is_array($data['images'])) {
                $design->clearMediaCollection('images');
                foreach ($data['images'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $design->addMedia($file)->toMediaCollection('images');
                    }
                }
            }

            return $design->fresh()->load(['sizes', 'options', 'customer.user']);
        });
    }

    public function delete(Design $design): void
    {
        DB::transaction(function () use ($design) {

            $design->clearMediaCollection('images');
            $design->delete();
        });
    }


    protected function syncDesignOptions(Design $design, array $options): void
    {
        
        $design->optionSelections()->delete();

        if (empty($options)) {
            return;
        }

        $rows = collect($options)
            ->filter(fn($opt) => ! empty($opt['id']))
            ->map(function ($opt) use ($design) {

                return [
                    'design_id'        => $design->id,
                    'design_option_id' => $opt['id'],

                    'value' => isset($opt['value'])
                        ? json_encode($opt['value'], JSON_UNESCAPED_UNICODE)
                        : null,

                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            })
            ->all();

        if (! empty($rows)) {
            DesignOptionSelection::insert($rows);
        }
    }
}
