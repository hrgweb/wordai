<?php

namespace App\Http\Controllers;

use App\Copyscape;
use App\Domain;
use App\DomainDetail;
use App\DomainGroup;
use App\Group;
use App\ProtectedTerm;
use App\User;
use App\UserLevel;
use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    protected $fromMon;
    protected $toSun;

	public function __construct() {
		$this->middleware('auth')->except('domainList');
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
        $domain_id = request('domain_id');
    	$group_id = request('group_id');
    	$protected = request('protected');
    	$synonym = request('synonym');
        $users = request('users');

    	DB::beginTransaction();
		try {
			// update domain isSet to true
			Domain::where('id', $domain_id)->update(['isSet' => 1]);

	    	// save protected terms
    		ProtectedTerm::create([
    			'domain_id' => $domain_id,
    			'user_id' => auth()->user()->id,
    			'terms' => $protected
    		]);

	    	// save domain detail
            $userList = [];
            for ($i=0; $i < count($users); $i++) {
                $domain = DomainDetail::create([
                    'user_id' => (int) $users[$i]['id'],
                    'domain_id' => $domain_id,
                    'group_id' => $group_id,
                    'protected' => $protected,
                    'synonym' => $synonym
                ]);
                $domain['domain'] = strtolower(request('domain'));

                // push to userList array
                array_push($userList, $domain);
            }
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}
		DB::commit();

		return response()->json($userList);
    }

    public function domainDetails()
    {
        return DB::table('domain_details AS dd')
                    ->join('domains AS d', 'd.id', '=', 'dd.domain_id')
                    ->groupBy('domain_id')
                    ->select([
                        'dd.id',
                        'dd.domain_id',
                        'dd.group_id',
                        'dd.protected',
                        'dd.synonym',
                        'dd.created_at',
                        'd.domain'
                    ])
                    ->orderBy('d.domain')
                    ->get();

    	// return DomainDetail::with('domain')->get();
    }

    public function updateDetails()
    {
        // delete pt, details record
        // save tp, details

    	$id = request('id');
        $domain_id = request('domain_id');
        $group_id = request('group_id');
        $protected = request('protected');
        $synonym = request('synonym');
        $users = request('users');

        // return request()->all();

    	DB::beginTransaction();
		try {
			// remove old protected terms
	    	ProtectedTerm::where('domain_id', $domain_id)->delete();

			// save protected terms
	    	// for ($i=0; $i < count($protected_terms); $i++) {
	    		ProtectedTerm::create([
	    			'domain_id' => $domain_id,
	    			'user_id' => auth()->user()->id,
	    			'terms' => $protected
	    		]);
	    	// }

	    	// delete old domain details by domain_id
	    	DomainDetail::where('domain_id', $domain_id)->delete();

            // save new domain details
            $userList = [];
            for ($i=0; $i < count($users); $i++) {
                $domain = DomainDetail::create([
                    'user_id' => (int) $users[$i]['id'],
                    'domain_id' => $domain_id,
                    'group_id' => $group_id,
                    'protected' => $protected,
                    'synonym' => $synonym
                ]);
                $domain['domain'] = strtolower(request('domain'));

                // push to userList array
                array_push($userList, $domain);
            }
		} catch (ValidationException $e) {
			DB::rollback();
			throw $e;
		}
		DB::commit();

        // get only the first result since we avoid duplicates
        return response()->json($domain);
    }

    public function removeDetails()
    {
    	// return request()->all();

        $domain_id = request('domain_id');
    	$group_id = request('group_id');

    	DB::beginTransaction();
		try {
			// remove old protected terms
	    	ProtectedTerm::where('domain_id', $domain_id)->delete();

	    	// update domain isSet to false
			Domain::where('id', $domain_id)->update(['isSet' => 0]);

			// remove domain_details data
			$detail = DomainDetail::where(['domain_id' => request('domain_id'), 'group_id' => request('group_id')])->delete();
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
            $this->fromMon = $year . '-' . $monthMon . '-' . $mon . ' 00:00:01';
            $this->toSun = $year . '-' . $monthSun . '-' . $sun  . ' 23:59:59';

        } else {
            $month = (int) request('curMonth') <= 9 ? '0'.request('curMonth') : request('curMonth');
            $this->fromMon = $year . '-' . $month . '-' . $mon . ' 00:00:01';
            $this->toSun = $year . '-' . $month . '-' . $sun  . ' 23:59:59';
        }
    }

    protected function columnsNeedForArticle()
    {
        return [
            'w.id AS word_id',
            'u.firstname',
            'u.lastname',
            // 'article_types.article_type',
            'd.domain',
            'doc_title',
            'keyword',
            'lsi_terms',
            'domain_protected',
            'article',
            'spintax',
            'spintax_copy',
            'spin',
            'protected',
            'synonym',
            'isEditorEdit',
            'isEditorUpdateSC',
            'isCsCheckHitMax',
            'isRespinHitMax',
            'isProcess',
            'hr_spent_editor_edit_article',
            'min_spent_editor_edit_article',
            'sec_spent_editor_edit_article',
            'w.created_at'
        ];
    }

    public function articlesThisWeek() {
        $this->paramsForDate();

        return DB::table('words AS w')
            ->leftJoin('users AS u', 'u.id', '=', 'w.user_id')
            ->leftJoin('domains AS d', 'd.id', '=', 'w.domain_id')
            ->where('isProcess', 1)
            ->whereBetween('w.created_at', [$this->fromMon, $this->toSun])
            ->orderBy('w.created_at')
            ->get($this->columnsNeedForArticle());
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
            ORDER BY full_name ASC
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
            ORDER BY d.domain ASC
        ");
    }

    public function listOfDomainByArticles() {
        $this->paramsForDate();

        return Word::where('domain_id', request('domain_id'))
                ->whereBetween('created_at', [$this->fromMon, $this->toSun])
                ->get();
    }

    public function groupList() {
        return Group::all(['id', 'group']);
    }

    public function newGroup() {
        $users = request('value');
        $counter = 0;

        for ($i=0; $i < count($users); $i++) {
            $data = [
                'group_id' => request('group_id'),
                'user_id' => $users[$i]['id']
            ];

            // post data
            DomainGroup::create($data);

            // increment counter
            $counter++;
        }

        return $counter === count($users)
            ? response()->json(['result' => true])
            : response()->json(['result' => false]);
    }

    public function getUsersAssociatedByDomain() {
        return DB::table('users AS u')
                    ->join('domain_group AS dg', 'dg.user_id', '=', 'u.id')
                    ->where('dg.group_id', request('group_id'))
                    ->get([
                        'u.id AS user_id',
                        'u.firstname',
                        'u.lastname'
                    ]);
    }

    public function updateGroup() {
        $users = request('value');
        $counter = 0;

        DB::beginTransaction();
        try {
            // delete old users
            DomainGroup::where('group_id', request('group_id'))->delete();

            // update users
            for ($i=0; $i < count($users); $i++) {
                $data = [
                    'group_id' => request('group_id'),
                    'user_id' => $users[$i]['id']
                ];

                // post data
                DomainGroup::create($data);

                // increment counter
                $counter++;
            }
        } catch (ValidationException $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();

        return $counter === count($users)
            ? response()->json(['result' => true])
            : response()->json(['result' => false]);
    }

    public function addGroup() {
        return Group::create(['group' => request('name')]);
    }

    public function updateCreateGroup() {
        return Group::where('id', request('group_id'))->update(['group' => request('name')]);
    }

    public function removeCreateGroup() {
        return Group::where('id', request('group_id'))->delete();
    }

    public function allArticles() {
        return DB::table('words')
            ->leftJoin('users', 'users.id', '=', 'words.user_id')
            ->leftJoin('article_types', 'article_types.id', '=', 'words.article_type_id')
            ->leftJoin('domains', 'domains.id', '=', 'words.domain_id')
            ->select(
                'words.id',
                'users.firstname',
                'users.lastname',
                'article_types.article_type',
                'domains.domain',
                'doc_title',
                'keyword',
                'lsi_terms',
                'domain_protected',
                'article',
                'spintax',
                'spintax_copy',
                'spin',
                'protected',
                'synonym',
                'isEditorEdit',
                'isEditorUpdateSC',
                'isCsCheckHitMax',
                'isRespinHitMax',
                'hr_spent_editor_edit_article',
                'min_spent_editor_edit_article',
                'sec_spent_editor_edit_article',
                'words.created_at'
            )
            ->orderBy('users.firstname')
            ->paginate(20);
    }

    public function searchArticle() {
        // return Word::where('doc_title', 'LIKE', '%'.request('search').'%')->get();

        return DB::table('words')
            ->leftJoin('users', 'users.id', '=', 'words.user_id')
            ->leftJoin('article_types', 'article_types.id', '=', 'words.article_type_id')
            ->leftJoin('domains', 'domains.id', '=', 'words.domain_id')
            ->select(
                'words.id',
                'users.firstname',
                'users.lastname',
                'article_types.article_type',
                'domains.domain',
                'doc_title',
                'keyword',
                'lsi_terms',
                'domain_protected',
                'article',
                'spintax',
                'spintax_copy',
                'spin',
                'protected',
                'synonym',
                'isEditorEdit',
                'isEditorUpdateSC',
                'isCsCheckHitMax',
                'isRespinHitMax',
                'hr_spent_editor_edit_article',
                'min_spent_editor_edit_article',
                'sec_spent_editor_edit_article',
                'words.created_at'
            )
            ->where('words.doc_title', 'LIKE', '%'.request('search').'%')
            ->orderBy('users.firstname')
            ->get();
    }

    public function editDetails()
    {
        $result = [];

        $detail = DB::table('domain_details AS dd')
                    ->leftJoin('domains AS d', 'd.id', '=', 'dd.domain_id')
                    ->leftJoin('groups AS g', 'g.id', '=', 'dd.group_id')
                    ->select([
                        'dd.id',
                        'dd.domain_id',
                        'dd.group_id',
                        'dd.user_id',
                        'dd.protected',
                        'dd.synonym',
                        'dd.created_at',
                        'd.domain',
                        'g.group',
                        'g.id AS group_id'
                    ])
                    ->where('dd.id', request('detail_id'))
                    ->first();

        // push result to array
        array_push($result, $detail);

        $domain_id = (int) $detail->domain_id;
        $group_id = (int) $detail->group_id;

        // list of users id
        $users_id = DomainDetail::where(['domain_id' => $domain_id, 'group_id' => $group_id])->pluck('user_id');

        // list of users
        $users = User::whereIn('id', $users_id)
                    ->get(['id AS user_id', 'firstname', 'lastname']);

        // push users to array
        array_push($result, $users);

        return response()->json(['detail' => $result[0], 'users' => $result[1]]);
    }

    public function searchArticlesByRange()
    {
        $from = request('from') . ' 00:00:00';
        $to = request('to') . ' 23:59:59';

        return DB::table('words AS w')
            ->leftJoin('users AS u', 'u.id', '=', 'w.user_id')
            ->leftJoin('domains AS d', 'd.id', '=', 'w.domain_id')
            ->whereBetween('w.created_at', [$from, $to])
            ->orderBy('w.created_at')
            ->get($this->columnsNeedForArticle());
    }

    public function searchByUser()
    {
        $from = request('from') . ' 00:00:00';
        $to = request('to') . ' 23:59:59';

        // get the id base on name of user
        $user_id = User::whereRaw("CONCAT(firstname, ' ', lastname) LIKE '%" . request('input') . "%'")->first(['id']);

        // if has result
        if ($user_id) {
            $user_id = $user_id->id;

            // get articles for this user
            return DB::table('words AS w')
                ->leftJoin('users AS u', 'u.id', '=', 'w.user_id')
                ->leftJoin('domains AS d', 'd.id', '=', 'w.domain_id')
                ->where('w.user_id', $user_id)
                ->whereBetween('w.created_at', [$from, $to])
                ->orderBy('w.created_at')
                ->get($this->columnsNeedForArticle());
        }
    }

    public function searchByGroup()
    {
        $from = request('from') . ' 00:00:00';
        $to = request('to') . ' 23:59:59';

        // get the id base on name of user
        $group_id = Group::where('group', request('input'))->first(['id']);

        // if has result
        if ($group_id) {
            $group_id = $group_id->id;

            // get articles for this user
            return DB::table('words AS w')
                ->leftJoin('users AS u', 'u.id', '=', 'w.user_id')
                ->leftJoin('domains AS d', 'd.id', '=', 'w.domain_id')
                ->where('w.group_id', $group_id)
                ->whereBetween('w.created_at', [$from, $to])
                ->orderBy('w.created_at')
                ->get($this->columnsNeedForArticle());
        }
    }

    public function searchByWebsite()
    {
        $from = request('from') . ' 00:00:00';
        $to = request('to') . ' 23:59:59';

        // get the id base on name of user
        $domain_id = Domain::where('domain', request('input'))->first(['id']);

        // if has result
        if ($domain_id) {
            $domain_id = $domain_id->id;

            // get articles for this user
            return DB::table('words AS w')
                ->leftJoin('users AS u', 'u.id', '=', 'w.user_id')
                ->leftJoin('domains AS d', 'd.id', '=', 'w.domain_id')
                ->where('w.domain_id', $domain_id)
                ->whereBetween('w.created_at', [$from, $to])
                ->orderBy('w.created_at')
                ->get($this->columnsNeedForArticle());
        }
    }

    public function downloadArticle()
    {
        // $article = request('spin');
        // $spintax = request('spintax');

        $filename = request('filename');
        $data = [
            'article_'.$filename => request('spin'),
            'spintax_'.$filename => request('spintax')
        ];
        // $folderPath = 'public/downloads/';
        // $path = $folderPath . $filename . '.csv';

        // check if folder not exist then create
        /*if (! File::exists(storage_path('app/'.$folderPath))) {
            Storage::makeDirectory($folderPath);
        }*/

        // store the file to disk
        // Storage::put($path, $article);

        // header('Content-type: text/csv; charset=utf-8');
        // header('Content-Disposition: attachment; filename='.$key);

        $results = [];
        foreach ($data as $key => $value) {

            array_push($results, $value);
        }

        /*$headers = [
            'Content-type' => 'text/csv'
        ];

        return response()->download(storage_path($path, $filename, $headers));*/
    }

    public function toApproveArticles()
    {
        return DB::table('words AS w')
                ->leftJoin('users AS u', 'u.id', '=', 'w.user_id')
                ->leftJoin('domains AS d', 'd.id', '=', 'w.domain_id')
                ->select($this->columnsNeedForArticle())
                ->where('w.isArticleApprove', 0)
                ->orderBy('w.created_at')
                ->paginate(20);
    }

    public function approveArticle()
    {
        return Word::where('id', request('id'))->update([
            'isArticleApprove' => 1,
            'reasonArticleNotAprrove' => null,
            'reasonArticleNotAprroveBody' => null
        ]);
    }

    public function disApproveArticle()
    {
        return Word::where('id', request('word_id'))->update([
            'isArticleApprove' => 0,
            'reasonArticleNotAprrove' => request('approveType'),
            'reasonArticleNotAprroveBody' => request('comment')
        ]);
    }
}
