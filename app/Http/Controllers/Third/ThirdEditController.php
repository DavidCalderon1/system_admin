<?php

namespace App\Http\Controllers\Third;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ThirdRequest;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\ThirdPartiesRepositoryInterface;
use Illuminate\Session\Store as SessionStore;

/**
 * Class ThirdEditController
 * @package App\Http\Controllers\Third
 */
class ThirdEditController extends Controller
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
     * @var
     */
    protected $session;

    /**
     * ThirdEditController constructor.
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
     * @param int $thirdId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $thirdId)
    {
        if (!$this->hasPermission(PermissionsConstants::THIRD_UPDATE)) {
            abort(404);
        }

        $third = $this->thirdPartiesRepository->get($thirdId);

        return view('thirds.edit', compact('third'));
    }

    /**
     * @param ThirdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ThirdRequest $request)
    {
        if (!$this->hasPermission(PermissionsConstants::THIRD_UPDATE)) {
            return $this->response(401);
        }

        try {
            $id = $request->get('id');

            $request = $request->validated();

            $request['description'] = (!empty($request['description'])) ? $request['description'] : '';
            $request['last_name'] = (!empty($request['last_name'])) ? $request['last_name'] : '';
            $request['phone_extension'] = (!empty($request['phone_extension'])) ? $request['phone_extension'] : '';

            $country = $this->countryRepository->getCountryByCode($request['country_code']);

            $request['country_id'] = $country['id'];

            $saved = $this->thirdPartiesRepository->update($id, $request);

            if (!$saved) {
                throw new \Exception(__('thirds.an_error_has_occurred'), 500);
            }

            $this->session->flash('message', __('thirds.update_success'));

            return $this->response(201);
        } catch (\Exception $exception) {
            return $this->response($exception->getCode(), $exception->getMessage());
        }

    }
}
