<?php

namespace App\Http\Controllers\ApiExternal;

use App\Http\Controllers\Controller;
use App\Parsers\ParserContract;
use App\Repositories\UserBalanceRepository;
use App\Repositories\UserLogRepository;
use App\Libs\Interfaces\OrmCommandInterface;
use App\Repositories\BigdateRepository;
use App\Events\Broadcasts\UserChangeCoinsBroadcast;

class ApiExternalController extends Controller
{
    public function metadata(
        ParserContract $parser, 
        UserBalanceRepository $userBalanceRepository, 
        OrmCommandInterface $commander,
        UserLogRepository $userLogRepository,
        BigdateRepository $bigdateRepository
    )
    {
        $site = request()->site;
        $user = auth()->user();
        
        $commander->setCommand($userBalanceRepository, 'subtractMeta', [
            $user,
        ]);
        
        $commander->setCommand($parser, 'parse', [
            $site
        ]);
        
        $commander->setCommand($userLogRepository, 'store', [
            $user,
            $parser,
            'Payment by metadata: '.$site,
            config('saas.price_meta')
        ]);
        
        $commander->setCommand($bigdateRepository, 'updateMeta', [
            $site,
            $parser
        ]);
        
        if (!$commander->execute()) {
            return response()->json([
                'errors' => $commander->getErrors(),
                'message' => 'An error occurred on the site',
            ], 503);
        }
        
        event(new UserChangeCoinsBroadcast($user));
        
        return response()->json([
            'data' => $parser->getLastResult()
        ]);
    }
}
