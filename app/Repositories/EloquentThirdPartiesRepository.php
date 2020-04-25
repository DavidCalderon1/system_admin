<?php

namespace App\Repositories;

use App\Models\ThirdParties;
use App\Repositories\Interfaces\ThirdPartiesRepositoryInterface;

/**
 * Class EloquentThirdPartiesRepository
 * @package App\Repositories
 */
class EloquentThirdPartiesRepository implements ThirdPartiesRepositoryInterface
{
    /**
     * @var ThirdParties
     */
    protected $thirdParties;

    /**
     * EloquentThirdPartiesRepository constructor.
     * @param ThirdParties $thirdParties
     */
    public function __construct(ThirdParties $thirdParties)
    {
        $this->thirdParties = $thirdParties;
    }
}
