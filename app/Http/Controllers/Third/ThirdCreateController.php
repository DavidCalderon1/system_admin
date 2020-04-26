<?php

namespace App\Http\Controllers\Third;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ThirdRequest;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\ThirdPartiesRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\Store as SessionStore;
use Illuminate\View\View;

/**
 * Class ThirdCreateController
 * @package App\Http\Controllers\Third
 */
class ThirdCreateController extends Controller
{
    /**
     * @var ThirdPartiesRepositoryInterface
     */
    protected $thirdPartiesRepository;

    /**
     * @var CountryRepositoryInterface
     */
    protected $countryRepository;

    /**
     * @var SessionStore
     */
    protected $session;

    /**
     * ThirdCreateController constructor.
     * @param ThirdPartiesRepositoryInterface $thirdPartiesRepository
     * @param CountryRepositoryInterface $countryRepository
     * @param SessionStore $session
     */
    public function __construct(
        ThirdPartiesRepositoryInterface $thirdPartiesRepository,
        CountryRepositoryInterface $countryRepository,
        SessionStore $session
    )
    {
        $this->middleware('auth');

        $this->thirdPartiesRepository = $thirdPartiesRepository;
        $this->countryRepository = $countryRepository;
        $this->session = $session;
    }

    /**
     * @return View
     */
    public function create(): View
    {
        if (!$this->hasPermission(PermissionsConstants::THIRD_CREATE)) {
            abort(404);
        }

        return view('thirds.create');
    }

    /**
     * @param ThirdRequest $request
     * @return JsonResponse
     */
    public function store(ThirdRequest $request): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::THIRD_CREATE)) {
            return $this->response(401);
        }

        try {
            $request = $request->validated();
            $request['description'] = (!empty($request['description'])) ? $request['description'] : '';
            $request['last_name'] = (!empty($request['last_name'])) ? $request['last_name'] : '';
            $request['phone_extension'] = (!empty($request['phone_extension'])) ? $request['phone_extension'] : '';

            $country = $this->countryRepository->getCountryByCode($request['country_code']);

            $request['country_id'] = $country['id'];

            $saved = $this->thirdPartiesRepository->store($request);

            if (!$saved) {
                throw new \Exception(__('thirds.an_error_has_occurred'), 500);
            }

            $this->session->flash('message', __('thirds.save_success'));

            return $this->response(201);
        } catch (\Exception $exception) {
            return $this->response($exception->getCode(), $exception->getMessage());
        }
    }
}
