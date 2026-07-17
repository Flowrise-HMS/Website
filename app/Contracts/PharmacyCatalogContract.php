<?php

namespace Modules\Website\Contracts;

interface PharmacyCatalogContract
{
    /**
     * @return iterable<int, mixed>
     */
    public function items(): iterable;
}
