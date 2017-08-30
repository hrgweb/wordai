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
    protected $fromMon;
    protected $toSun;

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

    protected function paramsForDate() {
        $mon = request('fromMon');
        $sun = request('toSun');
        $year = request('curYear');

        $month = 0;
        $monthMon = request('curMonthMon');
        $monthSun = request('curMonthSun');
        $isSameMonth = request('monthSame');

        // check if month is not same, then use additional request data
        if ($isSameMonth === 'false') {
            $monthMon = (int) $monthMon <= 9 ? '0'.$monthMon : $monthMon;
            $monthSun = (int) $monthSun <= 9 ? '0'.$monthSun : $monthSun;
            $this->fromMon = $year . '-' . $monthMon . '-' . $mon . ' 00:00:00';
            $this->toSun = $year . '-' . $monthSun . '-' . $sun  . ' 23:59:59';

        } else {
            $month = (int) request('curMonth') <= 9 ? '0'.request('curMonth') : request('curMonth');
            $this->fromMon = $year . '-' . $month . '-' . $mon . ' 00:00:00';
            $this->toSun = $year . '-' . $month . '-' . $sun  . ' 23:59:59';
        }
    }

    public function articlesThisWeek() {
        $this->paramsForDate();

        return DB::table('words AS w')
            ->join('users AS u', 'u.id', '=', 'w.user_id')
            ->join('domains AS d', 'd.id', '=', 'w.domain_id')
            ->whereBetween('w.created_at', [$this->fromMon, $this->toSun])
            ->orderBy('w.created_at')
            ->get([
                'w.id AS word_id',
                'w.doc_title',
                'w.keyword',
                'w.article',
                'w.created_at',
                'w.isEditorEdit',
                'w.isProcess',
                'd.id AS domain_id',
                'd.domain',
                'u.id AS user_id',
                'u.firstname',
                'u.lastname'
            ]);
    }

    public function articlesCreator() {
        $this->paramsForDate();

        return DB::select("
            SELECT u.id AS user_id, CONCAT(u.firstname, ' ', u.lastname) AS full_name
            FROM `words` AS w
            JOIN users AS u
            ON u.id = w.user_id
            WHERE w.created_at BETWEEN '" . $this->fromMon . "' AND '" . $this->toSun . "'
            GROUP BY w.user_id
        ");
    }

    public function listOfArticlesCreatedByUser() {
        $this->paramsForDate();

        return Word::where('user_id', request('user_id'))
                ->whereBetween('created_at', [$this->fromMon, $this->toSun])
                ->get();
    }

    public function articlesDomain() {
        $this->paramsForDate();

        return DB::select("
            SELECT d.id AS domain_id, d.domain
            FROM `words` AS w
            JOIN domains AS d
            ON d.id = w.domain_id
            WHERE w.created_at BETWEEN '" . $this->fromMon . "' AND '" . $this->toSun . "'
            GROUP BY w.domain_id
        ");
    }

    public function listOfDomainByArticles() {
        $this->paramsForDate();

        return Word::where('domain_id', request('domain_id'))
                ->whereBetween('created_at', [$this->fromMon, $this->toSun])
                ->get();
    }
}
