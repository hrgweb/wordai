<?php

namespace App\Http\Controllers;

use App\Article;
use App\Repositories\Spintax;
use App\Repositories\WordRepository;
use App\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WordsController extends Controller
{
	private $word;
	private $spin;

	function __construct(WordRepository $word, Spintax $spin)
	{
		$this->middleware('auth');
		$this->word = $word;
		$this->spin = $spin;
	}

	public function test()
	{
		$pizza  = "
			piece1 


			piece2 


			piece3 
			piece4 piece5 piece6
		";
		/*$pizza = "
			How To Apply For Social Security Disability


			The first step in how to apply for social security disability is to contact the Social Security Administration to schedule your disability interview.  You may contact your local Social Security office by phone, or make an office visit, or you could call the toll free Social Security number to have a disability claim scheduled or taken for you in your regional office. 

			For people who are unclear about the differences between SSD and SSI, Social Security administers two disability programs -- Social Security disability (SSDI) and Supplemental Security Income (SSI).  Social Security disability is based upon guaranteed status, which is achieved through your work task.  Supplemental Security Income is a need-based program that does not depend upon your work history.  SSI depends upon your income or resources. 

			Where can I go to apply?

			Applying for impairment in person 

			An in person interview in your local Social Security office has become the most dependable process to submit an application for disability with Social Security. 

			The claims representative may assess your eligibility for Social Security Disability and Supplemental Security Income (SSI) disability in the time of the disability interview.  They'll finish all the essential disability forms for you and you'll be able to sign your medical release form in this interview as well.  

			This means that the disability claim will be prepared to be mailed to your state disability bureau the same day (the agency is known in most countries as DDS, or disability determination services) with no waiting on the email or to get a 're-contact' from Social Security to finish the required disability forms. 

			Applying for disability over the phone 

			If you want to do a telephone disability application, you should contact your regional Social Security office to set up an appointment to get a Social Security and/or an SSI disability claim (ora few individuals will possess concurrent claims, meaning that claims for both SSD and SSI simultaneously). 

			In such cases, a claims representative will complete your disability program in addition to the necessary disability medical forms.  They will mail you a medical release form (kind SSA-847) to signal together with a self addressed postage-paid envelop for the return of the medical release form. 

			Applying for disability online 

			If you want to apply to your own handicap on the internet, you may only file for Social Security disability in this time, not SSI.  The online impairment applications process requires that a claims representative contact you to finish an SSI disability program if you would like to file.  To put it differently, an SSI claim isn't regarded as officially accepted without the prospective claimant being contacted; whereas an SSD application could be initiated on the web by the claimant entirely on their own.  If you file on the web, make sure to finish your 'disability medical report' online also.  Unfortunately, you will need to print, sign, and email a medical release form to your regional Social Security office to be able to finish your disability application. 

			Currently, the medical release form is one of the few forms that still expect a pen and ink signature to be legitimate.  The cause of this is that medical release forms have been used to acquire medical information that is covered by stringent privacy laws. 

			Currently, it's likely better for you to make an application for disability by telephone or do an in-person impairment interview, because it allows Social Security to get all the necessary information at the time of your disability interview. 

			As you still have to mail in a medical release form if you file by telephone, it is still much more convenient than waiting for Social Security to re-contact you for an SSI program and in some cases to get your medical information. 

			Whether you file online or by telephone, it's very important that you return your signed medical release form within thirty days of filing your disability claim.  If Social Security does not receive your medical release form, your disability claim might be denied for failure to cooperate.  This means your disability claim will never be sent to a disability examiner for a medical disability decision (meaning your documents won't be gathered and evaluated). 

			How to Apply for Disability - What medical conditions can you apply for?

			What are a few of the conditions which individuals file disability claims for?  Regarding physical impairments, disability applications often cite these issues: osteoporosis and other forms of arthritis (such as rheumatoid arthritis), heart problems, higher blood pressure, diabetes, diabetic neuropathy, MS, irritable bowel syndrome, crohn's disease, peripheral artery disease, various types of cancer, eye issues, ear problems, epilepsy, asthma, COPD, reflex sympathetic dystrophy, muscular dystrophy, and meniere's disease. 

			Regarding emotional impairments (psychiatric and psychological), disability applications often include these problems: bipolar disorder, major depression, schizophrenia, low IQ, personality disorder, anxiety disorder, panic attacks, autism, asperger's, and mental retardation.

			When should you submit a claim for disability benefits with SSA? 

			In many cases, the individual is unsure if their situation is severe enough or has lasted long enough to meet the SSA disability demands. 

			In other instances, the individual hasn't stopped working yet but is concerned that their condition will worsen and also force a halt to work activity.  Or, they may have already reduced their time on the job and are uncertain if their existing level of work or sales will allow them to submit a claim and receive benefits. 

			In the case of applications filed for kids, the issue might have to do with how a kid even qualifies for disability, what advantages are provided, how long those advantages will continue, and what kinds of evidence will be necessary to approve the claim (questions, clearly, that apply equally to adult claims). 

			For an adult, or an adult filing on behalf of a disabled child, the question of 'when to file' can be addressed by the next questions: 

			1) Is the individual's condition severe, meaning does it have a considerable impact on the individual's ability to engage in regular activities of everyday living?  Determining whether a person's condition is acute or non-severe, of course, is a subjective assessment. 

			However, a report on the claimant's medical evidence as well as questioning their ability to take part in activities of daily living (usually, ADL information is obtained by a disability examiner via a telephone interview or with the claimant fill out and return an ADL questionaire) will generally permit this to be rapidly ascertained. 

			Some disability claims are denied on the grounds of the NSI, or non-severe disability.  Most maintains, however, are determined to involve one or even more acute impairments, thus permitting the claim to be completely evaluated. 

			2) Has the individual's condition lasted for a full year?  For both Social Security Disability and SSI, 1 calendar year is the benchmark for determining whether or not a individual is disabled. 

			If the person's condition is disabling (meaning an adult is unable to take part in work activity, or even a child is unable to take part in age-appropriate actions) but neglects to continue for an whole year, their case will not fulfill the Social Security Administration's definition of disability and their claim will be denied on the grounds of duration, duration being defined as the failure to satisfy the one-year minimum length requirement. 

			3) If the person's condition hasn't lasted for a complete year 'however', is it, based on a review of their medical and non-medical proof (such as work history, or, in the case of kids, school documents), be projected to last for the minimal length of one calendar year? 

			If so--and this type of projection is routinely made by disability examiners and administrative law judges-- the claimant may potentially get a Social Security Disability award, an SSI disability award, or even an awarding of concurrent benefits involving both programs. 

			4.  If the person's condition (which might involve one or more physical or mental impairments) is judged to be acute, and has lasted for at least 12 months period, has it also prevented the individual, if they're a kid applicant, from engaging in normal daily activities that are suitable to their age, like grade-level school function. 

			Obviously, to get a disability examiner or disability judge to make a medical-vocational decision of the kind that results in a disability approval, it will be exceedingly important for the claimant to supply a precise and comprehensive work history when the disability claim is filed.  This will enable the adjudicator to correctly recognize the claimant's past jobs and work skills, and, in so doing, render a more precise decision. 

			How do you help make your disability interview go smoothly? 

			The most important tip is that you ought to be prepared.  In-person disability interviews may take ninety minutes or longer depending on how ready a person is.  Now you should have the ability to answer questions with regard to these: 

			1.  You should have documentation of your arrival, citizenship or alien status. 

			2.  Your work history; in different words, the kinds of jobs that you've had prior to becoming disabled.  For more information on how SSA views your work history: What can Social Security Disability Need to understand about your Work History and Jobs? 

			3.  The names, addresses, phone numbers, and treatment dates of all of the physicians, clinics, and hospitals that have treated you during your illness.  To learn more on how SSA utilizes your medical records at the decision-making procedure: Social Security Disability, Medical Records, along with a individual's Limitations . 

			4.  Private information regarding yourself like your own marriages, divorces--depending upon the type of benefit you're filing for--and kids (minors or adult disabled children). 

			5.  The claims representative will also ask questions regarding your resources (bank accounts, life insurance policies, land, trust funds, stocks, bonds, IRAís, 401Ks, or money) along with your income (salary, short term disability or long disability benefits, veterans benefits, or another source of income), so as to assess your potential entitlement to Supplemental Security Income. 

			If you've got your information and documentation prepared at the time you submit an application for disability with the Social Security Administration, you can make the practice of filing for disability less stressful by following these guidelines in how to apply for social security disability.


			*****************************

			http://www.ssdrc.com/disabilityquestions1-12.html
			http://www.ssdrc.com/disabilityquestions4-1.html
			http://www.ssdrc.com/disabilityquestions1-17.html
			http://www.ssdrc.com/disabilityquestions4-18.html

			Julius Matucinos  - 8:05 - 8:30 6/20/2017
		";*/
		// $pieces = explode(PHP_EOL, $pizza);
		// $pieces = preg_split("/\\r\\n|\\r|\\n/", $pizza);
		$pieces = preg_split("/\\n\\n\\n/", $pizza);
		// $pieces = preg_split("/\\r/", $pizza);


		dd($pieces);
		// var_dump($pieces);
		// return $pieces;
	}

	private function generateSpintax(array $request, $articleOrParagraph)
	{
		// dd($request['article']);

		$word = $this->word;

		$request['keyword'] = $word->remove_underline_and_spaces_for_terms(request('keyword'));
		$request['lsi_terms'] = $word->remove_underline_and_spaces_for_terms(request('lsi_terms'));
		$request['domain_protected'] = $word->remove_underline_and_spaces_for_terms(request('domain_protected'));
		$request['protected'] = $word->remove_underline_and_spaces_for_terms(request('protected'));

		$terms_protected =  $request['keyword'] . ',' . $request['lsi_terms'] . ',' . $request['domain_protected'] . ',' . $request['protected'];
		$terms_protected = $word->remove_underline_and_spaces_for_terms($terms_protected);

		// api - vars
		// $text = stripslashes($request['article']);
		$quality = 100;
		$email = 'accounting@connexionsolutions.com';
		$pass = 'fastredsportscar';
		$protected = $request['protected'];
		$synonyms = $request['synonyms'];

		$quality = 'readable';
		$nonested = 'off';
		$sentence = 'on';
		$paragraph = 'on';
		$title = 'off';
		$nooriginal = 'on';

		// $paragraphs = $word->split_article_into_paragraph($request->article);

		$result = $this->api(
			$articleOrParagraph,
			$quality, 
			$email, 
			$pass, 
			$terms_protected, 
			$synonyms, 
			$nonested, 
			$sentence, 
			$paragraph, 
			$title, 
			$nooriginal
		);

    	return $result;
	}

	public function generateParagraph()
	{
		$paragraphs = request('paragraphs');
		$result = [];

		for ($i=0; $i < count($paragraphs); $i++) { 
			array_push($result, $this->spin->process($paragraphs[$i]));
		}

		return response()->json($result);
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'doc_title' => 'required', 
	    	'keyword' => 'required',
	    	'lsi_terms' => 'required',
	    	// 'domain_protected' => 'required',
	    	'article' => 'required',
	    	'dom_name' => 'required', 
	    	'protected' => 'required',
	    	// 'synonyms' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['isError' => true, 'errors' => $validator->errors()]);
        }

        return $this->generateSpintax($request->all(), $request->article);
	}

	private function generateSpintaxParagraph(array $request, $paragraph)
	{
		return $this->generateSpintax($request, $paragraph);
	}

	public function generateRespintax()
	{
		return $this->generateSpintaxParagraph(request()->all(), request('paragraph'));
	}

	public function postSpinTax()
	{
		// dd(request()->all());

		$result = auth()->user()->words()->create(request()->all());

		return response()->json($result);
	}

	private function api($text, $quality, $email, $pass, $protected, $synonyms, $nonested, $sentence, $paragraph, $title, $nooriginal)
	{
		if (isset($text) && isset($quality) && isset($email) && isset($pass))
		{
			$text = urlencode($text);
			$ch = curl_init('http://wordai.com/users/turing-api.php');

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, "s=$text&quality=$quality&email=$email&pass=$pass&output=json&protected=$protected&synonyms=$synonyms");
			
			$result = curl_exec($ch);
			curl_close ($ch);

			return $result;
		}
		else
		{
			return 'Error: Not All Variables Set!';
		}
	}

	public function article()
	{
		return view('words.article');
	}

	public function getRawArticles()
	{
		$authUser = auth()->user();

		$raw = Word::where('user_id', $authUser->id)->get();

		return response()->json($raw);
	}

	public function generateArticle()
	{
		$article = request('spintax');
		$result = $this->spin->process($article);

		auth()->user()->articles()->create(['article' => $result]);

		return response()->json($result);
	}
}
