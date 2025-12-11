<?php

namespace App\Services\Admin;

use App\Models\DesignOption;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DesignOptionService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $query = DesignOption::query();

       
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $locale = app()->getLocale();

            $query->where("name->{$locale}", 'like', "%{$search}%");
        }

        
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

       
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            if ($filters['is_active'] === '1') {
                $query->where('is_active', true);
            } elseif ($filters['is_active'] === '0') {
                $query->where('is_active', false);
            }
        }

        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 15;
        }

        return $query->orderByDesc('id')->paginate($perPage)->withQueryString();
    }

    public function create(array $data): DesignOption
    {
        $option = new DesignOption();

        $this->fillTranslatableName($option, $data);

        $option->type      = $data['type'];
        $option->is_active = ! empty($data['is_active']);

        $option->save();

        return $option;
    }

    public function update(DesignOption $option, array $data): DesignOption
    {
       
        if (isset($data['name'])) {
            $this->fillTranslatableName($option, $data);
        }

        
        if (isset($data['type'])) {
            $option->type = $data['type'];
        }

       
        if (array_key_exists('is_active', $data)) {
            $option->is_active = ! empty($data['is_active']);
        }

        $option->save();

        return $option->fresh();
    }

    public function delete(DesignOption $option): void
    {
        $option->delete();
    }

    
    protected function fillTranslatableName(DesignOption $option, array $data): void
    {
       
        $currentEn = $option->getTranslation('name', 'en', false) ?? '';
        $currentAr = $option->getTranslation('name', 'ar', false) ?? '';

        $name = $data['name'] ?? null;

        $en = $currentEn;
        $ar = $currentAr;

       
        if (is_array($name)) {
            $en = $name['en'] ?? $currentEn;
            $ar = $name['ar'] ?? $currentAr;
        } else {
            
            if (isset($data['name_en'])) {
                $en = $data['name_en'];
            }
            if (isset($data['name_ar'])) {
                $ar = $data['name_ar'];
            }
        }

        
        $translations = [
            'en' => $en,
            'ar' => $ar ?: $en, 
        ];

        $option->setTranslations('name', $translations);
    }
}
