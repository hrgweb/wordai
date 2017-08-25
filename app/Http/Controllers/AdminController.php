<?php

namespace App\Http\Controllers;

use App\Copyscape;
use App\Domain;
use App\DomainDetail;
use App\ProtectedTerm;
use App\User;
use App\UserLevel;
use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}

    public function postDomain()
    {
    	// validate domain to avoid duplicate entry
		$validator = Validator::make(request()->all(), [
			'domain' => 'required|unique:domains'
		]);

		if ($validator->fails()) {
			return response()->json(['isSuccess' => false, 'result' => $validator->errors()]);
		}

    	$result = auth()->user()->domains()->create(request()->all());

		return response()->json($result);
    }

    public function domainList()
    {
    	$domains = Domain::orderBy('domain', 'asc')->get();

    	return response()->json($domains);
    }

    public function domainListNotSet()
    {
    	$domains = Domain::where('isSet', 0)->orderBy('domain', 'asc')->get();

    	return response()->json($domains);
    }

    public function updateDomain()
    {
    	// validate domain to avoid duplicate entry
		$validator = Validator::make(request()->all(), [
			'domain' => 'required|unique:domains'
		]);

		if ($validator->fails()) {
			return response()->json(['isSuccess' => false, 'result' => $validator->errors()]);
		}

    	$domain = request('domain');
    	$result = DB::table('domains')->where('id', request('id'))->update(['domain' => $domain]);

    	return response()->json(['result' => $result, 'domain' => $domain]);
    }

    public function removeDomain()
    {
    	$id = request('id');
    	$result = DB::table('domains')->where('id', $id)->delete();

    	return response()->json($result);
    }

    public function postProtectedTerms()
    {
    	$terms = explode(',', request('terms'));
    	$terms = implode(',', request('terms'));

    	return $terms;

    	$result = auth()->user()->terms()->create(request()->all());

    	return response()->json($result);

    }

    public function saveDetails()
    {
    	$user_id = request('user_id');
    	$domain_id = request('detail.domain_id');
    	$protected = request('detail.protected');
    	$synonym = request('detail.synonym');
    	$protected_terms = request('protectedTerms');

    	DB::beginTransaction();
		try {
			// update domain isSet to true
			Domain::where('id', $domain_id)->update(['isSet' => 1]);

	    	// save protected terms
	    	// for ($i=0; $i < count($protected_terms); $i++) {
	    		ProtectedTerm::create([
	    			'domain_id' => $domain_id,
	    			'user_id' => auth()->user()->id,
	    			'terms' => $protected_terms // $protected_terms[$i]
	    		]);
	    	// }

	    	// save domain detail
	    	$domain = DomainDetail::create([
	    		'user_id' => $user_id,
	    		'domain_id' => $domain_id,
	    		'protected' => $protected,
	    		'synonym' => $synonym
	    	]);
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}
		DB::commit();

		return response()->json($domain);
    }

    public function domainDetails()
    {
    	return DomainDetail::with('domain')->get();
    }

    public function updateDetails()
    {
    	// return request()->all();

    	$id = request('detail.id');
    	$domain_id = request('detail.domain_id');
    	$protected = request('detail.protected');
    	$synonym = request('detail.synonym');
    	$protected_terms = request('protectedTerms');

    	DB::beginTransaction();
		try {
			// remove old protected terms
	    	ProtectedTerm::where('domain_id', $domain_id)->delete();

			// save protected terms
	    	// for ($i=0; $i < count($protected_terms); $i++) {
	    		ProtectedTerm::create([
	    			'domain_id' => $domain_id,
	    			'user_id' => auth()->user()->id,
	    			'terms' => $protected_terms // $protected_terms[$i]
	    		]);
	    	// }

	    	// update domain_details protected terms
	    	$domain = DomainDetail::where('id', $id)->update([
    			'protected' => $protected,
    			'synonym' => $synonym
    		]);
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}
		DB::commit();

    	return $domain;
    }

    public function removeDetails()
    {
    	// return request()->all();

    	$domain_id = request('domain_id');

    	DB::beginTransaction();
		try {
			// remove old protected terms
	    	ProtectedTerm::where('domain_id', $domain_id)->delete();

	    	// update domain isSet to true
			Domain::where('id', $domain_id)->update(['isSet' => 0]);

			// remove domain_details data
			$detail = DomainDetail::where('id', request('id'))->delete();
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}
		DB::commit();

    	return $detail;
    }

    public function updateCopyscapeSetting() {
    	return Copyscape::where('id', 1)->update(request()->all());
    }

    public function retrieveCopyscapeSetting() {
    	return Copyscape::first(['o', 'e', 'c', 'i', 'x']);
    }

    public function articlesThisWeek() {
        $mon = request('fromMon');
        $sun = request('toSun');
        $month = (int) request('curMonth') <= 9 ? '0'.request('curMonth') : request('curMonth');
        $year = request('curYear');

        $fromMon = $year . '-' . $month . '-' . $mon . ' 00:00:00';
        $toSun = $year . '-' . $month . '-' . $sun  . ' 23:59:59';

        return DB::table('words')
            ->whereBetween('created_at', [$fromMon, $toSun])
            ->oldest()
            ->get();
    }

    public function articlesEditedThisWeek() {
        $mon = request('fromMon');
        $sun = request('toSun');
        $month = (int) request('curMonth') <= 9 ? '0'.request('curMonth') : request('curMonth');
        $year = request('curYear');

        $fromMon = $year . '-' . $month . '-' . $mon . ' 00:00:00';
        $toSun = $year . '-' . $month . '-' . $sun  . ' 23:59:59';

        return DB::table('words')
            ->whereBetween('created_at', [$fromMon, $toSun])
            ->where('isEditorEditt', 1)
            ->oldest()
            ->get();
    }
}
