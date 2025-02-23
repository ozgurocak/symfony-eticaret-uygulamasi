<?php

namespace App\Service;

use App\Repository\CategoryRepository;

class CategoryService
{
    public function __construct(private CategoryRepository $categoryRepository)
    {}

    public function getAllCategories()
    {
        return $this->categoryRepository->findAll();
    }
}