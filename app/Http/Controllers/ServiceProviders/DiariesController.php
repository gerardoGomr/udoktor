<?php

namespace Udoktor\Http\Controllers\ServiceProviders;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Udoktor\Domain\Users\Repositories\UsersRepository;
use Udoktor\Http\Controllers\Controller;
use Udoktor\Http\Requests\AddScheduleRequest;

/**
 * Class DiariesController
 *
 * @package Udoktor\Http\Controllers\ServiceProviders
 * @category Controller
 * @author  Gerardo Adrián Gómez Ruiz <gerardo.gomr@gmail.com>
 */
class DiariesController extends Controller
{
    /**
     * The repository from storage
     *
     * @var UsersRepository
     */
    private $usersRepository;

    /**
     * Class constructor
     *
     * @param UsersRepository $repository
     */
    public function __construct(UsersRepository $repository)
    {
        $this->usersRepository = $repository;
    }

    /**
     * shows the view for settings of user's diary
     *
     * @return Illuminate\Support\Facades\View
     */
    public function index()
    {
        return view('service_provider.diary_schedules');
    }

    /**
     * change diary type
     *
     * @param Request $request
     *
     * @return Illuminate\Html\JsonResponse
     */
    public function changeDiaryType(Request $request)
    {
        $response = [];
        try {
            $diaryScheduleType = (int) $request->input('diaryScheduleType');
            $user              = Auth::user();

            $user->changeDiaryType($diaryScheduleType);

            $this->usersRepository->persist($user);

            $response['status'] = 'success';
            $response['html']   = view('service_provider.diary_schedules_list')->render();

        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['error']  = $e->getMessage();

        } finally {
            return response()->json($response);
        }
    }

    /**
     * modifies services lasting
     *
     * @param Request $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function modifyServicesLasting(Request $request)
    {
        $response = [];
        try {
            $this->validate($request, [
                'lasting' => 'required|numeric'
            ]);

            $servicesLasting = (int) $request->input('lasting');
            $user            = Auth::user();

            $user->modifyServicesLasting($servicesLasting);

            $this->usersRepository->persist($user);

            $response['status'] = 'success';
            $response['html']   = view('service_provider.diary_schedules_list')->render();

        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['error']  = $e->getMessage();

        } finally {
            return response()->json($response);
        }
    }

    /**
     * adds a new schedule to user
     *
     * @param AddScheduleRequest $request
     *
     * @return Illuminate\Html\JsonResponse
     *
     * @throws Exception when the schedule is invalid
     */
    public function addSchedule(AddScheduleRequest $request)
    {
        $response = [];
        try {
            $user = Auth::user();
            $user->addSchedule($request->input('start-hour'), $request->input('end-hour'), $request->input('clients-limit'));

            $this->usersRepository->persist($user);

            $response['status'] = 'success';
            $response['html']   = view('service_provider.diary_schedules_list')->render();

            return response()->json($response);

        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['error']  = $e->getMessage();

            return response()->json($response);
        }
    }
}