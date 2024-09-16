<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Service\CategoryService;

class AppExtension extends AbstractExtension
{
    public function __construct(private CategoryService $categoryService)
    {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('categories', [$this, 'getCategories'])
        ];
    }

    public function getCategories()
    {
        return $this->categoryService->getAllCategories();
    }
}