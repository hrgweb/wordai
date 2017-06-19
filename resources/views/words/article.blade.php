@extends('layouts.admin')

@section ('content')
	<generate-article token="{{ csrf_token() }}"></generate-article>
@endsection
